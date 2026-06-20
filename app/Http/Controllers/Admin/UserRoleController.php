<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRoleController extends Controller
{
    private const PRIVILEGED_ROLES = ['superadmin', 'admin'];

    private const ROLES = [
        'superadmin' => 'Super Admin',
        'admin' => 'Admin Staff',
        'tenant' => 'Doctor Tenant',
        'doctor' => 'Doctor',
        'patient' => 'Patient',
        'user' => 'User',
    ];

    public function index(Request $request)
    {
        $query = User::query()->with('tenant');

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roleCounts = User::query()
            ->selectRaw('role, count(*) as aggregate')
            ->groupBy('role')
            ->pluck('aggregate', 'role');

        return view('admin.users.index', [
            'users' => $users,
            'roles' => self::ROLES,
            'roleCounts' => $roleCounts,
            'filters' => $request->only(['q', 'role', 'status']),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => self::ROLES,
            'tenants' => $this->tenantOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'mobile' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'tenant_id' => ['nullable', 'string', Rule::exists('tenants', 'id')],
            'status' => ['nullable', 'boolean'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'] ?? null,
            'role' => $validated['role'],
            'tenant_id' => $validated['tenant_id'] ?? null,
            'status' => (int) ($validated['status'] ?? 1),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'managedUser' => $user,
            'roles' => self::ROLES,
            'tenants' => $this->tenantOptions(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'tenant_id' => ['nullable', 'string', Rule::exists('tenants', 'id')],
            'status' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $newStatus = (int) ($validated['status'] ?? 0);
        $guardMessage = $this->guardPrivilegedChange($request, $user, $validated['role'], $newStatus);
        if ($guardMessage) {
            return back()->withInput()->with('error', $guardMessage);
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'] ?? null,
            'role' => $validated['role'],
            'tenant_id' => $validated['tenant_id'] ?? null,
            'status' => $newStatus,
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function status(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $newStatus = (int) $validated['status'];
        $guardMessage = $this->guardPrivilegedChange($request, $user, $user->role, $newStatus);
        if ($guardMessage) {
            return back()->with('error', $guardMessage);
        }

        $user->update(['status' => $newStatus]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($this->isActivePrivilegedUser($user) && $this->activePrivilegedUserCount() <= 1) {
            return back()->with('error', 'You cannot delete the last active privileged admin.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    private function guardPrivilegedChange(Request $request, User $user, string $newRole, int $newStatus): ?string
    {
        $removesPrivilege = $this->isPrivilegedRole($user->role)
            && (!$this->isPrivilegedRole($newRole) || $newStatus !== 1);

        if ($user->is($request->user()) && $removesPrivilege) {
            return 'You cannot remove your own superadmin access.';
        }

        if ($this->isActivePrivilegedUser($user) && $removesPrivilege && $this->activePrivilegedUserCount() <= 1) {
            return 'You cannot remove access from the last active privileged admin.';
        }

        return null;
    }

    private function isPrivilegedRole(?string $role): bool
    {
        return in_array($role, self::PRIVILEGED_ROLES, true);
    }

    private function isActivePrivilegedUser(User $user): bool
    {
        return $this->isPrivilegedRole($user->role) && (int) $user->status === 1;
    }

    private function activePrivilegedUserCount(): int
    {
        return User::query()
            ->whereIn('role', self::PRIVILEGED_ROLES)
            ->where('status', 1)
            ->count();
    }

    private function tenantOptions()
    {
        return Tenant::query()
            ->latest()
            ->get(['id', 'data']);
    }
}

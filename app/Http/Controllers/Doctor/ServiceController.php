<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\ServiceRequest;
use App\Models\DoctorService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $doctor = User::first();
        $services = DoctorService::get();
        //$services = fn($q) => $q->orderByDesc('order_column');
        //dd($services);

        return view('service', compact('services', 'doctor'));
    }

    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['order_column'] = (DoctorService::where('user_id', $request->user()->id)->max('order_column') ?? 0) + 10;

        DoctorService::create($data);
        return back()->with('ok', 'Service added.');
    }

    public function update(ServiceRequest $request, DoctorService $service)
    {
        $this->authorizeOwner($request, $service);
        $service->update($request->validated());
        return back()->with('ok', 'Service updated.');
    }

    public function destroy(Request $request, DoctorService $service)
    {
        $this->authorizeOwner($request, $service);
        $service->delete();
        return back()->with('ok', 'Service removed.');
    }

    /** Bulk upsert all rows from one form submit */
    public function bulkUpsert(Request $request)
    {
        $user = $request->user();

        $payload = $request->validate([
            'items'              => 'required|array',
            'items.*.id'         => 'nullable|integer',
            'items.*.title'      => 'required|string|max:255',
            'items.*.description'=> 'nullable|string',
            'items.*.icon'       => 'nullable|string|max:255',
            'items.*.badge'      => 'nullable|string|max:100',
            'items.*.features'   => 'nullable|array',
            'items.*.features.*' => 'nullable|string|max:255',
            'items.*.order'      => 'nullable|integer',
        ]);

        DB::connection('tenant')->transaction(function () use ($payload, $user) {
            $seen = [];
            foreach ($payload['items'] as $i => $row) {
                $attrs = [
                    'user_id'      => $user->id,
                    'title'        => $row['title'],
                    'description'  => $row['description'] ?? null,
                    'icon'         => $row['icon'] ?? null,
                    'badge'        => $row['badge'] ?? null,
                    'features'     => $row['features'] ?? [],
                    'order_column' => $row['order'] ?? (($i+1)*10),
                ];

                if (!empty($row['id'])) {
                    $model = DoctorService::where('user_id', $user->id)->find($row['id']);
                    if ($model) { $model->update($attrs); $seen[] = $model->id; continue; }
                }
                $new = DoctorService::create($attrs);
                $seen[] = $new->id;
            }

            // Optionally delete items that were removed from the UI
            DoctorService::where('user_id', $user->id)
                ->whereNotIn('id', $seen)->delete();
        });

        return back()->with('ok', 'Services updated.');
    }

    /** Simple drag-sort endpoint */
    public function reorder(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(['order' => 'required|array']);
        foreach ($data['order'] as $pos => $id) {
            DoctorService::where('user_id', $user->id)->where('id', $id)->update(['order_column' => ($pos+1)*10]);
        }
        return response()->json(['ok' => true]);
    }

    protected function authorizeOwner(Request $request, DoctorService $service): void
    {
        abort_unless($service->user_id === $request->user()->id, 403);
    }
}

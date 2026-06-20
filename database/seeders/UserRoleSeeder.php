<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_USER_PASSWORD', 'password');

        $users = [
            [
                'name' => env('SEED_SUPERADMIN_NAME', 'Super Admin'),
                'email' => env('SEED_SUPERADMIN_EMAIL', 'superadmin@doctorsprofile.xyz'),
                'mobile' => env('SEED_SUPERADMIN_MOBILE', '01700000001'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin Staff',
                'email' => 'admin@doctorsprofile.xyz',
                'mobile' => '01700000002',
                'role' => 'admin',
            ],
            [
                'name' => 'Tenant Doctor',
                'email' => 'tenant@doctorsprofile.xyz',
                'mobile' => '01700000003',
                'role' => 'tenant',
            ],
            [
                'name' => 'Doctor User',
                'email' => 'doctor@doctorsprofile.xyz',
                'mobile' => '01700000004',
                'role' => 'doctor',
            ],
            [
                'name' => 'Patient User',
                'email' => 'patient@doctorsprofile.xyz',
                'mobile' => '01700000005',
                'role' => 'patient',
            ],
            [
                'name' => 'General User',
                'email' => 'user@doctorsprofile.xyz',
                'mobile' => '01700000006',
                'role' => 'user',
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'mobile' => '01700000007',
                'role' => 'user',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);

            $user->fill([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'role' => $data['role'],
                'status' => 1,
            ]);

            if (!$user->exists || blank($user->password)) {
                $user->password = Hash::make($password);
            }

            $user->save();
        }
    }
}

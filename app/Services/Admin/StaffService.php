<?php

namespace App\Services\Admin;

use App\Models\Admin\Staff;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    public function createStaff(array $data, ?UploadedFile $image): Staff
    {
        return DB::transaction(function () use ($data, $image) {
            $imagePath = null;
            if ($image) {
                $imagePath = saveImagePath($image, 'staff');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']),
                'image' => $imagePath,
                'role' => 4, // Default role logic from controller
            ]);

            $staffData = [
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'image' => $imagePath,
                'password' => Hash::make($data['password']),
                'salary' => $data['salary'],
                'nid_no' => $data['nid_no'] ?? null,
                'join_date' => $data['join_date'] ?? date('Y-m-d'),
                'status' => $data['status'] ?? 0,
            ];

            $staff = Staff::create($staffData);

            $user->syncRoles($data['role']);

            return $staff;
        });
    }

    public function updateStaff(Staff $staff, array $data, ?UploadedFile $image): bool
    {
        return DB::transaction(function () use ($staff, $data, $image) {
            $user = User::find($staff->user_id);

            $imagePath = $staff->image;
            if ($image) {
                $imagePath = saveImagePath($image, $staff->image, 'staff');
            }

            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'image' => $imagePath,
            ];

            $staffData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'image' => $imagePath,
                'salary' => $data['salary'],
                'nid_no' => $data['nid_no'] ?? null,
                'join_date' => $data['join_date'] ?? date('Y-m-d'),
                'status' => $data['status'] ?? 0,
            ];

            if (!empty($data['password'])) {
                $passwordHash = Hash::make($data['password']);
                $userData['password'] = $passwordHash;
                $staffData['password'] = $passwordHash;
            }

            if ($user) {
                $user->update($userData);
                $user->syncRoles($data['role']);
            }

            return $staff->update($staffData);
        });
    }

    public function deleteStaff(int $id): bool
    {
        $staff = Staff::findOrFail($id);
        if ($staff->image != null && file_exists($staff->image)) {
            unlink($staff->image);
        }
        if ($staff->user_id) {
             User::destroy($staff->user_id);
        }
        return $staff->delete();
    }

    public function changeStatus(int $id): bool
    {
        $staff = Staff::findOrFail($id);
        $status = $staff->status == 1 ? 0 : 1;
        return $staff->update(['status' => $status]);
    }
}

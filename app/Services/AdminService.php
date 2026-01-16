<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    public function updateProfile(User $user, array $data, ?UploadedFile $image): bool
    {
        $imagePath = $image ? saveImagePath($image, $user->image, 'user-profile') : $user->image;

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'image'     => $imagePath,
            'phone'     => $data['phone'] ?? $user->phone,
            'address'   => $data['address'] ?? $user->address,
        ];

        return $user->update($updateData);
    }

    public function changePassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function assignRole(User $user, string $role): void
    {
        if ($user->getRoleNames()->isNotEmpty()){
            $user->syncRoles($role);
        } else {
            $user->assignRole($role);
        }
    }
}

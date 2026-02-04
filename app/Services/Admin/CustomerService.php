<?php

namespace App\Services\Admin;

use App\Models\Admin\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(array $data): Customer
    {
        // Create user with phone as password
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['phone']), // Password is phone number
        ]);

        // Handle image upload if exists
        if (isset($data['image'])) {
            $data['image'] = saveImagePath($data['image'], 'customer');
        }

        // Set user_id
        $data['user_id'] = $user->id;

        // Remove password field if exists
        unset($data['password'], $data['password_confirmation']);

        return Customer::create($data);
    }

    public function update(Customer $customer, array $data): bool
    {
        // Update user information
        if ($customer->user_id) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
            ];

            // Update password to phone number if phone changed
            if (isset($data['phone']) && $data['phone'] !== $customer->phone) {
                $userData['password'] = Hash::make($data['phone']);
            }

            $customer->user->update($userData);
        }

        // Handle image upload if exists
        if (isset($data['image'])) {
            $data['image'] = saveImagePath($data['image'], $customer->image ?? null, 'customer');
        }

        // Remove password fields if exists
        unset($data['password'], $data['password_confirmation']);

        return $customer->update($data);
    }

    public function destroy(Customer $customer): bool
    {
        // Record deletion
        \App\Models\Admin\DeletedCustomer::create([
            'name' => $customer->user->name ?? 'Unknown',
            'phone' => $customer->phone,
            'email' => $customer->user->email ?? 'N/A',
        ]);

        // Delete image if exists
        if ($customer->image && file_exists($customer->image)) {
            unlink($customer->image);
        }

        // Delete associated user if exists
        if ($customer->user_id) {
            $customer->user->delete();
        }

        return $customer->delete();
    }

    public function changeStatus(Customer $customer): bool
    {
        return $customer->update([
            'status' => !$customer->status
        ]);
    }
}


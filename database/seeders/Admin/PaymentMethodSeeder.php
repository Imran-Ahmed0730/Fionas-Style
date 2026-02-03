<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'icon' => null,
                'sandbox' => 0,
                'status' => 1,
            ],
            [
                'name' => 'Bank Transfer',
                'icon' => null,
                'sandbox' => 0,
                'status' => 1,
            ],
            [
                'name' => 'Mobile Banking',
                'icon' => null,
                'sandbox' => 0,
                'status' => 1,
            ],
            [
                'name' => 'Credit/Debit Card',
                'icon' => null,
                'sandbox' => 0,
                'status' => 1,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}

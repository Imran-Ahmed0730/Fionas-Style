<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\AccountHead;

class AccountHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heads = [
            [
                'title' => 'Customer Order',
                'type' => 1,
                'editable' => 0,
            ],
            [
                'title' => 'Product Purchase',
                'type' => 2,
                'editable' => 1,
            ],
            [
                'title' => 'Order Cancellation',
                'type' => 2,
                'editable' => 1,
            ],
            [
                'title' => 'Product Refund',
                'type' => 2,
                'editable' => 1,
            ],
            [
                'title' => 'Transport',
                'type' => 2,
                'editable' => 1,
            ],
            [
                'title' => 'Error Handling Expense',
                'type' => 2,
                'editable' => 0,
            ],
            [
                'title' => 'Error Handling Income',
                'type' => 1,
                'editable' => 0,
            ],
        ];

        foreach ($heads as $head) {
            AccountHead::updateOrCreate(
                ['title' => $head['title'], 'type' => $head['type']],
                ['editable' => $head['editable'], 'status' => 1]
            );
        }
    }
}

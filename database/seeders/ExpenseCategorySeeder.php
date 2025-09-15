<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Office Supplies', 'description' => 'Expenses for office stationery and supplies', 'user_id' => 3],
            ['name' => 'Utilities', 'description' => 'Electricity, water, internet bills, etc.', 'user_id' => 3],
            ['name' => 'Rent', 'description' => 'Monthly rent for office or shop', 'user_id' => 3],
            ['name' => 'Travel', 'description' => 'Travel expenses for business purposes', 'user_id' => 3],
            ['name' => 'Salary', 'description' => 'Payments to employees and staff', 'user_id' => 3],
            ['name' => 'Maintenance', 'description' => 'Repairs and maintenance costs', 'user_id' => 3],
            ['name' => 'Marketing', 'description' => 'Advertising and promotional expenses', 'user_id' => 3],
            ['name' => 'Miscellaneous', 'description' => 'Other miscellaneous expenses', 'user_id' => 3],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}

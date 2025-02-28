<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use App\Models\DailyRecord;
use App\Models\Organization;
use App\Models\DailyRecordProduct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $account = Account::create(['name' => 'King Baker Production']);

        $branch1 = Branch::create([
            'code' => 'KB001',
            'name' => 'King Baker Turntable',
            'address' => 'Brusubi Turntable',
            'phone' => '7654321',
        ]);

        $branch2 = Branch::create([
            'code' => 'KB002',
            'name' => 'King Baker Traffic Light',
            'address' => 'Traffic Light',
            'phone' => '7654322',
        ]);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Isatou',
            'last_name' => 'Demba',
            'email' => 'isatou@kingbakers.net',
            'password' => 'secret',
            'phone' => '3040993',
            'address' => 'Bakau',
            'role' => 'admin',
        ]);

        User::factory(5)->create(['account_id' => $account->id]);

        $category1 = Category::create(
            [
                'name' => 'Bread',
                'description' => 'Bread products',
            ]
        );

        $category2 = Category::create(
            [
                'name' => 'Pastry',
                'description' => 'Pastry products',
            ]
        );

        $category3 = Category::create(
            [
                'name' => 'Cake',
                'description' => 'Cake products',
            ]
        );

        $product1 = Product::create(
            [
                'name' => 'Bread',
                'code' => 'BRD001',
                'description' => 'Bread products',
                'cost_price' => 5,
                'selling_price' => 10,
                'quantity' => 100,
                'category_id' => $category1->id,
                'branch_id' => $branch1->id,
            ]
        );

        $product2 = Product::create(
            [
                'name' => 'Pastry',
                'code' => 'PST001',
                'description' => 'Pastry products',
                'cost_price' => 10,
                'selling_price' => 20,
                'quantity' => 50,
                'category_id' => $category2->id,
                'branch_id' => $branch1->id,
            ]
        );

        $product3 = Product::create(
            [
                'name' => 'Cake',
                'code' => 'CK001',
                'description' => 'Cake products',
                'cost_price' => 20,
                'selling_price' => 40,
                'quantity' => 20,
                'category_id' => $category3->id,
                'branch_id' => $branch2->id,
            ]
        );

        // Create a daily record with multiple products
        $dailyRecord = DailyRecord::create([
            'record_date' => now(),
            'number_of_products' => 3, // We'll add 3 products
            'total_revenue' => 0, // Will be calculated after adding products
        ]);

        // Add products to the daily record
        DailyRecordProduct::create([
            'daily_record_id' => $dailyRecord->id,
            'product_id' => $product1->id,
            'quantity' => 10,
            'revenue' => 10 * $product1->selling_price, // 100
        ]);

        DailyRecordProduct::create([
            'daily_record_id' => $dailyRecord->id,
            'product_id' => $product2->id,
            'quantity' => 5,
            'revenue' => 5 * $product2->selling_price, // 100
        ]);

        DailyRecordProduct::create([
            'daily_record_id' => $dailyRecord->id,
            'product_id' => $product3->id,
            'quantity' => 3,
            'revenue' => 3 * $product3->selling_price, // 120
        ]);

        // Calculate the total revenue
        $dailyRecord->calculateTotalRevenue();

        // $organizations = Organization::factory(100)
        //     ->create(['account_id' => $account->id]);

        // Contact::factory(100)
        //     ->create(['account_id' => $account->id])
        //     ->each(function ($contact) use ($organizations) {
        //         $contact->update(['organization_id' => $organizations->random()->id]);
        //     });
    }
}

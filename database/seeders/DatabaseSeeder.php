<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Default "Guest" Customer
        // This ensures the CheckoutController always has a customer to link orders to.
        Customer::create([
            'FirstName' => 'Guest',
            'LastName'  => 'Customer',
            'Email'     => 'guest@quickmart.com',
            'Phone'     => '0000000000',
            'Address'   => 'In-Store Walk-in',
            'CreatedAt' => now(),
        ]);

        // 2. Create Categories
        $beverages = Category::create([
            'CategoryName' => 'Beverages',
            'Description'  => 'Cold drinks, juices, and water.',
        ]);

        $snacks = Category::create([
            'CategoryName' => 'Snacks',
            'Description'  => 'Chips, biscuits, and chocolates.',
        ]);

        $essentials = Category::create([
            'CategoryName' => 'Daily Essentials',
            'Description'  => 'Hygiene products and daily needs.',
        ]);

        // 3. Create Products linked to Categories
        
        // Beverages
        Product::create([
            'CategoryID'    => $beverages->CategoryID,
            'Name'          => 'Mineral Water (500ml)',
            'Description'   => 'Purified drinking water.',
            'Price'         => 15.00,
            'StockQuantity' => 100,
        ]);

        Product::create([
            'CategoryID'    => $beverages->CategoryID,
            'Name'          => 'Cola Can (330ml)',
            'Description'   => 'Refreshing carbonated soft drink.',
            'Price'         => 35.00,
            'StockQuantity' => 50,
        ]);

        Product::create([
            'CategoryID'    => $beverages->CategoryID,
            'Name'          => 'Orange Juice (1L)',
            'Description'   => 'Freshly squeezed orange juice.',
            'Price'         => 85.00,
            'StockQuantity' => 30,
        ]);

        // Snacks
        Product::create([
            'CategoryID'    => $snacks->CategoryID,
            'Name'          => 'Potato Chips (BBQ)',
            'Description'   => 'Crispy barbecue flavored chips.',
            'Price'         => 45.00,
            'StockQuantity' => 60,
        ]);

        Product::create([
            'CategoryID'    => $snacks->CategoryID,
            'Name'          => 'Chocolate Bar',
            'Description'   => 'Milk chocolate with almonds.',
            'Price'         => 50.00,
            'StockQuantity' => 80,
        ]);

        // Essentials
        Product::create([
            'CategoryID'    => $essentials->CategoryID,
            'Name'          => 'Hand Sanitizer',
            'Description'   => '70% Alcohol solution.',
            'Price'         => 60.00,
            'StockQuantity' => 200,
        ]);
        
        Product::create([
            'CategoryID'    => $essentials->CategoryID,
            'Name'          => 'Face Mask (Pack of 5)',
            'Description'   => 'Disposable 3-ply surgical masks.',
            'Price'         => 25.00,
            'StockQuantity' => 150,
        ]);
    }
}

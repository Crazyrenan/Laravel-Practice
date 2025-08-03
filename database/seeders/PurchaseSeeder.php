<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Define example products
        $products = [
            ['name' => 'Excelso Java Arabica', 'code' => 'EX-JA-001'],
            ['name' => 'Excelso Toraja', 'code' => 'EX-TJ-002'],
            ['name' => 'Excelso House Blend', 'code' => 'EX-HB-003'],
            ['name' => 'Excelso Robusta', 'code' => 'EX-RB-004'],
            ['name' => 'Excelso Mandheling', 'code' => 'EX-MD-005'],
        ];

        for ($i = 0; $i < 20; $i++) {
            $product = $faker->randomElement($products);
            $quantity = $faker->numberBetween(1, 5);
            $unitPrice = $faker->randomFloat(2, 50000, 100000);
            $totalPrice = $quantity * $unitPrice;
            $purchaseDate = $faker->dateTimeBetween('-2 years', 'now');
            $year = $purchaseDate->format('Y');

            DB::table('purchases')->insert([
                'customer_id' => $faker->numberBetween(1, 5), // make sure these customer IDs exist!
                'product_name' => $product['name'],
                'product_code' => $product['code'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'purchase_date' => $purchaseDate->format('Y-m-d'),
                'year' => $year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

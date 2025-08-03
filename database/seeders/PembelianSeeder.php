<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembelian;
use Faker\Factory as Faker;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        Pembelian::truncate();

        $faker = Faker::create();

        $items = [
            'Equipment' => ['Excavator', 'Bulldozer', 'Dump Truck', 'Drilling Machine', 'Wheel Loader', 'Conveyor Belt'],
            'Fuel' => ['Diesel Fuel', 'Gasoline', 'Hydraulic Oil', 'Gear Oil', 'Industrial Lubricant'],
            'Spare Part' => ['Hydraulic Hose', 'Engine Filter', 'Brake Pad', 'Gearbox Assembly', 'Cooling Fan'],
            'Office Supplies' => ['Printer Paper', 'Ballpoint Pen', 'Toner Cartridge', 'Whiteboard Marker', 'Filing Cabinet'],
        ];


        foreach (range(1, 50) as $index) {
            $category = $faker->randomElement(array_keys($items));
            $item_name = $faker->randomElement($items[$category]);
            $quantity = $faker->numberBetween(1, 500);
            $unit_price = $faker->randomFloat(2, 100, 10000);
            $buying_price = $faker->randomFloat(2, $unit_price * 0.5, $unit_price * 0.8);
            $total_price = $quantity * $unit_price;
            $tax = $total_price * 0.11;
            $grand_total = $total_price + $tax;

            $purchasedate = $faker->dateTimeBetween('2024-01-01 00:00:00', '2024-12-31 00:00:00');
        
            Pembelian::create([
                'vendor_id' => $faker->numberBetween(1, 10),
                'project_id' => $faker->numberBetween(1, 5),
                'requested_by' => $faker->numberBetween(1, 10),
                'purchase_order_number' => strtoupper('PO-' . $faker->unique()->bothify('####??')),
                'item_name' => $item_name,
                'item_code' => strtoupper($faker->unique()->bothify('ITEM###')),
                'category' => $category,
                'quantity' => $quantity,
                'unit' => $faker->randomElement(['pcs', 'kg', 'ltr', 'box']),
                'buy_price' => $buying_price,
                'unit_price' => $unit_price,
                'total_price' => $total_price,
                'tax' => $tax,
                'grand_total' => $grand_total,
                'purchase_date' => $purchasedate,
                'expected_delivery_date' => $faker->dateTimeBetween('+1 days', '+30 days'),
                'status' => $faker->randomElement(['Pending', 'Approved', 'Delivered']),
                'remarks' => $faker->optional()->sentence(),
                
                //ini timestamp
                'created_at' => $purchasedate,
                'updated_at' => now(),

            ]);

        }
    }
}

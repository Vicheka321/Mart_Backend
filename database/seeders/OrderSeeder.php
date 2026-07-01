<?php

namespace Database\Seeders;

use App\Models\OrderModel;
use App\Models\Order_itemModel;
use App\Models\PaymentModel;
use App\Models\ProductsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'khan Chamkar Mon',
            'khan Daun Penh',
            'khan 7 Makara',
            'khan Tuol Kork',
            'khan Sen Sok',
            'khan Russey Keo',
            'khan Mean Chey',
            'khan Chbar Ampov',
            'khan Por Sen Chey',
            'khan Prek Pnov',
        ];

        $communes = [
            'sangkat Boeng Keng Kang I',
            'sangkat Boeng Keng Kang II',
            'sangkat Olympic',
            'sangkat Tonle Bassac',
            'sangkat Teuk Thla',
            'sangkat Nirouth',
            'sangkat Choam Chao I',
            'sangkat Choam Chao II',
            'sangkat Veal Sbov',
        ];

        $streets = [
            'Street 51',
            'Street 63',
            'Street 95',
            'Street 110',
            'Street 182',
            'Street 214',
            'Street 271',
            'Russian Blvd',
            'Norodom Blvd',
            'Monivong Blvd',
        ];



        $customers = User::role('Customer')->get();

        $products = ProductsModel::all();

        if ($customers->isEmpty() || $products->isEmpty()) {

            $this->command->warn('Customer or Product not found.');

            return;
        }


        for ($i = 1; $i <= 500; $i++) {
            $customer = $customers->random();
            $createdAt = Carbon::now()
                ->subDays(rand(0, 365))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $house = rand(1, 999);

            $address =
           
                $streets[array_rand($streets)] . ", " .
                $communes[array_rand($communes)] . ", " .
                $districts[array_rand($districts)] . ", Phnom Penh";


            $status = fake()->randomElement([
                // 'pending',
                // 'processing',
                'completed',
                // 'cancelled',
            ]);

            $paymentMethod = fake()->randomElement([
                'cash',
                'khqr',
            ]);


            $order = OrderModel::create([

                'user_id' => $customer->id,

                'delivery_address' => $address,

                'lat' => fake()->latitude(11.45, 11.68),

                'lng' => fake()->longitude(104.80, 104.98),

                'status' => $status,

                'payment_method' => $paymentMethod,

                'total_amount' => 0,

                'created_at' => $createdAt,

                'updated_at' => $createdAt,

            ]);

            $totalAmount = 0;

            $itemCount = rand(1, 5);

            $orderProducts = $products->random(min($itemCount, $products->count()));

            foreach ($orderProducts as $product) {

                $qty = rand(1, 4);

                $price = $product->sale_price;

                $lineTotal = $qty * $price;

                $totalAmount += $lineTotal;

                Order_itemModel::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'qty'        => $qty,
                    'price'      => $price,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
            $order->update([
                'total_amount' => $totalAmount,
            ]);
            PaymentModel::create([
                'order_id'       => $order->id,
                'amount'         => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'transaction_id' => 'TXN' . strtoupper(fake()->bothify('########')),
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);
        }
    }
}

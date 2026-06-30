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
            'Chamkar Mon',
            'Daun Penh',
            '7 Makara',
            'Tuol Kork',
            'Sen Sok',
            'Russey Keo',
            'Mean Chey',
            'Chbar Ampov',
            'Por Sen Chey',
            'Prek Pnov',
        ];

        $communes = [
            'Boeng Keng Kang I',
            'Boeng Keng Kang II',
            'Boeng Keng Kang III',
            'Tonle Bassac',
            'Olympic',
            'Phsar Thmey I',
            'Phsar Thmey II',
            'Wat Phnom',
            'Boeng Kak I',
            'Boeng Kak II',
            'Teuk Thla',
            'Khmuonh',
            'Kakab I',
            'Kakab II',
            'Choam Chao I',
            'Choam Chao II',
            'Nirouth',
            'Veal Sbov',
            'Chbar Ampov I',
            'Chbar Ampov II',
        ];

        $streets = [
            'Street 51',
            'Street 63',
            'Street 95',
            'Street 110',
            'Street 182',
            'Street 214',
            'Street 271',
            'Street 278',
            'Street 294',
            'Street 310',
            'Street 360',
            'Street 432',
            'Street 598',
            'Russian Blvd',
            'Monivong Blvd',
            'Norodom Blvd',
            'Mao Tse Toung Blvd',
            'Hun Sen Blvd',
        ];

        $landmarks = [
            'Near AEON Mall',
            'Near Central Market',
            'Near Olympic Market',
            'Near Orussey Market',
            'Near Royal Palace',
            'Near Independence Monument',
            'Near NagaWorld',
            'Near Chip Mong Mall',
            'Near Eden Garden',
            'Near TK Avenue',
            'Near Airport',
            'Near Wat Phnom',
        ];

        $customers = User::role('Customer')->get();
        $products  = ProductsModel::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Customers or Products not found.');
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
                "House {$house}, "
                . $streets[array_rand($streets)]
                . ", "
                . $communes[array_rand($communes)]
                . ", "
                . $districts[array_rand($districts)]
                . ", Phnom Penh, "
                . $landmarks[array_rand($landmarks)];

            $status = fake()->randomElement([
                'pending',
                'pending',
                'processing',
                'processing',
                'completed',
                'completed',
                'completed',
                'cancelled',
            ]);

            $paymentMethod = fake()->randomElement([
                'cash',
                'cash',
                'khqr',

            ]);

            $order = OrderModel::create([
                'user_id'           => $customer->id,
                'delivery_address'  => $address,
                'lat'               => fake()->latitude(11.45, 11.68),
                'lng'               => fake()->longitude(104.80, 104.98),
                'status'            => $status,
                'payment_method'    => $paymentMethod,
                'total_amount'      => 0,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ]);

            // ── Order items ──────────────────────────────────────
            $itemCount  = rand(1, 5);
            $orderItems = $products->random(min($itemCount, $products->count()));
            $totalAmount = 0;

            foreach ($orderItems as $product) {
                $quantity  = rand(1, 4);
                $price     = $product->price ?? 0;
                $lineTotal = $price * $quantity;
                $totalAmount += $lineTotal;

                Order_itemModel::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'qty'   => $quantity,
                    'price'      => $price,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // ── Update order total ───────────────────────────────
            $order->update(['total_amount' => $totalAmount]);

            // ── Payment record ───────────────────────────────────
            PaymentModel::create([
                'order_id'       => $order->id,
                'amount'         => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => $status === 'cancelled'
                    ? 'cancelled'
                    : ($status === 'pending' ? 'pending' : 'paid'),
                'transaction_id' => strtoupper(fake()->bothify('TXN########')),
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);
        }

        $this->command->info('500 orders seeded successfully.');
    }
}

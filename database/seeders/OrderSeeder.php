<?php

namespace Database\Seeders;

use App\Models\OrderModel;
use App\Models\Order_itemModel;
use App\Models\PaymentModel;
use App\Models\User;
use App\Models\ProductsModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only users who have Customer role via Spatie
        $customers = User::role('Customer')->pluck('id');
        $products  = ProductsModel::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please seed customers and products first.');
            return;
        }

        // Create fake orders
        for ($i = 1; $i <= 500; $i++) {
            $customerId = $customers->random();

            // Random order date in last 2 years
            $createdAt = Carbon::now()->subDays(rand(1, 730));

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */
            $paymentMethod = fake()->randomElement([
                'cash',
                'aba',
                'wing',
                'khqr'
            ]);

            $status = fake()->randomElement([
                'pending',
                'processing',
                'completed',
                'cancelled'
            ]);

            $order = OrderModel::create([
                'user_id'           => $customerId,
                'delivery_address'  => fake()->address(),
                'lat'               => fake()->latitude(10.0, 14.8),
                'lng'               => fake()->longitude(102.3, 107.7),
                'status'            => $status,
                'total_amount'      => 0, // update later
                'payment_method'    => $paymentMethod,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Order Items
            |--------------------------------------------------------------------------
            */
            $itemCount = rand(1, 5);
            $subtotal  = 0;

            // Track used product ids so same product won't repeat in one order
            $usedProductIds = [];

            for ($j = 1; $j <= $itemCount; $j++) {

                $availableProducts = $products->whereNotIn('id', $usedProductIds);

                if ($availableProducts->isEmpty()) {
                    break;
                }

                $product = $availableProducts->random();
                $usedProductIds[] = $product->id;

                $qty   = rand(1, 5);
                $price = $product->sale_price ?? $product->price ?? rand(1, 100);

                Order_itemModel::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'qty'        => $qty,
                    'price'      => $price,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $subtotal += $price * $qty;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Order Total
            |--------------------------------------------------------------------------
            */
            $finalTotal = max(0, $subtotal);

            $order->update([
                'total_amount' => $finalTotal,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            | If cancelled => optional skip payment
            | If not cancelled => create payment
            */
            if ($order->status !== 'cancelled') {
                PaymentModel::create([
                    'order_id'       => $order->id,
                    'amount'         => $finalTotal,
                    'payment_method' => $paymentMethod,
                    'payment_status' => fake()->randomElement([
                        'paid',
                        'paid',
                        'paid',
                        'unpaid',
                    ]),
                    'transaction_id' => strtoupper(fake()->bothify('TXN######')),
                    'created_at'     => $createdAt,
                    'updated_at'     => $createdAt,
                ]);
            }
        }
    }
}
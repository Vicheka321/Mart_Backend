<?php

namespace Database\Seeders;

use App\Models\OrderModel;
use App\Models\Order_itemModel;
use App\Models\PaymentModel;
use App\Models\User;
use App\Models\ProductsModel;
use App\Models\AddressModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->pluck('id');
        $products  = ProductsModel::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please seed customers and products first.');
            return;
        }

        // Create 50,000 fake orders
        for ($i = 1; $i <= 500; $i++) {
            $customerId = $customers->random();

            // Random order date in last 2 years
            $createdAt = Carbon::now()->subDays(rand(1, 730));

            /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */
            // $address = AddressModel::create([
            //     'user_id'    => $customerId,
            //     'full_name'  => fake()->name(),
            //     'phone'      => '09' . rand(10000000, 99999999),
            //     'address'    => fake()->address(),
            //     'lat'        => fake()->latitude(10.0, 14.8),
            //     'lng'        => fake()->longitude(102.3, 107.7),
            //     'created_at' => $createdAt,
            //     'updated_at' => $createdAt,
            // ]);

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

            $order = OrderModel::create([
                'user_id'        => $customerId,
                // 'address_id'     => $address->id,
                'delivery_address' => fake()->address(),
                'lat'        => fake()->latitude(10.0, 14.8),
                'lng'        => fake()->longitude(102.3, 107.7),
                'status'         => fake()->randomElement([
                    // 'pending',
                    // 'processing',
                    'completed',
                    // 'cancelled'
                ]),
                'total_amount'   => 0, // update later
                'payment_method' => $paymentMethod,
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Order Items
        |--------------------------------------------------------------------------
        */
            $itemCount = rand(1, 5);
            $subtotal  = 0;

            // Keep track of products already added to this order
            $usedProductIds = [];

            for ($j = 1; $j <= $itemCount; $j++) {

                // Get products not already used in this order
                $availableProducts = $products->whereNotIn('id', $usedProductIds);

                // Stop if no products left
                if ($availableProducts->isEmpty()) {
                    break;
                }

                // Select a unique product
                $product = $availableProducts->random();

                // Remember this product ID
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
        | Payment (only for non-cancelled orders)
        |--------------------------------------------------------------------------
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
                       
                    ]),
                    'transaction_id' => strtoupper(fake()->bothify('TXN######')),
                    'created_at'     => $createdAt,
                    'updated_at'     => $createdAt,
                ]);
            }
        }
    }
}

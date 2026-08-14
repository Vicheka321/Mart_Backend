<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use App\Models\ProductsImageModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{

    public function run(): void
    {
        $productData = [
            [
                'name' => 'Coca Cola 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Classic Coca Cola soft drink in a 330ml can, offering the same crisp, fizzy, refreshing cola taste found in shops and restaurants across Cambodia. Widely stocked in supermarkets, convenience stores, and street-side stalls, it is a popular grab-and-go choice for hot days.',
                'cost_price' => 0.35,
                'sale_price' => 0.50,
                'images' => [
                    'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/b1.png',
                    'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p2b1c1.png',
                    'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p3b2c1.png',
                ],
                
            ],
            [
                'name' => 'Coca Cola Zero',
                'category' => 1,
                'brand' => 1,
                'description' => 'Zero sugar, zero calories, full Coca Cola taste in a 330ml can. A great option for health-conscious shoppers in Cambodia who still want the familiar cola flavor without the sugar.',
                'cost_price' => 0.38,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p2b1c1.png'
            ],
            [
                'name' => 'Pepsi 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Bold and refreshing Pepsi cola in a convenient 330ml can, a long-time favorite soft drink sold widely across Cambodian grocery stores and eateries.',
                'cost_price' => 0.32,
                'sale_price' => 0.48,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p3b2c1.png'
            ],
            [
                'name' => 'Pepsi Black',
                'category' => 1,
                'brand' => 2,
                'description' => 'A sugar-free Pepsi with a smooth, bold cola taste for a guilt-free refreshment, ideal for customers looking to cut back on sugar while enjoying their favorite cola brand.',
                'cost_price' => 0.35,
                'sale_price' => 0.52,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p4b2c1.png'
            ],
            [
                'name' => 'Eau Kulen 500ml',
                'category' => 1,
                'brand' => 3,
                'description' => 'Pure natural mineral water sourced from Kulen mountain, bottled in a convenient 500ml size. One of the most trusted and widely purchased local bottled water brands in Cambodia, ideal for daily hydration.',
                'cost_price' => 0.20,
                'sale_price' => 0.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p5b3c1.png'
            ],
            [
                'name' => 'Food Storage Container',
                'category' => 2,
                'brand' => 11,
                'description' => 'Durable, airtight food storage container ideal for keeping leftovers, rice, and pantry items fresh in Cambodia\'s humid climate. Stackable design saves space in small kitchens.',
                'cost_price' => 1.10,
                'sale_price' => 1.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p6.png'
            ],
            [
                'name' => 'Kitchen Storage Box',
                'category' => 2,
                'brand' => 11,
                'description' => 'Sturdy stackable storage box designed to keep dry goods, spices, and kitchen essentials organized and protected from insects and moisture.',
                'cost_price' => 1.25,
                'sale_price' => 2.10,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p7.png'
            ],
            [
                'name' => 'Plastic Food Wrap',
                'category' => 2,
                'brand' => 11,
                'description' => 'Clingy, easy-to-tear plastic wrap that keeps food fresh and protected in the fridge or when carrying meals on the go, a household staple in Cambodian kitchens.',
                'cost_price' => 0.65,
                'sale_price' => 1.10,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p8.png'
            ],
            [
                'name' => 'Food Storage Bag',
                'category' => 2,
                'brand' => 11,
                'description' => 'Resealable food storage bags great for meal prepping, freezing, and organizing snacks or leftovers, commonly used by households and small food vendors alike.',
                'cost_price' => 0.55,
                'sale_price' => 0.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p9.png'
            ],
            [
                'name' => 'Lunch Box',
                'category' => 2,
                'brand' => 11,
                'description' => 'Compact and leak-resistant lunch box, perfect for carrying meals to work, school, or the local market, a practical everyday item for busy Cambodian families.',
                'cost_price' => 1.40,
                'sale_price' => 2.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p10.png'
            ],
            [
                'name' => 'Nestlé Nescafé Classic',
                'category' => 3,
                'brand' => 10,
                'description' => 'Rich and aromatic instant coffee that delivers a satisfying classic coffee experience, one of the best-selling instant coffee brands found in Cambodian homes and cafes.',
                'cost_price' => 1.80,
                'sale_price' => 2.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p11.png'
            ],
            [
                'name' => 'Nestlé Nescafé 3 in 1',
                'category' => 3,
                'brand' => 10,
                'description' => 'Convenient instant coffee mix with coffee, creamer, and sugar in every sachet, a fast and popular breakfast choice for Cambodian workers and students on the move.',
                'cost_price' => 1.20,
                'sale_price' => 1.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p12.png'
            ],
            [
                'name' => 'Nestlé Milo',
                'category' => 3,
                'brand' => 10,
                'description' => 'Malty chocolate energy drink powder loved by kids and adults alike, a household favorite for breakfast and after-school energy across Cambodia.',
                'cost_price' => 2.10,
                'sale_price' => 3.25,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p13.png'
            ],
            [
                'name' => 'Nestlé Nestea Lemon Tea',
                'category' => 3,
                'brand' => 10,
                'description' => 'Refreshing instant lemon tea powder with a perfectly balanced sweet and tangy taste, popular as a cooling drink during Cambodia\'s hot season.',
                'cost_price' => 1.30,
                'sale_price' => 2.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p14.png'
            ],
            [
                'name' => 'Nestlé Coffee Mate',
                'category' => 3,
                'brand' => 10,
                'description' => 'Creamy non-dairy coffee creamer that adds smooth richness to any cup of coffee, a common pantry staple used alongside local iced coffee preparations.',
                'cost_price' => 1.45,
                'sale_price' => 2.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p15.png'
            ],
            [
                'name' => 'Nestlé Sweet Corn',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned sweet corn kernels, ready to use for salads, soups, and side dishes, offering a convenient way to add vegetables to everyday Cambodian home cooking.',
                'cost_price' => 0.85,
                'sale_price' => 1.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p16.png'
            ],
            [
                'name' => 'Nestlé Baked Beans',
                'category' => 4,
                'brand' => 10,
                'description' => 'Tender baked beans in a savory tomato sauce, a quick and easy pantry staple that pairs well with rice or bread for a fast meal.',
                'cost_price' => 0.90,
                'sale_price' => 1.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p17.png'
            ],
            [
                'name' => 'Nestlé Tuna',
                'category' => 4,
                'brand' => 10,
                'description' => 'Premium canned tuna packed in oil or brine, great for sandwiches and salads, and a convenient protein source for quick Cambodian family meals.',
                'cost_price' => 1.10,
                'sale_price' => 1.80,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p18.png'
            ],
            [
                'name' => 'Nestlé Sardines',
                'category' => 4,
                'brand' => 10,
                'description' => 'Flavorful canned sardines in tomato sauce, a protein-rich addition to any meal and a popular affordable staple in Cambodian households.',
                'cost_price' => 0.95,
                'sale_price' => 1.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p19.png'
            ],
            [
                'name' => 'Nestlé Coconut Milk',
                'category' => 4,
                'brand' => 10,
                'description' => 'Rich and creamy coconut milk, perfect for curries, desserts, and beverages, an essential ingredient in many traditional Khmer dishes and sweets.',
                'cost_price' => 0.75,
                'sale_price' => 1.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p20.png'
            ],
            [
                'name' => 'Buldak Original',
                'category' => 5,
                'brand' => 5,
                'description' => 'Spicy Korean fire noodles with the original hot chicken flavor everyone loves, a trending favorite among Cambodian youth for its intense heat and viral appeal.',
                'cost_price' => 0.70,
                'sale_price' => 1.15,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p21.png'
            ],
            [
                'name' => 'Buldak Carbonara',
                'category' => 5,
                'brand' => 5,
                'description' => 'Creamy carbonara-style spicy noodles combining fiery heat with a rich, savory sauce, a popular pick for fans of Korean-style instant noodles in Cambodia.',
                'cost_price' => 0.75,
                'sale_price' => 1.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p22.png'
            ],
            [
                'name' => 'Buldak Cheese',
                'category' => 5,
                'brand' => 5,
                'description' => 'Spicy fire noodles topped with a cheesy twist to balance out the heat, offering a milder alternative for those easing into the Buldak spice challenge.',
                'cost_price' => 0.75,
                'sale_price' => 1.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p23.png'
            ],
            [
                'name' => 'Koreno Chicken',
                'category' => 5,
                'brand' => 6,
                'description' => 'Savory chicken-flavored instant noodles with a satisfying, hearty broth, a quick and affordable meal option found in nearly every Cambodian grocery store.',
                'cost_price' => 0.30,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p24.png'
            ],
            [
                'name' => 'Koreno Seafood',
                'category' => 5,
                'brand' => 6,
                'description' => 'Flavorful seafood-flavored instant noodles with a light, savory broth, popular as a fast everyday meal across Cambodia.',
                'cost_price' => 0.30,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p25.png'
            ],
            [
                'name' => 'Mistine Baby Powder',
                'category' => 6,
                'brand' => 7,
                'description' => 'Gentle baby powder that keeps skin soft, dry, and comfortable throughout the day, well suited to Cambodia\'s hot and humid climate.',
                'cost_price' => 1.00,
                'sale_price' => 1.70,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p26.png'
            ],
            [
                'name' => 'Mistine Baby Lotion',
                'category' => 6,
                'brand' => 7,
                'description' => 'Mild, moisturizing lotion formulated to nourish and protect delicate baby skin, a trusted choice among Cambodian parents.',
                'cost_price' => 1.20,
                'sale_price' => 1.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p27.png'
            ],
            [
                'name' => 'Mistine Baby Shampoo',
                'category' => 6,
                'brand' => 7,
                'description' => 'Tear-free baby shampoo that gently cleanses hair and scalp without irritation, ideal for daily use on sensitive baby skin.',
                'cost_price' => 1.15,
                'sale_price' => 1.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p28.png'
            ],
            [
                'name' => 'Mistine Baby Soap',
                'category' => 6,
                'brand' => 7,
                'description' => 'Mild baby soap that cleanses gently while keeping baby skin soft and smooth, a popular budget-friendly choice for Cambodian households.',
                'cost_price' => 0.60,
                'sale_price' => 1.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p29.png'
            ],
            [
                'name' => 'Mistine Baby Wipes',
                'category' => 6,
                'brand' => 7,
                'description' => 'Soft and gentle baby wipes for quick, effective cleaning on sensitive skin, convenient for use at home or while out and about.',
                'cost_price' => 0.90,
                'sale_price' => 1.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p30.png'
            ],
            [
                'name' => 'Mistine Facial Foam',
                'category' => 7,
                'brand' => 7,
                'description' => 'Gentle foaming facial cleanser that removes dirt, oil, and impurities for fresh, clean skin, a top-selling Mistine item across Cambodian pharmacies and marts.',
                'cost_price' => 1.30,
                'sale_price' => 2.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p31.png'
            ],
            [
                'name' => 'Mistine Face Cream',
                'category' => 7,
                'brand' => 7,
                'description' => 'Hydrating face cream that nourishes and softens skin for a healthy glow, formulated to suit Cambodia\'s tropical climate.',
                'cost_price' => 1.50,
                'sale_price' => 2.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p32.png'
            ],
            [
                'name' => 'Mistine Serum',
                'category' => 7,
                'brand' => 7,
                'description' => 'Lightweight facial serum designed to brighten and revitalize skin from within, a popular addition to daily Cambodian skincare routines.',
                'cost_price' => 2.00,
                'sale_price' => 3.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p33.png'
            ],
            [
                'name' => 'Mistine Sunscreen',
                'category' => 7,
                'brand' => 7,
                'description' => 'Lightweight sunscreen that protects skin from harmful UV rays without feeling greasy, essential given Cambodia\'s strong year-round sun.',
                'cost_price' => 1.60,
                'sale_price' => 2.65,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p34.png'
            ],
            [
                'name' => 'Mistine Toner',
                'category' => 7,
                'brand' => 7,
                'description' => 'Refreshing facial toner that balances skin and preps it for the next skincare step, helping combat oiliness in Cambodia\'s humid weather.',
                'cost_price' => 1.25,
                'sale_price' => 2.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p35.png'
            ],
            [
                'name' => 'Julies Cheese Sandwich',
                'category' => 8,
                'brand' => 4,
                'description' => 'Crispy sandwich crackers filled with a rich and savory cheese cream, a well-known Malaysian-made snack popular with Cambodian schoolchildren.',
                'cost_price' => 0.45,
                'sale_price' => 0.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p36.png'
            ],
            [
                'name' => 'Julies Chocolate Sandwich',
                'category' => 8,
                'brand' => 4,
                'description' => 'Crunchy sandwich crackers filled with smooth, sweet chocolate cream, a favorite lunchbox treat found in convenience stores across Cambodia.',
                'cost_price' => 0.45,
                'sale_price' => 0.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p37.png'
            ],
            [
                'name' => 'Oishi Potato Chips',
                'category' => 8,
                'brand' => 9,
                'description' => 'Crispy, crunchy potato chips seasoned to perfection for a satisfying snack, a widely recognized Thai snack brand popular throughout Cambodia.',
                'cost_price' => 0.40,
                'sale_price' => 0.70,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p38.png'
            ],
            [
                'name' => 'Oishi Prawn Crackers',
                'category' => 8,
                'brand' => 9,
                'description' => 'Light and crunchy prawn crackers bursting with savory seafood flavor, a popular snack for both kids and adults.',
                'cost_price' => 0.35,
                'sale_price' => 0.60,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p39.png'
            ],
            [
                'name' => 'Oishi Seaweed Snack',
                'category' => 8,
                'brand' => 9,
                'description' => 'Crispy roasted seaweed snack with a savory, umami-rich taste, a trendy healthier snack option gaining popularity in Cambodian convenience stores.',
                'cost_price' => 0.40,
                'sale_price' => 0.68,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p40.png'
            ],
            [
                'name' => 'Ajinomoto MSG 100g',
                'category' => 9,
                'brand' => 8,
                'description' => 'Classic umami seasoning in a 100g pack that enhances the flavor of any dish, a kitchen essential found in nearly every Cambodian household and street food stall.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p41.png'
            ],
            [
                'name' => 'Ajinomoto MSG 250g',
                'category' => 9,
                'brand' => 8,
                'description' => 'Larger 250g pack of classic umami seasoning for everyday cooking, a cost-effective option for busy home kitchens and small restaurants.',
                'cost_price' => 1.10,
                'sale_price' => 1.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p42.png'
            ],
            [
                'name' => 'Ajinomoto Chicken Powder',
                'category' => 9,
                'brand' => 8,
                'description' => 'Savory chicken seasoning powder that adds depth and flavor to soups and dishes, widely used in Khmer home cooking for soups and stir-fries.',
                'cost_price' => 0.60,
                'sale_price' => 1.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p43.png'
            ],
            [
                'name' => 'Ajinomoto Soup Stock',
                'category' => 9,
                'brand' => 8,
                'description' => 'Ready-to-use soup stock seasoning that brings rich flavor to broths and soups, a convenient shortcut for making flavorful Khmer soups at home.',
                'cost_price' => 0.65,
                'sale_price' => 1.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p44.png'
            ],
            [
                'name' => 'Ajinomoto Umami Seasoning',
                'category' => 9,
                'brand' => 8,
                'description' => 'All-purpose umami seasoning that enhances the natural taste of your favorite meals, a versatile pantry staple used across Cambodian cuisine.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p45.png'
            ],
            [
                'name' => 'Mistine Body Wash',
                'category' => 10,
                'brand' => 7,
                'description' => 'Refreshing body wash that gently cleanses while leaving skin feeling soft and fresh, ideal for Cambodia\'s hot and humid climate.',
                'cost_price' => 1.35,
                'sale_price' => 2.25,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p46.png'
            ],
            [
                'name' => 'Mistine Body Lotion',
                'category' => 10,
                'brand' => 7,
                'description' => 'Moisturizing body lotion that hydrates and nourishes skin all day long, popular among Cambodian consumers for its lightweight, non-greasy feel.',
                'cost_price' => 1.40,
                'sale_price' => 2.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p47.png'
            ],
            [
                'name' => 'Mistine Shampoo',
                'category' => 10,
                'brand' => 7,
                'description' => 'Gentle shampoo that cleanses hair and scalp while leaving hair soft and manageable, a household name in Cambodian personal care aisles.',
                'cost_price' => 1.10,
                'sale_price' => 1.85,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p48.png'
            ],
            [
                'name' => 'Mistine Conditioner',
                'category' => 10,
                'brand' => 7,
                'description' => 'Nourishing conditioner that smooths and softens hair, leaving it easy to manage, often paired with Mistine shampoo for a full hair care routine.',
                'cost_price' => 1.15,
                'sale_price' => 1.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p49.png'
            ],
            [
                'name' => 'Mistine Hand Cream',
                'category' => 10,
                'brand' => 7,
                'description' => 'Rich hand cream that moisturizes and softens dry hands with lasting hydration, a compact and affordable personal care item.',
                'cost_price' => 0.85,
                'sale_price' => 1.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p50.png'
            ],

        ];

        foreach ($productData as $index => $item) {

            $createdAt = now()->subDays(rand(1, 730));

            $product = ProductsModel::create([
                'categories_id' => $item['category'],
                'brand_id'      => $item['brand'],
                'product_code'  => 'PRD' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'name'          => $item['name'],
                'description'   => $item['description'],
                'unit'          => 'piece',

                'cost_price'    => $item['cost_price'],
                'sale_price'    => $item['sale_price'],

                'quantity'      => 100,

                'status'        => true,
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);
            $images = $item['images']
                ?? [$item['image'] ?? null];

            foreach ($images as $image) {

                if (empty($image)) {
                    continue;
                }

                ProductsImageModel::create([
                    'product_id' => $product->id,
                    'image_url'  => $image,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}

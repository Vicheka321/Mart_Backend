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
            [
                'name' => 'Sprite 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Crisp lemon-lime soda with a clean, fizzy finish, a favorite mixer and standalone refresher sold in nearly every Cambodian mart and street stall.',
                'cost_price' => 0.33,
                'sale_price' => 0.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p51.png'
            ],
            [
                'name' => 'Fanta Orange 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Vibrant orange-flavored soda with a sweet, fruity fizz, a colorful and popular choice for kids and family gatherings across Cambodia.',
                'cost_price' => 0.33,
                'sale_price' => 0.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p52.png'
            ],
            [
                'name' => 'Mirinda Orange 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Bright, citrusy orange soda with a bold carbonated kick, widely available alongside Pepsi products in Cambodian convenience stores.',
                'cost_price' => 0.32,
                'sale_price' => 0.48,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p53.png'
            ],
            [
                'name' => '7 Up 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Light and refreshing lemon-lime soda with a crisp, clean taste, popular as a mixer for local drinks and a stand-alone cooler.',
                'cost_price' => 0.32,
                'sale_price' => 0.48,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p54.png'
            ],
            [
                'name' => 'Eau Kulen 1.5L',
                'category' => 1,
                'brand' => 3,
                'description' => 'Large-format natural mineral water bottle from Kulen mountain, ideal for families and offices needing a bigger daily hydration supply.',
                'cost_price' => 0.45,
                'sale_price' => 0.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p55.png'
            ],
            [
                'name' => 'Schweppes Soda Water 325ml',
                'category' => 1,
                'brand' => 3,
                'description' => 'Plain sparkling soda water with a clean, crisp bubble, commonly used as a mixer for local iced drinks and cocktails.',
                'cost_price' => 0.35,
                'sale_price' => 0.58,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p56.png'
            ],
            [
                'name' => 'Tiger Fresh Green Tea 500ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Lightly sweetened bottled green tea with a smooth, refreshing taste, a popular grab-and-go choice on hot Cambodian afternoons.',
                'cost_price' => 0.40,
                'sale_price' => 0.65,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p57.png'
            ],
            [
                'name' => 'Coca Cola 1.5L',
                'category' => 1,
                'brand' => 1,
                'description' => 'Family-size bottle of classic Coca Cola, perfect for sharing at gatherings, parties, and meals across Cambodian households.',
                'cost_price' => 0.85,
                'sale_price' => 1.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p58.png'
            ],
            [
                'name' => 'Pepsi 1.5L',
                'category' => 1,
                'brand' => 2,
                'description' => 'Large family-size bottle of Pepsi cola, offering the same bold taste for sharing at home or at restaurants.',
                'cost_price' => 0.80,
                'sale_price' => 1.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p59.png'
            ],
            [
                'name' => 'Eau Kulen 330ml',
                'category' => 1,
                'brand' => 3,
                'description' => 'Compact 330ml bottle of pure Kulen mountain mineral water, convenient for on-the-go hydration throughout the day.',
                'cost_price' => 0.15,
                'sale_price' => 0.28,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p60.png'
            ],
            [
                'name' => 'Vacuum Seal Bags',
                'category' => 2,
                'brand' => 11,
                'description' => 'Reusable vacuum-seal storage bags that lock in freshness for meats, vegetables, and leftovers, well suited to Cambodia\'s humid climate.',
                'cost_price' => 1.30,
                'sale_price' => 2.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p61.png'
            ],
            [
                'name' => 'Glass Storage Jar Set',
                'category' => 2,
                'brand' => 11,
                'description' => 'Set of airtight glass jars ideal for storing rice, sugar, and dried spices, keeping pantry staples fresh and pest-free.',
                'cost_price' => 2.10,
                'sale_price' => 3.60,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p62.png'
            ],
            [
                'name' => 'Silicone Food Cover Set',
                'category' => 2,
                'brand' => 11,
                'description' => 'Stretchable silicone lids that seal bowls and plates of any shape, a reusable alternative to plastic wrap for everyday kitchens.',
                'cost_price' => 1.45,
                'sale_price' => 2.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p63.png'
            ],
            [
                'name' => 'Bamboo Cutting Board',
                'category' => 2,
                'brand' => 11,
                'description' => 'Durable bamboo cutting board that resists knife marks and odors, a sturdy kitchen essential for home cooks.',
                'cost_price' => 2.30,
                'sale_price' => 3.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p64.png'
            ],
            [
                'name' => 'Non-Stick Frying Pan',
                'category' => 2,
                'brand' => 11,
                'description' => 'Lightweight non-stick frying pan for everyday cooking, well suited to preparing stir-fries and Khmer home dishes.',
                'cost_price' => 4.50,
                'sale_price' => 7.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p65.png'
            ],
            [
                'name' => 'Stainless Steel Cooking Pot',
                'category' => 2,
                'brand' => 11,
                'description' => 'Durable stainless steel pot for boiling soups and rice, a long-lasting addition to any Cambodian kitchen.',
                'cost_price' => 5.20,
                'sale_price' => 8.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p66.png'
            ],
            [
                'name' => 'Insulated Thermos Flask',
                'category' => 2,
                'brand' => 11,
                'description' => 'Vacuum-insulated flask that keeps drinks hot or cold for hours, handy for commuting or outdoor market visits.',
                'cost_price' => 2.80,
                'sale_price' => 4.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p67.png'
            ],
            [
                'name' => 'Dish Drying Rack',
                'category' => 2,
                'brand' => 11,
                'description' => 'Compact stainless steel drying rack that keeps dishes organized and dry, ideal for small Cambodian kitchens.',
                'cost_price' => 3.10,
                'sale_price' => 5.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p68.png'
            ],
            [
                'name' => 'Rice Storage Bin 10kg',
                'category' => 2,
                'brand' => 11,
                'description' => 'Large sealed rice storage bin that keeps rice fresh and free from insects, sized for typical household rice consumption.',
                'cost_price' => 3.60,
                'sale_price' => 6.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p69.png'
            ],
            [
                'name' => 'Kitchen Utensil Set',
                'category' => 2,
                'brand' => 11,
                'description' => 'Complete set of cooking utensils including spatula, ladle, and tongs, essential tools for everyday Khmer cooking.',
                'cost_price' => 2.90,
                'sale_price' => 4.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p70.png'
            ],

            // =======================
            [
                'name' => 'Nestlé Nescafé Gold',
                'category' => 3,
                'brand' => 10,
                'description' => 'Smooth, premium instant coffee with a rich aroma, a step-up choice for Cambodian coffee drinkers wanting extra quality.',
                'cost_price' => 2.60,
                'sale_price' => 3.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p71.png'
            ],
            [
                'name' => 'Nestlé Nescafé Decaf',
                'category' => 3,
                'brand' => 10,
                'description' => 'Full-flavored instant coffee without the caffeine, suited to evening drinkers or those cutting back on caffeine intake.',
                'cost_price' => 2.20,
                'sale_price' => 3.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p72.png'
            ],
            [
                'name' => 'Nestlé Milo Ready-to-Drink 200ml',
                'category' => 3,
                'brand' => 10,
                'description' => 'Chilled malty chocolate drink in a convenient carton, a favorite grab-and-go energy boost for Cambodian students.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p73.png'
            ],
            [
                'name' => 'Nestlé Nestea Peach',
                'category' => 3,
                'brand' => 10,
                'description' => 'Sweet peach-flavored instant iced tea powder, a fruity twist on the classic lemon tea loved during the hot season.',
                'cost_price' => 1.30,
                'sale_price' => 2.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p74.png'
            ],
            [
                'name' => 'Nestlé Bear Brand Milk',
                'category' => 3,
                'brand' => 10,
                'description' => 'Sterilized plain milk in a can, a trusted everyday drink for both children and adults across Cambodia.',
                'cost_price' => 0.55,
                'sale_price' => 0.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p75.png'
            ],
            [
                'name' => 'Nestlé Nescafé Original 3 in 1 Strong',
                'category' => 3,
                'brand' => 10,
                'description' => 'Extra-strong instant coffee mix sachet for those wanting a bolder morning pick-me-up on busy workdays.',
                'cost_price' => 1.25,
                'sale_price' => 2.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p76.png'
            ],
            [
                'name' => 'Nestlé Cerelac Infant Cereal',
                'category' => 3,
                'brand' => 10,
                'description' => 'Fortified infant cereal blended with milk, a trusted first-food choice recommended by pediatricians for Cambodian babies.',
                'cost_price' => 1.90,
                'sale_price' => 3.10,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p77.png'
            ],
            [
                'name' => 'Nestlé Milo Cereal',
                'category' => 3,
                'brand' => 10,
                'description' => 'Chocolate malt breakfast cereal that combines the beloved Milo taste with a crunchy, energizing morning meal.',
                'cost_price' => 2.10,
                'sale_price' => 3.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p78.png'
            ],
            [
                'name' => 'Nestlé Nescafé Ice Roast',
                'category' => 3,
                'brand' => 10,
                'description' => 'Instant coffee blend crafted for iced coffee, matching the strong roasted flavor popular in Khmer iced coffee culture.',
                'cost_price' => 1.85,
                'sale_price' => 2.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p79.png'
            ],
            [
                'name' => 'Nestlé Everyday Creamer Powder',
                'category' => 3,
                'brand' => 10,
                'description' => 'Milk powder blend for tea and coffee, offering a creamy texture and affordable everyday alternative to fresh milk.',
                'cost_price' => 1.60,
                'sale_price' => 2.60,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p80.png'
            ],
            [
                'name' => 'Nestlé Mixed Vegetables',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned mixed vegetables ready to stir-fry or add to soups, a time-saving option for busy Cambodian households.',
                'cost_price' => 0.80,
                'sale_price' => 1.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p81.png'
            ],
            [
                'name' => 'Nestlé Mushroom Soup',
                'category' => 4,
                'brand' => 10,
                'description' => 'Creamy canned mushroom soup that can be enjoyed on its own or used as a cooking base for sauces.',
                'cost_price' => 1.00,
                'sale_price' => 1.65,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p82.png'
            ],
            [
                'name' => 'Nestlé Chicken Curry',
                'category' => 4,
                'brand' => 10,
                'description' => 'Ready-to-eat canned chicken curry with a rich, spiced sauce, a quick meal solution for busy weeknights.',
                'cost_price' => 1.60,
                'sale_price' => 2.60,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p83.png'
            ],
            [
                'name' => 'Nestlé Condensed Milk',
                'category' => 4,
                'brand' => 10,
                'description' => 'Sweet, thick condensed milk widely used in Cambodian iced coffee, desserts, and traditional sweet treats.',
                'cost_price' => 0.70,
                'sale_price' => 1.15,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p84.png'
            ],
            [
                'name' => 'Nestlé Evaporated Milk',
                'category' => 4,
                'brand' => 10,
                'description' => 'Creamy evaporated milk that adds richness to coffee, desserts, and Khmer curries, a versatile pantry staple.',
                'cost_price' => 0.65,
                'sale_price' => 1.10,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p85.png'
            ],
            [
                'name' => 'Nestlé Fruit Cocktail',
                'category' => 4,
                'brand' => 10,
                'description' => 'Sweet canned fruit cocktail perfect for desserts and fruit salads, a convenient treat for family gatherings.',
                'cost_price' => 0.95,
                'sale_price' => 1.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p86.png'
            ],
            [
                'name' => 'Nestlé Green Peas',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned green peas ready to add to rice dishes and stir-fries, a convenient vegetable option year-round.',
                'cost_price' => 0.75,
                'sale_price' => 1.25,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p87.png'
            ],
            [
                'name' => 'Nestlé Braised Pork',
                'category' => 4,
                'brand' => 10,
                'description' => 'Tender canned braised pork in savory sauce, a hearty protein option that pairs well with steamed rice.',
                'cost_price' => 1.70,
                'sale_price' => 2.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p88.png'
            ],
            [
                'name' => 'Nestlé Mackerel in Tomato Sauce',
                'category' => 4,
                'brand' => 10,
                'description' => 'Flavorful canned mackerel simmered in tomato sauce, an affordable protein staple found in most Cambodian pantries.',
                'cost_price' => 0.90,
                'sale_price' => 1.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p89.png'
            ],
            [
                'name' => 'Nestlé Chickpeas',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned chickpeas ready for salads, stews, and snacking, a nutritious and versatile pantry addition.',
                'cost_price' => 0.85,
                'sale_price' => 1.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p90.png'
            ],
            [
                'name' => 'Buldak Curry',
                'category' => 5,
                'brand' => 5,
                'description' => 'Spicy fire noodles infused with a fragrant curry twist, a popular variation for fans of the original Buldak heat.',
                'cost_price' => 0.78,
                'sale_price' => 1.25,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p91.png'
            ],
            [
                'name' => 'Buldak 2x Spicy',
                'category' => 5,
                'brand' => 5,
                'description' => 'Extra-hot version of the original fire noodles for those chasing an even bigger spice challenge.',
                'cost_price' => 0.80,
                'sale_price' => 1.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p92.png'
            ],
            [
                'name' => 'Buldak Stew',
                'category' => 5,
                'brand' => 5,
                'description' => 'Rich, brothy take on the classic fire noodles, combining bold spice with a comforting stew-style broth.',
                'cost_price' => 0.80,
                'sale_price' => 1.28,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p93.png'
            ],
            [
                'name' => 'Koreno Kimchi',
                'category' => 5,
                'brand' => 6,
                'description' => 'Tangy kimchi-flavored instant noodles with a savory, spicy broth, a budget-friendly Korean-style option in Cambodia.',
                'cost_price' => 0.32,
                'sale_price' => 0.58,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p94.png'
            ],
            [
                'name' => 'Koreno Spicy Beef',
                'category' => 5,
                'brand' => 6,
                'description' => 'Hearty beef-flavored instant noodles with a spicy kick, a filling and affordable everyday meal.',
                'cost_price' => 0.32,
                'sale_price' => 0.58,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p95.png'
            ],
            [
                'name' => 'Koreno Tom Yum',
                'category' => 5,
                'brand' => 6,
                'description' => 'Sour and spicy tom yum flavored noodles, blending Thai-inspired taste with convenient instant preparation.',
                'cost_price' => 0.33,
                'sale_price' => 0.60,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p96.png'
            ],
            [
                'name' => 'Tom Yum Shrimp',
                'category' => 5,
                'brand' => 6,
                'description' => 'Classic sour-spicy shrimp tom yum instant noodles, one of the most recognized instant noodle flavors in the region.',
                'cost_price' => 0.30,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p97.png'
            ],
            [
                'name' => 'Indomie Fried Noodles',
                'category' => 5,
                'brand' => 6,
                'description' => 'Popular Indonesian-style fried noodles with savory seasoning and crispy fried onions, a well-loved instant meal across Southeast Asia.',
                'cost_price' => 0.35,
                'sale_price' => 0.62,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p98.png'
            ],
            [
                'name' => 'Buldak Cream Carbonara Cup',
                'category' => 5,
                'brand' => 5,
                'description' => 'Cup-noodle version of the creamy carbonara fire noodles, convenient for quick meals at school or work.',
                'cost_price' => 0.90,
                'sale_price' => 1.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p99.png'
            ],
            [
                'name' => 'Koreno Cup Noodle Chicken',
                'category' => 5,
                'brand' => 6,
                'description' => 'Convenient cup version of the classic chicken noodles, ready in minutes with just hot water, ideal for busy days.',
                'cost_price' => 0.45,
                'sale_price' => 0.78,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p100.png'
            ],
            [
                'name' => 'Coca Cola Cherry 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Classic Coca Cola blended with a bold cherry flavor, a fun twist on the original found in select Cambodian marts.',
                'cost_price' => 0.36,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p101.png'
            ],
            [
                'name' => 'Coca Cola Lime 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Crisp cola with a citrus lime kick, a refreshing seasonal variant popular in the hot season.',
                'cost_price' => 0.36,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p102.png'
            ],
            [
                'name' => 'Coca Cola Can 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Classic Coca Cola in a convenient can format, ideal for quick grab-and-go refreshment.',
                'cost_price' => 0.34,
                'sale_price' => 0.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p103.png'
            ],
            [
                'name' => 'Coca Cola Mini Can 250ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Compact mini can size, perfect for kids or a smaller portion of the classic cola taste.',
                'cost_price' => 0.28,
                'sale_price' => 0.42,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p104.png'
            ],
            [
                'name' => 'Coca Cola Zero Can 330ml',
                'category' => 1,
                'brand' => 1,
                'description' => 'Zero sugar cola in a can, offering the same bold taste without the calories.',
                'cost_price' => 0.36,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p105.png'
            ],

            // ===== Pepsi (brand 2) - Drinks (category 1) =====
            [
                'name' => 'Pepsi Mango 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Tropical mango-infused cola blend, a fruity limited-run variant popular with younger drinkers.',
                'cost_price' => 0.34,
                'sale_price' => 0.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p106.png'
            ],
            [
                'name' => 'Pepsi Twist Lemon 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Classic Pepsi cola with a citrus lemon twist, sold alongside regular Pepsi in most marts.',
                'cost_price' => 0.34,
                'sale_price' => 0.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p107.png'
            ],
            [
                'name' => 'Pepsi Can 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Bold Pepsi cola in a convenient can, a familiar choice at restaurants and street stalls.',
                'cost_price' => 0.32,
                'sale_price' => 0.48,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p108.png'
            ],
            [
                'name' => 'Pepsi Max 330ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Zero-sugar cola with maximum bold taste, for shoppers cutting back on sugar.',
                'cost_price' => 0.35,
                'sale_price' => 0.52,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p109.png'
            ],
            [
                'name' => 'Pepsi Mini Can 250ml',
                'category' => 1,
                'brand' => 2,
                'description' => 'Smaller can size of the classic Pepsi taste, convenient for a light refreshment.',
                'cost_price' => 0.27,
                'sale_price' => 0.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p110.png'
            ],

            // ===== Eau Kulen (brand 3) - Drinks (category 1) =====
            [
                'name' => 'Eau Kulen Sparkling 500ml',
                'category' => 1,
                'brand' => 3,
                'description' => 'Lightly carbonated mineral water from Kulen mountain, a crisp alternative to still bottled water.',
                'cost_price' => 0.25,
                'sale_price' => 0.42,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p111.png'
            ],
            [
                'name' => 'Eau Kulen 6L Jug',
                'category' => 1,
                'brand' => 3,
                'description' => 'Large-format 6L water jug for home dispensers, a cost-efficient hydration option for families.',
                'cost_price' => 1.10,
                'sale_price' => 1.85,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p112.png'
            ],
            [
                'name' => 'Eau Kulen 19L Bottle',
                'category' => 1,
                'brand' => 3,
                'description' => 'Large refillable water bottle for home and office dispensers, a common sight in Cambodian offices.',
                'cost_price' => 1.60,
                'sale_price' => 2.70,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p113.png'
            ],
            [
                'name' => 'Eau Kulen Lemon Infused 500ml',
                'category' => 1,
                'brand' => 3,
                'description' => 'Mineral water lightly infused with natural lemon essence for a subtle refreshing taste.',
                'cost_price' => 0.28,
                'sale_price' => 0.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p114.png'
            ],

            // ===== No Brand (brand 11) - Household (category 2) =====
            [
                'name' => 'Trash Bags Large 20pc',
                'category' => 2,
                'brand' => 11,
                'description' => 'Sturdy large trash bags for household waste, sold in convenient rolls of 20.',
                'cost_price' => 0.80,
                'sale_price' => 1.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p115.png'
            ],
            [
                'name' => 'Aluminum Foil Roll',
                'category' => 2,
                'brand' => 11,
                'description' => 'Durable aluminum foil for wrapping and cooking, a kitchen essential across Cambodian households.',
                'cost_price' => 1.00,
                'sale_price' => 1.70,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p116.png'
            ],
            [
                'name' => 'Dish Sponge 3-Pack',
                'category' => 2,
                'brand' => 11,
                'description' => 'Scratch-free dish sponges for everyday washing up, sold in a value 3-pack.',
                'cost_price' => 0.45,
                'sale_price' => 0.80,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p117.png'
            ],
            [
                'name' => 'Mop and Bucket Set',
                'category' => 2,
                'brand' => 11,
                'description' => 'Compact mop and bucket combo for quick floor cleaning in tiled Cambodian homes.',
                'cost_price' => 3.80,
                'sale_price' => 6.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p118.png'
            ],
            [
                'name' => 'Clothes Drying Rack',
                'category' => 2,
                'brand' => 11,
                'description' => 'Foldable stainless steel rack for air-drying laundry, suited for small apartment balconies.',
                'cost_price' => 4.20,
                'sale_price' => 6.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p119.png'
            ],
            [
                'name' => 'Broom and Dustpan Set',
                'category' => 2,
                'brand' => 11,
                'description' => 'Lightweight broom and dustpan set for quick daily sweeping and tidying.',
                'cost_price' => 1.80,
                'sale_price' => 3.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p120.png'
            ],

            // ===== Nestlé (brand 10) - Coffee & Tea (category 3) / Canned Food (category 4) =====
            [
                'name' => 'Nestlé Nescafé Cappuccino',
                'category' => 3,
                'brand' => 10,
                'description' => 'Frothy cappuccino-style instant coffee mix, a cafe-style treat at home.',
                'cost_price' => 1.50,
                'sale_price' => 2.40,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p121.png'
            ],
            [
                'name' => 'Nestlé Nescafé Mocha',
                'category' => 3,
                'brand' => 10,
                'description' => 'Chocolate-coffee instant mix combining rich cocoa notes with classic Nescafé flavor.',
                'cost_price' => 1.55,
                'sale_price' => 2.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p122.png'
            ],
            [
                'name' => 'Nestlé Milo Tin 400g',
                'category' => 3,
                'brand' => 10,
                'description' => 'Family-size tin of malty Milo powder for daily breakfast drinks.',
                'cost_price' => 2.60,
                'sale_price' => 4.10,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p123.png'
            ],
            [
                'name' => 'Nestlé Nestea Green Tea',
                'category' => 3,
                'brand' => 10,
                'description' => 'Instant green tea powder with a light, refreshing taste for hot Cambodian afternoons.',
                'cost_price' => 1.30,
                'sale_price' => 2.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p124.png'
            ],
            [
                'name' => 'Nestlé Nescafé 3in1 Box (30 sachets)',
                'category' => 3,
                'brand' => 10,
                'description' => 'Box of individual 3-in-1 coffee sachets, convenient for offices and travel.',
                'cost_price' => 3.00,
                'sale_price' => 4.80,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p125.png'
            ],
            [
                'name' => 'Nestlé Corned Beef',
                'category' => 4,
                'brand' => 10,
                'description' => 'Savory canned corned beef, quick to prepare with rice for a simple hearty meal.',
                'cost_price' => 1.65,
                'sale_price' => 2.70,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p126.png'
            ],
            [
                'name' => 'Nestlé Chicken Sausages',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned chicken sausages in brine, an easy protein add-on for soups and fried rice.',
                'cost_price' => 1.20,
                'sale_price' => 1.95,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p127.png'
            ],
            [
                'name' => 'Nestlé Bamboo Shoots',
                'category' => 4,
                'brand' => 10,
                'description' => 'Canned bamboo shoots ready for Khmer soups and stir-fries.',
                'cost_price' => 0.70,
                'sale_price' => 1.20,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p128.png'
            ],

            // ===== Julies (brand 4) - Food/Snacks (category 8) =====
            [
                'name' => 'Julies Butter Cookies Tin',
                'category' => 8,
                'brand' => 4,
                'description' => 'Crumbly, buttery cookies in a tin, a favorite gifting snack during Cambodian holidays.',
                'cost_price' => 1.20,
                'sale_price' => 2.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p129.png'
            ],
            [
                'name' => 'Julies Wafer Rolls',
                'category' => 8,
                'brand' => 4,
                'description' => 'Crispy rolled wafers with a creamy filling, a light everyday snack.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p130.png'
            ],
            [
                'name' => 'Julies Pandan Wafer',
                'category' => 8,
                'brand' => 4,
                'description' => 'Fragrant pandan-flavored wafer biscuits, a Southeast Asian favorite.',
                'cost_price' => 0.50,
                'sale_price' => 0.85,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p131.png'
            ],
            [
                'name' => 'Julies Milk Sandwich Crackers',
                'category' => 8,
                'brand' => 4,
                'description' => 'Sandwich crackers filled with sweet milk cream, a popular lunchbox item for kids.',
                'cost_price' => 0.45,
                'sale_price' => 0.75,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p132.png'
            ],
            [
                'name' => 'Julies Strawberry Wafer',
                'category' => 8,
                'brand' => 4,
                'description' => 'Crispy layered wafer with a sweet strawberry cream filling.',
                'cost_price' => 0.50,
                'sale_price' => 0.85,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p133.png'
            ],

            // ===== Buldak (brand 5) - Instant Food (category 5) =====
            [
                'name' => 'Buldak Bulgogi',
                'category' => 5,
                'brand' => 5,
                'description' => 'Sweet and spicy bulgogi-flavored fire noodles, a milder Buldak variant with BBQ notes.',
                'cost_price' => 0.78,
                'sale_price' => 1.25,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p134.png'
            ],
            [
                'name' => 'Buldak Habanero Lime',
                'category' => 5,
                'brand' => 5,
                'description' => 'Extra-hot habanero lime fire noodles for spice enthusiasts chasing bold new flavors.',
                'cost_price' => 0.80,
                'sale_price' => 1.30,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p135.png'
            ],
            [
                'name' => 'Buldak Cup Original',
                'category' => 5,
                'brand' => 5,
                'description' => 'Cup version of the original fire noodles, ready with hot water in minutes.',
                'cost_price' => 0.90,
                'sale_price' => 1.45,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p136.png'
            ],
            [
                'name' => 'Buldak Mala',
                'category' => 5,
                'brand' => 5,
                'description' => 'Numbing-spicy mala-flavored fire noodles, a fusion variant blending Sichuan heat with the classic Buldak kick.',
                'cost_price' => 0.82,
                'sale_price' => 1.32,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p137.png'
            ],
            [
                'name' => 'Buldak Tteokbokki',
                'category' => 5,
                'brand' => 5,
                'description' => 'Spicy rice cake style noodles inspired by Korean tteokbokki, chewy and fiery.',
                'cost_price' => 0.85,
                'sale_price' => 1.35,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p138.png'
            ],

            // ===== Koreno (brand 6) - Instant Food (category 5) =====
            [
                'name' => 'Koreno Curry',
                'category' => 5,
                'brand' => 6,
                'description' => 'Mild curry-flavored instant noodles with a comforting, aromatic broth.',
                'cost_price' => 0.32,
                'sale_price' => 0.58,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p139.png'
            ],
            [
                'name' => 'Koreno Mushroom',
                'category' => 5,
                'brand' => 6,
                'description' => 'Savory mushroom-flavored instant noodles, a lighter vegetarian-friendly option.',
                'cost_price' => 0.32,
                'sale_price' => 0.58,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p140.png'
            ],
            [
                'name' => 'Koreno Duck Noodle',
                'category' => 5,
                'brand' => 6,
                'description' => 'Rich duck-flavored broth noodles, a heartier pick among the Koreno lineup.',
                'cost_price' => 0.35,
                'sale_price' => 0.62,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p141.png'
            ],
            [
                'name' => 'Koreno Cup Kimchi',
                'category' => 5,
                'brand' => 6,
                'description' => 'Cup-format version of the tangy kimchi noodles, ready with hot water in minutes.',
                'cost_price' => 0.48,
                'sale_price' => 0.80,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p142.png'
            ],
            [
                'name' => 'Koreno Vegetable Broth',
                'category' => 5,
                'brand' => 6,
                'description' => 'Light vegetable-based broth noodles, a milder everyday option for the whole family.',
                'cost_price' => 0.30,
                'sale_price' => 0.55,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p143.png'
            ],

            // ===== Ajinomoto (brand 8) - Seasoning (category 9) =====
            [
                'name' => 'Ajinomoto Garlic Powder',
                'category' => 9,
                'brand' => 8,
                'description' => 'Finely ground garlic powder that adds aromatic depth to marinades and stir-fries.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p144.png'
            ],
            [
                'name' => 'Ajinomoto Five Spice Powder',
                'category' => 9,
                'brand' => 8,
                'description' => 'Aromatic five-spice blend used in braises and roasted meats.',
                'cost_price' => 0.65,
                'sale_price' => 1.05,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p145.png'
            ],
            [
                'name' => 'Ajinomoto Shrimp Paste',
                'category' => 9,
                'brand' => 8,
                'description' => 'Pungent fermented shrimp paste essential to many traditional Khmer dips.',
                'cost_price' => 0.90,
                'sale_price' => 1.50,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p146.png'
            ],
            [
                'name' => 'Ajinomoto Palm Vinegar',
                'category' => 9,
                'brand' => 8,
                'description' => 'Mild palm-based vinegar used for pickling and dressing salads.',
                'cost_price' => 0.55,
                'sale_price' => 0.90,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p147.png'
            ],
            [
                'name' => 'Ajinomoto Sate Sauce',
                'category' => 9,
                'brand' => 8,
                'description' => 'Rich, peanut-based sate sauce for grilled skewers and dipping.',
                'cost_price' => 1.00,
                'sale_price' => 1.65,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p148.png'
            ],
            [
                'name' => 'Ajinomoto Seafood Seasoning',
                'category' => 9,
                'brand' => 8,
                'description' => 'Umami-rich seafood seasoning powder used to enhance soups and grilled dishes.',
                'cost_price' => 0.60,
                'sale_price' => 1.00,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p149.png'
            ],
            [
                'name' => 'Ajinomoto Pepper Salt Mix',
                'category' => 9,
                'brand' => 8,
                'description' => 'Blended pepper and salt seasoning, a versatile finishing touch for grilled and fried dishes.',
                'cost_price' => 0.55,
                'sale_price' => 0.92,
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p150.png'
            ],
            // [
            //     'name' => 'Jasmine Rice 5kg',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Fragrant, long-grain jasmine rice, the everyday staple rice found in nearly every Cambodian household.',
            //     'cost_price' => 4.20,
            //     'sale_price' => 6.80,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p201.png'
            // ],
            // [
            //     'name' => 'Sticky Rice 2kg',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Glutinous sticky rice used for traditional Khmer desserts and festive dishes.',
            //     'cost_price' => 2.30,
            //     'sale_price' => 3.75,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p202.png'
            // ],
            // [
            //     'name' => 'Red Rice 2kg',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Nutritious red rice with a nutty flavor, favored by health-conscious households.',
            //     'cost_price' => 2.60,
            //     'sale_price' => 4.20,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p203.png'
            // ],
            // [
            //     'name' => 'Rice Vermicelli 400g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Thin dried rice noodles used in traditional Khmer noodle soups and stir-fries.',
            //     'cost_price' => 0.80,
            //     'sale_price' => 1.35,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p204.png'
            // ],
            // [
            //     'name' => 'Dried Mung Beans 500g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Versatile dried mung beans used in soups, desserts, and traditional Khmer sweets.',
            //     'cost_price' => 1.00,
            //     'sale_price' => 1.65,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p205.png'
            // ],
            // [
            //     'name' => 'Dried Red Beans 500g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Nutritious dried red beans commonly used in soups and traditional desserts.',
            //     'cost_price' => 1.05,
            //     'sale_price' => 1.75,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p206.png'
            // ],
            // [
            //     'name' => 'Palm Sugar 500g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Traditional Cambodian palm sugar with a rich caramel flavor, essential for authentic Khmer cooking.',
            //     'cost_price' => 1.40,
            //     'sale_price' => 2.30,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p207.png'
            // ],
            // [
            //     'name' => 'All-Purpose Flour 1kg',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Fine, versatile flour suited for baking bread, cakes, and traditional Khmer pastries.',
            //     'cost_price' => 0.90,
            //     'sale_price' => 1.50,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p208.png'
            // ],
            // [
            //     'name' => 'Rice Flour 500g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Fine rice flour used in traditional Khmer desserts, dumplings, and batters.',
            //     'cost_price' => 0.75,
            //     'sale_price' => 1.25,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p209.png'
            // ],
            // [
            //     'name' => 'Tapioca Pearls 400g',
            //     'category' => 16,
            //     'brand' => 27,
            //     'description' => 'Chewy tapioca pearls used in desserts and the popular bubble tea drinks found across Cambodia.',
            //     'cost_price' => 0.85,
            //     'sale_price' => 1.45,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p210.png'
            // ],
            // [
            //     'name' => 'White Sandwich Bread',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Soft, fresh white bread loaf, a breakfast staple for sandwiches and toast in Cambodian homes.',
            //     'cost_price' => 0.70,
            //     'sale_price' => 1.20,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p211.png'
            // ],
            // [
            //     'name' => 'Whole Wheat Bread',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Hearty whole wheat bread loaf offering more fiber for health-conscious households.',
            //     'cost_price' => 0.85,
            //     'sale_price' => 1.40,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p212.png'
            // ],
            // [
            //     'name' => 'Butter Croissant',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Flaky, buttery croissant baked fresh, a popular breakfast pastry in Cambodian cafes and bakeries.',
            //     'cost_price' => 0.60,
            //     'sale_price' => 1.00,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p213.png'
            // ],
            // [
            //     'name' => 'Sweet Bun Pack',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Soft, lightly sweetened buns filled with cream or red bean paste, a popular after-school snack.',
            //     'cost_price' => 0.75,
            //     'sale_price' => 1.25,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p214.png'
            // ],
            // [
            //     'name' => 'Baguette',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Crispy-crust baguette influenced by Cambodia\'s French colonial heritage, commonly used for num pang sandwiches.',
            //     'cost_price' => 0.40,
            //     'sale_price' => 0.70,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p215.png'
            // ],
            // [
            //     'name' => 'Chocolate Muffin',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Moist chocolate muffin with a rich, fudgy center, a sweet treat for breakfast or dessert.',
            //     'cost_price' => 0.65,
            //     'sale_price' => 1.10,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p216.png'
            // ],
            // [
            //     'name' => 'Cream Puff Pack',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Light choux pastry filled with sweet custard cream, a popular Cambodian bakery favorite.',
            //     'cost_price' => 0.90,
            //     'sale_price' => 1.50,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p217.png'
            // ],
            // [
            //     'name' => 'Coconut Bun',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Soft bun filled with sweet shredded coconut, a beloved local bakery treat.',
            //     'cost_price' => 0.55,
            //     'sale_price' => 0.92,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p218.png'
            // ],
            // [
            //     'name' => 'Pandan Chiffon Cake',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Light and fluffy chiffon cake flavored with fragrant pandan, a favorite Southeast Asian dessert.',
            //     'cost_price' => 2.20,
            //     'sale_price' => 3.60,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p219.png'
            // ],
            // [
            //     'name' => 'Egg Tart',
            //     'category' => 17,
            //     'brand' => 28,
            //     'description' => 'Flaky pastry shell filled with smooth egg custard, a popular sweet snack found in Cambodian bakeries.',
            //     'cost_price' => 0.45,
            //     'sale_price' => 0.78,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p220.png'
            // ],
            // [
            //     'name' => 'Red Bull 250ml',
            //     'category' => 18,
            //     'brand' => 12,
            //     'description' => 'Classic energy drink that delivers a quick boost of alertness, a favorite among students and night-shift workers.',
            //     'cost_price' => 0.75,
            //     'sale_price' => 1.25,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p221.png'
            // ],
            // [
            //     'name' => 'Carabao Energy Drink',
            //     'category' => 18,
            //     'brand' => 13,
            //     'description' => 'Popular Thai energy drink with a bold, sweet taste, widely consumed by laborers and drivers across Cambodia.',
            //     'cost_price' => 0.45,
            //     'sale_price' => 0.78,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p222.png'
            // ],
            // [
            //     'name' => 'Sting Energy Drink',
            //     'category' => 18,
            //     'brand' => 14,
            //     'description' => 'Fruity, affordable energy drink favored by younger Cambodians for its bold taste and quick pick-me-up.',
            //     'cost_price' => 0.40,
            //     'sale_price' => 0.70,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p223.png'
            // ],
            // [
            //     'name' => 'M-150 Energy Drink',
            //     'category' => 18,
            //     'brand' => 14,
            //     'description' => 'Small-bottle energy drink with a concentrated boost, a long-time favorite among Cambodian moto-taxi drivers.',
            //     'cost_price' => 0.35,
            //     'sale_price' => 0.60,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p224.png'
            // ],
            // [
            //     'name' => 'Red Bull Sugar Free',
            //     'category' => 18,
            //     'brand' => 12,
            //     'description' => 'Sugar-free version of the classic energy drink, offering the same boost without the added sugar.',
            //     'cost_price' => 0.80,
            //     'sale_price' => 1.32,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p225.png'
            // ],
            // [
            //     'name' => 'Carabao White Energy Drink',
            //     'category' => 18,
            //     'brand' => 13,
            //     'description' => 'Milder, milk-based energy drink variant, a smoother alternative to the original bold formula.',
            //     'cost_price' => 0.48,
            //     'sale_price' => 0.82,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p226.png'
            // ],
            // [
            //     'name' => 'Sting Blue Charge',
            //     'category' => 18,
            //     'brand' => 14,
            //     'description' => 'Blueberry-flavored energy drink offering a fruity twist on the classic energy boost.',
            //     'cost_price' => 0.42,
            //     'sale_price' => 0.72,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p227.png'
            // ],
            // [
            //     'name' => 'Kratingdaeng Original',
            //     'category' => 18,
            //     'brand' => 12,
            //     'description' => 'The original Thai formula energy shot, credited as the inspiration behind many global energy drink brands.',
            //     'cost_price' => 0.38,
            //     'sale_price' => 0.65,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p228.png'
            // ],
            // [
            //     'name' => 'Sting Red',
            //     'category' => 18,
            //     'brand' => 14,
            //     'description' => 'Classic red-flavored energy drink with a bold, sweet taste popular among Cambodian youth.',
            //     'cost_price' => 0.40,
            //     'sale_price' => 0.70,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p229.png'
            // ],
            // [
            //     'name' => 'Carabao Cave Energy Shot',
            //     'category' => 18,
            //     'brand' => 13,
            //     'description' => 'Compact energy shot format for a fast, concentrated boost on busy days.',
            //     'cost_price' => 0.55,
            //     'sale_price' => 0.95,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p230.png'
            // ],
            // [
            //     'name' => 'Double A Copy Paper A4',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Smooth, high-quality A4 copy paper suited for printing, schoolwork, and office use.',
            //     'cost_price' => 3.20,
            //     'sale_price' => 5.20,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p231.png'
            // ],
            // [
            //     'name' => 'Double A Notebook',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Durable ruled notebook ideal for students and office note-taking.',
            //     'cost_price' => 0.90,
            //     'sale_price' => 1.50,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p232.png'
            // ],
            // [
            //     'name' => 'Ballpoint Pen Set',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Smooth-writing ballpoint pens in a multi-color set, a daily essential for school and office.',
            //     'cost_price' => 0.60,
            //     'sale_price' => 1.00,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p233.png'
            // ],
            // [
            //     'name' => 'Correction Tape',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Clean, easy-to-apply correction tape for quickly fixing writing mistakes.',
            //     'cost_price' => 0.50,
            //     'sale_price' => 0.85,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p234.png'
            // ],
            // [
            //     'name' => 'Highlighter Set',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Vibrant highlighter set in assorted colors, useful for studying and organizing notes.',
            //     'cost_price' => 0.85,
            //     'sale_price' => 1.40,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p235.png'
            // ],
            // [
            //     'name' => 'Sticky Notes Pack',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Colorful sticky notes for reminders, bookmarks, and organizing tasks at school or work.',
            //     'cost_price' => 0.55,
            //     'sale_price' => 0.95,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p236.png'
            // ],
            // [
            //     'name' => 'Mechanical Pencil Set',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Precise mechanical pencils with replaceable lead, a favorite among students for consistent writing.',
            //     'cost_price' => 0.70,
            //     'sale_price' => 1.20,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p237.png'
            // ],
            // [
            //     'name' => 'Scissors',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Sharp, comfortable-grip scissors suited for school projects and everyday office use.',
            //     'cost_price' => 0.65,
            //     'sale_price' => 1.10,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p238.png'
            // ],
            // [
            //     'name' => 'Stapler with Staples',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Compact stapler with a box of staples included, a handy tool for office and school organization.',
            //     'cost_price' => 1.10,
            //     'sale_price' => 1.85,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p239.png'
            // ],
            // [
            //     'name' => 'Whiteboard Markers Set',
            //     'category' => 19,
            //     'brand' => 29,
            //     'description' => 'Set of vibrant whiteboard markers with easy-erase ink, useful for classrooms and offices.',
            //     'cost_price' => 0.95,
            //     'sale_price' => 1.60,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p240.png'
            // ],
            // [
            //     'name' => 'Anker USB-C Charging Cable',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Durable, fast-charging USB-C cable built to withstand daily use and frequent travel.',
            //     'cost_price' => 2.50,
            //     'sale_price' => 4.20,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p241.png'
            // ],
            // [
            //     'name' => 'Anker Power Bank 10000mAh',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Compact power bank offering multiple phone charges on the go, ideal for commuting and travel.',
            //     'cost_price' => 8.50,
            //     'sale_price' => 13.90,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p242.png'
            // ],
            // [
            //     'name' => 'Anker Wall Charger',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Fast-charging wall adapter compatible with most smartphones, a reliable everyday charging accessory.',
            //     'cost_price' => 3.20,
            //     'sale_price' => 5.30,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p243.png'
            // ],
            // [
            //     'name' => 'Anker Wireless Earbuds',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Compact wireless earbuds with clear sound and a secure fit, popular for commuting and workouts.',
            //     'cost_price' => 12.00,
            //     'sale_price' => 19.50,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p244.png'
            // ],
            // [
            //     'name' => 'Anker Phone Holder',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Adjustable phone stand suited for video calls, watching shows, or navigation while driving.',
            //     'cost_price' => 2.00,
            //     'sale_price' => 3.40,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p245.png'
            // ],
            // [
            //     'name' => 'Anker Bluetooth Speaker',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Portable Bluetooth speaker delivering clear sound, great for gatherings and outdoor use.',
            //     'cost_price' => 9.50,
            //     'sale_price' => 15.80,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p246.png'
            // ],
            // [
            //     'name' => 'Anker Screen Protector',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Tempered glass screen protector that guards phone screens against scratches and cracks.',
            //     'cost_price' => 1.20,
            //     'sale_price' => 2.10,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p247.png'
            // ],
            // [
            //     'name' => 'Anker Phone Case',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Protective phone case designed to absorb drops while keeping a slim profile.',
            //     'cost_price' => 2.10,
            //     'sale_price' => 3.60,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p248.png'
            // ],
            // [
            //     'name' => 'Anker Car Charger',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Dual-port car charger that keeps devices powered during long commutes or road trips.',
            //     'cost_price' => 2.80,
            //     'sale_price' => 4.70,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p249.png'
            // ],
            // [
            //     'name' => 'Anker USB Hub',
            //     'category' => 20,
            //     'brand' => 30,
            //     'description' => 'Multi-port USB hub that expands connectivity for laptops with limited ports.',
            //     'cost_price' => 4.50,
            //     'sale_price' => 7.50,
            //     'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/p250.png'
            // ],

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

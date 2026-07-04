<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use App\Models\ProductsImageModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productData = [
            [
                'name' => 'Coca Cola 330ml Can',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Pepsi 330ml Can',
                'category' => 1,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sprite 330ml Can',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Fanta Orange 330ml',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => '7Up Lemon Lime 330ml',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Mirinda Strawberry 330ml',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/612HeyYXOnL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nescafe Classic 200g',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nescafe 3in1 Coffee Mix',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Milo Chocolate Drink 400g',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ovaltine Malt Drink 400g',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Red Bull Energy Drink 250ml',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Mogu Mogu Mango 320ml',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Minute Maid Orange Juice 1L',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Tiger Beer 330ml',
                'category' => 1,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Anchor Beer 330ml',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Angkor Beer 330ml',
                'category' => 1,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Cambodia Beer 330ml',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nestle Pure Life Water 500ml',
                'category' => 1,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Aquafina Water 500ml',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ice Mountain Mineral Water 1.5L',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Lays Classic Potato Chips 60g',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Oishi Prawn Crackers 60g',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/612HeyYXOnL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Pringles Original 100g',
                'category' => 2,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Doritos Nacho Cheese 150g',
                'category' => 2,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ritz Crackers 200g',
                'category' => 2,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Oreo Original Cookies 137g',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Chocopie 12pcs Box',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'KitKat Chocolate 4 Finger',
                'category' => 2,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Snickers Chocolate Bar',
                'category' => 2,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Twix Chocolate Bar',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Pocky Chocolate Sticks',
                'category' => 2,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Tao Kae Noi Seaweed Snack',
                'category' => 2,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Cheetos Cheese Puffs 90g',
                'category' => 2,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Kokomo Coconut Biscuit',
                'category' => 2,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Khmer Rice Crackers 100g',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Anchor Full Cream Milk 1L',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dutch Mill Yogurt Drink 180ml',
                'category' => 3,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Anlene Gold Milk Powder 800g',
                'category' => 3,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'President Butter 200g',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Cheddar Cheese Slices 200g',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Yakult Probiotic Drink 5pk',
                'category' => 3,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Meiji Fresh Milk 1L',
                'category' => 3,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dutch Lady UHT Milk 1L',
                'category' => 3,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nestle Cream 250ml',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Greek Style Yogurt 500g',
                'category' => 3,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Vinamilk Fresh Milk 1L',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Colgate Toothpaste 150g',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Colgate Total Toothbrush',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sensodyne Toothpaste 100g',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Head & Shoulders Shampoo 400ml',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Pantene Shampoo 400ml',
                'category' => 9,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dove Body Wash 500ml',
                'category' => 9,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nivea Body Lotion 400ml',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Rexona Deodorant Spray',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Gillette Razor 3pk',
                'category' => 9,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Johnson\'s Baby Powder 200g',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Vaseline Petroleum Jelly 100g',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dettol Hand Wash 500ml',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dettol Antiseptic Liquid 500ml',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Lifebuoy Hand Soap 250g',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sunlight Dishwashing Liquid 800ml',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ariel Detergent Powder 1kg',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Downy Fabric Softener 1L',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Glade Air Freshener',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Harpic Toilet Cleaner 500ml',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51ZNB3skixL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Kleenex Tissue Box',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Scotch Brite Sponge 3pk',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Mr Muscle Glass Cleaner 500ml',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Chicken Nuggets 500g',
                'category' => 4,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen French Fries 1kg',
                'category' => 4,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/51ZNB3skixL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Fish Fillet 500g',
                'category' => 4,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Dumplings 400g',
                'category' => 4,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Mixed Vegetables 500g',
                'category' => 4,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ice Cream Vanilla 1L',
                'category' => 4,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Pork Sausage 500g',
                'category' => 4,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Spring Rolls 400g',
                'category' => 4,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'White Sandwich Bread 400g',
                'category' => 5,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Whole Wheat Bread 400g',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Croissant 6pcs Pack',
                'category' => 5,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Chocolate Muffin 4pcs',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Hotdog Buns 6pcs',
                'category' => 5,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Butter Cookies Tin 200g',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sponge Cake Roll',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Garlic Bread 300g',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Tuna in Oil 185g',
                'category' => 6,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Sardines in Tomato Sauce 155g',
                'category' => 6,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Corn 340g',
                'category' => 6,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Baked Beans 420g',
                'category' => 6,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Coconut Milk 400ml',
                'category' => 6,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Peas 300g',
                'category' => 6,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Mushroom 425g',
                'category' => 6,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/51ZNB3skixL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Pineapple Slices 565g',
                'category' => 6,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Vegetable Cooking Oil 1L',
                'category' => 7,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Soy Sauce 700ml',
                'category' => 7,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Fish Sauce 700ml',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Oyster Sauce 500g',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'White Sugar 1kg',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Iodized Salt 500g',
                'category' => 7,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Jasmine Rice 5kg',
                'category' => 7,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'All Purpose Flour 1kg',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Instant Noodles Pack 5pcs',
                'category' => 7,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/612HeyYXOnL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Chili Sauce 500ml',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Tomato Ketchup 340g',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Mayonnaise 400g',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Curry Powder 100g',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Black Pepper Ground 50g',
                'category' => 7,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Vitamin C Tablets 100pcs',
                'category' => 8,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Panadol Paracetamol 20 Tabs',
                'category' => 8,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Multivitamin Gummies 60pcs',
                'category' => 8,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Hand Sanitizer Gel 500ml',
                'category' => 8,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Face Mask 50pcs Box',
                'category' => 8,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Digital Thermometer',
                'category' => 8,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Herbal Cough Syrup 100ml',
                'category' => 8,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Omega 3 Fish Oil Capsules',
                'category' => 8,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nivea Body Lotion 400ml (Pack of 2)',
                'category' => 9,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Lifebuoy Hand Soap 250g (Pack of 2)',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Face Mask 50pcs Box (Pack of 2)',
                'category' => 8,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Soy Sauce 700ml (Pack of 2)',
                'category' => 7,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nescafe Classic 200g (Pack of 2)',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Canned Corn 340g (Pack of 2)',
                'category' => 6,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Garlic Bread 300g (Pack of 2)',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sponge Cake Roll (Pack of 2)',
                'category' => 5,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Minute Maid Orange Juice 1L (Pack of 2)',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nescafe 3in1 Coffee Mix (Pack of 2)',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dove Body Wash 500ml (Pack of 2)',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Soy Sauce 700ml (Pack of 2)',
                'category' => 7,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nestle Cream 250ml (Pack of 2)',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Tomato Ketchup 340g (Pack of 2)',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'KitKat Chocolate 4 Finger (Pack of 2)',
                'category' => 2,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Glade Air Freshener (Pack of 2)',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Head & Shoulders Shampoo 400ml (Pack of 2)',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sensodyne Toothpaste 100g (Pack of 2)',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nivea Body Lotion 400ml (Pack of 2) (Pack of 2)',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Anchor Full Cream Milk 1L (Pack of 2)',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Multivitamin Gummies 60pcs (Pack of 2)',
                'category' => 8,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Colgate Toothpaste 150g (Pack of 2)',
                'category' => 9,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Frozen Dumplings 400g (Pack of 2)',
                'category' => 4,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Nescafe Classic 200g (Pack of 2) (Pack of 2)',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/710DwRCax+L._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Glade Air Freshener (Pack of 2)',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Ice Mountain Mineral Water 1.5L (Pack of 2)',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Omega 3 Fish Oil Capsules (Pack of 2)',
                'category' => 8,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Omega 3 Fish Oil Capsules (Pack of 2) (Pack of 2)',
                'category' => 8,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Oreo Original Cookies 137g (Pack of 2)',
                'category' => 2,
                'brand' => 8,
                'image' => 'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Minute Maid Orange Juice 1L (Pack of 2)',
                'category' => 1,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Glade Air Freshener (Pack of 2) (Pack of 2)',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Fanta Orange 330ml (Pack of 2)',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Doritos Nacho Cheese 150g (Pack of 2)',
                'category' => 2,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Sunlight Dishwashing Liquid 800ml (Pack of 2)',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Dutch Lady UHT Milk 1L (Pack of 2)',
                'category' => 3,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg'
            ],
            [
                'name' => 'Curry Powder 100g (Pack of 2)',
                'category' => 7,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg'
            ],
        ];

        foreach ($productData as $index => $item) {

            $createdAt = now()->subDays(rand(1, 730));

            $product = ProductsModel::create([
                'categories_id' => $item['category'],
                'brand_id'      => $item['brand'],
                'product_code'  => 'PRD' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'name'          => $item['name'],
                'description'   => $item['name'],
                'unit'          => 'piece',
                'cost_price'    => rand(1, 100),
                'sale_price'    => rand(1, 200),
                'quantity'      => rand(10, 500),
                'status'        => true,
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            ProductsImageModel::create([
                'product_id' => $product->id,
                'image_url'  => $item['image'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
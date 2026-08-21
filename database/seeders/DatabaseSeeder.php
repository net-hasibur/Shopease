<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HeroSlide;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users - More users added
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@shopease.com',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@shopease.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $customer = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@shopease.com',
            'role' => 'customer',
            'password' => Hash::make('password'),
        ]);

        // Create 50 random users instead of 10
        $users = User::factory(50)->create();
        $allUsers = collect([$customer, $superAdmin, $admin])->concat($users);

        // 2. Seed Hero Slides - Added more slides
        $slides = [
            [
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=70',
                'link' => '/product-details/wireless-noise-cancelling-headphones',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1483985988355-66a78b4f5bb1?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop?category=fashion',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1534126511673-b6899657816a?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop?category=electronics',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }

        // 3. Seed Categories - Added more categories
        $categoriesData = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70',
                'description' => 'Find the latest and greatest in high-tech gadgets and accessories.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70',
                'description' => 'Stay on trend with our modern, stylish apparel and accessories.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'image' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70',
                'description' => 'Transform your space with premium furniture, decor, and tools.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beauty',
                'slug' => 'beauty',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70',
                'description' => 'Self-care and cosmetics for a fresh and glowing look.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70',
                'description' => 'Gear and equipment for active, healthy living.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
                'description' => 'Knowledge, stories, and educational resources.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Gadgets',
                'slug' => 'gadgets',
                'image' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=400&q=70',
                'description' => 'Cool gadgets and accessories for tech enthusiasts.',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=70',
                'description' => 'Delicious foods, snacks, and beverages.',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'image' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=400&q=70',
                'description' => 'Fun toys and games for all ages.',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=400&q=70',
                'description' => 'Products for a healthy lifestyle and wellness.',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['name']] = Category::create($catData);
        }

        // 4. Seed Products - Added more products
        $productsData = [
            // Existing products (kept as is)
            [
                'id' => 14,
                'category_name' => 'Electronics',
                'name' => 'Wireless Noise-Cancelling Headphones',
                'slug' => 'wireless-noise-cancelling-headphones',
                'short_description' => 'Premium wireless noise-cancelling headphones with up to 40 hours of battery life.',
                'description' => 'The ShopEase Wireless Noise-Cancelling Headphones are engineered for listeners who refuse to compromise. Advanced hybrid active noise cancellation silences the world around you, while custom 40mm drivers deliver deep, balanced sound across every genre.',
                'price' => 6499.00,
                'compare_at_price' => 8999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 980,
                'images' => [
                    'photo-1505740420928-5e560c06d30e',
                    'photo-1484704849700-f032a568e944',
                    'photo-1583394838336-acd977736f90',
                    'photo-1546435770-a3e426bf472b',
                    'photo-1577174881658-0f30ed549adc',
                ],
            ],
            [
                'id' => 13,
                'category_name' => 'Electronics',
                'name' => 'Smart Fitness Watch Series 6',
                'slug' => 'smart-fitness-watch-series-6',
                'short_description' => 'Stay on top of your health and fitness with this premium smartwatch.',
                'description' => 'Smart Fitness Watch Series 6 tracking heart rate, steps, sleep, oxygen levels, and workouts. Sleek AMOLED display with custom watch faces and up to 7 days of battery life.',
                'price' => 4299.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 870,
                'images' => ['photo-1523275335684-37898b6baf30'],
            ],
            [
                'id' => 12,
                'category_name' => 'Fashion',
                'name' => 'Classic Leather Sneakers',
                'slug' => 'classic-leather-sneakers',
                'short_description' => 'Minimalist leather sneakers designed for daily comfort and style.',
                'description' => 'Crafted with premium full-grain leather, these classic sneakers are perfect for casual wear. Stitched rubber cupsole offers great durability and grip.',
                'price' => 2999.00,
                'compare_at_price' => 3999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 760,
                'images' => ['photo-1542291026-7eec264c27ff'],
            ],
            [
                'id' => 11,
                'category_name' => 'Fashion',
                'name' => 'Premium Sunglasses UV400',
                'slug' => 'premium-sunglasses-uv400',
                'short_description' => 'Modern stylish sunglasses offering 100% UV400 protection.',
                'description' => 'Protect your eyes in style with these premium sunglasses. Features a durable lightweight frame, impact-resistant lenses, and scratch-resistant coating.',
                'price' => 1599.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 540,
                'images' => ['photo-1572635196237-14b3f281503f'],
            ],
            [
                'id' => 10,
                'category_name' => 'Fashion',
                'name' => 'Minimalist Backpack 20L',
                'slug' => 'minimalist-backpack-20l',
                'short_description' => 'Waterproof travel and school backpack with a 15.6-inch laptop compartment.',
                'description' => 'This 20L minimalist backpack is perfect for work, school, or travel. Made from durable waterproof polyester with multiple internal organization compartments.',
                'price' => 2499.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 320,
                'images' => ['photo-1553062407-98eeb64c6a62'],
            ],
            [
                'id' => 9,
                'category_name' => 'Home & Living',
                'name' => 'Ceramic Pour-Over Coffee Set',
                'slug' => 'ceramic-pour-over-coffee-set',
                'short_description' => 'Handcrafted ceramic coffee dripper and server for manual brewing.',
                'description' => 'Elevate your morning coffee routine. Features a double-walled ceramic dripper for heat retention and a heat-resistant glass carafe server.',
                'price' => 1899.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 210,
                'images' => ['photo-1495774856032-8b90bbb32b32'],
            ],
            [
                'id' => 8,
                'category_name' => 'Electronics',
                'name' => 'Mechanical Keyboard RGB',
                'slug' => 'mechanical-keyboard-rgb',
                'short_description' => 'Tactile, responsive mechanical keyboard with customizable RGB backlighting.',
                'description' => 'Full-sized mechanical keyboard featuring blue switches for a satisfying click, double-shot injection keycaps, and multiple dynamic RGB lighting profiles.',
                'price' => 5499.00,
                'compare_at_price' => 6299.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 430,
                'images' => ['photo-1587829741301-dc798b83add3'],
            ],
            [
                'id' => 7,
                'category_name' => 'Fashion',
                'name' => 'Cotton Oversized T-Shirt',
                'slug' => 'cotton-oversized-t-shirt',
                'short_description' => 'Super soft, 100% organic cotton oversized streetwear t-shirt.',
                'description' => 'Heavyweight organic cotton t-shirt with a relaxed boxy fit and durable ribbed collar. Designed for everyday streetwear comfort.',
                'price' => 899.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 150,
                'images' => ['photo-1521572163474-6864f9cf17ab'],
            ],
            [
                'id' => 6,
                'category_name' => 'Electronics',
                'name' => 'Portable Bluetooth Speaker',
                'slug' => 'portable-bluetooth-speaker',
                'short_description' => 'IPX7 waterproof portable speaker with rich bass and crystal clear sound.',
                'description' => 'Take your music anywhere. With 20W dual drivers, passive bass radiators, and up to 24 hours of playback time, this speaker is built for parties and outdoors.',
                'price' => 2199.00,
                'compare_at_price' => 2799.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 620,
                'images' => ['photo-1608043152269-423dbba4e7e1'],
            ],
            [
                'id' => 5,
                'category_name' => 'Electronics',
                'name' => 'Wireless Earbuds Pro',
                'slug' => 'wireless-earbuds-pro',
                'short_description' => 'True wireless earbuds with touch control and smart ANC charging case.',
                'description' => 'Experience true audio freedom. Designed with Bluetooth 5.2, active environment noise isolation, and smart fast charging supporting up to 30 hours total battery life.',
                'price' => 3499.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 810,
                'images' => ['photo-1590658268037-6bf12165a8df'],
            ],
            [
                'id' => 4,
                'category_name' => 'Home & Living',
                'name' => 'Scented Soy Candle Set',
                'slug' => 'scented-soy-candle-set',
                'short_description' => 'A set of 4 aromatherapy soy candles with calming scents.',
                'description' => 'Made from 100% natural eco-friendly soy wax and premium therapeutic essential oils. Scents include Lavender, Rosemary, Vanilla, and Jasmine.',
                'price' => 749.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 280,
                'images' => ['photo-1602874801007-bd458bb1b8b6'],
            ],
            [
                'id' => 3,
                'category_name' => 'Beauty',
                'name' => 'Matte Lipstick Collection',
                'slug' => 'matte-lipstick-collection',
                'short_description' => 'Long-lasting, moisturizing matte lipsticks in 6 stunning shades.',
                'description' => 'Stunning matte formulation that keeps lips hydrated and rich with pigment all day long. Transfer-proof and smudge-free formula.',
                'price' => 1299.00,
                'compare_at_price' => 1699.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 360,
                'images' => ['photo-1586495777744-4413f21062fa'],
            ],
            [
                'id' => 2,
                'category_name' => 'Sports',
                'name' => 'Yoga Mat Non-Slip',
                'slug' => 'yoga-mat-non-slip',
                'short_description' => 'Eco-friendly TPE non-slip fitness and yoga mat with carry strap.',
                'description' => 'Extra-thick 6mm TPE mat offering optimal cushioning and joint support. Textured non-slip surface provides great grip for complex yoga poses.',
                'price' => 1450.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 290,
                'images' => ['photo-1601925260368-ae2f83cf8b7f'],
            ],
            [
                'id' => 1,
                'category_name' => 'Sports',
                'name' => 'Stainless Steel Water Bottle',
                'slug' => 'stainless-steel-water-bottle',
                'short_description' => 'Double-walled vacuum insulated water bottle (750ml).',
                'description' => 'Keeps beverages cold for up to 24 hours or hot for up to 12 hours. Made with food-grade 18/8 stainless steel and BPA-free leak-proof lid.',
                'price' => 999.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 470,
                'images' => ['photo-1602143407151-7111542de6e8'],
            ],
            // New Products
            [
                'id' => 15,
                'category_name' => 'Gadgets',
                'name' => 'Wireless Charging Pad 15W',
                'slug' => 'wireless-charging-pad-15w',
                'short_description' => 'Fast 15W Qi-compatible wireless charging pad for all devices.',
                'description' => 'Universal wireless charger with 15W fast charging capability. Compatible with iPhone, Samsung, and all Qi-enabled devices. LED indicator and anti-slip design.',
                'price' => 1299.00,
                'compare_at_price' => 1799.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 530,
                'images' => ['photo-1586810724476-c294fb7ac01b', 'photo-1610792515862-4be0a1febd95'],
            ],
            [
                'id' => 16,
                'category_name' => 'Gadgets',
                'name' => 'Smartphone Camera Lens Kit',
                'slug' => 'smartphone-camera-lens-kit',
                'short_description' => '3-in-1 smartphone lens kit with macro, wide-angle, and fisheye.',
                'description' => 'Transform your smartphone photography with this professional lens kit. Includes 12x macro lens, 120° wide-angle, and 185° fisheye lens. Premium aluminum alloy construction.',
                'price' => 999.00,
                'compare_at_price' => 1599.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 280,
                'images' => ['photo-1596178060671-7a80dc8059ea'],
            ],
            [
                'id' => 17,
                'category_name' => 'Food & Beverages',
                'name' => 'Organic Matcha Green Tea 100g',
                'slug' => 'organic-matcha-green-tea-100g',
                'short_description' => 'Premium organic matcha green tea powder from Japan.',
                'description' => 'High-quality ceremonial grade matcha from the Uji region of Japan. Rich in antioxidants, L-theanine, and natural caffeine. Perfect for tea ceremonies or smoothies.',
                'price' => 749.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 410,
                'images' => ['photo-1592301446407-d8e4a8f87cb9'],
            ],
            [
                'id' => 18,
                'category_name' => 'Food & Beverages',
                'name' => 'Premium Assorted Chocolate Box',
                'slug' => 'premium-assorted-chocolate-box',
                'short_description' => 'Luxurious assortment of handcrafted Belgian chocolates.',
                'description' => 'Elegant gift box featuring 36 pieces of artisanal Belgian chocolate. Includes dark, milk, and white chocolate varieties with gourmet fillings. Perfect for gifting.',
                'price' => 1899.00,
                'compare_at_price' => 2499.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 350,
                'images' => ['photo-1549007953-2b2d07c232f5'],
            ],
            [
                'id' => 19,
                'category_name' => 'Toys & Games',
                'name' => 'Building Blocks 1500 Pcs',
                'slug' => 'building-blocks-1500-pcs',
                'short_description' => 'Massive creative building block set with storage case.',
                'description' => 'This versatile building block set includes 1500 pieces in various colors and sizes. Includes instruction book to build 20+ different models. Develops creativity and problem-solving skills.',
                'price' => 3499.00,
                'compare_at_price' => 4999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 290,
                'images' => ['photo-1558060370-d6443f2b4d36', 'photo-1587654780291-39c9404d746b'],
            ],
            [
                'id' => 20,
                'category_name' => 'Toys & Games',
                'name' => 'Remote Control Drone 4K',
                'slug' => 'remote-control-drone-4k',
                'short_description' => '4K camera drone with GPS, altitude hold, and 20min flight time.',
                'description' => 'Professional-grade drone with 4K ultra HD camera, GPS positioning, brushless motors, and one-key return. Perfect for aerial photography and beginners.',
                'price' => 8999.00,
                'compare_at_price' => 11999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 180,
                'images' => ['photo-1507582020474-9a35b7d455d9', 'photo-1524143986875-3b098d78b363'],
            ],
            [
                'id' => 21,
                'category_name' => 'Health & Wellness',
                'name' => 'Professional Blood Pressure Monitor',
                'slug' => 'professional-blood-pressure-monitor',
                'short_description' => 'Clinical-grade automatic blood pressure monitor with large display.',
                'description' => 'Easy-to-use automatic blood pressure monitor with large LCD display. Tracks systolic, diastolic pressure, and pulse. Includes irregular heartbeat detection and memory for 2 users.',
                'price' => 3599.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 160,
                'images' => ['photo-1527612820672-5b56351f7346'],
            ],
            [
                'id' => 22,
                'category_name' => 'Health & Wellness',
                'name' => 'Aromatherapy Essential Oil Set',
                'slug' => 'aromatherapy-essential-oil-set',
                'short_description' => '6 pure therapeutic essential oils with diffuser.',
                'description' => 'Includes lavender, peppermint, eucalyptus, tea tree, lemon, and orange essential oils. 100% pure, natural, and cruelty-free. Comes with a sleek ultrasonic diffuser.',
                'price' => 999.00,
                'compare_at_price' => 1499.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 440,
                'images' => ['photo-1608571423902-eed4a5ad8108'],
            ],
            [
                'id' => 23,
                'category_name' => 'Books',
                'name' => 'The Art of Coding 2nd Edition',
                'slug' => 'the-art-of-coding-2nd-edition',
                'short_description' => 'Comprehensive guide to modern software development practices.',
                'description' => 'The ultimate guide for software developers. Covers best practices, design patterns, clean code principles, and modern architecture. Includes real-world examples and case studies.',
                'price' => 799.00,
                'compare_at_price' => 999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 310,
                'images' => ['photo-1532012197267-da84d127e765'],
            ],
            [
                'id' => 24,
                'category_name' => 'Beauty',
                'name' => 'Vitamin C Serum 30ml',
                'slug' => 'vitamin-c-serum-30ml',
                'short_description' => 'Brightening and anti-aging vitamin C serum with hyaluronic acid.',
                'description' => 'Potent vitamin C serum with 20% pure vitamin C and hyaluronic acid. Reduces dark spots, fine lines, and promotes collagen production. Suitable for all skin types.',
                'price' => 899.00,
                'compare_at_price' => 1299.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 520,
                'images' => ['photo-1598440947619-2c35fc9aa908'],
            ],
            [
                'id' => 25,
                'category_name' => 'Home & Living',
                'name' => 'Smart LED Light Bulbs 4 Pack',
                'slug' => 'smart-led-light-bulbs-4-pack',
                'short_description' => 'WiFi-enabled smart LED bulbs with RGB and app control.',
                'description' => 'Set of 4 smart LED bulbs compatible with Alexa and Google Home. Control brightness, color, and schedules through the app. Energy-efficient with 16M colors.',
                'price' => 1499.00,
                'compare_at_price' => 2499.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 390,
                'images' => ['photo-1563299796-17596ed6b017'],
            ],
        ];

        $seededProducts = [];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_name']];

            $product = Product::create([
                'id' => $pData['id'],
                'category_id' => $cat->id,
                'name' => $pData['name'],
                'slug' => $pData['slug'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'price' => $pData['price'],
                'compare_at_price' => $pData['compare_at_price'],
                'stock_status' => $pData['stock_status'],
                'is_best_seller' => $pData['is_best_seller'],
                'is_featured' => $pData['is_featured'],
                'is_active' => $pData['is_active'],
                'sold_count' => $pData['sold_count'],
            ]);

            $seededProducts[] = $product;

            // Seed associated images
            foreach ($pData['images'] as $index => $imgCode) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgCode,
                    'alt_text' => $product->name.' - Image '.($index + 1),
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        // 5. Seed Coupons - Added more coupons
        $coupons = [
            [
                'code' => 'SAVE10',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 500,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME500',
                'type' => 'fixed',
                'value' => 500,
                'min_order_amount' => 3000,
                'starts_at' => now(),
                'expires_at' => now()->addDays(90),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 60,
                'min_order_amount' => 1000,
                'starts_at' => now(),
                'expires_at' => now()->addDays(15),
                'usage_limit' => 200,
                'used_count' => 12,
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'type' => 'percent',
                'value' => 20,
                'min_order_amount' => 2500,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addDays(25),
                'usage_limit' => 150,
                'used_count' => 28,
                'is_active' => true,
            ],
            [
                'code' => 'FLASH15',
                'type' => 'percent',
                'value' => 15,
                'min_order_amount' => 1000,
                'starts_at' => now()->addDays(2),
                'expires_at' => now()->addDays(5),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'LOYALTY100',
                'type' => 'fixed',
                'value' => 100,
                'min_order_amount' => 500,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addDays(40),
                'usage_limit' => 300,
                'used_count' => 45,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        // 6. Seed Reviews - More reviews for all products
        $reviewComments = [
            'Excellent product! Highly recommend.',
            'Good quality for the price.',
            'Fast delivery and great packaging.',
            'Works as expected. Very satisfied.',
            'Not as good as I expected but okay.',
            'Absolutely love it! Will buy again.',
            'Average product. Nothing special.',
            'Outstanding quality and value.',
            'The best purchase I\'ve made this year.',
            'Good but could be improved.',
            'Perfect for my needs. Highly satisfied.',
            'Delivery was quick and product is great.',
            'Excellent customer service too.',
            'Value for money. Recommended.',
            'Very happy with this purchase.',
        ];

        // Seed specific product reviews (for Product 14)
        $prod14 = Product::find(14);
        if ($prod14) {
            $reviews14 = [
                [
                    'name' => 'Rina A.',
                    'rating' => 5,
                    'comment' => 'Sound quality is amazing and the noise cancellation actually works on the bus. Battery lasts me almost a week. Highly recommended!',
                    'date' => now()->subDays(36),
                ],
                [
                    'name' => 'Karim H.',
                    'rating' => 5,
                    'comment' => 'Delivered fast inside Dhaka, paid cash on delivery. Build quality feels premium for the price.',
                    'date' => now()->subDays(43),
                ],
                [
                    'name' => 'Sadia R.',
                    'rating' => 4,
                    'comment' => 'Very comfortable for long use. Only wish the case was a bit smaller, but overall great value.',
                    'date' => now()->subDays(51),
                ],
                [
                    'name' => 'Tamim I.',
                    'rating' => 5,
                    'comment' => 'Best headphones I\'ve ever owned! The bass is incredible and the battery lasts forever.',
                    'date' => now()->subDays(28),
                ],
                [
                    'name' => 'Nusrat F.',
                    'rating' => 4,
                    'comment' => 'Great sound quality but the noise cancellation could be a bit stronger.',
                    'date' => now()->subDays(15),
                ],
            ];

            foreach ($reviews14 as $revData) {
                $revUser = User::factory()->create([
                    'name' => $revData['name'],
                    'email' => Str::slug($revData['name']).'@example.com',
                ]);

                Review::create([
                    'product_id' => $prod14->id,
                    'user_id' => $revUser->id,
                    'rating' => $revData['rating'],
                    'comment' => $revData['comment'],
                    'is_approved' => true,
                    'approved_at' => $revData['date'],
                    'created_at' => $revData['date'],
                    'updated_at' => $revData['date'],
                ]);
            }
        }

        // Seed random reviews for other products (more reviews)
        foreach ($seededProducts as $prod) {
            if ($prod->id === 14) {
                continue;
            }
            // Create 3-6 reviews per product
            $numReviews = rand(3, 6);
            $reviewerUsers = $users->random(min($numReviews, $users->count()));

            $reviewIndex = 0;
            foreach ($reviewerUsers as $revUser) {
                $rating = rand(3, 5);
                $date = now()->subDays(rand(5, 60));

                Review::create([
                    'product_id' => $prod->id,
                    'user_id' => $revUser->id,
                    'rating' => $rating,
                    'comment' => $reviewComments[array_rand($reviewComments)],
                    'is_approved' => true,
                    'approved_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                $reviewIndex++;
            }
        }

        // 7. Seed Wishlists - More wishlist items
        foreach ($allUsers as $usr) {
            // Add 3-6 random products to wishlist
            $wishProducts = collect($seededProducts)->random(rand(3, 6));
            foreach ($wishProducts as $p) {
                Wishlist::create([
                    'user_id' => $usr->id,
                    'product_id' => $p->id,
                ]);
            }
        }

        // 8. Seed Carts & Cart Items
        foreach ($allUsers as $usr) {
            $cart = Cart::create([
                'user_id' => $usr->id,
            ]);

            // Add 2-4 random products to cart
            $cartProducts = collect($seededProducts)->random(rand(2, 4));
            foreach ($cartProducts as $p) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $p->id,
                    'quantity' => rand(1, 3),
                ]);
            }
        }

        // 9. Seed Orders & Order Items - More orders
        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];
        $paymentMethods = ['cod', 'bkash', 'nagad', 'credit_card'];
        $paymentStatuses = ['initiated', 'completed', 'failed', 'refunded'];
        $districts = ['Dhaka', 'Chattogram', 'Khulna', 'Rajshahi', 'Sylhet', 'Barishal', 'Rangpur', 'Mymensingh'];
        $areas = ['Mirpur', 'Gulshan', 'Banani', 'Dhanmondi', 'Mohammadpur', 'Uttara', 'Motijheel', 'Paltan', 'Jatrabari'];

        $couponIds = Coupon::pluck('id')->toArray();

        foreach ($allUsers as $usr) {
            // Create 3-5 orders per user
            for ($o = 0; $o < rand(3, 5); $o++) {
                $numProducts = rand(1, 4);
                $orderedProducts = collect($seededProducts)->random($numProducts);
                $subtotal = 0;

                $orderItems = [];
                foreach ($orderedProducts as $p) {
                    $qty = rand(1, 3);
                    $subtotal += $p->price * $qty;
                    $orderItems[] = [
                        'product' => $p,
                        'quantity' => $qty,
                    ];
                }

                // Apply random coupon
                $couponId = (rand(1, 3) < 2) ? $couponIds[array_rand($couponIds)] : null;
                $discount = 0;
                if ($couponId) {
                    $coupon = Coupon::find($couponId);
                    if ($coupon && $subtotal >= $coupon->min_order_amount) {
                        if ($coupon->type === 'percent') {
                            $discount = min($subtotal * ($coupon->value / 100), 1000);
                        } else {
                            $discount = min($coupon->value, $subtotal);
                        }
                    }
                }

                $delivery = (rand(1, 5) < 2) ? 0 : 60; // Sometimes free shipping
                $total = $subtotal - $discount + $delivery;

                $status = $orderStatuses[array_rand($orderStatuses)];
                $placedAt = now()->subDays(rand(1, 45));

                // Adjust status based on order age
                if ($placedAt->diffInDays(now()) > 30) {
                    $status = 'completed';
                } elseif ($placedAt->diffInDays(now()) > 15) {
                    $status = 'delivered';
                }

                $order = Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                    'user_id' => $usr->id,
                    'customer_name' => $usr->name,
                    'phone' => '017'.rand(10000000, 99999999),
                    'email' => $usr->email,
                    'district' => $districts[array_rand($districts)],
                    'area' => $areas[array_rand($areas)],
                    'address' => 'House '.rand(1, 99).', Road '.rand(1, 20).', Block '.chr(rand(65, 75)),
                    'notes' => rand(1, 3) < 2 ? 'Deliver in afternoon' : 'Please call before delivery',
                    'coupon_id' => $couponId,
                    'discount_amount' => $discount,
                    'subtotal' => $subtotal,
                    'delivery_charge' => $delivery,
                    'total' => $total,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                    'status' => $status,
                    'placed_at' => $placedAt,
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'unit_price' => $item['product']->price,
                        'quantity' => $item['quantity'],
                        'line_total' => $item['product']->price * $item['quantity'],
                    ]);
                }

                // Create payment
                Payment::create([
                    'order_id' => $order->id,
                    'gateway' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_id' => rand(1, 3) < 2 ? 'TXN_'.strtoupper(Str::random(16)) : null,
                    'val_id' => rand(1, 3) < 2 ? 'VAL_'.strtoupper(Str::random(12)) : null,
                    'amount' => $total,
                    'currency' => 'BDT',
                    'status' => $paymentStatuses[array_rand($paymentStatuses)],
                ]);

                // Create order status history
                $historyStatuses = ['pending'];
                if ($status !== 'pending') {
                    $historyStatuses[] = 'confirmed';
                    if ($status === 'processing' || $status === 'shipped' || $status === 'delivered' || $status === 'completed') {
                        $historyStatuses[] = 'processing';
                    }
                    if ($status === 'shipped' || $status === 'delivered' || $status === 'completed') {
                        $historyStatuses[] = 'shipped';
                    }
                    if ($status === 'delivered' || $status === 'completed') {
                        $historyStatuses[] = 'delivered';
                    }
                    if ($status === 'completed') {
                        $historyStatuses[] = 'completed';
                    }
                }
                if ($status === 'cancelled') {
                    $historyStatuses[] = 'cancelled';
                }

                $historyNotes = [
                    'Order placed successfully.',
                    'Order confirmed by admin.',
                    'Order is being processed.',
                    'Order has been shipped.',
                    'Order delivered successfully.',
                    'Order marked as completed.',
                    'Order cancelled by customer.',
                ];

                $historyIndex = 0;
                foreach ($historyStatuses as $hStatus) {
                    $note = '';
                    switch ($hStatus) {
                        case 'pending':
                            $note = 'Order placed successfully.';
                            break;
                        case 'confirmed':
                            $note = 'Order confirmed by admin.';
                            break;
                        case 'processing':
                            $note = 'Order is being processed.';
                            break;
                        case 'shipped':
                            $note = 'Order has been shipped.';
                            break;
                        case 'delivered':
                            $note = 'Order delivered successfully.';
                            break;
                        case 'completed':
                            $note = 'Order marked as completed.';
                            break;
                        case 'cancelled':
                            $note = 'Order cancelled by customer.';
                            break;
                    }

                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => $hStatus,
                        'note' => $note,
                        'changed_by' => rand(1, 3) < 2 ? $admin->id : $superAdmin->id,
                        'created_at' => $placedAt->addHours($historyIndex * 2),
                        'updated_at' => $placedAt->addHours($historyIndex * 2),
                    ]);
                    $historyIndex++;
                }
            }
        }

        // Add some completed orders with delivery confirmation
        $completedOrders = Order::where('status', 'completed')->get();
        foreach ($completedOrders as $order) {
            // Update payment status to completed
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment && $payment->status !== 'completed') {
                $payment->update(['status' => 'completed']);
            }

            // Add delivery confirmation
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'completed',
                'note' => 'Order completed. Thank you for shopping with ShopEase!',
                'changed_by' => $admin->id,
                'created_at' => $order->placed_at->addDays(rand(3, 7)),
                'updated_at' => $order->placed_at->addDays(rand(3, 7)),
            ]);
        }
    }
}

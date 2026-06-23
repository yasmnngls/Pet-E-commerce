<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('categories')->insertOrIgnore([
            ['id' => 1, 'name' => 'Dog', 'slug' => 'dog', 'type' => 'pet', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Cat', 'slug' => 'cat', 'type' => 'pet', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Fish', 'slug' => 'fish', 'type' => 'pet', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Bird', 'slug' => 'bird', 'type' => 'pet', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insertOrIgnore([
            ['id' => 1, 'name' => 'Seller Admin', 'email' => 'seller@example.com', 'password' => Hash::make('password'), 'role' => 'seller', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Customer One', 'email' => 'buyer1@example.com', 'password' => Hash::make('password'), 'role' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Customer Two', 'email' => 'buyer2@example.com', 'password' => Hash::make('password'), 'role' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Customer Three', 'email' => 'buyer3@example.com', 'password' => Hash::make('password'), 'role' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Customer Four', 'email' => 'buyer4@example.com', 'password' => Hash::make('password'), 'role' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Customer Five', 'email' => 'buyer5@example.com', 'password' => Hash::make('password'), 'role' => 'customer', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insertOrIgnore([
            ['id' => 1, 'name' => 'Dog Chew Toy', 'slug' => 'dog-chew-toy-1', 'category_id' => 1, 'seller_id' => 1, 'description' => 'Durable chew toy for dogs.', 'price' => 499.00, 'stock_quantity' => 25, 'image' => 'images/pet1.jpg', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Cat Scratching Post', 'slug' => 'cat-scratching-post-2', 'category_id' => 2, 'seller_id' => 1, 'description' => 'Premium scratching post for cats.', 'price' => 799.00, 'stock_quantity' => 18, 'image' => 'images/pet2.jpg', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Fish Tank Decor', 'slug' => 'fish-tank-decor-3', 'category_id' => 3, 'seller_id' => 1, 'description' => 'Colorful aquarium decorations.', 'price' => 299.00, 'stock_quantity' => 40, 'image' => 'images/pet3.jpg', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Bird Cage Swing', 'slug' => 'bird-cage-swing-4', 'category_id' => 4, 'seller_id' => 1, 'description' => 'Safe swing for pet birds.', 'price' => 349.00, 'stock_quantity' => 15, 'image' => 'images/pet4.jpg', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Pet Grooming Brush', 'slug' => 'pet-grooming-brush-5', 'category_id' => 1, 'seller_id' => 1, 'description' => 'Soft grooming brush for all pets.', 'price' => 249.00, 'stock_quantity' => 30, 'image' => 'images/pet5.jpg', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('addresses')->insertOrIgnore([
            ['id' => 1, 'user_id' => 2, 'label' => 'Home', 'full_name' => 'Customer One', 'phone' => '09171234567', 'street' => '123 Elm St', 'barangay' => 'Central', 'city' => 'Manila', 'province' => 'Metro Manila', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 3, 'label' => 'Office', 'full_name' => 'Customer Two', 'phone' => '09179876543', 'street' => '456 Pine Ave', 'barangay' => 'North', 'city' => 'Quezon City', 'province' => 'Metro Manila', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'user_id' => 4, 'label' => 'Home', 'full_name' => 'Customer Three', 'phone' => '09172345678', 'street' => '789 Oak Drive', 'barangay' => 'West', 'city' => 'Pasig', 'province' => 'Metro Manila', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'user_id' => 5, 'label' => 'Apt', 'full_name' => 'Customer Four', 'phone' => '09173456789', 'street' => '321 Maple Rd', 'barangay' => 'East', 'city' => 'Makati', 'province' => 'Metro Manila', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'user_id' => 6, 'label' => 'Home', 'full_name' => 'Customer Five', 'phone' => '09174567890', 'street' => '654 Cedar Lane', 'barangay' => 'South', 'city' => 'Taguig', 'province' => 'Metro Manila', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $orderIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $buyerId = $i % 5 + 2;
            $addressId = $buyerId - 1;
            $orderIds[] = DB::table('orders')->insertGetId([
                'order_number' => 'ORD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $buyerId,
                'address_id' => $addressId,
                'total' => 0,
                'status' => $i <= 5 ? ($i === 2 ? 'shipped' : 'pending') : 'delivered',
                'payment_method' => 'Credit Card',
                'tracking_number' => $i === 2 ? 'TRK' . rand(100000, 999999) : null,
                'created_at' => now()->subDays(11 - $i),
                'updated_at' => now()->subDays(11 - $i),
            ]);
        }

        $deliveredRevenue = 0;
        for ($i = 1; $i <= 50; $i++) {
            DB::table('order_items')->insert([
                'order_id' => $orderIds[5 + (int) floor(($i - 1) / 10)],
                'item_id' => ($i % 5) + 1,
                'item_type' => 'App\\Models\\Product',
                'price' => 2000.00,
                'quantity' => 1,
                'seller_id' => 1,
                'status' => 'delivered',
                'created_at' => now()->subDays(11 - ($i % 10)),
                'updated_at' => now()->subDays(11 - ($i % 10)),
            ]);
            $deliveredRevenue += 2000.00;
        }

        $pendingOrders = [
            ['order' => $orderIds[0], 'product_id' => 1, 'status' => 'pending', 'price' => 1000.00, 'quantity' => 1],
            ['order' => $orderIds[1], 'product_id' => 2, 'status' => 'pending', 'price' => 1000.00, 'quantity' => 1],
            ['order' => $orderIds[2], 'product_id' => 3, 'status' => 'shipped', 'price' => 1000.00, 'quantity' => 1],
            ['order' => $orderIds[3], 'product_id' => 4, 'status' => 'pending', 'price' => 1000.00, 'quantity' => 1],
            ['order' => $orderIds[4], 'product_id' => 5, 'status' => 'pending', 'price' => 1000.00, 'quantity' => 1],
        ];

        foreach ($pendingOrders as $item) {
            DB::table('order_items')->insert([
                'order_id' => $item['order'],
                'item_id' => $item['product_id'],
                'item_type' => 'App\\Models\\Product',
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'seller_id' => 1,
                'status' => $item['status'],
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ]);
        }

        DB::table('orders')->whereIn('id', [$orderIds[0], $orderIds[1], $orderIds[2], $orderIds[3], $orderIds[4]])->update(['status' => 'pending']);

        DB::table('seller_applications')->insertOrIgnore([
            'id' => 1,
            'user_id' => 1,
            'store_name' => 'PowerPuff Pets Shop',
            'store_type' => 'Individual',
            'legal_name' => 'Seller Admin',
            'business_address' => '123 Seller Street, Metro Manila',
            'customer_support_contact' => 'seller@example.com',
            'bank_name' => 'Metrobank',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Seller Admin',
            'product_categories' => json_encode(['Dog', 'Cat', 'Fish', 'Bird']),
            'shipping_methods' => json_encode(['Courier', 'Pickup']),
            'terms_accepted' => true,
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('withdrawals')->insertOrIgnore([
            'id' => 1,
            'seller_id' => 1,
            'amount' => 50000.00,
            'withdrawal_method' => 'Bank Transfer',
            'status' => 'completed',
            'notes' => 'Seeded withdrawal for testing',
            'requested_at' => now()->subDays(2),
            'processed_at' => now()->subDay(),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDay(),
        ]);
    }
}

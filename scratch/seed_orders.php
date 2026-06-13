<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

if (Order::count() === 0) {
    $users = User::all();
    if ($users->isEmpty()) {
        $users = collect([
            User::create(['name' => 'Juan Perez', 'email' => 'juan@example.com', 'password' => bcrypt('password')]),
            User::create(['name' => 'Maria Gomez', 'email' => 'maria@example.com', 'password' => bcrypt('password')])
        ]);
    }

    $products = Product::take(5)->get();
    
    $statuses = ['Pendiente', 'Pagado', 'Enviado'];
    
    foreach(range(1, 10) as $i) {
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => $users->random()->id,
            'total' => rand(5000, 25000),
            'status' => $statuses[array_rand($statuses)],
        ]);
        
        if ($products->isNotEmpty()) {
            foreach(range(1, rand(1, 3)) as $j) {
                $product = $products->random();
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => rand(1, 3),
                    'price' => $product->price,
                ]);
            }
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null,
                'product_name' => 'Perfume Generico ' . $i,
                'quantity' => rand(1, 3),
                'price' => rand(2000, 5000),
            ]);
        }
    }
    echo "Orders seeded successfully.";
} else {
    echo "Orders already exist.";
}

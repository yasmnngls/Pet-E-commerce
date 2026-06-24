<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (App\Models\Product::whereNotNull('image')->take(5)->get() as $p) {
    echo $p->id . ' | ' . $p->image . PHP_EOL;
}

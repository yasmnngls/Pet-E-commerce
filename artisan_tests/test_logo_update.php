<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SellerApplication;

try {
    echo "Running schema check for seller_applications.logo_path\n";
    $res = \DB::select("select column_name from information_schema.columns where table_name='seller_applications' and column_name='logo_path'");
    var_export($res);
    echo "\n";

    $id = 13; // adjust if needed
    $store = SellerApplication::find($id);
    if (!$store) {
        echo "No SellerApplication with id={$id} found. Trying first()...\n";
        $store = SellerApplication::first();
    }
    if (!$store) {
        echo "No SellerApplication records exist. Exiting.\n";
        exit(1);
    }

    echo "Found SellerApplication id={$store->id}, current logo_path='" . ($store->logo_path ?? 'NULL') . "'\n";

    $new = 'storage/test-logo-' . time() . '.jpg';
    $store->logo_path = $new;
    $updated = $store->save();
    echo "Save returned: "; var_export($updated); echo "\n";

    $fresh = SellerApplication::find($store->id);
    echo "After save, logo_path='" . ($fresh->logo_path ?? 'NULL') . "'\n";

    echo "Done.\n";
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

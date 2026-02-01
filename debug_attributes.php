<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin\Product;
use App\Models\Admin\Attribute;

$name = "Organic Bamboo Pajama Set";
$product = Product::where('name', 'like', "%$name%")->first();

if (!$product) {
    echo "Product not found: $name\n";
    exit;
}

echo "Product ID: " . $product->id . "\n";
echo "Product Name: " . $product->name . "\n";
echo "Attribute Values (Raw): " . $product->attribute_values . "\n";
echo "Attribute Values (Type): " . gettype($product->attribute_values) . "\n";

if ($product->attribute_values) {
    $attributeData = json_decode($product->attribute_values, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON Decode Error: " . json_last_error_msg() . "\n";
    }
    echo "Decoded Attribute Data: " . print_r($attributeData, true) . "\n";

    if (is_array($attributeData)) {
        $attrIds = array_keys($attributeData);
        echo "Attribute IDs: " . implode(', ', $attrIds) . "\n";

        $attributeNames = Attribute::whereIn('id', $attrIds)->pluck('name', 'id');
        echo "Attribute Names: " . print_r($attributeNames->toArray(), true) . "\n";
    } else {
        echo "Attribute Data is not an array after decode.\n";
    }
}

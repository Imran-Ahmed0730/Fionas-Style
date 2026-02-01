<?php

namespace App\Services\Admin;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Brand;
use App\Models\Admin\Unit;
use App\Models\Admin\Color;
use App\Models\Admin\Attribute;
use App\Models\Admin\ProductImage;
use App\Models\Admin\AttributeValue;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\ProductStock;
use Illuminate\Support\Str;

class ProductService
{
    public function getProductAttributes()
    {
        $data['categories'] = $this->getActiveCategories();
        $data['brands'] = $this->getActiveBrands();
        $data['units'] = $this->getActiveUnits();
        $data['colors'] = $this->getColors();
        $data['attributes'] = $this->getActiveAttributes();
        return $data;
    }
    public function getById($id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        $data['slug'] = str()->slug($data['name']) . '-' . uniqid();

        // Handle images
        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], null, 'product/thumbnail');
        }

        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], null, 'product/meta-image');
        }

        // Map and unset non-db or differently named fields
        if (isset($data['shipping_time'])) {
            $data['shipping_duration'] = $data['shipping_time'];
            unset($data['shipping_time']);
        }
        if (isset($data['shipping_return_policy'])) {
            $data['shipping_and_return_policy'] = $data['shipping_return_policy'];
            unset($data['shipping_return_policy']);
        }
        if (isset($data['color_id'])) {
            $data['color'] = json_encode($data['color_id']);
            unset($data['color_id']);
        }
        if (isset($data['attribute_value_id'])) {
            $data['attribute_values'] = json_encode($data['attribute_value_id']);
            unset($data['attribute_value_id']);
        }

        // attributes
        unset($data['attribute_id']);

        // Handle variants flag
        $variants = $data['variant'] ?? [];
        $data['is_variant'] = count($variants) > 0 ? 1 : 0;
        unset($data['variant']); // Will manually store later

        // Remove gallery images from main create to handle separately
        $galleryImages = $data['images'] ?? [];
        unset($data['images']);

        $product = Product::create($data);

        // Store gallery images
        if (count($galleryImages) > 0) {
            $this->storeImages($product, $galleryImages);
        }

        // Store variants
        if (count($variants) > 0) {
            foreach ($variants as $v) {
                $v['selling_price'] = $data['discount_type'] == 1 ? $v['price'] - $data['discount'] : $v['price'] - ($v['price'] * $data['discount'] / 100);
                $variantData = [
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'attr_name' => $v['name'], // Store variant name as attr_name
                    'sku' => $v['sku'] ?? null,
                    'regular_price' => $v['price'],
                    'selling_price' => $v['selling_price'],
                    'stock_qty' => 0,
                ];

                if (isset($v['image'])) {
                    $variantData['image'] = saveImagePath($v['image'], null, 'product/variant');
                }

                ProductVariant::create($variantData);

            }
        }

        return $product;
    }

    public function update(Product $product, array $data): bool
    {

        if (isset($data['name']) && $data['name'] != $product->name) {
            $data['slug'] = str()->slug($data['name']) . '-' . uniqid();
            ProductStock::where('product_name', $product->name)->update(['product_name' => $data['name']]);
        }

        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], $product->thumbnail, 'product/thumbnail');
        }

        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], $product->meta_image, 'product/meta-image');
        }

        // Map and unset non-db or differently named fields
        if (isset($data['shipping_time'])) {
            $data['shipping_duration'] = $data['shipping_time'];
            unset($data['shipping_time']);
        }
        if (isset($data['shipping_return_policy'])) {
            $data['shipping_and_return_policy'] = $data['shipping_return_policy'];
            unset($data['shipping_return_policy']);
        }
        if (isset($data['color_id'])) {
            $data['color'] = json_encode($data['color_id']);
            unset($data['color_id']);
        }
        if (isset($data['attribute_value_id'])) {
            $data['attribute_values'] = json_encode($data['attribute_value_id']);
            unset($data['attribute_value_id']);
        }

        // checkbox/switch fields - handle nulls from request
        $checkboxes = ['cod_available', 'include_to_todays_deal', 'is_featured', 'is_replaceable', 'is_trending'];
        foreach ($checkboxes as $cb) {
            $data[$cb] = $data[$cb] ?? 0;
        }

        // Unset temporary fields
        unset($data['attribute_id']);

        // Handle variants flag
        $newVariants = $data['variant'] ?? [];
        $existingVariantsCount = isset($data['existing_variant']) ? count($data['existing_variant']) : 0;
        $data['is_variant'] = ($newVariants || $existingVariantsCount > 0) ? 1 : 0;

        unset($data['variant']); // Will manually store later

        // Store gallery images if any
        if (isset($data['images'])) {
            $this->storeImages($product, $data['images']);
        }

        // Handle existing variants update
        if (isset($data['existing_variant'])) {
            foreach ($data['existing_variant'] as $v) {
                $variant = ProductVariant::find($v['id']);
                if ($variant) {
                    $sellingPrice = $data['discount_type'] == 1 ? $v['price'] - $data['discount'] : $v['price'] - ($v['price'] * $data['discount'] / 100);
                    $variant->update([
                        'regular_price' => $v['price'],
                        'selling_price' => $sellingPrice,
                        'sku' => $v['sku'] ?? $variant->sku,
                    ]);
                }
            }
        }

        // Handle new variants if added during update
        if (count($newVariants) > 0) {
            foreach ($newVariants as $v) {
                $v['selling_price'] = $data['discount_type'] == 1 ? $v['price'] - $data['discount'] : $v['price'] - ($v['price'] * $data['discount'] / 100);

                $variantData = [
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'attr_name' => $v['name'], // Store variant name as attr_name
                    'sku' => $v['sku'] ?? null,
                    'regular_price' => $v['price'],
                    'selling_price' => $v['selling_price'],
                    'stock_qty' => $v['stock_qty'] ?? 0,
                ];
                if (isset($v['image'])) {
                    $variantData['image'] = saveImagePath($v['image'], null, 'product/variant');
                }
                ProductVariant::create($variantData);
            }
        }

        if ($data['is_variant'] == 0) {
            ProductStock::updateOrCreate(
                ['product_name' => $product->name, 'variant_name' => null],
                [
                    'qty' => $data['stock_qty'] ?? $product->stock_qty,
                    'sku' => $data['sku'] ?? $product->sku,
                    'buying_price' => $data['buying_price'] ?? 0,
                ]
            );
        }

        return $product->update($data);
    }

    public function delete($id): bool
    {
        $product = Product::findOrFail($id);
        if ($product->thumbnail && file_exists($product->thumbnail)) {
            unlink($product->thumbnail);
        }
        // Delete gallery images
        foreach ($product->gallery as $image) {
            if ($image->image && file_exists($image->image)) {
                unlink($image->image);
            }
            $image->delete();
        }
        // Delete variants
        foreach ($product->variants as $variant) {
            if ($variant->image && file_exists($variant->image)) {
                unlink($variant->image);
            }
            $variant->delete();
        }

        $product->stocks()->delete();

        return $product->delete();
    }

    public function changeStatus(Product $product, $column): bool
    {
        $status = $product->$column == 1 ? 0 : 1;
        return $product->update([
            $column => $status
        ]);
    }

    public function storeImages(Product $product, array $images)
    {
        foreach ($images as $image) {
            $path = saveImagePath($image, null, 'product/gallery');
            $product->gallery()->create(['image' => $path]);
        }
    }

    public function getActiveCategories()
    {
        return Category::active()->where('id', '!=', 1)->orderBy('parent_id')->get();
    }

    public function getActiveBrands()
    {
        return Brand::active()->where('id', '!=', 1)->orderBy('name')->get();
    }

    public function getActiveUnits()
    {
        return Unit::active()->get();
    }

    public function getColors()
    {
        return Color::orderBy('name', 'asc')->get();
    }

    public function getActiveAttributes()
    {
        return Attribute::active()->get();
    }

    public function getAttributeValues($ids)
    {
        return Attribute::whereIn('id', (array) $ids)->with('attributeValues')->get();
    }

    public function deleteImage(ProductImage $productImage)
    {
        if (file_exists($productImage->image)) {
            unlink($productImage->image);
        }
        return $productImage->delete();
    }

    public function deleteVariant(ProductVariant $variant)
    {
        // Delete variant image if exists
        if ($variant->image && file_exists($variant->image)) {
            unlink($variant->image);
        }

        // Delete stock record for this variant
        ProductStock::where('variant_name', $variant->name)
            ->where('sku', $variant->sku)
            ->delete();

        return $variant->delete();
    }

    public function generateSku()
    {
        $sku_syntax = getSetting('product_sku_syntax');
        do {
            // Generate a SKU with format 'sk0001' to 'sk9999'
            $sku = Str::replace('[[random_number]]', '', $sku_syntax) . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Check if SKU is unique
            $isUnique = !Product::where('sku', $sku)->exists();
        } while (!$isUnique); // Retry if SKU is not unique

        return $sku;
    }
}

<?php
namespace App\DTOs\Responses;

use App\Models\Product;

class ProductDetailDTO {
    public function __construct(
        public readonly string $name,
        public readonly string $brand_name,
        public readonly string $short_desc,
        public readonly string $long_desc,
        public readonly array $images,
        public readonly array $specs,
        public readonly array $skus, // Mảng các SKU đã được định dạng
        public readonly float $rating
    ) {}

    public static function fromEntity(Product $product, string $brandName): self {
        return new self(
            name: $product->name,
            brand_name: $brandName,
            short_desc: $product->short_desc ?? '',
            long_desc: $product->long_desc ?? '',
            images: $product->images,
            specs: $product->specs,
            skus: array_map(fn($sku) => [
                'code' => $sku->sku_code,
                'price' => number_format($sku->price, 0, ',', '.') . ' ₫',
                'saving' => number_format($sku->getSavingAmount(), 0, ',', '.') . ' ₫'
            ], $product->getSkus()),
            rating: $product->rating_avg
        );
    }
}
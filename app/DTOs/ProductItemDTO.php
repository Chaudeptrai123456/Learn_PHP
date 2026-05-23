<?php
namespace App\DTOs\Responses;

use App\Models\Product;

class ProductItemDTO {
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $formatted_price,
        public readonly ?string $formatted_old_price,
        public readonly string $image_url,
        public readonly int $discount_percent,
        public readonly int $sold_count,
        public readonly string $badge
    ) {}

    /**
     * Map từ Entity sang DTO
     */
    public static function fromEntity(Product $product): self {
        $sku = $product->getDefaultSku();
        
        return new self(
            id: $product->id,
            name: $product->name,
            slug: $product->slug,
            formatted_price: number_format($sku->price, 0, ',', '.') . ' ₫',
            formatted_old_price: $sku->old_price ? number_format($sku->old_price, 0, ',', '.') . ' ₫' : null,
            image_url: $sku->image_url ?? 'default.jpg',
            discount_percent: $sku->getDiscountPercent(),
            sold_count: $sku->sold_qty,
            badge: $product->status === 'published' ? 'Bán chạy' : 'Mới'
        );
    }
}
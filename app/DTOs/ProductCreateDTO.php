<?php
namespace App\DTOs;

class ProductCreateDTO {
    /**
     * @param ProductSkuDTO[] $skus
     */
    public function __construct(
        public readonly int $category_id,
        public readonly int $brand_id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $short_desc,
        public readonly ?string $long_desc,
        public readonly float $base_price,
        public readonly array $skus
    ) {}

    public static function fromRequest(array $request): self {
        $skus = array_map(fn($item) => ProductSkuDTO::fromArray($item), $request['skus'] ?? []);

        return new self(
            category_id: (int)$request['category_id'],
            brand_id:    (int)$request['brand_id'],
            name:        $request['name'],
            slug:        $request['slug'],
            short_desc:  $request['short_description'] ?? null,
            long_desc:   $request['long_description'] ?? null,
            base_price:  (float)$request['base_price'],
            skus:        $skus
        );
    }
}
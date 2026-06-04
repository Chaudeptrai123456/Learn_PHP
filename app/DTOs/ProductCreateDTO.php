<?php
namespace App\DTOs;

class ProductCreateDTO {

    public int $category_id;
    public int $brand_id;
    public string $name;
    public string $slug;
    public ?string $short_desc;
    public ?string $long_desc;
    public float $base_price = 0;

    public array $skus = [];

    public static function fromRequest(array $request): self {

        $dto = new self();

        $dto->category_id = (int)($request['category_id'] ?? 0);
        $dto->brand_id    = (int)($request['brand_id'] ?? 0);
        $dto->name        = $request['name'] ?? '';
        $dto->slug        = $request['slug'] ?? '';
        $dto->short_desc  = $request['short_desc'] ?? null;
        $dto->long_desc   = $request['long_desc'] ?? null;
        $dto->base_price = 0;
        if (!empty($request['skus'])) {
            foreach ($request['skus'] as $item) {
                $dto->skus[] = ProductSkuDTO::fromArray($item);
            }
        }

        return $dto;
    }
}
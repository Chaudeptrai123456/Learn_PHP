<?php
namespace App\Models;

class Brand {
    public int $id;
    public string $name;
    public string $slug;
    public ?string $logo_url;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->logo_url = $data['logo_url'] ?? null;
    }
}
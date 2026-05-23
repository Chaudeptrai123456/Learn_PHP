<?php
namespace App\Models;

class Category {
    public int $id;
    public ?int $parent_id;
    public string $name;
    public string $slug;
    public bool $status;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->parent_id = $data['parent_id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->status = (bool)($data['status'] ?? 1);
    }
}
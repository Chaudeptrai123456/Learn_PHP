<?php

class OrderItem
{
    public $id;
    public $order_id;
    public $product_id;
    public $sku_id;

    public $product_name;
    public $sku_code;

    public $price;
    public $quantity;

    public $image_url;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->order_id = $data['order_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->sku_id = $data['sku_id'] ?? null;

        $this->product_name = $data['product_name'] ?? null;
        $this->sku_code = $data['sku_code'] ?? null;

        $this->price = $data['price'] ?? 0;
        $this->quantity = $data['quantity'] ?? 0;

        $this->image_url = $data['image_url'] ?? null;
    }

    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }
}
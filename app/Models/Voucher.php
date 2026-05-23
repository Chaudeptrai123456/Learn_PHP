<?php
namespace App\Models;

use DateTime;

class Voucher {
    public string $code;
    public string $type; 
    public float $value;
    public float $min_order_value;
    public ?float $max_discount;
    public ?DateTime $end_date;
    public int $usage_limit;

    public function __construct(array $data = []) {
        $this->code = $data['code'] ?? '';
        $this->type = $data['discount_type'] ?? 'fixed';
        $this->value = (float)($data['discount_value'] ?? 0);
        $this->min_order_value = (float)($data['min_order_value'] ?? 0);
        $this->max_discount = isset($data['max_discount_value']) ? (float)$data['max_discount_value'] : null;
        $this->end_date = isset($data['end_date']) ? new DateTime($data['end_date']) : null;
        $this->usage_limit = $data['usage_limit'] ?? 0;
    }
    public function isValid(): bool {
        $now = new DateTime();
        if ($this->end_date && $now > $this->end_date) return false;
        return true;
    }
    public function getDiscountAmount(float $totalAmount): float {
        if ($totalAmount < $this->min_order_value) return 0;
        $discount = 0;
        if ($this->type === 'percent') {
            $discount = ($totalAmount * $this->value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = $this->value;
        }

        return $discount;
    }
}
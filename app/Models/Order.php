<?php

class Order
{
    public $id;
    public $user_id;
    public $voucher_id;

    public $total_amount;
    public $discount_amount;
    public $final_amount;

    public $status;
    public $payment_status;
    public $payment_method;

    public $note;

    public $created_at;
    public $updated_at;

    // Constants cho dễ dùng
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';

    const METHOD_COD = 'cod';
    const METHOD_VNPAY = 'vnpay';
    const METHOD_MOMO = 'momo';

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->voucher_id = $data['voucher_id'] ?? null;

        $this->total_amount = $data['total_amount'] ?? 0;
        $this->discount_amount = $data['discount_amount'] ?? 0;
        $this->final_amount = $data['final_amount'] ?? 0;

        $this->status = $data['status'] ?? self::STATUS_PENDING;
        $this->payment_status = $data['payment_status'] ?? self::PAYMENT_UNPAID;
        $this->payment_method = $data['payment_method'] ?? self::METHOD_COD;

        $this->note = $data['note'] ?? null;

        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function canCancel()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED
        ]);
    }
}
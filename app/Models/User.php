<?php

namespace App\Models;

class User {
    public $id;
    public $email;
    public $name;
    public $phone;
    public $address;
    public $avatar_url;
    public $password;
    public $provider;
    public $provider_id;
    public $role;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($data = []) {
        $this->id           = $data['id'] ?? null;
        $this->email        = $data['email'] ?? null;
        $this->name         = $data['name'] ?? null;

        $this->phone        = $data['phone'] ?? null;
        $this->address      = $data['address'] ?? null;

        $this->avatar_url   = $data['avatar_url'] ?? null;
        $this->password     = $data['password'] ?? null;
        $this->provider     = $data['provider'] ?? 'local';
        $this->provider_id  = $data['provider_id'] ?? null;
        $this->role         = $data['role'] ?? 'user';
        $this->status       = $data['status'] ?? 1;
        $this->created_at   = $data['created_at'] ?? null;
        $this->updated_at   = $data['updated_at'] ?? null;
    }

    public function toArray() {
        return [
            'id'           => $this->id,
            'email'        => $this->email,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'address'      => $this->address,
            'avatar_url'   => $this->avatar_url,
            'password'     => $this->password,
            'provider'     => $this->provider,
            'provider_id'  => $this->provider_id,
            'role'         => $this->role,
            'status'       => $this->status,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
<?php

namespace Wox\Models;

defined('ABSPATH') || exit;

class Cart
{
    public ?int $id;
    public ?string $session_id;
    public ?int $user_id;
    public ?string $phone;
    public string $cart_data;
    public float $cart_total = 0.00;
    public string $currency = 'USD';
    public string $status = 'abandoned';
    public int $reminder_count = 0;
    public ?string $recovered_at;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function get_cart_items(): array
    {
        return json_decode($this->cart_data, true) ?: [];
    }

    public function is_recovered(): bool
    {
        return 'recovered' === $this->status;
    }

    public function save(): void
    {
        global $wpdb;

        $data = [
            'session_id' => $this->session_id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'cart_data' => $this->cart_data,
            'cart_total' => $this->cart_total,
            'currency' => $this->currency,
            'status' => $this->status,
            'reminder_count' => $this->reminder_count,
            'recovered_at' => $this->recovered_at,
        ];

        if ($this->id) {
            $wpdb->update($wpdb->prefix . 'wox_carts', $data, ['id' => $this->id]);
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_carts', $data);
            $this->id = $wpdb->insert_id;
        }
    }
}

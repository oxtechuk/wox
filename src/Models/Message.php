<?php

namespace Wox\Models;

defined('ABSPATH') || exit;

class Message
{
    public ?int $id;
    public ?int $order_id;
    public ?int $customer_id;
    public string $phone;
    public ?string $template_name;
    public string $message_body;
    public string $status;
    public ?string $error_message;
    public ?string $provider_message_id;
    public ?string $sent_at;
    public ?string $delivered_at;
    public string $created_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function is_sent(): bool
    {
        return 'sent' === $this->status;
    }

    public function is_failed(): bool
    {
        return 'failed' === $this->status;
    }

    public function save(): void
    {
        global $wpdb;

        $data = [
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'phone' => $this->phone,
            'template_name' => $this->template_name,
            'message_body' => $this->message_body,
            'status' => $this->status,
            'error_message' => $this->error_message,
            'provider_message_id' => $this->provider_message_id,
            'sent_at' => $this->sent_at,
            'delivered_at' => $this->delivered_at,
        ];

        if ($this->id) {
            $wpdb->update($wpdb->prefix . 'wox_messages', $data, ['id' => $this->id]);
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_messages', $data);
            $this->id = $wpdb->insert_id;
        }
    }
}

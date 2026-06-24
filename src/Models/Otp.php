<?php

namespace Wox\Models;

defined('ABSPATH') || exit;

class Otp
{
    public ?int $id;
    public string $phone;
    public string $code;
    public string $context = 'checkout';
    public int $attempts = 0;
    public int $max_attempts = 5;
    public string $expires_at;
    public ?string $verified_at;
    public string $created_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function is_expired(): bool
    {
        return strtotime($this->expires_at) < time();
    }

    public function is_locked(): bool
    {
        return $this->attempts >= $this->max_attempts;
    }

    public function is_verified(): bool
    {
        return null !== $this->verified_at;
    }
}

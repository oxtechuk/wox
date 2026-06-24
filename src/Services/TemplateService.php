<?php

namespace Wox\Services;

defined('ABSPATH') || exit;

class TemplateService
{
    const VARIABLES = [
        '{{first_name}}' => 'Customer first name',
        '{{full_name}}' => 'Customer full name',
        '{{phone}}' => 'Customer phone number',
        '{{order_number}}' => 'Order number',
        '{{order_date}}' => 'Order date',
        '{{order_total}}' => 'Order total amount',
        '{{currency}}' => 'Order currency',
        '{{payment_method}}' => 'Payment method',
        '{{shipping_method}}' => 'Shipping method',
        '{{product_list}}' => 'List of ordered products',
        '{{order_status}}' => 'Current order status',
        '{{coupon_code}}' => 'Coupon code',
        '{{tracking_url}}' => 'Order tracking URL',
        '{{store_name}}' => 'Store name',
        '{{support_contact}}' => 'Support contact information',
    ];

    public function get_available_variables(): array
    {
        return self::VARIABLES;
    }

    public function render(string $content, array $data): string
    {
        return str_replace(array_keys(self::VARIABLES), array_values($data), $content);
    }

    public function validate_body(string $body): bool
    {
        return !empty(trim($body));
    }
}

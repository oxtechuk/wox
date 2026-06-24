<?php

namespace Wox\Frontend;

use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class ProductInquiry
{
    use Singleton;
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets(): void
    {
        if (!is_product()) {
            return;
        }

        wp_enqueue_style('wox-chat', WOX_ASSETS_URL . '/css/chat-button.css', [], WOX_VERSION);
    }

    public function render_button(): void
    {
        if ('yes' !== get_option('wox_chat_product_inquiry', 'yes')) {
            return;
        }

        $phone = get_option('wox_support_contact', '');
        if (empty($phone)) {
            return;
        }

        global $product;
        if (!$product) {
            return;
        }

        $product_name = $product->get_name();
        $product_url = $product->get_permalink();

        $message = sprintf(
            __('Hello, I have a question about: %s', 'whatsapp-ox'),
            $product_name
        );

        if ('yes' === get_option('wox_product_include_price', 'yes')) {
            $price = wp_strip_all_tags(wc_price($product->get_price()));
            $message .= "\n" . sprintf(__('Price: %s', 'whatsapp-ox'), $price);
        }

        if ('yes' === get_option('wox_product_include_sku', 'no')) {
            $sku = $product->get_sku();
            if ($sku) {
                $message .= "\n" . sprintf(__('SKU: %s', 'whatsapp-ox'), $sku);
            }
        }

        if ('yes' === get_option('wox_product_include_url', 'yes')) {
            $message .= "\n" . sprintf(__('URL: %s', 'whatsapp-ox'), $product_url);
        }

        $url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone) . '?text=' . rawurlencode($message);

        $button_text = get_option('wox_chat_product_button_text', __('Ask via WhatsApp', 'whatsapp-ox'));

        echo '<div class="wox-product-inquiry" style="margin:15px 0;">';
        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="button alt" style="background:#25d366;color:#fff;border-color:#25d366;">';
        echo '<svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff;vertical-align:middle;margin-right:6px;" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
        echo esc_html($button_text);
        echo '</a>';
        echo '</div>';
    }
}

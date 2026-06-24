<div class="wrap">
    <h1><?php echo esc_html__('Abandoned Cart Recovery', 'whatsapp-ox'); ?></h1>

    <h2><?php echo esc_html__('Settings', 'whatsapp-ox'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('wox_cart_settings'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html__('Enable Cart Recovery', 'whatsapp-ox'); ?></th>
                <td><label><input type="checkbox" name="wox_cart_enabled" value="yes" <?php checked($enabled, 'yes'); ?> /> <?php echo esc_html__('Track abandoned carts and send reminders', 'whatsapp-ox'); ?></label></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_cart_delay"><?php echo esc_html__('First Reminder Delay (minutes)', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_cart_delay" name="wox_cart_delay" value="<?php echo esc_attr($delay); ?>" min="5" max="1440" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_cart_reminder_count"><?php echo esc_html__('Max Reminders', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_cart_reminder_count" name="wox_cart_reminder_count" value="<?php echo esc_attr($reminder_count); ?>" min="1" max="10" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_cart_coupon"><?php echo esc_html__('Recovery Coupon Code (optional)', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="wox_cart_coupon" name="wox_cart_coupon" value="<?php echo esc_attr($coupon); ?>" class="regular-text" /></td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="wox_save_cart_settings" class="button button-primary"><?php echo esc_html__('Save Settings', 'whatsapp-ox'); ?></button>
        </p>
    </form>

    <h2 style="margin-top:40px;"><?php echo esc_html__('Abandoned Carts', 'whatsapp-ox'); ?></h2>

    <?php if (empty($carts)) : ?>
        <p><?php echo esc_html__('No abandoned carts tracked yet.', 'whatsapp-ox'); ?></p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Phone', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Total', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Reminders', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Last Updated', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carts as $cart) : ?>
                    <tr>
                        <td><?php echo esc_html($cart->phone); ?></td>
                        <td><?php echo wp_kses_post(wc_price($cart->cart_total)); ?></td>
                        <td><?php echo esc_html($cart->reminder_count); ?></td>
                        <td><?php echo esc_html($cart->updated_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

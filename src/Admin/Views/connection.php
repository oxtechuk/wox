<div class="wrap">
    <h1><?php echo esc_html__('WhatsApp API Connection', 'whatsapp-ox'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('wox_connection'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="wox_phone_number_id"><?php echo esc_html__('Phone Number ID', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="wox_phone_number_id" name="wox_phone_number_id" value="<?php echo esc_attr($phone_number_id); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_access_token"><?php echo esc_html__('Access Token', 'whatsapp-ox'); ?></label></th>
                <td><input type="password" id="wox_access_token" name="wox_access_token" value="<?php echo esc_attr($access_token); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_webhook_verify_token"><?php echo esc_html__('Webhook Verify Token', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="wox_webhook_verify_token" name="wox_webhook_verify_token" value="<?php echo esc_attr($webhook_token); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_test_phone"><?php echo esc_html__('Test Phone Number', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="wox_test_phone" name="wox_test_phone" value="<?php echo esc_attr($test_phone); ?>" class="regular-text" />
                    <p class="description"><?php echo esc_html__('Phone number for testing (include country code).', 'whatsapp-ox'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo esc_html__('Sandbox Mode', 'whatsapp-ox'); ?></th>
                <td><label><input type="checkbox" name="wox_sandbox_mode" value="yes" <?php checked($sandbox, 'yes'); ?> /> <?php echo esc_html__('Enable sandbox (mock provider, no real messages sent)', 'whatsapp-ox'); ?></label></td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="wox_save_connection" class="button button-primary"><?php echo esc_html__('Save Connection', 'whatsapp-ox'); ?></button>
        </p>
    </form>
</div>

<div class="wrap">
    <h1><?php echo esc_html__('OTP Settings', 'whatsapp-ox'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('wox_otp'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html__('Enable OTP', 'whatsapp-ox'); ?></th>
                <td><label><input type="checkbox" name="wox_otp_enabled" value="yes" <?php checked($enabled, 'yes'); ?> /> <?php echo esc_html__('Enable phone verification via OTP', 'whatsapp-ox'); ?></label></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_otp_length"><?php echo esc_html__('OTP Code Length', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_otp_length" name="wox_otp_length" value="<?php echo esc_attr($length); ?>" min="4" max="10" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_otp_expiry"><?php echo esc_html__('OTP Expiry (seconds)', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_otp_expiry" name="wox_otp_expiry" value="<?php echo esc_attr($expiry); ?>" min="60" max="3600" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_otp_max_attempts"><?php echo esc_html__('Max Attempts', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_otp_max_attempts" name="wox_otp_max_attempts" value="<?php echo esc_attr($max_attempts); ?>" min="1" max="20" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="wox_otp_cooldown"><?php echo esc_html__('Resend Cooldown (seconds)', 'whatsapp-ox'); ?></label></th>
                <td><input type="number" id="wox_otp_cooldown" name="wox_otp_cooldown" value="<?php echo esc_attr($cooldown); ?>" min="30" max="600" /></td>
            </tr>
            <tr>
                <th scope="row"><?php echo esc_html__('Verification Contexts', 'whatsapp-ox'); ?></th>
                <td>
                    <label><input type="checkbox" name="wox_otp_checkout" value="yes" <?php checked($checkout, 'yes'); ?> /> <?php echo esc_html__('Checkout (before order submission)', 'whatsapp-ox'); ?></label><br>
                    <label><input type="checkbox" name="wox_otp_registration" value="yes" <?php checked($registration, 'yes'); ?> /> <?php echo esc_html__('Account Registration', 'whatsapp-ox'); ?></label><br>
                    <label><input type="checkbox" name="wox_otp_login" value="yes" <?php checked($login, 'yes'); ?> /> <?php echo esc_html__('Login', 'whatsapp-ox'); ?></label>
                </td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="wox_save_otp" class="button button-primary"><?php echo esc_html__('Save OTP Settings', 'whatsapp-ox'); ?></button>
        </p>
    </form>
</div>

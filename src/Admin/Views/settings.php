<div class="wrap wo-settings-wrap">
    <h1><?php echo esc_html__('WhatsApp OX Settings', 'whatsapp-ox'); ?></h1>

    <nav class="nav-tab-wrapper">
        <a href="?page=whatsapp-ox-settings&tab=general" class="nav-tab <?php echo $tab === 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=provider" class="nav-tab <?php echo $tab === 'provider' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Provider', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=automations" class="nav-tab <?php echo $tab === 'automations' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Automations', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=carts" class="nav-tab <?php echo $tab === 'carts' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Abandoned Carts', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=otp" class="nav-tab <?php echo $tab === 'otp' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('OTP', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=widget" class="nav-tab <?php echo $tab === 'widget' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Widget', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=product" class="nav-tab <?php echo $tab === 'product' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Sell via WhatsApp', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=logs" class="nav-tab <?php echo $tab === 'logs' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Logs', 'whatsapp-ox'); ?></a>
        <a href="?page=whatsapp-ox-settings&tab=reports" class="nav-tab <?php echo $tab === 'reports' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Reports', 'whatsapp-ox'); ?></a>
    </nav>

    <form method="post" action="?page=whatsapp-ox-settings&tab=<?php echo esc_attr($tab); ?>">
        <?php wp_nonce_field('wox_settings'); ?>

        <div class="tab-content" style="margin-top:20px;">

<?php if ($tab === 'general'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Store Information', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wox_store_name"><?php esc_html_e('Store Name', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_store_name" name="wox_store_name" value="<?php echo esc_attr($data['store_name']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Used as {{store_name}} in message templates.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_support_contact"><?php esc_html_e('Support Phone Number', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_support_contact" name="wox_support_contact" value="<?php echo esc_attr($data['support_contact']); ?>" class="regular-text" placeholder="e.g. 1234567890" />
                            <p class="description"><?php esc_html_e('Used for the WhatsApp chat widget and product inquiry button.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_logs_retention_days"><?php esc_html_e('Logs Retention', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="number" id="wox_logs_retention_days" name="wox_logs_retention_days" value="<?php echo esc_attr($data['logs_retention_days']); ?>" min="1" max="365" class="small-text" /> <?php esc_html_e('days', 'whatsapp-ox'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Daily Report', 'whatsapp-ox'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="wox_daily_report" value="yes" <?php checked($data['daily_report'], 'yes'); ?> />
                                <?php esc_html_e('Send daily WhatsApp summary to store admin', 'whatsapp-ox'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_language"><?php esc_html_e('Language', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <select id="wox_language" name="wox_language">
                                <option value="en" <?php selected($data['language'], 'en'); ?>><?php esc_html_e('English', 'whatsapp-ox'); ?></option>
                                <option value="ar" <?php selected($data['language'], 'ar'); ?>><?php esc_html_e('Arabic', 'whatsapp-ox'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose plugin interface language. English is the default.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

<?php elseif ($tab === 'provider'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Provider Selection', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Provider', 'whatsapp-ox'); ?></th>
                        <td>
                            <fieldset>
                                <label style="display:block;margin-bottom:8px;">
                                    <input type="radio" name="wox_provider" value="whatsapp_cloud" <?php checked($data['provider'], 'whatsapp_cloud'); ?> />
                                    <strong><?php esc_html_e('WhatsApp Cloud API', 'whatsapp-ox'); ?></strong>
                                    <span class="description"> — <?php esc_html_e('Meta official API (free tier available)', 'whatsapp-ox'); ?></span>
                                </label>
                                <label style="display:block;">
                                    <input type="radio" name="wox_provider" value="twilio" <?php checked($data['provider'], 'twilio'); ?> />
                                    <strong><?php esc_html_e('Twilio WhatsApp API', 'whatsapp-ox'); ?></strong>
                                    <span class="description"> — <?php esc_html_e('Twilio conversational platform', 'whatsapp-ox'); ?></span>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="wox-settings-section" id="wox-wa-settings" style="display:<?php echo $data['provider'] === 'twilio' ? 'none' : 'block'; ?>;">
                <h2><?php esc_html_e('WhatsApp Cloud API Settings', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wox_phone_number_id"><?php esc_html_e('Phone Number ID', 'whatsapp-ox'); ?></label></th>
                        <td><input type="text" id="wox_phone_number_id" name="wox_phone_number_id" value="<?php echo esc_attr($data['phone_number_id']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_access_token"><?php esc_html_e('Access Token', 'whatsapp-ox'); ?></label></th>
                        <td><input type="password" id="wox_access_token" name="wox_access_token" value="<?php echo esc_attr($data['access_token']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_webhook_verify_token"><?php esc_html_e('Webhook Verify Token', 'whatsapp-ox'); ?></label></th>
                        <td><input type="text" id="wox_webhook_verify_token" name="wox_webhook_verify_token" value="<?php echo esc_attr($data['webhook_token']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_test_phone"><?php esc_html_e('Test Phone Number', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_test_phone" name="wox_test_phone" value="<?php echo esc_attr($data['test_phone']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Include country code, no plus sign.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Sandbox Mode', 'whatsapp-ox'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="wox_sandbox_mode" value="yes" <?php checked($data['sandbox'], 'yes'); ?> />
                                <?php esc_html_e('Enable sandbox (mock provider, no real messages sent)', 'whatsapp-ox'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="wox-settings-section" id="wox-twilio-settings" style="display:<?php echo $data['provider'] === 'twilio' ? 'block' : 'none'; ?>;">
                <h2><?php esc_html_e('Twilio WhatsApp Settings', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wox_twilio_account_sid"><?php esc_html_e('Account SID', 'whatsapp-ox'); ?></label></th>
                        <td><input type="text" id="wox_twilio_account_sid" name="wox_twilio_account_sid" value="<?php echo esc_attr($data['twilio_account_sid']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_twilio_auth_token"><?php esc_html_e('Auth Token', 'whatsapp-ox'); ?></label></th>
                        <td><input type="password" id="wox_twilio_auth_token" name="wox_twilio_auth_token" value="<?php echo esc_attr($data['twilio_auth_token']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_twilio_from_number"><?php esc_html_e('WhatsApp From Number', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_twilio_from_number" name="wox_twilio_from_number" value="<?php echo esc_attr($data['twilio_from_number']); ?>" class="regular-text" placeholder="e.g. 14155238886" />
                            <p class="description"><?php esc_html_e('The Twilio WhatsApp-enabled number (include country code).', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <script>
            (function() {
                var radios = document.querySelectorAll('input[name="wox_provider"]');
                var waSettings = document.getElementById('wox-wa-settings');
                var twSettings = document.getElementById('wox-twilio-settings');
                function toggle() {
                    var val = document.querySelector('input[name="wox_provider"]:checked').value;
                    waSettings.style.display = val === 'twilio' ? 'none' : 'block';
                    twSettings.style.display = val === 'twilio' ? 'block' : 'none';
                }
                radios.forEach(function(r) { r.addEventListener('change', toggle); });
            })();
            </script>

<?php elseif ($tab === 'automations'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Order Event Automations', 'whatsapp-ox'); ?></h2>
                <p><?php esc_html_e('Automatically send WhatsApp messages when order status changes.', 'whatsapp-ox'); ?></p>
                <table class="wp-list-table widefat fixed striped" style="margin-top:15px;">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Trigger', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Enabled', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Template', 'whatsapp-ox'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['triggers'] as $key => $label) : ?>
                            <tr>
                                <td><?php echo esc_html($label); ?></td>
                                <td>
                                    <input type="checkbox" name="automation_<?php echo esc_attr($key); ?>_enabled" value="yes" <?php checked($data['automations'][$key]['enabled'] ?? '', 'yes'); ?> />
                                </td>
                                <td>
                                    <select name="automation_<?php echo esc_attr($key); ?>_template">
                                        <option value=""><?php esc_html_e('Select template', 'whatsapp-ox'); ?></option>
                                        <?php foreach ($data['templates'] as $tpl) : ?>
                                            <option value="<?php echo esc_attr($tpl->id); ?>" <?php selected($data['automations'][$key]['template_id'] ?? '', $tpl->id); ?>><?php echo esc_html($tpl->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

<?php elseif ($tab === 'carts'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Cart Recovery Settings', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Cart Recovery', 'whatsapp-ox'); ?></th>
                        <td><label><input type="checkbox" name="wox_cart_enabled" value="yes" <?php checked($data['cart_enabled'], 'yes'); ?> /> <?php esc_html_e('Track abandoned carts and send reminders', 'whatsapp-ox'); ?></label></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_cart_delay"><?php esc_html_e('First Reminder Delay (minutes)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_cart_delay" name="wox_cart_delay" value="<?php echo esc_attr($data['cart_delay']); ?>" min="5" max="1440" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_cart_reminder_count"><?php esc_html_e('Max Reminders', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_cart_reminder_count" name="wox_cart_reminder_count" value="<?php echo esc_attr($data['cart_reminder_count']); ?>" min="1" max="10" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_cart_coupon"><?php esc_html_e('Recovery Coupon Code', 'whatsapp-ox'); ?></label></th>
                        <td><input type="text" id="wox_cart_coupon" name="wox_cart_coupon" value="<?php echo esc_attr($data['cart_coupon']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Optional', 'whatsapp-ox'); ?>" /></td>
                    </tr>
                </table>
            </div>

            <div class="wox-settings-section">
                <h2><?php esc_html_e('Abandoned Carts', 'whatsapp-ox'); ?></h2>
                <?php if (empty($data['carts'])) : ?>
                    <p><?php esc_html_e('No abandoned carts tracked yet.', 'whatsapp-ox'); ?></p>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Phone', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Total', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Reminders', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Last Updated', 'whatsapp-ox'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['carts'] as $cart) : ?>
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

<?php elseif ($tab === 'otp'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('OTP Verification Settings', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable OTP', 'whatsapp-ox'); ?></th>
                        <td><label><input type="checkbox" name="wox_otp_enabled" value="yes" <?php checked($data['otp_enabled'], 'yes'); ?> /> <?php esc_html_e('Enable phone verification via OTP', 'whatsapp-ox'); ?></label></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_otp_length"><?php esc_html_e('OTP Code Length', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_otp_length" name="wox_otp_length" value="<?php echo esc_attr($data['otp_length']); ?>" min="4" max="10" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_otp_expiry"><?php esc_html_e('OTP Expiry (seconds)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_otp_expiry" name="wox_otp_expiry" value="<?php echo esc_attr($data['otp_expiry']); ?>" min="60" max="3600" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_otp_max_attempts"><?php esc_html_e('Max Attempts', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_otp_max_attempts" name="wox_otp_max_attempts" value="<?php echo esc_attr($data['otp_max_attempts']); ?>" min="1" max="20" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_otp_cooldown"><?php esc_html_e('Resend Cooldown (seconds)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_otp_cooldown" name="wox_otp_cooldown" value="<?php echo esc_attr($data['otp_cooldown']); ?>" min="30" max="600" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Verification Contexts', 'whatsapp-ox'); ?></th>
                        <td>
                            <label><input type="checkbox" name="wox_otp_checkout" value="yes" <?php checked($data['otp_checkout'], 'yes'); ?> /> <?php esc_html_e('Checkout (before order submission)', 'whatsapp-ox'); ?></label><br>
                            <label><input type="checkbox" name="wox_otp_registration" value="yes" <?php checked($data['otp_registration'], 'yes'); ?> /> <?php esc_html_e('Account Registration', 'whatsapp-ox'); ?></label><br>
                            <label><input type="checkbox" name="wox_otp_login" value="yes" <?php checked($data['otp_login'], 'yes'); ?> /> <?php esc_html_e('Login', 'whatsapp-ox'); ?></label>
                        </td>
                    </tr>
                </table>
            </div>

<?php elseif ($tab === 'widget'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Floating WhatsApp Widget', 'whatsapp-ox'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Widget', 'whatsapp-ox'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="wox_chat_enabled" value="yes" <?php checked($data['widget_enabled'], 'yes'); ?> />
                                <?php esc_html_e('Show floating WhatsApp chat button on frontend', 'whatsapp-ox'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_chat_greeting"><?php esc_html_e('Default Greeting', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_chat_greeting" name="wox_chat_greeting" value="<?php echo esc_attr($data['widget_greeting']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Hello, I have a question.', 'whatsapp-ox'); ?>" />
                            <p class="description"><?php esc_html_e('Pre-filled message when user opens chat.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Icon Style', 'whatsapp-ox'); ?></th>
                        <td>
                            <fieldset>
                                <label style="display:inline-block;margin-right:20px;">
                                    <input type="radio" name="wox_chat_icon_style" value="round" <?php checked($data['widget_icon_style'], 'round'); ?> />
                                    <?php esc_html_e('Round', 'whatsapp-ox'); ?>
                                </label>
                                <label style="display:inline-block;margin-right:20px;">
                                    <input type="radio" name="wox_chat_icon_style" value="square" <?php checked($data['widget_icon_style'], 'square'); ?> />
                                    <?php esc_html_e('Square', 'whatsapp-ox'); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Position', 'whatsapp-ox'); ?></th>
                        <td>
                            <fieldset>
                                <label style="display:inline-block;margin-right:20px;">
                                    <input type="radio" name="wox_chat_position" value="right" <?php checked($data['widget_position'], 'right'); ?> />
                                    <?php esc_html_e('Right', 'whatsapp-ox'); ?>
                                </label>
                                <label style="display:inline-block;">
                                    <input type="radio" name="wox_chat_position" value="left" <?php checked($data['widget_position'], 'left'); ?> />
                                    <?php esc_html_e('Left', 'whatsapp-ox'); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_chat_size"><?php esc_html_e('Icon Size (px)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_chat_size" name="wox_chat_size" value="<?php echo esc_attr($data['widget_size']); ?>" min="30" max="120" class="small-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_chat_bottom"><?php esc_html_e('Bottom Offset (px)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_chat_bottom" name="wox_chat_bottom" value="<?php echo esc_attr($data['widget_bottom']); ?>" min="0" max="200" class="small-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_chat_side_offset"><?php esc_html_e('Side Offset (px)', 'whatsapp-ox'); ?></label></th>
                        <td><input type="number" id="wox_chat_side_offset" name="wox_chat_side_offset" value="<?php echo esc_attr($data['widget_side_offset']); ?>" min="0" max="200" class="small-text" /></td>
                    </tr>
                </table>
            </div>

            <div class="wox-settings-section">
                <h2><?php esc_html_e('Preview', 'whatsapp-ox'); ?></h2>
                <p style="margin-bottom:20px;">
                    <span class="description"><?php esc_html_e('Shows how the widget will look on your store frontend.', 'whatsapp-ox'); ?></span>
                </p>
                <div style="position:relative;width:300px;height:160px;background:#f0f0f1;border:1px solid #ccc;border-radius:8px;overflow:hidden;">
                    <div style="position:absolute;bottom:<?php echo esc_attr($data['widget_bottom']); ?>px;<?php echo esc_attr($data['widget_position']); ?>:<?php echo esc_attr($data['widget_side_offset']); ?>px;width:<?php echo esc_attr($data['widget_size']); ?>px;height:<?php echo esc_attr($data['widget_size']); ?>px;background:#25d366;border-radius:<?php echo $data['widget_icon_style'] === 'square' ? '8px' : '50%'; ?>;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.2);transition:all 0.2s;">
                        <svg viewBox="0 0 24 24" style="width:<?php echo round((int)$data['widget_size'] * 0.5); ?>px;height:<?php echo round((int)$data['widget_size'] * 0.5); ?>px;fill:#fff;" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                </div>
            </div>

<?php elseif ($tab === 'product'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Sell via WhatsApp', 'whatsapp-ox'); ?></h2>
                <p><?php esc_html_e('Add a WhatsApp inquiry button on product pages so customers can ask about items.', 'whatsapp-ox'); ?></p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Button', 'whatsapp-ox'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="wox_chat_product_inquiry" value="yes" <?php checked($data['product_inquiry_enabled'], 'yes'); ?> />
                                <?php esc_html_e('Show WhatsApp button on single product pages', 'whatsapp-ox'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wox_chat_product_button_text"><?php esc_html_e('Button Text', 'whatsapp-ox'); ?></label></th>
                        <td>
                            <input type="text" id="wox_chat_product_button_text" name="wox_chat_product_button_text" value="<?php echo esc_attr($data['product_button_text']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Ask via WhatsApp', 'whatsapp-ox'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Include in Message', 'whatsapp-ox'); ?></th>
                        <td>
                            <fieldset>
                                <label style="display:block;margin-bottom:6px;">
                                    <input type="checkbox" name="wox_product_include_price" value="yes" <?php checked($data['product_include_price'], 'yes'); ?> />
                                    <?php esc_html_e('Product price', 'whatsapp-ox'); ?>
                                </label>
                                <label style="display:block;margin-bottom:6px;">
                                    <input type="checkbox" name="wox_product_include_sku" value="yes" <?php checked($data['product_include_sku'], 'yes'); ?> />
                                    <?php esc_html_e('Product SKU', 'whatsapp-ox'); ?>
                                </label>
                                <label style="display:block;">
                                    <input type="checkbox" name="wox_product_include_url" value="yes" <?php checked($data['product_include_url'], 'yes'); ?> />
                                    <?php esc_html_e('Product URL', 'whatsapp-ox'); ?>
                                </label>
                            </fieldset>
                            <p class="description"><?php esc_html_e('Select which product details to include in the WhatsApp message.', 'whatsapp-ox'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="wox-settings-section">
                <h2><?php esc_html_e('Example Message Preview', 'whatsapp-ox'); ?></h2>
                <div style="background:#f0f0f1;padding:15px;border-radius:8px;max-width:500px;">
                    <code style="background:transparent;display:block;line-height:1.6;">
                        <?php
                        $msg = sprintf(__('Hello, I have a question about: %s', 'whatsapp-ox'), '[Product Name]');
                        if ('yes' === $data['product_include_price']) {
                            $msg .= "\n" . __('Price:', 'whatsapp-ox') . ' [Price]';
                        }
                        if ('yes' === $data['product_include_sku']) {
                            $msg .= "\n" . __('SKU:', 'whatsapp-ox') . ' [SKU]';
                        }
                        if ('yes' === $data['product_include_url']) {
                            $msg .= "\n" . __('URL:', 'whatsapp-ox') . ' [Product URL]';
                        }
                        echo nl2br(esc_html($msg));
                        ?>
                    </code>
                </div>
            </div>

<?php elseif ($tab === 'logs'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Message Logs', 'whatsapp-ox'); ?></h2>

                <form method="get" action="" style="margin-bottom:20px;">
                    <input type="hidden" name="page" value="whatsapp-ox-settings" />
                    <input type="hidden" name="tab" value="logs" />
                    <label for="order_id"><?php esc_html_e('Order ID:', 'whatsapp-ox'); ?></label>
                    <input type="number" id="order_id" name="order_id" value="<?php echo esc_attr($data['log_order_id']); ?>" placeholder="e.g. 123" />
                    <select name="status">
                        <option value=""><?php esc_html_e('All statuses', 'whatsapp-ox'); ?></option>
                        <option value="sent" <?php selected($data['log_status'], 'sent'); ?>><?php esc_html_e('Sent', 'whatsapp-ox'); ?></option>
                        <option value="failed" <?php selected($data['log_status'], 'failed'); ?>><?php esc_html_e('Failed', 'whatsapp-ox'); ?></option>
                        <option value="pending" <?php selected($data['log_status'], 'pending'); ?>><?php esc_html_e('Pending', 'whatsapp-ox'); ?></option>
                    </select>
                    <input type="text" name="s" value="<?php echo esc_attr($data['log_search']); ?>" placeholder="<?php esc_attr_e('Search by phone or message...', 'whatsapp-ox'); ?>" />
                    <button type="submit" class="button"><?php esc_html_e('Filter', 'whatsapp-ox'); ?></button>
                </form>

                <?php if (empty($data['logs'])) : ?>
                    <p><?php esc_html_e('No log entries found.', 'whatsapp-ox'); ?></p>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('ID', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Order', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Phone', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Template', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Status', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Error', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Sent At', 'whatsapp-ox'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['logs'] as $log) : ?>
                                <tr>
                                    <td><?php echo esc_html($log->id); ?></td>
                                    <td><?php echo $log->order_id ? '<a href="' . esc_url(get_edit_post_link($log->order_id)) . '">#' . esc_html($log->order_id) . '</a>' : '—'; ?></td>
                                    <td><?php echo esc_html($log->phone); ?></td>
                                    <td><?php echo esc_html($log->template_name ?: '—'); ?></td>
                                    <td>
                                        <span style="color:<?php echo 'sent' === $log->status ? '#46b450' : ('failed' === $log->status ? '#dc3232' : '#f0ad4e'); ?>;">
                                            <?php echo esc_html($log->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html($log->error_message ?: '—'); ?></td>
                                    <td><?php echo esc_html($log->sent_at ?: '—'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

<?php elseif ($tab === 'reports'): ?>
            <div class="wox-settings-section">
                <h2><?php esc_html_e('Reports', 'whatsapp-ox'); ?></h2>

                <form method="get" action="" style="margin-bottom:20px;">
                    <input type="hidden" name="page" value="whatsapp-ox-settings" />
                    <input type="hidden" name="tab" value="reports" />
                    <select name="period">
                        <option value="7days" <?php selected($data['report_period'], '7days'); ?>><?php esc_html_e('Last 7 days', 'whatsapp-ox'); ?></option>
                        <option value="30days" <?php selected($data['report_period'], '30days'); ?>><?php esc_html_e('Last 30 days', 'whatsapp-ox'); ?></option>
                        <option value="90days" <?php selected($data['report_period'], '90days'); ?>><?php esc_html_e('Last 90 days', 'whatsapp-ox'); ?></option>
                        <option value="all" <?php selected($data['report_period'], 'all'); ?>><?php esc_html_e('All time', 'whatsapp-ox'); ?></option>
                    </select>
                    <button type="submit" class="button"><?php esc_html_e('Filter', 'whatsapp-ox'); ?></button>
                </form>

                <div class="wox-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-top:20px;">
                    <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
                        <h3><?php esc_html_e('Messages Sent', 'whatsapp-ox'); ?></h3>
                        <p style="font-size:2em;font-weight:700;margin:10px 0 0;"><?php echo esc_html(number_format($data['report_sent'])); ?></p>
                    </div>
                    <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
                        <h3><?php esc_html_e('Delivered', 'whatsapp-ox'); ?></h3>
                        <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#46b450;"><?php echo esc_html(number_format($data['report_delivered'])); ?></p>
                    </div>
                    <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
                        <h3><?php esc_html_e('Failed', 'whatsapp-ox'); ?></h3>
                        <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#dc3232;"><?php echo esc_html(number_format($data['report_failed'])); ?></p>
                    </div>
                    <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
                        <h3><?php esc_html_e('Carts Recovered', 'whatsapp-ox'); ?></h3>
                        <p style="font-size:2em;font-weight:700;margin:10px 0 0;"><?php echo esc_html(number_format($data['report_recovered'])); ?></p>
                    </div>
                    <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
                        <h3><?php esc_html_e('Recovered Revenue', 'whatsapp-ox'); ?></h3>
                        <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#46b450;"><?php echo wp_kses_post(wc_price($data['report_revenue'])); ?></p>
                    </div>
                </div>

                <?php if (!empty($data['report_daily'])) : ?>
                    <h2 style="margin-top:40px;"><?php esc_html_e('Daily Breakdown', 'whatsapp-ox'); ?></h2>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Date', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Status', 'whatsapp-ox'); ?></th>
                                <th><?php esc_html_e('Count', 'whatsapp-ox'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['report_daily'] as $stat) : ?>
                                <tr>
                                    <td><?php echo esc_html($stat->date); ?></td>
                                    <td><?php echo esc_html($stat->status); ?></td>
                                    <td><?php echo esc_html(number_format($stat->count)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

<?php endif; ?>
        </div>

        <?php if (!in_array($tab, ['logs', 'reports'], true)) : ?>
        <p class="submit">
            <button type="submit" name="wox_save_settings" class="button button-primary"><?php esc_html_e('Save Settings', 'whatsapp-ox'); ?></button>
        </p>
        <?php endif; ?>
    </form>
</div>

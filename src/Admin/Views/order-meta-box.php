<div class="wox-order-box">
    <p><strong><?php echo esc_html__('Phone:', 'whatsapp-ox'); ?></strong> <?php echo esc_html($phone); ?></p>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('wox_order_send'); ?>
        <input type="hidden" name="action" value="wox_admin_send" />
        <input type="hidden" name="order_id" value="<?php echo esc_attr($post->ID); ?>" />

        <p>
            <label for="wox_template_id"><?php echo esc_html__('Template:', 'whatsapp-ox'); ?></label>
            <select id="wox_template_id" name="template_id" style="width:100%;">
                <option value=""><?php echo esc_html__('— Custom message —', 'whatsapp-ox'); ?></option>
                <?php foreach ($templates as $tpl) : ?>
                    <option value="<?php echo esc_attr($tpl->id); ?>"><?php echo esc_html($tpl->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="wox_custom_message"><?php echo esc_html__('Or custom message:', 'whatsapp-ox'); ?></label>
            <textarea id="wox_custom_message" name="custom_message" rows="3" style="width:100%;"></textarea>
        </p>

        <p>
            <button type="submit" name="wox_admin_send" class="button button-primary"><?php echo esc_html__('Send WhatsApp', 'whatsapp-ox'); ?></button>
        </p>
    </form>

    <?php if ($last_logs) : ?>
        <hr />
        <p><strong><?php echo esc_html__('Recent Messages:', 'whatsapp-ox'); ?></strong></p>
        <ul style="font-size:11px;">
            <?php foreach ($last_logs as $log) : ?>
                <li style="color:<?php echo 'sent' === $log->status ? '#46b450' : '#dc3232'; ?>;">
                    [<?php echo esc_html($log->created_at); ?>] <?php echo esc_html($log->status); ?>
                    <?php echo $log->error_message ? ' - ' . esc_html($log->error_message) : ''; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

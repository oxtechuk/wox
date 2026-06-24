<div class="wrap">
    <h1><?php echo esc_html__('Campaign:', 'whatsapp-ox'); ?> <?php echo esc_html($campaign->name); ?></h1>

    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=whatsapp-ox-campaigns')); ?>" class="button">&larr; <?php echo esc_html__('Back to Campaigns', 'whatsapp-ox'); ?></a>
    </p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin:20px 0;">
        <div style="background:#fff;padding:15px;border:1px solid #ccd0d4;border-radius:4px;text-align:center;">
            <strong><?php echo esc_html__('Status', 'whatsapp-ox'); ?></strong>
            <p style="font-size:1.5em;margin:5px 0;"><?php echo esc_html($campaign->status); ?></p>
        </div>
        <div style="background:#fff;padding:15px;border:1px solid #ccd0d4;border-radius:4px;text-align:center;">
            <strong><?php echo esc_html__('Total', 'whatsapp-ox'); ?></strong>
            <p style="font-size:1.5em;margin:5px 0;"><?php echo esc_html($campaign->total_count); ?></p>
        </div>
        <div style="background:#fff;padding:15px;border:1px solid #ccd0d4;border-radius:4px;text-align:center;">
            <strong><?php echo esc_html__('Sent', 'whatsapp-ox'); ?></strong>
            <p style="font-size:1.5em;margin:5px 0;color:#46b450;"><?php echo esc_html($campaign->sent_count); ?></p>
        </div>
        <div style="background:#fff;padding:15px;border:1px solid #ccd0d4;border-radius:4px;text-align:center;">
            <strong><?php echo esc_html__('Created', 'whatsapp-ox'); ?></strong>
            <p style="font-size:1em;margin:5px 0;"><?php echo esc_html($campaign->created_at); ?></p>
        </div>
    </div>

    <?php if ('draft' === $campaign->status && $campaign->total_count > 0) : ?>
        <form method="post" action="" style="margin-bottom:20px;">
            <?php wp_nonce_field('wox_campaign'); ?>
            <input type="hidden" name="campaign_id" value="<?php echo esc_attr($campaign->id); ?>" />
            <button type="submit" name="wox_send_campaign" class="button button-primary" onclick="return confirm('<?php echo esc_js(__('Send this campaign to all customers now?', 'whatsapp-ox')); ?>');"><?php echo esc_html__('Send Campaign Now', 'whatsapp-ox'); ?></button>
        </form>
    <?php endif; ?>

    <h2><?php echo esc_html__('Recipients', 'whatsapp-ox'); ?></h2>

    <?php if (empty($logs)) : ?>
        <p><?php echo esc_html__('No recipients.', 'whatsapp-ox'); ?></p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Phone', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Status', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Error', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Sent At', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log) : ?>
                    <tr>
                        <td><?php echo esc_html($log->phone); ?></td>
                        <td>
                            <span style="color:<?php echo 'sent' === $log->status ? '#46b450' : ('failed' === $log->status ? '#dc3232' : '#999'); ?>;">
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

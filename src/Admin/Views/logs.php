<div class="wrap">
    <h1><?php echo esc_html__('Message Logs', 'whatsapp-ox'); ?></h1>

    <form method="get" action="" style="margin-bottom:20px;">
        <input type="hidden" name="page" value="whatsapp-ox-logs" />

        <label for="order_id"><?php echo esc_html__('Order ID:', 'whatsapp-ox'); ?></label>
        <input type="number" id="order_id" name="order_id" value="<?php echo esc_attr($order_id); ?>" placeholder="e.g. 123" />

        <select name="status">
            <option value=""><?php echo esc_html__('All statuses', 'whatsapp-ox'); ?></option>
            <option value="sent" <?php selected($status, 'sent'); ?>><?php echo esc_html__('Sent', 'whatsapp-ox'); ?></option>
            <option value="failed" <?php selected($status, 'failed'); ?>><?php echo esc_html__('Failed', 'whatsapp-ox'); ?></option>
            <option value="pending" <?php selected($status, 'pending'); ?>><?php echo esc_html__('Pending', 'whatsapp-ox'); ?></option>
        </select>

        <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="<?php echo esc_attr__('Search by phone or message...', 'whatsapp-ox'); ?>" />

        <button type="submit" class="button"><?php echo esc_html__('Filter', 'whatsapp-ox'); ?></button>
    </form>

    <?php if (empty($logs)) : ?>
        <p><?php echo esc_html__('No log entries found.', 'whatsapp-ox'); ?></p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('ID', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Order', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Phone', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Template', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Status', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Error', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Sent At', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log) : ?>
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

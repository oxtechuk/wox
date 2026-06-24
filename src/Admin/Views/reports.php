<div class="wrap">
    <h1><?php echo esc_html__('Reports', 'whatsapp-ox'); ?></h1>

    <form method="get" action="" style="margin-bottom:20px;">
        <input type="hidden" name="page" value="whatsapp-ox-reports" />
        <select name="period">
            <option value="7days" <?php selected($period, '7days'); ?>><?php echo esc_html__('Last 7 days', 'whatsapp-ox'); ?></option>
            <option value="30days" <?php selected($period, '30days'); ?>><?php echo esc_html__('Last 30 days', 'whatsapp-ox'); ?></option>
            <option value="90days" <?php selected($period, '90days'); ?>><?php echo esc_html__('Last 90 days', 'whatsapp-ox'); ?></option>
            <option value="all" <?php selected($period, 'all'); ?>><?php echo esc_html__('All time', 'whatsapp-ox'); ?></option>
        </select>
        <button type="submit" class="button"><?php echo esc_html__('Filter', 'whatsapp-ox'); ?></button>
    </form>

    <div class="wox-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-top:20px;">
        <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h3><?php echo esc_html__('Messages Sent', 'whatsapp-ox'); ?></h3>
            <p style="font-size:2em;font-weight:700;margin:10px 0 0;"><?php echo esc_html(number_format($sent_count)); ?></p>
        </div>
        <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h3><?php echo esc_html__('Delivered', 'whatsapp-ox'); ?></h3>
            <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#46b450;"><?php echo esc_html(number_format($delivered_count)); ?></p>
        </div>
        <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h3><?php echo esc_html__('Failed', 'whatsapp-ox'); ?></h3>
            <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#dc3232;"><?php echo esc_html(number_format($failed_count)); ?></p>
        </div>
        <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h3><?php echo esc_html__('Carts Recovered', 'whatsapp-ox'); ?></h3>
            <p style="font-size:2em;font-weight:700;margin:10px 0 0;"><?php echo esc_html(number_format($recovered_count)); ?></p>
        </div>
        <div class="wox-stat-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h3><?php echo esc_html__('Recovered Revenue', 'whatsapp-ox'); ?></h3>
            <p style="font-size:2em;font-weight:700;margin:10px 0 0;color:#46b450;"><?php echo wp_kses_post(wc_price($recovered_revenue)); ?></p>
        </div>
    </div>

    <h2 style="margin-top:40px;"><?php echo esc_html__('Daily Breakdown', 'whatsapp-ox'); ?></h2>

    <?php if (empty($daily_stats)) : ?>
        <p><?php echo esc_html__('No data for this period.', 'whatsapp-ox'); ?></p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Date', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Status', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Count', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daily_stats as $stat) : ?>
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

<div class="wrap wox-dashboard">
    <div class="wox-dash-header">
        <img src="<?php echo esc_url(WOX_PLUGIN_URL . 'assets/images/logo.svg'); ?>" alt="WhatsApp OX" class="wox-dash-logo">
        <form method="get" action="" class="wox-period-form">
            <input type="hidden" name="page" value="whatsapp-ox">
            <select name="period" onchange="this.form.submit()">
                <option value="7days" <?php selected($period, '7days'); ?>><?php esc_html_e('Last 7 days', 'whatsapp-ox'); ?></option>
                <option value="30days" <?php selected($period, '30days'); ?>><?php esc_html_e('Last 30 days', 'whatsapp-ox'); ?></option>
                <option value="90days" <?php selected($period, '90days'); ?>><?php esc_html_e('Last 90 days', 'whatsapp-ox'); ?></option>
                <option value="all" <?php selected($period, 'all'); ?>><?php esc_html_e('All time', 'whatsapp-ox'); ?></option>
            </select>
        </form>
    </div>

    <div class="wox-kpi-row">
        <div class="wox-kpi-card">
            <span class="wox-kpi-icon wox-kpi-icon-sent">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 015.66 13.66.5.5 0 01-.12.08L13.3 17l.82-2.28A8 8 0 1010 2zm4.03 8.43l-1.37-.64a.47.47 0 00-.48.05c-.2.17-.68.8-.84.97-.15.16-.26.13-.4.06-.13-.06-.71-.34-1.28-.85-.48-.43-.8-.94-.9-1.1-.1-.17 0-.3.07-.38.08-.08.16-.2.22-.32.06-.13.1-.24.07-.36-.04-.12-.32-.86-.5-1.2-.17-.32-.38-.18-.5-.16-.13.02-.27.02-.36.06-.1.04-.39.2-.6.46-.28.3-.56.83-.56 1.63s.56 1.75.64 1.87c.08.12 1.18 1.94 2.87 2.78.4.2.7.32.94.42.4.16.78.14 1.07.08.34-.06 1-.42 1.16-.84.17-.42.15-.78.1-.86-.04-.08-.13-.12-.27-.18z"/></svg>
            </span>
            <span class="wox-kpi-label"><?php esc_html_e('Sent', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html(number_format($total_sent)); ?></span>
        </div>
        <div class="wox-kpi-card">
            <span class="wox-kpi-icon wox-kpi-icon-rate">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 11.5v-7l5 3.5-5 3.5z"/></svg>
            </span>
            <span class="wox-kpi-label"><?php esc_html_e('Delivery Rate', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html($delivery_rate); ?>%</span>
        </div>
        <div class="wox-kpi-card">
            <span class="wox-kpi-icon wox-kpi-icon-failed">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 5h2v6H9V5zm0 8h2v2H9v-2z"/></svg>
            </span>
            <span class="wox-kpi-label"><?php esc_html_e('Failed', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value wox-kpi-value-danger"><?php echo esc_html(number_format($total_failed)); ?></span>
        </div>
        <div class="wox-kpi-card">
            <span class="wox-kpi-icon wox-kpi-icon-revenue">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11.5v-1.25c1.07-.2 2-.85 2-2.25 0-1.4-1.08-2.25-2.5-2.25-.83 0-1.5-.22-1.5-1s.67-1 1.5-1c.55 0 1.03.2 1.38.56l1.24-1.24A3.46 3.46 0 0011 5.5V4H9v1.5C7.93 5.7 7 6.35 7 7.75c0 1.4 1.08 2.25 2.5 2.25.83 0 1.5.22 1.5 1s-.67 1-1.5 1c-.55 0-1.03-.2-1.38-.56L7 11.93A3.46 3.46 0 009 14.5V16h2v-1.5z"/></svg>
            </span>
            <span class="wox-kpi-label"><?php esc_html_e('Revenue Recovered', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html(wc_price($recovered_revenue)); ?></span>
        </div>
    </div>

    <div class="wox-kpi-row">
        <div class="wox-kpi-card wox-kpi-card-sm">
            <span class="wox-kpi-label"><?php esc_html_e('Active Customers', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html(number_format($active_customers)); ?></span>
        </div>
        <div class="wox-kpi-card wox-kpi-card-sm">
            <span class="wox-kpi-label"><?php esc_html_e('Unread Messages', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value <?php echo $unread_count > 0 ? 'wox-kpi-value-warn' : ''; ?>"><?php echo esc_html(number_format($unread_count)); ?></span>
        </div>
        <div class="wox-kpi-card wox-kpi-card-sm">
            <span class="wox-kpi-label"><?php esc_html_e('Carts Recovered', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html(number_format($carts_recovered)); ?></span>
        </div>
        <div class="wox-kpi-card wox-kpi-card-sm">
            <span class="wox-kpi-label"><?php esc_html_e('OTPs Verified', 'whatsapp-ox'); ?></span>
            <span class="wox-kpi-value"><?php echo esc_html(number_format($otps_verified)); ?></span>
        </div>
    </div>

    <div class="wox-charts-row">
        <div class="wox-chart-panel">
            <h3><?php esc_html_e('Messages Over Time', 'whatsapp-ox'); ?></h3>
            <?php
            $max_msg = 1;
            foreach ($chart_messages as $d) {
                $max_msg = max($max_msg, $d->sent + $d->failed);
            }
            $max_msg = max($max_msg, 1);
            ?>
            <div class="wox-chart-bars">
                <?php if (empty($chart_messages)) : ?>
                    <div class="wox-chart-empty"><?php esc_html_e('No data for this period.', 'whatsapp-ox'); ?></div>
                <?php else : ?>
                    <?php foreach ($chart_messages as $d) :
                        $h = (int) round((($d->sent + $d->failed) / $max_msg) * 100);
                        $h_sent = $d->sent > 0 ? (int) round(($d->sent / ($d->sent + $d->failed)) * 100) : 0;
                    ?>
                        <div class="wox-chart-bar-group">
                            <div class="wox-chart-bar" style="height:<?php echo max(4, $h); ?>%">
                                <div class="wox-chart-bar-sent" style="height:<?php echo $h_sent; ?>%"></div>
                            </div>
                            <span class="wox-chart-label"><?php echo esc_html(gmdate('M j', strtotime($d->date))); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="wox-chart-panel">
            <h3><?php esc_html_e('Revenue Recovered', 'whatsapp-ox'); ?></h3>
            <?php
            $max_rev = 1;
            foreach ($chart_revenue as $d) {
                $max_rev = max($max_rev, (float) $d->revenue);
            }
            $max_rev = max($max_rev, 1);
            $currency_sym = get_woocommerce_currency_symbol();
            ?>
            <div class="wox-chart-bars">
                <?php if (empty($chart_revenue)) : ?>
                    <div class="wox-chart-empty"><?php esc_html_e('No revenue data for this period.', 'whatsapp-ox'); ?></div>
                <?php else : ?>
                    <?php foreach ($chart_revenue as $d) :
                        $h = (int) round(((float) $d->revenue / $max_rev) * 100);
                    ?>
                        <div class="wox-chart-bar-group">
                            <div class="wox-chart-bar wox-chart-bar-revenue" style="height:<?php echo max(4, $h); ?>%" title="<?php echo esc_attr($currency_sym . number_format((float) $d->revenue, 2)); ?>">
                            </div>
                            <span class="wox-chart-label"><?php echo esc_html(gmdate('M j', strtotime($d->date))); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="wox-section">
        <h2>
            <?php esc_html_e('Inbox', 'whatsapp-ox'); ?>
            <?php if ($unread_count > 0) : ?>
                <span class="wox-badge"><?php echo esc_html($unread_count); ?></span>
            <?php endif; ?>
        </h2>
        <?php if (empty($inbox)) : ?>
            <p class="wox-empty"><?php esc_html_e('No incoming messages yet.', 'whatsapp-ox'); ?></p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped wox-inbox-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('From', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Message', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Received', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Status', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Action', 'whatsapp-ox'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inbox as $msg) : ?>
                        <tr class="<?php echo $msg->status === 'unread' ? 'wox-inbox-unread' : ''; ?>">
                            <td>
                                <strong><?php echo esc_html($msg->name ?: $msg->phone); ?></strong>
                                <?php if ($msg->name) : ?>
                                    <br><small><?php echo esc_html($msg->phone); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(mb_substr($msg->message_body, 0, 100)); ?></td>
                            <td>
                                <?php
                                $created = new \DateTime($msg->created_at);
                                echo esc_html($created->format('M j, H:i'));
                                ?>
                            </td>
                            <td>
                                <span class="wox-status-badge wox-status-<?php echo esc_attr($msg->status); ?>">
                                    <?php echo esc_html(ucfirst($msg->status)); ?>
                                </span>
                            </td>
                            <td>
                                <button class="button wox-reply-btn" data-id="<?php echo esc_attr($msg->id); ?>" data-phone="<?php echo esc_attr($msg->phone); ?>">
                                    <?php esc_html_e('Reply', 'whatsapp-ox'); ?>
                                </button>
                            </td>
                        </tr>
                        <tr class="wox-reply-row" id="wox-reply-<?php echo esc_attr($msg->id); ?>" style="display:none;">
                            <td colspan="5">
                                <div class="wox-reply-form">
                                    <textarea class="wox-reply-text" rows="2" placeholder="<?php esc_attr_e('Type your reply...', 'whatsapp-ox'); ?>"></textarea>
                                    <div class="wox-reply-actions">
                                        <span class="wox-reply-error" style="color:#dc3232;display:none;"></span>
                                        <button class="button wox-reply-send" data-id="<?php echo esc_attr($msg->id); ?>">
                                            <?php esc_html_e('Send Reply', 'whatsapp-ox'); ?>
                                        </button>
                                        <button class="button wox-reply-cancel" data-id="<?php echo esc_attr($msg->id); ?>">
                                            <?php esc_html_e('Cancel', 'whatsapp-ox'); ?>
                                        </button>
                                        <span class="wox-reply-spinner" style="display:none;"><?php esc_html_e('Sending...', 'whatsapp-ox'); ?></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="wox-bottom-grid">
        <div class="wox-section">
            <h2><?php esc_html_e('Top Customers', 'whatsapp-ox'); ?></h2>
            <?php if (empty($top_customers)) : ?>
                <p class="wox-empty"><?php esc_html_e('No customer activity in this period.', 'whatsapp-ox'); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Phone', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Last Template', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Messages', 'whatsapp-ox'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_customers as $c) : ?>
                            <tr>
                                <td><?php echo esc_html($c->phone); ?></td>
                                <td><?php echo esc_html($c->template_name ?: '-'); ?></td>
                                <td><?php echo esc_html(number_format($c->msg_count)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="wox-section">
            <h2><?php esc_html_e('Recent Abandoned Carts', 'whatsapp-ox'); ?></h2>
            <?php if (empty($recent_carts)) : ?>
                <p class="wox-empty"><?php esc_html_e('No abandoned carts tracked yet.', 'whatsapp-ox'); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Phone', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Total', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Status', 'whatsapp-ox'); ?></th>
                            <th><?php esc_html_e('Reminders', 'whatsapp-ox'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_carts as $cart) : ?>
                            <tr>
                                <td><?php echo esc_html($cart->phone ?: '-'); ?></td>
                                <td><?php echo esc_html(wc_price($cart->cart_total)); ?></td>
                                <td>
                                    <span class="wox-status-badge wox-status-<?php echo esc_attr($cart->status); ?>">
                                        <?php echo esc_html(ucfirst($cart->status)); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($cart->reminder_count); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="wox-section">
        <h2><?php esc_html_e('Recent Messages', 'whatsapp-ox'); ?></h2>
        <?php if (empty($recent_messages)) : ?>
            <p class="wox-empty"><?php esc_html_e('No messages sent yet.', 'whatsapp-ox'); ?></p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Phone', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Message', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Template', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Status', 'whatsapp-ox'); ?></th>
                        <th><?php esc_html_e('Date', 'whatsapp-ox'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_messages as $m) : ?>
                        <tr>
                            <td><?php echo esc_html($m->phone); ?></td>
                            <td><?php echo esc_html(mb_substr($m->message_body, 0, 60)); ?></td>
                            <td><?php echo esc_html($m->template_name ?: '-'); ?></td>
                            <td>
                                <span class="wox-status-badge wox-status-<?php echo esc_attr($m->status); ?>">
                                    <?php echo esc_html(ucfirst($m->status)); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $created = new \DateTime($m->created_at);
                                echo esc_html($created->format('M j, H:i'));
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

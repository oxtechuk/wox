<div class="wrap">
    <h1><?php echo esc_html__('Campaigns', 'whatsapp-ox'); ?></h1>

    <div class="wox-campaign-layout" style="display:grid;grid-template-columns:2fr 1fr;gap:30px;align-items:start;">

        <div>
            <h2><?php echo esc_html__('Saved Campaigns', 'whatsapp-ox'); ?></h2>

            <?php if (empty($campaigns)) : ?>
                <p><?php echo esc_html__('No campaigns yet.', 'whatsapp-ox'); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php echo esc_html__('Name', 'whatsapp-ox'); ?></th>
                            <th><?php echo esc_html__('Status', 'whatsapp-ox'); ?></th>
                            <th><?php echo esc_html__('Sent / Total', 'whatsapp-ox'); ?></th>
                            <th><?php echo esc_html__('Created', 'whatsapp-ox'); ?></th>
                            <th><?php echo esc_html__('Actions', 'whatsapp-ox'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campaigns as $c) : ?>
                            <tr>
                                <td><a href="<?php echo esc_url(admin_url('admin.php?page=whatsapp-ox-campaigns&action=view&campaign_id=' . $c->id)); ?>"><?php echo esc_html($c->name); ?></a></td>
                                <td>
                                    <span style="color:<?php echo 'completed' === $c->status ? '#46b450' : ('running' === $c->status ? '#f0ad4e' : '#999'); ?>;">
                                        <?php echo esc_html($c->status); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($c->sent_count . ' / ' . $c->total_count); ?></td>
                                <td><?php echo esc_html($c->created_at); ?></td>
                                <td>
                                    <?php if ('draft' === $c->status && $c->total_count > 0) : ?>
                                        <form method="post" style="display:inline;">
                                            <?php wp_nonce_field('wox_campaign'); ?>
                                            <input type="hidden" name="campaign_id" value="<?php echo esc_attr($c->id); ?>" />
                                            <button type="submit" name="wox_send_campaign" class="button button-small" onclick="return confirm('<?php echo esc_js(__('Send this campaign now?', 'whatsapp-ox')); ?>');"><?php echo esc_html__('Send Now', 'whatsapp-ox'); ?></button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=whatsapp-ox-campaigns&action=view&campaign_id=' . $c->id)); ?>" class="button button-small"><?php echo esc_html__('View', 'whatsapp-ox'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
            <h2><?php echo esc_html__('New Campaign', 'whatsapp-ox'); ?></h2>

            <form method="post" action="">
                <?php wp_nonce_field('wox_campaign'); ?>

                <p>
                    <label for="campaign_name"><?php echo esc_html__('Campaign Name', 'whatsapp-ox'); ?></label>
                    <input type="text" id="campaign_name" name="name" class="regular-text" style="width:100%;" required />
                </p>

                <p>
                    <label for="template_id"><?php echo esc_html__('Use Template (optional)', 'whatsapp-ox'); ?></label>
                    <select id="template_id" name="template_id" style="width:100%;">
                        <option value=""><?php echo esc_html__('— Write custom message —', 'whatsapp-ox'); ?></option>
                        <?php foreach ($templates as $tpl) : ?>
                            <option value="<?php echo esc_attr($tpl->id); ?>"><?php echo esc_html($tpl->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="campaign_message"><?php echo esc_html__('Message Body', 'whatsapp-ox'); ?></label>
                    <textarea id="campaign_message" name="message" rows="5" style="width:100%;" required></textarea>
                    <span class="description"><?php echo esc_html__('Available:', 'whatsapp-ox'); ?> <code>{{first_name}}</code> <code>{{full_name}}</code> <code>{{store_name}}</code></span>
                </p>

                <hr />

                <h4><?php echo esc_html__('Customer Filter', 'whatsapp-ox'); ?></h4>

                <p>
                    <label for="segment_min_spent"><?php echo esc_html__('Min Total Spent', 'whatsapp-ox'); ?></label>
                    <input type="number" id="segment_min_spent" name="segment_min_spent" step="0.01" min="0" style="width:100%;" placeholder="0" />
                </p>
                <p>
                    <label for="segment_max_spent"><?php echo esc_html__('Max Total Spent', 'whatsapp-ox'); ?></label>
                    <input type="number" id="segment_max_spent" name="segment_max_spent" step="0.01" min="0" style="width:100%;" placeholder="<?php echo esc_attr__('No limit', 'whatsapp-ox'); ?>" />
                </p>
                <p>
                    <label for="segment_min_orders"><?php echo esc_html__('Min Orders Count', 'whatsapp-ox'); ?></label>
                    <input type="number" id="segment_min_orders" name="segment_min_orders" min="0" style="width:100%;" placeholder="0" />
                </p>

                <p class="submit">
                    <button type="submit" name="wox_create_campaign" class="button button-primary"><?php echo esc_html__('Create Campaign', 'whatsapp-ox'); ?></button>
                </p>
            </form>
        </div>
    </div>

    <div style="margin-top:30px;background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:4px;">
        <h2><?php echo esc_html__('All Customer Phones', 'whatsapp-ox'); ?></h2>
        <p><?php echo sprintf(esc_html__('Total %d phone numbers found.', 'whatsapp-ox'), count($customer_phones)); ?></p>

        <div style="max-height:300px;overflow-y:auto;background:#f0f0f1;padding:10px;border-radius:4px;font-family:monospace;font-size:12px;">
            <?php foreach ($customer_phones as $phone) : ?>
                <span style="display:inline-block;background:#fff;padding:3px 8px;margin:2px;border:1px solid #ddd;border-radius:3px;"><?php echo esc_html($phone); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

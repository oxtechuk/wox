<div class="wrap">
    <h1><?php echo esc_html__('Automations', 'whatsapp-ox'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('wox_automations'); ?>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Trigger', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Enabled', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Template', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($triggers as $key => $label) : ?>
                    <tr>
                        <td><?php echo esc_html($label); ?></td>
                        <td>
                            <input type="checkbox" name="automation_<?php echo esc_attr($key); ?>_enabled" value="yes" <?php checked($automations[$key]['enabled'] ?? '', 'yes'); ?> />
                        </td>
                        <td>
                            <select name="automation_<?php echo esc_attr($key); ?>_template">
                                <option value=""><?php echo esc_html__('Select template', 'whatsapp-ox'); ?></option>
                                <?php foreach ($templates as $tpl) : ?>
                                    <option value="<?php echo esc_attr($tpl->id); ?>" <?php selected($automations[$key]['template_id'] ?? '', $tpl->id); ?>><?php echo esc_html($tpl->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" name="wox_save_automations" class="button button-primary"><?php echo esc_html__('Save Automations', 'whatsapp-ox'); ?></button>
        </p>
    </form>
</div>

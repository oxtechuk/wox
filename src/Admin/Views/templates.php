<div class="wrap">
    <h1><?php echo esc_html__('Message Templates', 'whatsapp-ox'); ?></h1>

    <h2><?php echo esc_html__('New Template', 'whatsapp-ox'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('wox_template'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="name"><?php echo esc_html__('Template Name', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="name" name="name" class="regular-text" required /></td>
            </tr>
            <tr>
                <th scope="row"><label for="language"><?php echo esc_html__('Language', 'whatsapp-ox'); ?></label></th>
                <td>
                    <select id="language" name="language">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="header"><?php echo esc_html__('Header (optional)', 'whatsapp-ox'); ?></label></th>
                <td><textarea id="header" name="header" class="large-text" rows="2"></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="body"><?php echo esc_html__('Body', 'whatsapp-ox'); ?></label></th>
                <td>
                    <textarea id="body" name="body" class="large-text" rows="6" required></textarea>
                    <p class="description">
                        <?php echo esc_html__('Available variables:', 'whatsapp-ox'); ?>
                        <code>{{first_name}}</code> <code>{{full_name}}</code> <code>{{phone}}</code>
                        <code>{{order_number}}</code> <code>{{order_total}}</code> <code>{{currency}}</code>
                        <code>{{payment_method}}</code> <code>{{order_status}}</code> <code>{{store_name}}</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="footer"><?php echo esc_html__('Footer (optional)', 'whatsapp-ox'); ?></label></th>
                <td><textarea id="footer" name="footer" class="large-text" rows="2"></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="variables"><?php echo esc_html__('Custom Variables (comma separated)', 'whatsapp-ox'); ?></label></th>
                <td><input type="text" id="variables" name="variables" class="regular-text" placeholder="product_name, discount, expiry_date" /></td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="wox_save_template" class="button button-primary"><?php echo esc_html__('Save Template', 'whatsapp-ox'); ?></button>
        </p>
    </form>

    <h2 style="margin-top:40px;"><?php echo esc_html__('Saved Templates', 'whatsapp-ox'); ?></h2>

    <?php if (empty($templates)) : ?>
        <p><?php echo esc_html__('No templates yet.', 'whatsapp-ox'); ?></p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Name', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Language', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Status', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Created', 'whatsapp-ox'); ?></th>
                    <th><?php echo esc_html__('Actions', 'whatsapp-ox'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($templates as $template) : ?>
                    <tr>
                        <td><?php echo esc_html($template->name); ?></td>
                        <td><?php echo esc_html($template->language); ?></td>
                        <td><?php echo esc_html($template->status); ?></td>
                        <td><?php echo esc_html($template->created_at); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=whatsapp-ox-templates&action=delete&id=' . $template->id)); ?>" class="button button-small" onclick="return confirm('<?php echo esc_js(__('Delete this template?', 'whatsapp-ox')); ?>');"><?php echo esc_html__('Delete', 'whatsapp-ox'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

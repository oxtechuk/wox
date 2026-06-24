<?php

namespace Wox\Models;

defined('ABSPATH') || exit;

class Template
{
    public ?int $id;
    public string $name;
    public string $language = 'en';
    public ?string $header;
    public string $body;
    public ?string $footer;
    public ?string $variables;
    public string $status = 'pending';
    public ?string $provider_template_id;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function get_variable_list(): array
    {
        if (empty($this->variables)) {
            return [];
        }
        return array_map('trim', explode(',', $this->variables));
    }

    public function save(): void
    {
        global $wpdb;

        $data = [
            'name' => $this->name,
            'language' => $this->language,
            'header' => $this->header,
            'body' => $this->body,
            'footer' => $this->footer,
            'variables' => $this->variables,
            'status' => $this->status,
            'provider_template_id' => $this->provider_template_id,
        ];

        if ($this->id) {
            $wpdb->update($wpdb->prefix . 'wox_templates', $data, ['id' => $this->id]);
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_templates', $data);
            $this->id = $wpdb->insert_id;
        }
    }
}

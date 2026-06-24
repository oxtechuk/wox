<?php

namespace Wox\Models;

defined('ABSPATH') || exit;

class Campaign
{
    public ?int $id;
    public string $name;
    public ?int $template_id;
    public ?string $segment_data;
    public string $status = 'draft';
    public int $sent_count = 0;
    public int $total_count = 0;
    public ?string $scheduled_at;
    public ?string $started_at;
    public ?string $completed_at;
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

    public function get_segment(): array
    {
        return json_decode($this->segment_data, true) ?: [];
    }

    public function is_running(): bool
    {
        return 'running' === $this->status;
    }

    public function is_completed(): bool
    {
        return 'completed' === $this->status;
    }

    public function save(): void
    {
        global $wpdb;

        $data = [
            'name' => $this->name,
            'template_id' => $this->template_id,
            'segment_data' => $this->segment_data,
            'status' => $this->status,
            'sent_count' => $this->sent_count,
            'total_count' => $this->total_count,
            'scheduled_at' => $this->scheduled_at,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
        ];

        if ($this->id) {
            $wpdb->update($wpdb->prefix . 'wox_campaigns', $data, ['id' => $this->id]);
        } else {
            $wpdb->insert($wpdb->prefix . 'wox_campaigns', $data);
            $this->id = $wpdb->insert_id;
        }
    }
}

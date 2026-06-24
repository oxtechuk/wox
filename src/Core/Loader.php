<?php

namespace Wox\Core;

defined('ABSPATH') || exit;

class Loader
{
    protected array $actions = [];
    protected array $filters = [];

    public function add_action(string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->actions[] = compact('hook', 'component', 'callback', 'priority', 'accepted_args');
    }

    public function add_filter(string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->filters[] = compact('hook', 'component', 'callback', 'priority', 'accepted_args');
    }

    public function run(): void
    {
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }
    }
}

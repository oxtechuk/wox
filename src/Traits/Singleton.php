<?php

namespace Wox\Traits;

defined('ABSPATH') || exit;

trait Singleton
{
    private static $instance = null;

    public static function get_instance(...$args)
    {
        if (null === self::$instance) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

    private function __construct() {}
    protected function __clone() {}
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}

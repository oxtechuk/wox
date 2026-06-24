<?php

namespace Wox\Database;

use Wox\Traits\Singleton;

defined('ABSPATH') || exit;

class Migrator
{
    use Singleton;

    public function migrate(): void
    {
        $current_version = get_option('wox_db_version', '0.0.0');

        if (version_compare($current_version, Schema::DB_VERSION, '>=')) {
            return;
        }

        Schema::create_tables();

        update_option('wox_db_version', Schema::DB_VERSION);
    }
}

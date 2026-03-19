<?php

/**
 * Dashboard plugin install process.
 */
function plugin_dashboard_install(): bool
{
    global $DB;

    $queries = [
        "CREATE TABLE IF NOT EXISTS `glpi_plugin_dashboard_count` (
            `type` INT NOT NULL,
            `id` INT NOT NULL,
            `quant` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`type`, `id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        "CREATE TABLE IF NOT EXISTS `glpi_plugin_dashboard_map` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `entities_id` INT NOT NULL,
            `location` VARCHAR(50) DEFAULT NULL,
            `lat` FLOAT NOT NULL,
            `lng` FLOAT NOT NULL,
            PRIMARY KEY (`id`, `entities_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        "CREATE TABLE IF NOT EXISTS `glpi_plugin_dashboard_config` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `value` VARCHAR(125) NOT NULL,
            `users_id` VARCHAR(25) NOT NULL DEFAULT '',
            PRIMARY KEY (`id`),
            UNIQUE KEY `name_user` (`name`, `users_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($queries as $sql) {
        if (!$DB->query($sql)) {
            return false;
        }
    }

    if (!$DB->query("INSERT IGNORE INTO glpi_plugin_dashboard_count (`type`, `id`, `quant`) VALUES (1, 0, 1)")) {
        return false;
    }

    // Migrate old config values that used -1 for entities selection.
    if (
        !$DB->query(
            "UPDATE glpi_plugin_dashboard_config
             SET `value` = ''
             WHERE `name` = 'entity' AND `value` = '-1'"
        )
    ) {
        return false;
    }

    // Keep backward compatibility with legacy logic that upserts map by location.
    if (!plugin_dashboard_index_exists('glpi_plugin_dashboard_map', 'location')) {
        if (!$DB->query("ALTER TABLE `glpi_plugin_dashboard_map` ADD UNIQUE KEY `location` (`location`)")) {
            return false;
        }
    }

    return true;
}

/**
 * Dashboard plugin uninstall process.
 */
function plugin_dashboard_uninstall(): bool
{
    global $DB;

    $queries = [
        "DROP TABLE IF EXISTS `glpi_plugin_dashboard_count`",
        "DROP TABLE IF EXISTS `glpi_plugin_dashboard_map`",
        "DROP TABLE IF EXISTS `glpi_plugin_dashboard_config`",
    ];

    foreach ($queries as $sql) {
        if (!$DB->query($sql)) {
            return false;
        }
    }

    return true;
}

/**
 * Check if an index exists on a given table.
 */
function plugin_dashboard_index_exists(string $table, string $index): bool
{
    global $DB;

    $escaped_table = $DB->escape($table);
    $escaped_index = $DB->escape($index);
    $result = $DB->query(
        "SHOW INDEX FROM `$escaped_table` WHERE Key_name = '$escaped_index'"
    );

    if ($result === false) {
        return false;
    }

    return $DB->numrows($result) > 0;
}

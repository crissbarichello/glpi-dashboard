<?php

/**
 * Dashboard plugin for GLPI.
 */
define('PLUGIN_DASHBOARD_VERSION', '1.0.5');
define('PLUGIN_DASHBOARD_MIN_GLPI_VERSION', '10.0.0');
define('PLUGIN_DASHBOARD_MAX_GLPI_VERSION', '12.0.0');

/**
 * Init hooks of the plugin.
 */
function plugin_init_dashboard(): void
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['dashboard'] = true;

    Plugin::registerClass('PluginDashboardConfig', [
        'addtabon' => ['Entity']
    ]);

    $PLUGIN_HOOKS['menu_toadd']['dashboard'] = ['plugins' => 'PluginDashboardConfig'];
    $PLUGIN_HOOKS['config_page']['dashboard'] = 'front/index.php';
}

/**
 * Get plugin metadata.
 *
 * @return array<string, mixed>
 */
function plugin_version_dashboard(): array
{
    return [
        'name'         => __('Dashboard', 'dashboard'),
        'version'      => PLUGIN_DASHBOARD_VERSION,
        'author'       => '<a href="https://plugins.glpi-project.org/#/plugin/dashboard">Stevenes Donato</a>',
        'license'      => 'GPLv2+',
        'homepage'     => 'https://plugins.glpi-project.org/#/plugin/dashboard',
        'requirements' => [
            'glpi' => [
                'min' => PLUGIN_DASHBOARD_MIN_GLPI_VERSION,
                'max' => PLUGIN_DASHBOARD_MAX_GLPI_VERSION,
            ],
        ],
    ];
}

/**
 * Check pre-requisites before install.
 */
function plugin_dashboard_check_prerequisites(): bool
{
    if (
        version_compare(GLPI_VERSION, PLUGIN_DASHBOARD_MIN_GLPI_VERSION, '>=')
        && version_compare(GLPI_VERSION, PLUGIN_DASHBOARD_MAX_GLPI_VERSION, '<')
    ) {
        return true;
    }

    if (method_exists('Plugin', 'messageIncompatible')) {
        Plugin::messageIncompatible(
            'core',
            PLUGIN_DASHBOARD_MIN_GLPI_VERSION,
            PLUGIN_DASHBOARD_MAX_GLPI_VERSION
        );
    } else {
        echo sprintf(
            'Dashboard plugin is not compatible with GLPI %s. Supported range: >= %s and < %s.',
            GLPI_VERSION,
            PLUGIN_DASHBOARD_MIN_GLPI_VERSION,
            PLUGIN_DASHBOARD_MAX_GLPI_VERSION
        );
    }
    return false;
}

/**
 * Check configuration process.
 */
function plugin_dashboard_check_config(bool $verbose = false): bool
{
    if ($verbose) {
        echo 'Installed / not configured';
    }
    return true;
}

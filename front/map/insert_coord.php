<?php

include ("../../../../inc/includes.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Html::redirect($CFG_GLPI['root_doc'] . '/front/entity.php');
}

if (method_exists('Session', 'checkCSRF')) {
    Session::checkCSRF($_POST);
}

$ent_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$lng = isset($_POST['lng']) ? trim((string)$_POST['lng']) : '';
$lat = isset($_POST['lat']) ? trim((string)$_POST['lat']) : '';

if ($ent_id <= 0) {
    Html::redirect($CFG_GLPI['root_doc'] . '/front/entity.php');
}

if ($lng !== '' && $lat !== '') {
    $lng_value = (float)$lng;
    $lat_value = (float)$lat;

    $query = "SELECT name FROM glpi_entities WHERE id = ".$ent_id;
    $result = $DB->query($query);
    if ($result !== false) {
        $location = $DB->result($result, 0, 'name');
        $location = $DB->escape($location);

        $exists = $DB->query("SELECT id FROM glpi_plugin_dashboard_map WHERE entities_id = ".$ent_id." LIMIT 1");
        if ($exists !== false && $DB->numrows($exists) > 0) {
            $map_id = (int)$DB->result($exists, 0, 'id');
            $DB->query("
                UPDATE glpi_plugin_dashboard_map
                SET location = '$location', lat = $lat_value, lng = $lng_value
                WHERE id = $map_id
            ");
        } else {
            $DB->query("
                INSERT INTO glpi_plugin_dashboard_map (entities_id, location, lat, lng)
                VALUES ($ent_id, '$location', $lat_value, $lng_value)
            ");
        }
    }

    Html::redirect($CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id);
}

if ($lng === '' && $lat === '') {
    $query = "DELETE FROM glpi_plugin_dashboard_map WHERE entities_id = ".$ent_id;
    $DB->query($query);
}

Html::redirect($CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id);


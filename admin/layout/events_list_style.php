<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminEventsListStyle = BASE_URL . '/public/assets/css/admin/admin_events_list.css';
$adminExtraStyles[] = $adminEventsListStyle;
if (defined('ADMIN_EXTRA_STYLES_RENDERED')) {
    echo '<link rel="stylesheet" href="' . htmlspecialchars($adminEventsListStyle, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
}
?>

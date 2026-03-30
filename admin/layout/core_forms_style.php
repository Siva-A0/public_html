<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminCoreFormsStyle = BASE_URL . '/public/assets/css/admin/admin_core_forms.css';
$adminExtraStyles[] = $adminCoreFormsStyle;
if (defined('ADMIN_EXTRA_STYLES_RENDERED')) {
    echo '<link rel="stylesheet" href="' . htmlspecialchars($adminCoreFormsStyle, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
}
?>

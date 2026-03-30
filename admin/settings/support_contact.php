<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbSupportSettings = TB_SUPPORT_SETTINGS;

$message = '';
$messageType = 'success';

$settings = $fcObj->getSupportSettings($tbSupportSettings);
$supportEmail = isset($settings['support_email']) ? (string)$settings['support_email'] : '';
$whatsappNumber = isset($settings['whatsapp_number']) ? (string)$settings['whatsapp_number'] : '';
$smtpHost = isset($settings['smtp_host']) ? (string)$settings['smtp_host'] : '';
$smtpPort = isset($settings['smtp_port']) ? (int)$settings['smtp_port'] : 587;
$smtpSecure = isset($settings['smtp_secure']) ? (string)$settings['smtp_secure'] : 'tls';
$smtpUsername = isset($settings['smtp_username']) ? (string)$settings['smtp_username'] : '';
$smtpPassword = isset($settings['smtp_password']) ? (string)$settings['smtp_password'] : '';
$smtpFromEmail = isset($settings['smtp_from_email']) ? (string)$settings['smtp_from_email'] : '';
$smtpFromName = isset($settings['smtp_from_name']) ? (string)$settings['smtp_from_name'] : '';

if (isset($_POST['save_support_contact'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please try again.';
        $messageType = 'danger';
    } else {
        $supportEmail = trim((string)($_POST['support_email'] ?? ''));
        $whatsappNumber = trim((string)($_POST['whatsapp_number'] ?? ''));
        $smtpHost = trim((string)($_POST['smtp_host'] ?? ''));
        $smtpPort = (int)($_POST['smtp_port'] ?? 587);
        if ($smtpPort <= 0) {
            $smtpPort = 587;
        }
        $smtpSecure = strtolower(trim((string)($_POST['smtp_secure'] ?? 'tls')));
        if (!in_array($smtpSecure, array('none', 'ssl', 'tls'), true)) {
            $smtpSecure = 'tls';
        }
        $smtpUsername = trim((string)($_POST['smtp_username'] ?? ''));
        $smtpPassword = trim((string)($_POST['smtp_password'] ?? ''));
        $smtpFromEmail = trim((string)($_POST['smtp_from_email'] ?? ''));
        $smtpFromName = trim((string)($_POST['smtp_from_name'] ?? ''));

        $smtpAnySet = ($smtpHost !== '' || $smtpUsername !== '' || $smtpPassword !== '' || $smtpFromEmail !== '');

        if ($supportEmail === '' && $whatsappNumber === '') {
            $message = 'Enter at least one contact method (email or WhatsApp).';
            $messageType = 'danger';
        } elseif ($supportEmail !== '' && !filter_var($supportEmail, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid support email address.';
            $messageType = 'danger';
        } elseif ($smtpAnySet && ($smtpHost === '' || $smtpUsername === '' || $smtpPassword === '' || $smtpFromEmail === '')) {
            $message = 'For SMTP, host, username, password, and from email are required.';
            $messageType = 'danger';
        } elseif ($smtpFromEmail !== '' && !filter_var($smtpFromEmail, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid SMTP from email address.';
            $messageType = 'danger';
        } else {
            $saved = $fcObj->updateSupportSettings($tbSupportSettings, $supportEmail, $whatsappNumber, array(
                'smtp_host' => $smtpHost,
                'smtp_port' => $smtpPort,
                'smtp_secure' => $smtpSecure,
                'smtp_username' => $smtpUsername,
                'smtp_password' => $smtpPassword,
                'smtp_from_email' => $smtpFromEmail,
                'smtp_from_name' => $smtpFromName
            ));
            if ($saved !== false) {
                $message = 'Support contact settings updated successfully.';
                $messageType = 'success';
            } else {
                $message = 'Unable to update support contact settings. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}
?>

<div class="container-fluid support-contact-page">
    <div class="page-shell">
    <div class="page-hero">
        <h3 class="page-title">Support Contact Settings</h3>
        <p class="page-subtitle">Configure where student support requests are sent.</p>
    </div>

    <div class="card settings-card">
        <div class="card-body p-4">
            <?php if ($message !== '') { ?>
                <div class="alert alert-<?php echo $messageType; ?> py-2">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <form method="post" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Support Email</label>
                        <input
                            type="email"
                            name="support_email"
                            class="form-control"
                            value="<?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="support@example.com"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp Number (with country code)</label>
                        <input
                            type="text"
                            name="whatsapp_number"
                            class="form-control"
                            value="<?php echo htmlspecialchars($whatsappNumber, ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="919876543210"
                        >
                    </div>

                    <div class="col-12 mt-3">
                        <h5 class="mb-2">SMTP Settings (Optional, recommended)</h5>
                        <div class="text-muted mb-2" style="font-size:13px;">If provided, emails are sent via SMTP on both local and hosted servers.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control" value="<?php echo htmlspecialchars($smtpHost, ENT_QUOTES, 'UTF-8'); ?>" placeholder="smtp.gmail.com">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SMTP Port</label>
                        <input type="number" name="smtp_port" class="form-control" value="<?php echo (int)$smtpPort; ?>" placeholder="587">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Security</label>
                        <select name="smtp_secure" class="form-select">
                            <option value="tls" <?php echo $smtpSecure === 'tls' ? 'selected' : ''; ?>>TLS</option>
                            <option value="ssl" <?php echo $smtpSecure === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                            <option value="none" <?php echo $smtpSecure === 'none' ? 'selected' : ''; ?>>None</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SMTP Username</label>
                        <input type="text" name="smtp_username" class="form-control" value="<?php echo htmlspecialchars($smtpUsername, ENT_QUOTES, 'UTF-8'); ?>" placeholder="your-email@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SMTP Password / App Password</label>
                        <input type="password" name="smtp_password" class="form-control" value="<?php echo htmlspecialchars($smtpPassword, ENT_QUOTES, 'UTF-8'); ?>" placeholder="App password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Email</label>
                        <input type="email" name="smtp_from_email" class="form-control" value="<?php echo htmlspecialchars($smtpFromEmail, ENT_QUOTES, 'UTF-8'); ?>" placeholder="no-reply@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Name</label>
                        <input type="text" name="smtp_from_name" class="form-control" value="<?php echo htmlspecialchars($smtpFromName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="AIML Department Support">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" name="save_support_contact" class="btn btn-primary">
                        Save Settings
                    </button>
                    <a href="<?php echo BASE_URL; ?>/admin/settings/otheroperations.php" class="btn btn-outline-secondary">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<?php include_once('../layout/footer.php'); ?>


<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!function_exists('supportReadSmtpResponse')) {
    function supportReadSmtpResponse($socket){
        $response = '';
        while (!feof($socket)) {
            $line = fgets($socket, 515);
            if ($line === false) {
                break;
            }
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return $response;
    }
}

if (!function_exists('supportSmtpCommand')) {
    function supportSmtpCommand($socket, $command, $expectedCodes){
        if ($command !== null) {
            fwrite($socket, $command . "\r\n");
        }
        $response = supportReadSmtpResponse($socket);
        $code = (int)substr((string)$response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('supportSendViaSmtp')) {
    function supportSendViaSmtp($to, $subject, $body, $replyTo, $smtpSettings, &$errorMessage){
        $host = trim((string)($smtpSettings['smtp_host'] ?? ''));
        $port = (int)($smtpSettings['smtp_port'] ?? 587);
        $secure = strtolower(trim((string)($smtpSettings['smtp_secure'] ?? 'tls')));
        $username = trim((string)($smtpSettings['smtp_username'] ?? ''));
        $password = trim((string)($smtpSettings['smtp_password'] ?? ''));
        $fromEmail = trim((string)($smtpSettings['smtp_from_email'] ?? ''));
        $fromName = trim((string)($smtpSettings['smtp_from_name'] ?? ''));
        if ($host === '' || $username === '' || $password === '' || $fromEmail === '') {
            $errorMessage = 'SMTP settings are incomplete.';
            return false;
        }
        if ($port <= 0) {
            $port = ($secure === 'ssl') ? 465 : 587;
        }
        $transportHost = $host;
        if ($secure === 'ssl') {
            $transportHost = 'ssl://' . $host;
        }
        $socket = @fsockopen($transportHost, $port, $errno, $errstr, 20);
        if (!$socket) {
            $errorMessage = 'SMTP connect failed: ' . $errstr;
            return false;
        }
        stream_set_timeout($socket, 20);
        if (!supportSmtpCommand($socket, null, array(220))) {
            fclose($socket);
            $errorMessage = 'SMTP greeting failed.';
            return false;
        }
        if (!supportSmtpCommand($socket, 'EHLO localhost', array(250))) {
            fclose($socket);
            $errorMessage = 'SMTP EHLO failed.';
            return false;
        }
        if ($secure === 'tls') {
            if (!supportSmtpCommand($socket, 'STARTTLS', array(220))) {
                fclose($socket);
                $errorMessage = 'SMTP STARTTLS failed.';
                return false;
            }
            $cryptoEnabled = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if (!$cryptoEnabled) {
                fclose($socket);
                $errorMessage = 'SMTP TLS negotiation failed.';
                return false;
            }
            if (!supportSmtpCommand($socket, 'EHLO localhost', array(250))) {
                fclose($socket);
                $errorMessage = 'SMTP EHLO after TLS failed.';
                return false;
            }
        }
        if (!supportSmtpCommand($socket, 'AUTH LOGIN', array(334)) || !supportSmtpCommand($socket, base64_encode($username), array(334)) || !supportSmtpCommand($socket, base64_encode($password), array(235))) {
            fclose($socket);
            $errorMessage = 'SMTP authentication failed.';
            return false;
        }
        if (!supportSmtpCommand($socket, 'MAIL FROM:<' . $fromEmail . '>', array(250)) || !supportSmtpCommand($socket, 'RCPT TO:<' . $to . '>', array(250, 251)) || !supportSmtpCommand($socket, 'DATA', array(354))) {
            fclose($socket);
            $errorMessage = 'SMTP envelope/data command failed.';
            return false;
        }
        $encodedSubject = function_exists('mb_encode_mimeheader') ? mb_encode_mimeheader($subject, 'UTF-8') : $subject;
        $headers = array();
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'From: ' . ($fromName !== '' ? $fromName . ' ' : '') . '<' . $fromEmail . '>';
        if ($replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Subject: ' . $encodedSubject;
        $headers[] = 'To: <' . $to . '>';
        $safeBody = str_replace(array("\r\n", "\r"), "\n", $body);
        $safeBody = str_replace("\n.", "\n..", $safeBody);
        $data = implode("\r\n", $headers) . "\r\n\r\n" . str_replace("\n", "\r\n", $safeBody) . "\r\n.";
        if (!supportSmtpCommand($socket, $data, array(250))) {
            fclose($socket);
            $errorMessage = 'SMTP message body send failed.';
            return false;
        }
        supportSmtpCommand($socket, 'QUIT', array(221, 250));
        fclose($socket);
        return true;
    }
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();
$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$supportMessage = '';
$supportMessageType = '';
$whatsAppRedirectUrl = '';
$mailToRedirectUrl = '';
$subject = '';
$messageBody = '';
$supportSettings = $fcObj->getSupportSettings(TB_SUPPORT_SETTINGS);
$supportEmail = trim((string)($supportSettings['support_email'] ?? ''));
$supportWhatsappNumber = trim((string)($supportSettings['whatsapp_number'] ?? ''));

if (isset($_POST['submit_support'])) {
    $subject = trim((string)($_POST['subject'] ?? ''));
    $messageBody = trim((string)($_POST['message'] ?? ''));
    if ($subject === '' || $messageBody === '') {
        $supportMessage = 'Subject and message are required.';
        $supportMessageType = 'danger';
    } else {
        $user = $userData[0];
        $studentName = trim(((string)($user['firstname'] ?? '')) . ' ' . ((string)($user['lastname'] ?? '')));
        if ($studentName === '') {
            $studentName = (string)($_SESSION['userName'] ?? 'Student');
        }
        $studentEmail = trim((string)($user['mail_id'] ?? ''));
        $studentUsername = (string)($_SESSION['userName'] ?? '');
        $emailSubject = '[Student Support] ' . $subject;
        $emailBody = "Student Support Request\n\n" . "Student Name: " . $studentName . "\n" . "Username: " . $studentUsername . "\n" . "Email: " . $studentEmail . "\n\n" . "Subject: " . $subject . "\n" . "Message:\n" . $messageBody . "\n";
        $emailConfigured = ($supportEmail !== '' && filter_var($supportEmail, FILTER_VALIDATE_EMAIL));
        $smtpSettings = array(
            'smtp_host' => trim((string)($supportSettings['smtp_host'] ?? '')),
            'smtp_port' => (int)($supportSettings['smtp_port'] ?? 587),
            'smtp_secure' => trim((string)($supportSettings['smtp_secure'] ?? 'tls')),
            'smtp_username' => trim((string)($supportSettings['smtp_username'] ?? '')),
            'smtp_password' => trim((string)($supportSettings['smtp_password'] ?? '')),
            'smtp_from_email' => trim((string)($supportSettings['smtp_from_email'] ?? '')),
            'smtp_from_name' => trim((string)($supportSettings['smtp_from_name'] ?? ''))
        );
        $smtpConfigured = ($smtpSettings['smtp_host'] !== '' && $smtpSettings['smtp_username'] !== '' && $smtpSettings['smtp_password'] !== '' && $smtpSettings['smtp_from_email'] !== '');
        $mailSent = false;
        $smtpError = '';
        if ($emailConfigured) {
            if ($smtpConfigured) {
                $mailSent = supportSendViaSmtp($supportEmail, $emailSubject, $emailBody, $studentEmail, $smtpSettings, $smtpError);
            } else {
                $fromDomain = isset($_SERVER['HTTP_HOST']) ? preg_replace('/:\d+$/', '', (string)$_SERVER['HTTP_HOST']) : 'localhost';
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $headers .= "From: no-reply@" . $fromDomain . "\r\n";
                if ($studentEmail !== '') {
                    $headers .= "Reply-To: " . $studentEmail . "\r\n";
                }
                $mailSent = @mail($supportEmail, $emailSubject, $emailBody, $headers);
            }
            if (!$mailSent) {
                $mailToRedirectUrl = 'mailto:' . rawurlencode($supportEmail) . '?subject=' . rawurlencode($emailSubject) . '&body=' . rawurlencode($emailBody);
            }
        }
        $waNumber = preg_replace('/\D+/', '', $supportWhatsappNumber);
        $waConfigured = ($waNumber !== '');
        if ($waConfigured) {
            $waMessage = "Student Support Request\n" . "Student: " . $studentName . " (" . $studentUsername . ")\n" . "Subject: " . $subject . "\n" . "Message: " . $messageBody;
            $whatsAppRedirectUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);
        }
        if (!$emailConfigured && !$waConfigured) {
            $supportMessage = 'Support contact is not configured by admin yet.';
            $supportMessageType = 'danger';
        } elseif ($emailConfigured && !$mailSent && !$waConfigured) {
            $supportMessage = 'Email send failed on server. Your mail app will open with a prefilled draft.';
            $supportMessageType = 'warning';
            $subject = '';
            $messageBody = '';
        } elseif ($emailConfigured && !$mailSent && $waConfigured) {
            $supportMessage = 'Email send failed on server, but WhatsApp is ready. Mail app draft will also open.';
            $supportMessageType = 'warning';
            $subject = '';
            $messageBody = '';
        } elseif ($emailConfigured && $mailSent && $waConfigured) {
            $supportMessage = 'Your support request was sent by email and WhatsApp will open now.';
            $supportMessageType = 'success';
            $subject = '';
            $messageBody = '';
        } elseif ($emailConfigured && $mailSent) {
            $supportMessage = 'Your support request was sent successfully by email.';
            $supportMessageType = 'success';
            $subject = '';
            $messageBody = '';
        } else {
            $supportMessage = 'Your support request message is ready in WhatsApp.';
            $supportMessageType = 'success';
            $subject = '';
            $messageBody = '';
        }
    }
}

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'studentsupport';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:820px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.student-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--sp-text);font-size:14px;font-weight:700}.student-meta-pill strong{color:var(--sp-primary)}.student-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.38fr);gap:20px}.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.student-alert.alert-success{background:#ecf7ef;color:#12653a}.student-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.student-alert.alert-warning{background:#fff7e7;color:#8a5a00}.student-alert.alert-info{background:#edf5ff;color:#1f568d}.student-form-grid{display:grid;gap:16px}.student-field{display:grid;gap:8px}.student-label{color:var(--sp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.student-input,.student-textarea{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--sp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.student-input:focus,.student-textarea:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.student-textarea{min-height:160px;resize:vertical}.student-primary-btn,.student-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;text-decoration:none}.student-primary-btn{background:linear-gradient(135deg,var(--sp-primary),var(--sp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.student-link-btn{background:#e8f0f8;color:var(--sp-primary)}.student-info-list{display:grid;gap:12px}.student-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-info-card h3{margin:0 0 8px;color:var(--sp-primary-deep);font-size:17px;font-weight:800}.student-info-card p{margin:0;color:var(--sp-text);font-size:14px;line-height:1.6}.student-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.student-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel{padding:18px;border-radius:20px}.student-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="student-page">
    <section class="student-hero">
        <span class="student-kicker">Support Desk</span>
        <h1>Student Support</h1>
        <p>Send your issue to the support team from one cleaner help desk page, with email and WhatsApp follow-up paths when those channels are configured.</p>
        <div class="student-meta-line">
            <?php if ($supportEmail !== '') { ?><span class="student-meta-pill"><strong>Email</strong> <?php echo htmlspecialchars($supportEmail); ?></span><?php } ?>
            <?php if ($supportWhatsappNumber !== '') { ?><span class="student-meta-pill"><strong>WhatsApp</strong> <?php echo htmlspecialchars($supportWhatsappNumber); ?></span><?php } ?>
        </div>
    </section>

    <?php if ($supportMessage !== '') { ?><div class="student-alert alert alert-<?php echo $supportMessageType; ?>"><?php echo htmlspecialchars($supportMessage); ?></div><?php } ?>
    <?php if ($whatsAppRedirectUrl !== '') { ?><div class="student-alert alert alert-info">If WhatsApp did not open automatically, <a href="<?php echo htmlspecialchars($whatsAppRedirectUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">open it here</a>.</div><?php } ?>
    <?php if ($mailToRedirectUrl !== '') { ?><div class="student-alert alert alert-info">If your mail app did not open automatically, <a href="<?php echo htmlspecialchars($mailToRedirectUrl, ENT_QUOTES, 'UTF-8'); ?>">open the draft here</a>.</div><?php } ?>

    <section class="student-layout-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Raise a Support Request</h2><p class="student-panel-subtitle">Describe the issue clearly so the team can respond faster.</p></div>
                <span class="student-tag">Request</span>
            </div>
            <form method="POST" action="" class="student-form-grid">
                <div class="student-field"><label class="student-label">Subject</label><input type="text" name="subject" class="student-input" placeholder="Example: Unable to access previous papers" value="<?php echo htmlspecialchars($subject); ?>" required></div>
                <div class="student-field"><label class="student-label">Message</label><textarea name="message" class="student-textarea" placeholder="Describe your issue clearly, include where and when it happened." required><?php echo htmlspecialchars($messageBody); ?></textarea></div>
                <div class="student-actions"><button type="submit" name="submit_support" class="student-primary-btn"><i class="bi bi-send me-1"></i> Submit Request</button></div>
            </form>
        </div>
        <aside class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">How Support Works</h2><p class="student-panel-subtitle">A quick reminder of the available contact flow.</p></div>
                <span class="student-tag">Guide</span>
            </div>
            <div class="student-info-list">
                <article class="student-info-card"><h3>Email Route</h3><p>If support email is configured, your request is sent directly from the portal or prepared in your mail app.</p></article>
                <article class="student-info-card"><h3>WhatsApp Route</h3><p>If WhatsApp support is configured, a prefilled support message can open instantly for faster follow-up.</p></article>
                <article class="student-info-card"><h3>Best Practice</h3><p>Include the exact issue, page name, and what you were trying to do so the team can reproduce the problem quickly.</p></article>
            </div>
        </aside>
    </section>
</div>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>

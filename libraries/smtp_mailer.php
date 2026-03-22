<?php

if (!function_exists('app_smtp_read_response')) {
    function app_smtp_read_response($socket){
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

if (!function_exists('app_smtp_command')) {
    function app_smtp_command($socket, $command, $expectedCodes){
        if ($command !== null) {
            fwrite($socket, $command . "\r\n");
        }

        $response = app_smtp_read_response($socket);
        $code = (int)substr((string)$response, 0, 3);
        return in_array($code, (array)$expectedCodes, true);
    }
}

if (!function_exists('app_send_mail_via_smtp')) {
    /**
     * Minimal SMTP sender (LOGIN auth, optional TLS/SSL).
     * $smtpSettings keys: smtp_host, smtp_port, smtp_secure (tls|ssl|none), smtp_username, smtp_password, smtp_from_email, smtp_from_name
     */
    function app_send_mail_via_smtp($to, $subject, $bodyText, $replyTo, $smtpSettings, &$errorMessage){
        $errorMessage = '';

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

        $transportHost = ($secure === 'ssl') ? ('ssl://' . $host) : $host;
        $socket = @fsockopen($transportHost, $port, $errno, $errstr, 20);
        if (!$socket) {
            $errorMessage = 'SMTP connect failed: ' . $errstr;
            return false;
        }

        stream_set_timeout($socket, 20);

        if (!app_smtp_command($socket, null, array(220))) {
            fclose($socket);
            $errorMessage = 'SMTP greeting failed.';
            return false;
        }

        if (!app_smtp_command($socket, 'EHLO localhost', array(250))) {
            fclose($socket);
            $errorMessage = 'SMTP EHLO failed.';
            return false;
        }

        if ($secure === 'tls') {
            if (!app_smtp_command($socket, 'STARTTLS', array(220))) {
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

            if (!app_smtp_command($socket, 'EHLO localhost', array(250))) {
                fclose($socket);
                $errorMessage = 'SMTP EHLO after TLS failed.';
                return false;
            }
        }

        if (!app_smtp_command($socket, 'AUTH LOGIN', array(334)) ||
            !app_smtp_command($socket, base64_encode($username), array(334)) ||
            !app_smtp_command($socket, base64_encode($password), array(235))) {
            fclose($socket);
            $errorMessage = 'SMTP authentication failed.';
            return false;
        }

        $to = trim((string)$to);
        $subject = trim((string)$subject);
        $bodyText = (string)$bodyText;
        $replyTo = trim((string)$replyTo);

        if ($to === '' || $subject === '' || $bodyText === '') {
            fclose($socket);
            $errorMessage = 'Email payload is incomplete.';
            return false;
        }

        if (!app_smtp_command($socket, 'MAIL FROM:<' . $fromEmail . '>', array(250)) ||
            !app_smtp_command($socket, 'RCPT TO:<' . $to . '>', array(250, 251)) ||
            !app_smtp_command($socket, 'DATA', array(354))) {
            fclose($socket);
            $errorMessage = 'SMTP envelope/data command failed.';
            return false;
        }

        $fromHeader = $fromName !== '' ? ($fromName . ' <' . $fromEmail . '>') : $fromEmail;

        $headers = array(
            'From: ' . $fromHeader,
            'To: ' . $to,
            'Subject: ' . $subject,
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit'
        );

        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        // Dot-stuffing for SMTP transparency.
        $bodyLines = preg_split("/\\r\\n|\\n|\\r/", $bodyText);
        $safeBody = '';
        foreach ($bodyLines as $line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
            $safeBody .= $line . "\r\n";
        }

        $message = implode("\r\n", $headers) . "\r\n\r\n" . $safeBody . "\r\n.\r\n";
        fwrite($socket, $message);

        if (!app_smtp_command($socket, null, array(250))) {
            fclose($socket);
            $errorMessage = 'SMTP send failed.';
            return false;
        }

        app_smtp_command($socket, 'QUIT', array(221, 250));
        fclose($socket);
        return true;
    }
}


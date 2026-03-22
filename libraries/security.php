<?php

if (!function_exists('app_get_csrf_token')) {
    function app_get_csrf_token()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('app_validate_csrf_token')) {
    function app_validate_csrf_token($token)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = isset($_SESSION['csrf_token']) ? (string)$_SESSION['csrf_token'] : '';
        $providedToken = is_string($token) ? $token : '';

        return $sessionToken !== '' && hash_equals($sessionToken, $providedToken);
    }
}

if (!function_exists('app_rotate_csrf_token')) {
    function app_rotate_csrf_token()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('app_hash_password')) {
    function app_hash_password($plainTextPassword)
    {
        return password_hash((string)$plainTextPassword, PASSWORD_DEFAULT);
    }
}

if (!function_exists('app_verify_password')) {
    function app_verify_password($plainTextPassword, $storedHash)
    {
        $plainTextPassword = (string)$plainTextPassword;
        $storedHash = (string)$storedHash;

        if ($storedHash === '') {
            return false;
        }

        $hashInfo = password_get_info($storedHash);
        if (!empty($hashInfo['algo'])) {
            return password_verify($plainTextPassword, $storedHash);
        }

        return hash_equals(sha1($plainTextPassword), $storedHash);
    }
}

if (!function_exists('app_password_needs_rehash')) {
    function app_password_needs_rehash($storedHash)
    {
        $storedHash = (string)$storedHash;
        $hashInfo = password_get_info($storedHash);

        if (empty($hashInfo['algo'])) {
            return true;
        }

        return password_needs_rehash($storedHash, PASSWORD_DEFAULT);
    }
}

if (!function_exists('app_store_uploaded_image')) {
    function app_store_uploaded_image($file, $targetDir, $baseName, &$errorMessage = '', $maxSize = 2097152)
    {
        $errorMessage = '';

        if (!is_array($file) || !isset($file['error']) || (int)$file['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = 'File upload failed. Please try again.';
            return '';
        }

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errorMessage = 'Invalid upload source.';
            return '';
        }

        if (isset($file['size']) && (int)$file['size'] > $maxSize) {
            $errorMessage = 'Uploaded file is too large.';
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? (string)finfo_file($finfo, $file['tmp_name']) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMimeMap = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        );

        if (!isset($allowedMimeMap[$mimeType])) {
            $errorMessage = 'Only JPG, PNG, and WEBP images are allowed.';
            return '';
        }

        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$baseName);
        if ($baseName === '') {
            $baseName = 'image';
        }

        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            $errorMessage = 'Unable to prepare the upload directory.';
            return '';
        }

        $extension = $allowedMimeMap[$mimeType];
        $fileName = $baseName . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errorMessage = 'Unable to save the uploaded image.';
            return '';
        }

        return $fileName;
    }
}

if (!function_exists('app_destroy_session_securely')) {
    function app_destroy_session_securely()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}

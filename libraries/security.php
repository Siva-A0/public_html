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

if (!function_exists('app_normalize_upload_base_name')) {
    function app_normalize_upload_base_name($baseName)
    {
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$baseName);
        return $baseName !== '' ? $baseName : 'image';
    }
}

if (!function_exists('app_prepare_image_destination')) {
    function app_prepare_image_destination($targetDir, $baseName, $extension, &$errorMessage = '')
    {
        $errorMessage = '';
        $baseName = app_normalize_upload_base_name($baseName);

        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            $errorMessage = 'Unable to prepare the upload directory.';
            return array('', '');
        }

        $fileName = $baseName . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        return array($fileName, $destination);
    }
}

if (!function_exists('app_create_image_resource_from_string')) {
    function app_create_image_resource_from_string($imageBinary)
    {
        if (!function_exists('imagecreatefromstring')) {
            return null;
        }

        $image = @imagecreatefromstring($imageBinary);
        return $image !== false ? $image : null;
    }
}

if (!function_exists('app_save_image_resource')) {
    function app_save_image_resource($imageResource, $destination, $extension)
    {
        $isGdObject = class_exists('GdImage', false) && ($imageResource instanceof GdImage);
        if (!is_resource($imageResource) && !$isGdObject) {
            return false;
        }

        imagealphablending($imageResource, true);
        imagesavealpha($imageResource, true);

        switch (strtolower((string)$extension)) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($imageResource, $destination, 90);
            case 'png':
                return imagepng($imageResource, $destination, 6);
            case 'webp':
                return function_exists('imagewebp') ? imagewebp($imageResource, $destination, 90) : false;
        }

        return false;
    }
}

if (!function_exists('app_store_cropped_image')) {
    function app_store_cropped_image($croppedData, $targetDir, $baseName, &$errorMessage = '', $maxSize = 4194304)
    {
        $errorMessage = '';
        $croppedData = trim((string)$croppedData);
        if ($croppedData === '') {
            $errorMessage = 'No cropped image data was provided.';
            return '';
        }

        if (strpos($croppedData, 'data:image/') !== 0 || strpos($croppedData, ';base64,') === false) {
            $errorMessage = 'Invalid cropped image data.';
            return '';
        }

        $parts = explode(';base64,', $croppedData, 2);
        $mimePart = strtolower((string)($parts[0] ?? ''));
        $encoded = (string)($parts[1] ?? '');
        $allowedMimeMap = array(
            'data:image/jpeg' => 'jpg',
            'data:image/png' => 'png',
            'data:image/webp' => 'webp'
        );

        if (!isset($allowedMimeMap[$mimePart])) {
            $errorMessage = 'Only JPG, PNG, and WEBP cropped images are allowed.';
            return '';
        }

        $imageBinary = base64_decode($encoded, true);
        if ($imageBinary === false || $imageBinary === '') {
            $errorMessage = 'Unable to decode the cropped image.';
            return '';
        }

        if (strlen($imageBinary) > $maxSize) {
            $errorMessage = 'Cropped image is too large.';
            return '';
        }

        $imageInfo = @getimagesizefromstring($imageBinary);
        if ($imageInfo === false || empty($imageInfo['mime'])) {
            $errorMessage = 'Invalid cropped image.';
            return '';
        }

        $extension = $allowedMimeMap[$mimePart];
        if (($imageInfo[0] ?? 0) < 50 || ($imageInfo[1] ?? 0) < 50) {
            $errorMessage = 'Cropped image is too small.';
            return '';
        }

        list($fileName, $destination) = app_prepare_image_destination($targetDir, $baseName, $extension, $errorMessage);
        if ($destination === '') {
            return '';
        }

        $saved = false;
        $imageResource = app_create_image_resource_from_string($imageBinary);
        if ($imageResource !== null) {
            $saved = app_save_image_resource($imageResource, $destination, $extension);
            imagedestroy($imageResource);
        } else {
            $saved = @file_put_contents($destination, $imageBinary) !== false;
        }

        if (!$saved) {
            $errorMessage = 'Unable to save the cropped image.';
            return '';
        }

        return $fileName;
    }
}

if (!function_exists('app_store_processed_image')) {
    function app_store_processed_image($file, $croppedData, $targetDir, $baseName, &$errorMessage = '', $maxSize = 2097152)
    {
        $croppedData = trim((string)$croppedData);
        if ($croppedData !== '') {
            return app_store_cropped_image($croppedData, $targetDir, $baseName, $errorMessage, max($maxSize * 2, 4194304));
        }

        if (!is_array($file) || !isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errorMessage = 'Please choose an image to upload.';
            return '';
        }

        return app_store_uploaded_image($file, $targetDir, $baseName, $errorMessage, $maxSize);
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

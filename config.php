<?php

define('ROOT_PATH', __DIR__);

if (!function_exists('app_load_env_file')) {
    function app_load_env_file($filePath)
    {
        static $loadedFiles = array();

        $realPath = realpath($filePath);
        if ($realPath === false || isset($loadedFiles[$realPath]) || !is_file($realPath) || !is_readable($realPath)) {
            return;
        }

        $lines = file($realPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name === '') {
                continue;
            }

            $length = strlen($value);
            if ($length >= 2) {
                $first = $value[0];
                $last = $value[$length - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            if (getenv($name) === false) {
                putenv($name . '=' . $value);
            }

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
            }

            if (!array_key_exists($name, $_SERVER)) {
                $_SERVER[$name] = $value;
            }
        }

        $loadedFiles[$realPath] = true;
    }
}

if (!function_exists('app_env')) {
    function app_env($name, $default = '')
    {
        if (array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        $value = getenv($name);
        if ($value !== false) {
            return $value;
        }

        if (array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        return $default;
    }
}

if (!function_exists('app_normalize_path')) {
    function app_normalize_path($path)
    {
        $path = str_replace('\\', '/', (string)$path);
        return rtrim($path, '/');
    }
}

if (!function_exists('app_detect_base_url')) {
    function app_detect_base_url($rootPath)
    {
        $configuredBaseUrl = trim((string)app_env('APP_BASE_URL', ''));
        if ($configuredBaseUrl !== '') {
            if ($configuredBaseUrl === '/') {
                return '';
            }

            return '/' . trim($configuredBaseUrl, '/');
        }

        $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
        $realRootPath = realpath($rootPath);

        if ($documentRoot !== false && $realRootPath !== false) {
            $normalizedDocumentRoot = strtolower(app_normalize_path($documentRoot));
            $normalizedRootPath = strtolower(app_normalize_path($realRootPath));

            if ($normalizedRootPath === $normalizedDocumentRoot) {
                return '';
            }

            if (strpos($normalizedRootPath, $normalizedDocumentRoot . '/') === 0) {
                $relativePath = substr(app_normalize_path($realRootPath), strlen(app_normalize_path($documentRoot)));
                $relativePath = '/' . trim(str_replace('\\', '/', $relativePath), '/');
                return $relativePath === '/' ? '' : $relativePath;
            }
        }

        return '/department';
    }
}

$localEnvPath = ROOT_PATH . '/.env.local';
app_load_env_file($localEnvPath);
$defaultEnvPath = ROOT_PATH . '/.env';
app_load_env_file($defaultEnvPath);

$appBaseUrl = app_detect_base_url(ROOT_PATH);

define('BASE_URL', $appBaseUrl);
define('INCLUDES_PATH', ROOT_PATH . '/public/Includes');
define('LIB_PATH', ROOT_PATH . '/libraries');
define('IMG_PATH', BASE_URL . '/gallery');

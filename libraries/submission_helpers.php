<?php

if (!function_exists('app_submission_marker')) {
    function app_submission_marker($ownerType, $entryType, $ownerId)
    {
        $ownerType = strtoupper(trim((string)$ownerType));
        $entryType = strtoupper(trim((string)$entryType));
        $ownerId = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$ownerId);

        return '[APP:' . $ownerType . ':' . $entryType . '][ID:' . $ownerId . '] ';
    }
}

if (!function_exists('app_build_submission_desc')) {
    function app_build_submission_desc($ownerType, $entryType, $ownerId, $ownerLabel, $contextTag, $text)
    {
        $ownerLabel = trim((string)$ownerLabel);
        $contextTag = trim((string)$contextTag);
        $text = trim((string)$text);

        return app_submission_marker($ownerType, $entryType, $ownerId) . $ownerLabel . ' - ' . $contextTag . ' - ' . $text;
    }
}

if (!function_exists('app_is_prefixed_submission')) {
    function app_is_prefixed_submission($desc)
    {
        return preg_match('/^\[APP:[A-Z]+:[A-Z]+\]\[ID:[^\]]+\]\s*/', (string)$desc) === 1;
    }
}

if (!function_exists('app_strip_submission_marker')) {
    function app_strip_submission_marker($desc)
    {
        return preg_replace('/^\[APP:[A-Z]+:[A-Z]+\]\[ID:[^\]]+\]\s*/', '', trim((string)$desc), 1);
    }
}

if (!function_exists('app_split_submission_file')) {
    function app_split_submission_file($rawDesc)
    {
        $rawDesc = (string)$rawDesc;
        if (strpos($rawDesc, '$$') === false) {
            return array('text' => $rawDesc, 'file' => '');
        }

        $parts = explode('$$', $rawDesc, 2);
        return array(
            'text' => (string)($parts[0] ?? ''),
            'file' => (string)($parts[1] ?? '')
        );
    }
}

if (!function_exists('app_format_submission_meta')) {
    function app_format_submission_meta($desc)
    {
        $desc = app_strip_submission_marker($desc);
        $parts = explode(' - ', $desc, 3);
        if (count($parts) === 3) {
            return array(
                'owner' => $parts[0],
                'context' => $parts[1],
                'text' => $parts[2]
            );
        }

        return array(
            'owner' => '',
            'context' => '',
            'text' => $desc
        );
    }
}

if (!function_exists('app_safe_submission_file_url')) {
    function app_safe_submission_file_url($fileName)
    {
        $fileName = trim((string)$fileName);
        if ($fileName === '' || preg_match('/^[A-Za-z0-9._-]+$/', $fileName) !== 1) {
            return '';
        }

        $diskPath = ROOT_PATH . '/public/assets/images/achievements/' . $fileName;
        if (!is_file($diskPath)) {
            return '';
        }

        return BASE_URL . '/public/assets/images/achievements/' . rawurlencode($fileName);
    }
}

if (!function_exists('app_guess_submission_time')) {
    function app_guess_submission_time($fileName)
    {
        $fileName = trim((string)$fileName);
        if (preg_match('/_([0-9]{14})_/', $fileName, $matches) !== 1) {
            return '';
        }

        $dateTime = DateTime::createFromFormat('YmdHis', $matches[1]);
        if (!$dateTime) {
            return '';
        }

        return $dateTime->format('Y-m-d H:i');
    }
}

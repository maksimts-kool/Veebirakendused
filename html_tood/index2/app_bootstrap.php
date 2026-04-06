<?php

if (defined('INDEX2_BOOTSTRAPPED')) {
    return;
}

define('INDEX2_BOOTSTRAPPED', true);
define('INDEX2_ROOT', __DIR__);

$appConfig = require INDEX2_ROOT . '/app_config.php';

if (!function_exists('app_config')) {
    function app_config($key = null, $default = null)
    {
        global $appConfig;

        if ($key === null || $key === '') {
            return $appConfig;
        }

        $segments = explode('.', $key);
        $value = $appConfig;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('app_now')) {
    function app_now()
    {
        return date('c');
    }
}

if (!function_exists('app_starts_with')) {
    function app_starts_with($haystack, $needle)
    {
        if ($needle === '') {
            return true;
        }

        return strncmp((string)$haystack, (string)$needle, strlen((string)$needle)) === 0;
    }
}

if (!function_exists('app_start_session')) {
    function app_start_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('app_ensure_parent_dir')) {
    function app_ensure_parent_dir($path)
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
    }
}

if (!function_exists('app_read_json_file')) {
    function app_read_json_file($path, $default)
    {
        app_ensure_parent_dir($path);

        if (!file_exists($path)) {
            app_write_json_file($path, $default);
            return $default;
        }

        $raw = file_get_contents($path);
        if ($raw === false || trim($raw) === '') {
            return $default;
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return $default;
        }

        return $decoded;
    }
}

if (!function_exists('app_write_json_file')) {
    function app_write_json_file($path, $data)
    {
        app_ensure_parent_dir($path);

        return file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        ) !== false;
    }
}

if (!function_exists('app_get_client_ip')) {
    function app_get_client_ip()
    {
        $sources = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        ];

        foreach ($sources as $source) {
            if (empty($_SERVER[$source])) {
                continue;
            }

            $parts = explode(',', $_SERVER[$source]);
            foreach ($parts as $part) {
                $candidate = trim($part);
                if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                    return $candidate;
                }
            }
        }

        return 'unknown';
    }
}

if (!function_exists('app_current_path')) {
    function app_current_path()
    {
        return $_SERVER['PHP_SELF'] ?? '';
    }
}

if (!function_exists('app_build_url')) {
    function app_build_url($path, array $params = [])
    {
        $query = http_build_query($params);
        if ($query === '') {
            return $path;
        }

        return $path . '?' . $query;
    }
}

if (!function_exists('app_normalize_return_to')) {
    function app_normalize_return_to($returnTo)
    {
        $returnTo = trim((string)$returnTo);

        if ($returnTo === '') {
            return app_current_path();
        }

        if (preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $returnTo) || app_starts_with($returnTo, '//')) {
            return app_current_path();
        }

        return $returnTo;
    }
}

if (!function_exists('app_flash_add')) {
    function app_flash_add($type, $message)
    {
        app_start_session();

        if (!isset($_SESSION['app_flash_messages']) || !is_array($_SESSION['app_flash_messages'])) {
            $_SESSION['app_flash_messages'] = [];
        }

        $_SESSION['app_flash_messages'][] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}

if (!function_exists('app_flash_take_all')) {
    function app_flash_take_all()
    {
        app_start_session();

        $messages = [];
        if (isset($_SESSION['app_flash_messages']) && is_array($_SESSION['app_flash_messages'])) {
            $messages = $_SESSION['app_flash_messages'];
        }

        unset($_SESSION['app_flash_messages']);

        return $messages;
    }
}

if (!function_exists('app_render_flash_messages')) {
    function app_render_flash_messages()
    {
        $messages = app_flash_take_all();
        if (empty($messages)) {
            return '';
        }

        $html = '';
        foreach ($messages as $message) {
            $type = htmlspecialchars($message['type'], ENT_QUOTES, 'UTF-8');
            $text = htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');
            $html .= '<div style="margin:12px auto;max-width:900px;padding:12px 16px;border-radius:12px;border:1px solid #d1d5db;background:#fff;color:#111827;">';
            $html .= '<strong style="text-transform:capitalize;">' . $type . ':</strong> ' . $text;
            $html .= '</div>';
        }

        return $html;
    }
}

if (!function_exists('app_set_denied_context')) {
    function app_set_denied_context(array $context)
    {
        app_start_session();
        $_SESSION['app_ip_denied_context'] = $context;
    }
}

if (!function_exists('app_get_denied_context')) {
    function app_get_denied_context()
    {
        app_start_session();
        if (isset($_SESSION['app_ip_denied_context']) && is_array($_SESSION['app_ip_denied_context'])) {
            return $_SESSION['app_ip_denied_context'];
        }

        return [];
    }
}

if (!function_exists('app_clear_denied_context')) {
    function app_clear_denied_context()
    {
        app_start_session();
        unset($_SESSION['app_ip_denied_context']);
    }
}

if (!function_exists('app_get_authorized_ip_map')) {
    function app_get_authorized_ip_map()
    {
        $path = app_config('security.authorized_ips_file');
        $map = app_read_json_file($path, []);
        $defaults = app_config('security.auto_authorized_ips', []);

        foreach ($defaults as $defaultIp) {
            if (!isset($map[$defaultIp])) {
                $map[$defaultIp] = [
                    'label' => 'Automatic local access',
                    'added_at' => app_now(),
                    'source' => 'auto',
                ];
            }
        }

        app_write_json_file($path, $map);

        return $map;
    }
}

if (!function_exists('app_is_ip_authorized')) {
    function app_is_ip_authorized($ip = null)
    {
        $ip = $ip ?: app_get_client_ip();
        $authorizedIps = app_get_authorized_ip_map();

        return isset($authorizedIps[$ip]);
    }
}

if (!function_exists('app_authorize_ip')) {
    function app_authorize_ip($ip, array $meta = [])
    {
        $authorizedIps = app_get_authorized_ip_map();
        $authorizedIps[$ip] = array_merge(
            [
                'label' => 'Approved IP',
                'added_at' => app_now(),
                'source' => 'manual',
            ],
            $meta
        );

        app_write_json_file(app_config('security.authorized_ips_file'), $authorizedIps);

        return $authorizedIps[$ip];
    }
}

if (!function_exists('app_get_ip_requests')) {
    function app_get_ip_requests()
    {
        return app_read_json_file(app_config('security.ip_requests_file'), []);
    }
}

if (!function_exists('app_save_ip_requests')) {
    function app_save_ip_requests(array $requests)
    {
        return app_write_json_file(app_config('security.ip_requests_file'), array_values($requests));
    }
}

if (!function_exists('app_get_latest_request_for_ip')) {
    function app_get_latest_request_for_ip($ip)
    {
        $requests = array_reverse(app_get_ip_requests());

        foreach ($requests as $request) {
            if (isset($request['ip']) && $request['ip'] === $ip) {
                return $request;
            }
        }

        return null;
    }
}

if (!function_exists('app_get_request_by_id')) {
    function app_get_request_by_id($requestId)
    {
        foreach (app_get_ip_requests() as $request) {
            if (isset($request['id']) && $request['id'] === $requestId) {
                return $request;
            }
        }

        return null;
    }
}

if (!function_exists('app_remove_authorized_ip')) {
    function app_remove_authorized_ip($ip)
    {
        $authorizedIps = app_get_authorized_ip_map();
        if (!isset($authorizedIps[$ip])) {
            return false;
        }

        unset($authorizedIps[$ip]);
        return app_write_json_file(app_config('security.authorized_ips_file'), $authorizedIps);
    }
}

if (!function_exists('app_update_request_status')) {
    function app_update_request_status($requestId, $status)
    {
        $requests = app_get_ip_requests();
        $updatedRequest = null;

        foreach ($requests as $index => $request) {
            if (!isset($request['id']) || $request['id'] !== $requestId) {
                continue;
            }

            if (($request['status'] ?? '') === 'pending' && in_array($status, ['approved', 'declined'], true)) {
                $requests[$index]['status'] = $status;
                $requests[$index]['decision_at'] = app_now();

                if ($status === 'approved') {
                    app_authorize_ip($request['ip'], [
                        'label' => 'Approved from admin panel',
                        'added_at' => app_now(),
                        'source' => 'admin-panel',
                    ]);
                }
            }

            $updatedRequest = $requests[$index];
            break;
        }

        if ($updatedRequest !== null) {
            app_save_ip_requests($requests);
        }

        return $updatedRequest;
    }
}

if (!function_exists('app_request_ip_access')) {
    function app_request_ip_access($reason, $returnTo, $note = '')
    {
        $ip = app_get_client_ip();
        $returnTo = app_normalize_return_to($returnTo);
        $existingRequest = app_get_latest_request_for_ip($ip);

        if ($existingRequest && isset($existingRequest['status']) && $existingRequest['status'] === 'pending') {
            return $existingRequest;
        }

        $requests = app_get_ip_requests();
        $request = [
            'id' => uniqid('ip_', true),
            'ip' => $ip,
            'reason' => (string)$reason,
            'note' => (string)$note,
            'status' => 'pending',
            'request_path' => (string)$returnTo,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'requested_at' => app_now(),
        ];

        $requests[] = $request;
        app_save_ip_requests($requests);

        return $request;
    }
}

if (!function_exists('app_is_admin_session')) {
    function app_is_admin_session()
    {
        return !empty($_SESSION['admin']) || (isset($_SESSION['roll']) && $_SESSION['roll'] === 'admin');
    }
}

if (!function_exists('app_require_ip_admin_access')) {
    function app_require_ip_admin_access()
    {
        if (app_is_admin_session()) {
            return;
        }

        app_require_basic_auth('IP Access Admin');
    }
}

if (!function_exists('app_require_authorized_ip_for_action')) {
    function app_require_authorized_ip_for_action($reason, $returnTo)
    {
        if (app_is_ip_authorized()) {
            return true;
        }

        $returnTo = app_normalize_return_to($returnTo);

        app_set_denied_context([
            'reason' => $reason,
            'return_to' => $returnTo,
            'denied_at' => app_now(),
        ]);

        app_flash_add('warning', 'Your IP address is not authorized to change data yet. You can send an approval request below.');
        header('Location: ' . $returnTo);
        exit;
    }
}

if (!function_exists('app_handle_ip_request_submission')) {
    function app_handle_ip_request_submission(array $options = [])
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['request_ip_access'])) {
            return;
        }

        $returnTo = isset($_POST['request_return_to']) && $_POST['request_return_to'] !== ''
            ? $_POST['request_return_to']
            : ($options['return_to'] ?? app_current_path());
        $returnTo = app_normalize_return_to($returnTo);

        $denied = app_get_denied_context();
        $reason = isset($_POST['request_reason']) && $_POST['request_reason'] !== ''
            ? trim($_POST['request_reason'])
            : ($denied['reason'] ?? ($options['reason'] ?? 'Protected data change'));

        $note = isset($_POST['request_note']) ? trim($_POST['request_note']) : '';

        $request = app_request_ip_access($reason, $returnTo, $note);
        app_flash_add(
            'success',
            'Access request for IP ' . $request['ip'] . ' has been saved for admin review.'
        );
        app_clear_denied_context();

        header('Location: ' . $returnTo);
        exit;
    }
}

if (!function_exists('app_render_ip_access_panel')) {
    function app_render_ip_access_panel(array $options = [])
    {
        $flashHtml = app_render_flash_messages();

        if (app_is_ip_authorized()) {
            return $flashHtml;
        }

        $ip = app_get_client_ip();
        $latestRequest = app_get_latest_request_for_ip($ip);
        $deniedContext = app_get_denied_context();
        $reason = $deniedContext['reason'] ?? ($options['reason'] ?? 'Protected data change');
        $returnTo = $options['return_to'] ?? ($deniedContext['return_to'] ?? app_current_path());

        $statusHtml = '<p style="margin:0 0 12px;color:#374151;">Only authorized IP addresses can change data. Current IP: <strong>' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . '</strong>.</p>';

        if ($latestRequest && ($latestRequest['status'] ?? '') === 'pending') {
            $statusHtml .= '<p style="margin:0;color:#92400e;">A request for this IP is already pending review.</p>';
        } elseif ($latestRequest && ($latestRequest['status'] ?? '') === 'declined') {
            $statusHtml .= '<p style="margin:0 0 12px;color:#b91c1c;">The latest request for this IP was declined. You can send a new request.</p>';
        }

        $html = $flashHtml;
        $html .= '<section style="max-width:900px;margin:16px auto;padding:18px;border:1px solid #e5e7eb;border-radius:16px;background:#fff7ed;">';
        $html .= '<h2 style="margin:0 0 10px;font-size:1.2rem;">IP Authorization Required</h2>';
        $html .= $statusHtml;
        $html .= '<p style="margin:0 0 12px;color:#4b5563;">An administrator can review requests in the IP admin panel.</p>';

        if (!$latestRequest || ($latestRequest['status'] ?? '') !== 'pending') {
            $html .= '<form method="post" style="display:grid;gap:10px;">';
            $html .= '<input type="hidden" name="request_ip_access" value="1">';
            $html .= '<input type="hidden" name="request_reason" value="' . htmlspecialchars($reason, ENT_QUOTES, 'UTF-8') . '">';
            $html .= '<input type="hidden" name="request_return_to" value="' . htmlspecialchars($returnTo, ENT_QUOTES, 'UTF-8') . '">';
            $html .= '<label style="display:grid;gap:6px;">';
            $html .= '<span style="font-weight:600;">Optional note for approval</span>';
            $html .= '<textarea name="request_note" rows="3" placeholder="For example: this is my work computer IP." style="padding:10px;border:1px solid #d1d5db;border-radius:10px;"></textarea>';
            $html .= '</label>';
            $html .= '<button type="submit" style="justify-self:start;padding:10px 16px;border:none;border-radius:10px;background:#111827;color:#fff;cursor:pointer;">Request IP approval</button>';
            $html .= '</form>';
        }

        $html .= '</section>';

        return $html;
    }
}

if (!function_exists('app_get_admin_password')) {
    function app_get_admin_password()
    {
        return (string)app_config('security.admin_password', '');
    }
}

if (!function_exists('app_require_basic_auth')) {
    function app_require_basic_auth($realm = 'Admin Area')
    {
        $correctUser = (string)app_config('security.basic_auth_user', '');
        $correctPass = (string)app_config('security.basic_auth_password', '');

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Access denied';
            exit;
        }

        if ($_SERVER['PHP_AUTH_USER'] !== $correctUser || ($_SERVER['PHP_AUTH_PW'] ?? '') !== $correctPass) {
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Wrong username or password';
            exit;
        }
    }
}

app_start_session();
app_get_authorized_ip_map();

$dbHost = app_config('database.host');
$dbUser = app_config('database.user');
$dbPassword = app_config('database.password');
$dbName = app_config('database.name');
$dbCharset = app_config('database.charset', 'utf8mb4');

$yhendus = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
if ($yhendus->connect_errno) {
    die('Database connection failed: ' . $yhendus->connect_error);
}

$yhendus->set_charset($dbCharset);
$connect = $yhendus;

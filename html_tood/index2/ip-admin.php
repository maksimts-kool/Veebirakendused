<?php

require_once __DIR__ . '/app_bootstrap.php';

app_require_ip_admin_access();

if (!function_exists('ip_admin_describe_pages')) {
    function ip_admin_describe_pages(array $pages)
    {
        $pages = app_normalize_allowed_pages($pages);

        if (in_array('*', $pages, true)) {
            return 'All protected pages';
        }

        if (empty($pages)) {
            return 'No pages selected';
        }

        $labels = [];
        foreach ($pages as $page) {
            $labels[] = app_get_protected_page_label($page);
        }

        return implode(', ', $labels);
    }
}

if (!function_exists('ip_admin_render_page_selector')) {
    function ip_admin_render_page_selector($fieldName, array $selectedPages, $selectorId)
    {
        $definitions = app_get_protected_page_definitions();
        $selectedPages = app_normalize_allowed_pages($selectedPages);
        $allSelected = in_array('*', $selectedPages, true);

        $html = '<div id="' . htmlspecialchars($selectorId, ENT_QUOTES, 'UTF-8') . '" data-page-selector="1" style="display:grid;gap:8px;">';
        $html .= '<label style="display:flex;gap:8px;align-items:flex-start;padding:8px 10px;border:1px solid #d1d5db;border-radius:10px;background:#f9fafb;">';
        $html .= '<input type="checkbox" data-select-all="1" name="' . htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8') . '[]" value="*" ' . ($allSelected ? 'checked' : '') . '>';
        $html .= '<span><strong>All protected pages</strong><br><span style="color:#6b7280;font-size:0.9rem;">Full edit access everywhere this IP protection is used.</span></span>';
        $html .= '</label>';

        foreach ($definitions as $pageKey => $definition) {
            $checked = !$allSelected && in_array($pageKey, $selectedPages, true) ? 'checked' : '';
            $disabled = $allSelected ? 'disabled' : '';
            $labelOpacity = $allSelected ? 'opacity:0.55;' : '';
            $html .= '<label style="display:flex;gap:8px;align-items:flex-start;padding:8px 10px;border:1px solid #e5e7eb;border-radius:10px;background:#fff;' . $labelOpacity . '">';
            $html .= '<input type="checkbox" data-page-option="1" name="' . htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8') . '[]" value="' . htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') . '" ' . $checked . ' ' . $disabled . '>';
            $html .= '<span><strong>' . htmlspecialchars($definition['label'], ENT_QUOTES, 'UTF-8') . '</strong><br><span style="color:#6b7280;font-size:0.9rem;">' . htmlspecialchars($definition['description'], ENT_QUOTES, 'UTF-8') . '</span></span>';
            $html .= '</label>';
        }

        $html .= '</div>';

        return $html;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_id'], $_POST['request_action'])) {
        $requestId = trim((string)$_POST['request_id']);
        $action = trim((string)$_POST['request_action']);

        if ($action === 'approve') {
            $allowedPages = app_normalize_allowed_pages($_POST['allowed_pages'] ?? []);
            if (empty($allowedPages)) {
                app_flash_add('warning', 'Select at least one page before approving this IP.');
                header('Location: ip-admin.php');
                exit;
            }

            $updated = app_update_request_status($requestId, 'approved', [
                'allowed_pages' => $allowedPages,
            ]);
            if ($updated) {
                app_flash_add('success', 'IP ' . $updated['ip'] . ' has been approved for: ' . ip_admin_describe_pages($updated['approved_pages'] ?? $allowedPages) . '.');
            }
        } elseif ($action === 'decline') {
            $updated = app_update_request_status($requestId, 'declined');
            if ($updated) {
                app_flash_add('warning', 'IP ' . $updated['ip'] . ' has been declined.');
            }
        }

        header('Location: ip-admin.php');
        exit;
    }

    if (isset($_POST['authorized_ip'], $_POST['save_authorized_ip_pages'])) {
        $ip = trim((string)$_POST['authorized_ip']);
        $allowedPages = app_normalize_allowed_pages($_POST['allowed_pages'] ?? []);

        if (empty($allowedPages)) {
            app_flash_add('warning', 'Select at least one page before saving IP access.');
            header('Location: ip-admin.php');
            exit;
        }

        $updated = app_update_authorized_ip($ip, [
            'allowed_pages' => $allowedPages,
        ]);

        if ($updated !== null) {
            app_flash_add('success', 'Access for IP ' . $ip . ' was updated to: ' . ip_admin_describe_pages($allowedPages) . '.');
        }

        header('Location: ip-admin.php');
        exit;
    }

    if (isset($_POST['remove_authorized_ip'])) {
        $ip = trim((string)$_POST['remove_authorized_ip']);
        if (app_remove_authorized_ip($ip)) {
            app_flash_add('success', 'IP ' . $ip . ' was removed from the authorized list.');
        }

        header('Location: ip-admin.php');
        exit;
    }
}

$requests = array_reverse(app_get_ip_requests());
$authorizedIps = app_get_authorized_ip_map();
ksort($authorizedIps);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IP Admin</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f3f4f6;color:#111827;margin:0;padding:24px;">
    <main style="max-width:1100px;margin:0 auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">IP Access Admin</h1>
                <p style="margin:8px 0 0;color:#4b5563;">Review pending IP requests and manage the whitelist.</p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="content/php-ab2/admin.php" style="padding:10px 14px;border-radius:10px;background:#111827;color:#fff;text-decoration:none;">Toidupood admin</a>
                <a href="content/valimised/index.php" style="padding:10px 14px;border-radius:10px;background:#111827;color:#fff;text-decoration:none;">Valimised admin</a>
            </div>
        </div>

        <?=app_render_flash_messages();?>

        <section style="background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;margin-bottom:20px;">
            <h2 style="margin-top:0;">Pending Requests</h2>
            <?php
            $pendingRequests = array_values(array_filter($requests, static function ($request) {
                return ($request['status'] ?? '') === 'pending';
            }));
            ?>
            <?php if (empty($pendingRequests)): ?>
                <p style="margin-bottom:0;color:#4b5563;">There are no pending requests right now.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">IP</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Reason</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Requested Page</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Requested</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
                    </tr>
                    <?php foreach ($pendingRequests as $request): ?>
                        <?php
                        $requestedPage = $request['requested_page'] ?? app_normalize_permission_page_key($request['request_path'] ?? '');
                        $defaultPages = $requestedPage !== '' ? [$requestedPage] : [];
                        ?>
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <strong><?=htmlspecialchars($request['ip'], ENT_QUOTES, 'UTF-8')?></strong><br>
                                <span style="color:#6b7280;font-size:0.9rem;"><?=htmlspecialchars($request['user_agent'] ?? '', ENT_QUOTES, 'UTF-8')?></span>
                            </td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <?=htmlspecialchars($request['reason'] ?? '', ENT_QUOTES, 'UTF-8')?>
                                <?php if (!empty($request['note'])): ?>
                                    <div style="margin-top:6px;color:#6b7280;"><?=nl2br(htmlspecialchars($request['note'], ENT_QUOTES, 'UTF-8'))?></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <strong><?=htmlspecialchars(app_get_protected_page_label($requestedPage), ENT_QUOTES, 'UTF-8')?></strong><br>
                                <span style="color:#6b7280;font-size:0.9rem;"><?=htmlspecialchars($requestedPage !== '' ? $requestedPage : ($request['request_path'] ?? ''), ENT_QUOTES, 'UTF-8')?></span>
                            </td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;"><?=htmlspecialchars($request['requested_at'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <form method="post" style="display:grid;gap:10px;min-width:320px;">
                                    <input type="hidden" name="request_id" value="<?=htmlspecialchars($request['id'], ENT_QUOTES, 'UTF-8')?>">
                                    <input type="hidden" name="request_action" value="approve">
                                    <div>
                                        <div style="font-weight:600;margin-bottom:8px;">Grant access to pages</div>
                                        <?=ip_admin_render_page_selector('allowed_pages', $defaultPages, 'approve-pages-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', (string)$request['id']))?>
                                    </div>
                                    <button type="submit" style="justify-self:start;padding:8px 12px;border:none;border-radius:8px;background:#16a34a;color:#fff;cursor:pointer;">Approve</button>
                                </form>
                                <form method="post" style="display:inline-block;margin-top:8px;">
                                    <input type="hidden" name="request_id" value="<?=htmlspecialchars($request['id'], ENT_QUOTES, 'UTF-8')?>">
                                    <input type="hidden" name="request_action" value="decline">
                                    <button type="submit" style="padding:8px 12px;border:none;border-radius:8px;background:#dc2626;color:#fff;cursor:pointer;">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>

        <section style="background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;margin-bottom:20px;">
            <h2 style="margin-top:0;">Authorized IPs</h2>
            <?php if (empty($authorizedIps)): ?>
                <p style="margin-bottom:0;color:#4b5563;">No authorized IPs found.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">IP</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Source</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Access</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Added</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
                    </tr>
                    <?php foreach ($authorizedIps as $ip => $meta): ?>
                        <?php $allowedPages = app_get_effective_allowed_pages($meta); ?>
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($ip, ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($meta['source'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <strong><?=htmlspecialchars(ip_admin_describe_pages($allowedPages), ENT_QUOTES, 'UTF-8')?></strong>
                                <?php if (!in_array('*', $allowedPages, true)): ?>
                                    <div style="margin-top:6px;color:#6b7280;font-size:0.9rem;"><?=htmlspecialchars(implode(', ', $allowedPages), ENT_QUOTES, 'UTF-8')?></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($meta['added_at'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <?php if (($meta['source'] ?? '') === 'auto'): ?>
                                    <span style="color:#6b7280;">Automatic full access</span>
                                <?php else: ?>
                                    <form method="post" style="display:grid;gap:10px;min-width:320px;">
                                        <input type="hidden" name="authorized_ip" value="<?=htmlspecialchars($ip, ENT_QUOTES, 'UTF-8')?>">
                                        <input type="hidden" name="save_authorized_ip_pages" value="1">
                                        <div>
                                            <div style="font-weight:600;margin-bottom:8px;">Edit allowed pages</div>
                                            <?=ip_admin_render_page_selector('allowed_pages', $allowedPages, 'edit-pages-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', (string)$ip))?>
                                        </div>
                                        <button type="submit" style="justify-self:start;padding:8px 12px;border:none;border-radius:8px;background:#2563eb;color:#fff;cursor:pointer;">Save access</button>
                                    </form>
                                    <form method="post" style="display:inline-block;margin-top:8px;">
                                        <input type="hidden" name="remove_authorized_ip" value="<?=htmlspecialchars($ip, ENT_QUOTES, 'UTF-8')?>">
                                        <button type="submit" style="padding:8px 12px;border:none;border-radius:8px;background:#111827;color:#fff;cursor:pointer;">Remove</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>

        <section style="background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;">
            <h2 style="margin-top:0;">Recent History</h2>
            <?php if (empty($requests)): ?>
                <p style="margin-bottom:0;color:#4b5563;">No request history yet.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">IP</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Status</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Reason</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Decision</th>
                    </tr>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($request['ip'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($request['status'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($request['reason'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($request['decision_at'] ?? '-', ENT_QUOTES, 'UTF-8')?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
<script>
document.querySelectorAll('[data-page-selector="1"]').forEach(function(selector) {
    var allCheckbox = selector.querySelector('[data-select-all="1"]');
    var optionCheckboxes = Array.from(selector.querySelectorAll('[data-page-option="1"]'));

    if (!allCheckbox) {
        return;
    }

    function syncSelectorState() {
        optionCheckboxes.forEach(function(checkbox) {
            checkbox.disabled = allCheckbox.checked;
            if (allCheckbox.checked) {
                checkbox.checked = false;
            }

            var label = checkbox.closest('label');
            if (label) {
                label.style.opacity = allCheckbox.checked ? '0.55' : '1';
            }
        });
    }

    allCheckbox.addEventListener('change', syncSelectorState);
    syncSelectorState();
});
</script>
</html>

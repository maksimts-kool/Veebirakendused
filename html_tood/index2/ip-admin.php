<?php

require_once __DIR__ . '/app_bootstrap.php';

app_require_ip_admin_access();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_id'], $_POST['request_action'])) {
        $requestId = trim((string)$_POST['request_id']);
        $action = trim((string)$_POST['request_action']);

        if ($action === 'approve') {
            $updated = app_update_request_status($requestId, 'approved');
            if ($updated) {
                app_flash_add('success', 'IP ' . $updated['ip'] . ' has been approved.');
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
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Page</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Requested</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
                    </tr>
                    <?php foreach ($pendingRequests as $request): ?>
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
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;"><?=htmlspecialchars($request['request_path'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;"><?=htmlspecialchars($request['requested_at'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;vertical-align:top;">
                                <form method="post" style="display:inline-block;margin-right:8px;">
                                    <input type="hidden" name="request_id" value="<?=htmlspecialchars($request['id'], ENT_QUOTES, 'UTF-8')?>">
                                    <input type="hidden" name="request_action" value="approve">
                                    <button type="submit" style="padding:8px 12px;border:none;border-radius:8px;background:#16a34a;color:#fff;cursor:pointer;">Approve</button>
                                </form>
                                <form method="post" style="display:inline-block;">
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
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Added</th>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb;">Actions</th>
                    </tr>
                    <?php foreach ($authorizedIps as $ip => $meta): ?>
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($ip, ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($meta['source'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?=htmlspecialchars($meta['added_at'] ?? '', ENT_QUOTES, 'UTF-8')?></td>
                            <td style="padding:10px;border-bottom:1px solid #f3f4f6;">
                                <?php if (($meta['source'] ?? '') === 'auto'): ?>
                                    <span style="color:#6b7280;">Automatic</span>
                                <?php else: ?>
                                    <form method="post" style="display:inline-block;">
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
</html>

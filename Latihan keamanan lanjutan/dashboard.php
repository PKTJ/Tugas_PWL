<?php
require_once 'config.php';
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Check for 2FA requirement
if (isset($_SESSION['2fa_required']) && $_SESSION['2fa_required'] === true) {
    header('Location: two_factor.php');
    exit();
}

// Get user information
try {
    $db = DatabaseConnection::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        session_destroy();
        header('Location: index.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    session_destroy();
    header('Location: index.php');
    exit();
}

// Get recent security logs for this user
$security_logs = [];
try {
    $stmt = $db->prepare("SELECT * FROM security_logs WHERE details LIKE ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute(['%' . $user['email'] . '%']);
    $security_logs = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Security logs error: " . $e->getMessage());
}

// Get active sessions count
$active_sessions = 0;
try {
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM user_sessions WHERE user_id = ? AND expires_at > NOW()");
    $stmt->execute([$user['id']]);
    $result = $stmt->fetch();
    $active_sessions = $result['count'] ?? 0;
} catch (Exception $e) {
    error_log("Active sessions error: " . $e->getMessage());
}

$csrf_token = SecurityUtils::generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Advanced Security Practice</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security Dashboard</span>
                </div>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                    <div class="user-menu">
                        <button class="btn-menu" onclick="toggleUserMenu()">
                            <i class="fas fa-user-circle"></i>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#" onclick="showProfile()"><i class="fas fa-user"></i> Profile</a>
                            <a href="#" onclick="showSettings()"><i class="fas fa-cogs"></i> Settings</a>
                            <a href="#" onclick="showSecurity()"><i class="fas fa-shield-alt"></i> Security</a>
                            <hr>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="dashboard-grid">
                <!-- Security Overview -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-shield-check"></i> Security Overview</h3>
                    </div>
                    <div class="card-content">
                        <div class="security-stats">
                            <div class="stat-item">
                                <div class="stat-icon <?php echo $user['email_verified'] ? 'verified' : 'unverified'; ?>">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Email Verification</span>
                                    <span class="stat-value"><?php echo $user['email_verified'] ? 'Verified' : 'Unverified'; ?></span>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon <?php echo $user['two_factor_enabled'] ? 'enabled' : 'disabled'; ?>">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Two-Factor Auth</span>
                                    <span class="stat-value"><?php echo $user['two_factor_enabled'] ? 'Enabled' : 'Disabled'; ?></span>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon active">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Last Login</span>
                                    <span class="stat-value"><?php echo $user['last_login'] ? date('M j, Y H:i', strtotime($user['last_login'])) : 'N/A'; ?></span>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon active">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Active Sessions</span>
                                    <span class="stat-value"><?php echo $active_sessions; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions">
                            <button class="action-btn" onclick="changePassword()">
                                <i class="fas fa-key"></i>
                                Change Password
                            </button>
                            <button class="action-btn" onclick="toggle2FA()">
                                <i class="fas fa-mobile-alt"></i>
                                <?php echo $user['two_factor_enabled'] ? 'Disable' : 'Enable'; ?> 2FA
                            </button>
                            <button class="action-btn" onclick="viewSessions()">
                                <i class="fas fa-list"></i>
                                Manage Sessions
                            </button>
                            <button class="action-btn" onclick="downloadData()">
                                <i class="fas fa-download"></i>
                                Download Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card full-width">
                    <div class="card-header">
                        <h3><i class="fas fa-history"></i> Recent Security Activity</h3>
                        <button class="btn btn-secondary" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                    <div class="card-content">
                        <div class="activity-list">
                            <?php if (empty($security_logs)): ?>
                                <div class="no-activity">
                                    <i class="fas fa-info-circle"></i>
                                    <p>No recent activity found.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($security_logs as $log): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon <?php echo strtolower($log['event_type']); ?>">
                                            <?php
                                            $icon = 'fas fa-info-circle';
                                            switch ($log['event_type']) {
                                                case 'LOGIN_SUCCESS':
                                                    $icon = 'fas fa-sign-in-alt';
                                                    break;
                                                case 'LOGIN_FAILED':
                                                    $icon = 'fas fa-exclamation-triangle';
                                                    break;
                                                case 'USER_REGISTERED':
                                                    $icon = 'fas fa-user-plus';
                                                    break;
                                                case 'PASSWORD_CHANGED':
                                                    $icon = 'fas fa-key';
                                                    break;
                                                default:
                                                    $icon = 'fas fa-info-circle';
                                            }
                                            ?>
                                            <i class="<?php echo $icon; ?>"></i>
                                        </div>
                                        <div class="activity-details">
                                            <div class="activity-title"><?php echo htmlspecialchars($log['event_type']); ?></div>
                                            <div class="activity-description"><?php echo htmlspecialchars($log['details']); ?></div>
                                            <div class="activity-meta">
                                                <span><i class="fas fa-clock"></i> <?php echo date('M j, Y H:i:s', strtotime($log['created_at'])); ?></span>
                                                <span><i class="fas fa-globe"></i> <?php echo htmlspecialchars($log['ip_address']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-lightbulb"></i> Security Tips</h3>
                    </div>
                    <div class="card-content">
                        <div class="security-tips">
                            <div class="tip-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Use a unique, strong password</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Enable two-factor authentication</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Regularly review your account activity</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Keep your browser updated</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Never share your login credentials</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.btn-menu') && !event.target.matches('.btn-menu *')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        function showProfile() {
            alert('Profile management would be implemented here.');
        }

        function showSettings() {
            alert('Settings page would be implemented here.');
        }

        function showSecurity() {
            alert('Advanced security settings would be implemented here.');
        }

        function changePassword() {
            alert('Password change functionality would be implemented here with proper security measures.');
        }

        function toggle2FA() {
            const action = '<?php echo $user['two_factor_enabled'] ? 'disable' : 'enable'; ?>';
            alert(`Two-factor authentication ${action} functionality would be implemented here.`);
        }

        function viewSessions() {
            alert('Session management interface would be implemented here showing all active sessions.');
        }

        function downloadData() {
            alert('Data export functionality would be implemented here (GDPR compliance).');
        }

        function refreshLogs() {
            location.reload();
        }

        // Auto-refresh security logs every 5 minutes
        setInterval(function() {
            // In a real application, this would use AJAX to refresh only the logs section
            console.log('Auto-refreshing security logs...');
        }, 300000);
    </script>
</body>
</html>
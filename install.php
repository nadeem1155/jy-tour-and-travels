<?php
/**
 * JY TOUR and TRAVELS - 1-Click Database Installer & Setup Wizard
 * Access via browser: http://localhost/JY%20TOURS%20TRAVELS/install.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$configFile = __DIR__ . '/config/database.php';
$schemaFile = __DIR__ . '/database/schema.sql';

$installed = false;
$error = '';
$logs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? '127.0.0.1');
    $dbPort = trim($_POST['db_port'] ?? '3306');
    $dbName = trim($_POST['db_name'] ?? 'jy_tours_travels');
    $dbUser = trim($_POST['db_user'] ?? 'root');
    $dbPass = $_POST['db_pass'] ?? '';

    try {
        // 1. Connect to MySQL server (without selecting DB first)
        $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $logs[] = "Connected to MySQL Server successfully.";

        // 2. Create Database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $logs[] = "Database `{$dbName}` verified/created.";

        // 3. Switch to database
        $pdo->exec("USE `{$dbName}`;");

        // 4. Read and execute schema.sql
        if (!file_exists($schemaFile)) {
            throw new Exception("schema.sql file not found at: {$schemaFile}");
        }

        $sql = file_get_contents($schemaFile);
        
        // Remove comments and execute statements
        $pdo->exec($sql);
        $logs[] = "Executed database/schema.sql successfully.";
        $logs[] = "Default vehicles seeded: Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus.";
        $logs[] = "Default services seeded: Luxury Sedan, Luxury MUV, Luxury SUV, Luxury Bus, Luxury Hatch Back.";
        $logs[] = "Admin credentials initialized (admin / Admin@JY2026#Secure).";

        // 5. Update config/database.php
        $dbConfigContent = "<?php\n"
            . "/**\n * JY TOUR and TRAVELS - Database Configuration\n */\n"
            . "defined('APP_INIT') or define('APP_INIT', true);\n\n"
            . "define('DB_HOST', getenv('DB_HOST') ?: '{$dbHost}');\n"
            . "define('DB_PORT', getenv('DB_PORT') ?: '{$dbPort}');\n"
            . "define('DB_NAME', getenv('DB_NAME') ?: '{$dbName}');\n"
            . "define('DB_USER', getenv('DB_USER') ?: '{$dbUser}');\n"
            . "define('DB_PASS', getenv('DB_PASS') ?: " . var_export($dbPass, true) . ");\n"
            . "define('DB_CHARSET', 'utf8mb4');\n\n"
            . "function get_db_connection(): ?PDO {\n"
            . "    static \$pdo = null;\n"
            . "    if (\$pdo !== null) return \$pdo;\n"
            . "    \$dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;\n"
            . "    \$options = [\n"
            . "        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,\n"
            . "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n"
            . "        PDO::ATTR_EMULATE_PREPARES   => false,\n"
            . "        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHARSET\n"
            . "    ];\n"
            . "    try {\n"
            . "        \$pdo = new PDO(\$dsn, DB_USER, DB_PASS, \$options);\n"
            . "        return \$pdo;\n"
            . "    } catch (PDOException \$e) {\n"
            . "        error_log('Database connection error: ' . \$e->getMessage());\n"
            . "        return null;\n"
            . "    }\n"
            . "}\n";

        file_put_contents($configFile, $dbConfigContent);
        $logs[] = "Saved updated database credentials to config/database.php.";

        $installed = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup Wizard | JY TOUR and TRAVELS</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #04361c 0%, #074e28 100%);
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }
        .installer-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            border-top: 6px solid #ffd700;
        }
        .installer-header {
            background: #f8faf9;
            padding: 26px 30px;
            border-bottom: 1px solid #e3ebe6;
            text-align: center;
        }
        .btn-install {
            background: linear-gradient(135deg, #ffd700 0%, #f5a623 100%);
            color: #111827;
            font-weight: 800;
            border: none;
            padding: 13px;
        }
    </style>
</head>
<body>

<div class="installer-card">
    <div class="installer-header">
        <h3 class="fw-bold mb-1 text-dark">JY TOUR and TRAVELS</h3>
        <p class="text-muted small mb-0">1-Click Database Installer & Setup Wizard</p>
    </div>

    <div class="p-4 p-md-5">
        <?php if ($installed): ?>
        <div class="alert alert-success">
            <h5 class="fw-bold"><i class="fa-solid fa-circle-check me-2"></i> Setup Completed Successfully!</h5>
            <p class="small mb-2">The database and all initial records have been created.</p>
            <ul class="small mb-3">
                <?php foreach ($logs as $log): ?>
                <li><?= htmlspecialchars($log); ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="p-3 bg-white rounded border border-success mb-3 text-dark">
                <strong>Admin Login Credentials:</strong><br>
                Portal URL: <a href="admin/login.php" class="text-success fw-bold">admin/login.php</a><br>
                Username: <code>admin</code><br>
                Password: <code>Admin@JY2026#Secure</code>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-success flex-grow-1"><i class="fa-solid fa-globe me-1"></i> Visit Website</a>
                <a href="admin/login.php" class="btn btn-warning flex-grow-1 text-dark fw-bold"><i class="fa-solid fa-lock me-1"></i> Open Admin Portal</a>
            </div>
        </div>
        <?php else: ?>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 small">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Error:</strong> <?= htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <p class="text-muted small mb-4">
            Enter your MySQL database connection credentials below. The installer will automatically create the database <code>jy_tours_travels</code>, build all 8 tables, and seed vehicle, service, and settings data.
        </p>

        <form action="install.php" method="POST">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold small">Database Host</label>
                    <input type="text" name="db_host" class="form-control" value="127.0.0.1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Port</label>
                    <input type="text" name="db_port" class="form-control" value="3306" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Database Name</label>
                    <input type="text" name="db_name" class="form-control" value="jy_tours_travels" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">MySQL Username</label>
                    <input type="text" name="db_user" class="form-control" value="root" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">MySQL Password</label>
                    <input type="password" name="db_pass" class="form-control" placeholder="Leave empty if none">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-install w-100 rounded-3">
                        <i class="fa-solid fa-database me-2"></i> Install Database & Run Setup
                    </button>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

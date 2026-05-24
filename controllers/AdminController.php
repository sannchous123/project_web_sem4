<?php
class AdminController {
    public function index() {
        if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'admin123') {
            header('WWW-Authenticate: Basic realm="Admin Panel"');
            header('HTTP/1.0 401 Unauthorized');
            die('Доступ запрещён.');
        }

        $pdo = Database::getInstance()->getPdo();
        $stmt = $pdo->query("SELECT a.*, GROUP_CONCAT(pl.language_name SEPARATOR ', ') as languages FROM applications a LEFT JOIN application_languages al ON a.id = al.application_id LEFT JOIN programming_languages pl ON al.language_id = pl.id GROUP BY a.id ORDER BY a.created_at DESC");
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT pl.language_name, COUNT(al.application_id) as count FROM programming_languages pl LEFT JOIN application_languages al ON pl.id = al.language_id GROUP BY pl.id ORDER BY count DESC");
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT COUNT(*) as total FROM applications");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        View::render('admin', ['applications' => $applications, 'stats' => $stats, 'total' => $total]);
    }
}

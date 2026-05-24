<?php
class FormController {
    public function index() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $pdo = Database::getInstance()->getPdo();
        $stmt = $pdo->query("SELECT id, language_name FROM programming_languages ORDER BY language_name");
        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cookie_errors = [];
        if (isset($_COOKIE['form_errors'])) {
            $cookie_errors = json_decode($_COOKIE['form_errors'], true) ?: [];
            setcookie('form_errors', '', time() - 3600, '/', '', true, true);
        }

        $cookie_old = [];
        if (isset($_COOKIE['form_old_values'])) {
            $cookie_old = json_decode($_COOKIE['form_old_values'], true) ?: [];
            setcookie('form_old_values', '', time() - 3600, '/', '', true, true);
        }

        $saved_data = [];
        if (isset($_COOKIE['form_saved_data'])) {
            $saved_data = json_decode($_COOKIE['form_saved_data'], true) ?: [];
        }

        $merged_old = $cookie_old ?: $saved_data;
        $message = $_SESSION['success_message'] ?? '';
        unset($_SESSION['success_message']);

        View::render('form', [
            'languages' => $languages,
            'errors' => $cookie_errors,
            'old' => $merged_old,
            'message' => $message,
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    public function loginPage() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);
        View::render('login', ['error' => $error, 'csrf_token' => $_SESSION['csrf_token']]);
    }

    public function login() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['login_error'] = 'Ошибка проверки формы.';
            header('Location: /login');
            exit();
        }

        $pdo = Database::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM applications WHERE username = ?");
        $stmt->execute([trim($_POST['username'])]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && Auth::verifyPassword($_POST['password'], $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /');
        } else {
            $_SESSION['login_error'] = 'Неверный логин или пароль.';
            header('Location: /login');
        }
        exit();
    }
}

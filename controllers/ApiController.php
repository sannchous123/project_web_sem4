<?php
class ApiController {
    private function jsonResponse($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getJsonInput() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        if (!$data) {
            $this->jsonResponse(['error' => 'Неверный формат JSON'], 400);
        }
        return $data;
    }

    public function create() {
        $data = $this->getJsonInput();
        $errors = Validator::validateApplication($data);

        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
        }

        $pdo = Database::getInstance()->getPdo();
        $username = Auth::generateUsername($data['full_name']);
        $password = Auth::generatePassword();
        $password_hash = Auth::hashPassword($password);

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO applications (full_name, phone, email, birth_date, gender, biography, agreed_to_contract, username, password_hash) VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)");
            $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $data['birth_date'], $data['gender'], $data['biography'] ?? '', $username, $password_hash]);
            $id = $pdo->lastInsertId();

            if (!empty($data['languages'])) {
                $stmt = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
                foreach ($data['languages'] as $lang_id) {
                    $stmt->execute([$id, $lang_id]);
                }
            }

            $pdo->commit();
            $this->jsonResponse([
                'success' => true,
                'id' => $id,
                'username' => $username,
                'password' => $password,
                'profile_url' => '/api/application/' . $id,
            ], 201);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('API Create Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Ошибка сохранения.'], 500);
        }
    }

    public function update($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $id) {
            $this->jsonResponse(['error' => 'Не авторизован.'], 401);
        }

        $data = $this->getJsonInput();
        $errors = Validator::validateApplication($data);

        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
        }

        $pdo = Database::getInstance()->getPdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE applications SET full_name = ?, phone = ?, email = ?, birth_date = ?, gender = ?, biography = ? WHERE id = ?");
            $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $data['birth_date'], $data['gender'], $data['biography'] ?? '', $id]);

            $stmt = $pdo->prepare("DELETE FROM application_languages WHERE application_id = ?");
            $stmt->execute([$id]);

            if (!empty($data['languages'])) {
                $stmt = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
                foreach ($data['languages'] as $lang_id) {
                    $stmt->execute([$id, $lang_id]);
                }
            }

            $pdo->commit();
            $this->jsonResponse(['success' => true]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('API Update Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Ошибка обновления.'], 500);
        }
    }

    public function get($id) {
        $pdo = Database::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            $this->jsonResponse(['error' => 'Не найдено.'], 404);
        }

        $stmt = $pdo->prepare("SELECT language_id FROM application_languages WHERE application_id = ?");
        $stmt->execute([$id]);
        $data['languages'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        unset($data['password_hash']);
        $this->jsonResponse($data);
    }
}

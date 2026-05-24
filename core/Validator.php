<?php
class Validator {
    public static function validateApplication($data) {
        $errors = [];

        $full_name = trim($data['full_name'] ?? '');
        if (empty($full_name)) {
            $errors['full_name'] = 'ФИО обязательно.';
        } elseif (strlen($full_name) > 150) {
            $errors['full_name'] = 'ФИО не более 150 символов.';
        } elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $full_name)) {
            $errors['full_name'] = 'Только буквы, пробелы, дефис.';
        }

        $phone = trim($data['phone'] ?? '');
        if (empty($phone)) {
            $errors['phone'] = 'Телефон обязателен.';
        } elseif (!preg_match('/^[\d\s\+\-\(\)]+$/', $phone)) {
            $errors['phone'] = 'Только цифры, плюс, скобки, пробелы, дефис.';
        } else {
            $digitsOnly = preg_replace('/[^\d]/', '', $phone);
            if (strlen($digitsOnly) < 10 || strlen($digitsOnly) > 15) {
                $errors['phone'] = 'От 10 до 15 цифр.';
            }
        }

        $email = trim($data['email'] ?? '');
        if (empty($email)) {
            $errors['email'] = 'Email обязателен.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Неверный формат email.';
        }

        $birth_date = $data['birth_date'] ?? '';
        if (empty($birth_date)) {
            $errors['birth_date'] = 'Дата рождения обязательна.';
        } else {
            $age = date('Y') - date('Y', strtotime($birth_date));
            if ($age < 16 || $age > 120) {
                $errors['birth_date'] = 'Возраст от 16 до 120 лет.';
            }
        }

        $gender = $data['gender'] ?? '';
        if (!in_array($gender, ['male', 'female', 'other'])) {
            $errors['gender'] = 'Выберите пол.';
        }

        $languages = $data['languages'] ?? [];
        if (empty($languages)) {
            $errors['languages'] = 'Выберите язык.';
        }

        $biography = trim($data['biography'] ?? '');
        if (!empty($biography) && !preg_match('/^[a-zA-Zа-яА-ЯёЁ0-9\s\.\,\!\?\-\:\;\"\'\(\)\n\r]+$/u', $biography)) {
            $errors['biography'] = 'Буквы, цифры, пробелы, знаки препинания.';
        }

        return $errors;
    }
}

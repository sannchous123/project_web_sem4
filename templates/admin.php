<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ccc; padding: 8px; font-size: 13px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Админ-панель</h1>
    <p><a href="/">На главную</a></p>
    <h2>Все записи (<?= count($applications) ?>)</h2>
    <table>
        <tr><th>ID</th><th>ФИО</th><th>Телефон</th><th>Email</th><th>Дата рождения</th><th>Пол</th><th>Языки</th></tr>
        <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= htmlspecialchars($app['id']) ?></td>
                <td><?= htmlspecialchars($app['full_name']) ?></td>
                <td><?= htmlspecialchars($app['phone']) ?></td>
                <td><?= htmlspecialchars($app['email']) ?></td>
                <td><?= htmlspecialchars($app['birth_date']) ?></td>
                <td><?= $app['gender'] == 'male' ? 'Мужской' : ($app['gender'] == 'female' ? 'Женский' : 'Другой') ?></td>
                <td><?= htmlspecialchars($app['languages'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Статистика по языкам (всего: <?= $total ?>)</h2>
    <table>
        <tr><th>Язык</th><th>Количество</th></tr>
        <?php foreach ($stats as $row): ?>
            <tr><td><?= htmlspecialchars($row['language_name']) ?></td><td><?= $row['count'] ?></td></tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

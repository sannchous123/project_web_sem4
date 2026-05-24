document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(form);
        var data = {};
        formData.forEach(function(value, key) {
            if (key === 'languages[]') {
                if (!data['languages']) data['languages'] = [];
                data['languages'].push(value);
            } else {
                data[key] = value;
            }
        });

        var messageDiv = document.getElementById('formMessage');
        messageDiv.innerHTML = '<p>Отправка...</p>';
        messageDiv.style.color = '#2563eb';

        fetch('/api/application', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.errors) {
                var errorsHtml = '<ul style="color: red;">';
                for (var field in result.errors) {
                    errorsHtml += '<li>' + result.errors[field] + '</li>';
                }
                errorsHtml += '</ul>';
                messageDiv.innerHTML = errorsHtml;
            } else if (result.success) {
                messageDiv.innerHTML = '<p style="color: green;">Заявка успешно отправлена!</p>' +
                    '<p><strong>Ваш логин:</strong> ' + result.username + '</p>' +
                    '<p><strong>Ваш пароль:</strong> ' + result.password + '</p>' +
                    '<p>Сохраните их для редактирования данных. <a href="/login">Войти</a></p>';
                form.reset();
            } else {
                messageDiv.innerHTML = '<p style="color: red;">Ошибка: ' + (result.error || 'Неизвестная ошибка') + '</p>';
            }
        })
        .catch(function(error) {
            messageDiv.innerHTML = '<p style="color: red;">Ошибка сети. Попробуйте позже.</p>';
        });
    });
});

<nav class="navbar">
    <div class="container nav-container">
        <a href="/" class="logo">IT<span>Service</span></a>
    </div>
</nav>

<section style="padding: 8rem 0 5rem;">
    <div class="container" style="max-width: 500px;">
        <h2>Вход в личный кабинет</h2>
        <?php if ($error): ?>
            <p class="error" style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" action="/login" style="background: #f8fafc; padding: 2rem; border-radius: 8px;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <button type="submit" class="btn submit-btn" style="width: 100%;">Войти</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;"><a href="/">← На главную</a></p>
    </div>
</section>

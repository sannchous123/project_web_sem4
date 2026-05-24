<nav class="navbar">
    <div class="container nav-container">
        <a href="#" class="logo">IT<span>Service</span></a>
        <ul class="nav-menu">
            <li class="nav-item"><a href="#support" class="nav-link">Поддержка</a></li>
            <li class="nav-item"><a href="#tariffs" class="nav-link">Тарифы</a></li>
            <li class="nav-item"><a href="#contact-form" class="nav-link">Контакты</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a href="/login" class="nav-link">Личный кабинет</a></li>
                <li class="nav-item"><a href="/logout" class="nav-link">Выйти</a></li>
            <?php else: ?>
                <li class="nav-item"><a href="/login" class="nav-link">Войти</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<header class="hero">
    <div class="hero-video-container">
        <video autoplay muted loop playsinline class="hero-video-bg">
            <source src="/assets/video/video.mp4" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1>Комплексное IT-обслуживание для вашего бизнеса</h1>
            <p>Профессиональная поддержка, администрирование и продвижение сайтов в Краснодаре</p>
        </div>
    </div>
</header>

<section id="contact-form" class="contact-section" style="padding: 3rem 0;">
    <div class="container">
        <h2>Оставить заявку</h2>
        <div id="formMessage" style="margin-bottom: 1rem;"></div>

        <form id="contactForm" class="contact-form" action="/api/application" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="form-row">
                <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                    <label for="full_name">ФИО *</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['full_name']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                    <?php if (isset($errors['phone'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['phone']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group <?= isset($errors['birth_date']) ? 'has-error' : '' ?>">
                    <label for="birth_date">Дата рождения *</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($old['birth_date'] ?? '') ?>" required>
                    <?php if (isset($errors['birth_date'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['birth_date']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?= isset($errors['gender']) ? 'has-error' : '' ?>">
                    <label>Пол *</label>
                    <div class="radio-group" style="display: flex; gap: 15px; margin-top: 8px;">
                        <label><input type="radio" name="gender" value="male" <?= ($old['gender'] ?? '') == 'male' ? 'checked' : '' ?>> Мужской</label>
                        <label><input type="radio" name="gender" value="female" <?= ($old['gender'] ?? '') == 'female' ? 'checked' : '' ?>> Женский</label>
                        <label><input type="radio" name="gender" value="other" <?= ($old['gender'] ?? '') == 'other' ? 'checked' : '' ?>> Другой</label>
                    </div>
                    <?php if (isset($errors['gender'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['gender']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group <?= isset($errors['languages']) ? 'has-error' : '' ?>">
                <label for="languages">Любимые языки программирования *</label>
                <select id="languages" name="languages[]" multiple size="6" required>
                    <?php foreach ($languages as $lang): ?>
                        <option value="<?= $lang['id'] ?>" <?= (isset($old['languages']) && is_array($old['languages']) && in_array($lang['id'], $old['languages'])) ? 'selected' : '' ?>><?= htmlspecialchars($lang['language_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['languages'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['languages']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['biography']) ? 'has-error' : '' ?>">
                <label for="biography">Биография</label>
                <textarea id="biography" name="biography" rows="4"><?= htmlspecialchars($old['biography'] ?? '') ?></textarea>
                <?php if (isset($errors['biography'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['biography']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="agreed_to_contract" value="1" required> Я ознакомлен(а) с условиями контракта</label>
            </div>

            <button type="submit" class="btn submit-btn">Отправить заявку</button>
        </form>
    </div>
</section>

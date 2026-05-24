<?php
class View {
    public static function render($template, $data = []) {
        extract($data);
        $templatePath = __DIR__ . '/../templates/' . $template . '.php';
        if (file_exists($templatePath)) {
            require $templatePath;
        }
    }

    public static function renderWithLayout($template, $data = []) {
        $data['content'] = $template;
        extract($data);
        require __DIR__ . '/../templates/layout.php';
    }
}

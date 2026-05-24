<?php
class Router {
    public function dispatch($method, $uri) {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        $uri = $uri ?: '/';

        if ($uri === '/' || $uri === '/index.php') {
            $controller = new FormController();
            $controller->index();
            return;
        }

        if ($uri === '/login') {
            $controller = new FormController();
            if ($method === 'POST') {
                $controller->login();
            } else {
                $controller->loginPage();
            }
            return;
        }

        if ($uri === '/logout') {
            session_destroy();
            header('Location: /');
            exit();
        }

        if ($uri === '/admin') {
            $controller = new AdminController();
            $controller->index();
            return;
        }

        if ($uri === '/api/application' && $method === 'POST') {
            $controller = new ApiController();
            $controller->create();
            return;
        }

        if (preg_match('#^/api/application/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $controller = new ApiController();
            $controller->update($matches[1]);
            return;
        }

        if (preg_match('#^/api/application/(\d+)$#', $uri, $matches) && $method === 'GET') {
            $controller = new ApiController();
            $controller->get($matches[1]);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}

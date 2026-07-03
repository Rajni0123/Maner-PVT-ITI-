<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], string $layout = 'public'): void
    {
        extract($data);
        $viewFile = base_path('views/' . $view . '.php');
        if (!is_file($viewFile)) {
            http_response_code(500);
            die('View not found: ' . e($view));
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = $layout ? base_path('views/layouts/' . $layout . '.php') : null;
        if ($layoutFile && is_file($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial(string $view, array $data = []): void
    {
        extract($data);
        require base_path('views/' . $view . '.php');
    }
}

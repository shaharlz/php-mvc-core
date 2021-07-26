<?php


namespace App\Core;


use App\Core\Exceptions\NotFoundException;

class Router {

    protected array $routes = [];
    protected Application $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function get(string $path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve() {
        $path = $this->app->request->getPath();
        $method = $this->app->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if (!$callback) {
            throw new NotFoundException();
        }
        if (is_string($callback))
            return $this->renderView($callback);
        if (is_array($callback)) {
            /**
             * @var Controller $controller
             */
            $controller =  new $callback[0]($this->app);
            $controller->setAction($callback[1]);
            $this->app->controller = $controller;
            $callback[0] = $this->app->controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }

        }
        return call_user_func($callback);
    }

    public function renderView(string $view, array $params = [], string $layout = 'main') {
        $layoutContent = $this->layoutContent($layout);
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent(string $viewContent, string $layout = 'main') {
        $layoutContent = $this->layoutContent($layout);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent(string $layout) : false | string {
        ob_start();
        include_once APPDIR . "/Views/Layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView(string $view, array $params = []) : false | string {
        foreach ($params as $key => $param) {
            $$key = $param;
        }
        ob_start();
        include_once __DIR__ . "/../Views/$view.php";
        return ob_get_clean();
    }

    public function redirect(string $location): void {
        header("Location: $location");
    }

}
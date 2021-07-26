<?php


namespace App\Core;


use App\Core\Middlewares\Middleware;

abstract class Controller {

    protected Application $app;
    public string $action = '';
    /**
     *@var Middleware[]
     */
    protected array $middlewares = [];

    public function __construct(Application $app) {
        $this->app = $app;
    }

    protected function render(string $view, array $params = [], string $layout = 'main') {
        return $this->app->router->renderView($view, $params, $layout);
    }

    public function registerMiddleware(Middleware $middleware) {
        array_push($this->middlewares, $middleware);
    }

    /**
     * @return string
     */
    public function getAction(): string {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void {
        $this->action = $action;
    }

    /**
     * @return Middleware[]
     */
    public function getMiddlewares(): array {
        return $this->middlewares;
    }

}
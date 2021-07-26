<?php


namespace App\Core\Middlewares;


use App\Core\Application;
use App\Core\Exceptions\ForbiddenException;

class AuthMiddleware extends Middleware {

    private array $actions = [];

    public function __construct(Application $app, array $actions = []) {
        parent::__construct($app);
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute() {
        if (!$this->app->isLoggedIn()) {
            if (empty($this->actions) || in_array($this->app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
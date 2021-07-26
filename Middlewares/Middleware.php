<?php


namespace App\Core\Middlewares;


use App\Core\Application;

abstract class Middleware {

    protected Application $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public abstract function execute();

}
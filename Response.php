<?php


namespace App\Core;


class Response {

    protected Application $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function getStatusCode() : bool | int {
        return http_response_code();
    }

}
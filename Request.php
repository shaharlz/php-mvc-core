<?php


namespace App\Core;


class Request {

    protected Application $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function getPath() : string {

        $path = $_SERVER['REQUEST_URI'] ?? '/mvc/';
        $position = strpos($path, '?');
        if ($position === false)
            return $path;
        return substr($path, 0, $position);
    }

    public function method() : string {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody() : array {
        $body = [];

        if ($this->method() === 'get') {
            foreach ($_GET as $key => $param) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else if ($this->method() === 'post') {
            foreach ($_POST as $key => $param) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function isGet() : bool {
        return $this->method() === 'get';
    }

    public function isPost() : bool {
        return $this->method() === 'post';
    }

    public function getClientIp(): ?string {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function isUsingIPv6(): bool {
        if(filter_var($this->getClientIp(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return true;
        }
        return false;
    }

}
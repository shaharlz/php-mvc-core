<?php


namespace App\Core;

class Application {

    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?Controller $controller;
    public ?UserModel $user;
    public string $userClass;

    public function __construct(array $configs) {
        $this->setGlobalConstants($configs['globalConstants']);
        $this->userClass = $configs['userClass'];
        $this->response = new Response($this);
        $this->request = new Request($this);
        $this->router = new Router($this);
        $this->session = new Session($this);
        $this->db = new Database($this, $configs['db']);
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::getPrimaryKey();
            $this->user = (new $this->userClass($this))->findOne([
                $primaryKey => $primaryValue
            ]);
        } else {
            $this->user = null;
        }
    }

    private function setGlobalConstants(array $globalConstants) {
        foreach ($globalConstants as $key => $value) {
            define($key, $value);
        }
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Throwable $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView('_error', [
                'exception' => $e
            ] );
        }
    }

    public function login(UserModel $user): bool {
        $this->user = $user;
        $primaryKey = $user::getPrimaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    public function isLoggedIn(): bool {
        return isset($this->user);
    }
}
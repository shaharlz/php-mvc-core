<?php


namespace App\Core;


class Session {

    protected Application $app;
    protected const FLASH_KEY = 'flash_messages';

    public function __construct(Application $app) {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? null;
    }

    public function __destruct() {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove'])
                unset($flashMessages[$key]);
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function set(string $key, string $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): string|bool {
        return $_SESSION[$key] ?? false;
    }

    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

}
<?php

class CSRF {
    public static function generate()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verify($token)
    {
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }

    public static function check()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !self::verify($_POST['csrf_token'])) {
                die('CSRF token validation failed.');
            }
        }
    }
}

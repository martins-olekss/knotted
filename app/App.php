<?php

/**
 * Class App
 */
class App
{
    const LOG_FILE = 'main.log';

    /**
     * @param $message
     */
    public static function log($message)
    {
        $date = date("[d:m:Y H:i:s] ", time());
        $logFilePath = __DIR__ . '/../storage/log/' . self::LOG_FILE;
        if (is_array($message)) {
            $message = print_r($message, true);
        }
        $message .= PHP_EOL;
        try {
            file_put_contents($logFilePath, $date . $message, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param null $key
     * @return mixed
     */
    public static function post($key = null)
    {
        if ($key === null) {
            $post = $_POST;
        } else {
            $post = $_POST[$key];
        }

        return $post;
    }

    public static function verifyRegisterKey()
    {
        $config = parse_ini_file("../config/main.ini");
        if (!$config) {
            self::log('Config not set');

            return false;
        }
        if (!isset($_COOKIE['key']) || $_COOKIE['key'] !== $config['registerKey']) {
            self::log('Missing or incorrect key');

            return false;
        }
        self::log('Provided key ' . $_COOKIE['key']);

        return true;
    }
}
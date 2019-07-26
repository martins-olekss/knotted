<?php

/**
 * Class App
 */
class App
{
    const LOG_FILE = 'system.log';

    /**
     * @param $message
     */
    public static function log($message)
    {
        $logFilePath = __DIR__ . '/../log/' . self::LOG_FILE;
        if (is_array($message)) {
            $message = print_r($message, true);
        }
        $message .= PHP_EOL;
        try {
            file_put_contents($logFilePath, $message, FILE_APPEND | LOCK_EX);
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

    /**
     * @param $message
     */
    public static function addNotification($message)
    {
        $_SESSION['notifications'][] = $message;
    }

    /**
     * @return bool|string
     */
    public static function displayNotification()
    {
        if (
            isset($_SESSION['notifications'])
            && is_array($_SESSION['notifications'])
            && !empty($_SESSION['notifications'])
        ) {
            $html = '';
            foreach ($_SESSION['notifications'] as $item) {
                $html .= '<p>' . $item . '</p>';
            }
            unset($_SESSION['notifications']);

            return $html;
        }

        return false;
    }
}
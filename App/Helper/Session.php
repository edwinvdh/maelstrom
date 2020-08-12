<?php
/**
 * Session bag based on native PHP_SESSION
 */
namespace App\Helper;

class Session
{
    const ERROR_MESSAGE = 'sessionErrorMessage';
    const NOTIFICATION_MESSAGE = 'sessionNotificationMessage';

    private static bool $sessionStarted = false;

    public function __construct()
    {
        if (!self::$sessionStarted AND !headers_sent()) {
            session_start();
            self::$sessionStarted = true;
        }
    }

    /**
     * @param string $key
     * @param string|null $value
     */
    public function set(string $key, ?string $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $_SESSION[$key];
    }

    public function save()
    {
        session_commit();
    }

    /**
     * @param string $message
     */
    public function setErrorMessage(string $message)
    {
        $this->set(self::ERROR_MESSAGE, $message);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): ?string
    {
        $return = $this->get(self::ERROR_MESSAGE);
        $this->set(self::ERROR_MESSAGE, '');

        return $return;
    }

    /**
     * @param string $message
     */
    public function setNotificationMessage(string $message)
    {
        $this->set(self::NOTIFICATION_MESSAGE, $message);
    }

    /**
     * @return string
     */
    public function getNotificationMessage(): ?string
    {
        $return = $this->get(self::NOTIFICATION_MESSAGE);
        $this->set(self::NOTIFICATION_MESSAGE, '');

        return $return;
    }
}
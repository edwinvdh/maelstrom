<?php
/**
 * Session bag based on native PHP_SESSION
 */
namespace App\Helper;

class Session
{
    const ERROR_MESSAGE = 'sessionErrorMessage';
    const NOTIFICATION_MESSAGE = 'sessionNotificationMessage';
    const SESSION_DURATION = 900;
    private static bool $sessionStarted = false;

    private string $transport = '';

    public function __construct()
    {
        if (!self::$sessionStarted AND !headers_sent()) {
            session_start();
            self::$sessionStarted = true;
            $this->setCustomCookie();
        }

        if (isset($_COOKIE['transport'])) {
            $this->transport = $_COOKIE['transport'];
        } else {
            $this->transport = $this->getHash();
        }

        if (!$this->checkCookie()) {
            setcookie('transport', '', time()-(86400*65535));
            header('location: \login');
            die();
        }
    }

    private function setCustomCookie()
    {
        $hash = $this->getHash();
        if (!isset($_COOKIE['transport'])) {
            setcookie('transport', $hash, self::SESSION_DURATION);
        }

        $this->transport = $hash;
    }

    /**
     * @return bool
     */
    private function checkCookie()
    {
        if ($this->getHash() != $this->transport) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function getHash()
    {
        return md5($_SERVER['HTTP_USER_AGENT']);
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
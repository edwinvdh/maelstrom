<?php

namespace App\Helper;

/**
 * Translate get / post data to an internal Request Object
 * Only allowing strings for now (to prevent using mixed as datatype).
 * I won't be needing arrays for this one either.
 */
class Request
{
    private array $requestData = [];

    public function __construct()
    {
        if ($this->isGet()) {
            $this->setAllGetVars();
        }

        if ($this->isPost()) {
            $this->setAllPostVars();
        }

        if ($this->isDelete()) {
            $this->setAllDeleteVars();
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->requestData[$key];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value): void
    {
        $this->requestData[$key] = $value;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return (count($_POST) > 0 and !isset($_POST['delete']));
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return (count($_GET) > 0  and !isset($_GET['delete']));
    }

    /**
     * @return bool
     */
    public function isDelete():bool
    {
        $hasGetOrPost = (count($_GET) > 0 or count($_POST) > 0);

        if ($hasGetOrPost and (isset($_GET['delete']) or isset($_POST['delete']))) {
            return true;
        }

        return false;
    }

    private function setAllGetVars(): void
    {
        foreach ($_GET as $key => $value) {
            $this->set($key, $value);
        }
        return;
    }

    private function setAllPostVars(): void
    {
        foreach ($_POST as $key => $value) {
            $this->set($key, $value);
        }
    }

    private function setAllDeleteVars(): void
    {
        $this->setAllPostVars();
        $this->setAllGetVars();
    }
}
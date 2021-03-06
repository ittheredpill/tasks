<?php

namespace Core;

class Request
{

    private $resource;
    private $action;
    private $adminCookieName;
    private $adminCookieHash;

    public function __construct(array $config)
    {
        list($resourceAndAction) = explode('?', $_SERVER['REQUEST_URI']);
        $requestUri = explode("/", $resourceAndAction);
        $this->resource = !empty($requestUri[1]) ? strtolower($requestUri[1]) : 'index';
        $this->action = !empty($requestUri[2]) ? strtolower($requestUri[2]) : 'index';

        $this->adminCookieName = $config['admin']['cookie_name'];
        $this->adminCookieHash = $config['admin']['cookie_hash'];
    }

    public function setResource($resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setAction($action): void
    {
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function makeAdmin(): bool
    {
        return setcookie($this->adminCookieName, $this->adminCookieHash, time() + 86400, '/');
    }

    public function revokeAdmin(): bool
    {
        unset($_COOKIE[$this->adminCookieName]);
        return setcookie($this->adminCookieName, null, -1, '/');
    }

    public function isAdmin(): bool
    {
        return isset($_COOKIE[$this->adminCookieName]) && $_COOKIE[$this->adminCookieName] == $this->adminCookieHash;
    }

    public function get($param = null, $default = null)
    {
        if (is_null($param)) {
            return $_GET;
        }
        return $_GET[$param] ?? $default;
    }

    public function post($param = null, $default = null)
    {
        if (is_null($param)) {
            return $_POST;
        }
        return $_POST[$param] ?? $default;
    }
}
<?php

namespace App;

/**
 * Class Request
 * @package App
 */
class Request
{
    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $cookie;

    /**
     * @var array
     */
    private $session;

    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $server;

    /**
     * Request constructor.
     * @param array $post
     * @param array $get
     * @param array $files
     * @param array $cookie
     * @param array $session
     * @param array $request
     * @param array $server
     */
    public function __construct(array $post, array $get, array $files, array $cookie, array $session, array $request, array $server)
    {
        $this->post = $post;
        $this->get = $get;
        $this->files = $files;
        $this->cookie = $cookie;
        $this->session = $session;
        $this->request = $request;
        $this->server = $server;
    }

    /**
     * @return Request
     */
    public static function createFromGlobals()
    {
        session_start();
        return new Request($_POST, $_GET, $_FILES, $_COOKIE, $_SESSION, $_REQUEST, $_SERVER);
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param $key
     * @return array|false|string
     */
    public function getEnv($key)
    {
        return getenv($key);
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->server["REQUEST_URI"];
    }
}
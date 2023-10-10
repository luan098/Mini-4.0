<?php

namespace Mini\core;

class Router
{
    private $host;
    private $route;
    private $subRoute;
    private $params;

    public function __construct($url)
    {
        $urlParts = parse_url($url);
        $this->host = $urlParts['host'] ?? '';
        $path = trim($urlParts['path'] ?? '', '/');
        $pathSegments = explode('/', $path);

        // Extract the route, subRoute, and parameters
        $this->route = array_shift($pathSegments) ?? '';
        $this->subRoute = array_shift($pathSegments) ?? '';
        $this->params = $pathSegments;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getSubRoute()
    {
        return $this->subRoute;
    }

    public function getParams()
    {
        return $this->params;
    }
}
<?php
namespace lib\routes;


class Router
{
    protected $routesDictionary = [];

    public function get(string $route, string $action): void
    {
        $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route)) . "$@D";
        $this->routesDictionary[$pattern] = ['action'=> $action, 'method'=> 'GET'];
    }

    public function resolve($uri, $method)
    {
        foreach ($this->routesDictionary as $pattern => $route){
            $matches = Array();
            if($method == $route['method'] && preg_match($pattern, $uri, $matches)) {
                $action = explode('@',$route['action']);
                $controller = new $action[0];
                array_shift($matches);
//                $controller->$action[1]();
//                print_r($action[1]);
                print_r($controller->{$action[1]}());
            }
        }
        var_dump($uri);
    }
}
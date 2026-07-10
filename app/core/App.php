<?php 
class App{
    protected $controllers = "Home";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL() ?? [];

        if(isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')){
            $this->controllers = ucfirst($url[0]);
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controllers . '.php';
        $this->controllers = new $this->controllers;

        if(isset($url[1]) && method_exists($this->controllers, $url[1])){
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controllers, $this->method], $this->params);
    }

    public function parseURL(){
        if( isset($_GET['url']) ){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/',$url);
            return $url;
        }

        return [];
    }
}
?>

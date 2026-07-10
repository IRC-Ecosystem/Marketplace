<?php 
class Controllers{
    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function redirect($path = '')
    {
        header('Location: ' . BASEURL . $path);
        exit;
    }

    public function back()
    {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASEURL));
        exit;
    }
}
?>

<?php
abstract class Controller
{
    public function loadModel(string $model)
    {
        // initiation du model dans la classe fille
        require_once(ROOT . 'models/' . $model . '.php');
        $this->$model = new $model();
    }

    public function loadView(string $view, array $data = [])
    {
        // Récupère les données et les extraits sous forme de variables
        extract($data);
        require_once(ROOT . 'Views/' . strtolower(get_class($this)) . '/' . $view . '.php');
        // remplacer $data pour un objet
    }
}

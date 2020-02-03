<?php

namespace App\backend;

class ManagerBo
{
    protected function dbConnect()
    {
        try {
            $db = new \PDO('mysql:host=localhost:8889;dbname=project5; charset=utf8', 'root', 'root', array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            return $db;
        } catch (\Exception $e) {
            die('Erreur :' . $e->getMessage());
        }
    }
}

<?php

namespace App\core;

class DB {
    
    private static $instance = null;

    public static function getInstance(){
        if (!self::$instance) {
        self::$instance = new \PDO('mysql:host=localhost;dbname=certitransporte', 'root', '');
        }
        return self::$instance;
    }
    
}

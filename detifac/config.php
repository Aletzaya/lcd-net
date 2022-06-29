<?php
namespace detifac;

class Configuration {
    
    private static $dbc = array(
            "driver" => "mysql",
            "charset" => "utf8mb4",
            "host" => "192.168.1.200",
            "username" => "root",
            "pass" => "det15a",
            "database" => "detifac"
        );

    public static function get() {
        return (object) Configuration::$dbc;
    }
}

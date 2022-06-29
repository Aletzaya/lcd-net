<?php

namespace detifac;

require_once ('softcoatl/SoftcoatlHTTP.php');

class IConnection extends \com\softcoatl\utils\IConnection {

    public static function getConnection() {
        return parent::getConnection(Configuration::get());
    }

}

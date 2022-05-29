<?php

/*
 * PAC
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33;

class PAC {
    private $url;
    private $user;
    private $password;

    function __construct($url = '', $user = '', $password = '') {
        $this->url = $url;
        $this->user = $user;
        $this->password = $password;
    }

    function getUrl() {
        return $this->url;
    }

    function getUser() {
        return $this->user;
    }

    function getPassword() {
        return $this->password;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPassword($password) {
        $this->password = $password;
    }

}

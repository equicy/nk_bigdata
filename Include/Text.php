<?php

namespace BigData;

trait Text {
    protected static function passHash($username, $password) {
        global $_CONFIG;
        return base64_encode(hash('sha256', $username.$_CONFIG['PASSWORD_SALT'].$password, true));
    }
}
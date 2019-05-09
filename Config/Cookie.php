<?php

class Config_Cookie extends Abstract_Config
{

    public function configs()
    {
        $iniSettings = parse_ini_file(APP_ROOT . 'Config/system.ini');

        return array(
            'cookie' => new \Slim\Middleware\SessionCookie(
                array(
                    'expires' => '6 months',
                    'path' => '/',
                    'domain' => null,
                    'secure' => false,
                    'httponly' => false,
                    'name' => 'slim_session',
                    'secret' => $iniSettings['cookie_secret'],
                    'cipher' => MCRYPT_RIJNDAEL_256,
                    'cipher_mode' => MCRYPT_MODE_CBC
                )
            )
        );

    }

}

<?php

//Check if cache directories exist and create if not
$dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

$proxyCacheDir = $dataPath
    . DIRECTORY_SEPARATOR
    . 'DoctrineORMModule'
    . DIRECTORY_SEPARATOR
    . 'Proxy';
if (!is_dir($proxyCacheDir)) {
    mkdir($proxyCacheDir, 0777, true);
}

return array(
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'mydatabase',
                )
            )
        )
    ),
);

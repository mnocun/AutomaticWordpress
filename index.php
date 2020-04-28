<?php

define( 'ABS', __DIR__ );

use AutomaticWordpress\Configurate;

require __DIR__.'/source/autoload.php';
$configurate = new Configurate( __DIR__.'/enviroment.ini' );

var_dump( $configurate->configurate );

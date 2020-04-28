<?php

use AutomaticWordpress\Configurate;

define( 'ABS', __DIR__ );

require __DIR__.'/source/autoload.php';
require __DIR__.'/zone/index.php';

$wordpress_name = $argv[ 0 ] ?? uniqid( 'wordpress_' );

$configurate = new Configurate( __DIR__.'/enviroment.ini' );

if( !download_wordpress( $configurate ) )
    exit( 'Error! Cannot download wordpress' );

$wordpress_file = __DIR__.'/zone/wordpress/';

$database = new Database( $configurate );

$wordpress = new Wordpress( $wordpress_file );
$wordpress->add_database_property( $database );
$wordpress->write();

if( !move_to_desination( $configurate, $wordpress_name ) )
    exit( "Error! Cannot move to destination location! \n Wordpress directory is avaliable in '$wordpress_file'" );

exit( 'Wordpress installed successfully' );
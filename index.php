<?php

use AutomaticWordpress\Configurate;
use AutomaticWordpress\Database;
use AutomaticWordpress\Wordpress;

define( 'ABS', __DIR__ );

require __DIR__.'/source/autoload.php';
require __DIR__.'/zone/index.php';

$wordpress_name = $argv[ 0 ] ?? uniqid( 'wordpress_' );
$wordpress_template_location = __DIR__.'/source/wp-config-template.txt';

$configurate = new Configurate( __DIR__.'/environment.ini' );

if( !download_wordpress( $configurate ) )
    exit( 'Error! Cannot download wordpress' );

$wordpress_dir = __DIR__.'/zone/wordpress/';

$database = new Database( $configurate, $wordpress_name );

$wordpress = new Wordpress( $configurate, $wordpress_template_location );
$wordpress->add_database_property( $database );
$wordpress->write( $wordpress_dir );

if( !move_to_destination( $configurate, $wordpress_dir, $wordpress_name ) )
    exit( "Error! Cannot move to destination location! \n Wordpress directory is avaliable in '$wordpress_file'" );

exit( 'Wordpress installed successfully' );
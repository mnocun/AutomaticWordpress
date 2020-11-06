<?php

namespace AutomaticWordpress;
use Exception;

define('ABS', __DIR__);

require implode(DIRECTORY_SEPARATOR, [ABS, 'autoload.php']);

echo '<pre>';

try {
    $startExecutionTime = microtime(true);

    $configuration = new Configuration(implode(DIRECTORY_SEPARATOR, [ABS, '.env']));
    $database = new Database($configuration);
    $wordpress = new Wordpress($database, $configuration);
    
    if (!$wordpress->install(implode(DIRECTORY_SEPARATOR, [ABS, 'zone', 'example']), Lang::EN)) {
        throw new Exception('Cannot install wordpress', 20);
    } else {
        throw new Exception('Wordpress has been installed successfully', 0);
    }
} catch (Exception $exception) {
    echo $exception->getMessage().PHP_EOL;
    echo 'Execution time: '.round(microtime(true) - $startExecutionTime, 2).' s'.PHP_EOL;
}




// require __DIR__.'/source/autoload.php';
// require __DIR__.'/zone/index.php';

// require(__DIR__.'/classes/wordpress.class.php');

// $wordpress_name = $argv[ 1 ] ?? uniqid( 'wordpress_' );
// $wordpress_template_location = __DIR__.'/source/wp-config-template.txt';

// $configurate = new Configurate( __DIR__.'/environment.ini' );

// if( !download_wordpress( $configurate ) )
//     exit( "Error! Cannot download wordpress\n" );

// $wordpress_dir = __DIR__.'/zone/wordpress/';

// $database = new Database( $configurate, $wordpress_name );

// $wordpress = new Wordpress( $configurate, $wordpress_template_location );
// $wordpress->add_database_property( $database );
// $wordpress->write( $wordpress_dir );

// if( !move_to_destination( $configurate, $wordpress_dir, $wordpress_name ) )
//     exit( "Error! Cannot move to destination location! \n Wordpress directory is avaliable in '$wordpress_file'\n" );

// exit( "Wordpress installed successfully\n" );
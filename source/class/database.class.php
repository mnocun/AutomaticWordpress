<?php

namespace AutomaticWordpress;

final class Database {
    private $database_handle = null;

    private $current_property = [];

    public function __construct( Configurate $configuration, string $name ) {
        if( !is_array( $configuration->sql ) ) exit( 'Error! Database configurate property does not exist\n' );
        try{
            $this->database_handle = new \PDO(
                'mysql:host='.( $configuration->sql[ 'host' ] ?? 'localhost' ).';port='.( $configuration->sql[ 'port' ] ?? 3306 ),
                $configuration->sql[ 'username' ] ?? 'root',
                $configuration->sql[ 'password' ] ?? ''
            );
            $this->database_handle->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }catch( \PDOException $e ) {
            exit( 'Error! '.$e->getMessage() );
        }

        $this->current_property = [
            'host' => $configuration->sql[ 'host' ] ?? 'localhost',
            'port' => $configuration->sql[ 'port' ] ?? 3306 
        ];

        if( !$this->create_database_structure( $name ) ) 
            exit( 'Error! Cannot configurate database\n' );
    }

    public function __get( string $property ) {
        return $this->current_property[ $property ] ?? null;
    }

    private function create_database_structure( string $name ) : bool {
        try {
            $database_name = $this->sanitaze( $name );
            $database_user = 'user_'.$database_name;
            $database_password = md5( uniqid( 'KEY_' ) );
            $state = $this->database_handle->exec( "CREATE DATABASE $database_name" );
            $state = $this->database_handle->exec( "GRANT ALL PRIVILEGES ON `$database_name`.* TO '$database_user'@'localhost' IDENTIFIED BY '$database_password'" );
            $this->current_property[ 'name' ] = $database_name;
            $this->current_property[ 'user' ] = $database_user;
            $this->current_property[ 'password' ] = $database_password;
        }catch( \PDOException $e ) {
            return false;
        }
        return true;
    }

    private function sanitaze( string $property ) : string {
        $property = str_replace( [ ';', ' ', '.' ], [ '', '_', '_' ], $property );
        return $property;
    }
}
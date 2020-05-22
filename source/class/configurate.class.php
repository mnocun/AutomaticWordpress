<?php

namespace AutomaticWordpress;

final class Configurate {
    private $configurate_array = [];

    public function __construct( string $ini_file_location ) {
        if( empty( $ini_file_location ) || !file_exists( $ini_file_location ) )
            exit( "Error! Configurate file does not exits\n" );
        $this->configurate_array = parse_ini_file( $ini_file_location, true );
        if( empty( $this->configurate_array ) )
            exit( "Error! Configurate file is empty\n" );
    }

    public function __get( string $property ) {
        switch( $property ) {
            case 'configurate':
                return $this->configurate_array;
            default:
                return $this->configurate[ $property ] ?? null;
        }
    }

}
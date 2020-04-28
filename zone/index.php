<?php

use AutomaticWordpress\Configurate;

function download_wordpress( Configurate $configuration ) : bool {
    if( $configuration === null || !is_array( $configuration->wordpress ) ) return false;
    $use = $configuration->wordpress[ 'use' ] ?? 'zip';
    if( $use == 'zip' ) {
        if( !extension_loaded( 'zip' ) )
            exit( 'Error! Script required extension zip\n' );
        $url = $configuration->wordpress[ 'download_zip_url' ] ?? false;
    }else {
        if( !extension_loaded( 'phar' ) )
            exit( 'Error! Script required extension phar\n' );
        $url = $configuration->wordpress[ 'download_tar_url' ] ?? false;
    }
    if( $url === false ) return false;

    $file_name = basename( $url );
    if( !file_put_contents( __DIR__.'/'.$file_name, file_get_contents( $url ) ) && !file_exists( __DIR__.'/'.$file_name ) ) return false;

    if( $use == 'zip' ) {
        $compress_file = new ZipArchive;
        if( !$compress_file->open( __DIR__.'/'.$file_name ) ) { unlink( __DIR__.'/'.$file_name ); return false; }
        $compress_file->extractTo( __DIR__ );
        $compress_file->close();
    }else {
        try{
            $compress_file = new PharData( __DIR__.'/'.$file_name );
            $compress_file->extractTo( __DIR__ );
        }catch( Exception $e ) {
            unlink( __DIR__.'/'.$file_name );
            return false;
        }
    }
    unlink( __DIR__.'/'.$file_name );
    return true;
}

function move_to_destination( Configurate $configuration, string $wordpress_directory, string $directory_name ) : bool {
    if( $configuration === null || !is_array( $configuration->location ) || empty( $wordpress_directory ) || empty( $directory_name ) ) return false;
    if( !isset( $configuration->location[ 'path' ] ) ) return false;
    $dir_destination = rtrim( $configuration->location[ 'path' ], '/' ).'/'.$directory_name;
    if( !rename( $wordpress_directory, $dir_destination ) ) return false;
    return true;
}
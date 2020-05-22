<?php

namespace AutomaticWordpress;

final class Wordpress {
    private $template       = null;
    private $configuration  = null;
    private $database       = null;

    public function __construct( Configurate $configuration, string $template_loaction ) {
        $this->configuration = $configuration;
        $this->get_template_content( $template_loaction );
    }

    public function add_database_property( Database $database ) : bool {
        if( $database == null ) return false;
        $this->database = $database;
        return true;
    }

    public function write( string $dir_location ) : bool {
        try{
            file_put_contents( $dir_location.'wp-config.php', $this->generate() );
        }catch( \Exception $e ) {
            exit( 'Error! '.$e->getMessage() );
        }
        return false;
    }

    private function generate() : string {
        $output = str_replace( [
            '{database-name}',
            '{database-user}',
            '{database-password}',
            '{database-host}',
            '{database-port}',
            '{salt-block}',
            '{ftp-block}'
        ], [
            $this->database->name,
            $this->database->user,
            $this->database->password,
            $this->database->host,
            $this->database->port,
            $this->get_salt_block(),
            $this->get_ftp_block()
        ], $this->template );
        return $output;
    }

    private function get_template_content( string $template_loaction ) : void {
        $this->template = file_get_contents( $template_loaction );
    }

    private function get_ftp_block() : string {
        if( $this->configuration->ftp[ 'include_to_wordpress' ] ?? false ) {
            if( ( $this->configuration->ftp[ 'direct' ] ?? false ) == true ) {
                return "define('FS_METHOD', 'direct');\n";
            }else {
                if( !isset( $this->configuration->ftp[ 'host' ], $this->configuration->ftp[ 'username' ], $this->configuration->ftp[ 'password' ] ) )
                    exit( 'Error! FTP Configuration is not valid\n' );
                $host = $this->configuration->ftp[ 'host' ];
                $username = $this->configuration->ftp[ 'username' ];
                $password = $this->configuration->ftp[ 'password' ];
                return "define('FTP_USER', '$username');\ndefine('FTP_PASS', '$password');\ndefine('FTP_HOST', '$host');\n";
            }
        }else return '';
    }

    private function get_salt_block() : string {
        return file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
    }
}
<?php

namespace AutomaticWordpress;

use Exception;
use PDOException;
use PDO;

class Database
{
    protected $connection;

    protected $host;
    protected $port;
    protected $charset;

    public function __construct(Configuration $configuration)
    {   
        $host = $this->host = $configuration->db_host;
        $port = $this->port = intval($configuration->db_port);
        $charset = $this->charset = $configuration->db_charset;
        $username = $configuration->db_user;
        $password = $configuration->db_password;

        $properties = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false];

        if (!empty($charset)) {
            $properties[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES $charset";
        }

        try {
            $this->connection = new PDO(
                "mysql:host=$host;port=$port;",
                $username,
                $password,
                $properties
            );
        } catch (PDOException $exception) {
            echo $exception->getMessage().PHP_EOL;
            throw new Exception('Error connecting to the database', 12);
        }
    }

    public function createUserWithDatabase(string $name) : array
    {
        $name = str_replace( [ ';', ' ', '.' ], [ '', '_', '_' ], $name );
        try {
            $userPassword = $this->generatePassword(24);
            $this->connection->exec("CREATE DATABASE $name");
            $this->connection->exec("GRANT ALL PRIVILEGES ON `$name`.* TO '$name'@'localhost' IDENTIFIED BY '$userPassword'");
            return [
                'DB_HOST' => "{$this->host}:{$this->port}",
                'DB_CHARSET' => $this->charset,
                'DB_NAME' => $name,
                'DB_USER' => $name,
                'DB_PASSWORD' => $userPassword
            ];
        } catch (PDOException $exception) {
            echo $exception->getMessage().PHP_EOL;
            throw new Exception('Error connecting to the database', 12);
        }
    }

    public function generatePassword($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('Keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    public function __debugInfo()
    {
        return null;
    }
}
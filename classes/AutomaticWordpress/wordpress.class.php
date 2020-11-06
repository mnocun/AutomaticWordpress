<?php

namespace AutomaticWordpress;

use Exception;
use ZipArchive;
use PharData;

class Wordpress
{
    const ZIP = 'zip';
    const TAR = 'tar.gz';

    protected $database;
    protected $configuration;

    public function __construct(Database $database, Configuration $configuration)
    {
        $this->database = $database;
        $this->configuration = $configuration;
    }

    public function install(string $location, string $lang = '') : bool
    {
        $installationType = $this->detectInstallationAbility();
        $languageCode = Lang::getCode($lang);
        $urlTemplate = 'https://[LANG]wordpress.org/latest[LANG_CODE].[INSTALLATION_TYPE]';

        if (empty($lang)) {
            $downloadUrl = str_replace(['[LANG]', '[LANG_CODE]', '[INSTALLATION_TYPE]'], ['', '', $installationType], $urlTemplate);
        } else {
            $downloadUrl = str_replace(['[LANG]', '[LANG_CODE]', '[INSTALLATION_TYPE]'], [$lang.'.', '-'.$languageCode, $installationType], $urlTemplate);
        }
        $locationTemp = implode(DIRECTORY_SEPARATOR, [ABS, 'zone', 'latest.'.$installationType]);
        if (!file_put_contents($locationTemp, file_get_contents($downloadUrl))) {
            return false;
        }

        if (!$this->resolveInstallation($installationType, $locationTemp, $lang, $location)) {
            if (file_exists($locationTemp)) {
                unlink($locationTemp);
            }
            return false;
        }
        return true;
    }

    public function __debugInfo()
    {
        return null;
    }

    protected function configurateInstallation(string $location, string $wordpressFolder, string $lang) : bool
    {        
        if (!rename($wordpressFolder, $location)) {
            return false;
        }

        $databaseProperty = $this->database->createUserWithDatabase(basename($location));
        return $this->createConfigurationFile($location, $databaseProperty, $this->getFtpConfiguration());
    }

    protected function installZip(string $archiveLocation, string $lang, string $location) : bool
    {
        $archive = new ZipArchive;
        if (!$archive->open($archiveLocation)) {
            return false;
        }
        $archive->extractTo(dirname($archiveLocation));
        if (!$archive->close()) {
            echo $archive->getStatusString().PHP_EOL;
        }
        unlink($archiveLocation);
        $wordpressLocation = implode(DIRECTORY_SEPARATOR, [dirname($archiveLocation), 'wordpress']);
        return $this->configurateInstallation($location, $wordpressLocation, $lang);
    }

    protected function installTar(string $archiveLocation, string $lang, string $location) : bool
    {
        try {
            $archive = new PharData($archiveLocation);
            $archive->extractTo(dirname($archiveLocation));
        } catch (Exception $exception) {
            return false;
        }
        unlink($archiveLocation);
        $wordpressLocation = implode(DIRECTORY_SEPARATOR, [dirname($archiveLocation), 'wordpress']);
        return $this->configurateInstallation($location, $wordpressLocation, $lang);
    }

    protected function resolveInstallation(string $installationType, string $archiveLocation, string $lang, string $location) : bool
    {
        switch ($installationType) {
            case Wordpress::ZIP:
                return $this->installZip($archiveLocation, $lang, $location);
            case Wordpress::TAR:
                return $this->installTar($archiveLocation, $lang, $location);
            default:
                return false;
        }
    }

    protected function detectInstallationAbility() : string
    {
        if (extension_loaded('zip')) {
            return Wordpress::ZIP;
        } elseif (extension_loaded('phar')) {
            return Wordpress::TAR;
        } else {
            throw new Exception('The script did not detect the required extensions', 13);
        }
    }

    protected function createConfigurationFile(string $location, array $databaseProperty, array $ftpProperty) : bool
    {
        $blocks = [];
        foreach(array_merge($databaseProperty, $ftpProperty) as $name => $property) {
            $blocks[] = "define( '$name', '$property' );";
        }

        $template = file_get_contents(implode(DIRECTORY_SEPARATOR, [ABS, 'source', 'wordpressConfigurationFile.txt']));
        $template = str_replace(['{{salt}}', '{{blocks}}'], [$this->getSaltBlock(), implode(PHP_EOL, $blocks)], $template);

        return file_put_contents(implode(DIRECTORY_SEPARATOR, [$location, 'wp-config.php']), $template);
    }

    protected function getFtpConfiguration() : array
    {
        $direct = in_array($this->configuration->ftp_direct, ['true', 1]);

        $host = $this->configuration->ftp_direct;
        $username = $this->configuration->ftp_user;
        $password = $this->configuration->ftp_password;

        if ($direct) {
            return ['FS_METHOD' => 'direct'];
        }

        if (!empty($host)) {
            return [
                'FTP_HOST' => $host,
                'FTP_USER' => $username,
                'FTP_PASS' => $password
            ];
        }

        return [];
    }

    protected function getSaltBlock() : string
    {
        return file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/');
    }
}
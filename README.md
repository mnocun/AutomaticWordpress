# AutomaticWordpress
The script automatically adds wordpress files and configure it

To run the script, configure the environment by editing the `.env` file
```
DB_CHARSET = utf8
DB_HOST = localhost
DB_PORT = 3306
DB_USER = root
DB_PASSWORD = 

FTP_DIRECT = true

FTP_HOST = 
FTP_USER =
FTP_PASSWORD =
```

Configure wordpress profiles optionally in `profiles.ini`
```ini
[profile1]
lang = ES
plugins = "woocommerce, bbpress"
[profile2]
lang = RU
plugins = "classic-editor"
[profile3]
lang = EN
```

To use the script, please have the php extensions installed:
* `PDO`
* `ZIP` or `PHAR`

Flags you can use:
* `--lang` Select the language of wordpress in iso format, e.g. EN, ES, PL
* `--profile` Select the wordpress profile defined in profiles.ini

Use `php index.php [INSTALLATION FOLDER] [FLAGS]` to execute script

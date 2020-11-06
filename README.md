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
To use the script, please have the php extensions installed :
* `PDO`
* `ZIP` or `PHAR`

Use `php index.php [INSTALLATION FOLDER] [?LANG]` to execute script

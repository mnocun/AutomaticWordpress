# AutomaticWordpress
The script automatically adds wordpress files and configure it

To run the script, configure the environment by editing the `environment.ini` file
```ini
[wordpress]
; Change this if you use another wordpress lang version
use = 'zip' ; [ 'zip', 'tar' ]
download_zip_url = "https://pl.wordpress.org/latest-pl_PL.zip"
download_tar_url = "https://pl.wordpress.org/latest-pl_PL.tar.gz"
[sql]
host = 'localhost'
port = 3306
username = 'root'
password = ''
[ftp]
; When you set true option below you must set host,username and password
include_to_wordpress = true
direct = true
host = ""
username = ""
password = ""
[location]
; Set directory where wordpress will be installed
path = ""
```
To use the script, please have the php extensions installed :
* `PDO`
* `ZIP` or `PHAR`

Use `php index.php "instalation_name"` to execute script

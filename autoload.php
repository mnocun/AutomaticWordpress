<?php

set_include_path(implode(PATH_SEPARATOR, [
    get_include_path(),
    implode(DIRECTORY_SEPARATOR, [ABS, 'classes'])
]));
spl_autoload_extensions('.php,.class.php');
spl_autoload_register();
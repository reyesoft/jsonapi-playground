<?php
declare(strict_types=1);

$helper_meta_file = __DIR__.'/../../bootstrap/cache/_ide_helper_meta.php';
if(!file_exists($helper_meta_file)) {
    chdir(__DIR__.'/../../');
//    exec('composer ide-helper');
}
// require $helper_meta_file;

require __DIR__.'/../../vendor/nunomaduro/larastan/bootstrap.php';

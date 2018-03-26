<?php

/**
 * @param $msg - the error text
 * @param $line - __LINE__ of calling error
 * @param $file - __FILE__ of calling error
 */
function ww_log_error($msg, $line, $file){
    error_log("***ERROR: " .$msg . " LINE: " . $line . " FILE: " . $file);

    //TODO if we want to log/notify anywhere else we can do it!
}



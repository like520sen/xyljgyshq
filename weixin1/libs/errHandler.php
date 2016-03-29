<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/5/23
 * Time: 14:02
 */

function customError($errNo,$errStr,$errFile,$errLine){
    echo "<b>Custom error:</b>[$errNo] $errStr</br>";
    echo "Error on line $errLine in $errFile</br>";
    echo "Ending Script";
    $errText="Custom error $errNo on line $errLine in $errFile on ".date('Y-m-d H:i:s',time());
    writerErrLog($errText);
}

//writer err to errLog.txt
function writerErrLog($errText){
//    err::writerErrLogToDatabase($errText);

}
//set error handler
set_error_handler("customError");

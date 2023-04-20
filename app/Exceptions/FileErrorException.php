<?php

namespace App\Exceptions;

use Exception;

class FileErrorException extends Exception
{
    public function report(){

    }

    public function render($request){
        return 'Failed to upload file!';

    }

}

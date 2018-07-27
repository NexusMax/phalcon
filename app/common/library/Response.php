<?php
namespace App\Library;
use Phalcon\Http\Response as BaseResponse;

/**
 * Description of Response
 *
 * @author softdream
 */
class Response extends BaseResponse {
    //put your code here

    public function redirect($locationPath = null, $baseUrl = null, $statusCode = null) {
        if($statusCode){
            $this->setStatusHeader($code);
        }

        if(substr($locationPath, 0,1) === '/'){
            $locationPath = substr($locationPath, 1);
        }

        header("Location: ".$baseUrl.'/'.$locationPath);
        exit;
    }

    protected function setStatusHeader($code){
        header("HTTP/1.0 ".$code);
    }

}
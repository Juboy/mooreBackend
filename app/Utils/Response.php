<?php

namespace App\Utils;


class Response
{

    public static $success = 0;
    public static $failed = 1;

    private $status;
    private $message;

    public function setStatus(int $status) {
        $this->status = $status;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function setMessage(String $message){
        $this->message = $message;
    }

    public function getMessage(): String {
        return $this->message;
    }

    public function toArray(){
        return ['status' => $this->status, 'message' => $this->message];
    }
}

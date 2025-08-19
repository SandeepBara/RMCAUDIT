<?php

    function sendMail($sendTo="",$contains="",$subject="Email from SUDA")
    {
        $email = \Config\Services::email();

        $email->setTo($sendTo);
        $email->setSubject($subject);
        $email->setMessage($contains);
        $response = $email->send();

        if (!$response) {
            $email->printDebugger(['headers']);
        } 
        return $response;
    }




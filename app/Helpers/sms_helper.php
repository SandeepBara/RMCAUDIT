<?php

if(!function_exists('SMSJHGOVT'))
{
	function SMSJHGOVT($mobileno, $message, $templateid=null)
    {
        if(strlen($mobileno)==10 && is_numeric($mobileno) && $templateid != NULL)
        {
            $username="uidjharsms-udd"; //username of the department
            $password="udd#@1"; //password of the department
            $senderid="JHGOVT"; //senderid of the deparment
            $message=$message; //message content
            $deptSecureKey= "d6c0ac84-59eb-4908-95aa-d1478e996c3b"; //departsecure key for encryption of message...
            $encryp_password=sha1(trim($password));
            $url = "https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT";

            $key=hash('sha512', trim($username).trim($senderid).trim($message).trim($deptSecureKey));
            $data = array(
				"username" => trim($username),
				"password" => trim($encryp_password),
				"senderid" => trim($senderid),
				"content" => trim($message),
				"smsservicetype" =>"singlemsg",
				"mobileno" =>trim($mobileno),
				"key" => trim($key),
				"templateid" => $templateid,
            );

            $fields = '';
            foreach($data as $key => $value) {
                $fields .= $key . '=' . urlencode($value) . '&';
            }
            rtrim($fields, '&');
            $post = curl_init();
            //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
            curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
            curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($post, CURLOPT_URL, $url);
            curl_setopt($post, CURLOPT_POST, count($data));
            curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($post); //result from mobile seva server
            curl_close($post);// print_var($post);

            $response = ['response'=>true, 'status'=> 'success', 'msg'=>1];
            if (strpos($result, '402,MsgID') !== false)
            {
                $response = ['response'=>true, 'status'=> 'success', 'msg'=>$result];
            }
            else
            {
                $response = ['response'=>false, 'status'=> 'failure', 'msg'=>$result];                
            }
			
			//print_r($response);
            return $response;
        }
        else
        {
            if($templateid == NULL)
              $response = ['response'=>false, 'status'=> 'failure', 'msg'=>'Template Id is required'];
            else
              $response = ['response'=>false, 'status'=> 'failure', 'msg'=>'Invalid Mobile No.'];
            return $response;
        }
	}
}
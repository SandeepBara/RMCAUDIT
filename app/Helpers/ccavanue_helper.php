<?php 
    
    function encrypt($plainText,$key)
    {
        $key = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    function decrypt($encryptedText,$key)
    {
        
        $key = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $data = convertArray($decryptedText);
        return $data;
        //return $decryptedText;
    }
        //*********** Padding Function *********************

    function pkcs5_pad ($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

        //********** Hexadecimal to Binary function for php 4.0 version ********

    function hextobin($hexString) 
    { 
        $length = strlen($hexString); 
        $binString="";   
        $count=0; 
        while($count<$length) 
        {       
            $subString =substr($hexString,$count,2);           
            $packedString = pack("H*",$subString); 
            if ($count==0)
            {
                $binString=$packedString;
            } 
            
            else 
            {
                $binString.=$packedString;
            } 
            
            $count+=2; 
        } 
        return $binString; 
    } 

    function convertArray(string $dicriptTest)
    {
        $arr = explode('&', $dicriptTest);
       
        if(sizeof($arr)==1)
        {
            return json_decode(json_encode(json_decode($arr[0])),true)['Order_Status_Result'];
        }
        
        $data = [];
        foreach($arr as $val)
        {
            $valuse = explode('=', $val);
            $data[$valuse[0]] = $valuse[1]??"";
        }
        return $data;

    }
    function dataShow(array $data)
    {
        print('<pre>'. print_r($data, true) . '</pre>');
    }

    function modelShow($data)
    {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>Municipal Corporation</title>
            <link rel='icon' href='".base_url()."/public/assets/img/favicon.ico'>
            <link href='".base_url()."/public/assets/css/bootstrap.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/css/nifty.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/css/demo/nifty-demo-icons.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/plugins/font-awesome/css/font-awesome.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css' rel='stylesheet'>
            <link href='".base_url()."/public/assets/css/common.css' rel='stylesheet'>
            <script src='".base_url()."/public/assets/js/jquery.min.js'></script>
        ";
        echo "
        </body>
        </html>
        <script src='".base_url()."/public/assets/js/bootstrap.min.js'></script>
        <script src='".base_url()."/public/assets/js/nifty.min.js'></script>
        <script src='".base_url()."/public/assets/plugins/masked-input/jquery.maskedinput.min.js'></script>
        <script src='".base_url()."/public/assets/plugins/select2/js/select2.min.js'></script>
        <script src='".base_url()."/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js'></script>
        <script src='".base_url()."/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js'></script>
        <script src='".base_url()."/public/assets/bootstrap4-toggle/js/bootstrap4-toggle.min.js'></script>
        <script type='text/javascript'>
            $(window).on('load', function() {
                $('#payment_Model').modal('show');
            });
            function redirectWindow()
            {
                if('".$data['order_status']."' != 'Success')
                {
                    $('#postForm').attr('action','".$data['cancel_url']."') ;
                    $('#postForm').submit();

                    //document.location.href='".$data['cancel_url']."';
                }
                $('#postForm').attr('action','".$data['redirect_url']."') ;
                $('#postForm').submit();
                //document.location.href='".$data['redirect_url']."';
            }
        </script>
        ";
        echo "<form method='post' id = 'postForm' action =''>
                ";
                foreach($_POST as  $key=>$val)
                {
                    echo "<input type ='hidden' name ='".$key."' value = '".$val."'/>";
                }                
            echo "
             </form>
             <div class='col-md-12  text-center ' style ='padding-top:200px;'>
                <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#payment_Model'>Click here to Show Status</button>
                <!-- Owner Doc Upload Modal -->
                <div class='modal fade' id='payment_Model' role='dialog'>
                    <div class='modal-dialog modal-lg'>
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <button type='button' class='close btn-sm' onclick='redirectWindow()' data-dismiss='modal'>&times;</button>
                                <h4 class='modal-title'>Payment Response Status</h4>
                            </div>
                            <div class='modal-body'>
                                <div class='table-responsive'>
                                    <table class='table table-bordered text-sm' >
                                        <tr>
                                            <td><b>Payment Status</b></td>
                                            <td>:</td>
                                            <td>".$data['order_status']."</td>
                                            <td><b>Oder No.</b></td>
                                            <td>:</td>
                                            <td>".$data['order_id']."</td>
                                        </tr>
                                        <tr>
                                            <td><b>Biller Mobile No.</b></td>
                                            <td>:</td>
                                            <td>".$data['billing_tel']."</td>
                                            <td><b>Biller Email Id</b></td>
                                            <td>:</td>
                                            <td>".$data['billing_email']."</td>
                                            
                                        </tr>
                                        <tr>                            
                                            <td>Amount</td>
                                            <td>:</td>
                                            <td>".$data['amount']."</td>
                                            <td></td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>:</td>
                                            <td colspan='3'></td>
                                            <td colspan='4'>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>:</td>
                                            <td colspan='3'>                                
                                            </td>
                                            <td colspan='4'>                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='9' class='text-right'>                                
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }
    function getWorkingKey($modeuleId)
    {
        $WorkingKey = "";
        switch($modeuleId)         
        {
            #property
            case 1 :    $WorkingKey= getenv("PROPERTY_CCA_WORKING_KEY");
                        break;
            #water
            case 2 :    $WorkingKey= getenv("WATER_CCA_WORKING_KEY");
                        break;
            #Trade
            case 3 :    $WorkingKey= getenv("TRADE_CCA_WORKING_KEY");
                        break;
        }
        return $WorkingKey;
    }
    function getAccessCode($modeuleId)
    {
        $AccessCode = "";
        switch($modeuleId)         
        {
            #property
            case 1 :    $AccessCode= getenv("PROPERTY_CCA_ACCESS_CODE");
                        break;
            #water
            case 2 :    $AccessCode= getenv("WATER_CCA_ACCESS_CODE");
                        break;
            #Trade
            case 3 :    $AccessCode= getenv("TRADE_CCA_ACCESS_CODE");
                        break;
        }
        return $AccessCode;
    }
    function getOderId(int $modeuleId)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';        
        for ($i = 0; $i < 10; $i++) 
        {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $orderId = (("Order_".$modeuleId.date('dmyhism').$randomString));
        $orderId = explode("=",chunk_split($orderId,30,"="))[0];         
        $CCA_MERCHANT_ID = "";
        switch($modeuleId)         
        {
            #property
            case 1 :    $CCA_MERCHANT_ID= getenv("PROPERTY_CCA_MERCHANT_ID");
                        break;
            #water
            case 2 :    $CCA_MERCHANT_ID= getenv("WATER_CCA_MERCHANT_ID");
                        break;
            #Trade
            case 3 :    $CCA_MERCHANT_ID= getenv("TRADE_CCA_MERCHANT_ID");
                        break;
        } 
        if(!$CCA_MERCHANT_ID)
        {
            throw new Exception("MERCHANT_ID NOT CREATED");
        }
        return ["orderId"=>$orderId,"merchantId"=>$CCA_MERCHANT_ID] ;
    }
    
    function CCAvanuePay($merchantId, $accessCode, $workingKey, $orderId, $amount, $redirectUrl, $cancelUrl, $billing_mobile_no,$appNo="")
    { 
        helper(['db_helper']);
        try{
            $merchant_data = 'merchant_id='.$merchantId;
            $merchant_data .= '&language=EN';
            $merchant_data .= '&amount='.$amount;
            $merchant_data .= '&currency=INR';
            $merchant_data .= '&redirect_url='.$redirectUrl;
            $merchant_data .= '&cancel_url='.$cancelUrl;
            $merchant_data .= '&billing_name=RANCHI NAGAR NIGAM';            
            $merchant_data .= '&billing_address='.$appNo;
            $merchant_data .= '&billing_tel='.$billing_mobile_no;
            $merchant_data .= "&order_id=".$orderId;

            $encrypted_data=encrypt($merchant_data, $workingKey);
            $inputs=[
                "merchant_id"   => $merchantId,
                "amount"    => $amount,
                "redirect_url"  => $redirectUrl,
                "cancel_url"    => $cancelUrl,
                "billing_tel" => $billing_mobile_no,
                "order_id"  =>  $orderId,
                "payload" =>$merchant_data,
                "enct_payload" =>    $encrypted_data
            ];
            $db = db_connect(dbSystem());
            $builder = $db->table("tbl_ccrevenue_request")
                ->insert($inputs);
            if(!$db->insertID())
            {
                return;
            }
            echo "<form method='post' name='redirect' action='".getenv("CCREVEN_URL")."'>
                    <input type=hidden name=encRequest value=".$encrypted_data.">
                    <input type=hidden name=access_code value=".$accessCode.">
                    </form>
                    <script language='javascript'>document.redirect.submit();</script>
                ";
            die;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function CheckReqResp($merchant_json_data, $working_key, $access_code)
    {
        $merchant_data = json_encode($merchant_json_data);
        $encrypted_data = encrypt($merchant_data, $working_key);
        $final_data = 'enc_request='.$encrypted_data.'&access_code='.$access_code.'&command=orderStatusTracker&request_type=JSON&response_type=JSON';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://apitest.ccavenue.com/apis/servlet/DoWebTrans");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER,'Content-Type: application/json') ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
        // Get server response ...
        $result = curl_exec($ch);
        curl_close($ch);
        $status = '';
        $information = explode('&', $result);
        
        $dataSize = sizeof($information);
        for ($i = 0; $i < $dataSize; $i++) {
            $info_value = explode('=', $information[$i]);
            if ($info_value[0] == 'enc_response') {
                $status = decrypt(trim($info_value[1]), $working_key);
                
            }
        }
        return $status;
    }

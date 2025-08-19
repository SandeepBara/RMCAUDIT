<?php

function display_error($validation,$field)
{
    if($validation->hasError($field))
    {
        //print_var($validation->getError($field));
        return $validation->getError($field);
    }
    else
        return false;
}

 function exporttoexcel($data, $filename='report')
{
    
    $session = session();
    //$length = $data['count'];
    
    if(count($data['result']) > 0)
    { 
        $delimiter = ","; 
        $filename = $filename.strtotime(date('Y-m-d h:i:s')).".csv";

        // Create a file pointer ;
        $f = fopen('php://memory', 'w'); 	

        // Set column headers ;
        $fields=array('Sl No.');
        foreach($data['result'][0] as $key => $val)
        {
                array_push($fields,$key); 
        }
        fputcsv($f, $fields, $delimiter); 
        
        foreach($data['result'] as $key =>$val)
        {
            $line = array($key);
            foreach($val as $field)
            {
                array_push($line,$field);
               
            }
            
            fputcsv($f, $line, $delimiter);   
        }
        
        fseek($f, 0); 
        
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
            
        //output all remaining data on a file pointer 
        fpassthru($f); 
    
    }
    exit;
}

function keyword()
{
    if(strtoupper($_SERVER['REQUEST_METHOD'])=='POST')
    {
        $data='';
        foreach($_POST as $key =>$val)
        {
            if(!in_array(strtolower($key),['ward_id','ward_no','ward']))
                $data.="$val";
        }
    }
    return $data;
}


function getDenialAmountTrade($notice_date=null,$current_date=null)
{
    
    
    $notice_date=$notice_date?date('Y-m-d',strtotime($notice_date)):date('Y-m-d');
    $current_date=$current_date?date('Y-m-d',strtotime($current_date)):date('Y-m-d');
    $datediff = strtotime($current_date)-strtotime($notice_date); //days difference in second
    $totalDays =   abs(ceil($datediff / (60 * 60 * 24))); // total no. of days
    $denialAmount=100+(($totalDays)*10);
   
    return $denialAmount;
}

function re_day_diff($from_date, $licence_for_year,$post_from=null)
{
    $valid_from = $from_date;
    $valid_upto =date("Y-m-d", strtotime("+$licence_for_year years", strtotime($valid_from)));
    $diff_day = (int)date_diff(date_create(date('Y-m-d')),date_create($valid_upto))->format("%R%a");
    $year = abs(((-1)*$diff_day)/365);
    //$valid_upto = date("Y-m-d",($diff_day)); 
    $temp = [
        'diff_day'=>$diff_day,
        'valid_upto'=>$valid_upto,
        'valid_from'=>$valid_from,
        'year'=>$year,
    ];
    
    if($post_from)
    {
        return json_encode($temp);
    }
    return $temp;       
}

/************************* sms according to template id *****************************/

//for trade

if(!function_exists('Trade'))
{
    function Trade($data=array(),$sms_for=null)
    {
        if(strtoupper($sms_for)==strtoupper('Payment done'))
        {       
          try
          {
              // Payment done with amount {#var#} for Application No {#var#}. {#var#}
              $sms = "Payment done with amount ".$data['ammount']." for Application No ".$data['application_no'].". Reference Number '".$data['ref_no']."'";
              $temp_id = "1307162359745436093";
              return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
          } 
          catch(Exception $e)
          {
            return array("sms_formate"=>"Payment done with amount {#var#} for Application No {#var#}. {#var#}",
            "discriuption"=>"1. 2 para required 
                      2. 1st para array('ammount'=>'','application_no'=>'','ref_no'=>'') sizeof 3  
                      3. 2nd para sms for ",
            "error"=>$e,
            'status'=>false);
          }
        }
        

        elseif(strtoupper($sms_for)==strtoupper('License expired'))
        {
            try
            {
                // Dear Trade Owner, Your Municipal Trade License {#var#} is to be expired on {#var#}. Please renew your license to avoid legal actions. Please ignore if already done. For Details call-{#var#} {#var#}
                //$sms = "Dear Trade Owner, Your Municipal Trade License 11 is to be expired on 2022-03-01. Please renew your license to avoid legal actions. Please ignore if already done. For Details call-123 123"; 
                $sms = "Dear Trade Owner, Your Municipal Trade License ".$data['licence_no']." is to be expired on ".$data['exp_date'].". Please renew your license to avoid legal actions. Please ignore if already done. For Details call-".$data['toll_free_no1'].' '.$data['ulb_name']."";
                $temp_id = "1307162359758955377";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Dear Trade Owner, Your Municipal Trade License {#var#} is to be expired on {#var#}. Please renew your license to avoid legal actions. Please ignore if already done. For Details call-{#var#} {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('licence_no'=>'','exp_date'=>'','toll_free_no1'=>'','ulb_name'=>'') sizeof 4  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('Application Approved'))
        {
            try
            {
                // Your Application {#var#} has been approved. Your License no is {#var#}. {#var#}               
                $sms = "Your Application $data[application_no] has been approved. Your License no is $data[licence_no]. $data[ulb_name]";
                $temp_id = "1307162359751828659";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Application {#var#} has been approved. Your License no is {#var#}. {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('application_no'=>'','licence_no'=>'','ulb_name'=>'') sizeof 3  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('sent back'))
        {
            try
            {
                // Your Application {#var#} is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION               
                //$sms = "Your Application $data[application_no] is sent back to $data[to] by $data[by] for rectification. Please rectify it and submit it shortly. $data[ulb_name]";
                $sms = "Your Application $data[application_no] is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION";
                $temp_id = "1307161908232955556";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Application {#var#} is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('application_no'=>'') sizeof 1  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }
        else
        {
            return array('sms'=>'pleas supply two para',
                          '1'=>'array()',
                          '2'=>"sms for 
                          1. Payment done
                          2. License expired
                          3. Application Approved
                          4. sent back",
                          'status'=>false
                        );
        }
        
    }
}

if(!function_exists('Water'))
{
    function Water($data=array(),$sms_for=null)
    {
        if(strtoupper($sms_for)==strtoupper('Apply Application'))
        {
            $sms="Your Application No. for Water Connection request is {#var#}. {#var#}";
            try
            {
                // Your Application No. for Water Connection request is {#var#}. {#var#}
                $sms = "Your Application No. for Water Connection request is ".$data['application_no'].". ".$data['ulb_name'];
                $temp_id = "1307162359771216938";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Application No. for Water Connection request is {#var#}. {#var#}",
                "discriuption"=>"1. 2 para required 
                        2. 1st para array('application_no'=>'','ulb_name'=>'') sizeof 2  
                        3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('Payment done'))
        {       
          try
          {
              // Payment of Rs. {#var#} for Application No. {#var#} have been successfully done. {#var#}
              $sms = " Payment of Rs. ".$data['ammount']." for Application No. ".$data['application_no']." have been successfully done. Trans. No.".$data['ref_no']."";
              $temp_id = "1307162359771216938";
              return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
          } 
          catch(Exception $e)
          {
            return array("sms_formate"=>"Payment of Rs. {#var#} for Application No. {#var#} have been successfully done. {#var#}",
            "discriuption"=>"1. 2 para required 
                      2. 1st para array('ammount'=>'','application_no'=>'','ref_no'=>'') sizeof 3  
                      3. 2nd para sms for ",
            "error"=>$e,
            'status'=>false);
          }
        }
        

        elseif(strtoupper($sms_for)==strtoupper('consumer Payment'))
        {
            try
            {
                // Water User Charge of Rs. {#var#} for Consumer No. {#var#} have been successfully done. {#var#}                
                $sms = "Water User Charge of Rs. $data[ammount] for Consumer No. $data[consumer_no] have been successfully done. Trans. No.'$data[ref_no]'";
                $temp_id = "1307162359786763116";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Water User Charge of Rs. {#var#} for Consumer No. {#var#} have been successfully done. {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('ammount'=>'','consumer_no'=>'','ref_no'=>'') sizeof 3  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('Application Approved'))
        {
            try
            {
                // Your Water connection request has been approved. Your Consumer Number is {#var#}               
                $sms = "Your Water connection request has been approved. Your Consumer Number is $data[consumer_no]";
                $temp_id = "1307161908275182619";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Water connection request has been approved. Your Consumer Number is {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('consumer_no'=>'') sizeof 1  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('sent back'))
        {
            try
            {
                // Your Application {#var#} is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION               
                //$sms = "Your Application $data[application_no] is sent back to $data[to] by $data[by] for rectification. Please rectify it and submit it shortly. $data[ulb_name]";
                $sms = "Your Application $data[application_no] is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION";
                $temp_id = "1307161908232955556";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Application {#var#} is sent back to you for rectification. Please rectify it and submit it shortly. RANCHI MUNICIPAL CORPORATION",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('application_no'=>'') sizeof 1  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('Site inspection set'))
        {
            try
            {
                // Your Site inspection Date is set on {#var#}. Please be there around the time. RANCHI MUNICIPAL CORPORATION               
                $sms = "Your Site inspection Date is set on $data[timestampe]. Please be there around the time. RANCHI MUNICIPAL CORPORATION";
                $temp_id = "1307161908281616235";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Your Site inspection Date is set on {#var#}. Please be there around the time. RANCHI MUNICIPAL CORPORATION",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('timestampe'=>'') sizeof 1  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        elseif(strtoupper($sms_for)==strtoupper('Site inspection cancelled'))
        {
            try
            {
                // Sorry!!.. Your inspection date and time is cancelled, New date and time will be informed you shortly '.RANCHI MUNICIPAL CORPORATION               
                $sms = "Sorry!!.. Your inspection date and time is cancelled, New date and time will be informed you shortly '.RANCHI MUNICIPAL CORPORATION";
                $temp_id = "1307161908287515622";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Sorry!!.. Your inspection date and time is cancelled, New date and time will be informed you shortly '.RANCHI MUNICIPAL CORPORATION",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array() sizeof 0  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }
        elseif(strtoupper($sms_for)==strtoupper('Consumer Demand'))
        {
            try
            {
                //Pls pay water user charge of amount {#var#} against your consumer no. {#var#}. * Pls. ignore if already paid. If any query call us {#var#}. {#var#}               
                $sms = "Pls pay water user charge of amount ".$data['amount']." against your consumer no. ".$data['consumer_no'].". * Pls. ignore if already paid. If any query call us ".$data['toll_free_no1'].'. '.$data['ulb_name'];
                $temp_id = "1307162359780171746";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Pls pay water user charge of amount {#var#} against your consumer no. {#var#}. * Pls. ignore if already paid. If any query call us {#var#}. {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('amount'=>'','consumer_no'=>'','toll_free_no1'=>'','ulb_name'=>'') sizeof 4  
                      3. 2nd para sms for ",
                "error"=>$e,
                'status'=>false);
            }
        }

        else
        {
            return array('sms'=>'pleas supply two para',
                          '1'=>'array()',
                          '2'=>"sms for 
                          1. Payment done
                          2. consumer Payment
                          3. Application Approved
                          4. sent back
                          5. Site inspection set
                          6. Site inspection cancelled
                          7. Apply Application",                          
                          'status'=>false
                        );
        }
        
    }
}

if(!function_exists("Property"))
{
    function Property($data=array(),$sms_for=null)
    {
        if(strtoupper($sms_for)==strtoupper('Holding Demand'))
        {
            try
            {
                //Holding Tax of Rs{#var#} upto QTR {#var#} is due for Holding No: {#var#} {#var#}               
                $sms = "Holding Tax of Rs ".$data["amount"]." upto QTR ".$data["qtr"]." is due for Holding No: ".$data["holding_no"]." ".$data['ulb_name']."";
                $temp_id = "1307162359693822172";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Holding Tax of Rs{#var#} upto QTR {#var#} is due for Holding No: {#var#} {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('amount'=>'','qtr'=>'','holding_no'=>'','ulb_name'=>'') sizeof 4  
                      3. 2nd para sms for ",
                "error"=>$e->getMessage(),
                'status'=>false);
            }
        }
        elseif(strtoupper($sms_for)==strtoupper('Holding Demand Res'))
        {
            try
            {
                //Dear {#var#}, pay your against Holding No. {#var#} and Ward No {#var#} amount. {#var#}, * Please ignore if already paid. {#var#}               
                // $sms = "Holding Tax of Rs ".$data["amount"]." upto QTR ".$data["qtr"]." is due for Holding No: ".$data["holding_no"]." ".$data['ulb_name']."";
                $sms = "Dear ".$data["owner_name"].", pay your against Holding No. ".$data["holding_no"]." and Ward No ".$data["ward_no"]." amount. ".$data["amount"].", * Please ignore if already paid. ".$data['ulb_name']."";
                $temp_id = "1307162359687707022";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Dear {#var#}, pay your against Holding No. {#var#} and Ward No {#var#} amount. {#var#}, * Please ignore if already paid. {#var#}",
                "discriuption"=>"1. 2 para required 
                      2. 1st para array('owner_name'=>'','holding_no'=>'','ward_no'=>'','amount'=>'','ulb_name'=>'') sizeof 5  
                      3. 2nd para sms for ",
                "error"=>$e->getMessage(),
                'status'=>false);
            }
        }elseif(strtoupper($sms_for)==strtoupper('Holding Payment')){
            try
            {
                //Dear Citizen, than you for payment of INR {#var#}against holding number {#var#}for period of {#var#} to {#var#}. Call us @ 1800-123-7785              
                $sms = "Dear Citizen, than you for payment of INR ".$data["amount"]." against holding number ".($data["new_holding_no"]??$data["holding_no"])." for period of ".($data["from_qtr"]."/".$data['from_fyear'])." to ".($data["upto_qtr"]."/".$data['upto_fyear']).". Call us @ 1800-123-7785";
                $temp_id = "1307161908208019516";
                return array("sms"=>$sms,"temp_id"=>$temp_id,'status'=>true);              
            } 
            catch(Exception $e)
            {
                return array("sms_formate"=>"Dear Citizen, than you for payment of INR {#var#}against holding number {#var#}for period of {#var#} to {#var#}. Call us @ 1800-123-7785",
                "discriuption"=>"1. 6 para required 
                      2. 1st para array('amount'=>'','new_holding_no'=>'','from_qtr'=>'','from_fyear'=>'','upto_qtr'=>'','upto_fyear'=>'') sizeof 6  
                      3. 2nd para sms for ",
                "error"=>$e->getMessage(),
                'status'=>false);
            }
        }else{

        }
    }
}

if(!function_exists('insert_sms_log'))
{
    function insert_sms_log($db,$table,$data=array())
    {   
        try
        {
            $db->table($table)->insert($data);            
            return $db->insertID();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }
}

if(!function_exists('update_sms_log'))
{
    function update_sms_log($db,$table,$where=array(),$data=array())
    {   
        try
        {
            $db->table($table)->where($where)->update($data);
            //print_var($db->affectedRows());
            return $db->affectedRows();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }
}


if(!function_exists('send_sms'))
{
    function send_sms($mobile,$message, $templateid)
    {
        $res=SMSJHGOVT((strtoupper(getenv("SMS_TEST","false"))=="TRUE" ? "9304194749":$mobile), $message, $templateid);
        return $res;
    }
}

if(!function_exists('fy_year_list'))
{
    function fy_year_list($date=null)
    {
        $data=[];
        $strtotime = $date?strtotime($date):strtotime(date('Y-m-d'));
        $y = date('Y',$strtotime);
        $m=date('m',$strtotime);
        $year = $y;
        if($m>3)
            $year = $y+1;
        while (true)
        {
            $data[]=($year-1).'-'.$year;
            if($year=='2015')
                break;
            --$year;
        }
        // print_var($data);die;
        return ($data);
        
    }
    
}

#==========visiting report array maker=======

if(!function_exists("getlatLongIpAddress"))
{
    function getlatLongIpAddress(array $request=[])
    {
        if($request)
        {
            return ["latitude"=>$request["visiting_latitude"]??null,"longitude"=>$request["visiting_longitude"]??null,"ip"=>$request["visiting_ip"]??null,"address"=>$request["visiting_address"]??null,"status"=>true];
        }
        $getloc = json_decode(file_get_contents("http://ipinfo.io/"));
                    
        $coordinates = explode(",", $getloc->loc);
        $latitude = $coordinates[0]; // latitude
        $longitude = $coordinates[1]; // longitude
        $city = $getloc->city; $org = $getloc->org; $region = $getloc->region;
        $country = $getloc->country; $postal = $getloc->postal;
        $ip = $getloc->ip;
        $address = $org.",".$city.",".$region.",".$country.",".$postal;
        return ["latitude"=>$latitude,"longitude"=>$longitude,"ip"=>$ip,"address"=>$address,"status"=>true];
    }
}


if(!function_exists("waterDemandGenrateVisit"))
{
    function waterDemandGenrateVisit(array $consumer,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);

        return $vistingRepostInput = [
            'ref_no'=>$consumer['consumer_no'],
            'ref_type_id'=>$consumer['id'],
            'remarks_id' => 14,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 3,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
        ];
         
    }
}


if(!function_exists("waterConsumerTranVisit"))
{
    function waterConsumerTranVisit(array $consumer,$tranId,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);

        return $vistingRepostInput = [
            'ref_no'=>$consumer['consumer_no'],
            'ref_type_id'=>$consumer['id'],
            'remarks_id' => 15,#Water Bill Collection
            'other_remarks' =>null,
            'module_id' => 3,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
            'transaction_id' =>$tranId??null,
        ];
         
    }
} 

if(!function_exists("waterConnApplyVisit"))
{
    function waterConnApplyVisit(array $application,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);

        return $vistingRepostInput = [
            'ref_no'=>$application['application_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => 19,#Apply For Water New Connection
            'other_remarks' =>null,
            'module_id' => 3,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s')
        ];
         
    }
}

#=======end Water============
if(!function_exists("tradeConnApplyVisit"))
{
    function tradeConnApplyVisit(array $application,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 27 ; #other
        if($application['application_type_id']==1)
        {
            $remaks_id = 21;
        }
        if($application['application_type_id']==2)
        {
            $remaks_id = 22;
        }
        if($application['application_type_id']==4)
        {
            $remaks_id = 24;
        }
        if($application['application_type_id']==4)
        {
            $remaks_id = 24;
        }

        return $vistingRepostInput = [
            'ref_no'=>$application['application_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 4,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s')
        ];
         
    }
}

if(!function_exists("tradeNoticApplyVisit"))
{
    function tradeNoticApplyVisit(array $application,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 23 ; #Deniel Apply
        

        return $vistingRepostInput = [
            'ref_no'=>$application['notice_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,
            'other_remarks' =>null,
            'module_id' => 4,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s')
        ];
         
    }
}

if(!function_exists("tradeTranVisit"))
{
    function tradeTranVisit(array $application,$tranId,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 25 ; #Trade Collection
        

        return $vistingRepostInput = [
            'ref_no'=>$application['application_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 4,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
            'transaction_id' =>$tranId??null,
        ];
         
    }
}

#=====end trade=====

if(!function_exists("safAplyVisit"))
{
    function safAplyVisit(array $application,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 1 ; #SAF Apply
        

        return $vistingRepostInput = [
            'ref_no'=>$application['saf_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 1,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
        ];
         
    }
}

if(!function_exists("safTranVisit"))
{
    function safTranVisit(array $application,$tranId,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 2 ; #Payment Received
        

        return $vistingRepostInput = [
            'ref_no'=>$application['saf_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 1,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
            'transaction_id' =>$tranId??null,
        ];
         
    }
}

if(!function_exists("safGeoVisit"))
{
    function safGeoVisit(array $application,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 5 ; #Geo tag Done
        

        return $vistingRepostInput = [
            'ref_no'=>$application['saf_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 1,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
        ];
         
    }
}

#=====end Saf=====

if(!function_exists("propTranVisit"))
{
    function propTranVisit(array $application,$tranId,array $request=[])
    {
        $emp_mstr = session()->get("emp_details");
		$emp_details_id = $emp_mstr["id"]??0;

        $latLong = getlatLongIpAddress($request);
        $remaks_id = 7 ; #Payment Received
        

        return $vistingRepostInput = [
            'ref_no'=> trim($application['new_holding_no'])?$application['new_holding_no']:$application['holding_no'],
            'ref_type_id'=>$application['id'],
            'remarks_id' => $remaks_id,#Water Bill Generate
            'other_remarks' =>null,
            'module_id' => 2,
            "ip_address"=> $latLong["ip"],
            "address" => $latLong["address"],
            "latitude" => $latLong["latitude"],
            "longitude" => $latLong["longitude"],
            "emp_id" => $emp_details_id,
            'created_on' =>date('Y-m-d H:i:s'),
            'transaction_id' =>$tranId??null,
        ];
         
    }
}

#=====end Prop=====


if(!function_exists("levelRemarkTree")){

    function levelRemarkTree($id){
        $db_trade = db_connect(dbConfig("trade"));
        $sql = "with ints as(
                        SELECT 
                            tbl_level_pending.id, 
                            tbl_level_pending.apply_licence_id, 
                            case when tbl_level_pending.sender_user_type_id =0 then 11 else tbl_level_pending.sender_user_type_id end as sender_user_type_id,  
                            case when tbl_level_pending.forward_date is not null then tbl_level_pending.forward_date else tbl_level_pending.created_on::date end as forward_date , 
                            case when tbl_level_pending.forward_time is not null then tbl_level_pending.forward_time else tbl_level_pending.created_on::time end as forward_time, 
                            tbl_level_pending.created_on, 
                            tbl_level_pending.status, 
                            tbl_level_pending.remarks, 
                            tbl_level_pending.status,
                            tbl_level_pending.verification_status,
                            tbl_level_pending.emp_details_id, 
                            trim(REPLACE(case when tbl_level_pending.sender_user_type_id =0 then 'Back Office' else view_user_type_mstr.user_type end,'Trade','')) as user_type,
                            view_emp_details.emp_name,
                            ROW_NUMBER() over(order by tbl_level_pending.created_on ASC,tbl_level_pending.id ASC) as row_num  
                        FROM tbl_level_pending
                        left JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending.sender_user_type_id
                        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending.emp_details_id
                        where 1=1
                        AND ".(is_numeric($id) ? "tbl_level_pending.apply_licence_id = $id ":"md5(tbl_level_pending.apply_licence_id::text) ='$id'")."
                        ORDER BY tbl_level_pending.created_on ASC,tbl_level_pending.id ASC
                )
                select ints.*,
                    case when ints.row_num=1 then ints.created_on else concat(ints2.forward_date,' ',ints2.forward_time)::timestamp end as reiving_on
                from ints
                left join ints as ints2 on ints2.row_num = ints.row_num-1       
        ";
        $level = $db_trade->query($sql)->getResultArray();
    
        $tree = '
            <div class="panel panel-bordered panel-dark">
                    <div data-toggle="collapse" data-target="#demo" role="type">
                        <div class="panel-heading">
                            <h3 class="panel-title">Level Remarks
                            </h3>
                        </div>
                    </div>
    
                    <div class="panel-body collapse" id="demo">
                        <div class="nano has-scrollbar" style="height: 60vh">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <div class="panel-body chat-body media-block">
                                    
        ';
        $i = 0;
        foreach ($level as $row) {
            ++$i;
            $tree.='<div class="chat-'.(($i % 2 == 0) ? "user" : "user").'">
                    <div class="media-left">
                        <img src="'.base_url("public/assets/img/").'/'.$row["user_type"].'.png" class="img-circle img-sm" alt="'.$row["user_type"].'" title="'.$row["user_type"].'" loading="lazy" />
                        <br /> '.$row["emp_name"].'
                    </div>
                    <div class="media-body">
                        <div>
                            <p>'.(!empty($row["remarks"])?$row["remarks"]:'NA').'
                                <small> <b>Receiving Date</b> '. date("g:iA", strtotime($row["reiving_on"])) ." ". date("d M, Y", strtotime($row["reiving_on"])).'</small>
                                <small> <b>Forward Date</b> '. date("g:iA", strtotime($row["forward_time"])) ." ". date("d M, Y", strtotime($row["forward_date"])).'</small>
                            </p>
                        </div>
                    </div>
                </div>
            ';
        }
        $tree.='
                                </div>
                            </div>
                            <div class="nano-pane">
                                <div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
    
                    </div>
                </div>
        ';
        echo($tree);
    }
}

if(!function_exists("propLevelRemarkTree")){
    function propLevelRemarkTree($id){
        $db_prop = db_connect(dbConfig("property"));
        if(is_numeric($id))
        {
            $id = md5($id);
        }
        $sql ="SELECT 
            tbl_level_pending_dtl.id, 
            tbl_level_pending_dtl.saf_dtl_id, 
            tbl_level_pending_dtl.sender_user_type_id,  
            tbl_level_pending_dtl.forward_date, 
            tbl_level_pending_dtl.forward_time, 
            tbl_level_pending_dtl.created_on, 
            tbl_level_pending_dtl.status, 
            tbl_level_pending_dtl.remarks, 
            tbl_level_pending_dtl.status,
            tbl_level_pending_dtl.verification_status,
            tbl_level_pending_dtl.sender_emp_details_id	, 
            view_user_type_mstr.user_type, view_emp_details.emp_name
            FROM (
                SELECT
                tbl_bugfix_level_pending_dtl.id,
                tbl_bugfix_level_pending_dtl.saf_dtl_id, 
                tbl_bugfix_level_pending_dtl.sender_user_type_id, 
                tbl_bugfix_level_pending_dtl.forward_date, 
                tbl_bugfix_level_pending_dtl.forward_time, 
                tbl_bugfix_level_pending_dtl.created_on, 
                tbl_bugfix_level_pending_dtl.status, 
                tbl_bugfix_level_pending_dtl.remarks, 
                tbl_bugfix_level_pending_dtl.verification_status,
                tbl_bugfix_level_pending_dtl.sender_emp_details_id
            FROM tbl_bugfix_level_pending_dtl WHERE md5(saf_dtl_id::text) = '".$id."'
        )  AS tbl_level_pending_dtl
        JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending_dtl.sender_emp_details_id
        ORDER BY forward_date ASC, tbl_level_pending_dtl.created_on ASC";

        $level = $db_prop->query($sql)->getResultArray();

        $tree = '
            <div class="panel panel-bordered panel-dark">
                    <div data-toggle="collapse" data-target="#demo" role="type">
                        <div class="panel-heading">
                            <h3 class="panel-title">Level Remarks
                            </h3>
                        </div>
                    </div>
    
                    <div class="panel-body collapse" id="demo">
                        <div class="nano has-scrollbar" style="height: 60vh">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <div class="panel-body chat-body media-block">
                                    
        ';
        $i = 0;
        foreach ($level as $row) {
            ++$i;
            $tree.='<div class="chat-'.(($i % 2 == 0) ? "user" : "user").'">
                    <div class="media-left">
                        <img src="'.base_url("public/assets/img/").'/'.$row["user_type"].'.png" class="img-circle img-sm" alt="'.$row["user_type"].'" title="'.$row["user_type"].'" loading="lazy" />
                        <br /> '.$row["emp_name"].'
                    </div>
                    <div class="media-body">
                        <div>
                            <p>'.(!empty($row["remarks"])?$row["remarks"]:'NA').'
                                <small> <b>Receiving Date</b> '. date("g:iA", strtotime($row["reiving_on"])) ." ". date("d M, Y", strtotime($row["reiving_on"])).'</small>
                                <small> <b>Forward Date</b> '. date("g:iA", strtotime($row["forward_time"])) ." ". date("d M, Y", strtotime($row["forward_date"])).'</small>
                            </p>
                        </div>
                    </div>
                </div>
            ';
        }
        $tree.='
                                </div>
                            </div>
                            <div class="nano-pane">
                                <div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
    
                    </div>
                </div>
        ';
        echo($tree);
    }
}


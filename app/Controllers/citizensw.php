<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\Citizensw_water_model;
use App\Models\Citizensw_trade_model;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\WaterRateChartModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class Citizensw extends BaseController
{
    use ResponseTrait;
    protected $db;
    protected $dbSystem;
    protected $db_property;
    protected $db_watername;
    protected $db_tradename;
    protected $ulb;
    protected $ulb_mstr_id;

    #-------------
    protected $Citizensw_water_model;
    protected $Citizensw_trade_model;
    protected $tradeapplicationtypemstrmodel;
    protected $TradeApplyLicenceModel;    

    public function __construct()
    {
        $session = session();
        //parent::__construct();
        helper(['db_helper']);
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }

    function __destruct()
    {
        $this->dbSystem->close();
        if(isset($this->db_property))
            $this->db_property->close();
        if(isset($this->db_watername))
            $this->db_watername->close();
        if(isset( $this->db_tradename))
            $this->db_tradename->close();
    }

    public function index($url = null)
    {
        $encryption_iv = 'SingLe2021#@Wind';
        $encryption_key = "Nepal20House21&]";
        foreach ($_POST as $key => $value)
            $postData[$key] = Swsenc::decrypt($value, $encryption_key, $encryption_iv);

        $inputs = arrFilterSanitizeString($this->request->getVar());
        // print_var($inputs);
        // print_var($postData);
        // die;

        


        if (isset($_POST) && !empty($_POST)) 
        {
            // echo '<pre>';
            // print_r($_POST);print_r($postData);exit;
            $_SESSION['departmentId'] = $postData['departmentId'];
            $_SESSION['ulbId'] = $_POST['ulbId'];
            $_SESSION['serviceId'] = $_POST['serviceId'];
            $_SESSION['application_no'] = $_POST['application_no'];
            $_SESSION['consumer_no'] = isset($_POST['consumer_no']) ? $_POST['consumer_no'] : NULL;
            $_SESSION['license_no'] = isset($_POST['license_no']) ? $_POST['license_no'] : NULL;
            $_SESSION['custId'] = $postData['custId'];
            $_SESSION['caf_unique_no'] = $_POST['caf_unique_no'];
            $_SESSION['post_decript'] = $postData;
            $_SESSION['post_incrept'] = $_POST;

            if ($_SESSION['ulbId'] != "36") 
            {
                echo "Invalid ULBID";
                die();
            }            

            if ($_SESSION['ulbId'] == 36) 
            {
                $data = ['ulb_mstr_id' => 1];
                $serr1 = $this->model_ulb_mstr->getDbDetailsById($data);
                //print_var($serr1);
                //return redirect(base_url("Citizen/index/").hashEncrypt(md5(1)));
            }
           
            if (!empty($serr1))
            {
                $_SESSION["municipal_area_name"] = $serr1["ulb_name"];
                $_SESSION["municipal_area_id"] = $serr1["ulb_mstr_id"];                
                $_SESSION['municipal_id'] = $serr1["ulb_type_id"];
                $_SESSION['ulb_master_id'] = $serr1["ulb_mstr_id"];

                $session = session();
                $session->set('ulb_dtl', $serr1);                
                $this->db_connetion();                              
            }

            if (isset($_SESSION['serviceId'])) 
            {
                if ($_SESSION["serviceId"] == "32") 
                {        //Trade New License;  
                    $this->trade_service();
                    die('die1');                     
                } 
                else if ($_SESSION["serviceId"] == "581") 
                {        //Trade License (Renewal );
                    $this->trade_service();
                    die('die2');                      
                } 
                else if ($_SESSION["serviceId"] == "584") 
                {        //Trade License (Amendment );
                    $this->trade_service();
                    die('die3');  
                    
                } 
                else if ($_SESSION["serviceId"] == "585") 
                {        //Trade License (Surrender );
                    $this->trade_service();
                    die('die4'); 
                    
                }


                # ================water service========================= 
                #//New Water Connection; 
                else if ($_SESSION["serviceId"] == "211") 
                {
                    $this->sws_water();                                      
                    exit();
                }
                #//Regularization of Water Connection;
                else if ($_SESSION["serviceId"] == "582") 
                {                    
                    $this->sws_water();
                    exit();
                }
                #//Water Charges & Payment;
                else if ($_SESSION["serviceId"] == "617") 
                {                    
                    $this->consumer_demand_payment();                    
                    exit();
                } 
                else 
                {
                    die("Invalid ServiceId !");
                }
            }
        } 
        else 
        {
            echo "Invalid Request !";
            die();
        }
    }

    public function db_connetion()
    {

        $session = session();
        $ulb_details = $session->get('ulb_dtl');
        $this->ulb = $ulb_details;
        $this->ulb_id = $ulb_details['ulb_mstr_id'];
        helper(['form', 'db_helper']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $db_property = $ulb_dtl['property'];
        $db_water = $ulb_dtl['water'];
        $db_trade = $ulb_dtl['trade'];
        $this->db_property = db_connect($db_property);
        $this->db_watername = db_connect($db_water);
        $this->db_tradename = db_connect($db_trade);        
        $this->Citizensw_water_model = new Citizensw_water_model($this->db_watername);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db_tradename);        
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db_tradename);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db_tradename);
    }
    #================= Water services ========================
    public function sws_water()
    {
        unset($_SESSION['applynewconnection']);
        if (isset($_SESSION['custId'])) 
        {
            $_SESSION['apply_from'] = 'sws';
        }        
        $consdtl = "select C.* from tbl_apply_water_connection C join tbl_single_window_apply SW on SW.apply_connection_id=C.id  
                        where SW.caf_no='" . $_SESSION["caf_unique_no"] . "' and SW.service_id='" . $_SESSION['serviceId'] . "' order by C.id desc";
         
        $ftvhdata =$this->Citizensw_water_model->row_sql($consdtl);
        $totaldta = sizeof($ftvhdata);        
        if ($totaldta > 0) 
        {           
            $condtlrow = $ftvhdata[0];
            $_SESSION["application_no"] = $condtlrow["application_no"];
            echo '<script> window.location = "'.base_url()."/WaterApplyNewConnectionCitizenSW/water_connection_view/".md5($condtlrow['id']).'"; </script>'; 
            
        } 
        else
        {
            if (in_array($_SESSION['serviceId'],["211","582"]) && (isset($_SESSION['application_no']) && !empty($_SESSION['application_no']))) // "211" new connection and // "582" Regularization of Water Connection;
            {
                $consdtl = "select * from tbl_apply_water_connection where application_no='" . $_SESSION["application_no"]. "' and status = 1 order by id desc limit 1";
                $ftvhdata = $this->Citizensw_water_model->row_sql($consdtl);                
                $totaldta = sizeof($ftvhdata);                
                $_SESSION['conType'] = 1;
                $_SESSION["is_regu"] = 0;
                $_SESSION["para"] = 1;
                if ($totaldta > 0) 
                {
                    $app_no = $ftvhdata[0];                                        
                    $_SESSION["application_no"] = $app_no["application_no"];
                    echo '<script> window.location = "'.base_url()."/WaterApplyNewConnectionCitizenSW/water_connection_view/".md5($app_no['id']).'"; </script>';
                    
                } 
                else 
                {
                    echo '<script> window.location = "'.base_url()."/WaterApplyNewConnectionCitizenSW/index".'"; </script>';                   
                                                       
                }
            }             
            else 
            {
                echo '<script> window.location = "'.base_url()."/WaterApplyNewConnectionCitizenSW/index".'"; </script>';
                die();
            }
        }
    }

    public function consumer_demand_payment()
    {
        if(isset($_SESSION['consumer_no']))
        {            
            $consno=pg_query($_SESSION["db_water"],"select * from view_consumer 
            where consumer_no='".$_SESSION['consumer_no']."' and status = 1 order by id desc ");
            $data = $this->Citizensw_water_model->row_sql($consno);
            $tota_rec = sizeof($data);            
            if($tota_rec>0)
            { 
                // $conlicno=pg_fetch_array($consno);
                $conlicno=$data[0];
                $_SESSION['app_id']=$conlicno['id'];
                $_SESSION['apply_from']=$conlicno['apply_from'];
                echo '<script> window.location = "'.base_url()."/WaterUserChargeProceedPaymentCitizen/pay_payment/".md5($conlicno['id']).'"; </script>'; 
            }
            else
            {
                echo "Invalid Consumer No or ULB Id !";
                unset($_SESSION['custId']);
                unset($_SESSION['ulbId']);
                unset($_SESSION['apply_from']);
                die();
            }
        }
    }
    #================ End Water services ======================

    #================ Trade services ==========================
    public function trade_service ()
    {        
        unset($_SESSION['municipal_lic']);
		
        if(isset($_SESSION['custId']))
        {
            $_SESSION['apply_from']='sws'; 
        }
        
        
        $consdtl=" select C.* 
                   from tbl_apply_licence C 
                   join tbl_single_window_apply SW on SW.apply_license_id = C.id  
                   where SW.caf_no='".$_SESSION["caf_unique_no"]."' "
                   .(!in_array($_SESSION['serviceId'],["581","584","585"]) ? (" and SW.service_id='".$_SESSION['serviceId']."'"):(" and SW.caf_no='".$_SESSION['caf_unique_no']."'") )
                   ." order by C.id desc";
        
        $ftvhdata=$this->Citizensw_trade_model->row_sql($consdtl);        
        $totaldta = sizeof($ftvhdata);  
        // print_var($ftvhdata);die;
        #new license        
        if($_SESSION['serviceId']=="32")
        {  
            if($totaldta>0)
            {
                $condtlrow=$ftvhdata[0];               
                $sql_show="select *  from tbl_apply_licence where id='".$condtlrow['id']."' ORDER BY id desc LIMIT 1 ";
                $sql_res=$this->Citizensw_trade_model->row_sql($sql_show);               
                $row=sizeof($sql_res); 
                if($row>0)
                {                    
                    $rows=$sql_res[0]; 
                    $status=$rows["status"];
                    $document_status=$rows["document_upload_status"];                       
                    $_SESSION['app_id']=$rows['id'];
                    $_SESSION['apply_from']=$rows['apply_from'];
                    if(in_array($rows['payment_status'],[0]))
                    {                        
                        echo '<script> window.location = "'.base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($rows['id'])).'"; </script>'; 
                    }
                    else
                    {
                        echo '<script> window.location = "'.base_url()."/TradeCitizen/trade_licence_view/".md5($rows['id']).'"; </script>'; 
                    }
                }
                else
                {
                    $_SESSION['apply_from']='sws';
                    echo '<script> window.location = "'.base_url()."/TradeCitizen/applynewlicence/".md5(1).'"; </script>'; 
                }
            }
            else
            {
                $sql = "select * from tbl_apply_license where application_no='".$_SESSION['application_no']."' ";
                $application = $this->Citizensw_trade_model->row_sql($sql);
                $_SESSION['apply_from']='sws';                
                if(sizeof($application)>0)
                {    
                    if(in_array($application[0]['payment_status'],[0]))
                    {
                        //return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($applyid)));
                        echo '<script> window.location = "'.base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($application[0]['id'])).'"; </script>'; 
                    }
                    else
                    {
                       
                        echo '<script> window.location = "'.base_url()."/TradeCitizen/trade_licence_view/".md5($application[0]['id']).'"; </script>'; 
                    }                
                }
                else
                {
                    // print_var($_SESSION);
                    // die('new');
                    echo '<script> window.location = "'.base_url()."/TradeCitizenSW/applynewlicence/".md5(1).'"; </script>'; 
                    die;
                }
                
                //header("location:municipal_lic.php"); 
            }                    
                    
        }
        # "581" renew license, "584" amendment license , "585" surender license
        elseif(in_array($_SESSION['serviceId'],["581","584","585"]))
        {
            $application_type = null;
            if($_SESSION['serviceId']=="581")
            {
                $application_type = md5(2);
            }
            elseif($_SESSION['serviceId']=="584")
            {
                $application_type = md5(3);
            }
            elseif($_SESSION['serviceId']=="585")
            {
                $application_type = md5(4);
            }
            if($totaldta>0)
            {
                $condtlrow = $ftvhdata[0];
                $sql_show="select *  from tbl_apply_licence where id='".$condtlrow['id']."' ORDER BY id desc LIMIT 1 ";
                $sql_res=$this->Citizensw_trade_model ->row_sql($sql_show);
                if(sizeof($sql_res)>0)
                {
                    $data = $this->searchLicense($sql_res[0]['license_no'],$application_type);
                    $data = json_decode($data,true);
                    // print_var($data);
                    // die;
                    flashToast("message", $data['msg']);
                    if(isset($data['url']) && !empty($data['url']))
                    {
                        if(in_array($sql_res[0]['payment_status'],[0]))
                        {
                            echo '<script> window.location = "'.base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($sql_res[0]['id'])).'"; </script>'; 
                        }
                        else
                        {
                            echo '<script> window.location = "'.$data['url'].'"; </script>';
                        }
                         
                    }
                    else
                    {
                        echo $data['msg'];
                    }                    
                }
                else
                {                    
                    die('no data found');                   
                }
            }
            else
            {
                if(isset($_SESSION['license_no']) && empty($_SESSION['license_no']))
                {
                    $data = $this->searchLicense($_SESSION['license_no'],$application_type);
                    $data = json_decode($data,true);
                    $_SESSION['apply_from']='sws';
                    flashToast("message", $data['msg']);
                    if(isset($data['url']) && !empty($data['url']))
                    {
                        echo '<script> window.location = "'.$data['url'].'"; </script>';                       
                    }
                    else
                    {
                        echo $data['msg'];
                    }
                }
                else
                {
                    die('no data found Please Provide License No');
                }
            }
        }
        else
        {
            echo "Invalid Details !";
            die;
        }

        
    }


    public function searchLicense($license_no,$apptypeid = null)
    {
        $data = (array)null;
        $data["msg"] = '';
        $data['respons']= false;
        $dat['url'] = '';
        if ($apptypeid <> null) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);           
            if ($this->request->getMethod() == "post") 
            {
                
                {
                    $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                    $data["Searchlicense"] = $license_no;
                    if ($data["application_type"]["id"] == 3) 
                    {
                        // if applying for amendment, then search surrendered license
                        $application_type_id = 4; //surrender
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedataSurrendered($data["Searchlicense"], $application_type_id);
                    } 
                    else 
                    {
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedata($data["Searchlicense"]);
                    }
                    //print_var($licensedata);die;
                    if (isset($licensedata, $licensedata)) 
                    {
                        $id = md5($licensedata["id"]);
                        if ( $licensedata["update_status"] == 0 && $licensedata["pending_status"] != 5) 
                        {
                            //(is_null($licensedata["valid_upto"]) and
                            $data['respons']= false;
                            $data["msg"] = "Already Applied! Please Track Status of Application No." . $licensedata["application_no"];
                            $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . $id );
                        } 
                        else 
                        {                            
                            if ($data["application_type"]["id"] <> 4) 
                            { 
                                if (date('Y-m-d', strtotime($licensedata["valid_upto"] . ' - 30 days')) > date('Y-m-d') && $data["application_type"]["id"] != 3) 
                                { 
                                    $data["msg"] = "License Not Expired! This Licence Is Valid Upto " . $licensedata["valid_upto"];
                                    $data['respons']=false;
                                    $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . $id );
                                } 
                                else 
                                {
                                    if ($data["application_type"]["id"] == 3) 
                                    {
                                        if ($licensedata["application_type_id"] == 4 && $licensedata["pending_status"] == 5) 
                                        {
                                            //return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                                            $data['respons']=true;
                                            $data['url'] = base_url('TradeCitizenSW/applynewlicence/' . $apptypeid . '/' . $id);
                                        } 
                                        else 
                                        {
                                            //print_var($licensedata);
                                            $data['respons']=false;
                                            $data["msg"] = "Application No. $licensedata[application_no] is applied for surrender against License No. $licensedata[license_no] which is not approved yet.";
                                            $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . $id );
                                        }
                                    } 
                                    else 
                                    {
                                        // return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                                        $data['respons']=true;
                                        $data['url']=base_url('TradeCitizenSW/applynewlicence/' . $apptypeid . '/' . $id);
                                    }
                                }
                            } 
                            else 
                            {
                                if (is_null($licensedata["valid_upto"]) && $licensedata["update_status"] && $licensedata["pending_status"] != 5) 
                                {
                                    $data['respons']=false;
                                    $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . $id );
                                    $data["msg"] = "Already Applied! Please Track Status of Application No." . $licensedata["application_no"];
                                } 
                                else if ($licensedata['valid_upto'] <= date('Y-m-d')) 
                                {
                                    $data['respons']=false;
                                    $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . $id );
                                    $data["msg"] = "License No. $licensedata[license_no] is valid till $licensedata[valid_upto], which has expired. Therefore, please apply for renewal before surrender.";
                                } 
                                else 
                                {
                                    $data['respons']=true;
                                    $data['url']=base_url('TradeCitizenSW/applynewlicence/' . $apptypeid . '/' . $id);
                                    //return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                                }
                            }
                        }
                    } 
                    else 
                    {                        
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedata($data["Searchlicense"]);
                        if ($licensedata && $data["application_type"]["id"] == 3) 
                        {
                            $data["msg"] = "Please apply for surrender before amendment.";
                            $data['respons']=false;
                            $data['url'] =  base_url('TradeCitizen/trade_licence_view/' . md5($licensedata["id"]) );
                        } 
                        else
                        {
                            $data["msg"] = "License Not found.";
                            $data['respons']=false;
                        }
                            
                    }
                }
            }  
            return json_encode($data);          
            // return view('trade/Connection/SearchLicense', $data);
        }

        return json_encode($data);
    }

    #================ End Trade services ======================
    #==========================================================
    public function citizenSWM($url = null)
    {
        try
        {

            $ulb_details=getUlbDtl();
            session()->set('ulb_dtl',$ulb_details);
            $this->db_connetion();   
            $postData = $_POST;           
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $session = session();
            $session->set("swtest", "TEST FRO SONGLE WINDOWS");
            $_SESSION['departmentId'] = $postData['sws_reference_no']??'';
            $_SESSION['ulbId'] = $ulb_details['ulb_mstr_id'];
            $_SESSION['serviceId'] = $_POST['service_id']??'';
            $_SESSION['application_no'] = $_POST['application_no']??'';
            $_SESSION['consumer_no'] = isset($_POST['consumer_no']) ? $_POST['consumer_no'] : NULL;
            $_SESSION['license_no'] = isset($_POST['license_no']) ? $_POST['license_no'] : NULL;
            $_SESSION['custId'] = $postData['custId']??'';
            $_SESSION['caf_unique_no'] = $_POST['caf_no']??'';
            $_SESSION['post_decript'] = $postData;
            $_SESSION['post_incrept'] = $postData;  
            $_SESSION['apply_from'] = 'swsc';
            $data = $this->searWaterApplication($postData);
            return $this->response->redirect($data['url']);
            
        }
        catch (Exception $e)
        {
            echo $e->getMessage();  
        }
    
    }

    public function searWaterApplication($postData)
    {
        $sql=""; 
        //print_var($postData);die;
        $respons=['status'=>true,"url"=>""];
        if(isset($postData['caf_no']) && isset($postData['sws_reference_no']) && isset($postData['service_id']))
        {
            $sql = "select C.* 
                        from tbl_apply_water_connection C 
                        join tbl_single_window_apply SW on SW.apply_connection_id=C.id  
                        where SW.caf_no='" .$postData['caf_no']. "' 
                            and SW.service_id='" .$postData['service_id']. "' 
                            and SW.department_id = '".$postData['sws_reference_no']."'
                        order by C.id desc";
        }
        elseif(isset($postData['sws_reference_no']))
        {
            $sql = "select C.* 
                        from tbl_apply_water_connection C 
                        join tbl_single_window_apply SW on SW.apply_connection_id=C.id  
                        where SW.department_id = '".$postData['sws_reference_no']."'
                        order by C.id desc";
        }

        if(!$sql)
        {
            $respons['url'] = base_url("Citizensw/index");
        }
        else
        {
            $ftvhdata =$this->Citizensw_water_model->row_sql($sql);
            $totaldta = sizeof($ftvhdata);             
            if ($totaldta > 0) 
            {           
                $condtlrow = $ftvhdata[0];
                $_SESSION["application_no"] = $condtlrow["application_no"];
                $respons['url'] = base_url("WaterApplyNewConnectionCitizenSW/water_connection_view/".md5($condtlrow['id']));
            }
            else
            {
                $respons['url'] = base_url("WaterApplyNewConnectionCitizenSW/index");
            }
        }
        return $respons;

    }
    #==========================================================
    #================ Water Rate Chart for Single Windows======
    public function Water_Rate_SW()
    {
        try{
            $inputs = $this->request->getJSON();            
            // if($inputs->ulbId==36)
            {
                $db_name = dbConfig("water");
                $rate_chart_model = new WaterRateChartModel(db_connect($db_name));
                $data['Fixed']=$rate_chart_model->SingleWindowgetFixdeRates();
                $Metered=$rate_chart_model->SingleWindowgetMeterRates();
                foreach($Metered as $key => $val)
                {
                    if($val["category"]=="APL" && $val["property_type"]=="Residential")
                    {
                        $data["Metered"][]=[
                            "category"      =>null,
                            "range_from"    =>$val["range_from"],
                            "range_to"      =>$val["range_to"],
                            "rate"          =>$val["rate"],
                            "effective_date"=>$val["effective_date"],
                            "property_type"=>"Apartment/Multi Stored Unit",

                        ];
                    }
                    if($val["property_type"]=="Institutional")
                    {
                        $data["Metered"][]=[
                            "category"      =>null,
                            "range_from"    =>$val["range_from"],
                            "range_to"      =>$val["range_to"],
                            "rate"          =>$val["rate"],
                            "effective_date"=>$val["effective_date"],
                            "property_type"=>"Trust & NGO",

                        ];
                    }
                    $data["Metered"][]=[
                        "category"      =>$val["category"],
                        "range_from"    =>$val["range_from"],
                        "range_to"      =>$val["range_to"],
                        "rate"          =>$val["rate"],
                        "effective_date"=>$val["effective_date"],
                        "property_type"=>$val["property_type"],

                    ];
                }
                $data["Old"]=$rate_chart_model->SingleWindowConnectionFeeOld();
                $data["New"] = $rate_chart_model->SingleWindowConnectionFeeNew();
                foreach($data["New"] as $key => $val)
                {
                    $data["New"][$key]["category"] = "N/A";
                    if($val["property_type"]=="Residential")
                    {
                        $data["New"][$key]["category"] = "APL";                         
                    }
                }
                if($data["New"])
                {
                    $data["New"][]=[
                        "category"      =>"BPL",
                        "area_from_sqft"    =>null,
                        "area_upto_sqft"      =>null,
                        "conn_fee"          =>0.00,
                        "calculation_type" => "Fixed",
                        "effective_date"=>"2021-01-01",
                        "property_type"=>"Residential",

                    ];
                }
                $responseData = $inputs;
                // if($inputs->ulbId??false)
                // {
                //     return $this->respond(($data), 200);
                // }
                $session=session();
                $get_ulb_detail=getUlbDtl();
                $get_emp_details=$session->get('emp_details');
                $data["user_type"]=$get_emp_details['user_type_mstr_id']??null;  
                return view('water/water_connection/RateChart', $data);

            }
        }
        catch(Exception $e)
        {
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
        
    }

    #================End Hear==================================

}

class Swsenc
{
    static $ciphering = "AES-128-CTR";
    static $options = 0;
    public static function encrypt($data, $encryption_key, $encryption_iv)
    {
        $encryption = openssl_encrypt($data, Swsenc::$ciphering, $encryption_key, Swsenc::$options, $encryption_iv);
        return $encryption;
    }

    public static function decrypt($encrypted_data, $encryption_key, $encryption_iv)
    {
        $decryption = openssl_decrypt($encrypted_data, Swsenc::$ciphering, $encryption_key, Swsenc::$options, $encryption_iv);
        return $decryption;
    }
}

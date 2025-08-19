<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use Exception;
use Predis\Client;
use Config\Services;

use App\Models\model_prop_demand;
use App\Models\model_third_party_pay_request;
use App\Models\model_third_party_pay_response;
use App\Models\model_transaction;

use App\Models\model_view_water_consumer;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterPenaltyModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\ModelThirdPartyOnlineRequest;
use App\Models\WaterPaymentModel;
use App\Models\Water_Transaction_Model;

use App\Models\TradeApplyLicenceModel;
use App\Models\TradeItemsMstrModel;
use App\Models\TradeCategoryTypeModel;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeFirmOwnerModel;

use App\Models\ModelThirdPartyTradeOnlineRequest;
use App\Models\ModelThirdPartyTradeOnlineResponse;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeTransactionModel;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\Citizensw_trade_model;
use App\Models\model_trade_sms_log;
use App\Models\model_ulb_mstr;

class AmurtMisPortal extends AlphaController
{

    use ResponseTrait;
    protected $db_system;
    protected $db_property;
    protected $model_fy_mstr;
    protected $model_ward_mstr;
    protected $username;
    protected $password;
    protected $encrypter ;

    protected $modeldemand;
    protected $model_third_party_pay_request;
    protected $model_transaction;
    protected $model_third_party_pay_response;

    protected $model_view_water_consumer;
    protected $consumer_details_model;
    protected $consumer_demand_model;
    protected $WaterPenaltyModel;
    protected $apply_waterconn_model;
    protected $ModelThirdPartyOnlineRequest;

    protected $WaterUserChargeProceedPaymentCitizeController;
    protected $payment_model;
    protected $transaction_model;


    protected $TradeApplyLicenceModel;
    protected $tradeitemsmstrmodel;
    protected $TradeCategoryTypeModel;
    protected $tradefirmtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $TradeFirmOwnerModel;
    protected $TradeCitizenController;
    protected $model_third_party_trade_online_payment;
    protected $model_third_party_trade_response;
    protected $trade_application_type_mstr_model;
    protected $TradeTransactionModel;
    protected $model_trade_transaction_fine_rebet_details;
    protected $Citizensw_trade_model;
    protected $model_trade_sms_log;
    protected $modelUlb;


    


    

    public function __construct()
	{
        set_time_limit(70000);
        helper(['db_helper', 'utility_helper',"form_helper", "ccavanue_helper"]);
        $_REQUEST["api"] = true;
        $this->username = "amurt_mis";
        $this->password = "spsmis@12587#amurt";
        $this->encrypter =  \Config\Services::encrypter();
        
    }

    public function propDBConn() {
        $this->db_property = db_connect(dbConfig("property"));
        $this->db_system = db_connect(dbConfig("system"));
        $this->model_fy_mstr = new model_fy_mstr($this->db_system);
        $this->model_ward_mstr = new model_ward_mstr($this->db_system);
        $this->modeldemand = new model_prop_demand($this->db_property);
        $this->model_third_party_pay_request = new model_third_party_pay_request($this->db_property);
        $this->model_third_party_pay_response = new model_third_party_pay_response($this->db_property);
        $this->model_transaction = new model_transaction($this->db_property);
        
    }

    public function connectWaterDBConn(){
        $this->db = db_connect(dbConfig("water"));
        $this->db_system = db_connect(dbConfig("system"));
        $this->model_ward_mstr = new model_ward_mstr($this->db_system);

        $this->model_view_water_consumer = new model_view_water_consumer($this->db);
        $this->consumer_details_model = new water_consumer_details_model($this->db);
        $this->consumer_demand_model = new WaterConsumerDemandModel($this->db);
        $this->WaterPenaltyModel = new WaterPenaltyModel($this->db);
        $this->apply_waterconn_model = new WaterApplyNewConnectionModel($this->db);
        $this->ModelThirdPartyOnlineRequest = new ModelThirdPartyOnlineRequest($this->db);
        $this->model_third_party_pay_response = new model_third_party_pay_response($this->db);

        $this->WaterUserChargeProceedPaymentCitizeController = new WaterUserChargeProceedPaymentCitizen();
        $this->payment_model = new WaterPaymentModel($this->db);
        $this->transaction_model = new Water_Transaction_Model($this->db);
    }


    public function connectTradeDBConn(){
        $this->db = db_connect(dbConfig("trade"));
        $this->db_system = db_connect(dbConfig("system"));
        $this->model_ward_mstr = new model_ward_mstr($this->db_system);

        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->tradeitemsmstrmodel = new TradeItemsMstrModel($this->db);
        $this->TradeCategoryTypeModel = new TradeCategoryTypeModel($this->db);
        $this->tradefirmtypemstrmodel = new TradeFirmTypeMstrModel($this->db);
        $this->tradeownershiptypemstrmodel = new TradeOwnershipTypeMstrModel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeCitizenController = new TradeCitizen();
        $this->model_third_party_trade_online_payment = new ModelThirdPartyTradeOnlineRequest($this->db);
        $this->model_third_party_trade_response = new ModelThirdPartyTradeOnlineResponse($this->db);
        $this->trade_application_type_mstr_model = new TradeApplicationTypeMstrModel($this->db);
        $this->TradeTransactionModel= new TradeTransactionModel($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db);
        $this->model_trade_sms_log=new model_trade_sms_log($this->db);
        $this->modelUlb = new model_ulb_mstr($this->db_system);
    }

    public function index()
    {
        $inputs = $this->request->getJSON();
        
        if($this->request->getMethod(true) != 'POST')
        {
            $errors = $this->request->getMethod(true). " methode not allowed";
            return $this->fail($errors, 400);
        }
   
        if (!isset($inputs->userName) || trim($inputs->userName)!= $this->username || !isset($inputs->password) || trim($inputs->password)!= $this->password) 
        { 
            //username & password creadential matched
            $errors = "Invailid User or Password !!";
            return $this->fail($errors, 400);
        }
        else{
            return $this->dcbMisReport($inputs);
        }
    }
    

    public function dcbMisReport($inputs) {
        $this->propDBConn();
        $ulb_mstr_id = 1;
        $currentFY = getFY();
        
        try {
            $fYear = isset($inputs->fYear)?trim($inputs->fYear):"";
            $fYearId = $this->model_fy_mstr->getFyByFy(['fy' => $fYear])['id'];
            $fYearArr = explode("-", $fYear);
            if(count($fYearArr)!=2)
            {
                $errors = "Invailid financial year, please use like ".$currentFY;
                return $this->fail($errors, 400);
            }
            $fromDate = $fYearArr[0]."-04-01";
            $toDate = $fYearArr[1]."-03-31";
            
            $WhereFYCurrent = " AND tbl_saf_demand.fy_mstr_id=".$fYearId;
            
            $sql = "WITH  property AS(
                SELECT tbl_saf_dtl.id as saf_id FROM tbl_saf_dtl 
                JOIN (
                    SELECT geotag_dtl_id 
                    FROM tbl_saf_geotag_upload_dtl 
                    GROUP BY geotag_dtl_id
                ) AS geotagging ON geotagging.geotag_dtl_id=tbl_saf_dtl.id
                WHERE tbl_saf_dtl.created_on BETWEEN '".$fromDate."' AND '".$toDate."' 
                ),
                CURRENT_DEMAND AS (
                    select saf_dtl_id, sum(amount) as amount from tbl_saf_demand where tbl_saf_demand.status=1 
                    AND tbl_saf_demand.paid_status IN (0,1)   ".$WhereFYCurrent." 
                    group by saf_dtl_id
                ),
                CURRENT_COLLECTION AS (
                    select saf_dtl_id, sum(amount) as amount from tbl_saf_demand where tbl_saf_demand.status=1 
                    AND tbl_saf_demand.paid_status=1 AND tbl_saf_demand.status=1  ".$WhereFYCurrent." 
                    group by saf_dtl_id
                
                )
                SELECT COUNT(property.saf_id) AS prop_count, COUNT(demand.saf_dtl_id) AS current_holding, SUM(collection.amount) AS current_collection_amount, SUM(demand.amount) AS current_demand from property
                LEFT JOIN CURRENT_DEMAND as demand on property.saf_id=demand.saf_dtl_id
                LEFT JOIN CURRENT_COLLECTION as collection on property.saf_id=collection.saf_dtl_id";

            $builder = $this->db_property->query($sql);
            if ($report = $builder->getRowArray()) {
                $responseData [] = [
                    "ulbName" => "RANCHI MUNICIPAL CORPORATION",
                    "type" => "Property",
                    "financialYear" => $fYear, 
                    "noOfPropertyMapped" => $report['prop_count'],
                    "noOfPropertyBillRaised" => $report['current_holding'],
                    "taxRaised" => $report['current_demand'],
                    "collection" => $report['current_collection_amount'],
                    "status" => "1"
                ];
                $water = json_decode(json_decode($this->waterDcbMisReport($inputs)->getJSON()),true);
                $responseData[1]=$water;
                return $this->respond(($responseData), 200);
            }   

        }catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }

        //return view('property/reports/mis_dcb_report', $data);
    }

    public function waterDcbMisReport($inputs)
    {
        $this->db = db_connect(dbConfig("water"));
        $currentFY = getFY();
        try {
            $fYear = isset($inputs->fYear)?trim($inputs->fYear):"";
            $fYearArr = explode("-", $fYear);
            if(count($fYearArr)!=2)
            {
                $errors = "Invailid financial year, please use like ".$currentFY;
                return $this->fail($errors, 400);
            }
            $fromDate = $fYearArr[0]."-04-01";
            $toDate = $fYearArr[1]."-03-31";
            
            $sql = "WITH holding_wise_consumer AS (
                            SELECT tbl_consumer.id,
                                CASE
                                    WHEN tbl_consumer.holding_no::text = ''::text OR tbl_consumer.holding_no IS NULL THEN count(tbl_consumer.id)
                                    ELSE NULL::bigint
                                END AS non_holding_consumer,
                                CASE
                                    WHEN tbl_consumer.holding_no::text <> ''::text AND tbl_consumer.holding_no IS NOT NULL THEN count(tbl_consumer.id)
                                    ELSE NULL::bigint
                                END AS holding_consumer
                            FROM tbl_consumer
                            WHERE tbl_consumer.status = 1 and  tbl_consumer.created_on::date <='$toDate'::date 
                            AND tbl_consumer.holding_no::text <> ''::text AND tbl_consumer.holding_no IS NOT NULL
                            GROUP BY tbl_consumer.id 
                            ORDER BY tbl_consumer.id DESC
                    ),
                    demand as ( 
                        SELECT tbl_consumer_demand.consumer_id,
                            sum(tbl_consumer_demand.amount) as total_demand,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto < '$fromDate'::date 
                                    THEN tbl_consumer_demand.amount       
                                ELSE NULL::numeric
                                END
                            ) AS arrear_demand,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto between '$fromDate'::date AND '$toDate'::date 
                                    THEN tbl_consumer_demand.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_demand
                        FROM tbl_consumer_demand
                        JOIN holding_wise_consumer ON holding_wise_consumer.id = tbl_consumer_demand.consumer_id
                        WHERE tbl_consumer_demand.status = 1 
                            and  tbl_consumer_demand.generation_date::date between '$fromDate'::date AND '$toDate'::date
                        GROUP BY tbl_consumer_demand.consumer_id
                    ),
                    coll as ( 
                        SELECT tbl_consumer_collection.consumer_id,
                            sum(tbl_consumer_collection.amount) as total_collection,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto < '$fromDate'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS arrear_coll,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto between '$fromDate'::date AND '$toDate'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_coll
                        FROM tbl_consumer_collection
                        JOIN holding_wise_consumer ON holding_wise_consumer.id = tbl_consumer_collection.consumer_id
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                        WHERE tbl_transaction.transaction_date between '$fromDate'::date AND '$toDate'::date
                            AND tbl_transaction.status in(1,2)
                        GROUP BY tbl_consumer_collection.consumer_id
                    ),
                    prev_coll_amount as ( 
                            SELECT tbl_transaction.related_id,
                                sum(tbl_consumer_collection.amount) AS prev_coll
                            FROM tbl_consumer_collection
                            JOIN holding_wise_consumer ON holding_wise_consumer.id = tbl_consumer_collection.consumer_id
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                            WHERE tbl_transaction.transaction_date < '$fromDate'::date 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                AND tbl_transaction.status in(1,2)
                            GROUP BY tbl_transaction.related_id
                    ),
                    pay_user_charge as (
                        SELECT tbl_transaction.related_id
                            FROM tbl_consumer_collection
                            JOIN holding_wise_consumer ON holding_wise_consumer.id = tbl_consumer_collection.consumer_id
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                            WHERE tbl_transaction.transaction_date <= '$toDate'::date 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                AND tbl_transaction.status in(1,2)
                            GROUP BY tbl_transaction.related_id
                    )
                    SELECT
                        count(holding_wise_consumer.id) as total_consumer,
                        count(demand.consumer_id) as total_consumer_bill_raised,
                        count(pay_user_charge.related_id) as pay_user_charges,
                        sum(demand.total_demand) as total_demand,
                        sum(demand.arrear_demand) as arrear_demand,
                        sum(demand.curr_demand) as current_demand,
                        sum(coll.total_collection) as total_collection,
                        sum(coll.arrear_coll) as arrear_collection,
                        sum(coll.curr_coll) as curr_collection                        
                    FROM holding_wise_consumer
                    left JOIN demand ON demand.consumer_id = holding_wise_consumer.id
                    left JOIN coll ON coll.consumer_id = holding_wise_consumer.id
                    left join prev_coll_amount ON prev_coll_amount.related_id = holding_wise_consumer.id
                    left join pay_user_charge on pay_user_charge.related_id = holding_wise_consumer.id
                    ";

            $builder = $this->db->query($sql);
            if ($report = $builder->getRowArray()) {
                $responseData  = [
                    "ulbName" => "RANCHI MUNICIPAL CORPORATION",
                    "type" => "Water",
                    "financialYear" => $fYear, 
                    "householdsInState" => $report['total_consumer'],
                    "householdsPayingCharges"=>$report['pay_user_charges'],
                    "noOfBillRaised" => $report['total_consumer_bill_raised'],
                    "taxRaised" => $report['total_demand'],
                    "collection" => $report['total_collection'],
                    "status" => "1"
                ];
                return $this->respond(($responseData), 200);
            }

        }catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
    }

    #=====api for smarcity===============
    private function setSession(){
        $session = session();      
        $this->propDBConn();
        if(!isLogin()){
            $sql="select id, user_type_mstr_id,user_mstr_id, emp_name, personal_phone_no, photo_path, email_id from tbl_emp_details Order By id ASC LIMIT 1";
            $emp_details = $this->db_system->query($sql)->getResultArray()[0];
            $emp_details["token "]= date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
            $emp_details['ulb_list'] = [0=>[
                    "ulb_permission_id"=>1,
                    "ulb_mstr_id"=>1,
                    "ulb_name"=>"Ranchi Municipal Corporation",
                    "short_ulb_name"=>"RMC"
                ]
            ];
            $session->set('emp_details', $emp_details);
            $session->set('ulb_dtl', getUlbDtl());    			
    	}

    }


    public function smartCityPropertyApi(){
        try{
            // set_time_limit(700);
            $inputs = (array)$this->request->getJSON();
            $this->setSession();
            if($this->request->getMethod(true) != 'POST')
            {
                $errors = $this->request->getMethod(true). " methode not allowed";
                return $this->fail($errors, 400);
            }
            $currentFY = getFY();
            $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
            $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
            $ward_mstr_id = "";
            // array_merge($_REQUEST,$inputs);
            $obj = new \App\Controllers\prop_report();
            $obj->request = $this->request;
            foreach($inputs as $key=>$val){
                $_REQUEST[$key]=$val;
            }
            if(isset($inputs["fYear"])){
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$inputs["fYear"]])['id'];
            }
            if(isset($inputs["ward_no"])){
                $ward_mstr_id = $this->model_ward_mstr->select("*")->where("ulb_mstr_id",1)->where('ward_no',(string)$inputs["ward_no"])->get()->getFirstRow("array")['id']??"";
            }
            $_REQUEST["fy_mstr_id"] = $currentFyID;
            $_REQUEST["ward_mstr_id"] = $ward_mstr_id;
            $data = $obj->wardWiseDCB();
            $result["dcb"] =[];
            foreach($data["report_list"] as $key=>$list){
                array_push($result["dcb"],[
                    "ward_no"                                       =>$list["ward_no"],
                    "demand_from_holding"                           =>$list["current_holding"],
                    "arrear_demand"                                 =>round($list['arrear_demand'],2),
                    "current_demand"                                =>round($list['current_demand'],2),
                    "total_demand"                                  =>round($list['total_demand'],2),
                    "collection_from_holding"                       =>($list['collection_from_no_of_hh1']),
                    "arrear_collection"                             =>round($list['arrear_collection_amount']+$list['arrear_collection_amount2'],2),
                    "current_collection"                            =>round($list['actual_collection_amount']+$list['actual_collection_amount2'],2),
                    "total_collection"                              =>round($list['total_collection_amount']+$list['total_collection_amount2'],2),
                    "balance_holding"                               =>$list['balance_hh'],
                    "arrear_balance"                                =>round($list['arrear_balance_amount'],2),
                    "current_balance"                               =>round($list['current_balance_amount'],2),
                    "total_balance"                                 =>round($list['total_balance_amount2'],2),
                ]);
            }
            $data = $result;
            return $this->respond(($data), 200);
            
        }
        catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
    }

    public function smartCityWaterApi(){
        try{
            $inputs = (array)$this->request->getJSON();
            $this->setSession();
            if($this->request->getMethod(true) != 'POST')
            {
                $errors = $this->request->getMethod(true). " methode not allowed";
                return $this->fail($errors, 400);
            }
            $currentFY = getFY();
            $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
            $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
            $ward_mstr_id = "";
            $obj = new \App\Controllers\WaterWardWiseDCBReport();
            $obj->request = $this->request;
            foreach($inputs as $key=>$val){
                $_REQUEST[$key]=$val;
            }
            if(isset($inputs["fYear"])){
                $currentFY = $inputs["fYear"];
            }
            if(isset($inputs["ward_no"])){
                $ward_mstr_id = $this->model_ward_mstr->select("*")->where("ulb_mstr_id",1)->where('ward_no',(string)$inputs["ward_no"])->get()->getFirstRow("array")['id']??"";
            }
            $_REQUEST["fin_year"] = $currentFY;
            $_REQUEST["ward_mstr_id"] = $ward_mstr_id;
            $responseData = [];
            $data = $obj->index();
            $ward_wise_dcb=$data["ward_wise_dcb"];
            if($ward_wise_dcb){
                $total_consumer=0;
                $total_arrear_demand=0;
                $total_current_demand=0;
                $total_demand=0;
                $total_arrear_coll=0;
                $total_current_coll=0;
                $total_adv=0;
                $total_coll=0;
                $total_arrear_dues=0;
                $total_current_dues=0;
                $total_outstanding_dues=0;
                foreach($ward_wise_dcb as $kay=>$val)
                {
                    $total_consumer+=$val['total_consumer'];
                    $arr_demand=$val['arrear_demand']-$val['prev_coll_amt'];
                    $curr_demand=$val['current_demand'];


                    $total_arrear_demand=$total_arrear_demand+$arr_demand;
                    $total_current_demand=$total_current_demand+$curr_demand;
                    $total_demand=$total_arrear_demand+$total_current_demand;


                    $arrear_coll=$val['arrear_collection'];
                    $current_coll=$val['curr_collection'];
                    $advance=$val['advance_amount'];

                    $total_arrear_coll=$total_arrear_coll+$arrear_coll;
                    $total_current_coll=$total_current_coll+$current_coll;
                    $total_adv=$total_adv+$advance;


                    $total_coll=$total_arrear_coll+$total_current_coll+$total_adv;

                    $total_arrear_dues=$total_arrear_demand-$total_arrear_coll;
                    $total_current_dues=$total_current_demand-$total_current_coll;
                    
                    $total_outstanding_dues=$total_arrear_dues+$total_current_dues;
                    $responseData[$kay]=[
                        "ward_no" =>$val['ward_no'],
                        "total_consumer" =>round($val['total_consumer'],2),
                        "arrear_demand"=>round($val['arrear_demand']-$val['prev_coll_amt'],2),
                        "current_demand"=>round($val['current_demand'],2),
                        "total_demand"=>round(($val['arrear_demand']-$val['prev_coll_amt'])+$val['current_demand'],2),
                        "arrear_collection"=>round($val['arrear_collection'],2),
                        "current_collection"=>round($val['curr_collection'],2),
                        "advance_amount"=>round($val['advance_amount'],2),
                        "total_collection"=>round($val['arrear_collection'] + $val['curr_collection'] + $val['advance_amount'],2),
                        "arrear_balance"=>round(($val['arrear_demand']-$val['prev_coll_amt'])-$val['arrear_collection'],2),                        
                        "current_balance"=>round($val['current_demand']-$val['curr_collection'],2),
                        "total_balance"=>round((($val['arrear_demand']-$val['prev_coll_amt'])+$val['current_demand'])-($val['arrear_collection']+$val['curr_collection']),2),
                    ];
                }
                // $responseData[]=[
                //     "ward_no" =>"Total",
                //     "total_consumer" =>round($total_consumer,2),  
                //     "outstanding_demand"=>round($total_arrear_demand,2),
                //     "current_demand"=>round($total_current_demand,2),
                //     "total_demand"=>round($total_demand,2),
                //     "arrear_collection"=>round($total_arrear_coll,2),
                //     "current_collection"=>round($total_current_coll,2),
                //     "advance_amount"=>round($total_adv,2),
                //     "total_collection"=>round($total_coll,2),
                //     "balance_outstanding_demand"=>round($total_arrear_dues,2),                        
                //     "balance_current_demand"=>round($total_current_dues,2),
                //     "total_balance"=>round($total_outstanding_dues,2),
                // ];   
                $data["ward_wise_dcb"]=$responseData;         
            }
            $result["dcb"] = $data["ward_wise_dcb"];
            return $this->respond(($result), 200);
            
        }
        catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
    }

    public function smartCityWaterAppApi(){
        try{
            $inputs = (array)$this->request->getJSON();
            $this->setSession();
            if($this->request->getMethod(true) != 'POST')
            {
                $errors = $this->request->getMethod(true). " methode not allowed";
                return $this->fail($errors, 400);
            }
            $currentFY = getFY();
            $ward_mstr_id = "";
            $obj = new \App\Controllers\WaterWardWiseDCBReport();
            $obj->request = $this->request;
            foreach($inputs as $key=>$val){
                $_REQUEST[$key]=$val;
            }
            if(isset($inputs["fYear"])){
                $currentFY = $inputs["fYear"];
            }
            if(isset($inputs["ward_no"])){
                $ward_mstr_id = $this->model_ward_mstr->select("*")->where("ulb_mstr_id",1)->where('ward_no',(string)$inputs["ward_no"])->get()->getFirstRow("array")['id']??"";
            }
            $_REQUEST["fyear"] = $currentFY;
            $_REQUEST["ward_mstr_id"] = $ward_mstr_id;
            $data = $obj->getAppSummary(); 
            return $this->respond(($data), 200);
            
        }
        catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
    }

    public function smartCityTradeAppApi(){
        try{
            $data=[];
            $inputs = (array)$this->request->getJSON();
            $this->setSession();
            if($this->request->getMethod(true) != 'POST')
            {
                $errors = $this->request->getMethod(true). " methode not allowed";
                return $this->fail($errors, 400);
            }
            $currentFY = getFY();
            $ward_mstr_id = "";
            $obj = new \App\Controllers\TradeCollectionSummary();
            $obj->request = $this->request;            
                        
            if(isset($inputs["fYear"])){
                $currentFY = $inputs["fYear"];
            }
            if(isset($inputs["ward_no"])){
                $ward_mstr_id = $this->model_ward_mstr->select("*")->where("ulb_mstr_id",1)->where('ward_no',(string)$inputs["ward_no"])->get()->getFirstRow("array")['id']??"";
            }
            if(!isset($inputs["form_date"])){
                $inputs["from_date"] = date('Y-m-d');
            }
            if(!isset($inputs["to_date"])){
                $inputs["to_date"] = date('Y-m-d');
            }
            if(isset($inputs["upto_date"]) && $inputs["upto_date"]){
                $inputs["to_date"] = $inputs["upto_date"];
            }
            
            $inputs["fyear"] = $currentFY;
            $inputs["ward_mstr_id"] = $ward_mstr_id;
            foreach($inputs as $key=>$val){
                $_REQUEST[$key]=$val;
            }
            $summary = $obj->report();
            $result["collection"]=[
                [
                    "payment_mode"=>"cash",
                    "amount"=>round($summary["cash"]['cash']??0),
                    "tran_count"=>($summary["cash"]['id']??0),
                    "app_count"=>($summary["cash"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"cheque",
                    "amount"=>round($summary["cheque"]['cheque']??0),
                    "tran_count"=>($summary["cheque"]['id']??0),
                    "app_count"=>($summary["cheque"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"dd",
                    "amount"=>round($summary["dd"]['dd']??0),
                    "tran_count"=>($summary["dd"]['id']??0),
                    "app_count"=>($summary["dd"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"online",
                    "amount"=>round($summary["online"]['online']??0),
                    "tran_count"=>($summary["online"]['id']??0),
                    "app_count"=>($summary["online"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"card",
                    "amount"=>round($summary["card"]['card']??0),
                    "tran_count"=>($summary["card"]['id']??0),
                    "app_count"=>($summary["card"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"card",
                    "amount"=>round($summary["card"]['card']??0),
                    "tran_count"=>($summary["card"]['id']??0),
                    "app_count"=>($summary["card"]['consumer']??0),
                ],
            ] ;
            $result["canceled"]=[
                [
                    "payment_mode"=>"cheque",
                    "amount"=>round($summary["cheque_cancel"]['cheque']??0),
                    "tran_count"=>($summary["cheque_cancel"]['id']??0),
                    "app_count"=>($summary["cheque_cancel"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"dd",
                    "amount"=>round($summary["dd_cancel"]['dd']??0),
                    "tran_count"=>($summary["dd_cancel"]['id']??0),
                    "app_count"=>($summary["dd_cancel"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"online",
                    "amount"=>round($summary["online_cancel"]['online']??0),
                    "tran_count"=>($summary["online_cancel"]['id']??0),
                    "app_count"=>($summary["online_cancel"]['consumer']??0),
                ],
                [
                    "payment_mode"=>"card",
                    "amount"=>round($summary["card_cancel"]['card']??0),
                    "tran_count"=>($summary["card_cancel"]['id']??0),
                    "app_count"=>($summary["card_cancel"]['consumer']??0),
                ],
            ];

            $result["app_type_collection"]=[
                [
                    "app_type"=>"New Licence",
                    "amount"=>round($summary["new_licence"]['new']??0),
                    "tran_count"=>($summary["new_licence"]['id']??0),
                    "app_count"=>($summary["new_licence"]['consumer']??0),
                ],
                [
                    "app_type"=>"Renewal Licence",
                    "amount"=>round($summary["renewal_licence"]['renewal']??0),
                    "tran_count"=>($summary["renewal_licence"]['id']??0),
                    "app_count"=>($summary["renewal_licence"]['consumer']??0),
                ],
                [
                    "app_type"=>"Amendment Licence",
                    "amount"=>round($summary["amendment_licence"]['amendment']??0),
                    "tran_count"=>($summary["amendment_licence"]['id']??0),
                    "app_count"=>($summary["amendment_licence"]['consumer']??0),
                ],
                [
                    "app_type"=>"Surrender Licence",
                    "amount"=>round($summary["surender_licence"]['surender']??0),
                    "tran_count"=>($summary["surender_licence"]['id']??0),
                    "app_count"=>($summary["surender_licence"]['consumer']??0),
                ],
            ];
            $summary= $result;

            return $this->respond(($summary), 200);
            
        }
        catch(Exception $e){
            $errors = "Oops, error occurred  !!".$e->getMessage();
            return $this->fail($errors, 400);
        }
    }

    #========end api for smart city====================

    #========centralDashboardApi=======================

    public function centralDashboardLogin(){
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://upyog-test.niua.org/user/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "accept: application/json, text/plain",
                "authorization: Basic ZWdvdi11c2VyLWNsaWVudDo=",
                "content-type: application/x-www-form-urlencoded"
            ],
            CURLOPT_POSTFIELDS => http_build_query([
                'username' => 'JH_NDA_USER',
                'password' => 'upyogTest@123',
                'grant_type' => 'password',
                'scope' => 'read',
                'tenantId' => 'pg',
                'userType' => 'SYSTEM'
            ]),
        ]);

        // Execute the request and fetch the response
        $response = curl_exec($curl);

        // Check for errors
        $err = curl_error($curl);print_var($response); print_var($err);die;

        // Close the cURL session
        $i =1;
        while(true){
            $response = json_decode($response,true);            
            echo"\n login [".($i++)."] ====\n";
            print_var($response);
            echo"\n\n\n";
            if(isset($response["access_token"])){
                break;
            }
            sleep(20);
            $response = curl_exec($curl);
            
        }            
        curl_close($curl);
        return $response;
    }

    public function centralDashboardPushPropertyData(){
        $this->propDBConn();
        $wardList = $this->db_property->query("select * from view_ward_mstr where ulb_mstr_id=1 AND status =1 ")->getResultArray();
        $currentDate = date("Y-m-d");
        list($fromYear,$uptoYear) = explode("-",getFY("2020-04-01"));
        $fyearFromDate = $fromYear."-04-01";
        $fyearUptoDate = $uptoYear."-03-31";

        $privOneFyearFromDate = ($fromYear-1)."-04-01";
        $privOneFyearUptoDate = ($uptoYear-1)."-03-31";

        $privTwoFyearFromDate = ($fromYear-2)."-04-01";
        $privTwoFyearUptoDate = ($uptoYear-2)."-03-31";

        
        
        $response = $this->centralDashboardLogin();
        foreach($wardList as $ward){
            $wardId = $ward["id"];
            $sql_assets = "select count(id) as assessments, count(id) as todaysTotalApplications
                            from tbl_saf_dtl
                            where apply_date = '$currentDate'
                                AND ward_mstr_id = $wardId
            ";
            $assets = $this->db_property->query($sql_assets)->getFirstRow("array");            

            $sql_deactivate = "select count(distinct prop_dtl_id) as counts
                            from tbl_prop_saf_deactivation
                            join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_saf_deactivation.prop_dtl_id
                            where tbl_prop_saf_deactivation.deactivation_date = '$currentDate'
                                AND tbl_prop_dtl.ward_mstr_id = $wardId
            ";
            $deactivate = $this->db_property->query($sql_deactivate)->getFirstRow("array"); 

            $sql_transaction ="select (count( distinct (case when tran_type = 'Property' then prop_dtl_id end))
                                    + count( distinct (case when tran_type = 'Saf' then prop_dtl_id end))
                                ) as noOfPropertiesPaidToday,
                                ( count( distinct (case when tran_type = 'Saf' then prop_dtl_id end)) ) as INITIATED
                            from tbl_transaction
                            where status in(1,2)
                                AND tran_date = '$currentDate'
                                AND ward_mstr_id = $wardId
            ";
            $transection = $this->db_property->query($sql_transaction)->getFirstRow("array");   

            $sql_approve_saf ="with last_remarks as(
                                    select saf_dtl_id,forward_date,ROW_NUMBER() OVER(PARTITION BY saf_dtl_id ORDER BY id DESC) as row_num
                                    from tbl_level_pending_dtl	
                                )
                                select count(tbl_saf_dtl.id) as approved_saf,
                                    count(case when tbl_saf_dtl.assessment_type='New Assessment'  then tbl_saf_dtl.id end ) as approved_saf_new_assessment,
                                    count(case when tbl_saf_dtl.assessment_type='Reassessment'  then tbl_saf_dtl.id end ) as approved_saf_reassessment,
                                    count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then tbl_saf_dtl.id end ) as approved_saf_mutation
                                from tbl_saf_dtl
                                join last_remarks on last_remarks.saf_dtl_id = tbl_saf_dtl.id and row_num =1
                                where tbl_saf_dtl.saf_pending_status =1 
                                    AND forward_date::date ='$currentDate'
                                    AND ward_mstr_id = $wardId
            ";
            $approve_saf = $this->db_property->query($sql_approve_saf)->getFirstRow("array");

            $sql_doc_verify ="select count(id) as DOCVERIFIED
                            from tbl_saf_dtl
                            where doc_verify_status =1 
                                AND doc_verify_date = '$currentDate'
                                AND ward_mstr_id = $wardId            
            ";
            $doc_verify = $this->db_property->query($sql_doc_verify)->getFirstRow("array");

            $sql_filed_verification ="select count(distinct tbl_saf_dtl.id) as FIELDVERIFIED
                                    from tbl_saf_dtl
                                    join tbl_field_verification_dtl on tbl_field_verification_dtl.saf_dtl_id = tbl_saf_dtl.id
                                    where tbl_field_verification_dtl.status =1 
                                        AND tbl_field_verification_dtl.verified_by ='AGENCY TC'           
                                        AND tbl_field_verification_dtl.created_on::date = '$currentDate'
                                        AND tbl_saf_dtl.ward_mstr_id = $wardId       
            ";
            $filed_verification = $this->db_property->query($sql_filed_verification)->getFirstRow("array");

            $sql_current_property_resistor ="select count(id) as c_prop_registor
                                            from tbl_prop_dtl
                                            where created_on::date between '$fyearFromDate' and '$fyearUptoDate' 
                                                AND tbl_prop_dtl.ward_mstr_id = $wardId     
            ";
            $current_property_resistor = $this->db_property->query($sql_current_property_resistor)->getFirstRow("array");

            $sql_prive_one_property_resistor ="select count(id) as c_prop_registor
                                            from tbl_prop_dtl
                                            where created_on::date between '$privOneFyearFromDate' and '$privOneFyearUptoDate'
                                                AND tbl_prop_dtl.ward_mstr_id = $wardId        
            ";
            $prive_one_property_resistor = $this->db_property->query($sql_prive_one_property_resistor)->getFirstRow("array");

            $sql_prive_two_property_resistor ="select count(id) as c_prop_registor
                                            from tbl_prop_dtl
                                            where created_on::date between '$privTwoFyearFromDate' and '$privTwoFyearUptoDate' 
                                                AND tbl_prop_dtl.ward_mstr_id = $wardId       
            ";
            $prive_two_property_resistor = $this->db_property->query($sql_prive_two_property_resistor)->getFirstRow("array");

            $sql_category_type_prop ="select count(id),
                                        count(case when holding_type in('PURE_RESIDENTIAL') then id end) as Residential,
                                        count(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then id end) as Commercial, 
                                        count(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then id end) as MixedUse, 
                                        count(case when holding_type in('TRUST') then id end) as Institutional, 
                                        0 as Industrial, 
                                        count(case when holding_type in('VACANT_LAND') then id end) as vacanteLand
                                    from tbl_prop_dtl
                                    where status =1 
                                        AND created_on::date <= '$currentDate'
                                        AND tbl_prop_dtl.ward_mstr_id = $wardId               
            ";
            $category_type_prop = $this->db_property->query($sql_category_type_prop)->getFirstRow("array");

            $sql_transactions ="select sum(counts) as counts,
                                    sum(payable_amt) as payable_amt,
                                    sum(Residential) as Residential,
                                    sum(Residential_payable_amt) as Residential_payable_amt,
                                    sum(Commercial) as Commercial,
                                    sum(Commercial_payable_amt) as Commercial_payable_amt,
                                    sum(MixedUse) as MixedUse,
                                    sum(MixedUse_payable_amt) as MixedUse_payable_amt,
                                    sum(Institutional) as Institutional,
                                    sum(Institutional_payable_amt) as Institutional_payable_amt,
                                    sum(Industrial) as Industrial,
                                    sum(Industrial_payble_amt) as Industrial_payble_amt,
                                    sum(vacanteLand) as vacanteLand,
                                    sum(vacanteLand_payable_amt) as vacanteLand_payable_amt
                                from(
                                    (
                                        select count(DISTINCT(tbl_prop_dtl.id))counts,
                                            coalesce(sum(tbl_transaction.payable_amt),0) as payable_amt,
                                            count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                            coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction.payable_amt end),0) as Residential_payable_amt,
                                            count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                            coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction.payable_amt end),0) as Commercial_payable_amt,
                                            count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                            coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction.payable_amt end),0) as MixedUse_payable_amt,
                                            count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                            coalesce(sum(case when holding_type in('TRUST') then tbl_transaction.payable_amt end),0) as Institutional_payable_amt,
                                            0 as Industrial, 
                                            0 as Industrial_payble_amt,
                                            count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                            coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction.payable_amt end),0) as vacanteLand_payable_amt
                                        
                                        from tbl_prop_dtl
                                        join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                        where tbl_transaction.status in(1,2)
                                            AND tbl_transaction.tran_type = 'Property'
                                            AND tran_date = '$currentDate'
                                            AND tbl_prop_dtl.ward_mstr_id = $wardId 
                                    )
                                    union all(
                                            select count(DISTINCT(tbl_prop_dtl.id))counts,
                                            coalesce(sum(tbl_transaction.payable_amt),0) as payable_amt,
                                            count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                            coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction.payable_amt end),0) as Residential_payable_amt,
                                            count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                            coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction.payable_amt end),0) as Commercial_payable_amt,
                                            count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                            coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction.payable_amt end),0) as MixedUse_payable_amt,
                                            count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                            coalesce(sum(case when holding_type in('TRUST') then tbl_transaction.payable_amt end),0) as Institutional_payable_amt,
                                            0 as Industrial, 
                                            0 as Industrial_payble_amt,
                                            count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                            coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction.payable_amt end),0) as vacanteLand_payable_amt
                                        
                                        from tbl_saf_dtl as tbl_prop_dtl
                                        join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                        where tbl_transaction.status in(1,2)
                                            AND tbl_transaction.tran_type!= 'Property'
                                            AND tran_date = '$currentDate'
                                            AND tbl_prop_dtl.ward_mstr_id = $wardId
                                    )
                                )alls

            ";
            $transactions = $this->db_property->query($sql_transactions)->getFirstRow("array");

            $sql_payment_type ="select coalesce(sum(payable_amt),0) as total_payable_amt,
                                    coalesce(sum(case when upper(tran_mode)='ONLINE' then payable_amt end),0) as Digital,
                                    coalesce(sum(case when upper(tran_mode)!='ONLINE' then payable_amt end),0) as Non_Digital
                                from tbl_transaction
                                where tbl_transaction.status in(1,2)
                                    AND tran_date = '$currentDate'
                                    AND ward_mstr_id = $wardId  
            ";
            $payment_type = $this->db_property->query($sql_payment_type)->getFirstRow("array");

            $sql_taxes ="select sum(counts) as counts,
                            sum(payable_tax) as payable_tax,
                            sum(Residential) as Residential,
                            sum(Residential_payable_tax) as Residential_payable_tax,
                            sum(Commercial) as Commercial,
                            sum(Commercial_payable_tax) as Commercial_payable_tax,
                            sum(MixedUse) as MixedUse,
                            sum(MixedUse_payable_tax) as MixedUse_payable_tax,
                            sum(Institutional) as Institutional,
                            sum(Institutional_payable_tax) as Institutional_payable_tax,
                            sum(Industrial) as Industrial,
                            sum(Industrial_payble_tax) as Industrial_payble_tax,
                            sum(vacanteLand) as vacanteLand,
                            sum(vacanteLand_payable_tax) as vacanteLand_payable_tax
                        from(
                            (
                                select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_collection.amount),0) as payable_tax,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_collection.amount end),0) as Residential_payable_tax,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_collection.amount end),0) as Commercial_payable_tax,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_collection.amount end),0) as MixedUse_payable_tax,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_collection.amount end),0) as Institutional_payable_tax,
                                    0 as Industrial, 
                                    0 as Industrial_payble_tax,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_collection.amount end),0) as vacanteLand_payable_tax
                                
                                from tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_collection on tbl_collection.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type = 'Property'
                                    AND tran_date = '$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId 
                            )
                            union all(
                                    select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_collection.amount),0) as payable_tax,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_collection.amount end),0) as Residential_payable_tax,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_collection.amount end),0) as Commercial_payable_tax,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_collection.amount end),0) as MixedUse_payable_tax,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_collection.amount end),0) as Institutional_payable_tax,
                                    0 as Industrial, 
                                    0 as Industrial_payble_tax,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_collection.amount end),0) as vacanteLand_payable_tax
                                
                                from tbl_saf_dtl as tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_saf_collection as tbl_collection on tbl_collection.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type != 'Property'
                                    AND tran_date = '$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId
                            )
                        )alls
            
            ";
            $taxes = $this->db_property->query($sql_taxes)->getFirstRow("array");

            $sql_fine ="select sum(counts) as counts,
                            sum(fine) as fine,
                            sum(Residential) as Residential,
                            sum(Residential_payable_fine) as Residential_payable_fine,
                            sum(Commercial) as Commercial,
                            sum(Commercial_payable_fine) as Commercial_payable_fine,
                            sum(MixedUse) as MixedUse,
                            sum(MixedUse_payable_fine) as MixedUse_payable_fine,
                            sum(Institutional) as Institutional,
                            sum(Institutional_payable_fine) as Institutional_payable_fine,
                            sum(Industrial) as Industrial,
                            sum(Industrial_payble_fine) as Industrial_payble_fine,
                            sum(vacanteLand) as vacanteLand,
                            sum(vacanteLand_payable_fine) as vacanteLand_payable_fine
                        from(
                            (
                                select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_transaction_fine_rebet_details.amount),0) as fine,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction_fine_rebet_details.amount end),0) as Residential_payable_fine,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as Commercial_payable_fine,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as MixedUse_payable_fine,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_transaction_fine_rebet_details.amount end),0) as Institutional_payable_fine,
                                    0 as Industrial, 
                                    0 as Industrial_payble_fine,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction_fine_rebet_details.amount end),0) as vacanteLand_payable_fine
                                
                                from tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_transaction_fine_rebet_details On tbl_transaction_fine_rebet_details.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type = 'Property'
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='Add'
                                    AND tran_date ='$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId 
                            )
                            union all(
                                    select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_transaction_fine_rebet_details.amount),0) as fine,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction_fine_rebet_details.amount end),0) as Residential_payable_fine,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as Commercial_payable_fine,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as MixedUse_payable_fine,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_transaction_fine_rebet_details.amount end),0) as Institutional_payable_fine,
                                    0 as Industrial, 
                                    0 as Industrial_payble_fine,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction_fine_rebet_details.amount end),0) as vacanteLand_payable_fine
                                
                                from tbl_saf_dtl as tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_transaction_fine_rebet_details On tbl_transaction_fine_rebet_details.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type != 'Property'
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='Add'
                                    AND tran_date = '$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId 
                            )
                        )alls
            
            ";
            $fine = $this->db_property->query($sql_fine)->getFirstRow("array");

            $sql_rebate ="select sum(counts) as counts,
                            sum(rebate) as rebate,
                            sum(Residential) as Residential,
                            sum(Residential_payable_rebate) as Residential_payable_rebate,
                            sum(Commercial) as Commercial,
                            sum(Commercial_payable_rebate) as Commercial_payable_rebate,
                            sum(MixedUse) as MixedUse,
                            sum(MixedUse_payable_rebate) as MixedUse_payable_rebate,
                            sum(Institutional) as Institutional,
                            sum(Institutional_payable_rebate) as Institutional_payable_rebate,
                            sum(Industrial) as Industrial,
                            sum(Industrial_payble_rebate) as Industrial_payble_rebate,
                            sum(vacanteLand) as vacanteLand,
                            sum(vacanteLand_payable_rebate) as vacanteLand_payable_rebate
                        from(
                            (
                                select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_transaction_fine_rebet_details.amount),0) as rebate,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction_fine_rebet_details.amount end),0) as Residential_payable_rebate,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as Commercial_payable_rebate,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as MixedUse_payable_rebate,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_transaction_fine_rebet_details.amount end),0) as Institutional_payable_rebate,
                                    0 as Industrial, 
                                    0 as Industrial_payble_rebate,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction_fine_rebet_details.amount end),0) as vacanteLand_payable_rebate
                                
                                from tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_transaction_fine_rebet_details On tbl_transaction_fine_rebet_details.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type = 'Property'
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='Minus'
                                    AND tran_date = '$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId 
                            )
                            union all(
                                    select count(DISTINCT(tbl_prop_dtl.id))counts,
                                    coalesce(sum(tbl_transaction_fine_rebet_details.amount),0) as rebate,
                                    count(DISTINCT(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_prop_dtl.id end)) as Residential,
                                    coalesce(sum(case when holding_type in('PURE_RESIDENTIAL') OR holding_type is null then tbl_transaction_fine_rebet_details.amount end),0) as Residential_payable_rebate,
                                    count(DISTINCT(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_prop_dtl.id end)) as Commercial, 
                                    coalesce(sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as Commercial_payable_rebate,
                                    count(DISTINCT(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_prop_dtl.id end)) as MixedUse, 	 
                                    coalesce(sum(case when holding_type in('MIX COMMERCIAL','MIX_COMMERCIAL') then tbl_transaction_fine_rebet_details.amount end),0) as MixedUse_payable_rebate,
                                    count(DISTINCT(case when holding_type in('TRUST') then tbl_prop_dtl.id end)) as Institutional, 	 
                                    coalesce(sum(case when holding_type in('TRUST') then tbl_transaction_fine_rebet_details.amount end),0) as Institutional_payable_rebate,
                                    0 as Industrial, 
                                    0 as Industrial_payble_rebate,
                                    count(DISTINCT(case when holding_type in('VACANT_LAND') then tbl_prop_dtl.id end)) as vacanteLand, 	 
                                    coalesce(sum(case when holding_type in('VACANT_LAND') then tbl_transaction_fine_rebet_details.amount end),0) as vacanteLand_payable_rebate
                                
                                from tbl_saf_dtl as tbl_prop_dtl
                                join tbl_transaction on tbl_transaction.prop_dtl_id = tbl_prop_dtl.id 
                                join tbl_transaction_fine_rebet_details On tbl_transaction_fine_rebet_details.transaction_id = tbl_transaction.id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.tran_type != 'Property'
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='Minus'
                                    AND tran_date = '$currentDate'
                                    AND tbl_prop_dtl.ward_mstr_id = $wardId 
                            )
                        )alls            
            ";
            $rebate = $this->db_property->query($sql_rebate)->getFirstRow("array");

            $token = $response["access_token"];
            $userInfo=$response["UserRequest"];
            $data = [
                "RequestInfo" => [
                    "apiId" => "asset-services",
                    "ver" => null,
                    "ts" => null,
                    "action" => null,
                    "did" => null,
                    "key" => null,
                    "msgId" => date("d-m-Y",strtotime($currentDate)),
                    "authToken" => $token,
                    "userInfo" => $userInfo
                ],
                "Data" => [
                    [
                        "date" => date("d-m-Y",strtotime($currentDate)),
                        "module" => "PT",
                        "ward" => $ward["ward_no"],
                        "ulb" => "jh.ranchimunicipalcorporation",
                        "region" => "Ranchi",
                        "state" => "Jharkhand",
                        "metrics" => [
                            "assessments" => $assets["assessments"]??0,
                            "todaysTotalApplications" => $assets["todaystotalapplications"]??0,
                            "todaysClosedApplications" => $deactivate["counts"]??0,
                            "noOfPropertiesPaidToday" => $transection["noofpropertiespaidtoday"]??0,
                            "todaysApprovedApplications" => $approve_saf["approved_saf"]??0,
                            "todaysApprovedApplicationsWithinSLA" => 0,
                            "pendingApplicationsBeyondTimeline" => 0,
                            "avgDaysForApplicationApproval" => $approve_saf["approved_saf"]??0,
                            "StipulatedDays" => 0,
                            "todaysMovedApplications" => [
                                [
                                    "groupBy" => "applicationStatus",
                                    "buckets" => [
                                        ["name" => "APPROVED", "value" => $approve_saf["approved_saf"]??0],
                                        ["name" => "CORRECTIONPENDING", "value" => 0],
                                        ["name" => "DOCVERIFIED", "value" => $doc_verify["docverified"]??0],
                                        ["name" => "FIELDVERIFIED", "value" => $filed_verification["fieldverified"]??0],
                                        ["name" => "OPEN", "value" => 0],
                                        ["name" => "PAID", "value" => $transactions["counts"]??0],
                                        ["name" => "REJECTED", "value" => 0],
                                        ["name" => "INITIATED", "value" => $transection["initiated"]??0]
                                    ]
                                ]
                            ],
                            "propertiesRegistered" => [
                                [
                                    "groupBy" => "financialYear",
                                    "buckets" => [
                                        ["name" => "2018-19", "value" => $prive_two_property_resistor["c_prop_registor"]],
                                        ["name" => "2019-20", "value" => $prive_one_property_resistor["c_prop_registor"]],
                                        ["name" => "2020-21", "value" => $current_property_resistor["c_prop_registor"]]
                                    ]
                                ]
                            ],
                            "assessedProperties" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $category_type_prop["residential"]??0],
                                        ["name" => "Commercial", "value" => $category_type_prop["commercial"]??0],
                                        ["name" => "Mixed Use", "value" => $category_type_prop["mixeduse"]??0],
                                        ["name" => "Industrial", "value" => $category_type_prop["industrial"]??0],
                                        ["name" => "institutional", "value" => $category_type_prop["institutional"]??0],
                                    ]
                                ]
                            ],
                            "transactions" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $transactions["residential"]??0],
                                        ["name" => "Commercial", "value" => $transactions["commercial"]??0],
                                        ["name" => "Mixed Use", "value" => $transactions["mixeduse"]??0],
                                        ["name" => "Industrial", "value" => $transactions["industrial"]??0],
                                        ["name" => "institutional", "value" => $transactions["institutional"]??0],
                                    ]
                                ]
                            ],
                            "todaysCollection" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $transactions["residential_payable_amt"]??0],
                                        ["name" => "Commercial", "value" => $transactions["commercial_payable_amt"]??0],
                                        ["name" => "Mixed Use", "value" => $transactions["mixeduse_payable_amt"]??0],
                                        ["name" => "Industrial", "value" => $transactions["industrial_payable_amt"]??0],
                                        ["name" => "institutional", "value" => $transactions["institutional_payable_amt"]??0],
                                    ]
                                ],
                                [
                                    "groupBy" => "paymentChannelType",
                                    "buckets" => [
                                        ["name" => "Digital", "value" => $payment_type["digital"]??0],
                                        ["name" => "Non Digital", "value" => $payment_type["non_digital"]??0],
                                    ]
                                ]
                            ],
                            "propertyTax" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $taxes["residential"]??0],
                                        ["name" => "Commercial", "value" => $taxes["commercial"]??0],
                                        ["name" => "Mixed Use", "value" => $taxes["mixeduse"]??0],
                                        ["name" => "Industrial", "value" => $taxes["industrial"]??0],
                                        ["name" => "institutional", "value" => $taxes["institutional"]??0],
                                    ]
                                ]
                            ],
                            "cess" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $cess["residential"]??0],
                                        ["name" => "Commercial", "value" => $cess["commercial"]??0],
                                        ["name" => "Mixed Use", "value" => $cess["mixeduse"]??0],
                                        ["name" => "Industrial", "value" => $cess["industrial"]??0],
                                        ["name" => "institutional", "value" => $cess["institutional"]??0],
                                    ]
                                ]
                            ],
                            "rebate" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $rebate["residential_payable_rebate"]??0],
                                        ["name" => "Commercial", "value" => $rebate["commercial_payable_rebate"]??0],
                                        ["name" => "Mixed Use", "value" => $rebate["mixeduse_payable_rebate"]??0],
                                        ["name" => "Industrial", "value" => $rebate["industrial_payable_rebate"]??0],
                                        ["name" => "institutional", "value" => $rebate["institutional_payable_rebate"]??0],
                                    ]
                                ]
                            ],
                            "penalty" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $fine["residential_payable_fine"]??0],
                                        ["name" => "Commercial", "value" => $fine["commercial_payable_fine"]??0],
                                        ["name" => "Mixed Use", "value" => $fine["mixeduse_payable_fine"]??0],
                                        ["name" => "Industrial", "value" => $fine["industrial_payable_fine"]??0],
                                        ["name" => "institutional", "value" => $fine["institutional_payable_fine"]??0],
                                    ]
                                ]
                            ],
                            "interest" => [
                                [
                                    "groupBy" => "usageCategory",
                                    "buckets" => [
                                        ["name" => "Residential", "value" => $interest["residential_payable_fine"]??0],
                                        ["name" => "Commercial", "value" => $interest["commercial_payable_fine"]??0],
                                        ["name" => "Mixed Use", "value" => $interest["mixeduse_payable_fine"]??0],
                                        ["name" => "Industrial", "value" => $interest["industrial_payable_fine"]??0],
                                        ["name" => "institutional", "value" => $interest["institutional_payable_fine"]??0],
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ];
            $data = $this->convertInt($data);
            $data["Data"][0]["ward"]=" ".$ward["ward_no"];
            $url = "https://upyog-test.niua.org/national-dashboard/metric/_ingest";
            $ch = curl_init($url);

            // Setup request to send JSON via POST
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);

            // Execute the request and fetch the response
            $responseData = curl_exec($ch);

            // Check for errors
            $is_success = 1;
            if(!isset(json_decode($responseData,true)["responseHash"])){
                $is_success = 0;
            }
            echo "\n\n\n\n response: $wardId \n\n\n";
            print_var($responseData);
            echo "\n";
            $insertSql = "INSERT INTO tbl_central_dashboard_data(ward_id, user_login_json, push_data_json,response_json,is_success)
                values( $wardId, '".json_encode($response)."', '".json_encode($data)."','".$responseData."',$is_success::bool)";
            $this->db_property->query($insertSql);

            // Close cURL session
            curl_close($ch);
        }
    }

    
    public function centralDashboardPushTradeData(){
        echo"\n\n trade \n\n\n";
        $this->connectTradeDBConn();
        $wardList = $this->db->query("select * from view_ward_mstr where ulb_mstr_id=1 AND status =1 ")->getResultArray();
        $currentDate = date("Y-m-d");
        list($fromYear,$uptoYear) = explode("-",getFY("2020-04-01"));
        $fyearFromDate = $fromYear."-04-01";
        $fyearUptoDate = $uptoYear."-03-31";

        $privOneFyearFromDate = ($fromYear-1)."-04-01";
        $privOneFyearUptoDate = ($uptoYear-1)."-03-31";

        $privTwoFyearFromDate = ($fromYear-2)."-04-01";
        $privTwoFyearUptoDate = ($uptoYear-2)."-03-31";
        
        $response = $this->centralDashboardLogin();

        foreach($wardList as $ward){
            $wardId = $ward["id"];

            $sql_tax = "select count(id)tran_count,coalesce(sum(paid_amount),0) as paid_amount, 
                            (coalesce(sum(paid_amount),0)- coalesce(sum(penalty),0)) as tax,
                            coalesce(sum(penalty),0) as penalty
                        from(
                            select id ,paid_amount, 
                                (
                                    select sum(amount) 
                                    from tbl_transaction_fine_rebet_details 
                                    where status =1 and transaction_id = tbl_transaction.id and value_add_minus = 'Add'		
                                ) as penalty
                            from tbl_transaction
                            WHERE tbl_transaction.status in(1,2)
                                AND transaction_date = '$currentDate' 
                                AND ward_mstr_id = $wardId                            
                        )tran
            ";
            $tax = $this->db->query($sql_tax)->getFirstRow("array"); 

            $sql_payment_type = "select coalesce(sum(paid_amount),0) as total_payable_amt,
                                    coalesce(sum(case when upper(payment_mode)='ONLINE' then paid_amount end),0) as Digital,
                                    coalesce(sum(case when upper(payment_mode)!='ONLINE' then paid_amount end),0) as Non_Digital
                                from tbl_transaction
                                where tbl_transaction.status in(1,2) 
                                    AND transaction_date = '$currentDate'
                                    AND ward_mstr_id = $wardId
            ";
            $payment_type = $this->db->query($sql_payment_type)->getFirstRow("array");            

            $sql_apply_license = "select count(id) as today_apply
                                from tbl_apply_licence
                                where status =1 
                                    AND apply_date = '$currentDate'
                                    AND ward_mstr_id = $wardId
            ";
            $apply_license = $this->db->query($sql_apply_license)->getFirstRow("array"); 

            $sql_field_verify ="select count(distinct(tbl_taxdaroga_document_verification.apply_licence_id)) as today_field_verify
                                from tbl_taxdaroga_document_verification
                                join tbl_apply_licence on tbl_apply_licence.id = tbl_taxdaroga_document_verification.apply_licence_id
                                where tbl_taxdaroga_document_verification.status = 1 
                                    AND tbl_taxdaroga_document_verification.created_on::date = '$currentDate'
                                    AND tbl_apply_licence.ward_mstr_id = $wardId
            ";
            $field_verify = $this->db->query($sql_field_verify)->getFirstRow("array");   

            $sql_pending_application ="select count(id) as today_pending_application , 
                                        count(case when (current_date - apply_date)>21 then id end) as pending_aplications_beyond_timeline
                                    from tbl_apply_licence
                                    where status =1 AND pending_status =1
                                        AND payment_status = 1
                                        AND ward_mstr_id = $wardId
            ";
            $pending_application = $this->db->query($sql_pending_application)->getFirstRow("array");

            $sql_payment_pending_application ="select count(id) as today_payment_pending_application
                                                from tbl_apply_licence
                                                where status =1 AND pending_status !=5
                                                    AND payment_status = 0
                                                    AND ward_mstr_id = $wardId            
            ";
            $payment_pending_application = $this->db->query($sql_payment_pending_application)->getFirstRow("array");

            $sql_approved_application ="select count(id) as today_approved_application
                                    from tbl_apply_licence
                                    where status =1 AND pending_status =5          
                                        AND license_date = '$currentDate'
                                        AND ward_mstr_id = $wardId       
            ";
            $approved_application = $this->db->query($sql_approved_application)->getFirstRow("array");

            $sql_rejected_application = "select count(id) as today_rejected_application
                                        from tbl_apply_licence
                                        where status =1 AND pending_status =4
                                            AND license_date = '$currentDate'
                                            AND ward_mstr_id = $wardId  
            ";

            $rejected_application = $this->db->query($sql_rejected_application)->getFirstRow("array");

            $token = $response["access_token"];
            $userInfo=$response["UserRequest"];
            $data = [
                "RequestInfo" => [
                    "apiId" => "asset-services",
                    "ver" => null,
                    "ts" => null,
                    "action" => null,
                    "did" => null,
                    "key" => null,
                    "msgId" => date("d-m-Y",strtotime($currentDate)),
                    "authToken" => $token,
                    "userInfo" => $userInfo
                ],
                "Data" => [
                    [
                        "date" => date("d-m-Y",strtotime($currentDate)),
                        "module" => "TL",
                        "ward" => $ward["ward_no"],
                        "ulb" => "jh.ranchimunicipalcorporation",
                        "region" => "Ranchi",
                        "state" => "Jharkhand",
                        "metrics" => [
                            "transactions" => $tax["tran_count"]??0,
                            "todaysApplications" => $apply_license["today_apply"]??0,
                            "tlTax" => $tax["tax"]??0,
                            "adhocPenalty" => $tax["penalty"]??0,
                            "adhocRebate" => 0,
                            "todaysLicenseIssuedWithinSLA" => 0,
                            "todaysApprovedApplications" => $approved_application["today_approved_application"]??0,
                            "pendingApplicationsBeyondTimeline" => $pending_application["pending_aplications_beyond_timeline"]??0,
                            "todaysApprovedApplicationsWithinSLA" => 0,
                            "avgDaysForApplicationApproval" => $approved_application["today_approved_application"]??0,
                            "StipulatedDays" => 21,
                            "todaysCollection" => [
                                [
                                    "groupBy" => "tradeType",
                                    "buckets" => [
                                        ["name" => "BRICKFIELD", "value" => 0],
                                        ["name" => "GROCERYSTORES", "value" => 0],
                                        ["name" => "CHARCOAL_KLIN", "value" => 0],
                                    ],
                                ],
                                [
                                    "groupBy" => "paymentChannelType",
                                    "buckets" => [
                                        ["name" => "Digital", "value" => $payment_type["digital"]??0],
                                        ["name" => "Non Digital", "value" => $payment_type["non_digital"]??0],
                                    ],
                                ],
                            ],
                            "todaysTradeLicenses" => [
                                [
                                    "groupBy" => "status",
                                    "buckets" => [
                                        ["name" => "INITIATED", "value" => 0],
                                        ["name" => "APPLIED", "value" => $apply_license["today_apply"]??0],
                                        ["name" => "FIELDINSPECTION", "value" => $filed_verification["today_field_verify"]??0],
                                        ["name" => "PENDINGAPPROVAL", "value" => $pending_application["today_pending_application"]??0],
                                        ["name" => "PENDINGPAYMENT", "value" => $payment_pending_application["today_payment_pending_application"]??0],
                                        ["name" => "APPROVED", "value" => $approved_application["today_approved_application"]??0],
                                        ["name" => "REJECTED", "value" => $rejected_application["today_rejected_application"]??0],
                                        ["name" => "CANCELLED", "value" => 0],
                                        ["name" => "CITIZENACTIONREQUIRED", "value" => 0],
                                    ],
                                ],
                            ],
                            "applicationsMovedToday" => [
                                [
                                    "groupBy" => "status",
                                    "buckets" => [
                                        ["name" => "INITIATED", "value" => 0],
                                        ["name" => "APPLIED", "value" => $apply_license["today_apply"]??0],
                                        ["name" => "FIELDINSPECTION", "value" => $filed_verification["today_field_verify"]??0],
                                        ["name" => "PENDINGAPPROVAL", "value" => $pending_application["today_pending_application"]??0],
                                        ["name" => "PENDINGPAYMENT", "value" => $payment_pending_application["today_payment_pending_application"]??0],
                                        ["name" => "APPROVED", "value" => $approved_application["today_approved_application"]??0],
                                        ["name" => "REJECTED", "value" => $rejected_application["today_rejected_application"]??0],
                                        ["name" => "CANCELLED", "value" => 0],
                                        ["name" => "CITIZENACTIONREQUIRED", "value" => 0],
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ];
            $data = $this->convertInt($data);
            $data["Data"][0]["ward"]=" ".$ward["ward_no"];
            
            $url = "https://upyog-test.niua.org/national-dashboard/metric/_ingest";
            $ch = curl_init($url);

            // Setup request to send JSON via POST
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);

            // Execute the request and fetch the response
            $responseData = curl_exec($ch);

            // Check for errors
            $is_success = 1;
            if(!isset(json_decode($responseData,true)["responseHash"])){
                $is_success = 0;
            }
            echo "\n\n\n\n response: $wardId \n\n\n";
            print_var($responseData);
            echo "\n";
            $insertSql = "INSERT INTO tbl_central_dashboard_data(ward_id, user_login_json, push_data_json,response_json,is_success)
                values( $wardId, '".json_encode($response)."', '".json_encode($data)."','".$responseData."', $is_success::bool)";
            $this->db->query($insertSql);

            // Close cURL session
            curl_close($ch);
        }
    }

    
    public function centralDashboardPushWatereData(){
        echo"\n\n Water \n\n\n";
        $this->connectWaterDBConn();
        $wardList = $this->db->query("select * from view_ward_mstr where ulb_mstr_id=1 AND status =1 ")->getResultArray();
        $currentDate = date("Y-m-d");
        list($fromYear,$uptoYear) = explode("-",getFY("2020-04-01"));
        $fyearFromDate = $fromYear."-04-01";
        $fyearUptoDate = $uptoYear."-03-31";

        $privOneFyearFromDate = ($fromYear-1)."-04-01";
        $privOneFyearUptoDate = ($uptoYear-1)."-03-31";

        $privTwoFyearFromDate = ($fromYear-2)."-04-01";
        $privTwoFyearUptoDate = ($uptoYear-2)."-03-31";
        
        $response = $this->centralDashboardLogin();

        foreach($wardList as $ward){
            $wardId = $ward["id"];

            $sql_tran = "select count(tbl_transaction.id) as tran_count,
                            count( case when tbl_consumer.property_type_id=1 OR tbl_apply_water_connection.property_type_id=1 then tbl_transaction.id end) resident_count,
                            coalesce(sum( case when tbl_consumer.property_type_id=1 OR tbl_apply_water_connection.property_type_id=1 then tbl_transaction.paid_amount end),0) resident_paid_amount,
                            
                            count( case when tbl_consumer.property_type_id in(2,10) OR tbl_apply_water_connection.property_type_id in(2,10) then tbl_transaction.id end) comercial_count,
                            coalesce(sum( case when tbl_consumer.property_type_id in(2,10) OR tbl_apply_water_connection.property_type_id in(2,10) then tbl_transaction.paid_amount end),0) comercial_paid_amount,
                            
                            count( case when tbl_consumer.property_type_id in(4,13) OR tbl_apply_water_connection.property_type_id in(4,13) then tbl_transaction.id end) institutional_count,
                            coalesce(sum( case when tbl_consumer.property_type_id in(4,13) OR tbl_apply_water_connection.property_type_id in(4,13) then tbl_transaction.paid_amount end),0) institutional_paid_amount,

                            coalesce(sum(tbl_transaction.paid_amount),0) as total_payable_amt,
                            coalesce(sum(case when upper(tbl_transaction.payment_mode)='ONLINE' then tbl_transaction.paid_amount end),0) as digital,
                            coalesce(sum(case when upper(tbl_transaction.payment_mode)!='ONLINE' then tbl_transaction.paid_amount end),0) as non_digital,
                            (coalesce(sum(tbl_transaction.paid_amount),0) - coalesce(sum(tbl_transaction.penalty),0) + coalesce(sum(tbl_transaction.rebate),0)) as total_tax_amt,
                            (coalesce(sum(tbl_transaction.penalty),0)) as total_penalty_amt,
                            (coalesce(sum(tbl_transaction.rebate),0)) as total_rebate_amt
                        from tbl_transaction
                        left join tbl_consumer on tbl_consumer.id = tbl_transaction.related_id 
                            and tbl_transaction.transaction_type = 'Demand Collection'
                        left join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_transaction.related_id 
                            and tbl_transaction.transaction_type != 'Demand Collection'
                        where tbl_transaction.status in(1,2)
                            AND tbl_transaction.transaction_date = '$currentDate'
                            AND tbl_transaction.ward_mstr_id = $wardId

            ";
            $tran = $this->db->query($sql_tran)->getFirstRow("array"); 

            $sql_collection = "select coalesce(sum(case when tbl_consumer_demand.demand_from <'2024-04-01' then tbl_consumer_collection.amount end),0) as arrear_collection,
                                        coalesce(sum(case when tbl_consumer_demand.demand_from >='2024-04-01' then tbl_consumer_collection.amount end),0) as current_collection,
                                        coalesce(sum(case when tbl_consumer_demand.connection_type not in('Metered','Meter') then tbl_consumer_collection.amount end),0) as fixed_collection,
                                        coalesce(sum(case when tbl_consumer_demand.connection_type in('Metered','Meter') then tbl_consumer_collection.amount end),0) as meter_collection		
                                from tbl_transaction
                                join tbl_consumer_collection on tbl_consumer_collection.transaction_id = tbl_transaction.id 
                                join tbl_consumer_demand on tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                where tbl_transaction.status in(1,2)
                                    AND tbl_transaction.transaction_date = '$currentDate'
                                    AND tbl_transaction.ward_mstr_id = $wardId
            ";
            $collection = $this->db->query($sql_collection)->getFirstRow("array");            

            $sql_connection_type = "select count( distinct(case when connection_type in(1,2) then consumer_id end)) as meter_connection,
                                        count( distinct(case when connection_type not in(1,2) then consumer_id end)) as fixed_connection
                                from tbl_meter_status
                                join tbl_consumer on tbl_consumer.id = tbl_meter_status.consumer_id
                                where tbl_meter_status.status =1
                                    AND tbl_meter_status.connection_date = '$currentDate'
                                    AND tbl_consumer.ward_mstr_id = $wardId
            ";
            $connection_type = $this->db->query($sql_connection_type)->getFirstRow("array"); 

            $sql_applyconnection ="select count(id) as total_apply_application,
                                    count( case when upper(apply_from) in('ONL','ONLINE') then id end) online_app,
                                    count( case when upper(apply_from) in('JSK','TC') then id end) counter_app,
                                    
                                    count( case when property_type_id in(1) then id end) resident_app,
                                    count( case when property_type_id in(2,10) then id end) comercial_app,
                                    count( case when property_type_id in(4,13) then id end) institutional_app
                                from tbl_apply_water_connection
                                where tbl_apply_water_connection.apply_date = '$currentDate'
                                    AND tbl_apply_water_connection.ward_mstr_id = $wardId
            ";
            $applyconnection = $this->db->query($sql_applyconnection)->getFirstRow("array");   

            $sql_pending_application ="select 
                                            count(tbl_level_pending.id),
                                            count(case when (current_date - tbl_level_pending.created_on::date) <4 then tbl_level_pending.id end ) as lest_than_3,
                                            count(case when (current_date - tbl_level_pending.created_on::date) between 4 and 7 then tbl_level_pending.id end ) as lest_than_7,
                                            count(case when (current_date - tbl_level_pending.created_on::date) between 8 and 15 then tbl_level_pending.id end ) as lest_than_15,
                                            count(case when (current_date - tbl_level_pending.created_on::date) > 15 then tbl_level_pending.id end ) as more_than_15
                                        from tbl_level_pending 
                                        join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_level_pending.apply_connection_id
                                        where tbl_level_pending.verification_status=0 
                                            and tbl_level_pending.status=1 
                                            AND tbl_apply_water_connection.ward_mstr_id = $wardId
            ";
            $pending_application = $this->db->query($sql_pending_application)->getFirstRow("array");

            $sql_approved ="select 
                                count(tbl_level_pending.id) as approved
                            from tbl_level_pending 
                            join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_level_pending.apply_connection_id
                            where tbl_level_pending.verification_status = 1 
                                and tbl_level_pending.status = 1 
                                and tbl_level_pending.receiver_user_type_id = 16 
                                and tbl_level_pending.send_date = '$currentDate'
                                AND tbl_apply_water_connection.ward_mstr_id = $wardId            
            ";
            $approved = $this->db->query($sql_approved)->getFirstRow("array");

            $sql_disconnect ="select 
                                count(consumer_id) as deactivate
                            from tbl_consumer_deactivation 
                            where deactivation_date  ='$currentDate'
                                AND ward_mstr_id = $wardId       
            ";
            $disconnect = $this->db->query($sql_disconnect)->getFirstRow("array");

            $token = $response["access_token"];
            $userInfo=$response["UserRequest"];
            $data = [
                "RequestInfo" => [
                    "apiId" => "asset-services",
                    "ver" => null,
                    "ts" => null,
                    "action" => null,
                    "did" => null,
                    "key" => null,
                    "msgId" => date("d-m-Y",strtotime($currentDate)),
                    "authToken" => $token,
                    "userInfo" => $userInfo
                ],
                "Data" => [
                    [
                        "date" => date("d-m-Y",strtotime($currentDate)),
                        "module" => "WS",
                        "ward" => $ward["ward_no"],
                        "ulb" => "jh.ranchimunicipalcorporation",
                        "region" => "Ranchi",
                        "state" => "Jharkhand",
                        "metrics" => [
                            "transactions" => $tran["tran_count"]??0, 
                            "connectionsCreated" => [
                               [
                                    "groupBy" => "connectionType", 
                                    "buckets" => [
                                        [
                                            "name" => "WATER.METERED", 
                                            "value" => $connection_type["meter_connection"]??0,
                                        ], 
                                        [
                                            "name" => "WATER.NONMETERED", 
                                            "value" => $connection_type["fixed_connection"]??0,
                                        ], 
                                        [
                                            "name" => "SEWERAGE", 
                                            "value" => 0 
                                        ] 
                                    ] 
                               ] 
                            ], 
                            "todaysCollection" => [
                                [
                                    "groupBy" => "usageType", 
                                    "buckets" => [
                                        [
                                            "name" => "Residential", 
                                            "value" => $tran["resident_paid_amount"]??0,
                                        ], 
                                        [
                                                "name" => "Commercial", 
                                                "value" => $tran["comercial_paid_amount"]??0, 
                                        ], 
                                        [
                                                "name" => "Institutional", 
                                                "value" => $tran["institutional_paid_amount"]??0, 
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "paymentChannelType", 
                                    "buckets" => [
                                        [
                                            "name" => "Digital", 
                                            "value" =>  $tran["digital"]??0,  
                                        ], 
                                        [
                                                "name" => "Non Digital", 
                                                "value" => $tran["non_digital"]??0 ,
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "taxHeads", 
                                    "buckets" => [
                                        [
                                            "name" => "INTEREST", 
                                            "value" => $tran["total_penalty_amt"]??0 , 
                                        ], 
                                        [
                                            "name" => "LATE.CHARGES", 
                                            "value" => 0 
                                        ], 
                                        [
                                            "name" => "ADVANCE", 
                                            "value" => 0 
                                        ], 
                                        [
                                            "name" => "CURRENT.CHARGES", 
                                            "value" => $collection["current_collection"]??0, 
                                        ], 
                                        [
                                            "name" => "ARREAR.CHARGES", 
                                            "value" => $collection["arrear_collection"]??0, 
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "connectionType", 
                                    "buckets" => [
                                        [
                                            "name" => "WATER.METERED", 
                                            "value" =>  $collection["meter_collection"]??0,  
                                        ], 
                                        [
                                            "name" => "WATER.NONMETERED", 
                                            "value" => $collection["fixed_collection"]??0,  
                                        ], 
                                        [
                                            "name" => "SEWERAGE", 
                                            "value" => 0, 
                                        ] 
                                    ] 
                                ] 
                            ], 
                            "sewerageConnections" => [
                                [
                                    "groupBy" => "channelType", 
                                    "buckets" => [
                                        [
                                        "name" => "ONLINE", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "CSC", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "SYSTEM", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "COUNTER", 
                                        "value" => 0 
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "usageType", 
                                    "buckets" => [
                                        [
                                        "name" => " Mixed Use", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "Commercial", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "Residential", 
                                        "value" => 0 
                                        ], 
                                        [
                                        "name" => "Institutional", 
                                        "value" => 0 
                                        ] 
                                    ] 
                                ] 
                            ], 
                            "waterConnections" => [
                                [
                                    "groupBy" => "channelType", 
                                    "buckets" => [
                                        [
                                            "name" => "COUNTER", 
                                            "value" =>  $applyconnection["counter_app"]??0,  
                                        ], 
                                        [
                                            "name" => "ONLINE", 
                                            "value" => $applyconnection["online_app"]??0,   
                                        ], 
                                        [
                                            "name" => "CSC", 
                                            "value" => 0 
                                        ], 
                                        [
                                            "name" => "SYSTEM", 
                                            "value" => 0 
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "usageType", 
                                    "buckets" => [
                                        [
                                            "name" => "Residential", 
                                            "value" => $applyconnection["resident_app"]??0,    
                                        ], 
                                        [
                                            "name" => "Commercial", 
                                            "value" =>  $applyconnection["comercial_app"]??0,   
                                        ] 
                                    ] 
                                ], 
                                [
                                    "groupBy" => "meterType", 
                                    "buckets" => [
                                        [
                                            "name" => "METERED", 
                                            "value" => 0 
                                        ], 
                                        [
                                            "name" => "NON.METERED", 
                                            "value" => 0 
                                        ] 
                                    ] 
                                ] 
                            ], 
                            "pendingConnections" => [
                                [
                                    "groupBy" => "duration", 
                                    "buckets" => [
                                        [
                                        "name" => "0to3Days", 
                                        "value" => $pending_application["lest_than_3"]??0, 
                                        ], 
                                        [
                                        "name" => "3to7Days", 
                                        "value" => $pending_application["lest_than_7"]??0, 
                                        ], 
                                        [
                                        "name" => "7to15Days", 
                                        "value" => $pending_application["lest_than_15"]??0, 
                                        ], 
                                        [
                                        "name" => "MoreThan15Days", 
                                        "value" => $pending_application["more_than_15"]??0, 
                                        ] 
                                    ] 
                                ] 
                            ], 
                            "slaCompliance" => 0, 
                            "todaysTotalApplications" => $applyconnection["total_apply_application"]??0,
                            "todaysClosedApplications" => $deactivate["deactivate"]??0,
                            "todaysCompletedApplicationsWithinSLA" => 0, 
                            "pendingApplicationsBeyondTimeline" => 0, 
                            "avgDaysForApplicationApproval" => $approved["approved"]??0, 
                            "StipulatedDays" => 0 
                         ],
                    ]
                ]
            ];
            $data = $this->convertInt($data);
            $data["Data"][0]["ward"]=" ".$ward["ward_no"];
            
            $url = "https://upyog-test.niua.org/national-dashboard/metric/_ingest";
            $ch = curl_init($url);

            // Setup request to send JSON via POST
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);

            // Execute the request and fetch the response
            $responseData = curl_exec($ch);

            // Check for errors
            $is_success = 1;
            if(!isset(json_decode($responseData,true)["responseHash"])){
                $is_success = 0;
            }
            echo "\n\n\n\n response: $wardId \n\n\n";
            print_var($responseData);
            echo "\n";
            $insertSql = "INSERT INTO tbl_central_dashboard_data(ward_id, user_login_json, push_data_json,response_json,is_success)
                values( $wardId, '".json_encode($response)."', '".json_encode($data)."','".$responseData."', $is_success::bool)";
            $this->db->query($insertSql);

            // Close cURL session
            curl_close($ch);
        }
    }

    public function convertInt(array $data){
        return $response = array_map(function($val){
            if(is_array($val) || is_object($val)){
                return $this->convertInt($val);
            }
            return is_numeric($val) ? (int) ($val) : $val;
        },$data);
    }

    #========end api for centralDashboard==============

    //================ saf auto approved code =========
    public function logingAsSiSystem(){
        $session = session();
        {
            $this->db_system = db_connect(dbConfig("system"));
            $sql="select id, user_type_mstr_id,user_mstr_id, emp_name, personal_phone_no, photo_path, email_id from tbl_emp_details Order By id ASC LIMIT 1";
            $emp_details = $this->db_system->query($sql)->getResultArray()[0];
            $emp_details["id"] =0; 
            $emp_details["user_mstr_id"]=0;
            $emp_details["user_type_mstr_id"]=9;
            $emp_details["token "]= date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
            $emp_details['ulb_list'] = [0=>[
                    "ulb_permission_id"=>1,
                    "ulb_mstr_id"=>1,
                    "ulb_name"=>"Ranchi Municipal Corporation",
                    "short_ulb_name"=>"RMC"
                ]
            ];
            $session->set('emp_details', $emp_details);
            $session->set('ulb_dtl', getUlbDtl());    			
        }
    }

    public function logingAsEoSystem(){
        $session = session();
        {
            $this->db_system = db_connect(dbConfig("system"));
            $sql="select id, user_type_mstr_id,user_mstr_id, emp_name, personal_phone_no, photo_path, email_id from tbl_emp_details Order By id ASC LIMIT 1";
            $emp_details = $this->db_system->query($sql)->getResultArray()[0];
            $emp_details["id"] =0; 
            $emp_details["user_mstr_id"]=0;
            $emp_details["user_type_mstr_id"]=10;
            $emp_details["token "]= date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
            $emp_details['ulb_list'] = [0=>[
                    "ulb_permission_id"=>1,
                    "ulb_mstr_id"=>1,
                    "ulb_name"=>"Ranchi Municipal Corporation",
                    "short_ulb_name"=>"RMC"
                ]
            ];
            $session->set('emp_details', $emp_details);
            $session->set('ulb_dtl', getUlbDtl());    			
        }
    }

    public function propertyAutoApproved(){
        
        $this->logingAsSiSystem();
        $db = db_connect(dbConfig("property"));
        $db_system = db_connect(dbSystem());
        $objEo_Saf = new \App\Controllers\EO_SAF () ;
        $objUTC = new \App\Controllers\SafVerification();
        $objSi = new \App\Controllers\si_saf();
        $flag = $db_system->query("select * from site_maintenance")->getFirstRow("array");
		
        $with_sql ="with saf_com as (
                    select tbl_saf_dtl.id
                    from tbl_field_verification_dtl
                    join tbl_saf_dtl ON tbl_saf_dtl.id = tbl_field_verification_dtl.saf_dtl_id
                    where 
                        (
                            tbl_saf_dtl.prop_type_mstr_id != tbl_field_verification_dtl.prop_type_mstr_id
                            OR tbl_saf_dtl.road_type_mstr_id != tbl_field_verification_dtl.road_type_mstr_id
                            OR tbl_saf_dtl.area_of_plot != tbl_field_verification_dtl.area_of_plot
                            OR tbl_saf_dtl.ward_mstr_id != tbl_field_verification_dtl.ward_mstr_id
                            OR tbl_saf_dtl.is_mobile_tower != tbl_field_verification_dtl.is_mobile_tower
                            OR tbl_saf_dtl.tower_area != tbl_field_verification_dtl.tower_area
                            OR tbl_saf_dtl.tower_installation_date != tbl_field_verification_dtl.tower_installation_date
                            OR tbl_saf_dtl.is_hoarding_board != tbl_field_verification_dtl.is_hoarding_board
                            OR tbl_saf_dtl.hoarding_area != tbl_field_verification_dtl.hoarding_area
                            OR tbl_saf_dtl.hoarding_installation_date != tbl_field_verification_dtl.hoarding_installation_date
                            OR tbl_saf_dtl.is_petrol_pump != tbl_field_verification_dtl.is_petrol_pump
                            OR tbl_saf_dtl.under_ground_area != tbl_field_verification_dtl.under_ground_area
                            OR tbl_saf_dtl.petrol_pump_completion_date != tbl_field_verification_dtl.petrol_pump_completion_date
                            OR tbl_saf_dtl.is_water_harvesting != tbl_field_verification_dtl.is_water_harvesting
                            OR tbl_saf_dtl.zone_mstr_id != tbl_field_verification_dtl.zone_mstr_id
                        )
                    AND tbl_field_verification_dtl.verified_by = 'AGENCY TC'
                    AND tbl_saf_dtl.area_of_plot<5000
                    AND tbl_saf_dtl.assessment_type !='Mutation'  
                ),
                extra_floor_added as(
                    select distinct tbl_field_verification_floor_details.saf_dtl_id
                    from tbl_field_verification_floor_details
                    JOIN tbl_field_verification_dtl 
                        on tbl_field_verification_dtl.id = tbl_field_verification_floor_details.field_verification_dtl_id 
                    WHERE tbl_field_verification_dtl.verified_by = 'AGENCY TC' 
                        AND tbl_field_verification_floor_details.saf_floor_dtl_id =0
                ),
                floor_com as(
                    select distinct tbl_field_verification_floor_details.saf_dtl_id
                    from tbl_field_verification_floor_details
                    join tbl_saf_floor_details on tbl_saf_floor_details.id = tbl_field_verification_floor_details.saf_floor_dtl_id
                    JOIN tbl_field_verification_dtl on tbl_field_verification_dtl.id = tbl_field_verification_floor_details.field_verification_dtl_id
                    left join saf_com on saf_com.id = tbl_field_verification_floor_details.saf_dtl_id
                    where saf_com.id is null AND tbl_field_verification_dtl.verified_by = 'AGENCY TC'
                        AND(
                            tbl_field_verification_floor_details.floor_mstr_id != tbl_saf_floor_details.floor_mstr_id
                            OR tbl_field_verification_floor_details.usage_type_mstr_id != tbl_saf_floor_details.usage_type_mstr_id
                            OR tbl_field_verification_floor_details.const_type_mstr_id != tbl_saf_floor_details.const_type_mstr_id
                            OR tbl_field_verification_floor_details.occupancy_type_mstr_id != tbl_saf_floor_details.occupancy_type_mstr_id
                            OR tbl_field_verification_floor_details.builtup_area != tbl_saf_floor_details.builtup_area
                            OR tbl_field_verification_floor_details.date_from != tbl_saf_floor_details.date_from
                            OR tbl_field_verification_floor_details.date_upto != tbl_saf_floor_details.date_upto
                        )
                ) 
        ";

		$select_sql = ' select tbl_level_pending_dtl.id,tbl_level_pending_dtl.saf_dtl_id,tbl_level_pending_dtl.receiver_user_type_id, 
							tbl_level_pending_dtl.sender_emp_details_id, tbl_saf_dtl.assessment_type';

		$from_sql =" from tbl_level_pending_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
                    left join (
                        select distinct(id)
                        from(
                            select id
                            from saf_com
                            union all(
                                select saf_dtl_id as id
                                from extra_floor_added
                            )
                            union all(
                                select saf_dtl_id as id
                                from floor_com
                            )
                        )tests
                    )variation_saf on variation_saf.id = tbl_saf_dtl.id
                    WHERE receiver_user_type_id=7 and variation_saf.id is null
                        AND tbl_level_pending_dtl.verification_status=0
                        AND doc_upload_status=1
                        AND tbl_level_pending_dtl.status=1
                        AND (tbl_saf_dtl.area_of_plot * 435.56) < 5000
                        AND tbl_saf_dtl.assessment_type not in ('Mutation','Mutation with Reassessment','Reassessment')
                        AND tbl_level_pending_dtl.created_on::date = current_date 
                        ";
        $count_sql = $with_sql . 'select count(tbl_level_pending_dtl.id) as counts '.$from_sql; 
              
        $counts = $db->query($count_sql)->getFirstRow("array")["counts"]??0;    
        $limits= round( $counts - ($counts*0.1)) ;
        $record_sql = $with_sql . $select_sql . $from_sql ." order by tbl_level_pending_dtl.id ASC limit $limits";
        
        $data = $db->query($record_sql)->getResultArray();
        $totalSaf = sizeof($data);

        $currentEo = $db->query("select * from view_emp_details where user_type_id=10 and user_mstr_lock_status=0 order by id DESC")->getFirstRow("array");
        $currentSH = $db->query("select * from view_emp_details where user_type_id=9 and user_mstr_lock_status=0 order by id DESC")->getFirstRow("array");
        
        echo" autoforward  [$totalSaf] \n";
        // foreach($data as $key=>$val){
        //     echo("\n".$key."=======>".$val["saf_dtl_id"]."====> assesment Type ===>".$val["assessment_type"]."\n");
        //     $testSaf = $db->query("select id from tbl_saf_dtl where status=1 and saf_pending_status!=1 and id=".$val["saf_dtl_id"])->getFirstRow("array");
        //     if(!$testSaf){
        //         continue;
        //     }
        //     $db->transBegin();

        //     try{
        //         $insertSql = "INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
        //                         VALUES(".$val["id"].",".$val["saf_dtl_id"].",".$val["receiver_user_type_id"].",9,".$totalSaf.",'90% aouto approved')";
        //         $db->query($insertSql)->getResultArray();
        //         $result = $db->query("select utc_forward_assistant_new(CURRENT_DATE,".$val["id"].")")->getResultArray();
                
        //         if($result){                    
        //             $lastLevel = ($db->query("SELECT * FROM tbl_level_pending_dtl where saf_dtl_id =".$val["saf_dtl_id"]." order by id DESC limit 1")->getFirstRow("array"));
        //             $this->logingAsSiSystem();
        //             $reciverRole = 10;
        //             if($val["assessment_type"]=="Reassessment"){
        //                 $reciverRole=null;
        //             }
                    
        //             $insertSqlSi = " INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
        //                         VALUES(".$lastLevel["id"].",".$lastLevel["saf_dtl_id"].",".$lastLevel["receiver_user_type_id"].",".($reciverRole ? $reciverRole : "null").",".$totalSaf.",'90% aouto approved')";
                    
        //             $db->query($insertSqlSi)->getResultArray();
        //             $result = $objSi->view_new(md5($val["saf_dtl_id"]));

        //             if($currentSH){
        //                 $db->query("update tbl_saf_memo_dtl set emp_details_id=".$currentSH["id"]." Where saf_dtl_id=".$val["saf_dtl_id"]." and memo_type='FAM' and emp_details_id =0 and created_on::date = current_date")->getResultArray();
        //             }
        //             $result = $result===true?true:false;
        //         }
        //         if($result && $val["assessment_type"]!="Reassessment"){
        //             $lastLevel = ($db->query("SELECT * FROM tbl_level_pending_dtl where saf_dtl_id =".$val["saf_dtl_id"]." order by id DESC limit 1")->getFirstRow("array"));
        //             $this->logingAsEoSystem();
        //             $insertSqlEo = "INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
        //                         VALUES(".$lastLevel["id"].",".$lastLevel["saf_dtl_id"].",".$lastLevel["receiver_user_type_id"].",null,".$totalSaf.",'90% aouto approved')";
        //             $db->query($insertSqlEo)->getResultArray();
        //             $result = $objEo_Saf->view_new2(md5($val["saf_dtl_id"]));
        //             if($currentEo){
        //                 $db->query("update tbl_saf_memo_dtl set emp_details_id=".$currentEo["id"]." Where saf_dtl_id=".$val["saf_dtl_id"]." and memo_type='FAM' and emp_details_id =0 and created_on::date = current_date")->getResultArray();
        //             }
        //             $result = $result===true?true:false;
        //         }
        //         if($result && $db->transStatus() === TRUE){
        //             echo("commit \n");
        //             // $db->transRollback();
        //             $db->transCommit();
        //         }else{
        //             echo("rollback \n");
        //             $db->transRollback();
        //         }
        //     }catch(Exception $e){
        //         echo("error auto \n");
        //         $db->transRollback();
        //     }
        // }

        $record_sql=[];
        $record_sql[]="
            select tbl_level_pending_dtl.id,tbl_level_pending_dtl.saf_dtl_id,tbl_level_pending_dtl.receiver_user_type_id, 
                    tbl_level_pending_dtl.sender_emp_details_id, tbl_saf_dtl.assessment_type
            from tbl_level_pending_dtl
            INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
            left join(
                select distinct tbl_field_verification_dtl.saf_dtl_id as id
                from tbl_field_verification_dtl
                join tbl_force_auto_forward ON tbl_force_auto_forward.saf_dtl_id = tbl_field_verification_dtl.saf_dtl_id
                where tbl_force_auto_forward.created_on::date=current_date
                    AND tbl_field_verification_dtl.verified_by = 'AGENCY TC'
            )variation_saf on variation_saf.id = tbl_saf_dtl.id
            WHERE 
                receiver_user_type_id in (7) and variation_saf.id is not null 
                AND tbl_level_pending_dtl.verification_status=0
                AND doc_upload_status=1
                AND tbl_level_pending_dtl.status=1
                
                and ( tbl_saf_dtl.id in (select saf_dtl_id from tbl_force_auto_forward where created_on::date=current_date and status=1)) 
                ORDER BY tbl_level_pending_dtl.id DESC
        ";
        if($flag["utc_btc_approve"]=="t"){
            $record_sql[]="
                SELECT 
                    tbl_level_pending_dtl.id,tbl_level_pending_dtl.saf_dtl_id,tbl_level_pending_dtl.receiver_user_type_id, 
                    tbl_level_pending_dtl.sender_emp_details_id, tbl_saf_dtl.assessment_type,tbl_saf_dtl.saf_pending_status
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id  	
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN 
                ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE 
                    tbl_saf_dtl.status=1 
                    and tbl_saf_dtl.saf_pending_status =2
                    AND tbl_level_pending_dtl.verification_status='2'
                    AND tbl_level_pending_dtl.receiver_user_type_id=11
                    AND tbl_level_pending_dtl.status='1' 
                    AND tbl_level_pending_dtl.sender_user_type_id=7
            ";
		}


        // if($_REQUEST["orderBY"] && $_REQUEST["orderBY"]%2==0){
        //     $record_sql.=" ORDER BY tbl_saf_dtl.id ASC ";
        // }else{
        //     $record_sql.=" ORDER BY tbl_saf_dtl.id DESC ";
        // }
        foreach($record_sql as $rowsq){
            $data = $db->query($rowsq)->getResultArray();
            $totalSaf = sizeof($data);
            echo" Force autoforward =====$totalSaf====== \n";
            if($totalSaf==0){
                return true;
            }
            foreach($data as $key=>$val){
                echo("\n".$key."=======>".$val["saf_dtl_id"]."====> assesment Type ===>".$val["assessment_type"]."\n");
                $testSaf = $db->query("select id from tbl_saf_dtl where status=1 and saf_pending_status!=1 and id=".$val["saf_dtl_id"])->getFirstRow("array");
                if(!$testSaf){
                    continue;
                }
                $db->transBegin();

                if($flag["utc_btc_approve"]=="t"){
                    $tcVerification = $db->query("select * from tbl_field_verification_dtl where saf_dtl_id = ".$val["saf_dtl_id"]." and status=1 and verified_by='AGENCY TC'")->getFirstRow("array");
                    if($tcVerification){
                        $updateData = [];
                        if(!$tcVerification["zone_mstr_id"]){
                            $updateData["zone_mstr_id"]=$testSaf["zone_mstr_id"];
                        }if(!$tcVerification["ward_mstr_id"]){
                            $updateData["ward_mstr_id"]=$testSaf["ward_mstr_id"];
                        }
                        if($updateData){
                            $db->table("tbl_field_verification_dtl")->where("id",$tcVerification["id"])->update($updateData);
                        }
                    }
                }

                try{
                    $insertSql1 = "INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
                                    VALUES(".$val["id"].",".$val["saf_dtl_id"].",".$val["receiver_user_type_id"].",9,".$totalSaf.",'force approved')";
                    $db->query($insertSql1)->getResultArray();
                    $result = $db->query("select utc_forward_assistant_new(CURRENT_DATE,".$val["id"].")")->getResultArray();
                    echo"tc";
                    print_Var($result);
                    if($result){   
                        $test = $db->query("SELECT * FROM tbl_level_pending_dtl where saf_dtl_id =".$val["saf_dtl_id"]." order by id DESC limit 1")->getFirstRow("array");
                        if($test["receiver_user_type_id"]!=9){
                            $sqlIn = "INSERT INTO tbl_level_pending_dtl	
                                        (
                                            saf_dtl_id, sender_user_type_id, receiver_user_type_id, created_on, remarks, sender_emp_details_id,
                                            forward_date,forward_time
                                        )
                                        VALUES
                                        (
                                            ".$test['saf_dtl_id'].",7,9,current_date,'UTC Auto Forward',0,
                                            current_date,current_time
                                        ) 
                                        ";
                            $db->query($sqlIn)->getResult();
                        }                 
                        $lastLevel = ($db->query("SELECT * FROM tbl_level_pending_dtl where saf_dtl_id =".$val["saf_dtl_id"]." order by id DESC limit 1")->getFirstRow("array"));
                        $this->logingAsSiSystem();
                        $reciverRole = 10;
                        if($val["assessment_type"]=="Reassessment"){
                            $reciverRole=null;
                        }
                        $insertSql1Si = "INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
                                    VALUES(".$lastLevel["id"].",".$lastLevel["saf_dtl_id"].",".$lastLevel["receiver_user_type_id"].",".($reciverRole ? $reciverRole : "null").",".$totalSaf.",'force approved')";
                        $db->query($insertSql1Si)->getResultArray();
                        $result = $objSi->view_new(md5($val["saf_dtl_id"]));
                        if($currentSH){
                            $db->query("update tbl_saf_memo_dtl set emp_details_id=".$currentSH["id"]." Where saf_dtl_id=".$val["saf_dtl_id"]." and memo_type='FAM' and emp_details_id =0 and created_on::date = current_date")->getResultArray();
                        }
                        $result = $result===true?true:false;
                        echo"SH";
                        print_Var($result);
                    }
                    if($result && $val["assessment_type"]!="Reassessment"){
                        $lastLevel = ($db->query("SELECT * FROM tbl_level_pending_dtl where saf_dtl_id =".$val["saf_dtl_id"]." order by id DESC limit 1")->getFirstRow("array"));
                        $this->logingAsEoSystem();
                        $insertSql1Eo = "INSERT INTO tbl_saf_auto_forwards_log(from_level_id,saf_id,forward_from_user_type_id,forward_to_user_type_id,toltal_forward_saf_count,forward_type)
                                    VALUES(".$lastLevel["id"].",".$lastLevel["saf_dtl_id"].",".$lastLevel["receiver_user_type_id"].",null,".$totalSaf.",'force approved')";
                        $db->query($insertSql1Eo)->getResultArray();
                        $result = $objEo_Saf->view_new2(md5($val["saf_dtl_id"]));

                        if($currentEo){
                            $db->query("update tbl_saf_memo_dtl set emp_details_id=".$currentEo["id"]." Where saf_dtl_id=".$val["saf_dtl_id"]." and memo_type='FAM' and emp_details_id =0 and created_on::date = current_date")->getResultArray();
                        }
                        $result = $result===true?true:false;
                        echo"EO";
                        print_Var($result);
                    }
                    if($result && $db->transStatus() === TRUE){
                        echo("commit");
                        $db->transCommit();
                    }else{
                        $db->transRollback();
                        echo("\nrollback");
                        // print_Var($val);die;
                    }
                }catch(Exception $e){
                    $db->transRollback();
                    echo("\n error \n");
                    // print_Var($e);die;
                }
            }

        }
        echo "\n\n success";
    }

    //================ end saf auto approved code =========



    // API FOR THIRD PARTY PAYMENT(INDUS 29-04-2025)

    public function getPropertyDemand()
    {
        $this->propDBConn();
        $flag = $this->db_system->table("site_maintenance")->get()->getFirstRow("array");
        $whatsapp_payment = ($flag && $flag["whatsapp_payment"]=="t")?true:false;
        try {
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->fail('Method not allowed', 405);
            }

            $inputs = $this->request->getJSON(true);
            if (empty($inputs['holding_no']) && empty($inputs['new_holding_no'])) {
                return $this->fail('Invalid Key !!', 400);
            }

            $sql = "SELECT * FROM tbl_prop_dtl WHERE new_holding_no = :new_holding_no: OR holding_no = :holding_no:";
            $query = $this->db_property->query($sql, [
                'new_holding_no' => $inputs['new_holding_no'] ?? null,
                'holding_no' => $inputs['holding_no'] ?? null
            ]);

            $result = $query->getFirstRow();

            $propDtlId = $result->id;

            if (!$result) {
                return $this->failNotFound('No records found for the given holding number.');
            } elseif ($result->status != 1) {
                return $this->fail('Holding is not active.', 403);
            }

            $query = $this->db_property->query("SELECT get_prop_full_details($propDtlId) as demand_details");
            $demandData = $query->getFirstRow("array")["demand_details"] ?? [];
            $demandData = (json_decode($demandData, true));
            if ($demandData["demand_detail"]) {
                $maxFyearItem = array_reduce($demandData["demand_detail"], function ($carry, $item) {
                    if (!$carry || (int) explode('-', $item['fy'])[1] > (int) explode('-', $carry['fy'])[1]) {
                        return $item;
                    }
                    return $carry;
                });

                $maxFyear = $maxFyearItem['fy'];
                # Step 2: Filter data for maxFyear
                $filteredData = array_filter($demandData["demand_detail"], function ($item) use ($maxFyear) {
                    return $item['fy'] === $maxFyear;
                });

                # Step 3: Get max qtr from filtered data
                $maxQtr = max(array_column($filteredData, 'qtr'));
            }
            $demandData["DuesYear"] = $this->modeldemand->geDuesYear($propDtlId);
            $inputData = [
                'fy' => $demandData["DuesYear"]["max_year"] ?? "",
                'qtr' => $demandData["DuesYear"]["max_quarter"] ?? "",
                'prop_dtl_id' => $propDtlId,
                'user_id' => 0,
            ];
            $redirectUrl = base_url('AmurtMisPortal/propertyPaymentSuccess/' . $propDtlId);
            $cancelUrl = base_url('AmurtMisPortal/propertyPaymentFailed/' . $propDtlId);
            $demandData["order_id"] = "";
            $demandData["redirect_url"] = $redirectUrl;
            $demandData["failure_url"] = $cancelUrl;
            $demandData["payableBiffurcation"] = $this->modeldemand->getPropDemandAmountDetails($inputData);
            if($whatsapp_payment && (($demandData["payableBiffurcation"]["PayableAmount"]??0) > 0)){
                $demandData["payableBiffurcation"]["PayableAmount"]=0.00;
            }
            if (($demandData["payableBiffurcation"]["PayableAmount"]??0) > 0) {
                $orderId = $this->getOderId(1)["orderId"];
                $input = [
                    "order_id" => $orderId,
                    "merchant_id" => null,
                    "prop_dtl_id" => $propDtlId,
                    "module" => "Property",
                    "from_fy_mstr_id" => $demandData["DuesYear"]["min_fy_id"],
                    "from_fy" => $demandData["DuesYear"]["min_year"],
                    "from_qtr" => $demandData["DuesYear"]["min_quarter"],
                    "upto_fy_mstr_id" => $demandData["DuesYear"]["max_fy_id"],
                    "upto_fy" => $demandData["DuesYear"]["max_year"],
                    "upto_qtr" => $demandData["DuesYear"]["max_quarter"],
                    "demand_amt" => $demandData["payableBiffurcation"]["DemandAmount"],
                    "penalty_amt" => ($demandData["payableBiffurcation"]["OnePercentPnalty"] + $demandData["payableBiffurcation"]["OtherPenalty"] + ($demandData["payableBiffurcation"]["noticePenalty"] ?? 0)),
                    "discount" => ($demandData["payableBiffurcation"]["RebateAmount"] + $demandData["payableBiffurcation"]["AdvanceAmount"]),
                    "payable_amt" => round($demandData["payableBiffurcation"]["PayableAmount"]),
                    "redirect_url" => $redirectUrl,
                    "failure_url" => $cancelUrl,
                    "status" => 1

                ];
                $demandData["pg_mas_id"] = $this->model_third_party_pay_request->pay_request($input);
                $demandData["order_id"] = $orderId;
            }

            return $this->respond([
                'message' => 'Holding Details Fetched successfully!',
                'result' => $demandData

            ], 200);

        } catch (\Throwable $th) {
            return $this->failServerError('An error occurred: ' . $th->getMessage());
        }
    }

    public function propertyPaymentSuccess($prop_dtl_id)
    {
        $this->propDBConn();

        if ($this->request->getMethod(true) !== 'POST') {
            return $this->fail('Method not allowed', 405);
        }

        try {
            $decript = $this->request->getJSON(true);
            if (empty($decript['order_id'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['order_status'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
                return $this->fail('Invalid amount !!', 400);
            }

            $order_status = $decript["order_status"];
            if ($order_status != 'Success') {
                return $this->propertyPaymentFailed($prop_dtl_id);
            }

            $where = [
                "order_id" => $decript["order_id"],
                "prop_dtl_id" => $prop_dtl_id,
                "module" => 'Property'
            ];

            $request = $this->model_third_party_pay_request->getRecord($where);

            if (!$request || round($decript['amount']) != round($request['payable_amt'])) {
                throw new Exception("Payment order Id or amount is not matching");
            }

            $input = [
                "request_id" => $request['id'],
                "prop_dtl_id" => $request['prop_dtl_id'],
                "module" => $request['module'],
                "payable_amt" => $request["payable_amt"],
                "ip_address" => get_client_ip(),
                "merchant_id" => $request["merchant_id"],
                "order_id" => $request["order_id"],
                "tracking_id" => $decript["tracking_id"] ?? null,
                "bank_ref_no" => $decript["bank_ref_no"] ?? null,
                "error_code" => $decript["status_code"] ?? null,
                "error_desc" => $decript['failure_message']??null,
                "error_source" => null,
                "error_step" => null,
                "error_reason" => $decript['status_message']??null,
                "respons_data" => json_encode($decript),
                "status" => 1
            ];
            $data = [
                "prop_dtl_id" => $request['prop_dtl_id'],
                "fy" => $request["upto_fy"],
                "qtr" => $request["upto_qtr"],
                "user_id" => 0,
                "payment_mode" => "Online",
                "remarks" => null,
                "total_payable_amount" => $request["payable_amt"],
            ];

            $this->db_property->transBegin();
            $trxn_id = $this->model_transaction->prop_pay_now($data, []);
            $input["tran_id"] = $trxn_id;
            $this->model_third_party_pay_response->pay_response($input);

            if ($this->db_property->transStatus() === FALSE) {
                $this->db_property->transRollback();
                throw new Exception("Some error occured, Transaction process has been rollback!!!");
            } else {
                $this->db_property->transCommit();
                $message = "Order No.: " . $request["order_id"] . ", Amount: " . $request["payable_amt"];
                return $this->respond([
                    'message' => $message . "Transaction successfull !!!",
                    'result' => base_url('CitizenProperty/citizen_payment_receipt/' . md5($trxn_id))
                ], 200);
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server An error occurred: ' . $e->getMessage());
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function propertyPaymentFailed($prop_dtl_id)
    {
        $this->propDBConn();
        if ($this->request->getMethod(true) !== 'POST') {
            return $this->fail('Method not allowed', 405);
        }
        $decript = $this->request->getJSON(true);
        if (empty($decript['order_id'])) {
            return $this->fail('Invalid order_id !!', 400);
        }
        if (empty($decript['order_status'])) {
            return $this->fail('Invalid order_status !!', 400);
        }
        if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
            // return $this->fail('Invalid amount !!', 400);
        }
        $order_status = $decript["order_status"];
        if ($order_status == 'Success') {
            return $this->propertyPaymentSuccess($prop_dtl_id);
        }

        $where = [
            "order_id" => $decript["order_id"],
            "prop_dtl_id" => $prop_dtl_id,
            "module" => 'Property'
        ];
        $request = $this->model_third_party_pay_request->getRecord($where);
        $input = [
            "request_id" => $request['id'],
            "prop_dtl_id" => $request['prop_dtl_id'],
            "module" => $request['module'],
            "payable_amt" => $request["payable_amt"],
            "ip_address" => get_client_ip(),
            "merchant_id" => $request["merchant_id"],
            "order_id" => $request["order_id"],
            "tracking_id" => $decript["tracking_id"]??null,
            "bank_ref_no" => $decript["bank_ref_no"]??null,
            "error_code" => $decript["status_code"]??null,
            "error_desc" => $decript['failure_message']??null,
            "error_source" => null,
            "error_step" => null,
            "error_reason" => $decript['status_message']??null,
            "respons_data" => json_encode($decript),
            "status" => 1
        ];
        $this->model_third_party_pay_response->pay_response($input);

        if ($request["module"] == "Property") {
            return $this->respond([
                'message' => "Oops, Something went wrong, payment failed",
                'result' => base_url('CitizenProperty/Citizen_confirm_payment/' . md5($prop_dtl_id))
            ], 200);
        } else {
            return $this->respond([
                'message' => "Oops, Something went wrong, payment failed",
                'result' => base_url('CitizenDtl/citizen_saf_payment_details')
            ], 200);
        }
    }


    public function getWaterDemand()
    {
        try {
           
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->fail('Method not allowed', 405);
            }
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->fail('Method not allowed', 405);
            }

            $inputs = $this->request->getJSON(true);
            $consumer_no = $inputs['consumer_no'];

            if (empty($inputs['consumer_no'])) {
                return $this->fail('Invalid Consumer Number', 400);
            }

            $this->connectWaterDBConn();
            $flag = $this->db_system->table("site_maintenance")->get()->getFirstRow("array");
            $whatsapp_payment = ($flag && $flag["whatsapp_payment"]=="t")?true:false;
            $sql = "SELECT id FROM tbl_consumer WHERE consumer_no = ?";
            $query = $this->db->query($sql, [$consumer_no]);
            $result = $query->getFirstRow();

            if (!$result) {
                return $this->failNotFound('Consumer not found !');
            }

            $consumer_id = $result->id;
            $md5_consumer_id = md5($consumer_id);

            if (!empty($consumer_id)) {
                
                $data['consumer_details'] = $this->model_view_water_consumer->waterConsumerDetailsById($md5_consumer_id);
                $data['consumer_owner_details'] = $this->consumer_details_model->consumerDetailsbyMd5($md5_consumer_id);

                if (!empty($data['consumer_details']) && !isset($data['consumer_details']['id'])) {
                    !empty($data['consumer_details']['id']) ? $this->consumer_demand_model->impose_penalty($data['consumer_details']['id']) : '';

                }

                $data['demand_list'] = $this->consumer_demand_model->due_demand($md5_consumer_id);

                if (!empty($data['demand_list']) && isset($data['demand_list'][0]['demand_from'])) {
                    $demand_from = $data['demand_list'][0]['demand_from'];
                } else {
                    $demand_from = null;
                }

                if (!empty($data['demand_list'])) {
                    $lastIndex = array_key_last($data['demand_list']);
                    $demand_upto = $data['demand_list'][$lastIndex]['demand_upto'] ?? null;
                } else {
                    $demand_upto = null;
                }

                $redirectUrl = base_url('AmurtMisPortal/ConsumerPaySuccess/' . $consumer_id);
                $cancelUrl = base_url('AmurtMisPortal/ConsumerPaymentFailed/' . $consumer_id);
                $data["order_id"] = "";
                $data["redirect_url"] = $redirectUrl;
                $data["failure_url"] = $cancelUrl;

                $data['payableDemand'] = $this->getPayableWaterDemand($consumer_id, $demand_upto, $demand_from);
                if($whatsapp_payment && (($data['payableDemand']['balance_amount']??0) > 0)){
                    $data['payableDemand']['balance_amount']=0.00;
                }
                if (($data['payableDemand']['balance_amount']??0) > 0) {

                    $orderId = $this->getOderId(2)["orderId"];
                    $input = [
                        "order_id" => $orderId,
                        "merchant_id" => null,
                        "ref_id" => $consumer_id,
                        "ref_tbl" => "tbl_consumer",
                        "payment_from" => 'Demand Collection',
                        "demand_ids" => $data["payableDemand"]["demand_from"] . "--" . $data["payableDemand"]["demand_upto"],
                        "amount" => $data["payableDemand"]["balance_amount"],
                        "error_reason" => null,
                        "redirect_url" => $redirectUrl,
                        "failiear_url" => $redirectUrl,
                        "ip_address" => $_SERVER['REMOTE_ADDR'],
                        "status" => 2,
                    ];
                    $data["pg_mas_id"] = $this->ModelThirdPartyOnlineRequest->insertData($input);

                    $data["order_id"] = $orderId;
                }

            }

            return $this->respond([
                'message' => 'Consumer Details Fetched successfully!',
                'result' => $data

            ], 200);

        } catch (\Throwable $th) {
            return $this->failServerError('An error occurred: ' . $th->getMessage());
        }

    }

    public function getPayableWaterDemand($consumer_id, $demand_upto, $demand_from)
    {
        $demand = $this->consumer_demand_model->getAmountPayable($consumer_id, $demand_upto, $demand_from);
        # cheque bounce charge
        $demand["other_penalty"] = $this->WaterPenaltyModel->getUnpaidPenaltySum(md5($consumer_id), 'Consumer');
        $demand["rebate"] = 0.00; // Not In Use
        $demand["balance_amount"] += $demand["other_penalty"];

        $out = ["message" => "", "status" => true, "data" => $demand];

        return $demand;
    }

    public function ConsumerPaySuccess($consumer_id)
    {
        $this->connectWaterDBConn();

        if ($this->request->getMethod(true) !== 'POST') {
            return $this->fail('Method not allowed', 405);
        }
        try {
            $MD5conn_id = md5($consumer_id);
            $decript = $this->request->getJSON(true);
            if (empty($decript['order_id'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['order_status'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
                return $this->fail('Invalid amount !!', 400);
            }

            $consumer_details = $this->model_view_water_consumer->waterConsumerDetailsById(md5($consumer_id));

            $app_details =$consumer_details;

            $order_status = $decript["order_status"];

            $request = $this->ModelThirdPartyOnlineRequest->getRequestDataByOderId($decript["order_id"]);

            if ($order_status != "Success") {
                return $this->ConsumerPaymentFailed($MD5conn_id);
            }
            if ((!$request) || (!$app_details) || ($request['ref_id'] != $app_details["id"]) || ($request['ref_tbl'] != "tbl_consumer")) {
                
                throw new Exception("Payment Faild due to Invalid Order No.");
            }

            $consumer_id = $request["ref_id"];

            $water_conn_id = md5($request["ref_id"]);

            $param = [
                "status" => 1
            ];

            $responseInput = [
                "request_id" => $request['id'],
                "order_id" => $request["order_id"],
                "merchant_id" => $request["merchant_id"],
                "ref_id" => $request["ref_id"],
                "ref_tbl" => $request["ref_tbl"],
                "amount" => $request["amount"],
                "tracking_id" => $decript["tracking_id"]??null,
                "bank_ref_no" => $decript["bank_ref_no"]??null,
                "error_code" => $decript["status_code"]??null,
                "error_desc" => $decript["error_desc"] ?? NULL,
                "error_source" => $decript["error_source"] ?? NULL,
                "error_step" => $decript["error_step"] ?? NULL,
                "error_reason" => $decript["status_message"]??null,
                "respons_data" => json_encode($this->request->getVar()),
                "ip_address" => $_SERVER['REMOTE_ADDR'],
                "status" => 1
            ];

            
            $penalty_details = $this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
            $rebate_details = $this->payment_model->get_rebate_details($consumer_id);

            $amount = $request['amount'];
            $penalty = $penalty_details['penalty'];
            $rebate = $rebate_details['rebate'];
            $consumer_id = $request['ref_id'];

            $period = explode("--", $request["demand_ids"]);
            $due_from = $period[0];       // demand from
            $month = $period[1];    // demand upto
            $ward_mstr_id = $consumer_details['ward_mstr_id'];

            $total_amount = $request['amount'];

            $arge['consumer_id'] = $consumer_id;
            $arge['demand_upto'] = $month;

            $getAmountPayable = $this->WaterUserChargeProceedPaymentCitizeController->getAmountPayable($arge);
            $getAmountPayable = json_decode($getAmountPayable, true);

            if ($getAmountPayable['status'] != true) {
                throw new Exception("Payable Amount Not Calculated Please Visit Nearest Branch. !!!");
            }
            if ((int) $getAmountPayable['data']['balance_amount'] != 0) {
                $data['amount'] = $getAmountPayable['data']['balance_amount'] - $getAmountPayable['data']['rebate'];
            } else {
                $data['amount'] = $getAmountPayable['data']['amount'] + $getAmountPayable['data']['penalty'] + $getAmountPayable['data']['other_penalty'] - $getAmountPayable['data']['rebate'];
            }

            if (round($data['amount']) != round($request['amount'])) {
                throw new Exception("Payable Amount Missmatch . !!!");
            }
            $penalty = $getAmountPayable['data']['penalty'] + $getAmountPayable['data']['other_penalty'];
            $rebate = $getAmountPayable['data']['rebate'];
            $paid_amount = $data['amount'];
            $total_amount = $getAmountPayable['data']['amount'];

            $trans_arr = array();
            $trans_arr['ward_mstr_id'] = $ward_mstr_id;
            $trans_arr['transaction_type'] = "Demand Collection";
            $trans_arr['transaction_date'] = date('Y-m-d');
            $trans_arr['related_id'] = $consumer_id;
            $trans_arr['payment_mode'] = 'Online';
            $trans_arr['penalty'] = $penalty;
            $trans_arr['rebate'] = $rebate;
            $trans_arr['paid_amount'] = $paid_amount;
            $trans_arr['total_amount'] = $total_amount;
            $trans_arr['emp_details_id'] = 0;
            $trans_arr['created_on'] = date('Y-m-d H:i:s');
            $trans_arr['status'] = 1;
            $trans_arr['from_month'] = $due_from;
            $trans_arr['upto_month'] = $month;

            $other = [];
            $other["other_penalty"] = $getAmountPayable['data']['other_penalty'];
            $other["demand_id"] = $getAmountPayable['data']["demand_id"];

            $this->db->transBegin();

            $transaction_id = $this->transaction_model->water_pay_now($trans_arr, $other);
            $update = $this->ModelThirdPartyOnlineRequest->updateData($request["id"], $param);
            $responseInput["tran_id"] = $transaction_id;
            $response_id = $this->model_third_party_pay_response->insertData($responseInput);

            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
                throw new Exception("Some error occured, Transaction process has been rollback!!!");
            } else {

                $this->db->transCommit();
                $message = "Order No.: " . $request["order_id"] . ", Amount: " . $request["amount"];
                return $this->respond([
                    'message' => $message . "Transaction successfull !!!",
                    'result' => base_url('WaterUserChargePaymentCitizen/payment_tc_receipt/' . md5($consumer_id) . '/' . md5($transaction_id) . '/' . ($downloadReceipt ?? true))
                ], 200);
            }
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function ConsumerPaymentFailed($consumer_id)
    {
        $this->connectWaterDBConn();
        if ($this->request->getMethod(true) !== 'POST') {
            return $this->fail('Method not allowed', 405);
        }
        $decript = $this->request->getJSON(true);
        if (empty($decript['order_id'])) {
            return $this->fail('Invalid order_id !!', 400);
        }
        if (empty($decript['order_status'])) {
            return $this->fail('Invalid order_status !!', 400);
        }
        if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
            return $this->fail('Invalid amount !!', 400);
        }
        $order_status = $decript["order_status"];
        if ($order_status == 'Success') {
            return $this->ConsumerPaySuccess($consumer_id);
        }

        $pg_request = $this->ModelThirdPartyOnlineRequest->getRequestDataByOderId($decript["order_id"]);

        $param = [
            "error_reason" => $decript["status_message"],
            "status" => 3
        ];
        $responseInput = [
            "request_id" => $pg_request['id'],
            "order_id" => $decript["order_id"],
            "merchant_id" => $pg_request["merchant_id"],
            "ref_id" => $pg_request["ref_id"],
            "ref_tbl" => $pg_request["ref_tbl"],
            "amount" => $decript["amount"],
            "tracking_id" => $decript["tracking_id"]??null,
            "bank_ref_no" => $decript["bank_ref_no"]??null,
            "error_code" => $decript["status_code"]??null,
            "error_desc" => $decript["error_desc"] ?? NULL,
            "error_source" => $decript["error_source"] ?? NULL,
            "error_step" => $decript["error_step"] ?? NULL,
            "error_reason" => $decript["status_message"]??null,
            "respons_data" => json_encode($this->request->getVar()),
            "ip_address" => $_SERVER['REMOTE_ADDR'],
            "status" => 3
        ];

        $update = $this->ModelThirdPartyOnlineRequest->updateData($pg_request["id"], $param);
        $response_id = $this->model_third_party_pay_response->insertData($responseInput);

        if ($response_id) {
            $MD5conn_id = md5($consumer_id);
            return $this->respond([
                'message' => "Oops, Something went wrong, payment failed",
                'result' => base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/' . $MD5conn_id)
            ], 200);
        }

        return $this->fail('Failed to log payment failure', 500);

    }

    public function getTradeDemand()
    {
        try {
            
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->fail('Method not allowed', 405);
            }

            $inputs = $this->request->getJSON(true);
            $application_no = $inputs['application_no'] ?? null;

            if (empty($application_no)) {
                return $this->fail('Invalid Key!', 400);
            }

            $this->connectTradeDBConn();
            $flag = $this->db_system->table("site_maintenance")->get()->getFirstRow("array");
            $whatsapp_payment = ($flag && $flag["whatsapp_payment"]=="t")?true:false;
            $sql = "SELECT * FROM tbl_apply_licence WHERE application_no = ?";
            $result = $this->db->query($sql, [$application_no])->getRowArray();

            if (!$result) {
                return $this->fail('Invalid Application Number', 400);
            } elseif ($result['status'] != 1) {
                return $this->fail('Application Number is not active', 422);
            }

            $id = $result['id'];
            $md5_id = md5($id);

            $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($md5_id);
            $data['licencee']['old_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['ward_mstr_id'])['ward_no'] ?? null;
            $data['licencee']['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['new_ward_mstr_id'])['ward_no'] ?? null;

            $tradeDetails = $this->tradeitemsmstrmodel->tradedetail($data["licencee"]['nature_of_bussiness']);
            $data['licencee']['trade_item_name'] = $tradeDetails[0]['trade_item'] ?? null;

            // $data['categoryDetails'] = $this->TradeCategoryTypeModel->categoryDetails($data["licencee"]['category_type_id']);
            // $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencee"]["firm_type_id"]);
            // $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencee"]["ownership_type_id"]);
            // $data['firm_owner'] = $this->TradeFirmOwnerModel->getdatabyid_md5($md5_id);
            $data['application_type_name'] = $this->trade_application_type_mstr_model->getdatabymd5id(md5($result['application_type_id']));
            $application_type_name = $data['application_type_name']['application_type'];


            if ($result['payment_status'] != 1) {
                $args = [
                    'areasqft' => (float) $result['area_in_sqft'],
                    'applytypeid' => $result['application_type_id'],
                    'estdate' => ($result['application_type_id'] == 1) ? $result['establishment_date'] : $result['valid_from'],
                    'tobacco_status' => $result['tobacco_status'],
                    'licensefor' => $result['licence_for_years'],
                    'nature_of_business' => $result['nature_of_bussiness']
                ];

                $rate_data = $this->TradeCitizenController->getcharge($args);
                $data['ratedata'] = json_decode($rate_data, true) ?? [];
                if($whatsapp_payment && ((!empty($data['ratedata']) && isset($data['ratedata']['response'])))){
                    $data['ratedata']['response']=false;
                    $data['ratedata']['total_charge']=0.00;
                }
                if (!empty($data['ratedata']) && isset($data['ratedata']['response']) && $data['ratedata']['response'] === true) {
                    $redirectUrl = base_url('AmurtMisPortal/TradepaySuccess/' . $md5_id);
                    $cancelUrl = base_url('AmurtMisPortal/TradepaymentFailed/' . $md5_id);

                    $orderId = $this->getOderId(3)['orderId'] ?? null;

                    if ($orderId) {
                        $req_inputs = [
                            "order_id" => $orderId,
                            "merchant_id" => null,
                            "ref_id" => $id,
                            "ref_tbl" => "tbl_apply_licence",
                            'payment_from' => $application_type_name,
                            "amount" => $data['ratedata']['total_charge'] ?? 0,
                            "redirect_url" => $redirectUrl,
                            "failiear_url" => $cancelUrl,
                            "ip_address" => $_SERVER['REMOTE_ADDR'],
                        ];

                        // print_var($req_inputs);


                        $data['request_id'] = $this->model_third_party_trade_online_payment->insertData($req_inputs);

                        $data['order_id'] = $orderId;
                    }
                }
            }

            return $this->respond([
                'result' => $data,
                'application_id' => $md5_id,
                'message' => 'Trade Details Fetched Successfully!'
            ]);

        } catch (\Throwable $th) {
            log_message('error', 'An error occurred: ' . $th->getMessage());
            return $this->respond([
                'message' => $th->getMessage()
            ]);
        }
    }


    public function TradepaySuccess($MD5apply_licence_id)
    {
            $this->connectTradeDBConn();

            if ($this->request->getMethod(true) !== 'POST') {
                return $this->fail('Method not allowed', 405);
            }
            
            $decript = $this->request->getJSON(true);
            if (empty($decript['order_id'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['order_status'])) {
                return $this->fail('Invalid order_id !!', 400);
            }
            if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
                return $this->fail('Invalid amount !!', 400);
            }

            $order_status = $decript["order_status"];
            
            if ($order_status != 'Success') {
                return $this->TradepaymentFailed($MD5apply_licence_id);
            }

            try {

                $pg_request = $this->model_third_party_trade_online_payment->getRequestDataByOderId($decript["order_id"]);
                $data["license"] = $this->TradeApplyLicenceModel->apply_licence_md5($MD5apply_licence_id);
                

                if ((!$pg_request) || (!$data["license"]) || ($pg_request['ref_id'] != $data["license"]["id"] )|| ($pg_request['ref_tbl'] != "tbl_apply_licence")) {
                    throw new Exception("Payment Faild due to Invalid Order No.");
                }
                $data["application_type"] = $this->trade_application_type_mstr_model->getdatabymd5id(md5($data["license"]['application_type_id']));
                
                
                $denial_amount = 0;
                $sql_notice = "SELECT * FROM tbl_denial_notice WHERE md5(apply_id::text) = '$MD5apply_licence_id' AND status = 2";
                $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice, array())[0] ?? [];

                if (!empty($noticeDetails)) {
                    $denial_amount = getDenialAmountTrade($noticeDetails['created_on'], date('Y-m-d'));

                }

                $args['areasqft'] = (float) $data["license"]['area_in_sqft'];
                $args['applytypeid'] = $data["license"]["application_type_id"];
                $args['estdate'] = $data["license"]['application_type_id'] == 1 ? $data["license"]["establishment_date"] : $data["license"]['valid_from'];
                $args['tobacco_status'] = $data["license"]["tobacco_status"];
                $args['licensefor'] = $data["license"]["licence_for_years"];
                $args['apply_licence_id'] = $data["license"]["id"];
                $args['nature_of_business'] = $data['license']['nature_of_bussiness'];

                $rate_data = $this->TradeCitizenController->getcharge($args);
                $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                // print_var($rate_data);die;
                if (!$rate_data["response"]) {
                    throw new Exception("Rate Not Calculated");
                }
                if (($rate_data['total_charge'] + $denial_amount) != $decript["amount"]) {
                    throw new Exception("Paid Amount Miss-matching with demand amount!!!");
                }

                $param = [
                    "status" => 1
                ];

                $responseInput = [
                    "request_id" => $pg_request['id'],
                    "order_id" => $decript["order_id"],
                    "merchant_id" => $pg_request["merchant_id"],
                    "ref_id" => $pg_request["ref_id"],
                    "ref_tbl" => $pg_request["ref_tbl"],
                    "amount" => $decript["amount"],
                    "tracking_id" => $decript["tracking_id"]?? null,
                    "bank_ref_no" => $decript["bank_ref_no"]?? null,
                    "error_code" => $decript["status_code"]?? null,
                    "error_desc" => $decript["error_desc"] ?? NULL,
                    "error_source" => $decript["error_source"] ?? NULL,
                    "error_step" => $decript["error_step"] ?? NULL,
                    "error_reason" => $decript["status_message"]?? null,
                    "respons_data" => json_encode($this->request->getVar()),
                    "ip_address" => $_SERVER['REMOTE_ADDR'],
                    "status" => 1
                ];

                $ap_id = $data["license"]['id'];

                $sql_prive_trnas = "select * 
                                    from tbl_transaction 
                                    where related_id = $ap_id  
                                    order by id desc limit 1";
                $prive_transaction = $this->TradeTransactionModel->row_query($sql_prive_trnas)[0] ?? [];

                $transact_arr = array();
                $transact_arr['related_id'] = $data["license"]['id'];
                $transact_arr['ward_mstr_id'] = $data["license"]["ward_mstr_id"];
                $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = 'Online';
                $transact_arr['paid_amount'] = $rate_data['total_charge'] + $denial_amount;
                $transact_arr['penalty'] = $rate_data['penalty'] + $rate_data['arear_amount'] + $denial_amount;
                $transact_arr['status'] = 1;
                $transact_arr['emp_details_id'] = '0';
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = $_SERVER['REMOTE_ADDR'];

                $this->db->transBegin();

           
                
                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                $responseInput["tran_id"] = $transaction_id;  
                $request_update = $this->model_third_party_trade_online_payment->updateData($pg_request["id"], $param);
                
                $response_id = $this->model_third_party_trade_response->insertData($responseInput);
            
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);

                $denialAmount = $rate_data['arear_amount'] + $denial_amount;
                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply';
                    $denial['amount'] = $denialAmount;
                    $denial['value_add_minus'] = 'Add';
                    $denial['created_on'] = date('Y-m-d H:i:s');
                    $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                }
                $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusClear($data["license"]["id"]);
                # Update Provisional No

                // print_var($data["license"]['application_type_id'] == 1);
                // die;

                if ($data["license"]['application_type_id'] == 1) {
                    
    
                    $get_ulb_id = session()->get('ulb_dtl');
                    $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
                    $warddet = $this->model_ward_mstr->getWardNoBywardId($data["license"]["ward_mstr_id"]);
                    $ward_no = $warddet["ward_no"];
                    $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];
                    $prov_no = $short_ulb_name . $ward_no . date('mdy') . $data["license"]['id'];
                    $this->TradeApplyLicenceModel->update_prov_no($data["license"]['id'], $prov_no);
                }

             
    
                /**********sms send testing code *************/
    
                if ($data["application_type"]["id"] <> 4) {
                    $owner_for_sms = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($data["license"]["id"]));
                    $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                    $sms = Trade(array('ammount' => $transact_arr['paid_amount'], 'application_no' => $data["license"]["application_no"], 'ref_no' => $transaction_no), 'Payment done');
                    if ($sms['status'] == true) {
                        foreach ($owner_for_sms as $val) {
                            $message = $sms['sms'];
                            $templateid = $sms['temp_id'];
                            $sms_data = [
                                'ref_id' => $data["license"]["id"],
                                'ref_type' => 'tbl_apply_licence',
                                'mobile_no' => 7667043372,
                                'purpose' => strtoupper($data["application_type"]["application_type"] . " trade_SI"),
                                'template_id' => $templateid,
                                'message' => $message
                            ];
                            $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                            if ($sms_id) {
                                //$res=SMSJHGOVT("7050180186", $message, $templateid);
                                $res = send_sms($val['mobile'], $message, $templateid);//print_var($res);
                                if ($res) {
                                    $update = [
                                        'response' => $res['response'],
                                        'smgid' => $res['msg'],
                                    ];
                                    $where = ['id' => $sms_id];
                                    $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                }
                            }
    
    
                        }
    
                    }
                    // $this->db->transRollback();                    
                    // print_var($sms);die; 
                }
                /***********end sms send*********************/
                #------------sws push------------------
                $sw = [];
                $sw['application_statge'] = 1;
                $sw['amount'] = $rate_data['total_charge'];
                $sw['sw_status'] = 1;
                $sw['arrear_amount'] = $rate_data['arear_amount'];
                // $sw['denial_amount']=$denial_amount;
                $sw['rejection_fine'] = 0;
                $sw['total_amount'] = $transact_arr['paid_amount'];
                $where_sw = ['apply_license_id' => $transact_arr['related_id']];
                $get_ws = $this->Citizensw_trade_model->getData($where_sw);
                //print_var($get_ws);die;
                if ($data["license"]['apply_from'] == 'sws' && !empty($get_ws)) {
                    $where_sw = ['apply_license_id' => $transact_arr['related_id'], 'id' => $get_ws['id']];
                    $this->Citizensw_trade_model->updateData($sw, $where_sw);
                    $push_sw = array();
                    $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . '1' . '/' . md5($data["license"]['id']) . '/' . md5($transaction_id));
                    $push_sw['application_stage'] = 11;
                    $push_sw['status'] = 'Payment Done via Online of ' . $transact_arr['paid_amount'] . '-/Rs';
                    $push_sw['acknowledgment_no'] = $data["license"]['application_no'];
                    $push_sw['service_type_id'] = $get_ws['service_id'];
                    $push_sw['caf_unique_no'] = $get_ws['caf_no'];
                    $push_sw['department_id'] = $get_ws['department_id'];
                    $push_sw['Swsregid'] = $get_ws['cust_id'];
                    $push_sw['payable_amount '] = $transact_arr['paid_amount'];
                    $push_sw['payment_validity'] = '';
                    $push_sw['payment_other_details'] = '';
                    $push_sw['certificate_url'] = $path;
                    $push_sw['approval_date'] = $data["license"]['valid_from'];
                    $push_sw['expire_date'] = $data["license"]['valid_upto'];
                    $push_sw['licence_no'] = $data["license"]['license_no'];
                    $push_sw['certificate_no'] = $data["license"]['provisional_license_no'];
                    $push_sw['customer_id'] = $get_ws['cust_id'];
                    $post_url = getenv('single_indow_push_url');
                    $http = getenv('single_indow_push_http');
                    $resp = httpPostJson($post_url, $push_sw, $http);
    
                    $respons_data = [];
                    $respons_data['apply_license_id'] = $transact_arr['related_id'];
                    $respons_data['response_msg'] = json_encode([
                        'url' => $http . '/' . $post_url,
                        'data' => $push_sw
                    ]);
                    $respons_data['tbl_single_window_id'] = $get_ws['id'];
                    $respons_data['emp_id'] = null;
                    $respons_data['response_status'] = json_encode($resp);
                    $this->Citizensw_trade_model->insertResponse($respons_data);
    
                }

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    throw new Exception("Some error occured, Transaction process has been rollback!!!");
                } else {
                    $this->db->transCommit();
                    $message = "Order No.: " . $decript["order_id"] . ", Amount: " . $decript["amount"];
                    return $this->respond([
                        'message' => $message . "Transaction successfull !!!",
                        'result' => base_url('TradeCitizen/view_transaction_receipt/'.$MD5apply_licence_id.'/'.md5($transaction_id))
                    ], 200);
                }
        
            } catch (\Throwable $th) {
                log_message('error', 'An error occurred: ' . $th->getMessage());
                
                return $this->response->setJSON([
                    'message' => $th->getMessage()
                ])->setStatusCode(500);
            }
         
    }

    public function TradepaymentFailed($MD5apply_licence_id)
    {
        $this->connectTradeDBConn();
        if ($this->request->getMethod(true) !== 'POST') {
            return $this->fail('Method not allowed', 405);
        }
        $decript = $this->request->getJSON(true);
        
        if (empty($decript['order_id'])) {
            return $this->fail('Invalid order_id !!', 400);
        }
        if (empty($decript['order_status'])) {
            return $this->fail('Invalid order_status !!', 400);
        }
        if (empty($decript['amount']) || ($decript['amount'] ?? 0) == 0) {
            return $this->fail('Invalid amount !!', 400);
        }

        if (empty($decript['status_message'])) {
            return $this->fail('Send Error Msg with key name "status_message"', 400);
        }        

        $order_status = $decript['order_status'];

        if ($order_status == 'Success') {
            return $this->TradepaySuccess($MD5apply_licence_id);
        }

        $orderId = $decript['order_id'];

        try {

            $pg_request = $this->model_third_party_trade_online_payment->getRequestDataByOderId($orderId);
            $param = [
                "error_reason" => $decript['status_message'],
                "status" => 3
            ];
    
            $responseInput = [
                "request_id" => $pg_request['id'],
                "order_id" => $decript["order_id"],
                "merchant_id" => $pg_request["merchant_id"],
                "ref_id" => $pg_request["ref_id"],
                "ref_tbl" => $pg_request["ref_tbl"],
                "amount" => $decript["amount"],
                "tracking_id" => $decript["tracking_id"]??null,
                "bank_ref_no" => $decript["bank_ref_no"]??null,
                "error_code" => $decript["status_code"]??null,
                "error_desc" => $decript["error_desc"] ?? NULL,
                "error_source" => $decript["error_source"] ?? NULL,
                "error_step" => $decript["error_step"] ?? NULL,
                "error_reason" => $decript["status_message"]??null,
                "respons_data" => json_encode($this->request->getVar()),
                "ip_address" => $_SERVER['REMOTE_ADDR'],
                "status" => 3
            ];
    
            $update = $this->model_third_party_trade_online_payment->updateData($pg_request["id"], $param);
            $response_id = $this->model_third_party_trade_online_payment->insertData($responseInput);

            return $this->respond([
                'message' => "Oops, Something went wrong, payment failed",
                'result' => base_url('TradeCitizen/doc_upload/'.$MD5apply_licence_id)
        ], 200);
            
        } catch(\Exception $e){
            print_var($e);die;
        }
        
    }


    public function getOderId(int $modeuleId)
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
        
        return ["orderId"=>$orderId,"merchantId"=>$CCA_MERCHANT_ID] ;
    }


    public function decodeData(){
        $inputs = (array) $this->request->getJSON();
        $data=$inputs["hash"]??"";
		$decrept =  $this->encrypter->decrypt(base64_decode($data));
        return $this->respond([
                    'message' => "Transaction successfull !!!",
                    'data' => [
                        $decrept
                    ]
                ], 200);
	}
	

}
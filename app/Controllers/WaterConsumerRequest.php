<?php

namespace App\Controllers;

use App\Models\model_datatable;
use App\Models\model_document_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_user_type_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\Water\TblConsumerRequest;
use App\Models\Water\TblConsumerRequestDtl;
use App\Models\Water\TblRequestDoc;
use App\Models\Water\TblRequestType;
use App\Models\Water\TblRequestTypeFeeMstr;
use App\Models\Water\TblWfMstr;
use App\Models\Water\TblWfRoleMapMstr;
use App\Models\Water\TblWfTrack;
use App\Models\water_consumer_demand_model;
use App\Models\water_consumer_details_model;
use App\Models\Water_name_transfer_log_model;
use App\Models\Water_Transaction_Model;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterConsumerInitialMeterReadingModel;
use App\Models\WaterConsumerTaxModel;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterMobileModel;
use App\Models\WaterPaymentModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterViewConsumerModel;
use Exception;

class WaterConsumerRequest extends AlphaController
{
    protected $_session ;
    protected $_emp_dtl;
    protected $_emp_id;
    protected $_emp_type;
    protected $_ulb_mstr_id ;
    protected $_db;
	protected $_dbSystem;
	protected $_model_ulb_mstr;
	protected $_model_ward_mstr;
    protected $_modelViewWarPermission;
    protected $_modelUserTypeMaster;

    protected $_model_view_water_consumer ;
    protected $_modelWaterViewConsumerModel;
    protected $_consumer_details_model;
    protected $_consumer_demand_model;
    protected $_demand_model;
    protected $_search_consumer_mobile_model;
    protected $_meter_status_model;
    protected $_last_reading;
    protected $_payment_model;
    protected $_WaterMobileModel;
    protected $_consumer_tax_model;
    protected $_modelTblRequestType;
    protected $_modelTblConsumerRequest;
    protected $_modelTblConsumerRequestDtl;
    protected $_modelTblRequestTypeFeeMstr;
    protected $_modelTblWfRoleMapMstr;
    protected $_modelTblWfMstr;
    protected $_modelTblWfTrack;
    protected $_modelTransaction;
    protected $_modelPayment;
    protected $_model_dataTable;
    protected $_modelDocumentMstr;
    protected $_modelTblRequestDoc;
    protected $_Water_name_transfer_log_model;

    public function __construct()
	{
        parent::__construct();
        helper(['db_helper','form',"utility_helper","qr_code_generator_helper"]);     
        $this->_session=session();
        $this->_emp_dtl =  $this->_session->get("emp_details");
        $this->_emp_id =  $this->_emp_dtl["id"]??"";
        $this->_emp_type =  $this->_emp_dtl["user_type_mstr_id"]??"";

        $ulb_mstr = $this->_session->get("ulb_dtl")??getUlbDtl();
        $this->_ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        if($db_name = dbConfig("water"))
		{
            $this->_db = db_connect($db_name);
        }

        if ($db_name = dbSystem())
		{
            $this->_dbSystem = db_connect($db_name);
        }
		$this->_model_ulb_mstr = new model_ulb_mstr($this->_dbSystem);
		$this->_model_ward_mstr = new model_ward_mstr($this->_dbSystem);        
        $this->_modelViewWarPermission = new model_view_ward_permission($this->_dbSystem);
        $this->_modelUserTypeMaster = new model_user_type_mstr($this->_dbSystem);
        
        $this->_model_view_water_consumer = new model_view_water_consumer($this->_db);
        $this->_modelWaterViewConsumerModel = new WaterViewConsumerModel($this->_db);
        $this->_consumer_details_model=new water_consumer_details_model($this->_db);
        $this->_consumer_demand_model=new water_consumer_demand_model($this->_db);
        $this->_demand_model=new WaterConsumerDemandModel($this->_db);
        $this->_search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->_db);
        $this->_meter_status_model=new WaterMeterStatusModel($this->_db);
        $this->_last_reading=new WaterConsumerInitialMeterReadingModel($this->_db);
        $this->_payment_model=new WaterPaymentModel($this->_db);
        $this->_WaterMobileModel=new WaterMobileModel($this->_db);
        $this->_consumer_tax_model = new WaterConsumerTaxModel($this->_db);
        $this->_modelTblRequestType = new TblRequestType($this->_db);
        $this->_modelTblConsumerRequest = new TblConsumerRequest($this->_db);
        $this->_modelTblConsumerRequestDtl = new TblConsumerRequestDtl($this->_db);
        $this->_modelTblRequestTypeFeeMstr   = new TblRequestTypeFeeMstr($this->_db);
        $this->_modelTblWfRoleMapMstr = new TblWfRoleMapMstr($this->_db);
        $this->_modelTblWfMstr  = new TblWfMstr($this->_db);
        $this->_modelTblWfTrack = new TblWfTrack($this->_db);
        $this->_modelTransaction = new Water_Transaction_Model($this->_db);        
        $this->_modelPayment=new WaterPaymentModel($this->_db);
        $this->_model_dataTable = new model_datatable($this->_db); 
        $this->_modelDocumentMstr = new model_document_mstr($this->_db);
        $this->_modelTblRequestDoc = new TblRequestDoc($this->_db);
        $this->_Water_name_transfer_log_model = new Water_name_transfer_log_model($this->_db);
    }

	public function __destruct() {
		if(isset($this->_db)) $this->_db->close();
		if(isset($this->_dbSystem)) $this->_dbSystem->close();
	}

    public function applyRequest($conumerId)
    {
        $data = [];
        try{
            $consumerDtl = $this->_model_view_water_consumer->waterConsumerDetailsById($conumerId);
            if(!$consumerDtl){
                flashToast("message", "Consumer Dtl Not Found");
                return $this->response->redirect(base_url('WaterConsumerList/index/consumerRequest'));
            }
            $pendingRequest = $this->_modelTblConsumerRequest->select("count(id)")
                            ->where("status",1)
                            ->where("pending_status<>",5)
                            ->where("consumer_id",$consumerDtl["id"])
                            ->get()
                            ->getFirstRow("array")["count"];
            if($pendingRequest>0){
                flashToast("message", "This Consumer Have Already Pending Request Please Wait For Approval");
                return $this->response->redirect(base_url('WaterConsumerList/index/consumerRequest'));
            }
            $requestType = $this->_modelTblRequestType->getAllData();
            $dueAmount = $this->_consumer_demand_model->select("sum(balance_amount)as balance_amount")
                        ->where("status",1)
                        ->where("paid_status",0)
                        ->where("consumer_id",$consumerDtl["id"])
                        ->get()
                        ->getFirstRow("array");
            $data["balance_amount"]=$dueAmount["balance_amount"] ? $dueAmount["balance_amount"] : 0;
            $data["consumer_details"]=$consumerDtl;
            $data['consumer_owner_details'] = $this->_consumer_details_model->consumerDetails($data['consumer_details']['id']);
            $data["request_type"]=$requestType;
            if($this->request->getMethod()=="post"){
                $inputs=arrFilterSanitizeString($this->request->getVar());
                $rules = [
                        "request_type"=>"required|in_list[1,2,3]",                        
                        ];
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                }else{
                    $rate = $this->_modelTblRequestTypeFeeMstr->getRate($inputs["request_type"],"2025-01-01");
                    if(!$rate){
                        throw new Exception("Rate Not Found");
                    }
                    if($data["balance_amount"]>0 && $inputs["request_type"]==3){
                        throw new Exception("All Due are not clear");
                    }
                    $wf = $this->_modelTblWfMstr->getWf($inputs["request_type"]);                    
                    $wf_role_map = $this->_modelTblWfRoleMapMstr->getWfMaps($wf["id"]??0);
                    $initiator = [];
                    $finisher = [];
                    foreach($wf_role_map as $val){
                        if($val["is_initiator"]=="t"){
                            
                            array_push($initiator,$val);
                        }
                        if($val["is_finiser"]=="t"){
                            array_push($finisher,$val);
                        }
                    }
                    if(!$wf){
                        throw new Exception("Wf Not Mapped");
                    }
                    if(sizeof($initiator)==0){
                        throw new Exception("Initiator Not Defined");
                    }
                    if(sizeof($finisher)==0){
                        throw new Exception("Finisher Not Defined");
                    }
                    
                       
                    $this->_db->transBegin();               
                    $consumer_request = [
                        "request_type_id"=>$inputs["request_type"],
                        "consumer_id"=>$data['consumer_details']['id'],
                        "rate_id"=>$rate["id"],
                        "demand_amount"=>$rate["rate"],
                        "due_demand_amount"=>$rate["rate"],
                        "apply_emp_id"=> $this->_emp_id,
                        "apply_emp_role_id"=> $this->_emp_type, 
                        "wf_mstr_id"=> $wf["id"], 
                        "pending_at_role_id"=> $initiator[0]["role_id"], 
                        "initiater_role_id"=> $initiator[0]["role_id"],  
                        "finiser_role_id"=> $finisher[0]["role_id"],                       
                    ];
                    $insert_req_id = $this->_modelTblConsumerRequest->store($consumer_request);
                    
                    if(in_array($inputs["request_type"],[1])){

                        foreach($inputs["owner_name"] as $key=>$val){
                            $request_owner=[
                                "request_id"=>$insert_req_id,
                                "applicant_name"=>$val,
                                "father_name"=>$inputs["guardian_name"][$key]??"",
                                "district"=>$inputs["district"][$key]??"",
                                "state"=>$inputs["state"][$key]??"",
                                "mobile_no"=>$inputs["mobile_no"][$key]??"",                            
                                "emp_details_id"=>$this->_emp_id,
                            ];
                            $this->_modelTblConsumerRequestDtl->store($request_owner);
                        }
                    }
                    
					if($this->_db->transStatus() === FALSE)
					{
						$this->_db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return view("Water/AppRequest/ConsumerRequest",$data);
				
					}
					else
					{ 
						$this->_db->transCommit();
						flashToast("message", "Request Submitted Successfully!!!");
						return $this->response->redirect(base_url('WaterConsumerRequest/ViewDtl/'.$insert_req_id));
					}
                    
                }
            }
            return view("Water/AppRequest/ConsumerRequest",$data);
        }
        catch(Exception $e)
        {
            $this->_db->transRollback();
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ViewDtl($id,$action="view"){
        
        try{
            $data["from"] = $action;
            $data["request_dtl"] = $this->_modelTblConsumerRequest->where("id",$id)->get()->getFirstRow("array");
            if(!$data["request_dtl"]){
                throw new Exception("Data Not Find");
            } 
            $data["app_status"] = ($this->_modelTblConsumerRequest->getStatus($data["request_dtl"]["id"]));      
            $permission = $this->_modelTblWfRoleMapMstr->getWfMapPermission($data["request_dtl"]["wf_mstr_id"],$this->_emp_type);  
            if(in_array($data["request_dtl"]["pending_status"],[5,4]) && $permission){
                $permission["can_forward"] = "f";
                $permission["can_backward"] = "f";
                $permission["can_verify_doc"] = "f";
                $permission["can_upload_doc"] = "f";
                $permission["can_btc"] = "f";                
                $permission["can_take_payment"] = "f";                                
                $permission["can_edit"] = "f";                                                
                $permission["is_finiser"] = "f";                                                
                $permission["is_initiator"] = "f";                                                
                $permission["backword_role_id"] = null;                                                
                $permission["forward_role_id"] = null;
            }   
            $data["permission"] = $permission;
            $data["request_owner"] = $this->_modelTblConsumerRequestDtl->where("request_id",$id)->orderBy("id","ASC")->get()->getResultArray();
            $consumerDtl = $this->_model_view_water_consumer->waterConsumerDetailsById(md5($data["request_dtl"]["consumer_id"]));
            $requestType = $this->_modelTblRequestType->where("id",$data["request_dtl"]["request_type_id"])->get()->getFirstRow("array");
            $data["consumer_details"]=$consumerDtl;
            $data['consumer_owner_details'] = $this->_consumer_details_model->consumerDetails($data['consumer_details']['id']);
            if($data["request_dtl"]["pending_status"]==5){
                $oldOwnerIds = $this->_Water_name_transfer_log_model->select("old_owner_ids")->where("request_id",$data["request_dtl"]["id"])->get()->getFirstRow("array");
                $data['consumer_owner_details'] = $this->_consumer_details_model->whereIn("id",explode(",",$oldOwnerIds["old_owner_ids"]??""))->get()->getResultArray();
            }
            $data["request_dtl"]["request_type"]=$requestType["request_type"]??"";
            $data["level"] = $this->_modelTblWfTrack->getAppRemarks($data["request_dtl"]["id"],$this->_modelTblConsumerRequest->table);            
            $data["payment_detail"] = $this->_modelTransaction->getRequestTransactionList($data["request_dtl"]["id"]);
            $data["uploaded_doc_list"]=$this->_modelTblRequestDoc->getAllActiveDocuments($data["request_dtl"]["id"]);
            $data['required_doc_list'] = $this->HaveToUploadDoc($data["request_dtl"]);
            $data["fullDocUpload"] = true;            
            $data["fullDocVerify"] = true;
            $testUploadDocIsNotTested = array_filter($data["uploaded_doc_list"],function($val){
                return (!$val["verify_status"])?true:false;
            });
            $uploaded_doc_list = $data["uploaded_doc_list"];
            $testRequiredDocIsNotUploaded = array_filter($data["required_doc_list"],function($val)use($uploaded_doc_list){ 
                $val=$val[0]??[];               
                $isUploaded = array_filter($uploaded_doc_list,function($val1)use($val){
                    return ($val1["doc_for"]==($val["doc_for"]??""))?true:false;
                });
                $isDocRejected =array_filter($isUploaded,function($val2){
                    return $val2["verify_status"]==2 ? true: false;
                });
                return ((!$isUploaded) && ($val["is_mandatory"]??0)==1)?true:false;
            });

            $testRequiredDocIsRejected = array_filter($data["required_doc_list"],function($val)use($uploaded_doc_list){ 
                $val=$val[0]??[];               
                $isUploaded = array_filter($uploaded_doc_list,function($val1)use($val){
                    return ($val1["doc_for"]==($val["doc_for"]??""))?true:false;
                });
                $isDocRejected =array_filter($isUploaded,function($val2){
                    return $val2["verify_status"]==2 ? true: false;
                });
                return (($isDocRejected) && ($val["is_mandatory"]??0)==1)?true:false;
            });

            if(($permission["can_upload_doc"]??"f")=="t" && ($testRequiredDocIsNotUploaded || $testRequiredDocIsRejected)){
                $data["fullDocUpload"] = false; 
            }
            $data["can_upload_doc"]=="f";
            if(($data["request_dtl"]["is_parked"]=='t' || $data["request_data"]["pending_status"]==0) && ($permission["can_upload_doc"]??"f"=="t")){
                $data["can_upload_doc"]=="t";
            }
            
            if(($permission["can_verify_doc"]??"f")=="t" && $testUploadDocIsNotTested || $testRequiredDocIsRejected){
                $data["fullDocVerify"] = false; 
            }
            return view("Water/AppRequest/ConsumerRequestDtls",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function proceedToPayment($id){
        $url="";
        try{

            $request_dtl = $this->_modelTblConsumerRequest->where("md5(id::text)",$id)->get()->getFirstRow("array");
            if(!$request_dtl){
                throw new Exception("Data Not Find");
            }  
            if(!$request_dtl["is_full_pay"]=='t'){
                throw new Exception("This Application Has No Any Due");
            }    
            $consumerDtl = $this->_model_view_water_consumer->waterConsumerDetailsById(md5($request_dtl["consumer_id"]));
            $inputs=arrFilterSanitizeString($this->request->getVar());
            if($this->request->getMethod()=="post"){
                $trans_arr=array();
                $trans_arr['ward_mstr_id']=$consumerDtl["ward_mstr_id"]??null;
                $trans_arr['ip_address']="";
                $trans_arr['transaction_type']=$inputs["type"];
                $trans_arr['transaction_date']=date('Y-m-d');
                $trans_arr['related_id']=$request_dtl["id"];
                $trans_arr['payment_mode']=$inputs["payment_mode"];
                $trans_arr['penalty']=0;
                $trans_arr['rebate']=0.00;
                $trans_arr['paid_amount']=round($request_dtl["due_demand_amount"]);
                $trans_arr['total_amount']=round($request_dtl["due_demand_amount"]); //demand amount
                $trans_arr['status']=1;
                $trans_arr['emp_details_id']=$this->_emp_id;
                $trans_arr['created_on']=date('Y-m-d H:i:s');
                $trans_arr['from_month']=null;
                $trans_arr['upto_month']=null;
                $trans_arr['payment_from']=$inputs["payment_from"];
                $trans_arr['remarks']=$inputs["remarks"];
    
                $cheque_dtl=[];
                if(in_array($inputs["payment_mode"], ["CHEQUE", "DD","NEFT",'RTGS'])){
                    $cheque_dtl=[
                        "cheque_no"=> $inputs["cheque_no"],
                        "cheque_date"=> $inputs["cheque_date"],
                        "bank_name"=> $inputs["bank_name"],
                        "branch_name"=> $inputs["branch_name"]
                    ];
                    $trans_arr['status']=2;
                }  
                $balance =round($request_dtl["due_demand_amount"]-$trans_arr['paid_amount']) > 0 ? round($request_dtl["due_demand_amount"]-$trans_arr['paid_amount']) : 0;               
                $update_data=[
                    "payment_status"=>$trans_arr['status'],
                    "due_demand_amount"=>$balance,
                    "is_full_pay" => $balance>0?false:true,
                ];
                $this->_db->transBegin();
                $this->_dbSystem->transBegin();
                $transaction_id=$this->_modelPayment->insert_transaction($trans_arr);
                $trans_no="WTRAN".$transaction_id.date('YmdHis');
                $this->_modelPayment->update_trans_no($trans_no,$transaction_id);
                $this->_modelTblConsumerRequest->updateData($request_dtl["id"],$update_data);
    
                if($cheque_dtl)
                {
                    $cheque_dtl['transaction_id']=$transaction_id;
                    $this->_modelPayment->insert_cheque_details($cheque_dtl);
                }
                if($this->_db->transStatus() === FALSE)
                {
                    $this->_db->transRollback();
                    $this->_dbSystem->transRollback();
                    throw new Exception("Something went wrong");
                }
                else
                {
                    $this->_db->transCommit();
                    $this->_dbSystem->transCommit();
                    $url =base_url('WaterConsumerRequest/paymentReceipt/'.md5($transaction_id)."/counter") ;
    
                }
                return $this->response->redirect($url);
            } 
        }
        catch(Exception $e){
            $this->_db->transRollback();
            $this->_dbSystem->transRollback();
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function paymentReceipt($tranId,$from="citizen"){
        $data["from"]=$from;
        $data['transaction_details']=$this->_modelPayment->transaction_details($tranId);    
        $data["request_dtl"] = $this->_modelTblConsumerRequest->where("id",$data['transaction_details']["related_id"])->get()->getFirstRow("array");  
        $data["consumer_details"] = $this->_modelWaterViewConsumerModel->consumerDetails(md5($data["request_dtl"]["consumer_id"]));  
        $data['applicant_details']=$this->_modelTblConsumerRequestDtl->where("id",$data['transaction_details']["related_id"])->where("status",1)->get()->getResultArray();   
        if($data['request_dtl']["request_type_id"]!=1){
            $data['applicant_details']=$this->_consumer_details_model->consumerDetails($data['consumer_details']['id']); 
        }     
        $data['ulb_mstr_name'] = $this->_model_ulb_mstr->getulb_list($this->_ulb_mstr_id);
        $path=base_url('citizenPaymentReceipt/viewWaterRequestTranReceipt/'.$this->_ulb_mstr_id.'/'.$tranId);
        $data["path"] = $path;
		$data['ss']=qrCodeGeneratorFun($path);
        $data['user_type']=$this->_emp_type;
        $data["appId"]=$data['transaction_details']["related_id"];
        if($this->_emp_type!=5)
        {
            return view('Water/AppRequest/payment_receipt',$data);  
        }
        else
        {
            return view('mobile/water/payment_conn_tax_receipt',$data);  
        }

    }

    public function search(){
        $data =(array)null;
        try{
            $wardList = $this->_modelViewWarPermission->getPermittedWard($this->_emp_id);
            $data['wardList'] = $wardList;
            $data["from"]="search";
            $data['user_type']=$this->_emp_type;
            $user_type_nm = $this->_modelUserTypeMaster->getdatabyid(MD5($this->_emp_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            $ward_ids_array = array_map(function($val){
                return $val['ward_mstr_id'];
            },$wardList);
            $ward_ids_string = implode(',',$ward_ids_array); 
            $inputs=arrFilterSanitizeString($this->request->getVar());
                     
            if(isset($inputs['ward_mstr_id']) && $inputs['ward_mstr_id']!="All")
            {
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $ward_ids_string=$data['ward_mstr_id'];
            }
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_consumer_requests.request_no ilike('%".$data['keyword']."%')
                                OR tbl_consumer.consumer_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_consumer_details.applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.mobile_no ilike('%".$data['keyword']."%')  
                                OR tbl_consumer_request_dtls.req_applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_consumer_requests.* ,tbl_consumer.consumer_no,
                        tbl_consumer_request_dtls.*,
                        tbl_consumer_details.*,
                        view_ward_mstr.ward_no,
                        tbl_request_types.request_type
                FROM tbl_consumer_requests 
                JOIN tbl_request_types ON tbl_request_types.id = tbl_consumer_requests.request_type_id
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_requests.consumer_id
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select request_id,
                        string_agg(applicant_name,',') as req_applicant_name,
                        string_agg(father_name,',') as req_father_name,
                        string_agg(mobile_no::text,',') as req_mobile_no
                    from tbl_consumer_request_dtls
                    where status =1
                    group by request_id
                ) tbl_consumer_request_dtls on tbl_consumer_request_dtls.request_id = tbl_consumer_requests.id
                left join(
                    select consumer_id,
                        string_agg(applicant_name,',') as applicant_name,
                        string_agg(father_name,',') as father_name,
                        string_agg(mobile_no::text,',') as mobile_no
                    from tbl_consumer_details
                    where status =1
                    group by consumer_id
                ) tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_consumer.id
                WHERE 1=1 ".($this->_emp_type !=1 ? "AND tbl_consumer_requests.pending_at_role_id = ".$this->_emp_type : "")." 
                    AND tbl_consumer.ward_mstr_id IN ($ward_ids_string) AND tbl_consumer_requests.pending_at_role_id NOT IN(4,5) 
                    AND tbl_consumer_requests.status=1
                    $where
                ";
            $data['posts'] = $this->_model_dataTable->getDatatable($sql);

            return view("Water/AppRequest/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function inbox(){
        $data =(array)null;
        try{
            $wardList = $this->_modelViewWarPermission->getPermittedWard($this->_emp_id);
            $data['wardList'] = $wardList;
            $data["from"]="inbox";
            $data['user_type']=$this->_emp_type;
            $user_type_nm = $this->_modelUserTypeMaster->getdatabyid(MD5($this->_emp_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            $ward_ids_array = array_map(function($val){
                return $val['ward_mstr_id'];
            },$wardList);
            $ward_ids_string = implode(',',$ward_ids_array); 
            $inputs=arrFilterSanitizeString($this->request->getVar());
                     
            if(isset($inputs['ward_mstr_id']) && $inputs['ward_mstr_id']!="All")
            {
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $ward_ids_string=$data['ward_mstr_id'];
            }
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_consumer_requests.request_no ilike('%".$data['keyword']."%')
                                OR tbl_consumer.consumer_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_consumer_details.applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.mobile_no ilike('%".$data['keyword']."%')  
                                OR tbl_consumer_request_dtls.req_applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_consumer_requests.* ,tbl_consumer.consumer_no,
                        tbl_consumer_request_dtls.*,
                        tbl_consumer_details.*,
                        view_ward_mstr.ward_no,
                        tbl_request_types.request_type
                FROM tbl_consumer_requests 
                JOIN tbl_request_types ON tbl_request_types.id = tbl_consumer_requests.request_type_id
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_requests.consumer_id
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select request_id,
                        string_agg(applicant_name,',') as req_applicant_name,
                        string_agg(father_name,',') as req_father_name,
                        string_agg(mobile_no::text,',') as req_mobile_no
                    from tbl_consumer_request_dtls
                    where status =1
                    group by request_id
                ) tbl_consumer_request_dtls on tbl_consumer_request_dtls.request_id = tbl_consumer_requests.id
                left join(
                    select consumer_id,
                        string_agg(applicant_name,',') as applicant_name,
                        string_agg(father_name,',') as father_name,
                        string_agg(mobile_no::text,',') as mobile_no
                    from tbl_consumer_details
                    where status =1
                    group by consumer_id
                ) tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_consumer.id
                WHERE 1=1 ".($this->_emp_type !=1 ? "AND tbl_consumer_requests.pending_at_role_id = ".$this->_emp_type : "")." 
                    AND tbl_consumer.ward_mstr_id IN ($ward_ids_string) AND tbl_consumer_requests.pending_status NOT IN(4,5) 
                    AND tbl_consumer_requests.status=1
                    AND tbl_consumer_requests.payment_status =1
                    AND tbl_consumer_requests.is_parked != TRUE
                    $where
                ";
            $data['posts'] = $this->_model_dataTable->getDatatable($sql);

            return view("Water/AppRequest/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function outBox(){
        $data =(array)null;
        try{
            $wardList = $this->_modelViewWarPermission->getPermittedWard($this->_emp_id);
            $data['wardList'] = $wardList;
            $data["from"]="outBox";
            $data['user_type']=$this->_emp_type;
            $user_type_nm = $this->_modelUserTypeMaster->getdatabyid(MD5($this->_emp_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            $ward_ids_array = array_map(function($val){
                return $val['ward_mstr_id'];
            },$wardList);
            $ward_ids_string = implode(',',$ward_ids_array); 
            $inputs=arrFilterSanitizeString($this->request->getVar());
                     
            if(isset($inputs['ward_mstr_id']) && $inputs['ward_mstr_id']!="All")
            {
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $ward_ids_string=$data['ward_mstr_id'];
            }
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_consumer_requests.request_no ilike('%".$data['keyword']."%')
                                OR tbl_consumer.consumer_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_consumer_details.applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.mobile_no ilike('%".$data['keyword']."%')  
                                OR tbl_consumer_request_dtls.req_applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_consumer_requests.* ,tbl_consumer.consumer_no,
                        tbl_consumer_request_dtls.*,
                        tbl_consumer_details.*,
                        view_ward_mstr.ward_no,
                        tbl_request_types.request_type
                FROM tbl_consumer_requests 
                JOIN tbl_request_types ON tbl_request_types.id = tbl_consumer_requests.request_type_id
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_requests.consumer_id
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select request_id,
                        string_agg(applicant_name,',') as req_applicant_name,
                        string_agg(father_name,',') as req_father_name,
                        string_agg(mobile_no::text,',') as req_mobile_no
                    from tbl_consumer_request_dtls
                    where status =1
                    group by request_id
                ) tbl_consumer_request_dtls on tbl_consumer_request_dtls.request_id = tbl_consumer_requests.id
                left join(
                    select consumer_id,
                        string_agg(applicant_name,',') as applicant_name,
                        string_agg(father_name,',') as father_name,
                        string_agg(mobile_no::text,',') as mobile_no
                    from tbl_consumer_details
                    where status =1
                    group by consumer_id
                ) tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_consumer.id
                WHERE 1=1 ".($this->_emp_type !=1 ? "AND tbl_consumer_requests.pending_at_role_id != ".$this->_emp_type : "")." 
                    AND tbl_consumer.ward_mstr_id IN ($ward_ids_string) AND tbl_consumer_requests.pending_at_role_id NOT IN(4,5) 
                    AND tbl_consumer_requests.status=1
                    AND tbl_consumer_requests.is_parked != TRUE
                    $where
                ";
            $data['posts'] = $this->_model_dataTable->getDatatable($sql);

            return view("Water/AppRequest/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function btcList(){
        $data =(array)null;
        try{
            $wf_mstr_id = $this->_modelTblConsumerRequest->select("distinct(wf_mstr_id)wf_mstr_id")->get()->getResultArray();
            $wf_mstr_ids = array_map(function($val){
                return$val["wf_mstr_id"];
            },$wf_mstr_id);
            $getInitiatorRole = $this->_modelTblWfRoleMapMstr->select("distinct(role_id)role_id")
                ->whereIn("tbl_wf_mstr_id",$wf_mstr_ids)
                ->where("is_initiator",TRUE)
                ->get()
                ->getResultArray();
            $getInitiatorRoles = array_map(function($val){
                return $val['role_id'];
            },$getInitiatorRole);
            array_push($getInitiatorRoles,1);

            $wardList = $this->_modelViewWarPermission->getPermittedWard($this->_emp_id);
            $data['wardList'] = $wardList;
            $data["from"]="btcList";
            if(!in_array($this->_emp_type,$getInitiatorRoles)){
                $data["from"]="view";
            }
            $data['user_type']=$this->_emp_type;
            $user_type_nm = $this->_modelUserTypeMaster->getdatabyid(MD5($this->_emp_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            $ward_ids_array = array_map(function($val){
                return $val['ward_mstr_id'];
            },$wardList);
            $ward_ids_string = implode(',',$ward_ids_array); 
            $inputs=arrFilterSanitizeString($this->request->getVar());
                     
            if(isset($inputs['ward_mstr_id']) && $inputs['ward_mstr_id']!="All")
            {
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $ward_ids_string=$data['ward_mstr_id'];
            }
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_consumer_requests.request_no ilike('%".$data['keyword']."%')
                                OR tbl_consumer.consumer_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_consumer_details.applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_details.mobile_no ilike('%".$data['keyword']."%')  
                                OR tbl_consumer_request_dtls.req_applicant_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_father_name ilike('%".$data['keyword']."%')
                                OR tbl_consumer_request_dtls.req_mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_consumer_requests.* ,tbl_consumer.consumer_no,
                        tbl_consumer_request_dtls.*,
                        tbl_consumer_details.*,
                        view_ward_mstr.ward_no,
                        tbl_request_types.request_type
                FROM tbl_consumer_requests 
                JOIN tbl_request_types ON tbl_request_types.id = tbl_consumer_requests.request_type_id
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_requests.consumer_id
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select request_id,
                        string_agg(applicant_name,',') as req_applicant_name,
                        string_agg(father_name,',') as req_father_name,
                        string_agg(mobile_no::text,',') as req_mobile_no
                    from tbl_consumer_request_dtls
                    where status =1
                    group by request_id
                ) tbl_consumer_request_dtls on tbl_consumer_request_dtls.request_id = tbl_consumer_requests.id
                left join(
                    select consumer_id,
                        string_agg(applicant_name,',') as applicant_name,
                        string_agg(father_name,',') as father_name,
                        string_agg(mobile_no::text,',') as mobile_no
                    from tbl_consumer_details
                    where status =1
                    group by consumer_id
                ) tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_consumer.id
                WHERE 1=1 
                    AND tbl_consumer.ward_mstr_id IN ($ward_ids_string) AND tbl_consumer_requests.pending_at_role_id NOT IN(4,5) 
                    AND tbl_consumer_requests.status=1
                    AND tbl_consumer_requests.is_parked = TRUE
                    $where
                ";
            $data['posts'] = $this->_model_dataTable->getDatatable($sql);

            return view("Water/AppRequest/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function postNextLevel($id){
        try{
            $request_dtl = $this->_modelTblConsumerRequest->where("md5(id::text)",$id)->get()->getFirstRow("array");
            if(!$request_dtl){
                throw new Exception("Data Not Find");
            }
            $currentUserType = $this->_emp_type;
            if((int)$this->_emp_type==1){
                $currentUserType = $request_dtl["pending_at_role_id"];
            }
            $forwardBackwordRole = $this->_modelTblWfRoleMapMstr->getWfMapPermission($request_dtl["wf_mstr_id"],$currentUserType); 
            $getInitiatorRole = $this->_modelTblWfRoleMapMstr->getInitiatorRole($request_dtl["wf_mstr_id"]);  
            $permission = $this->_modelTblWfRoleMapMstr->getWfMapPermission($request_dtl["wf_mstr_id"],$this->_emp_type);          
            $inputs=arrFilterSanitizeString($this->request->getVar());
            if($this->request->getMethod()=="post"){               
                if($request_dtl["pending_at_role_id"]!=$currentUserType && $request_dtl["is_parked"]!='t'){
                    throw new Exception("This Application is not pending at you");
                }elseif($request_dtl["is_parked"]=='t' && $request_dtl["initiater_role_id"]!=$currentUserType){
                    throw new Exception("This Application is not pending at you");
                }
                if($request_dtl["pending_status"]==5){
                    throw new Exception("This Application is already approved");
                }
                if($request_dtl["payment_status"]==0){
                    throw new Exception("This Application payment is pending");
                }
                if($request_dtl["payment_status"]!=1){
                    throw new Exception("This Application payment is not clear please wait for clearance");
                }
                if($request_dtl["payment_status"]!=1){
                    throw new Exception("This Application payment is not clear please wait for clearance");
                }
                if(!$forwardBackwordRole){
                    throw new Exception($inputs["action_btn"]." Role Not Found");
                }
                $is_parked =false;
                $reciver_role_id = $forwardRole = $forwardBackwordRole["forward_role_id"];
                $backwardRole = $forwardBackwordRole["backword_role_id"];
                if(($getInitiatorRole["role_id"]??0)==$backwardRole && $inputs["action_btn"]=="Backward"){
                    $inputs["action_btn"] ="Back To Citizen";
                }
                if(($request_dtl["finiser_role_id"]??0)==$this->_emp_type && $inputs["action_btn"]=="Forward"){
                    $inputs["action_btn"] ="Approved";
                }
                if(in_array($inputs["action_btn"],["Backward"])){
                    $reciver_role_id =$backwardRole;
                }
                if(in_array($inputs["action_btn"],["Back To Citizen"])){
                    $reciver_role_id =$request_dtl["initiater_role_id"];
                    $is_parked =true;
                }
                if((!in_array($inputs["action_btn"],["Back To Citizen","Approved"])) && !$reciver_role_id){
                    throw new Exception("Forward Role Not Found");
                }
                if($permission["is_finiser"]=='f' && $inputs["action_btn"]=='Approved'){
                    throw new Exception("You cannot approved the application");
                }
                if($permission["can_reject"]=='f' && $inputs["action_btn"]=='Reject'){
                    throw new Exception("You cannot reject the application");
                }

                $lastRemarks = $this->_modelTblWfTrack->getLasRemarks($request_dtl["id"],$this->_modelTblConsumerRequest->table);
                $verification_status = $inputs["action_btn"]=="Forward"? 1:( $inputs["action_btn"]=="Backward"?1:( $inputs["action_btn"]=="Back To Citizen"?2:( $inputs["action_btn"]=="Reject"?3:( $inputs["action_btn"]=="Approved"?5:1))));                
                $updatePrivRemark = [
                    "reciver_user_id"     => $this->_emp_id,
                    "verification_status" => $verification_status,
                ];
                $insertRemarks =[
                    "ref_type"       => $this->_modelTblConsumerRequest->table,
                    "ref_value"      => $request_dtl["id"],
                    "sender_role_id" => $currentUserType,
                    "sender_user_id" => $this->_emp_id,
                    "reciver_role_id" => $reciver_role_id,
                    "remarks" => $inputs["level_remarks"],
                ];
                $app_update =[
                    "pending_status"    => $verification_status,
                    "max_role_attemped" => $request_dtl["max_role_attemped"] < $forwardBackwordRole["sl_no"] ? $forwardBackwordRole["sl_no"] : $request_dtl["max_role_attemped"],
                    "is_parked"         => $is_parked,
                ];
                if(!in_array($inputs["action_btn"],['Reject',"Approved"])){
                    $app_update["pending_at_role_id"]= $reciver_role_id;
                }
                if($request_dtl["is_parked"]=='t'){
                    $app_update["pending_at_role_id"] = $request_dtl["pending_at_role_id"]!=$request_dtl["initiater_role_id"]?$request_dtl["pending_at_role_id"]:$reciver_role_id;
                    $insertRemarks["reciver_role_id"] = $app_update["pending_at_role_id"];
                }
                if($is_parked){
                    $app_update["pending_at_role_id"] = $request_dtl["pending_at_role_id"];
                }
                if($permission && $permission["can_upload_doc"]=='t' && $inputs["action_btn"] == 'Forward'){
                    $app_update["doc_upload_status"] = 1;
                }
                if($permission && $permission["can_verify_doc"]=='t' && $inputs["action_btn"] == 'Forward'){
                    $app_update["doc_verify_status"] = 1;
                }
                

                $this->_db->transBegin();

                if(in_array($inputs["action_btn"],['Reject',"Approved"])){
                    $this->approvedReject($id);
                }
                $this->_modelTblConsumerRequest->updateData($request_dtl["id"],$app_update);
                $this->_modelTblWfTrack->updateData($lastRemarks["id"]??0,$updatePrivRemark);
                $this->_modelTblWfTrack->store($insertRemarks);
                
                if($this->_db->transStatus() === FALSE)
                {
                    throw new Exception("Something went wrong");
                }
                $this->_db->transCommit();
                return $this->response->redirect(base_url("WaterConsumerRequest/".$inputs["views"]??"outBox"));     

            }
            
        }catch(Exception $e){
            $this->_db->transRollback();
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function approvedReject($id){
        $request_dtl = $this->_modelTblConsumerRequest->where("MD5(id::TEXT)",$id)->get()->getFirstRow("array");
        $consumer_id = $request_dtl["consumer_id"];
        $inputs=arrFilterSanitizeString($this->request->getVar());
        
        $app_update["approval_rejected_date"]=date("Y-m-d H:i:s");
        $app_update["approval_rejected_emp_id"]=$this->_emp_id;                    
        $app_update["approval_rejected_emp_role_id"]=$this->_emp_type;
        if(in_array($inputs["action_btn"],['Forward',"Approved"])){
            if($request_dtl["request_type_id"]==1){  
                $oldOwners = $this->_consumer_details_model->consumerDetails($request_dtl["consumer_id"]);
                $old_owner_ids = array_map(function($val){
                    return $val['id'];
                },$oldOwners);
                $old_owner_ids = implode(',',$old_owner_ids);
                $new_owner_ids=""; 

                $this->_db->table("tbl_consumer_details")->where("consumer_id",$request_dtl["consumer_id"])->update(["status"=>0]);
                $newOwners = $this->_modelTblConsumerRequestDtl->where("request_id",$request_dtl["id"])->orderBy("id","ASC")->get()->getResultArray();
                foreach($newOwners as $val){
                    $inserArray = [
                        "consumer_id"=>$request_dtl["consumer_id"],
                        "applicant_name"=>$val["applicant_name"],
                        "father_name"=>$val["father_name"],
                        "city"=>$val["city"],
                        "district"=>$val["district"],
                        "state"=>$val["state"],
                        "mobile_no"=>$val["mobile_no"],
                        "emp_details_id"=>$val["emp_details_id"],
                        "status"=>$val["status"],
                    ];
                    $this->_consumer_details_model->insert($inserArray);
                    $new_owner_ids .= $this->_db->insertID().',';
                }
                $owner_name_transfer_log_arr=[];
                $owner_name_transfer_log_arr["request_id"]=$request_dtl["id"];
                $owner_name_transfer_log_arr['consumer_id'] = $request_dtl["consumer_id"];
                $owner_name_transfer_log_arr['old_owner_ids'] = rtrim($old_owner_ids,',');
                $owner_name_transfer_log_arr['new_owner_ids'] = rtrim($new_owner_ids,',');
                $owner_name_transfer_log_arr['remarks']       = $inputs['remarks'];						
                $owner_name_transfer_log_arr['ip_address']    = $_SERVER['REMOTE_ADDR'];
                $owner_name_transfer_log_arr['emp_detail_id']= $this->_emp_id;
                $inserted_id = $this->_Water_name_transfer_log_model->insertData($owner_name_transfer_log_arr);
                if(!$inserted_id)
                {
                    throw new Exception("Some Error Occurst Please Contact To Admin 1");
                }

            }
            if($request_dtl["request_type_id"]==3){
                $this->_model_view_water_consumer->where("id",$consumer_id)->update(["status"=>2]);
            }
        }
        if(in_array($inputs["action_btn"],['Reject'])){
            $app_update["status"] = 0; 
        }        
        $this->_modelTblConsumerRequest->updateData($request_dtl["id"],$app_update);
    }

    public function uploadDoc($id){
        try{
            $data = $inputs = arrFilterSanitizeString($this->request->getVar());
            $data["id"] = $id;
            $data["request_dtl"] = $this->_modelTblConsumerRequest->where("id",$id)->get()->getFirstRow("array");
            $requestType = $this->_modelTblRequestType->where("id",$data["request_dtl"]["request_type_id"])->get()->getFirstRow("array");
            $data["request_dtl"]["request_type"]=$requestType["request_type"]??"";
            $permission = $this->_modelTblWfRoleMapMstr->getWfMapPermission($data["request_dtl"]["wf_mstr_id"],$this->_emp_type);
            $data['required_doc_list'] = $this->HaveToUploadDoc($data["request_dtl"]);
            if($this->request->getMethod()=="post" && $data['request_dtl']['doc_verify_status']!=1){
                if(isset($inputs['btn_upload'])){
                    $rules = [
                        'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf,jpeg,jpg,png]',
                        
                    ];
                    if($this->validate($rules))
                    {
                        $upld_doc_path = $this->request->getFile('upld_doc_path');
                        if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved())
                        {
                            $ulb_dtl = $this->_model_ulb_mstr->getulb_list($this->_ulb_mstr_id);
                            try
                            {
                                $this->_db->transBegin();
                                $input = [
                                        'request_id' => $id,
                                        'doc_for' => isset($inputs["other_doc_name"]) && $inputs['other_doc']=="OTHER_DOC" ? strtoupper($inputs['other_doc_name']) :$inputs['other_doc'],
                                        'document_id' => $inputs['doc_mstr_id']? $inputs['doc_mstr_id'] :null,
                                        'emp_details_id' => $this->_emp_id,
                                    ];
        
                                if(($wtr_doc_dtl_id = $this->_modelTblRequestDoc->check_doc_exist($id, $inputs['other_doc'])) && $inputs['other_doc']!="OTHER_DOC")
                                {
                                    $delete_path = WRITEPATH.'uploads/'.$wtr_doc_dtl_id['doc_path'];
                                    if(file_exists($delete_path) && $wtr_doc_dtl_id['doc_path']!=null)
                                    // @unlink($delete_path);
                                    deleteFile($delete_path);
        
                                    $newFileName = md5($wtr_doc_dtl_id['id']);
                                    $file_ext = $upld_doc_path->getExtension();
        
                                    $path = $ulb_dtl['city']."/"."water_request_doc";
                                    $upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                    $upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                    $update_data=[
                                        "document_path"=>$upld_doc_path_save,
                                        "document_id" => $inputs['doc_mstr_id']? $inputs['doc_mstr_id'] :null,
                                        "verify_status"=>0,
                                    ];
                                    $this->_modelTblRequestDoc->updateData($wtr_doc_dtl_id["id"], $update_data);
                                
                                }
                                elseif(isset($inputs["other_doc_name"]) && ($wtr_doc_dtl_id = $this->_modelTblRequestDoc->check_doc_exist($id, strtoupper($inputs['other_doc_name']))) && $inputs['other_doc']=="OTHER_DOC"){
                                    $delete_path = WRITEPATH.'uploads/'.$wtr_doc_dtl_id['doc_path'];
                                    if(file_exists($delete_path) && $wtr_doc_dtl_id['doc_path']!=null)
                                    // @unlink($delete_path);
                                    deleteFile($delete_path);
        
                                    $newFileName = md5($wtr_doc_dtl_id['id']);
                                    $file_ext = $upld_doc_path->getExtension();
        
                                    $path = $ulb_dtl['city']."/"."water_request_doc";
                                    $upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                    $upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                    $update_data=[
                                        "document_path"=>$upld_doc_path_save,
                                        "document_id" => $inputs['doc_mstr_id']? $inputs['doc_mstr_id'] :null,
                                        "verify_status"=>0,
                                    ];
                                    $this->_modelTblRequestDoc->updateData($wtr_doc_dtl_id["id"], $update_data);
                                
                                }
                                elseif ($wtr_doc_dtl_id = $this->_modelTblRequestDoc->store($input))
                                {
                                    $newFileName = md5($wtr_doc_dtl_id);
                                    $file_ext = $upld_doc_path->getExtension();
                                    $path = $ulb_dtl['city']."/"."water_request_doc";
        
                                    $upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                    $upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                    $update_data=[
                                        "document_path"=>$upld_doc_path_save,
                                        "document_id" => $inputs['doc_mstr_id']? $inputs['doc_mstr_id'] :null,
                                        "verify_status"=>0,
                                    ];
                                    $this->_modelTblRequestDoc->updateData($wtr_doc_dtl_id, $update_data);
                                }
        
                                if ($this->_db->transStatus() === FALSE)
                                {
                                    $this->_db->transRollback();
                                    flashToast("message", "Oops, Document not uploaded.");
                                }
                                else
                                {
                                    $this->_db->transCommit();
                                    flashToast("message", "Document uploaded successfully.");
                                }
                            }
                            catch (Exception $e)
                            {                            
                                flashToast("message", $e->getMessage());
                            }
                        }
                    }
                    else
                    {                    
                        $errMsg = $this->validator->listErrors();
                        flashToast("message", $errMsg);
                    }
                    return $this->response->redirect(base_url('WaterConsumerRequest/uploadDoc/'.$id));
                }
                if(isset($inputs['action_btn']) && $inputs['action_btn']=='Forward'){
                    
                    $forwardBackwordRole = $this->_modelTblWfRoleMapMstr->getWfMapPermission($data["request_dtl"]["wf_mstr_id"],$data["request_dtl"]["initiater_role_id"]); 
                    $lastRemarks = $this->_modelTblWfTrack->getLasRemarks($data["request_dtl"]["id"],$this->_modelTblConsumerRequest->table);
                    $reciver_role_id =$data["request_dtl"]["pending_at_role_id"]==$data["request_dtl"]["initiater_role_id"] ? $forwardBackwordRole["forward_role_id"] :$data["request_dtl"]["pending_at_role_id"]  ;
                    $this->_db->transBegin();
                    $insertRemarks =[
                        "ref_type"       => $this->_modelTblConsumerRequest->table,
                        "ref_value"      => $data["request_dtl"]["id"],
                        "sender_role_id" => $this->_emp_type,
                        "sender_user_id" => $this->_emp_id,
                        "reciver_role_id" => $reciver_role_id,
                        "remarks" => $inputs["level_remarks"],
                    ];
                    $updatePrivRemark = [
                        "reciver_user_id"     => $this->_emp_id,
                        "verification_status" => 1,
                    ];
                    $app_update =[
                        "pending_status"    => 1,
                        "pending_at_role_id"=> $reciver_role_id,
                        "max_role_attemped" =>$data["request_dtl"]["max_role_attemped"] < $forwardBackwordRole["sl_no"] ? $forwardBackwordRole["sl_no"] : $data["request_dtl"]["max_role_attemped"],
                        "is_parked"         => false,
                        "doc_upload_status"=> 1,
                    ];
                    $this->_modelTblConsumerRequest->updateData($id,$app_update);
                    $this->_modelTblWfTrack->updateData($lastRemarks["id"]??0,$updatePrivRemark);
                    $this->_modelTblWfTrack->store($insertRemarks);
                    if ($this->_db->transStatus() === FALSE)
                    {
                        $this->_db->transRollback();
                        flashToast("message", "Oops, Document not uploaded.");
                    }
                    else
                    {
                        $this->_db->transCommit();
                        flashToast("message", "Document uploaded successfully.");
                        return $this->response->redirect(base_url('WaterConsumerRequest/viewDtl/'.$id));
                    }
                }
            }
            
            $data["uploaded_doc_list"]=$this->_modelTblRequestDoc->getAllActiveDocuments($id);
            $data["fullDocUpload"] = true;            
            $data["fullDocVerify"] = true;
            $testUploadDocIsNotTested = array_filter($data["uploaded_doc_list"],function($val){
                return (!$val["verify_status"])?true:false;
            });
            $uploaded_doc_list = $data["uploaded_doc_list"];
            $testRequiredDocIsNotUploaded = array_filter($data["required_doc_list"],function($val)use($uploaded_doc_list){ 
                $val=$val[0]??[];               
                $isUploaded = array_filter($uploaded_doc_list,function($val1)use($val){
                    return ($val1["doc_for"]==($val["doc_for"]??""))?true:false;
                });
                $isDocRejected =array_filter($isUploaded,function($val2){
                    return $val2["verify_status"]==2 ? true: false;
                });
                return ((!$isUploaded) && ($val["is_mandatory"]??0)==1)?true:false;
            });

            $testRequiredDocIsRejected = array_filter($data["required_doc_list"],function($val)use($uploaded_doc_list){ 
                $val=$val[0]??[];               
                $isUploaded = array_filter($uploaded_doc_list,function($val1)use($val){
                    return ($val1["doc_for"]==($val["doc_for"]??""))?true:false;
                });
                $isDocRejected =array_filter($isUploaded,function($val2){
                    return $val2["verify_status"]==2 ? true: false;
                });
                return (($isDocRejected) && ($val["is_mandatory"]??0)==1)?true:false;
            });

            if(($permission["can_upload_doc"]??"f")=="t" && ($testRequiredDocIsNotUploaded || $testRequiredDocIsRejected)){
                $data["fullDocUpload"] = false; 
            }
            if($permission["is_initiator"]=="f" || ($this->_emp_type != $data["request_dtl"]["pending_at_role_id"] && ($data["request_dtl"]["is_parked"]=='f'))){
                $data["fullDocUpload"] = false;
            }

            
            if(($permission["can_verify_doc"]??"f")=="t" && $testUploadDocIsNotTested){
                $data["fullDocVerify"] = false; 
            }
        }
        catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
        return view("Water/AppRequest/UploadDoc",$data);
    }

    public function HaveToUploadDoc($data)
	{
		$return=(array)null;
		if(in_array($data["request_type_id"], [1]))	// Holding No, SAF No
		{
            $return[]=$this->_modelDocumentMstr->getDocumentList("DEED");
            $return[]=$this->_modelDocumentMstr->getDocumentList("AFFIDAVIT");
            $return[]=$this->_modelDocumentMstr->getDocumentList("HOLDING_MEMO");
		}
		$return[]=$this->_modelDocumentMstr->getDocumentList('APPLICATION_FORM');
        $return[]=$this->_modelDocumentMstr->getDocumentList('LAST_PAYMENT_RECEIPT');
        $return[]=$this->_modelDocumentMstr->getDocumentList("OTHER_DOC");
		return $return;
	}

    public function verifyRejectDoc($md5AppId,$view="View"){
        try{
            $request_dtl = $this->_modelTblConsumerRequest->where("md5(id::text)",$md5AppId)->get()->getFirstRow("array");
            if(!$request_dtl){
                throw new Exception("Data Not Find");
            }
            $currentUserType = $this->_emp_type;
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            
            if($this->request->getMethod()=='post'&& $view=="inbox"){
                # Document Reject
                $uupdate_arr=[
                    'remarks'=> $inputs['remarks']??"",
                    'verify_status'=> isset($inputs["btn_reject"]) ? 2 : 1, 
                    'verified_by_emp_id'=> $this->_emp_id,
                    "verified_on"=>"Now()",
                ];
                if($uupdate_arr["verify_status"]==1 && $uupdate_arr["remarks"]==""){
                    $uupdate_arr["remarks"]="Approved";
                }
                $this->_modelTblRequestDoc->updateData($inputs["applicant_doc_id"],$uupdate_arr);
                if(isset($inputs["btn_reject"])){
                    flashToast("message", "Document rejected successfully");
                }

                # Document Verify
                if(isset($inputs["btn_verify"])){
                    flashToast("message", "Document approved successfully");
                }
                return $this->response->redirect(base_url('WaterConsumerRequest/viewDtl/'.$request_dtl["id"]."/".$view));
            }
        }
        catch(Exception $e){
            flashToast("message", $e->getMessage());
        }   
        return redirect()->back()->with('error', $e->getMessage());
    }

}
<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_transaction;
use App\Models\model_fy_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_collection;
use App\Models\model_tran_mode_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_cheque_details;
use App\Models\model_collection;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\WaterPaymentModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\model_view_trade_licence;
use App\Models\model_trade_licence;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_item_mstr;
use App\Models\model_prop_owner_detail;
use App\Models\model_firm_owner_name;
use App\Models\WaterViewConsumerModel;
use App\Models\water_consumer_details_model;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\TradeTradeItemsModel;

use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_saf_dtl;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_system_name;

use App\Models\model_emp_details;
use App\Models\water_applicant_details_model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterPenaltyModel;

use App\Models\model_govt_saf_dtl;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_govt_saf_tax;
use App\Models\model_govt_saf_tax_dtl;
use App\Models\model_govt_saf_demand_dtl;

use App\Models\Water\TblConsumerRequest;
use App\Models\Water\TblConsumerRequestDtl;

class citizenPaymentReceipt extends Controller
{
	protected $db;
	protected $dbSystem;
	protected $WaterPaymentModel;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_transaction;
	protected $model_fy_mstr;
	protected $model_saf_dtl;
	protected $model_prop_dtl;
	protected $model_collection;
	protected $model_saf_collection;
	protected $model_tran_mode_mstr;
	protected $model_cheque_details;
	protected $model_transaction_fine_rebet_details;
	protected $TradeApplyLicenceModel;
	protected $TradeTransactionModel;
	protected $TradeChequeDtlModel;
	protected $model_view_trade_licence;
	protected $model_trade_licence;
	protected $model_trade_licence_validity;
	protected $model_trade_item_mstr;
	protected $model_prop_owner_detail;
	protected $model_firm_owner_name;
	protected $WaterViewConsumerModel;
	protected $water_consumer_details_model;
	protected $TradeViewApplyLicenceOwnerModel;
	protected $TradeTradeItemsModel;

	protected $model_saf_memo_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_dtl;
	protected $model_saf_tax;
	protected $model_prop_tax;
	protected $model_system_name;
	protected $WaterPenaltyModel;

	protected $model_govt_saf_dtl;
	protected $model_govt_saf_officer_dtl;
	protected $model_govt_saf_floor_dtl;
	protected $model_govt_saf_tax;
	protected $model_govt_saf_tax_dtl;
	protected $model_govt_saf_demand_dtl;

	protected $payment_model;
	protected $consumer_details_model;
	protected $consumer_owner_details;
	protected $modelemp;
	protected $applicant_details;
	protected $_modelTblConsumerRequest;
	protected $_modelWaterViewConsumerModel;
	protected $_modelTblConsumerRequestDtl;

	public function __construct()
    {
		session();
    	helper(['form', 'db_helper', 'qr_code_generator_helper']);

        if($db_system = dbSystem()){
            $this->dbSystem = db_connect("db_system");
        }

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_system_name = new model_system_name($this->dbSystem);
    }



	public function saf_payment_receipt($ulb_id = null, $tran_no=null)
	{
		$data =(array)null;
		$data['tran_no'] = $tran_no;
		$path=base_url('citizenPaymentReceipt/saf_payment_receipt/'.$ulb_id.'/'.$tran_no);
		$qrcode=qrCodeGeneratorFun($path);
		if($ulb_mstr_dtl = getUlbDtl())
		{
			$this->db = db_connect($ulb_mstr_dtl['property']);
			$this->model_transaction = new model_transaction($this->db);
			$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);

			$data = $this->model_transaction->getBasicDtlForReceipt($tran_no);
			//print_var($data);
			$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['id']);
			$data["ulb_mstr_dtl"] = $ulb_mstr_dtl;
			$data["ulb_mstr_id"] = $ulb_id;
			$data["logo_path"] = $ulb_mstr_dtl["logo_path"];
			$data["ulb_mstr_name"] = $ulb_mstr_dtl["ulb_name"];
			$data['ss'] = $qrcode;;
			return view('citizen/SAF/citizen_saf_payment_receipt', $data);

		}
	}


	public function payment_jsk_receipt($ulb_id = null, $tran_no=null)
	{
		$data =(array)null;
		$data['tran_no'] = $tran_no;
		$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);

		if($ulb_mstr_dtl = getUlbDtl())
		{
			$this->db = db_connect($ulb_mstr_dtl['property']);
			$this->model_transaction = new model_transaction($this->db);
			$this->model_prop_dtl = new model_prop_dtl($this->db);
			$this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
			$this->model_cheque_details = new model_cheque_details($this->db);
			$this->model_collection = new model_collection($this->db);
			$this->model_transaction_fine_rebet_details = new model_transaction_fine_rebet_details($this->db);


			$data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_id);
			$data['tran_mode_dtl'] = $this->model_transaction->getTrandtlList($data['tran_no']);
			$data['tran_list'] = $data['tran_mode_dtl'];
			//print_var($data['tran_list']);
			$data['coll_dtl'] = $this->model_collection->collection_propdtl($data['tran_mode_dtl']['id']);
			$data['fyFrom'] = $this->model_fy_mstr->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
			$data['fyUpto'] = $this->model_fy_mstr->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
			$data['payMode'] = $this->model_tran_mode_mstr->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
			$data['holdingward'] = $this->model_prop_dtl->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
			$data['basic_details'] = $this->model_prop_dtl->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
			if($data['payMode']['id']==2 || $data['payMode']['id']==3){
				$data['chqDD_details'] = $this->model_cheque_details->mode_dtl($data['tran_no']);
			}
			//$data['penalty_dtl'] = $this->model_transaction_fine_rebet_details->penalty_dtl($data['tran_mode_dtl']['id']);
			$data['system_name'] = $this->model_system_name->system_name($data['tran_mode_dtl']['tran_date']);
		}
		$data['basic_receipt_details'] = $this->model_prop_dtl->basic_receipt_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		// print_var($data);
		return view('citizen/citizen_payment_receipt', $data);
	}

	//--------------------------------------------------------------------

	//---------Water --------------------


	public function view_transaction_receipt($ulb_id = null, $water_conn_id=null, $transaction_id=null)
	{
		$data =(array)null;
		$data['water_conn_id'] = $water_conn_id;
		$data['transaction_id'] = $transaction_id;
		//echo($water_conn_id);
		//$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_id.'/'.$water_conn_id.'/'.$transaction_id);
		$data["path"] = $path;
		$data['ss']=qrCodeGeneratorFun($path); 
		if($ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id)){ 
			$this->db = db_connect($ulb_mstr_dtl['water']);
			$this->payment_model=new WaterPaymentModel($this->db);
			$this->consumer_details_model=new WaterViewConsumerModel($this->db);
			$this->consumer_owner_details=new water_consumer_details_model($this->db);
			$a = $this->payment_model->fetch_all_application_data($water_conn_id);			
			$data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_id);
			$data['consumer_owner_details']=$this->consumer_owner_details->getConsumerDetailsbyMd5($water_conn_id);
			
			 $this->modelemp = new model_emp_details($this->db);
			 $this->applicant_details=new water_applicant_details_model($this->db);
			 $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);

			$data['ulb_id']=$ulb_id;
			//$data['applicant_details']=$this->payment_model->fetch_all_application_data($water_conn_id);
			$data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
			// print_var($transaction_id);die;
			$data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);
			$data['applicant_basic_details']=$this->applicant_details->getApplicantsName($water_conn_id);
			if(trim($data['transaction_details']['transaction_type'])=='Demand Collection')
			{
				$data['consumer_details']=$this->consumer_details_model->consumerDetails($water_conn_id);
				$where = array('id'=>$data['consumer_details']['apply_connection_id'],
                            'status!='=>0
                            );
				$id_water_app=md5($data['consumer_details']['apply_connection_id']);
				$data['applicant_details']=$this->payment_model->fetch_all_application_data($id_water_app);
				$data['consumer_owner_details']=$this->consumer_owner_details->getConsumerDetailsbyMd5($water_conn_id);
				$data['applicant_details']['ward_no']=$data['consumer_details']['ward_no'];
				$data['applicant_details']['address']=$data['consumer_details']['address'];
				if(!empty($data['consumer_owner_details']) && sizeof($data['consumer_owner_details'])>1)
				{
					$mobil='';
					$applicant='';
					$father_name="";
					$ward='';
					foreach($data['consumer_owner_details'] as $val)
					{
						$mobil.=$val['mobile_no'].',';
						$applicant=$val['applicant_details'].',';
						$father_name .= $val['father_name'].',';
					}
				$data['applicant_details']['applicant_name']=$applicant;
				$data['applicant_details']['mobile_no']=$mobil;
				$data['applicant_details']['father_name']=$father_name;
				}
				elseif(!empty($data['consumer_owner_details']))
				{
					$woner = $data['consumer_owner_details'][0];
					$data['applicant_details']['applicant_name']=$woner['applicant_name'];
					$data['applicant_details']['mobile_no']=$woner['mobile_no'];
					$data['applicant_details']['father_name']=$woner['father_name'];

				}
				$data['meter_reading']=$this->payment_model->meter_reding_for_recipt($data['transaction_details']['id']);
				$data['adjustment_amount'] =  $this->WaterPenaltyModel->get_tbl_adjustment_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);
        		$data['advance_amount'] =  $this->WaterPenaltyModel->get_tbl_advance_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);

			}
			elseif(in_array(trim($data['transaction_details']['transaction_type']),['New Connection',"Site Inspection"]))
			{
				$where = array('md5(id::text)'=>$water_conn_id,
                            'status!='=>0
                            );
				$data['applicant_details']=$this->payment_model->fetch_all_application_data($water_conn_id);
			}

		}
		
		return view('citizen/citizen_water_user_charge_payment_receipt', $data);
	}

	private function setDbWater($ulb_id=1){
		$ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id);
		$this->db = db_connect($ulb_mstr_dtl['water']);
		$this->payment_model=new WaterPaymentModel($this->db);
		$this->consumer_details_model=new WaterViewConsumerModel($this->db);
		$this->consumer_owner_details=new water_consumer_details_model($this->db);
		$this->modelemp = new model_emp_details($this->db);
		$this->applicant_details=new water_applicant_details_model($this->db);
		$this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
		$this->_modelTblConsumerRequest = new TblConsumerRequest($this->db);
		$this->_modelWaterViewConsumerModel = $this->consumer_details_model;
		$this->_modelTblConsumerRequestDtl = new TblConsumerRequestDtl($this->db);
	}

	public function viewWaterRequestTranReceipt($ulb_id = null,  $transaction_id=null){
		$this->setDbWater($ulb_id);
		$data =(array)null;
		$data['transaction_id'] = $transaction_id;
		$path=base_url('citizenPaymentReceipt/viewWaterRequestTranReceipt/'.$ulb_id.'/'.$transaction_id);
		$data["path"] = $path;
		$data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);    
        $data["request_dtl"] = $this->_modelTblConsumerRequest->where("id",$data['transaction_details']["related_id"])->get()->getFirstRow("array");  
        $data["consumer_details"] = $this->_modelWaterViewConsumerModel->consumerDetails(md5($data["request_dtl"]["consumer_id"]));  
        $data['applicant_details']=$this->_modelTblConsumerRequestDtl->where("id",$data['transaction_details']["related_id"])->where("status",1)->get()->getResultArray();   
        if($data['request_dtl']["request_type_id"]!=1){
            $data['applicant_details']=$this->consumer_owner_details->consumerDetails($data['consumer_details']['id']); 
        }     
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_id);
        $data["appId"]=$data['transaction_details']["related_id"];
		return view('Water/AppRequest/payment_receipt',$data); 
	}



	public function view_trade_transaction_receipt($ulb_id = null,$applyid = null,$transaction_id = null)
    {

        $data =(array)null;
		$data['applyid'] = $applyid;
		$data['transaction_id']=$transaction_id;
        //$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $path=base_url('citizenPaymentReceipt/view_trade_transaction_receipt/'.$ulb_id.'/'.$applyid.'/'.$transaction_id);
        $data['ss']=qrCodeGeneratorFun($path);

		if($ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id))
		{
			$this->db = db_connect($ulb_mstr_dtl['trade']);
			$this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
			$this->TradeTransactionModel = new TradeTransactionModel($this->db);
			$this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
			//print_r($ulb_mstr_dtl);
			$data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_id);
			$data['applicant_details']=$this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
			$data['transaction_details']=$this->TradeTransactionModel->transaction_details($transaction_id);
			//echo $data['applicant_details']['ward_mstr_id'];
			$data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_id);
			$data['delayApplyLicence'] = isset($data['rebet']['0']['amount'])?$data['rebet']['0']['amount']:0;
			$data['denialApply'] = isset($data['rebet']['1']['amount'])?$data['rebet']['1']['amount']:0;

			$data['ward_no']=$this->model_ward_mstr->getWardNoBywardId($data['applicant_details']['ward_mstr_id']);
			//$data['ward_no']=$warddet["ward_no"];
			$data['cheque_details']=$this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
		}

		return view('citizen/trade/payment_receipt',$data);
    }


	public function view_trade_provisinal_receipt($ulb_id = null,$applyid = null)
    {

        $data =(array)null;
		$data['applyid'] = $applyid;
		//$data['transaction_id']=$transaction_id;
        //$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $path=base_url('citizenPaymentReceipt/view_trade_provisinal_receipt/'.$ulb_id.'/'.$applyid);
        $data['ss']=qrCodeGeneratorFun($path);

		if($ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id)){
			$this->db = db_connect($ulb_mstr_dtl['db_trade']);
			$this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
			$this->TradeTransactionModel = new TradeTransactionModel($this->db);
			$this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
			$this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
			$this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);

			$data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_id);
			$data['basic_details'] = $this->TradeViewApplyLicenceOwnerModel->getDatabyid($applyid);
			$vUpto = $data['basic_details']['apply_date'];
			//$data["valid_upto"] = date('Y-m-d', strtotime(date($vUpto) , mktime(time())) ." + 20 day"));
			$data["valid_upto"] = date('Y-m-d',strtotime(date("$vUpto", mktime(time())) . " + 20 day"));
			$apply_licence_id=$data['basic_details']['id'];
			//$data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($applyid);
			$data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($applyid);
			$data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
		}

		return view('citizen/trade/provisional_licence',$data);

    }

	public function municipal_licence($ulb_id = null,$id=null)
	{
        $data =(array)null;
        $Session = Session();


        $emp_mstr = $Session->get("emp_details");
		$path=base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_id.'/'.$id);
        $data['ss']=qrCodeGeneratorFun($path);
		if($ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id)){
			$this->db = db_connect($ulb_mstr_dtl['db_trade']??"db_rmc_trade");
			$this->model_view_trade_licence = new model_view_trade_licence($this->db);
			$this->model_trade_licence = new model_trade_licence($this->db);
			$this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
			$this->model_trade_item_mstr = new model_trade_item_mstr($this->db);
			$this->model_firm_owner_name = new model_firm_owner_name($this->db);

			$login_emp_details_id = $emp_mstr["id"];
			$sender_user_type_id = $emp_mstr["user_type_mstr_id"];
			$data['ulb'] = $this->model_ulb_mstr->getulb_list($ulb_id);
			$data['ulb_mstr_id']=$data['ulb']['ulb_mstr_id'];
			$data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
			$apply_licence_id=$data['basic_details']['id'];
			$data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);

			$data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);

			$sign = "dmcsign.png"; 
			if($basic_details['license_date']>='2025-01-16'){
				$sign = "gautam.png"; 
			}
			$data["signature_path"]=base_url("/writable/eo_sign/$sign");
			if($data["basic_details"]["approved_by"]){
				$empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data["basic_details"]["approved_by"])->getFirstRow("array");
				$data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
			}       
		}
		return view('citizen/trade/municipal_licence', $data);

    }


	public function da_eng_memo_receipt($ulb_id, $memo_id, $enghing="ENG") {
        $Session = Session();
        $data =(array)null;
        $data['memo_id']=$memo_id;

		if($ulb_mstr_dtl = getUlbDtl())
		{
			$this->db = db_connect($ulb_mstr_dtl['property']);
			$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
			$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
			$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
			$this->model_saf_tax = new model_saf_tax($this->db);
			$this->model_prop_tax = new model_prop_tax($this->db);
			$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);

			$data['path']=base_url('citizenPaymentReceipt/da_eng_memo_receipt/'.$ulb_id.'/'.$memo_id);

			$data['ss']=qrCodeGeneratorFun($data['path']);

			$data['ulb']=$ulb_mstr_dtl;//$this->model_ulb_mstr->getULBById_MD5($ulb_id);

			$data['memo'] = $this->model_saf_memo_dtl->getMemoById_MD5($memo_id);
			$sql = "SELECT * FROM view_emp_details WHERE id=".$data['memo']["emp_details_id"];
			$empDtl = $this->dbSystem->query($sql)->getFirstRow("array");
			$data['memo']["verify_user_type_id"]=$empDtl["user_type_mstr_id"];
			//print_var($data);
			if(!$data['memo']){
				die("We could not fetch memo");
			}
			$data['owner_list'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['memo']['prop_dtl_id']]);
			$data['saf'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['memo']['saf_dtl_id']));
			/****saf tax******/
			$data['prop_tax'] = $this->model_saf_tax->getMaxTaxDtl($data['memo']['saf_dtl_id']);

			$data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);

			if($data['memo']['memo_type']=='SAM')
			{
				if ($enghing=="HIN") {
					return view('property/saf/da_hin_memo_receipt', $data);
				} else  {
					return view('property/saf/da_eng_memo_receipt', $data);
				}
			}
			else
			{
				$data['saf_tax'] = $data['prop_tax'];
				$data['prop_tax_dtl_single'] = $this->model_prop_tax->getdetails_propdtlid($data['memo']['prop_dtl_id']);
				$data['prop_tax_dtl'] = $this->model_prop_tax->getdetails_propdtlid($data['memo']['prop_dtl_id']);
				// print_var($data['prop_tax_dtl']);
				foreach($data['prop_tax_dtl'] as $key => $value)
				{
					$saftax = $this->model_saf_tax->getalltaxfyqtridbysafid($data['memo']['saf_dtl_id'], $value['fyear'],$value['qtr']);
					$data['prop_tax_dtl'][$key]['fyy'] = $value['fyear'];
					$data['prop_tax_dtl'][$key]['holding_tx'] = $saftax['holding_tax'];
				}
				//print_var($data);
				$degignation ="Deputy";
				$sign="";
				if ($data['memo']["verify_user_type_id"]==9) { 
					if($memo["emp_details_id"] == '1499'){
						$sign = "/girishprasad_sign.png";
					}else if($memo["emp_details_id"] == '1252'){
						$sign = "/nishant_tirky.png";
					}else if($memo["emp_details_id"] == '845'){
						$sign = "/mritunjay_kumar.jpg";
					}else if($memo["emp_details_id"] == '1615'){
						$sign = "/1615.png";
					}elseif ($memo["emp_details_id"] == '1688') {
						$sign = "/1688.png";
					}
					elseif(date('Y-m-d',strtotime($memo["created_on"]))>'2024-12-01'){
						$sign = "/1615.png";
					}else{
						$sign = "/robinkachhap.jpeg";
					}
				} else { 
					$sign = "dmcsign.png";                                     
					$degignation = "Additional";
					if($memo["created_on"]<'2024-09-28'){
						$sign="dmcsign_old.png";
						$degignation = "Additional";
					}
					if($memo["created_on"]<'2024-02-15'){
						$sign="rajnishkumar_sign.png";
					}
					if($data['memo']["emp_details_id"]=='1661'){
						$sign = "gautam.png"; 
						$degignation = "Deputy";
					}
					if($data['memo']["emp_details_id"]=='1718'){
						$degignation = "Additional";
					}
				} 
				$data["degignation"]=$degignation;
				$data["signature_path"]=base_url('writable/eo_sign/'.$sign);
				if($data['memo']["emp_details_id"]){
					$empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data['memo']["emp_details_id"])->getFirstRow("array");
					$data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
				}

				return view('property/saf/memo_receipt', $data);
			}
		}
    }
	public function da_eng_memo_receipt2($ulb_id, $memo_id, $enghing="ENG")
	{
        $data =(array)null;
        $data['memo_id']=$memo_id;
		if($ulb_mstr_dtl = $this->model_ulb_mstr->getULBById_MD5($ulb_id))
		{
			$this->db = db_connect($ulb_mstr_dtl['db_property']);
			$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
			$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
			$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
			$this->model_saf_tax = new model_saf_tax($this->db);
			$this->model_prop_tax = new model_prop_tax($this->db);
			$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);

			$data['path']=base_url('citizenPaymentReceipt/da_eng_memo_receipt/'.$ulb_id.'/'.$memo_id);

			$data['ss']=qrCodeGeneratorFun($data['path']);

			$data['ulb']=$ulb_mstr_dtl;//$this->model_ulb_mstr->getULBById_MD5($ulb_id);

			$sql = "SELECT
						tbl_saf_memo_dtl.id,
						view_ward_mstr.ward_no,
						new_ward.ward_no AS new_ward_no,
						tbl_saf_dtl.saf_no,
						tbl_saf_dtl.assessment_type,
						tbl_saf_dtl.prop_address,
						tbl_saf_memo_dtl.memo_no,
						tbl_saf_memo_dtl.holding_no,
						tbl_saf_owner_detail.owner_name,
						tbl_saf_memo_dtl.created_on,
						tbl_saf_tax.*
					FROM tbl_saf_memo_dtl
					INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
					INNER JOIN (
						SELECT
							max(id) AS max_id, saf_dtl_id
						FROM tbl_saf_tax
						WHERE status=1
						GROUP BY saf_dtl_id
					) AS tax_max_dtl ON tax_max_dtl.saf_dtl_id=tbl_saf_dtl.id
					INNER JOIN tbl_saf_tax ON tbl_saf_tax.id=tax_max_dtl.max_id
					INNER JOIN view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
					INNER JOIN view_ward_mstr AS new_ward on new_ward.id=tbl_saf_dtl.new_ward_mstr_id
					JOIN (
						SELECT saf_dtl_id,
						CONCAT(STRING_AGG(owner_name, ', '), ' ', STRING_AGG(relation_type, ', '), ' ', STRING_AGG(guardian_name, ', ')) AS owner_name
						FROM tbl_saf_owner_detail WHERE status=1
						GROUP BY saf_dtl_id
					) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id=tbl_saf_memo_dtl.saf_dtl_id
					WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.status=1
					AND MD5(tbl_saf_memo_dtl.id::TEXT)='".$memo_id."'
					ORDER BY tbl_saf_memo_dtl.created_on DESC";
			/* if ($result = $this->db->query($sql)) {

			} else {
				die("We could not fetch memo");
			} */
			$data['memo'] = $this->model_saf_memo_dtl->getMemoById_MD5($memo_id);
			//print_var($data);
			if(!$data['memo']){
				die("We could not fetch memo");
			}
			//print_var($data['memo']);exit;
			$data['owner_list'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['memo']['prop_dtl_id']]);
			//$data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['memo']['saf_dtl_id']);
			$data['saf'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['memo']['saf_dtl_id']));
			/****saf tax******/
			$max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($data['memo']['saf_dtl_id']);
			$max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($data['memo']['saf_dtl_id'],$max_fy_id['max_fy_id']);
			$data['saf_tax'] = $this->model_saf_tax->getallmaxfyqtridbysafid($data['memo']['saf_dtl_id'],$max_fy_id['max_fy_id'],$max_qtr['max_qtr']);
			/*********/
			/****prop tax******/

			$max_fy_id = $this->model_prop_tax->getmaxfyidbypropid($data['memo']['prop_dtl_id']);
			$max_qtr = $this->model_prop_tax->getmaxfyqtridbypropid($data['memo']['prop_dtl_id'],$max_fy_id['max_fy_id']);
			$data['prop_tax'] = $this->model_prop_tax->getallmaxfyqtridbypropid($data['memo']['prop_dtl_id'],$max_fy_id['max_fy_id'],$max_qtr['max_qtr']);
			/*********/

			$data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);

			if($data['memo']['memo_type']=='SAM')
			{
				if ($enghing=="HIN") {
					return view('property/saf/da_hin_memo_receipt', $data);
				} else  {
					return view('property/saf/da_eng_memo_receipt', $data);
				}
			}
			else
			{
				$data['prop_tax_dtl'] = $this->model_prop_tax->getdetails_propdtlid($data['memo']['prop_dtl_id']);
				// print_var($data['prop_tax_dtl']);
				foreach($data['prop_tax_dtl'] as $key => $value)
				{
					//print_var($value);
					//$fy = $this->model_fy_mstr->getFiyrByid($value['fy_mstr_id']);
					$saftax = $this->model_saf_tax->getalltaxfyqtridbysafid($data['memo']['prop_dtl_id'], $value['fyear'],$value['qtr']);
					$data['prop_tax_dtl'][$key]['fyy'] = $value['fyear'];
					$data['prop_tax_dtl'][$key]['holding_tx'] = $saftax['holding_tax'];
				}


				return view('property/saf/memo_receipt', $data);
			}
		}
    }

	public function govt_da_eng_memo_receipt($ulb_id, $memo_id, $enghing="ENG") {
        $Session = Session();
        $data =(array)null;
        $data['memo_id']=$memo_id;
		

		if($ulb_mstr_dtl = getUlbDtl())
		{
			
			$this->db = db_connect($ulb_mstr_dtl['property']);
			$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
			$this->model_govt_saf_officer_dtl = new model_govt_saf_officer_dtl($this->db);
			$this->model_govt_saf_dtl = new model_govt_saf_dtl($this->db);
			$this->model_govt_saf_tax = new model_govt_saf_tax($this->db);
			$this->model_prop_tax = new model_prop_tax($this->db);
			$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);

			$data['path']=base_url('citizenPaymentReceipt/govt_da_eng_memo_receipt/'.$ulb_id.'/'.$memo_id);

			$data['ss']=qrCodeGeneratorFun($data['path']);

			$data['ulb']=$ulb_mstr_dtl;//$this->model_ulb_mstr->getULBById_MD5($ulb_id);

			$data['memo'] = $this->model_saf_memo_dtl->getMemoById_MD5($memo_id);
			
			$sql = "SELECT * FROM view_emp_details WHERE id=".$data['memo']["emp_details_id"];
			$empDtl = $this->dbSystem->query($sql)->getFirstRow("array");
			$data['memo']["verify_user_type_id"]=$empDtl["user_type_mstr_id"];
			//print_var($data);
			if(!$data['memo']){
				die("We could not fetch memo");
			}
			$data['owner_list'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['memo']['prop_dtl_id']]);
			$data['saf'] = $this->model_govt_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['memo']['govt_saf_dtl_id']));
			/****saf tax******/
			$data['prop_tax'] = $this->model_govt_saf_tax->getMaxTaxDtl($data['memo']['govt_saf_dtl_id']);

			$data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);

			if($data['memo']['memo_type']=='SAM')
			{
				if ($enghing=="HIN") {
					return view('property/saf/govt_da_hin_memo_receipt', $data);
				} else  {
					return view('property/saf/govt_da_eng_memo_receipt', $data);
				}
			}
			else
			{
				$data['saf_tax'] = $data['prop_tax'];
				$data['prop_tax_dtl_single'] = $this->model_prop_tax->getdetails_propdtlid($data['memo']['prop_dtl_id']);
				$data['prop_tax_dtl'] = $this->model_prop_tax->getdetails_propdtlid($data['memo']['prop_dtl_id']);
				// print_var($data['prop_tax_dtl']);
				foreach($data['prop_tax_dtl'] as $key => $value)
				{
					$saftax = $this->model_govt_saf_tax->getalltaxfyqtridbysafid($data['memo']['govt_saf_dtl_id'], $value['fyear'],$value['qtr']);
					$data['prop_tax_dtl'][$key]['fyy'] = $value['fyear'];
					$data['prop_tax_dtl'][$key]['holding_tx'] = $saftax['holding_tax'];
				}
				//print_var($data);
				
				return view('property/gsaf/govt_memo_receipt', $data);
			}
		}
    }


	public function view_memo($apply_conn_id_md5,$ulb_id = null)
    {

        $data = array();
		$data['ulb']=session()->get('ulb_dtl');		
		$data['citize'] = true;			
		if($ulb_mstr_dtl = $this->model_ulb_mstr->getUlbMsrtDtlByMD5ID($ulb_id))
		{
			$this->db = db_connect(isset($ulb_mstr_dtl['db_water'])?$ulb_mstr_dtl['db_water']:$ulb_mstr_dtl['water']);
			$this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);			
			//$data['ulb']=$ulb_mstr_dtl;
			if(empty($data['ulb']))
			{
				$data['ulb'] = $ulb_mstr_dtl;				
				$data['ulb']['ulb_mstr_id']=$ulb_id;
				$data['ulb']['ulb_name_hindi']= $data['ulb']['description']??$data['ulb']['ulb_name_hindi'];		
				$data['ulb']['logo_path']= $data['ulb']['logo_path'];
			}
			$data['ulb_mstr_name']=$ulb_mstr_dtl;	
			$data['ulb_mstr_name']['ulb_mstr_id']=$ulb_id;	
			//print_var($data['ulb_mstr_name']);
			$sql =" with level_pending as(
						select * from tbl_level_pending
						where receiver_user_type_id = 16 and verification_status=1
						and md5(apply_connection_id::text) = '$apply_conn_id_md5'
					),
					owner as (
						select distinct(apply_connection_id) as apply_connection_id,
						string_agg( applicant_name,' , ')as ownere_name,
							string_agg( father_name,' , ')as father_name,
							string_agg(cast( mobile_no as varchar(10)),' , ')as mobile_no
						from tbl_applicant_details
						where md5(apply_connection_id::text) = '$apply_conn_id_md5'
							--and status = 1
						group by apply_connection_id
					),
					transaction as (
						select  distinct(related_id) as related_id,
							string_agg( transaction_no::text,' , ')as transaction_no,
							string_agg( transaction_date::text,' , ')as transaction_date,
							sum(case when transaction_type='New Connection' then total_amount else 0 end) as conn_fee,
							sum(case when transaction_type='Site Inspection' then total_amount else 0 end) as extra_charge,
							sum(total_amount) as total
						from tbl_transaction
						where md5(related_id::text) = '$apply_conn_id_md5'
						and transaction_type in ('New Connection','Site Inspection')
						group by related_id
					),
					charge as (
						select distinct(ap.id) as connection_id,
							sum(ch.conn_fee) as total_charge,
							sum(ch.penalty) as penalty,
							sum(case when charge_for ='New Connection' then conn_fee else 0 end )as conn_fee,
							sum(case when charge_for ='Site Inspection' then conn_fee else 0 end) as Site_Inspection
						from tbl_apply_water_connection ap
						join tbl_connection_charge ch on ch.apply_connection_id=ap.id
							and ch.status=1
						where md5(ap.id::text)='$apply_conn_id_md5'
						group by ap.id
					),
					je as(
						select *
						from view_site_inspection_details 
						where id = (select max(id) 
									 from view_site_inspection_details 
								  where  verified_by='JuniorEngineer' and status=1 
									and md5(apply_connection_id::text) = '$apply_conn_id_md5'
								  )
					),
					ae as(
						select *
						from view_site_inspection_details 
						where id = (select max(id) 
									 from view_site_inspection_details 
								  where  verified_by='AssistantEngineer' and status=1 
									and md5(apply_connection_id::text) = '$apply_conn_id_md5'
								  )
					)

					select ap.id,ap.application_no,ap.apply_date,date_part('year',ap.apply_date) as year,
						ap.address,ap.holding_no,ap.apply_from,ap.area_sqmt,ap.category,
						tbl_consumer.consumer_no,
						o.ownere_name,o.father_name,o.mobile_no,
						w.ward_no,l.send_date as recieved_date,l.forward_date as verify_date,
						t.transaction_no,t.transaction_date,
						t.conn_fee,t.extra_charge,t.total as total_diposit,
						ch.conn_fee,ch.penalty,ch.total_charge,ch.Site_Inspection,
						p.pipeline_type,
						case when ae.pipeline_size_type notnull then ae.pipeline_size_type 
							When je.pipeline_size_type notnull then je.pipeline_size_type
							else 'N/A' end as pipeline_size_type,
						case when ae.pipe_type notnull then ae.pipe_type 
							When je.pipe_type notnull then je.pipe_type
							else 'N/A' end as pipe_type,
						case when ae.pipeline_size notnull then ae.pipeline_size 
							When je.pipeline_size notnull then je.pipeline_size
							else 'N/A' end as pipeline_size,
						case when ae.ferrule_type notnull then ae.ferrule_type 
							When je.ferrule_type notnull then je.ferrule_type
							else 'N/A' end as ferrule_type,    
						case when ae.pipe_size notnull then ae.pipe_size 
							When je.pipe_size notnull then je.pipe_size
							else 'N/A' end as pipe_size,
						case when ae.road_type notnull then ae.road_type 
							When je.road_type notnull then je.road_type
							else 'N/A' end as road_type,
						je.emp_details_id as emp_details_id,
						'Junior Engineer' as user_type,
						case when l.emp_details_id notnull then l.emp_details_id 
							When l.receiver_user_id notnull then l.receiver_user_id
							else null end as eo_id
					from tbl_apply_water_connection ap
					join tbl_consumer on tbl_consumer.apply_connection_id = ap.id
					join owner o on o.apply_connection_id = ap.id
					join level_pending l on l.apply_connection_id = ap.id
					join view_ward_mstr w on w.id = ap.ward_id
					left join transaction t on t.related_id = ap.id
					join charge ch on ch.connection_id=ap.id
					join tbl_pipeline_type_mstr p on p.id = ap.pipeline_type_id
					left join ae on ae.apply_connection_id = ap.id     
                	left join je on je.apply_connection_id = ap.id
					where md5(ap.id::text)='$apply_conn_id_md5'
			";
			$data['data']=$this->apply_waterconn_model->getDataRowQuery($sql);	
			$data['eo_signatur']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/signetur/1411.png');
			$data['je_signatur']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/signetur/1399.png');			
			if(sizeof($data['data'])>0)
			{
				$data['data']=$data['data'][0];
				if(!empty($data['data']['eo_id']))
				{                
					$eo_signatur = APPPATH.'../public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['eo_id'].'.png';
					if(file_exists($eo_signatur))
						$data['eo_signatur'] = base_url('public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['eo_id'].'.png');

					$empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data['data']['eo_id'])->getFirstRow("array");  
					$data["eo_signatur"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["eo_signatur"] ;  
				} 
				if(!empty($data['data']['emp_details_id']))
				{                
					$je_signatur = APPPATH.'../public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['emp_details_id'].'.png';
					if(file_exists($je_signatur))
						$data['je_signatur']= base_url('public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['emp_details_id'].'.png');

					$empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data['data']['emp_details_id'])->getFirstRow("array");  
					$data["je_signatur"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["je_signatur"] ;
				}  
			}
			//print_var($sql);die;
			$path=base_url('citizenPaymentReceipt/view_memo/'.$apply_conn_id_md5."/".$ulb_id);
			$data['ss']=qrCodeGeneratorFun($path);
			// return view('citizen/water/memo',$data);
			return view('water/water_connection/memo',$data);
		}
    }



}

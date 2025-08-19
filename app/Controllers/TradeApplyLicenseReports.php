<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_trade_transaction;
use App\Models\model_application_type_mstr;
use App\Models\TradeTransactionModel;




class TradeApplyLicenseReports extends AlphaController
{
    
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $ward_model;
    protected $apply_license;
	protected $model_trade_level_pending_dtl;
    protected $model_trade_transaction;
    protected $model_application_type_mstr;
    protected $TradeTransactionModel;

    public function __construct(){

        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
        //print_r($get_ulb_detail);

        $this->ulb_id=$get_ulb_detail['ulb_mstr_id'];


        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
       
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->trade);
		$this->model_trade_level_pending_dtl=new model_trade_level_pending_dtl($this->trade);
        $this->model_trade_transaction=new model_trade_transaction($this->trade);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);

    }


    //reports 
    public function report()
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            
            if($data['ward_id']!="all")
            {   $data['ward_id_name']=array_filter($data['ward_list'], function($ward) use($ward_id){
                                            if($ward['id']==$ward_id)
                                            {return true;}
                                            else{return false;}

                                        });
                $data['ward_id_name']=array_values($data['ward_id_name']);
                $data['ward_id_name']=$data['ward_id_name'][0]['ward_no'];
                // print_var($data['ward_id_name']);print_var($data['ward_id']);die;
                $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
                $jskwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=0 AND ward_mstr_id=".$data['ward_id'];
                $bocwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=1 AND pending_status = 0  AND document_upload_status = 0 AND ward_mstr_id=".$data['ward_id'];
                $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
               // $data['ward_id_name']= $this->ward_model->getWardNo_Byid($data['ward_id']);
               // $data['ward_id_name']= $data['ward_id_name']['ward_no'];
                $where_collection=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
                $levelwhere="forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
                $lvlpendngwhere="tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];

            }
            else 
            {   
                $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $jskwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=0";
                $bocwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=1 AND pending_status = 0  AND document_upload_status = 0";
                $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $data['ward_id_name'] = "All";
                $where_collection=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $lvlpendngwhere=" tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";

            }
            $data['newapplyLicense']=$this->TradeApplyLicenceModel->newapplyLicensereport($colwhere);
			$data['renewapplyLicense']=$this->TradeApplyLicenceModel->renewapplyLicensereport($colwhere);
  			$data['amendapplyLicense']=$this->TradeApplyLicenceModel->amendapplyLicensereport($colwhere);
 			$data['surrendapplyLicense']=$this->TradeApplyLicenceModel->surrendapplyLicensereport($colwhere);
			$data['totalapplyLicense'] = $data['newapplyLicense']['count']+$data['renewapplyLicense']['count']+$data['amendapplyLicense']['count']+$data['surrendapplyLicense']['count'];
 			$data['backapplyLicense']=$this->model_trade_level_pending_dtl->back_to_citizen($levelwhere);
  			$data['pendingapplyLicense']=$this->model_trade_level_pending_dtl->pending_at_level($lvlpendngwhere);
  			$data['pndjskapplyLicense']=$this->TradeApplyLicenceModel->pndingjskapplyLicense($jskwhere);
            $data['pndbocapplyLicense']=$this->TradeApplyLicenceModel->pndingjskapplyLicense($bocwhere);
 			$data['finalapplyLicense']=$this->model_trade_level_pending_dtl->final_licence($levelwhere);
 			$data['finalapplyLicense']=$this->model_trade_level_pending_dtl->final_licence($levelwhere);
            $data['total_rejected_form']=$this->model_trade_level_pending_dtl->total_rejected_form($levelwhere);
            $data['totalprovisional'] = $this->model_trade_level_pending_dtl->totalprovisional($where);


             // amount collection 
             $data['newlicencecollection']=$this->TradeApplyLicenceModel->newlicence_collection($where_collection);
             $data['renewlicencecollection']=$this->TradeApplyLicenceModel->renewlicence_collection($where_collection);
             $data['amendmentcollection']=$this->TradeApplyLicenceModel->amendment_collection($where_collection);
        }
        else{

            $data['to_date'] = date('Y-m-d');
            $data['from_date'] = date('Y-m-d');
            $data['ward_id_name'] = "All";
            $data['ward_id'] = "all";
            $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $lvlpendngwhere=" tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $jskwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=0 ";
            $bocwhere = " apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=1 AND pending_status = 0  AND document_upload_status = 0 ";
            $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $where_collection=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $data['newapplyLicense']=$this->TradeApplyLicenceModel->newapplyLicensereport($colwhere);
			$data['renewapplyLicense']=$this->TradeApplyLicenceModel->renewapplyLicensereport($colwhere);
			$data['amendapplyLicense']=$this->TradeApplyLicenceModel->amendapplyLicensereport($colwhere);
			$data['surrendapplyLicense']=$this->TradeApplyLicenceModel->surrendapplyLicensereport($colwhere);
			$data['totalapplyLicense'] = $data['newapplyLicense']['count']+$data['renewapplyLicense']['count']+$data['amendapplyLicense']['count']+$data['surrendapplyLicense']['count'];
 			$data['backapplyLicense']=$this->model_trade_level_pending_dtl->back_to_citizen($levelwhere);
			$data['pendingapplyLicense']=$this->model_trade_level_pending_dtl->pending_at_level($lvlpendngwhere);
            $data['pndjskapplyLicense']=$this->TradeApplyLicenceModel->pndingjskapplyLicense($jskwhere);
            $data['pndbocapplyLicense']=$this->TradeApplyLicenceModel->pndingjskapplyLicense($bocwhere);
			$data['finalapplyLicense']=$this->model_trade_level_pending_dtl->final_licence($levelwhere);
            $data['total_rejected_form']=$this->model_trade_level_pending_dtl->total_rejected_form($levelwhere);
            $data['totalprovisional'] = $this->model_trade_level_pending_dtl->totalprovisional($where);


             // amount collection 
             $data['newlicencecollection']=$this->TradeApplyLicenceModel->newlicence_collection($where_collection);
             $data['renewlicencecollection']=$this->TradeApplyLicenceModel->renewlicence_collection($where_collection);
             $data['amendmentcollection']=$this->TradeApplyLicenceModel->amendment_collection($where_collection);
        }
        return view('report/trade_apply_license_report',$data);
    }


        public function pendingAtLevelcount($from_date=null,$to_date=null,$ward_id=null)
        {
            $data['from_date'] = base64_decode($from_date);
            $data['to_date'] = base64_decode($to_date);
            $data['ward_id'] = base64_decode($ward_id);

            if($data['ward_id']!="all"){
                $where=" tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];  
            }
            else {   
                $where=" tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."'"; 
            }
            $data['pendingAtda']=$this->model_trade_level_pending_dtl->pendingAtda($where);
            $data['pendingAttaxdaroga']=$this->model_trade_level_pending_dtl->pendingAttaxdaroga($where);
            $data['pendingAtsec']=$this->model_trade_level_pending_dtl->pendingAtsec($where);
            $data['pendingAteo']=$this->model_trade_level_pending_dtl->pendingAteo($where);

            return view('report/trade_pendingAtLevel_report',$data);
        }
    //ward wise licence details 
    //reports 
    public function ward_wise_details($from_date=null,$to_date=null,$ward_id=null,$application_type_id=null)
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['from_date'] = base64_decode($from_date);
        $data['to_date'] = base64_decode($to_date);
        $data['ward_id'] = base64_decode($ward_id);
        $data['application_type_id'] = base64_decode($application_type_id);
        if($data['application_type_id']=="1")
        {
             $data['application_type'] = "New License Request";
             $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and tbl_transaction.transaction_type ='NEW LICENSE'";

        }
        elseif($data['application_type_id']=="2")
        {
            $data['application_type'] = "Renewal License Request";
            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.transaction_type ='RENEWAL'";  
        }
        elseif($data['application_type_id']=="3")
        {
            $data['application_type'] = "Amendment License Request";
            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and tbl_transaction.transaction_type ='AMENDMENT'";    
        }
        elseif($data['application_type_id']=="4")
        {
            $data['application_type'] = "Surrender License Request";
            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and tbl_transaction.transaction_type ='SURRENDER'";
        }
        elseif($data['application_type_id']=="all")
        {
            $data['application_type'] = "All License Request";
            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and payment_status in(1,0) and application_type_id in(1,2,3,4)";
            
        }
        elseif($data['application_type_id']=="rej")
        {
            $data['application_type'] = "Rejected License";
            $where="level_pending::date between '".$data['from_date']."' and '".$data['to_date']."'";
            
        }
        elseif($data['application_type_id']=="levl")
        {
            $data['application_type'] = "Pending At Level";
            $where="level_pending.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";  
        }
        elseif($data['application_type_id']=="bo")
        {
            $data['application_type'] = "Back To Citizen";
            $where="level_pending.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";  
            
        }
        elseif($data['application_type_id']=="jsk")
        {
            $data['application_type'] = "Pending At JSK";
            $where="apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=0 ";
            
        }
        elseif($data['application_type_id']=="bco")
        {
            $data['application_type'] = "Pending At Back Office";
            $where="apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' AND payment_status=1 AND pending_status = 0  AND document_upload_status = 0 ";
            
        }
        elseif($data['application_type_id']=="5")
        {
            $data['application_type'] = "Final License";
            $where="level_pending.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";     
        }
        elseif($data['application_type_id']=="prov")
        {
            $data['application_type'] = "Provisional License";
            $where="apply_date::date between '".$data['from_date']."' and '".$data['to_date']."'";  
        }
        elseif($data['application_type_id']=="da")
        {
            $data['application_type'] = "Pending at Dealing Assistant";
            $where="tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_level_pending.status=1
            and tbl_level_pending.receiver_user_type_id = 17";  
        }
        elseif($data['application_type_id']=="td")
        {
            $data['application_type'] = "Pending at Tax Daroga";
            $where="tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_level_pending.status=1
            and  tbl_level_pending.receiver_user_type_id = 20";  
        }
        elseif($data['application_type_id']=="sh")
        { 
            $data['application_type'] = "Pending at Section Head";
            $where="tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_level_pending.status=1
            and tbl_level_pending.receiver_user_type_id = 18";  
        }
        elseif($data['application_type_id']=="eo")
        {
            $data['application_type'] = "Pending at Municipal Commissioner";
            $where="tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_level_pending.status=1
            and tbl_level_pending.receiver_user_type_id = 19";  
        }


        if($data['ward_id']=="all")
        {
            $data['licencedtls']=$this->TradeApplyLicenceModel->get_licence_by_ward($where,$data['application_type_id']);  
        }
        else
        {

            $data['licencedtls']=$this->TradeApplyLicenceModel->get_licence_by_ward($where,$data['application_type_id'],$data['ward_id']);       

            // $wardd = $this->ward_model->getdatabyid($data['ward_id']);
            // $data['licencedtls'][$key]['ward']=$wardd['ward_no'];   
        }
        //echo '<pre>',print_r( $data['licencedtls']),'</pre>';   exit;        
        return view('report/trade_ward_wise_license_report',$data);
    }



    //ward wise collection details 
    public function ward_wise_collection_details($from_date=null,$to_date=null,$ward_id=null,$application_type_id=null)
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['from_date'] = base64_decode($from_date);
        $data['to_date'] = base64_decode($to_date);
        $data['ward_id'] = base64_decode($ward_id);
        $data['application_type_id'] = base64_decode($application_type_id);
         if($data['application_type_id']=="1")
        {
             $data['application_type'] = "New License Amount Collection";
             $where="transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and transaction_type ='NEW LICENSE'";

        }
        elseif($data['application_type_id']=="2")
        {
            $data['application_type'] = "Renewal License Amount Collection";
            $where="transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and transaction_type ='RENEWAL'";  
        }
        elseif($data['application_type_id']=="3")
        {
            $data['application_type'] = "Amendment License Amount Collection";
            $where="transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and transaction_type ='AMENDMENT'";    
        }
        elseif($data['application_type_id']=="4")
        {
            $data['application_type'] = "Surrender License Amount Collection";
            $where="transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'  and transaction_type ='SURRENDER'";    
        }
 
        if($data['ward_id']=="all")
        {
           $data['licencedtls']=$this->TradeApplyLicenceModel->getCollection_by_ward($where);       
        }
        else
        {
          $data['licencedtls']=$this->TradeApplyLicenceModel->getCollection_by_ward($where,$data['ward_id']);
         }

       // echo '<pre>',print_r( $data['licencedtls']),'</pre>';   exit;        
        return view('report/trade_ward_wise_collections_report',$data);
    }
	
	public function new_LicenceList($fromDate=null,$todate=null,$ward_id=null)
    {

        $data =(array)null;
        
		$data['fromDate']=$fromDate;
		$data['todate']=$todate;
		$data['ward_id']=$ward_id;
        
		if($data['ward_id']!=""){
			$data['newapplyLicense']=$this->TradeApplyLicenceModel->newLicenseListward($data);
		}
		else {   
			$data['newapplyLicense']=$this->TradeApplyLicenceModel->newLicenseList($data);
		}
		//print_r($data);
		return view('report/new_licence_list',$data);
	}

    // Collection Summary Report of Municipal License
   public function collection_report_municpl_licnse()
   {
    return view('trade/connection/collection_report_municpl_licnse');
   }
   public function collection_report_municpl_licnse_ajax()
   {
    $data =(array)null;

    if($this->request->getMethod()=='post'){
        try{
        // data filter
        $from_date = sanitizeString($this->request->getVar('from_date'));
        $to_date = sanitizeString($this->request->getVar('to_date'));
        $data['fromDate']=$from_date;
        $data['todate']=$to_date;
        
       $data['jsk_cash_collection']=$this->model_trade_transaction->amount_collection($data,'CASH','JSK');
       $data['jsk_cheque_collection']=$this->model_trade_transaction->amount_collection($data,'CHEQUE','JSK');
       $data['jsk_dd_collection']=$this->model_trade_transaction->amount_collection($data,'DD','JSK');
       $data['tc_cash_collection']=$this->model_trade_transaction->amount_collection($data,'CASH','TC');
       $data['tc_cheque_collection']=$this->model_trade_transaction->amount_collection($data,'CHEQUE','TC');
       $data['tc_dd_collection']=$this->model_trade_transaction->amount_collection($data,'DD','TC');
       $data['online_payment']=$this->model_trade_transaction->amount_collection($data,'ONLINE','online');
       $total_jsk_collection = $data['jsk_cash_collection']['sum']+$data['jsk_cheque_collection']['sum']+$data['jsk_dd_collection']['sum'];
       $total_tc_collection = $data['tc_cash_collection']['sum']+$data['tc_cheque_collection']['sum']+$data['tc_dd_collection']['sum'];
       $total_cash_payment = $data['jsk_cash_collection']['sum']+$data['tc_cash_collection']['sum'];
       $total_cheque_payment = $data['jsk_cheque_collection']['sum']+$data['tc_cheque_collection']['sum'];
       $total_dd_payment = $data['jsk_dd_collection']['sum']+$data['tc_dd_collection']['sum'];
       $total =  $total_cash_payment+$total_cheque_payment+$total_dd_payment;
       $output_payment = "";
       $output_payment_online ="";
       $output_payment .= '
       <tr> 
           <th id="leftTd">JSK Collection</th>
           <td style="text-align:center;">'.$data['jsk_cash_collection']['sum'].'</td>
           <td style="text-align:center;">'.$data['jsk_cheque_collection']['sum'].'</td>
           <td style="text-align:center;">'.$data['jsk_dd_collection']['sum'].'</td>
           <td style="text-align:center;">'.number_format((float)$total_jsk_collection, 2, '.', '').'</td>
        </tr>
        <tr>
           <th id="leftTd">Door to Door Collection</th>
           <td style="text-align:center;">'.$data['tc_cash_collection']['sum'].'</td>
           <td style="text-align:center;">'.$data['tc_cheque_collection']['sum'].'</td>
           <td style="text-align:center;">'.$data['tc_dd_collection']['sum'].'</td>
           <td style="text-align:center;">'.number_format((float)$total_tc_collection, 2, '.', '').'</td>
        </tr>
        <tr>
           <th id="leftTd">Total</th>
           <td style="text-align:center;">'.number_format((float)$total_cash_payment, 2, '.', '').'</td>
           <td style="text-align:center;">'.number_format((float)$total_cheque_payment, 2, '.', '') .'</td>
           <td style="text-align:center;">'.number_format((float)$total_dd_payment, 2, '.', '') .'</td>
           <td style="text-align:center;">'.number_format((float)$total, 2, '.', '').'</td>
        </tr>
        <tr>
           <th id="leftTd" colspan="5">&nbsp;</th>
        </tr>';   


        $output_payment_online = '  <tr>
        <td  style="text-align:center;">'. $data['online_payment']['sum'].'</td>
        <td  style="text-align:center;">'. $data['online_payment']['sum'].'</td>
     </tr>';

     $response = array(
        "output_payment" => $output_payment,
        "output_payment_grand_total" => number_format((float)$total, 2, '.', ''),
        "output_payment_online" => $output_payment_online,
        "from_date_to_date" =>'From '. date("d-m-Y", strtotime($from_date)) .' To '. date("d-m-Y", strtotime($to_date)),
         );

         return json_encode($response);
        }catch(Exception $e){

        } 
    }
   }

   public function licence_request($from_date=null,$to_date=null,$ward_id=null,$application_type_id=null)
    {
        $data =(array)null;
        $data['from_date'] = base64_decode($from_date);
        $data['to_date'] = base64_decode($to_date);
        $data['ward_id'] = base64_decode($ward_id);
        $data['application_type_id'] = base64_decode($application_type_id);
        if($data['application_type_id']=="1")
        {
             $data['application_type'] = "New License Request";
        }
        elseif($data['application_type_id']=="2")
        {
            $data['application_type'] = "Renewal License Request";
        }
        elseif($data['application_type_id']=="3")
        {
            $data['application_type'] = "Amendment License Request";
        }
        elseif($data['application_type_id']=="4")
        {
            $data['application_type'] = "Surrender License Request";
        }
        elseif($data['application_type_id']=="all")
        {
            $data['application_type'] = "All License Request";
        }
        else{
            $data['application_type'] = "Pending At JSK";
        }
        
     	return view('report/new_licence_request',$data);		 
    }

    public function licence_status($from_date=null,$to_date=null,$ward_id=null,$status=null)
    {
        $data =(array)null;
        $data['from_date'] = base64_decode($from_date);
        $data['to_date'] = base64_decode($to_date);
        $data['ward_id'] = base64_decode($ward_id);
        $data['status'] = base64_decode($status);
         if($data['status']=="0")
        {
             $data['licence_status'] = "Pending At Level";
        }
        elseif($data['status']=="2")
        {
            $data['licence_status'] = "Back To Citizen";
        }
        elseif($data['status']=="5")
        {
            $data['licence_status'] = "Final License";
        }
        elseif($data['status']=="4")
        {
            $data['licence_status'] = "Rejected Form";
        }
        
    	return view('report/licence_status',$data);		 
    }

    

    //ward wise all licence details 
  public function AllLicenceReport()
  {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['ward_list1']=$this->ward_model->getWardList($data);
        $where='1=1';
     
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;

            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";


            if($data['ward_id']=="all")
            { 
                $data['licencedtls']=$this->TradeApplyLicenceModel->get_all_licence_by_ward($where);
                

            }
            else
            {
                
                $where.=" and tbl_transaction.ward_mstr_id = ".$data['ward_id'];
                $data['licencedtls']=$this->TradeApplyLicenceModel->get_all_licence_by_ward($where);
                //print_var($data['licencedtls']);  
                
            }
    
        }
        else
        {
            $data['to_date'] = date('Y-m-d');
            $data['from_date'] = date('Y-m-d');
            $data['ward_id_name'] = "All";
            $data['ward_id'] = "all";            
            
            $where="tbl_transaction.transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."'";

            if($data['ward_id']=="all")
            {
                $data['licencedtls']=$this->TradeApplyLicenceModel->get_all_licence_by_ward($where);
                $data['licence_details']=$data['licencedtls'];
                
                             
            }
            
            
        }   
        
        //print_var( $data['licence_details']);  exit; 
        return view('report/trade_ward_wise_all_licence_report',$data);
  }

     //ward wise all licence details 
     public function view_by_ward($ward_id,$from_date,$to_date)
     {
         $data =(array)null;
         $from_date = base64_decode($from_date);
         $to_date = base64_decode($to_date);
         $data['posts']=$this->TradeTransactionModel->get_Licence_by_single_ward_transaction($ward_id,$from_date,$to_date);          
       // echo '<pre>',print_r( $data['posts']),'</pre>';   exit; 
         return view('report/trade_wardWise_all_licence_report',$data);
     }

      //ward wise and application type  licence details 
      public function view_by_ward_and_application_type($ward_id,$application_type_id,$from_date,$to_date)
      {
          $data =(array)null;
          $ward_id = base64_decode($ward_id);
          $from_date = base64_decode($from_date);
          $to_date = base64_decode($to_date);
          $application_type_id = base64_decode($application_type_id);

          $where='';
          if($application_type_id == 1)
         {
            $application_type_id = 'NEW LICENSE';
         }
         elseif($application_type_id == 2)
         {
            $application_type_id = 'RENEWAL';
         }
         elseif($application_type_id == 3)
         {
            $application_type_id = 'AMENDMENT';
         }
         elseif($application_type_id == 4 )
         {
            $application_type_id = 'SURRENDER';
         }
         elseif($application_type_id == 'da' )
         { 
             $where = "tbl_level_pending.created_on::date between '$from_date' and  '$to_date'   
             and tbl_level_pending.status = 1 and tbl_level_pending.receiver_user_type_id = 17 and tbl_apply_licence.ward_mstr_id = $ward_id";
          }
         elseif($application_type_id == 'td' )
         {
            $where = "tbl_level_pending.created_on::date between '$from_date' and  '$to_date'   
            and tbl_level_pending.status = 1 and tbl_level_pending.receiver_user_type_id = 20 and tbl_apply_licence.ward_mstr_id =  $ward_id";
          }
         elseif($application_type_id == 'sh' )
         {
            $where = "tbl_level_pending.created_on::date between '$from_date' and  '$to_date'   
            and tbl_level_pending.status = 1 and tbl_level_pending.receiver_user_type_id = 18 and tbl_apply_licence.ward_mstr_id =  $ward_id";
          }
         elseif($application_type_id == 'eo' )
         {
            $where = "tbl_level_pending.created_on::date between '$from_date' and  '$to_date'   
            and tbl_level_pending.status = 1 and tbl_level_pending.receiver_user_type_id = 19 and tbl_apply_licence.ward_mstr_id =  $ward_id";
          }
         else
         { 
            $application_type_id;
         }
           $data['posts']=$this->TradeTransactionModel->get_Licence_by_ward_and_application_type($ward_id,$application_type_id,$from_date,$to_date,$where);    
          // print_var($data['posts']);die();
          return view('report/trade_wardWise_all_licence_report',$data);
      }

       //ward wise and application type  collection details 
       public function view_by_ward_and_application_typeCollection($ward_id,$application_type_id,$from_date,$to_date)
       {
           $data =(array)null;
           $ward_id = base64_decode($ward_id);
           $from_date = base64_decode($from_date);
           $to_date = base64_decode($to_date);
           $application_type_id = base64_decode($application_type_id);
           //print_r($application_type_id);exit;
          if($application_type_id == 1)
          {
             $application_type_id = 'NEW LICENSE';
          }
          elseif($application_type_id == 2)
          {
             $application_type_id = 'RENEWAL';
          }
          elseif($application_type_id == 3)
          {
             $application_type_id = 'AMENDMENT';
          }
          elseif($application_type_id == 4 )
          {
             $application_type_id = 'SURRENDER';
          }
          
           $data['posts']=$this->TradeTransactionModel->get_collection_by_ward_and_application_type($ward_id,$application_type_id,$from_date,$to_date);          
          // print_r( $data['posts']);exit;
           return view('report/trade_wardWise_collection_details',$data);
       }




}
?>

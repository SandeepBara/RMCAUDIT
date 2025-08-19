<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\Water_Transaction_Model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterConsumerModel;
use App\Models\water_consumer_details_model;
use App\Models\water_applicant_details_model;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterTransactionDeactivateModel;
use App\Models\water_consumer_demand_model;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\water_transaction_fine_rebet_details_model;
use App\Models\Water_Cheque_Details_Model;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterPenaltyModel;

class WaterTransactionDeactivate extends AlphaController
{
    protected $water;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $Water_Transaction_Model;
    protected $WaterApplyNewConnectionModel;
    protected $WaterConsumerModel;
    protected $water_consumer_details_model;
    protected $water_applicant_details_model;
    protected $WaterConnectionTypeModel;
    protected $WaterTransactionDeactivateModel;
    protected $water_consumer_demand_model;
    protected $WaterConsumerCollectionModel;
    protected $WaterConnectionChargeModel;
    protected $water_transaction_fine_rebet_details_model;
    protected $Water_Cheque_Details_Model;
    
    
    public function __construct(){

        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }

   

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->WaterConsumerModel = new WaterConsumerModel($this->water);
        $this->water_consumer_details_model = new water_consumer_details_model($this->water);
        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->WaterConnectionTypeModel = new WaterConnectionTypeModel($this->water);
        $this->WaterTransactionDeactivateModel = new WaterTransactionDeactivateModel($this->water);
        $this->water_consumer_demand_model = new water_consumer_demand_model($this->water);
        $this->WaterConsumerCollectionModel = new WaterConsumerCollectionModel($this->water);
        $this->WaterConnectionChargeModel = new WaterConnectionChargeModel($this->water);
        $this->water_transaction_fine_rebet_details_model = new water_transaction_fine_rebet_details_model($this->water);
        $this->Water_Cheque_Details_Model = new Water_Cheque_Details_Model($this->water);
        $this->WaterPenaltyInstallmentModel = new WaterPenaltyInstallmentModel($this->water);
        $this->WaterPenaltyModel = new WaterPenaltyModel($this->water);
    }

    function __destruct() {
		$this->water->close();
		$this->dbSystem->close();
	}


    public function detail($transaction_no=null)
    {
        $session=session();
        $emp_details=$session->get('emp_details');
        $emp_details_id=$emp_details['user_type_mstr_id'];
        if($emp_details_id!="2" && $emp_details_id!="1")
        {
            return redirect()->to('/home');
        }
        
        $data =(array)null;
        $waterTransactionList = [];
        if($this->request->getMethod()=='post')
        {
            //Water Transaction Details
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['transaction_no'] = strtoupper($inputs['transaction_no']);
            $data['cheque_no'] = strtoupper($inputs['cheque_no']);

            if ( $data['transaction_no']!='' && $tranDetails = $this->Water_Transaction_Model->getTransactionByTransactionNo($data)) 
            {
                if(!empty($tranDetails))
                {
                    if($tranDetails['verify_status']=="")
                    {
                        $temp1['transaction_id'] =md5($tranDetails['id']); 
                        $cke = $this->Water_Transaction_Model->getCheckDtlBytrid($temp1);
                        $waterTransactionList[0]['id'] = $tranDetails['id'];
                        $waterTransactionList[0]['transaction_date'] = $tranDetails['transaction_date'];
                        $waterTransactionList[0]['transaction_no'] = $tranDetails['transaction_no'];
                        $waterTransactionList[0]['cheque_no'] = $cke['cheque_no']??null;
                        $waterTransactionList[0]['cheque_date'] = $cke['cheque_date']??null;
                        $waterTransactionList[0]['bank_name'] = $cke['bank_name']??null;
                        $waterTransactionList[0]['branch_name'] = $cke['branch_name']??null;
                        $waterTransactionList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tranDetails['ward_mstr_id']);
                        $data['waterTransactionList'] = $waterTransactionList;
                    }
                    else
                    {
                        $data['validation'] = "Cash Verification Is Done, We Can Not Deactivate Transaction!!!";
                    }
                }
                else 
                {
                    $data['validation'] = "Record Does Not Exists";
                }
                
            } 
            elseif($data['cheque_no']!='' && $chequedtl = $this->Water_Transaction_Model->getCheckDtlByno($data))
            {
                
                foreach($chequedtl as $key => $val)
                { 
                    $temp['id']=md5($val['transaction_id']);
                    $trans = $this->Water_Transaction_Model->getTransactionByTransactionId($temp);                   
                    if(!empty($trans) && $trans['verify_status']=="")
                    {
                        $waterTransactionList[$key]['id'] = $trans['id'];
                        $waterTransactionList[$key]['transaction_date'] = $trans['transaction_date'];
                        $waterTransactionList[$key]['cheque_no'] = $val['cheque_no'];
                        $waterTransactionList[$key]['cheque_date'] = $val['cheque_date'];
                        $waterTransactionList[$key]['bank_name'] = $val['bank_name']??null;
                        $waterTransactionList[$key]['branch_name'] = $val['branch_name']??null;
                        $waterTransactionList[$key]['transaction_no'] = $trans['transaction_no'];
                        $waterTransactionList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($trans['ward_mstr_id']);
                        $data['waterTransactionList'] = $waterTransactionList;
                    }                    

                }
                if(sizeof($chequedtl)==1 && !empty($trans) && $trans['verify_status']!="")
                {
                    $data['validation'] = "Cash Verification Is Done, We Can Not Deactivate Transaction!!!";
                }
                if(sizeof($chequedtl)<1)
                {
                    $data['validation'] = "Record Does Not Exists";
                }

            }
            else 
            {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/transaction_deativate',$data);
        }
        else if(isset($transaction_no))
        {
            $data['transaction_no']=$transaction_no;
           if ($tranDetails = $this->Water_Transaction_Model->getTransactionByTransactionNoUsingMd($data)) {
                $waterTransactionList[0]['id'] = $tranDetails['id'];
                $waterTransactionList[0]['transaction_date'] = $tranDetails['transaction_date'];
                $waterTransactionList[0]['transaction_no'] = $tranDetails['transaction_no'];
                $data['transaction_no'] = $tranDetails['transaction_no'];
                $waterTransactionList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tranDetails['ward_mstr_id']);
                $data['waterTransactionList'] = $waterTransactionList;
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/transaction_deativate',$data);
        }
        else
        {
           $data['waterTransactionList'] = $waterTransactionList;
           return view('water/deactivate/transaction_deativate',$data);
        } 
    }
    
    public function create()
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        
        if($this->request->getMethod()=='post')
        {
            
            $input = [
                        'remark' => $this->request->getVar('remark'),
                        'deactive_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'transaction_id' => $this->request->getVar('transaction_id'),
                        'deactivated_by' =>$emp_details_id
                    ];

            $data['id'] = md5($input['transaction_id']);
            $tranDetails = $this->Water_Transaction_Model->getTransactionByTransactionId($data);
            $insert_id = $this->WaterTransactionDeactivateModel->insertData($input);
            
            if($insert_id)
            {
                $rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                if($this->validate($rules))
                {   
                    $file = $this->request->getFile('doc_path');
                    $extension = $file->getExtension();
                    
                    if($file->isValid() && !$file->hasMoved()){
                        $newName = $city['city'].'/water_transaction_deactivation'.'/'.md5($insert_id).".".$extension;
                        if($file->move(WRITEPATH.'uploads/', $newName))
                        {
                            $this->WaterTransactionDeactivateModel->uploadDocument($newName, $insert_id);
                        }
                    }
                }
                $where=['status'=>1,
                        'transaction_id'=>$input['transaction_id'],
                        'related_id'=>$tranDetails['related_id']
                    ];
                $update = ['status'=>0,
                            'remarks'=> $input['remark']
                        ];
                if($tranDetails['transaction_type']=="Demand Collection")
                {
                   $demandCollection = $this->WaterConsumerCollectionModel->getConsumerCollectionByTransactionId($input['transaction_id']);
                   foreach ($demandCollection as $value)
                   {
                        //Update Demand Table Data
                        $this->water_consumer_demand_model->updateStatus($value['demand_id']);
                   } 
                   //update Consumer Collection Table Data
                   $this->WaterConsumerCollectionModel->updateStatus($input['transaction_id']);
                   //Update transaction fine rebet details
                   $this->water_transaction_fine_rebet_details_model->updateStatus($input['transaction_id']);
                   
                   //update adjustment  and update advance
                   $where['module']='consumer';
                   $this->WaterPenaltyModel->update_tbl_adjustment_mstr($where,$update);
                   $this->WaterPenaltyModel->update_tbl_advance_mstr($where,$update);
                }
                else if($tranDetails['transaction_type']=="New Connection")
                {
                    
                    //tbl_connection_charge set paid_status=0
                    $this->WaterConnectionChargeModel->updatePaidStatus($input['transaction_id']);

                    # tbl_apply_water_connection set payment_status=0
                    $this->WaterApplyNewConnectionModel->updateApplyNewConnectionPaymentStatus($tranDetails['related_id']);

                    # tbl_penalty_installment set paid_status'=> 0, transaction_id'=> NULL, 'payment_from'=> NULL
                    $this->WaterPenaltyInstallmentModel->updateInstallmentDtlbyAppConnIdAdTrId($tranDetails['related_id'],$tranDetails['id']);

                    # tbl_penalty_dtl set status=1, Activate Penalty again 
                    $this->WaterPenaltyModel->updatePaidPenalty($tranDetails['related_id']);

                    //update adjustment  and update advance
                    $where['module']='connection';
                    $this->WaterPenaltyModel->update_tbl_adjustment_mstr($where,$update);
                    $this->WaterPenaltyModel->update_tbl_advance_mstr($where,$update);
                }
                else if($tranDetails['transaction_type']=="Penlaty Instalment")
                {
                     //tbl_connection_charge set paid_status=0
                    $this->WaterConnectionChargeModel->updatePaidStatus($input['transaction_id']);
                     # tbl_penalty_installment set paid_status'=> 0, transaction_id'=> NULL, 'payment_from'=> NULL
                    $this->WaterPenaltyInstallmentModel->updateInstallmentDtlbyAppConnIdAdTrId($tranDetails['related_id'],$tranDetails['id']);
                    //update adjustment  and update advance
                    $where['module']='connection';
                    $this->WaterPenaltyModel->update_tbl_adjustment_mstr($where,$update);
                    $this->WaterPenaltyModel->update_tbl_advance_mstr($where,$update);
                }
                else if($tranDetails['transaction_type']=="Site Inspection")
                {
                    //tbl_connection_charge set paid_status=0
                    $this->WaterConnectionChargeModel->updatePaidStatus($input['transaction_id']);
                    //update adjustment  and update advance
                    $where['module']='connection';
                    $this->WaterPenaltyModel->update_tbl_adjustment_mstr($where,$update);
                    $this->WaterPenaltyModel->update_tbl_advance_mstr($where,$update);
                }


                
                # tbl_transaction set status=0
                $this->Water_Transaction_Model->updateWaterTransactionStatus($input['transaction_id']);

                //cheque transaction id in cheque Details
                if($id = $this->Water_Cheque_Details_Model->checkTransactionIdExists($input['transaction_id']))
                {
                    $this->Water_Cheque_Details_Model->deactivateChequeDetails($id);
                }

                
                flashToast('deactivate', 'Transaction Deactivated Successfully!!');
                return $this->response->redirect(base_url('WaterTransactionDeactivate/detail'));
            }
            else
            {
                flashToast('deactivate', 'Something Is Wrong!!');
                return $this->response->redirect(base_url('WaterTransactionDeactivate/detail'));
            }
        }
    }

    public function view($id=null)
    {
        $data =(array)null;
        $data['id'] = $id;
        $data['statusData']=[1, 2];
        // print_r($data);
        $data['basic_details'] = $this->Water_Transaction_Model->getTransactionByTransactionId($data);
        if($data['basic_details']['transaction_type'] == "Demand Collection")
        {
            $consumer = $this->WaterConsumerModel->getConsumer($data['basic_details']['related_id']);
            //cosumer Number
            $data['consumer_no'] = $consumer['consumer_no'];
            //get Consumer Owner Details
            $data['ownerDetails'] = $this->water_consumer_details_model->consumerDetails($consumer['id']);
            //print_r($data);
            $data['connectionType'] = $this->WaterConnectionTypeModel->getconnectionType($consumer['connection_type_id']);
        }
        else
        {
            $applicant = $this->WaterApplyNewConnectionModel->getApplyConnectionDetailForDeactivation($data['basic_details']['related_id'],$data['statusData']);
            $data['application_no'] = $applicant['application_no'];
            $data['ownerDetails'] = $this->WaterApplyNewConnectionModel->water_owner_details(md5($applicant['id']));
            $data['connectionType'] = $this->WaterConnectionTypeModel->getconnectionType($applicant['connection_type_id']);
        }
        $data['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['basic_details']['ward_mstr_id']);
        //print_r($data);
        return view('water/deactivate/transaction_deativate_view',$data);
    }
}
?>

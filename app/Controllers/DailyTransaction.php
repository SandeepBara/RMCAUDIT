<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_view_water_transaction;
use App\Models\model_view_trade_transaction;
use App\Models\model_emp_details;
use App\Models\model_prop_owner_detail;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\TradeApplyLicenceModel;



class DailyTransaction extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_emp_details;
    protected $model_prop_owner_detail;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->model_view_trade_transaction=new model_view_trade_transaction($this->trade);
        $this->model_view_water_transaction=new model_view_water_transaction($this->water);
        
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->water_conn_model=new WaterApplyNewConnectionModel($this->water);
        $this->trade_model=new TradeApplyLicenceModel($this->water);
        
    }
    public function report()
    {
        $data =(array)null;
        $data['userList'] = $this->model_emp_details->getTCList();
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $data['tran_by_emp_details_id'] = $this->request->getVar('tran_by_emp_details_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            if($data['tran_by_emp_details_id']!=""){
                $data['emp_details_List'] = $this->model_transaction->getEmpDetailsIdForOneEmployee($data);
                foreach ($data['emp_details_List'] as $key => $value){
                    //Get Emp Details By Id
                    $emp_id = $value['tran_by_emp_details_id'];
                   // print_r($emp_id);
                    $data['emp_details_List'][$key]['emp_details'] = $this->model_emp_details->employeeDetails($emp_id);
                    $data['emp_details_List'][$key]['total'] = $this->model_transaction->calculateSumByTransactionEmpDetailsId($emp_id,$from_date,$to_date);
                    //All Transaction List by Emp
                    $data['emp_details_List'][$key]['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);
                    //print_r($data['transactionList']);
                    foreach ($data['emp_details_List'][$key]['transactionList'] as $keyy => $val){
                        $tran_type = $val['tran_type'];
                        $prop_dtl_id = $val['prop_dtl_id'];
                        if($tran_type=="Property")
                        {
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                            //print_r($data['emp_details_List'][$key]['transactionList'][$keyy]['holding']);
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                        }
                        else
                        {
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                        }
                    }

                    $data['emp_details_List'][$key]['watertransactionList'] = $this->model_view_water_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);

                    print_r($data['emp_details_List'][$key]['watertransactionList']);

                     foreach ($data['emp_details_List'][$key]['watertransactionList'] as $keyy => $val){
                        $tran_type = $val['transaction_type'];
                        $app_id = $val['related_id'];
                        if($tran_type=="New Connection" or $tran_type=="Site Inspection"  )
                        {
                            $data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding'] = $this->water_conn_model->getData(md5($prop_dtl_id));
                            //print_r($data['emp_details_List'][$key]['transactionList'][$keyy]['holding']);
                           
                        }
                        else
                        {
                            $data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);
                            
                        }
                    }





                }
            }else{
                $data['emp_details_List'] = $this->model_transaction->getEmpDetailsId($data);
                foreach ($data['emp_details_List'] as $key => $value){
                    //Get Emp Details By Id
                    $emp_id = $value['tran_by_emp_details_id'];
                   // print_r($emp_id);
                    $data['emp_details_List'][$key]['emp_details'] = $this->model_emp_details->employeeDetails($emp_id);
                    $data['emp_details_List'][$key]['total'] = $this->model_transaction->calculateSumByTransactionEmpDetailsId($emp_id,$from_date,$to_date);
                    //All Transaction List by Emp
                    $data['emp_details_List'][$key]['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);
                    //print_r($data['transactionList']);
                    foreach ($data['emp_details_List'][$key]['transactionList'] as $keyy => $val){
                        $tran_type = $val['tran_type'];
                        $prop_dtl_id = $val['prop_dtl_id'];
                        if($tran_type=="Property")
                        {
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                            //print_r($data['emp_details_List'][$key]['transactionList'][$keyy]['holding']);
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                        }
                        else
                        {
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);
                            $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                        }
                    }


                      $data['emp_details_List'][$key]['watertransactionList'] = $this->model_view_water_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);

                    //print_r($data['emp_details_List'][$key]['watertransactionList']);
                    
                     foreach ($data['emp_details_List'][$key]['watertransactionList'] as $keyy => $val){
                        $tran_type = $val['transaction_type'];
                        $app_id = $val['related_id'];
                        if($tran_type=="New Connection" or $tran_type=="Site Inspection"  )
                        {
                            $data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding'] = $this->water_conn_model->getApplicationNo($app_id);
                          //  print_r($data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding']);
                           
                        }
                        else
                        {
                            $data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding'] = $this->water_conn_model->getConsumerNo($app_id);
                            
                            // print_r($data['emp_details_List'][$key]['watertransactionList'][$keyy]['holding']);
                        }
                    }
                    
                    
                    $data['emp_details_List'][$key]['tradetransactionList'] = $this->model_view_water_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);

                       //print_r($data['emp_details_List'][$key]['watertransactionList']);
                    
                     foreach ($data['emp_details_List'][$key]['tradetransactionList'] as $keyy => $val){
                        $tran_type = $val['transaction_type'];
                        $app_id = $val['related_id'];
                        $data['emp_details_List'][$key]['tradetransactionList'][$keyy]['holding'] = $this->trade_model->getApplicationNo($app_id);
                        
                        //print_r($data['emp_details_List'][$key]['tradetransactionList'][$keyy]['holding']);
                           
                        
                        
                    }


                }
            }
            return view('report/daily_transaction_report',$data);
        } 
        else
        {
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['emp_details_List'] = $this->model_transaction->getEmpDetailsId($data);
            foreach ($data['emp_details_List'] as $key => $value){
                //Get Emp Details By Id
                $emp_id = $value['tran_by_emp_details_id'];
               // print_r($emp_id);
                $data['emp_details_List'][$key]['emp_details'] = $this->model_emp_details->employeeDetails($emp_id);
                $data['emp_details_List'][$key]['total'] = $this->model_transaction->calculateSumByTransactionEmpDetailsId($emp_id,$from_date,$to_date);
                //All Transaction List by Emp
                $data['emp_details_List'][$key]['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($emp_id,$from_date,$to_date);


                //print_r($data['transactionList']);
                foreach ($data['emp_details_List'][$key]['transactionList'] as $keyy => $val){
                    $tran_type = $val['tran_type'];
                    $prop_dtl_id = $val['prop_dtl_id'];
                    if($tran_type=="Property")
                    {
                        $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                        //print_r($data['emp_details_List'][$key]['transactionList'][$keyy]['holding']);
                      //  $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                    }
                    else
                    {
                        $data['emp_details_List'][$key]['transactionList'][$keyy]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);
                       // $data['emp_details_List'][$key]['transactionList'][$keyy]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                    }
                }



            }
            return view('report/daily_transaction_report',$data);
        } 
    }
}
?>

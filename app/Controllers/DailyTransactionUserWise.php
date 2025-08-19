<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_property_transaction;
use App\Models\model_view_water_transaction;
use App\Models\model_view_trade_transaction;
use App\Models\model_emp_details;
use App\Models\model_user_hierarchy;




class DailyTransactionUserWise extends AlphaController
{
    protected $db;
    protected $trade;
    protected $water;
    protected $dbSystem;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_emp_details;
    protected $model_prop_owner_detail;
    public function __construct()
    {
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
       
        $this->model_view_trade_transaction=new model_view_trade_transaction($this->trade);
        $this->model_view_water_transaction=new model_view_water_transaction($this->water);
        $this->model_view_property_transaction=new model_view_property_transaction($this->db);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_user_hierarchy=new model_user_hierarchy($this->dbSystem);
    }

    public function report()
    {
        $data =(array)null;
        $data['userList'] = $this->model_emp_details->getTCList();
        if($this->request->getMethod()=='post')
        {
            $arr=array();
            $data['from_date']=$this->request->getVar('from_date');
            $data['to_date']=$this->request->getVar('to_date');
            $data['tran_by_emp_details_id']=$this->request->getVar('tran_by_emp_details_id');

            if($data['tran_by_emp_details_id']!=0)  {
                echo "IF";
                /* $data['users_list']=$this->model_user_hierarchy->getUserDtls($data['tran_by_emp_details_id']);
                
                foreach($data['users_list'] as $val) {

                    $emp_id=$val['id'];
                    $arr[$val['id']]['emp_details']=$this->model_emp_details->getEmpDetailsById(md5($emp_id));
                    $arr[$val['id']]['watertrans']=$this->model_view_water_transaction->waterTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['waterTotalAmount']=$this->model_view_water_transaction->waterTotalAmountbyEmpId($data);
                    
                    $arr[$val['id']]['tradetrans']=$this->model_view_trade_transaction->tradeTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['tradeTotalAmount']=$this->model_view_trade_transaction->tradeTotalAmountbyEmpId($data);

                    $arr[$val['id']]['propertytrans']=$this->model_view_property_transaction->propertyTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['propertyTotalAmount']=$this->model_view_property_transaction->propertyTotalAmountbyEmpId($data);
                    break;
                } */
            }  else  {
                $data['users_list']=$data['userList'];
                foreach($data['users_list'] as $val)
                {
                    $emp_id=$val['id'];
                    $data['tran_by_emp_details_id']=$val['id'];

                    $arr[$val['id']]['emp_details'] = $this->model_emp_details->getEmpDetailsById(md5($emp_id));

                    $arr[$val['id']]['watertrans'] = $this->model_view_water_transaction->waterTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['waterTotalAmount'] = $this->model_view_water_transaction->waterTotalAmountbyEmpId($data);
                    
                    $arr[$val['id']]['tradetrans'] = $this->model_view_trade_transaction->tradeTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['tradeTotalAmount'] = $this->model_view_trade_transaction->tradeTotalAmountbyEmpId($data);
                    
                    $arr[$val['id']]['propertytrans'] = $this->model_view_property_transaction->propertyTransactionDetailsbyEmpId($data);
                    $arr[$val['id']]['propertyTotalAmount'] = $this->model_view_property_transaction->propertyTotalAmountbyEmpId($data);  
                    
                } 
                
                $data['tran_by_emp_details_id']="";
            }
            $data['a']=$arr;
            return view('report/daily_transaction_user_wise_report',$data);
        }
        else
        {
            $arr=array();
            $data['from_date'] = $data['to_date'] = date('Y-m-d');
            return view('report/daily_transaction_user_wise_report',$data);
        } 
    }
}
?>

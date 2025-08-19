<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\CashVerificationTradeModel;
use App\Models\CashVerificationPropertyModel;
use App\Models\CashVerificationWaterModel;
use App\Models\model_user_hierarchy;
use App\Models\model_cash_verification_mstr;
use App\Models\model_view_water_transaction;
use App\Models\model_view_trade_transaction;
use App\Models\model_view_property_transaction;



class CashVerificationDetailReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $ward_model;
    protected $apply_license;
    
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
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->apply_license=new TradeApplyLicenceModel($this->trade);
        $this->model_user_hierarchy=new model_user_hierarchy($this->dbSystem);
        $this->cash_verf_trade_model=new CashVerificationTradeModel($this->trade);
        $this->cash_verf_water_model=new CashVerificationWaterModel($this->water);
        $this->cash_verf_property_model=new CashVerificationPropertyModel($this->db);
        $this->model_cash_verification_mstr=new model_cash_verification_mstr($this->dbSystem);
        $this->model_view_water_transaction=new model_view_water_transaction($this->water);
        $this->model_view_trade_transaction=new model_view_trade_transaction($this->trade);
        $this->model_view_property_transaction=new model_view_property_transaction($this->db);
    }
   /* public function report()
    {

        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $data['from_date'] = $from_date;
            $data['userlist']=$this->model_user_hierarchy->getUserListExceptAdmins();
            $arr['a']=array();
            foreach($data['userlist'] as $val)
            {
                $arr['a'][$val['id']]['verified_amt_property']=$this->cash_verf_property_model->getVerifiedAmountProperty($val['id']);
                $arr['a'][$val['id']]['collected_amt_property']=$this->cash_verf_property_model->getCollectedAmountProperty($val['id']);
                $arr['a'][$val['id']]['getAllVerifiedProperty']=$this->cash_verf_property_model->getAllVerifiedProperty($val['id']);


                $arr['a'][$val['id']]['verified_amt_water']=$this->cash_verf_water_model->getVerifiedAmountWater($val['id']);
                $arr['a'][$val['id']]['getAllVerifiedWater']=$this->cash_verf_water_model->getAllVerifiedWater($val['id']);
                $arr['a'][$val['id']]['collected_amt_water']=$this->cash_verf_water_model->getCollectedAmountWater($val['id']);
                

                $arr['a'][$val['id']]['verified_amt_trade']=$this->cash_verf_trade_model->getVerifiedAmountTrade($val['id']);


                $arr['a'][$val['id']]['getAllVerifiedTrade']=$this->cash_verf_trade_model->getAllVerifiedTrade($val['id']);
                $arr['a'][$val['id']]['collected_amt_trade']=$this->cash_verf_trade_model->getCollectedAmountTrade($val['id']);
                
                
            }
            $data['a']=$arr['a'];

            //  print_r($arr['a']);

            
        }
        else
        {
            $data['to_date'] = date('Y-m-d');
            $data['from_date'] = date('Y-m-d');
        }
        //print_r($data['a']);


        return view('report/cash_verification_report',$data);

    }*/

    
    public function details($id=null)
    {

        $data =(array)null;
        
        $get_cash_verf_mstr_dtl=$this->model_cash_verification_mstr->getData($id);
        $cash_verify_id=$get_cash_verf_mstr_dtl['id'];
        $data['verified_amt']=$get_cash_verf_mstr_dtl['verify_amount'];
        
        
        $data['trans_prop']=$this->model_view_property_transaction->propertyTransactionDetailsbyEmpIdTransDate($cash_verify_id);
        $data['trans_water']=$this->model_view_water_transaction->waterTransactionDetailsbyEmpIdTransDate($cash_verify_id);
        $data['trans_trade']=$this->model_view_trade_transaction->tradeTransactionDetailsbyEmpIdTransDate($cash_verify_id);
        
        //$data['trans_prop_not_verified']=$this->model_view_property_transaction->propertyNotVerified($cash_verify_id);
       // $data['trans_water_not_verified']=$this->model_view_water_transaction->waterNotVerified($cash_verify_id);
        //$data['trans_trade_not_verified']=$this->model_view_trade_transaction->tradeNotVerified($cash_verify_id);
        
        
        //print_r($data['trans_prop']);
        //print_r($data['trans_water']);
        //print_r($data['trans_trade']);
        

        return view('report/cash_verification_detail_report',$data);

    }



}

?>

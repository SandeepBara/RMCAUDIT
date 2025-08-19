<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_prop_owner_detail;
use App\Models\model_trade_transaction;


class TradeCollectionReports extends AlphaController
{
    protected $db;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
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
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_trade_transaction=new model_trade_transaction($this->trade);

    }
    public function report()
    {
        $data =(array)null;
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $payment_mode = $this->request->getVar('payment_mode');
            
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['payment_mode'] = $payment_mode;
            
            if($data['payment_mode']!="")
            {
                $data['transaction']=$this->model_trade_transaction->getAllTransactionsPayModeWise($data);
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmountPayModeWise($data);
            
            }
            else
            {
                $data['transaction']=$this->model_trade_transaction->getAllTransaction($data);
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmount($data);
            
            }
        }
        return view('report/trade_transaction_report',$data);
}
}

?>

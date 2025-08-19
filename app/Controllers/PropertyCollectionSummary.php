<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;
use App\Models\model_collection;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\model_payment_adjust;

class PropertyCollectionSummary extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_transaction;
    protected $model_emp_details;
    protected $model_ward_mstr;
    protected $model_collection;
    protected $model_trade_transaction_fine_rebet_details;
    protected $model_payment_adjust;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->model_collection = new model_collection($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->model_payment_adjust = new model_payment_adjust($this->db);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        
    }
    public function report(){
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ward_list'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);

        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['from_date'] = $inputs['from_date'];
            $data['to_date'] = $inputs['to_date'];
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
            if($inputs['ward_mstr_id']!=""){ //Get Particular Record
                $data['cash'] = $this->model_transaction->getTotalCashCollectionBetweenDateCash($inputs);
                $data['cheque'] = $this->model_transaction->getTotalCashCollectionBetweenDateCheque($inputs);
                $data['dd'] = $this->model_transaction->getTotalCashCollectionBetweenDateDD($inputs);
                $data['card'] = $this->model_transaction->getTotalCashCollectionBetweenDateCard($inputs);
                $data['online'] = $this->model_transaction->getTotalCashCollectionBetweenDateOnline($inputs);
                $data['fund'] = $this->model_transaction->getTotalCashCollectionBetweenDateFund($inputs);
                $data['i_sure'] = $this->model_transaction->getTotalCashCollectionBetweenDatei_sure($inputs);
                //total Collection
                $data['total_collection'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['card']['card']+$data['online']['online']+$data['fund']['fund']+$data['i_sure']['i_sure'];
                //total holding
                $data['total_holding'] = $data['cash']['holding']+$data['cheque']['holding']+$data['dd']['holding']+$data['card']['holding']+$data['online']['holding']+$data['fund']['holding']+$data['i_sure']['holding'];
                //Cancel Collection 
                $data['cancel_cash'] = $this->model_collection->getTotalCancelCash($inputs);
                $data['cancel_cheque'] = $this->model_transaction->getTotalCancelCheque($inputs);
                $data['cancel_dd'] = $this->model_transaction->getTotalCancelDd($inputs);
                $data['cancel_card'] = $this->model_transaction->getTotalCancelCard($inputs);
                $data['cancel_online'] = $this->model_transaction->getTotalCancelOnline($inputs);
                $data['cancel_fund'] = $this->model_transaction->getTotalCancelFund($inputs);
                $data['cancel_i_sure'] = $this->model_transaction->getTotalCancelI_Sure($inputs);
                $data['netPayment'] = $this->model_transaction->getNetPayment($inputs);
                $data['rtgsPayment'] = $this->model_transaction->getRTGSPayment($inputs);
                //Holding Tax
                $data['tax'] = $this->model_collection->getHoldingTax($inputs);
                
                //Transaction Details
                $rebateAmount=0;
                if($transactionData = $this->model_transaction->getTransaction($inputs)){
                    foreach ($transactionData as $value) { //Rebate Calculation
                        $rebateData = $this->model_trade_transaction_fine_rebet_details->getRebateByTransactionId($value['id']);
                        $rebateAmount = $rebateAmount+$rebateData;  
                    }
                }
                $data['rebate']['rebate'] = $rebateAmount;
                //advanced
                $advancedAmount=0;
                if($transactionProp = $this->model_transaction->getPropId($inputs)){
                    foreach ($transactionProp as $value) {
                        $advancedData = $this->model_payment_adjust->getAdvancedByPropId($value['prop_dtl_id']);
                        $advancedAmount = $advancedAmount+$advancedData;
                    }
                    
                }
                $data['advanced']['advance'] = $advancedAmount;
                //total tax
                $data['total'] = $data['tax']['holding_tax']+$data['tax']['water_tax']+$data['tax']['health_tax']+$data['tax']['educantion_cess']+$data['tax']['health_cess']; 
                //total Collection Cancel
                $data['total_cancel'] = $data['cancel_cash']['amount']+$data['cancel_cheque']['cheque']+$data['cancel_dd']['dd']+$data['cancel_card']['card']+$data['cancel_online']['online']+$data['cancel_fund']['fund']+$data['cancel_i_sure']['i_sure'];
                //total Cancel Holding
                $data['cancel_holding'] = $data['cancel_cash']['holding']+$data['cancel_cheque']['holding']+$data['cancel_dd']['holding']+$data['cancel_card']['holding']+$data['cancel_online']['holding']+$data['cancel_fund']['holding']+$data['cancel_i_sure']['holding'];
                //Net Holding
                $data['net_holding'] = $data['cancel_holding']+$data['total_holding'];
                //Net Collection
                $data['net_collection'] = $data['total_cancel']+$data['total_collection'];
                //Grand Total 
                $data['grand'] = $data['net_collection']+$data['advanced']['advance']+$data['rebate']['rebate'];
                //total No of Holding
                $data['total_No_holding'] = $this->model_transaction->getTotalHolding($inputs);
            }else{ //Get All Records
                $data['cash'] = $this->model_transaction->getTotalCashCollectionBetweenDateCash($inputs);
                $data['cheque'] = $this->model_transaction->getTotalCashCollectionBetweenDateCheque($inputs);
                $data['dd'] = $this->model_transaction->getTotalCashCollectionBetweenDateDD($inputs);
                $data['card'] = $this->model_transaction->getTotalCashCollectionBetweenDateCard($inputs);
                $data['online'] = $this->model_transaction->getTotalCashCollectionBetweenDateOnline($inputs);
                $data['fund'] = $this->model_transaction->getTotalCashCollectionBetweenDateFund($inputs);
                $data['i_sure'] = $this->model_transaction->getTotalCashCollectionBetweenDatei_sure($inputs);
                //total Collection
                $data['total_collection'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['card']['card']+$data['online']['online']+$data['fund']['fund']+$data['i_sure']['i_sure'];
                //total holding
                $data['total_holding'] = $data['cash']['holding']+$data['cheque']['holding']+$data['dd']['holding']+$data['card']['holding']+$data['online']['holding']+$data['fund']['holding']+$data['i_sure']['holding'];
                //Cancel Collection 
                $data['cancel_cash'] = $this->model_collection->getTotalCancelCash($inputs);
                $data['cancel_cheque'] = $this->model_transaction->getTotalCancelCheque($inputs);
                $data['cancel_dd'] = $this->model_transaction->getTotalCancelDd($inputs);
                $data['cancel_card'] = $this->model_transaction->getTotalCancelCard($inputs);
                $data['cancel_online'] = $this->model_transaction->getTotalCancelOnline($inputs);
                $data['cancel_fund'] = $this->model_transaction->getTotalCancelFund($inputs);
                $data['cancel_i_sure'] = $this->model_transaction->getTotalCancelI_Sure($inputs);
                $data['netPayment'] = $this->model_transaction->getNetPayment($inputs);
                $data['rtgsPayment'] = $this->model_transaction->getRTGSPayment($inputs);
                //Holding Tax
                $data['tax'] = $this->model_collection->getHoldingTax($inputs);
                //Rebate
                $data['rebate'] = $this->model_trade_transaction_fine_rebet_details->getRebate($inputs);
                //advanced
                $data['advanced'] = $this->model_payment_adjust->getAdvanced($inputs);
                //total tax
                $data['total'] = $data['tax']['holding_tax']+$data['tax']['water_tax']+$data['tax']['health_tax']+$data['tax']['educantion_cess']+$data['tax']['health_cess']; 
                //total Collection Cancel
                $data['total_cancel'] = $data['cancel_cash']['amount']+$data['cancel_cheque']['cheque']+$data['cancel_dd']['dd']+$data['cancel_card']['card']+$data['cancel_online']['online']+$data['cancel_fund']['fund']+$data['cancel_i_sure']['i_sure'];
                //total Cancel Holding
                $data['cancel_holding'] = $data['cancel_cash']['holding']+$data['cancel_cheque']['holding']+$data['cancel_dd']['holding']+$data['cancel_card']['holding']+$data['cancel_online']['holding']+$data['cancel_fund']['holding']+$data['cancel_i_sure']['holding'];
                //Net Holding
                $data['net_holding'] = $data['cancel_holding']+$data['total_holding'];
                //Net Collection
                $data['net_collection'] = $data['total_cancel']+$data['total_collection'];
                //Grand Total 
                $data['grand'] = $data['net_collection']+$data['advanced']['advance']+$data['rebate']['rebate'];
                //total No of Holding
                $data['total_No_holding'] = $this->model_transaction->getTotalHolding($inputs);
            }
            return view('report/collection_summary',$data);
        }else{
            return view('report/collection_summary',$data);
        }
    } 
}
?>

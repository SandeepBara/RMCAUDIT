<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Water_Transaction_Model;
use App\Models\model_ward_mstr;
class WaterCollectionSummary extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $Water_Transaction_Model;
    protected $model_ward_mstr;
    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ward_list'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);

        if($this->request->getMethod()=='post'){
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['from_date'] = $inputs['from_date'];
            $data['to_date'] = $inputs['to_date'];
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
            if($inputs['ward_mstr_id']!=""){ //Get Particular Record
                $data['cash'] = $this->Water_Transaction_Model->getTotalCashCollection($inputs);
                $data['cheque'] = $this->Water_Transaction_Model->getTotalChequeCollection($inputs);
                $data['dd'] = $this->Water_Transaction_Model->getTotalDDCollection($inputs);
                $data['online'] = $this->Water_Transaction_Model->getTotalOnlineCollection($inputs);
                $data['card'] = $this->Water_Transaction_Model->getTotalCardCollection($inputs);
                //Calculate toal
                $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
                $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
                $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
                //cancel Amount Calculation
                $data['cheque_cancel'] = $this->Water_Transaction_Model->getTotalChequeCollectionCancel($inputs);
                $data['dd_cancel'] = $this->Water_Transaction_Model->getTotalDDCollectionCancel($inputs);
                $data['online_cancel'] = $this->Water_Transaction_Model->getTotalOnlineCollectionCancel($inputs);
                $data['card_cancel'] = $this->Water_Transaction_Model->getTotalCardCollectionCancel($inputs);
               //Total Cancellation
                $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
                $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
                $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
                //Calculate Net Collection
                $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
                $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
                $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];

                //Calculate New Licence
                $data['new_connection'] = $this->Water_Transaction_Model->getTotalNewCollection($inputs);
                $data['demand_collection'] = $this->Water_Transaction_Model->getTotalDemandCollection($inputs);
                //calculate Total 
                $data['total_new_collection'] = $data['new_connection']['consumer']+$data['demand_collection']['consumer'];
                $data['total_new_transaction'] = $data['new_connection']['id']+$data['demand_collection']['id'];
                $data['total_new_amount'] = $data['new_connection']['new']+$data['demand_collection']['renewal'];
            }else{ //Get All Records
                $data['cash'] = $this->Water_Transaction_Model->getTotalCashCollection($inputs);
                $data['cheque'] = $this->Water_Transaction_Model->getTotalChequeCollection($inputs);
                $data['dd'] = $this->Water_Transaction_Model->getTotalDDCollection($inputs);
                $data['online'] = $this->Water_Transaction_Model->getTotalOnlineCollection($inputs);
                $data['card'] = $this->Water_Transaction_Model->getTotalCardCollection($inputs);
                //Calculate toal
                $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
                $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
                $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
                //cancel Amount Caculation
                $data['cheque_cancel'] = $this->Water_Transaction_Model->getTotalChequeCollectionCancel($inputs);
                $data['dd_cancel'] = $this->Water_Transaction_Model->getTotalDDCollectionCancel($inputs);
                $data['online_cancel'] = $this->Water_Transaction_Model->getTotalOnlineCollectionCancel($inputs);
                $data['card_cancel'] = $this->Water_Transaction_Model->getTotalCardCollectionCancel($inputs);
               //Total Cancellation
                $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
                $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
                $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
                //Calculate Net Collection
                $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
                $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
                $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];

                //Calculate New Licence
                $data['new_connection'] = $this->Water_Transaction_Model->getTotalNewCollection($inputs);
                $data['demand_collection'] = $this->Water_Transaction_Model->getTotalDemandCollection($inputs);
                //calculate Total 
                $data['total_new_collection'] = $data['new_connection']['consumer']+$data['demand_collection']['consumer'];
                $data['total_new_transaction'] = $data['new_connection']['id']+$data['demand_collection']['id'];
                $data['total_new_amount'] = $data['new_connection']['new']+$data['demand_collection']['renewal'];
            }
            return view('water/water_connection/water_collection_summary',$data);
        }else{
            $inputs['from_date'] = date('Y-m-d');
            $inputs['to_date'] = date('Y-m-d');
            $data['cash'] = $this->Water_Transaction_Model->getTotalCashCollection($inputs);
            $data['cheque'] = $this->Water_Transaction_Model->getTotalChequeCollection($inputs);
            $data['dd'] = $this->Water_Transaction_Model->getTotalDDCollection($inputs);
            $data['online'] = $this->Water_Transaction_Model->getTotalOnlineCollection($inputs);
            $data['card'] = $this->Water_Transaction_Model->getTotalCardCollection($inputs);
            //Calculate toal
            $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
            $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
            $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
            //cancel Amount Caculation
            $data['cheque_cancel'] = $this->Water_Transaction_Model->getTotalChequeCollectionCancel($inputs);
            $data['dd_cancel'] = $this->Water_Transaction_Model->getTotalDDCollectionCancel($inputs);
            $data['online_cancel'] = $this->Water_Transaction_Model->getTotalOnlineCollectionCancel($inputs);
            $data['card_cancel'] = $this->Water_Transaction_Model->getTotalCardCollectionCancel($inputs);
           //Total Cancellation
            $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
            $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
            $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
            //Calculate Net Collection
            $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
            $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
            $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];

            //Calculate New Licence
            $data['new_connection'] = $this->Water_Transaction_Model->getTotalNewCollection($inputs);
            $data['demand_collection'] = $this->Water_Transaction_Model->getTotalDemandCollection($inputs);
            //calculate Total 
            $data['total_new_collection'] = $data['new_connection']['consumer']+$data['demand_collection']['consumer'];
            $data['total_new_transaction'] = $data['new_connection']['id']+$data['demand_collection']['id'];
            $data['total_new_amount'] = $data['new_connection']['new']+$data['demand_collection']['renewal'];
            return view('water/water_connection/water_collection_summary',$data);
        }
    } 
}
?>

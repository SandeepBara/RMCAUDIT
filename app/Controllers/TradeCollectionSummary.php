<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeTransactionModel;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;

class TradeCollectionSummary extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $TradeTransactionModel;
    protected $model_ward_mstr;
    protected $model_emp_details;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
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
            if($inputs['ward_mstr_id']!="")
            { //Get Particular Record
              $data['cash'] = $this->TradeTransactionModel->getTotalCashCollection($inputs);
              $data['cheque'] = $this->TradeTransactionModel->getTotalChequeCollection($inputs);
              $data['dd'] = $this->TradeTransactionModel->getTotalDDCollection($inputs);
              $data['online'] = $this->TradeTransactionModel->getTotalOnlineCollection($inputs);
              $data['card'] = $this->TradeTransactionModel->getTotalCardCollection($inputs);
               //Calculate toal
              $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
              $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
              $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
               //cancel Amount Caculation
               $data['cheque_cancel'] = $this->TradeTransactionModel->getTotalChequeCollectionCancel($inputs);
               $data['dd_cancel'] = $this->TradeTransactionModel->getTotalDDCollectionCancel($inputs);
               $data['online_cancel'] = $this->TradeTransactionModel->getTotalOnlineCollectionCancel($inputs);
               $data['card_cancel'] = $this->TradeTransactionModel->getTotalCardCollectionCancel($inputs);
               //Total Cancellation
               $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
               $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
               $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
               //Calculate Net Collection
                $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
                $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
                $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];
                //Calculate New Licence
                $data['new_licence'] = $this->TradeTransactionModel->getTotalNewLicenceCollection($inputs);
                $data['renewal_licence'] = $this->TradeTransactionModel->getTotalRenewalLicenceCollection($inputs);
                $data['amendment_licence'] = $this->TradeTransactionModel->getTotalAmendmentLicenceCollection($inputs);
                $data['surender_licence'] = $this->TradeTransactionModel->getTotalSurenderLicenceCollection($inputs);
                 //calculate Total 
                $data['total_new_licence_holder'] = $data['new_licence']['consumer']+$data['renewal_licence']['consumer']+$data['amendment_licence']['consumer']+$data['surender_licence']['consumer'];
                $data['total_new_transaction'] = $data['new_licence']['id']+$data['renewal_licence']['id']+$data['amendment_licence']['id']+$data['surender_licence']['id'];
                $data['total_new_amount'] = $data['new_licence']['new']+$data['renewal_licence']['renewal']+$data['amendment_licence']['amendment']+$data['surender_licence']['surender'];
            }
            else
            { //Get All Records
                $data['cash'] = $this->TradeTransactionModel->getTotalCashCollection($inputs);
                $data['cheque'] = $this->TradeTransactionModel->getTotalChequeCollection($inputs);
                $data['dd'] = $this->TradeTransactionModel->getTotalDDCollection($inputs);
                $data['online'] = $this->TradeTransactionModel->getTotalOnlineCollection($inputs);
                $data['card'] = $this->TradeTransactionModel->getTotalCardCollection($inputs);
                 //Calculate toal
                $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
                $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
                $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
                //cancel Amount Caculation
                $data['cheque_cancel'] = $this->TradeTransactionModel->getTotalChequeCollectionCancel($inputs);
                $data['dd_cancel'] = $this->TradeTransactionModel->getTotalDDCollectionCancel($inputs);
                $data['online_cancel'] = $this->TradeTransactionModel->getTotalOnlineCollectionCancel($inputs);
                $data['card_cancel'] = $this->TradeTransactionModel->getTotalCardCollectionCancel($inputs);
                //Total Cancellation
                $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
                $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
                $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
                //Calculate Net Collection
                $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
                $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
                $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];
                //Calculate New Licence
                $data['new_licence'] = $this->TradeTransactionModel->getTotalNewLicenceCollection($inputs);
                $data['renewal_licence'] = $this->TradeTransactionModel->getTotalRenewalLicenceCollection($inputs);
                $data['amendment_licence'] = $this->TradeTransactionModel->getTotalAmendmentLicenceCollection($inputs);
                $data['surender_licence'] = $this->TradeTransactionModel->getTotalSurenderLicenceCollection($inputs);
                 //calculate Total 
                $data['total_new_licence_holder'] = $data['new_licence']['consumer']+$data['renewal_licence']['consumer']+$data['amendment_licence']['consumer']+$data['surender_licence']['consumer'];
                $data['total_new_transaction'] = $data['new_licence']['id']+$data['renewal_licence']['id']+$data['amendment_licence']['id']+$data['surender_licence']['id'];
                $data['total_new_amount'] = $data['new_licence']['new']+$data['renewal_licence']['renewal']+$data['amendment_licence']['amendment']+$data['surender_licence']['surender'];
            }
            // return view('report/trade_collection_summary',$data);
        }
        else
        {
            $inputs['from_date'] = date('Y-m-d');
            $inputs['to_date'] = date('Y-m-d');
            $inputs['ward_mstr_id']='';
            $data['cash'] = $this->TradeTransactionModel->getTotalCashCollection($inputs);
            $data['cheque'] = $this->TradeTransactionModel->getTotalChequeCollection($inputs);
            $data['dd'] = $this->TradeTransactionModel->getTotalDDCollection($inputs);
            $data['online'] = $this->TradeTransactionModel->getTotalOnlineCollection($inputs);
            $data['card'] = $this->TradeTransactionModel->getTotalCardCollection($inputs);
            //Calculate toal
            $data['total_consumer'] = $data['cash']['consumer']+$data['cheque']['consumer']+$data['dd']['consumer']+$data['online']['consumer']+$data['card']['consumer'];
            $data['total_transaction'] = $data['cash']['id']+$data['cheque']['id']+$data['dd']['id']+$data['online']['id']+$data['card']['id'];
            $data['total_amount'] = $data['cash']['cash']+$data['cheque']['cheque']+$data['dd']['dd']+$data['online']['online']+$data['card']['card'];
            //cancel Amount Caculation
            $data['cheque_cancel'] = $this->TradeTransactionModel->getTotalChequeCollectionCancel($inputs);
            $data['dd_cancel'] = $this->TradeTransactionModel->getTotalDDCollectionCancel($inputs);
            $data['online_cancel'] = $this->TradeTransactionModel->getTotalOnlineCollectionCancel($inputs);
            $data['card_cancel'] = $this->TradeTransactionModel->getTotalCardCollectionCancel($inputs);
            //Total Cancellation
            $data['total_consumer_cancel'] =$data['cheque_cancel']['consumer']+$data['dd_cancel']['consumer']+$data['online_cancel']['consumer']+$data['card_cancel']['consumer'];
            $data['total_transaction_cancel'] = $data['cheque_cancel']['id']+$data['dd_cancel']['id']+$data['online_cancel']['id']+$data['card_cancel']['id'];
            $data['total_amount_cancel'] = $data['cheque_cancel']['cheque']+$data['dd_cancel']['dd']+$data['online_cancel']['online']+$data['card_cancel']['card'];
            //Calculate Net Collection
            $data['net_consumer'] = $data['total_consumer']-$data['total_consumer_cancel'];
            $data['net_transaction'] = $data['total_transaction']-$data['total_transaction_cancel'];
            $data['net_amount'] = $data['total_amount']-$data['total_amount_cancel'];

            //Calculate New Licence
            $data['new_licence'] = $this->TradeTransactionModel->getTotalNewLicenceCollection($inputs);
            $data['renewal_licence'] = $this->TradeTransactionModel->getTotalRenewalLicenceCollection($inputs);
            $data['amendment_licence'] = $this->TradeTransactionModel->getTotalAmendmentLicenceCollection($inputs);
            $data['surender_licence'] = $this->TradeTransactionModel->getTotalSurenderLicenceCollection($inputs);
            //calculate Total 
            $data['total_new_licence_holder'] = $data['new_licence']['consumer']+$data['renewal_licence']['consumer']+$data['amendment_licence']['consumer']+$data['surender_licence']['consumer'];
            $data['total_new_transaction'] = $data['new_licence']['id']+$data['renewal_licence']['id']+$data['amendment_licence']['id']+$data['surender_licence']['id'];
            $data['total_new_amount'] = $data['new_licence']['new']+$data['renewal_licence']['renewal']+$data['amendment_licence']['amendment']+$data['surender_licence']['surender'];

            //print_r($data['surender_licence']);
        }
        if(isset($_REQUEST['api'])){
            return $data;
        }
        return view('report/trade_collection_summary',$data);
    } 


    public function collection_details(){
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $data['ulb_name']=$ulb_dtl['ulb_name'];
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['team_leader'] = $this->model_emp_details->get_team_leaderActive();

        // print_var($data['team_leader']);return;
        return view('report/trade_collection_details',$data);
    } 
    
    public function get_tax_collector_ajax()
    {
          if($this->request->getMethod()=='post'){
 
            try{
                // data filter
                 $team_leader_id = sanitizeString($this->request->getVar('team_leader_id'));          
                 $data['tax_collector'] = $this->model_emp_details->get_tax_collector($team_leader_id);
                 $output = "";
                 $output.=  '<option value="">Select</option>';
                 foreach($data['tax_collector'] as $value)
                 {
                    $output.=  '<option value="'.$value['id'].'">'.$value['emp_name'].'</option>';
                 }
                 
                 return json_encode($output);
            }catch(\Exception $e){

            }
        }
    }

    public function get_collection_details_ajax()
    {
        if($this->request->getMethod()=='post'){
 
            try{
                // data filter
                  $from_date = sanitizeString($this->request->getVar('from_date'));          
                  $to_date = sanitizeString($this->request->getVar('to_date'));          
                   $tax_collector_id = sanitizeString($this->request->getVar('tax_collector_id'));  
                   if($tax_collector_id!="")
                   {        
                   $data['collection_details'] = $this->TradeTransactionModel->get_collection_details_with_id( $from_date,$to_date,$tax_collector_id);
                   }
                   else{
                   $data['collection_details'] = $this->TradeTransactionModel->get_collection_details( $from_date,$to_date);
                   }
                   $output = "";
                   $sn=1;
                   $total_consumer = 0;
                   $total_collection = 0;
                   if($data['collection_details']!=0)
                   {
                   foreach($data['collection_details'] as $value)
                   {
                    $color = ($value['tax_collector']=="ONLINE")?"text-info":"text-secondary";
                    $output .= '<tr>
                                    <td id="leftTd" class="col-sm-1">'.$sn++.'</td>
                                    <td id="leftTd"><span class="'.$color.'">'.$value['tax_collector']." &nbsp; ".$value['last_name'].'</span></td>
                                    <td id="leftTd">'.$value['id'].'</td>
                                    <td id="leftTd">'.$value['paid_amount'].'</td>
                                </tr>';
                    $total_consumer +=  $value['id']; 
                    $total_collection +=  $value['paid_amount'];         
                   }
                   }
                   else{
                       $output .= '<tr>
                                    <td id="leftTd" class="col-sm-1" colspan="4">No Result</td>
                                </tr>';
                   }

                   $response = array(
                    "output_tbl" => $output,
                    "from_date_to_date" =>'From '. date("d-m-Y", strtotime($from_date)) .' To '. date("d-m-Y", strtotime($to_date)),
                    "ttl_cons" => 'Total Consumers :- '.$total_consumer, 
                    "ttl_collloectn" => 'Total Collections :- '.$total_collection,
                );

                return json_encode($response);
            }catch(\Exception $e){

            }
        } 
    }

    
}
?>

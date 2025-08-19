<?php namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\model_trade_transaction;
use App\Models\model_emp_details;
use App\Models\model_datatable;



class TradeTCWiseCollectionReports extends AlphaController
{
    protected $db;
    protected $model_trade_transaction;
	protected $model_emp_details;
    protected $model_datatable;

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
        

       
        $this->model_trade_transaction=new model_trade_transaction($this->trade);
        $this->model_emp_details=new model_emp_details($this->dbSystem);
        $this->model_datatable = new model_datatable($this->trade);

    }
    public function index()
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['tc_list']=$this->model_emp_details->gettcList();
        
        return view('report/tc_wise_collection_reports_for_trade',$data);
    }


    public function get_data_tc_wise_collection_ajax() {
       
        if($this->request->getMethod()=='post'){
 
            try{

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
               
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                 if ($columnName=="s_no")
                     $columnName = 'tbl_transaction.id';                    
                if ($columnName=="ward_no")
                     $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="transaction_no")                   
                    $columnName = 'tbl_transaction.transaction_no';
                if ($columnName=="transaction_date")
                    $columnName = 'tbl_transaction.transaction_date';
                if ($columnName=="application_no")
                    $columnName = 'tbl_apply_licence.application_no';
                if ($columnName=="payment_mode")
                    $columnName = 'tbl_transaction.payment_mode';
                if ($columnName=="paid_amount")
                    $columnName = 'tbl_transaction.paid_amount';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';
 
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                $searchQuery = "";
                $whereQuery = "";
                $total = 0;
                                
                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));          
                $tc_id = sanitizeString($this->request->getVar('tc_id'));
                $app_type = sanitizeString($this->request->getVar('appl_type'));
                $pay_mode = sanitizeString($this->request->getVar('paymnt_mode')); 
                $data['to_date'] = $to_date;
                $data['from_date'] = $from_date;
                $data['tc_id']=$tc_id;
                $data['app_type'] = $app_type;
                $data['pay_mode']=$pay_mode;
                 
                if($tc_id =="0" && $app_type =="all" && $pay_mode =="all")
                { 
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                }
                 
                elseif($tc_id !="0" && $app_type !="all" && $pay_mode !="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.emp_details_id =  '".$tc_id."'";  
                $whereQuery .= "  AND  tbl_transaction.transaction_type =  '".$app_type."'";  
                $whereQuery .= "  AND  tbl_transaction.payment_mode =  '".$pay_mode."'"; 
                }
                
                 elseif($tc_id !="0" && $app_type =="all" && $pay_mode =="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.emp_details_id =  '".$tc_id."'";  
                }
                 elseif($tc_id !="0" && $app_type !="all" && $pay_mode =="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.emp_details_id =  '".$tc_id."'";  
                $whereQuery .= "  AND  tbl_transaction.transaction_type =  '".$app_type."'";  
                }
                 elseif($tc_id !="0" && $app_type =="all" && $pay_mode !="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.emp_details_id =  '".$tc_id."'";  
                $whereQuery .= "  AND  tbl_transaction.payment_mode =  '".$pay_mode."'"; 
                }
                 elseif($app_type !="all" && $tc_id =="0" && $pay_mode =="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.transaction_type =  '".$app_type."'";  
                 }
                 elseif($app_type !="all" && $tc_id =="0" && $pay_mode !="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.transaction_type =  '".$app_type."'";  
                $whereQuery .= "  AND  tbl_transaction.payment_mode =  '".$pay_mode."'";
                }
                 elseif($pay_mode !="all" && $tc_id =="0" && $app_type =="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $whereQuery .= "  AND  tbl_transaction.payment_mode =  '".$pay_mode."'"; 
                }

                $data['transaction']=$this->model_trade_transaction->get_total_paid_amount_trade_transaction($data);
           
                foreach($data['transaction'] as $value)
                 {
                    $total=$total+$value['paid_amount'];
                    
                 }
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                if($rowperpage< 0)
                {
                    $limit ="";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                      
                $whereQueryWithSearch = " AND (tbl_apply_licence.application_no ILIKE '%".$searchValue."%'
                        OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                        OR tbl_apply_licence.firm_name ILIKE '%".$searchValue."%'
                        OR view_emp_details.emp_name ILIKE '%".$searchValue."%')";
                                    
                }

                
                
                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY ".$columnName." DESC) AS s_no,
                view_ward_mstr.ward_no,
                tbl_transaction.transaction_no,
                tbl_transaction.transaction_date,
                tbl_apply_licence.application_no,
                tbl_apply_licence.firm_name,
                tbl_transaction.payment_mode,
                tbl_transaction.paid_amount,
                tbl_cheque_dtl.cheque_no,
                tbl_cheque_dtl.bank_name,
                tbl_cheque_dtl.branch_name,
                view_emp_details.emp_name
                 ";      

                $sql ="from tbl_transaction 
                       left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
                       left join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id = tbl_transaction.id and tbl_cheque_dtl.status <> 0 
                       left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
                       left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
                       where tbl_transaction.status in(1,2) 
                       and ".$whereQuery;

                  $totalRecords = $this->model_datatable->getTotalRecords($sql);  
                  if($totalRecords==0)
                  {
                    $total = 0;
                  }
              // return json_encode([$totalRecords]);
                if ($totalRecords>0) { 
                    ## Total number of records with filtering
                        $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                        $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    
                        $records = $this->model_datatable->getRecords($fetchSql);

                    //code added on fly, garbage is still left above
                    $newSql = $selectStatement . $sql . $whereQueryWithSearch ;
                    $totalRecordsForCounting = $this->model_datatable->getRecords($newSql);

                    $total =0;
                    foreach ($totalRecordsForCounting as $valss){
                        $total = $total+$valss['paid_amount'];
                    }
                    // end of code added on fly 

                   // return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total"  => $total,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }


}

?>

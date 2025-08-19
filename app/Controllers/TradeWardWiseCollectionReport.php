<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_prop_owner_detail;
use App\Models\model_trade_transaction;
use App\Models\model_ward_mstr;
use App\Models\model_datatable;


class TradeWardWiseCollectionReport extends AlphaController
{
    protected $db;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_prop_owner_detail;
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
        

        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_trade_transaction=new model_trade_transaction($this->trade);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->model_datatable = new model_datatable($this->trade);

    }
    public function report()
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        if($this->request->getMethod()=='post')
        {
            //print_r($this->request->getVar());

            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id']=$ward_id;
            
            if($data['ward_id']!="")
            {
                $data['transaction']=$this->model_trade_transaction->getAllTransactionsWardWise($data);
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmountWardWise($data);           
            }
            else
            {
                $data['transaction']=$this->model_trade_transaction->getAllTransaction($data);
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmount($data);
            }
        }
        else
        {
            $data['to_date'] = date('Y-m-d');
            $data['from_date'] = date('Y-m-d');
            
            $data['transaction']=$this->model_trade_transaction->getAllTransaction($data);
            $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmount($data);
        }

        return view('report/trade_ward_wise_collection_reports',$data);
    }


    public function get_data_ward_wise_collection_ajax() {
       
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
                $total=0;
                $getTotalAmount=0;
                                
                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));          
                $ward_id = sanitizeString($this->request->getVar('ward_id'));          
                $data['to_date'] = $to_date;
                $data['from_date'] = $from_date;
                $data['ward_id']=$ward_id;
                
                
                if($ward_id=="all")
                {
                $whereQuery .= "   tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND  tbl_transaction.transaction_date <=  '".$to_date."'";  
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmount($data);
                $getTotalAmount =  $data['getTotalAmount'];
                if($getTotalAmount=="")
                {
                    $getTotalAmount=0;
                }
                $data['transaction']=$this->model_trade_transaction->getAllTransaction($data);
                foreach($data['transaction'] as $value)
                {
                    $total=$total+$value['paid_amount'];
                }

                }
                else
                {
                $whereQuery .= "  tbl_transaction.transaction_date >=  '".$from_date."'";
                $whereQuery .= "  AND tbl_transaction.transaction_date <=  '".$to_date."'";
                $whereQuery .= "  AND tbl_transaction.ward_mstr_id=  '".$ward_id."'";
                $data['getTotalAmount']=$this->model_trade_transaction->getTotalAmountWardWise($data); 
                $getTotalAmount =  $data['getTotalAmount'];
                if($getTotalAmount=="")
                {
                    $getTotalAmount=0;
                }
                $data['transaction']=$this->model_trade_transaction->getAllTransactionsWardWise($data);
                foreach($data['transaction'] as $value)
                {
                    $total=$total+$value['paid_amount'];
                }
                }   
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
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
                view_emp_details.emp_name,
                tbl_firm_owner_name.owner_name
                ";      

                $sql ="from tbl_transaction left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id left join (select apply_licence_id,string_agg(owner_name,',') as owner_name from tbl_firm_owner_name group by apply_licence_id) as  tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id=tbl_apply_licence.id 
                left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
                left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
                where tbl_transaction.status in(1,2) and ".$whereQuery;

                $totalRecords = $this->model_datatable->getTotalRecords($sql);
              // return json_encode([$totalRecords]);
                if ($totalRecords>0) { 
                    ## Total number of records with filtering
                      $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                        $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    
                        $records = $this->model_datatable->getRecords($fetchSql);
                    //return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "getTotalAmount" => $getTotalAmount,
                    "total" => $total
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }
}

?>

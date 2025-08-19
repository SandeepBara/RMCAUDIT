<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\model_datatable;


class test_api extends Controller
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    
    
    public function __construct()
    {   

        /*$session=session();
        $ulb_details=$session->get('ulb_dtl');*/
        //print_r($ulb_details);



        /*parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        helper(['form']);*/
       /* $this->db = db_connect("db_rmc_trade"); 
         $this->dbSystem = db_connect("db_system"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
       // $this->model_datatable = new model_datatable($this->db);
        $this->model_datatable = new model_datatable($this->db);*/
        
    }
    
    public function index()
    {    
    	return "asdasd";
        
        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        $data['view']=$view;

        $where=1;
        /*if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_mstr_id']=$inputs['ward_mstr_id'];
            $data["fromdate"]=$inputs['from_date'];
            $data["todate"]=$inputs['to_date'];
            //print_r($inputs);
            $data['keyword']=$inputs['keyword'];  
            $data['likestmt']=[
                'application_no' => strtoupper($inputs['keyword']),
                'holding_no' => strtoupper($inputs['keyword']),
                'applicant_name' => strtoupper($inputs['keyword']),
                'mobile_no' => strtoupper($inputs['keyword'])
            ];         

             
        } 
        else{     $data["fromdate"]=date('Y-m-d');
            $data["todate"]=date('Y-m-d');          
                
            }  

             if($data['ward_mstr_id']==''){
                 $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_details($data);
                 // print_r($data['application_details']);
             }else{
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_detailsward($data);
             }  */  

       
       // print_r($data['application_details']);

       return view('trade/connection/SearchApplicant',$data);
    }
    
    public function tradeapplicationlistAjax() {
        if($this->request->getMethod()=='post'){
            try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="application_no")
                    $columnName = 'apply.application_no';
                if ($columnName=="license_no")
                    $columnName = 'apply.license_no';
                if ($columnName=="firm_name")
                    $columnName = 'apply.firm_name';
                if ($columnName=="application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName=="apply_date")
                    $columnName = 'apply.apply_date';
                if ($columnName=="apply_by")
                    $columnName = 'view_emp_details.name';
                

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));               
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND apply.apply_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  view_ward_mstr.ward_no='".$search_ward_mstr_id."'";
                }                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (apply.application_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR apply.license_no ILIKE '%".$searchValue."%'
                                    OR apply.firm_name ILIKE '%".$searchValue."%'
                                    OR view_emp_details.name ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    apply.application_no,
                                    apply.firm_name,
                                    tbl_application_type.application_type,
                                    apply.apply_date,
                                    view_emp_details.emp_name";
                $sql =  " FROM tbl_apply_licence apply  
                        INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id                      
                        INNER JOIN view_emp_details ON view_emp_details.id=apply.emp_details_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=apply.ward_mstr_id                        
                        WHERE apply.status>0 ".$whereQuery;
                
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                        return json_encode(['jjj'=>$sql]);
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;

                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Extention $e){

            }
        }
    }
    
    
}
<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use App\Models\model_datatable;
use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterApplicationLevelPendingReport extends AlphaController
{   
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    
    
    public function __construct()
    {   
        
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper']);
        helper(['form','form_helper','url_helper','utility_helper','qr_code_generator_helper']);
        helper(['url',  'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_report_model=new WaterReportModel($this->db);
        $this->model_datatable = new model_datatable($this->db);

    }
    
    public function index()
    {   
    	
        
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_var($data);
        $data['view']=$view??null;

        $where=1;
        if($this->request->getMethod()=='post')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];
            $data['level_status']=$inputs['level_status'];
            $where2="";
            if($data['level_status']==1)
            {
                $where2=" and receiver_user_type_id=16 and status!=0";
            }

            if($data['ward_id']!="")
            {
            	$where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
            }
            else
            {
            	$where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }

            $data['application_level_pending_list']=$this->water_report_model->applicationLevelPendingStatusDetail($where);
            //print_r($data['application_level_pending_list']);
            

        }
        else
        {
        	 $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
        }
        

        return view('water/report/application_level_pending_report',$data);
        
    }
    

    public function index_copy()
    {   
    	
        
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_var($data);
        $data['view']=$view??null;

        $where=1;
        if(strtolower($this->request->getMethod())=='post')
        {   

            $inputs = $this->request->getVar();
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];
            $data['level_status']=$inputs['level_status'];
            $where2="";
            
            if($data['level_status']==1)
            {
                $where2=" and receiver_user_type_id=16 and status!=0";
            }

            if($data['ward_id']!="")
            {
            	$where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
            }
            else
            {
            	$where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }

            try
            {
                $start = sanitizeString($this->request->getVar('start'));
                    
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="ward_no" )
                    $columnName='ward_no';
                else if ($columnName=="application_no")
                    $columnName = 'application_no';
                else if ($columnName=="applicant_name")
                    $columnName = 'applicant_name';    
                else if ($columnName=="mobile_no")
                    $columnName = 'mobile_no';            
                else
                    $columnName = 'apply_connection_id';
    
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filterarchValue = sanitizeString($this->request->getVar('search')['value']);
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start; 
    
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch = " AND (mobile_no ILIKE '%".$searchValue."%'
                                    OR application_no ILIKE '%".$searchValue."%'
                                    OR applicant_name ILIKE '%".$searchValue."%'
                                    OR ward_no ILIKE '%".$searchValue."%'
                                    
                                     )";
                }
                $base_url = base_url();   
                
                $select="SELECT ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                            *
                ";
                $from_tbl=" from view_application_level_pending_details $where                              
                ";
                
                $totalRecords = $this->model_datatable->getTotalRecords($from_tbl,false);
                
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from_tbl.$whereQueryWithSearch,false,);
                    
                    ## Fetch records            
                    $fetchSql = $select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit;
                   
                    $result = $this->model_datatable->getRecords($fetchSql,false);                
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'application_no'=>$tran_dtl['application_no'],
                                'applicant_name'=>$tran_dtl['applicant_name'],
                                'mobile_no'=>$tran_dtl['mobile_no'],
                                'category'=>$tran_dtl['category'],
                                'connection_type'=>$tran_dtl['connection_type'],
                                'apply_date'=>$tran_dtl['apply_date'],
                                'view'=>"<a href=".base_url().'/WaterApplyNewConnection/water_connection_view/'.md5($tran_dtl['apply_connection_id'])." class='btn btn-info'>View</a>",
                                
                            ];
                        }
                    }
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
    
                $response = array(
                    "draw" => 0,                
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                    "Total"=> isset($data['total'])?number_format($data['total']):0,                
    
                );
                return json_encode($response); 
                
            }
            catch(Exception $e)
            {
                print_r($e);
            } 
            $sql="select * from view_application_level_pending_details $where";
            $data['results']=$this->water_report_model->consumerWiseDCB2($sql);
            $data['application_level_pending_list']=$data['results']['result'];
            $data['count']=$data['results']['count'];
            $data['offset']=$data['results']['offset'];          
            

        }
        else
        {
        	 $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
        }
        

        return view('water/report/application_level_pending_report_copy',$data);
        
    }
    public function index_copyExcel($date_from=null,$date_upto=null,$ward_id=null,$level_status=null)
    {
        $inputs = $this->request->getVar();
        
        try
        {    
            $data['date_from']=$date_from;
            $data['date_upto']=$date_upto;
            $data['ward_id']=$ward_id;
            $data['level_status']=$level_status;
            $where2="";
            
            if($data['level_status']==1)
            {
                $where2=" and receiver_user_type_id=16 and status!=0";
            }

            if($data['ward_id']!="ALL")
            {
                $where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
            }
            else
            {
                $where=" where verification_status=".$data['level_status']." $where2 and level_date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }            
            
            $selectStatement=" SELECT *
            ";
            $sql=" from view_application_level_pending_details
                    $where                              
            ";

            $fetchSql = $selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            //print_var($result);die;
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        'ward_no'=>$tran_dtl['ward_no'],
                        'application_no'=>$tran_dtl['application_no'],
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'category'=>$tran_dtl['category'],
                        'connection_type'=>$tran_dtl['connection_type'],
                        'apply_date'=>$tran_dtl['apply_date'],
                        
                    ];

                }
                
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A2', 'Ward No.');
                            $activeSheet->setCellValue('B2', 'Application No.');
                            $activeSheet->setCellValue('C2', 'Applicant Name');
                            $activeSheet->setCellValue('D2', 'Mobile No.');
                            $activeSheet->setCellValue('E2', 'Category');
                            $activeSheet->setCellValue('F2', 'Connection Type');
                            $activeSheet->setCellValue('G2', 'Apply Date');
                            // $activeSheet->setCellValue('H2', 'Cheque No.');
                            // $activeSheet->setCellValue('I2', 'Cheuqe Date');
                            // $activeSheet->setCellValue('J2', 'Bank Name');
                            // $activeSheet->setCellValue('K2', 'Branch Name');
                            // $activeSheet->setCellValue('L2', 'Collected By');


                            $activeSheet->fromArray($records, NULL, 'A4');

            $filename = "Application Level Status Lists".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {   
            print_var($e);
        }

    }
    


    public function getPagination() {
        if($this->request->getMethod()=='post'){
            try{
                ## Read value
              $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
                if ($rowperpage=='-1')
                    $rowperpage = 1000000000; // 1,00,00,00,000 (All Condition)
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no")
                    $columnName = 'id';
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                ## Search 
                $searchQuery = "";
                if($searchValue != ''){
                    $searchQuery = "(ward_no ILIKE '%".$searchValue."%')";
                }

                // Date filter
                $search_by_from_ward_id = sanitizeString($this->request->getVar('search_by_from_ward_id'));
                $search_by_upto_ward_id = sanitizeString($this->request->getVar('search_by_upto_ward_id'));
                if ($search_by_from_ward_id != '' && $search_by_upto_ward_id != '') {
                    if($searchQuery!="")
                        $searchQuery = " AND ";
                    $searchQuery .= " (id between '".$search_by_from_ward_id."' AND '".$search_by_upto_ward_id."' ) ";
                }
                
                ## Total number of records without filtering
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('count(*) as allcount');
                $builder = $builder->get();
                $totalRecords = $builder->getFirstRow('array')['allcount'];

                ## Total number of records with filtering
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('count(*) as allcount');
                if($searchQuery != '')
                    $builder = $builder->where($searchQuery);
                $builder = $builder->get();
                $totalRecordwithFilter = $builder->getFirstRow('array')['allcount'];

                ## Fetch records
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('*');
                if($searchQuery != '')
                    $builder = $builder->where($searchQuery);
                $builder = $builder->orderBy($columnName, $columnSortOrder);
                $builder = $builder->limit($rowperpage, $start);
                $builder = $builder->get();
                //echo $this->db->getLastQuery();
                $records = $builder->getResultArray();

                $data = array();
                $sno = 0;
                foreach ($records AS $record) {
                    $sno++;
                    $data[] = array(
                        "s_no"=>$sno,
                        "id"=>$record['id'],
                        "ward_no"=>$record['ward_no'],
                        "ulb_mstr_id"=>$record['ulb_mstr_id'],
                        "status"=>$record['status']
                    );
                }
                
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $data
                );

                // return json_encode($response);
                // Get data
                /* $data = $this->model_pagination->getWard($inputs);
                return json_encode($data); */
            }catch(Extention $e){

            }
        } else {
            echo "GET";
        }
    }
    
    
    
}
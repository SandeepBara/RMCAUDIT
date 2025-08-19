<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use App\Models\model_emp_details;
use App\Models\model_ward_permission;

use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterLevelWisePendingReport extends AlphaController
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
        $this->model_emp_details=new model_emp_details($this->dbSystem);
        $this->model_ward_permission=new model_ward_permission($this->dbSystem);

    }
    
    public function index()
    {   
    	
        
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        $data['view']=$view;

        $where=1;
        if($this->request->getMethod()=='post')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];

            $users_list=$this->model_emp_details->getWaterUsers();
            //print_r($users_list);

            if($data['ward_id']!="")
            {
            	$where=" level_pending.created_on::date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
            }
            else
            {
            	$where=" level_pending.created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }

            $array=[];
            $level_pending_arr=[]; 
            foreach ($users_list as $key => $value)
            {
              
                $get_permitted_ward=$this->model_ward_permission->getWardDataByEmpdetailsId($value['id']);
                $permitted_ward=$get_permitted_ward['ward_mstr_id'];

                $level_pending_user_wise_count=$this->water_report_model->levelPendingUserWiseCount($where,$value['user_type_mstr_id'],$permitted_ward);
               

                $array[]=array("employee_name"=>$value['emp_name'],"designation"=>$value['designation'],"pending"=>$level_pending_user_wise_count,"permitted_ward"=>$permitted_ward,"user_type_mstr_id"=>$value['user_type_mstr_id']);

                

            }

            

        }
        else
        {
        	 $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
        }
        
        $_SESSION['where_level']=$where;
        $data['level_wise_pending']=$array;
        //print_r($data['level_wise_pending']);
            

        return view('water/report/level_wise_pending_report',$data);
        
    }

    public function levelwisePendingLists($user_type_mstr_id,$permitted_ward)
    {
        $data['level_wise_pending_lists']=$this->water_report_model->levelPendingUserWiseList($_SESSION['where_level'],$user_type_mstr_id,$permitted_ward);
        
        return view('water/report/level_wise_pending_list_report',$data);
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
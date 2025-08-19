<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterSiteInspectionModel;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterInspectionDetailsReport extends AlphaController
{   
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    protected $model_view_ward_permission;
    protected $model_datatable;
    
    
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
        
        helper(['form',"utility_helper"]);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->site_inspection_model=new WaterSiteInspectionModel($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_datatable = new model_datatable($this->db);
        
    }
    
    public function index()
    {   
    	
        
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        // $data['view']=$view;
        $data['ward_id']='';
        $data['date_from']=$data['date_upto']=date('Y-m-d');
        $data['site_insp_dtls']=null;
        
        $where=1;
        if($this->request->getMethod()=='get')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id']??"";

            if($data['ward_id']!="")
            {
            	$where=" inspection_date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
            }
            else
            {
            	$where=" inspection_date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }

            $sql="select view_ward_mstr.ward_no,application_no,applicant_name,mobile_no,connection_type,apply_date,inspection_date,
                    tbl_apply_water_connection.id as apply_connection_id,
                    tbl_site_inspection.emp_details_id,
                    view_emp_details.emp_name,
                    tbl_site_inspection.verified_by
                from tbl_site_inspection 
                join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_site_inspection.apply_connection_id
                join view_ward_mstr on view_ward_mstr.id = tbl_apply_water_connection.ward_id
                JOIN ( SELECT tbl_applicant_details.apply_connection_id,
                            string_agg((tbl_applicant_details.applicant_name)::text, ','::text) AS applicant_name,
                            string_agg((tbl_applicant_details.mobile_no)::text, ','::text) AS mobile_no
                        FROM tbl_applicant_details
                        GROUP BY tbl_applicant_details.apply_connection_id
                    ) owner on owner.apply_connection_id = tbl_apply_water_connection.id
                    
                LEFT JOIN tbl_connection_type_mstr on tbl_connection_type_mstr.id = tbl_site_inspection.connection_type_id 
                LEFT JOIN view_emp_details ON view_emp_details.id = tbl_site_inspection.emp_details_id
                where $where ";
            $result = $this->model_datatable->getDatatable($sql);
            $data['site_insp_dtls']=$result['result']??null;
            $data['count']= $result['count']??0;
            $data['offset']= $result['offset']??0;
            // print_var($result);
            

        }
       return view('water/report/inspection_details_report',$data);
       
    }
    public function levelSiteInspection ()
    {
        $emp_mstr = session()->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
		$receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $data['ulb_mstr_id']=$this->ulb_id;
        $user_type = "JuniorEngineer";
        $wardIds='';
        $where="1=1 ";
        if(in_array($user_type_mstr_id,[13,15]))
        {
            if($user_type_mstr_id==15)
                $user_type="AssistantEngineer";

            $wardList = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
            $data['ward_list'] = $wardList; 
            $wardIds=array_map(function($val)
            {			
                return $val['ward_mstr_id'];
                
            },$data['ward_list']);		
            $wardIds = implode(',',$wardIds);
        }
        else
        {
            $user_type="";
            $data['ward_list']=$this->ward_model->getWardList($data);
        }
        if($user_type)
            $where .= " AND  verified_by='$user_type' AND tbl_site_inspection.emp_details_id = $emp_details_id ";
        $data['ward_id']='';
        $data['date_from']=$data['date_upto']=date('Y-m-d');
        $data['site_insp_dtls']=null;
        if($this->request->getMethod()=='get')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['from_date']=isset($inputs['from_date'])?$inputs['from_date']:date('Y-m-d');
            $data['to_date']=isset($inputs['to_date'])?$inputs['to_date']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id']??"";
            $data['by_holding_owner_dtl']=$inputs['by_holding_owner_dtl']??'';
            $data['keyword']=$inputs['keyword']??'';
            if($data['ward_id']!="")
            {
            	$where .=" AND ward_id=".$data['ward_id'];
            }
            else
            {
                $where.=" AND tbl_apply_water_connection.ward_id in($wardIds) ";
            }
            if(in_array($data['by_holding_owner_dtl'],["BY_APPLICATION_NO","BY_OWNER"]))
            {
                if($data['by_holding_owner_dtl']=="BY_APPLICATION_NO")
                    $where .="AND application_no ILIKE '%".$data['keyword']."%'" ;
                else
                    $where .="AND (owner.applicant_name ILIKE '%".$data['keyword']."%'
                                    OR owner.father_name ILIKE '%".$data['keyword']."%'
                                    OR owner.mobile_no ILIKE '%".$data['keyword']."%')";
            }
            else
                $where .="AND inspection_date between '".$data['from_date']."' and '".$data['to_date']."'";
            
            $sql="select view_ward_mstr.ward_no,
                        application_no,applicant_name,
                        mobile_no,connection_type,apply_date,
                        inspection_date,
                    tbl_apply_water_connection.id as apply_connection_id,
                    tbl_site_inspection.emp_details_id,
                    tbl_site_inspection.verified_by
                from tbl_site_inspection 
                join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_site_inspection.apply_connection_id
                join view_ward_mstr on view_ward_mstr.id = tbl_apply_water_connection.ward_id
                JOIN ( SELECT tbl_applicant_details.apply_connection_id,
                            string_agg((tbl_applicant_details.applicant_name)::text, ','::text) AS applicant_name,
                            string_agg((tbl_applicant_details.father_name)::text, ','::text) AS father_name,
                            string_agg((tbl_applicant_details.mobile_no)::text, ','::text) AS mobile_no
                        FROM tbl_applicant_details
                        GROUP BY tbl_applicant_details.apply_connection_id
                    ) owner on owner.apply_connection_id = tbl_apply_water_connection.id
                    
                JOIN tbl_connection_type_mstr on tbl_connection_type_mstr.id = tbl_site_inspection.connection_type_id 
                where $where  ";
            $result = $this->model_datatable->getDatatable($sql);
            $data['site_insp_dtls']=$result['result']??null;
            $data['count']= $result['count']??0;
            $data['offset']= $result['offset']??0;
            // print_var($data['by_holding_owner_dtl']);
            

        }
       return view('water/report/level_inspection_details_report',$data);
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
            }catch(Exception $e){

            }
        } else {
            echo "GET";
        }
    }
    
    
    
}
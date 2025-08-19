<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterApplicationFormStatusDetailReport extends AlphaController
{   
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    protected $ward_model;
    protected $water_report_model;
    
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

    }
    
    public function index()
    {
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        $where=1;
        if($this->request->getMethod()=='post')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];
            
            if($data['ward_id']!="")
            {
                $where1=" and apply_date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
                $where2=" and m.created_on::date between '".$data['date_from']."' and '".$data['date_upto']."' and ward_id=".$data['ward_id'];
              
            }
            else
            {
                $where1=" and apply_date between '".$data['date_from']."' and '".$data['date_upto']."'";
                $where2=" and m.created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
              
            }
            
            $data['application_form_detail']=$this->water_report_model->getApplicationFormDetail($where1);      
            $data['level_pending_detail']=$this->water_report_model->levelPendingFormDetail($where2);      
            


        }
        else
        {    
             $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
             $data['ward_id']='';
             
        }

        return view('water/report/application_form_detail', $data);
    }


    public function LevelPendingReport()
    {
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        
        if($this->request->getMethod()=='post')
        {   

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];
            
            if($data['ward_id']!="")
            {
            }
            else
            {

            }
            
        }
        else
        {    
             $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
             
        }

        $data['list']=$this->water_report_model->LevelPendingListGrpByUserType();      
        return view('water/report/LevelPendingReport', $data);
    }

    public function LevelWisePendingList($user_type_id, $user_type)
    {
        $sql="select view_water_application_details.*
        from tbl_level_pending 
        join view_water_application_details on view_water_application_details.id=tbl_level_pending.apply_connection_id
        where tbl_level_pending.verification_status=0 and tbl_level_pending.status=1 and md5(tbl_level_pending.receiver_user_type_id::text)='$user_type_id'";
        $builder = $this->db->query($sql);
        $data['list']=$builder->getResultArray(); 
        $data["user_type"]= $user_type;
        return view('water/report/LevelWisePendingList', $data);
    }

    public function getParam($date_from,$date_upto,$ward_id,$result_for)
    {

        $data=array();
        $data['result_for']=$result_for;
        if($ward_id=='All')
        {
        	$where="";
        }
        else
        {
        	$where=" and ward_id=$ward_id";
        }
        if($result_for=='c4ca4238a0b923820dcc509a6f75849b')//1 new connection
        {
            $where=" where status in(1,2) and connection_type_id=1 and apply_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->getApplicationDetails($where);
        }
        else if($result_for=='c81e728d9d4c2f636f067f89cc14862c') //2 reguligetion 
        {
            $where=" where status in(1,2) and connection_type_id=2 and apply_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->getApplicationDetails($where);
        }
        else if($result_for=='eccbc87e4b5ce2fe28308fd9f2a7baf3') //3 jsk pending 
        {
            $where=" where status in(1,2) and (doc_status=0 or payment_status=0) and apply_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->getApplicationDetails($where);
        }
        else if($result_for=='a87ff679a2f3e71d9181a67b7542122c') //4 level pending 
        {

            $where=" where verification_status=0 and level_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->applicationLevelPendingStatusDetail($where);
        }
        else if($result_for=='e4da3b7fbbce2345d7772b0674a318d5') //5 rejected 
        {
            $where=" where verification_status=4 and level_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->applicationLevelPendingStatusDetail($where);
        }
        else if($result_for=='1679091c5a880faf6fb5e6087eb1b2dc') //6 back tot citizen
        {
            $where=" where verification_status=2 and level_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->applicationLevelPendingStatusDetail($where);
        }
        else if($result_for=='8f14e45fceea167a5a36dedd4bea2543') //7 approve
        {
            $where=" where verification_status=1 and receiver_user_type_id=16 and level_date between '$date_from' and '$date_upto' $where";
            $data['result']=$this->water_report_model->applicationLevelPendingStatusDetail($where);
        }
        

        return view('water/report/drilldown_application_form_detail',$data);

        
    }

    public function getPagination() 
    {
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
            }
            catch(Extention $e)
            {

            }
        } else {
            echo "GET";
        }
    }
    
    
    
    
    
}
<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_pagination;

use Exception;

class test_ajax extends Controller
{
	protected $db;
	protected $dbSystem;
	protected $model_pagination;

    public function __construct(){
        //parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper']);
        /* if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        } */
        $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system'); 
        $this->model_pagination = new model_pagination($this->db);
    }
	public function checkSession(){
		
		$session = session();
		print_r($session);
		if(!$session->has('ulb_dtl')){
			echo 'Session not found';
		}else{
			print_r($session->get('ulb_dtl'));
		}
		
	}
    public function index() {
        //$response = SMSJHGOVT(9508297172, 'Happy Birthday Manash');
        return view('pagination/pagination');
    }
	public function getwardList() {
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

                return json_encode($columnName);
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

                return json_encode($response);
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
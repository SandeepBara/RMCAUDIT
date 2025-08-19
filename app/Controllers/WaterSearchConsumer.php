<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\WaterSearchConsumerModel;
use App\Models\model_pagination;
use Exception;

use App\Models\WaterMobileModel;


class WaterSearchConsumer extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    protected $ward_model;
    protected $search_consumer_model;
    
    public function __construct()
    {

        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper','utility_helper']);
        if($db_name = dbConfig("water"))
        {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system);
        }

        helper(['form']);
        //$db_name = db_connect("db_rmc_property");

        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->search_consumer_model= new WaterSearchConsumerModel($this->db);
        $this->WaterMobileModel=new WaterMobileModel($this->db);
    }

    public function index($param='')
    {
        if($param=='search')
        {
            $view="WaterApplyNewConnection/water_connection_view/";
        }
        else if($param=='pay')
        {
            $view="WaterPayment/payment/";
        }
        else if($param=='dues')
        {
            $view="WaterViewConnectionCharge/fee_charge/";
        }
        $data=array();
        $Session = session();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        $data['view']=$view;
        //$Session->remove('tempData');
        $where=' 1=1';
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); 
        }
        if($this->request->getMethod()=='post')
        {
            $inputs = ($this->request->getVar());          
            $Session->set('tempData',$inputs); 
        }
        $tempData=$Session->get('tempData')??null;
        if(isset($_GET['btn_search']) && isset($_GET['ward_id']) && isset($_GET['keyword']))
        {
            $tempData=$this->request->getVar();
        } 
        if(!empty($tempData))
        {
            $inputs = $tempData;
            
            $data['ward_id']=$inputs['ward_id'];
            $data['keyword']=$inputs['keyword'];
            if(empty($data['keyword']))
                $data['keyword']=keyword();
            if($data['ward_id']!="")
            {
                $where=" ward_id=".$data['ward_id'];
            }
            if($data['keyword']!="")
            {
                $where=" applicant_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or application_no like '%".$data['keyword']."%'";
            }
            if($data['ward_id']!="" and $data['keyword']!="")
            {
                $where="ward_id=".$data['ward_id']." and (applicant_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or application_no like '%".$data['keyword']."%')";
            }

        }
        $select="select id,ward_no,application_no,pipeline_type,property_type,connection_type,connection_through,category,apply_date,
                applicant_name,mobile_no ";
        $form= " from view_water_application_details where apply_from!='Existing' and ".$where;
        $data['collection']=$this->WaterMobileModel->get_data_10($form,$select,false);
        $data['count']= $data['collection']['count'];
        $data['offset']=$data['collection']['offset'];
        $data['application_details']= $data['collection']['result'];
        //$data['application_details']=$this->search_consumer_model->fetch_consumer_details($where);
        //print_var($data['collection']);
        return view('water/water_connection/search_consumer', $data);
    }



    public function getPagination() 
    {
        if($this->request->getMethod()=='post')
        {
            try{
                ## Read value
                echo  $start = sanitizeString($this->request->getVar('start'));
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



    public function search() //
    {
        $data['froword_url']=$this->request->getvar('forward_url');
        
        $data['uid']=$this->request->getvar('uid');
        isset($_SESSION['tempData'])?session()->remove('tempData'):'';
        $data['ward_list']=$this->ward_model->getWardList(array('ulb_mstr_id'=>$this->ulb_id));
        //print_var($data['froword_url']);
        
        return view('water/water_connection/search',$data);
    }

    public function esportoexcel($data)
    {

    }



}

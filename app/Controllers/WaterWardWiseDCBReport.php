<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use Exception;


class WaterWardWiseDCBReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    protected $emp_dtl;
    protected $ward_model;
    protected $water_report_model;
    
    //protected $db_name;
    
    
    public function __construct()
    {   
        parent::__construct();
        helper(['db_helper','from-helper']);
        ini_set('memory_limit', '-1');
        helper(['php_office_helper']);
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $this->emp_dtl = $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        
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

    public function __destruct()
    {
        if($this->dbSystem) $this->dbSystem->close();
        if($this->db) $this->db->close();
    }

    public function index()
    {   
        
        $data=array();
        $where="";
        $sql = " select * from tbl_property_type_mstr where status = 1 ";
        $data['property_list'] = $this->water_report_model->row_sql($sql);       
        if($this->request->getMethod()=='post')
        {
            $data['fin_year']=$this->request->getVar('fin_year');
            $explode=explode('-', $data['fin_year']);
            $first=$explode[0];
            $second=$explode[1];
            $last_date=$first.'-03-31';
            $curr_last_date=$second.'-03-31';
            $data['property_type']=$this->request->getVar('property_type')??null;
            if($data['property_type']!='')
            {
                $where=" AND tbl_consumer.property_type_id = ".$data['property_type']." ";

            }

            
        }
        else
        {
            
            if(date('m')=='03' || date('m')=='02' || date('m')=='01')
            {
                 $last_date=(date('Y')-1).'-03-31';
                 $curr_last_date=date('Y').'-03-31';
            }
            else
            {
                 $last_date=date('Y').'-03-31';
                 $curr_last_date=(date('Y')+1).'-03-31';
            }
            $data['fin_year']='';
            $data['property_type']='';
            
        }

        if(in_array(($this->emp_dtl["user_type_mstr_id"]??0),[24,2]))
        {
            
            $consumer_type_wher1 =" AND (tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26')"; 
        
        
            $consumer_type_wher2 =" AND supper_Dray.consumer_id ISNULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
            
            $where1 = " ".$consumer_type_wher1;
            $where2 = " ".$consumer_type_wher2;
            $data['ward_wise_dcb']=$this->water_report_model->wardWiseDCB2PMU($last_date, $curr_last_date, $where,$where1,$where2);
        }
        else{

            $data['ward_wise_dcb']=$this->water_report_model->wardWiseDCB2($last_date, $curr_last_date, $where);
        }
        if($this->request->getVar("api")){
            return $data;
        }
        
        return view('water/report/ward_wise_dcb', $data);
        
    }
    
    public function getPagination()
    {
        if($this->request->getMethod()=='post')
        {
            try
            {
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
    
    public function Details_for_water_charges()
    {
        $data = (array) null;
        $data['ward_wise_dcb']= (array) null;
        $data['fin_year']='';
        $where="";
        $sql = " select * from tbl_property_type_mstr where status = 1 ";
        $data['property_list'] = $this->water_report_model->row_sql($sql); 
        $fyList = array_reverse(fy_year_list());
        foreach($fyList as $fyYear)
        {
            if($fyYear<='2017-2018')
            {
                continue;
            }
            list($formYear,$uptoYear) = explode("-",$fyYear);
            
            $last_date=$formYear.'-03-31';
            $curr_last_date=$uptoYear.'-03-31';
            

            $session=session();
            $ulb_details=$session->get('ulb_dtl')??getUlbDtl();        
            $this->ulb_id=$ulb_details['ulb_mstr_id'];
            
            $sql=" SELECT 
                        --view_ward_mstr.id,view_ward_mstr.ward_no,
                        --========CONSUMERS===================
                            SUM(coalesce(demand.total_consumer,0)) as total_consumer,
                            SUM(coalesce(demand.total_demand_consumer,0)) as total_demand_consumer,

                            SUM(coalesce(demand.non_hh_consumer,0)) as total_non_hh_consumer,
                            SUM(coalesce(demand.residential_non_hh_consumer,0)) as total_residential_non_hh_consumer,
                            SUM(coalesce(demand.commercial_non_hh_consumer,0)) as total_commercial_non_hh_consumer,
                            SUM(coalesce(demand.institutional_non_hh_consumer,0)) as total_institutional_non_hh_consumer,
                            SUM(coalesce(demand.other_non_hh_consumer,0)) as total_other_non_hh_consumer,

                            SUM(coalesce(demand.non_hh_demand_consumer,0)) as total_non_hh_demand_consumer,
                            SUM(coalesce(demand.residential_non_hh_demand_consumer,0)) as total_residential_non_hh_demand_consumer,
                            SUM(coalesce(demand.commercial_non_hh_demand_consumer,0)) as total_commercial_non_hh_demand_consumer,
                            SUM(coalesce(demand.institutional_non_hh_demand_consumer,0)) as total_institutional_non_hh_demand_consumer,
                            SUM(coalesce(demand.other_non_hh_demand_consumer,0)) as total_other_non_hh_demand_consumer,

                            SUM(coalesce(demand.hh_consumer,0)) as total_hh_consumer,
                            SUM(coalesce(demand.residential_hh_consumer,0)) as total_residential_hh_consumer,
                            SUM(coalesce(demand.commercial_hh_consumer,0)) as total_commercial_hh_consumer,
                            SUM(coalesce(demand.institutional_hh_consumer,0)) as total_institutional_hh_consumer,
                            SUM(coalesce(demand.other_hh_consumer,0)) as total_other_hh_consumer,

                            SUM(coalesce(demand.hh_demand_consumer,0)) as total_hh_demand_consumer,
                            SUM(coalesce(demand.residential_hh_demand_consumer,0)) as total_residential_hh_demand_consumer,
                            SUM(coalesce(demand.commercial_hh_demand_consumer,0)) as total_commercial_hh_demand_consumer,
                            SUM(coalesce(demand.institutional_hh_demand_consumer,0)) as total_institutional_hh_demand_consumer,
                            SUM(coalesce(demand.other_hh_demand_consumer,0)) as total_other_hh_demand_consumer,

                            SUM(coalesce(coll.total_consumer,0)) as total_collection_consumer,

                            SUM(coalesce(coll.non_hh_consumer,0)) as total_collection_non_hh_consumer,
                            SUM(coalesce(coll.residential_non_hh_consumer,0)) as total_collection_residential_non_hh_consumer,
                            SUM(coalesce(coll.commercial_non_hh_consumer,0)) as total_collection_commercial_non_hh_consumer,
                            SUM(coalesce(coll.institutional_non_hh_consumer,0)) as total_collection_institutional_non_hh_consumer,
                            SUM(coalesce(coll.other_non_hh_consumer,0)) as total_collection_other_non_hh_consumer,

                            SUM(coalesce(coll.hh_consumer,0)) as total_collection_hh_consumer,
                            SUM(coalesce(coll.residential_hh_consumer,0)) as total_collection_residential_hh_consumer,
                            SUM(coalesce(coll.commercial_hh_consumer,0)) as total_collection_commercial_hh_consumer,
                            SUM(coalesce(coll.institutional_hh_consumer,0)) as total_collection_institutional_hh_consumer,
                            SUM(coalesce(coll.other_hh_consumer,0)) as total_collection_other_hh_consumer,
                        --===========END CONSUMERS=============
                        --========OUTSTANDING DEMAND===================
                            SUM((coalesce(demand.arrear_demand,0)) - (coalesce(prev_coll.prev_coll_amt,0))) as out_standing_degining,

                            SUM((coalesce(demand.non_hh_arrear_demand,0)) - (coalesce(prev_coll.non_hh_prev_coll_amt,0))) as non_hh_out_standing_degining,
                            SUM((coalesce(demand.residential_non_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_non_hh_prev_coll_amt,0))) as residential_non_hh_out_standing_degining,
                            SUM((coalesce(demand.commercial_non_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_non_hh_prev_coll_amt,0))) as commercial_non_hh_out_standing_degining,
                            SUM((coalesce(demand.institutional_non_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_non_hh_prev_coll_amt,0))) as institutional_non_hh_out_standing_degining,
                            SUM((coalesce(demand.other_non_hh_arrear_demand,0)) - (coalesce(prev_coll.other_non_hh_prev_coll_amt,0))) as other_non_hh_out_standing_degining,

                            SUM((coalesce(demand.hh_arrear_demand,0)) - (coalesce(prev_coll.hh_prev_coll_amt,0))) as hh_out_standing_degining,
                            SUM((coalesce(demand.residential_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_hh_prev_coll_amt,0))) as residential__hh_out_standing_degining,
                            SUM((coalesce(demand.commercial_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_hh_prev_coll_amt,0))) as commercial_hh_out_standing_degining,
                            SUM((coalesce(demand.institutional_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_hh_prev_coll_amt,0))) as institutional_hh_out_standing_degining,
                            SUM((coalesce(demand.other_hh_arrear_demand,0)) - (coalesce(prev_coll.other_hh_prev_coll_amt,0))) as other_hh_out_standing_degining,

                        --========END OUTSTANDING DEMAND================
                        --========CURENT DEMAND=========================
                            SUM(coalesce(demand.current_demand,0)) as current_demand,

                            SUM(coalesce(demand.non_hh_current_demand,0)) as non_hh_current_demand,
                            SUM(coalesce(demand.residential_non_hh_current_demand,0)) as residential_non_hh_current_demand,
                            SUM(coalesce(demand.commercial_non_hh_current_demand,0)) as commercial_non_hh_current_demand,
                            SUM(coalesce(demand.institutional_non_hh_current_demand,0)) as institutional_non_hh_current_demand,
                            SUM(coalesce(demand.other_non_hh_current_demand,0)) as other_non_hh_current_demand,

                            SUM(coalesce(demand.hh_current_demand,0)) as hh_current_demand,
                            SUM(coalesce(demand.residential_hh_current_demand,0)) as residential_hh_current_demand,
                            SUM(coalesce(demand.commercial_hh_current_demand,0)) as commercial_hh_current_demand,
                            SUM(coalesce(demand.institutional_hh_current_demand,0)) as institutional_hh_current_demand,
                            SUM(coalesce(demand.other_hh_current_demand,0)) as other_hh_current_demand,
                        --========END CURENT DEMAND=========================
                        --========TOTAL DEMAND==============================
                            (SUM((coalesce(demand.arrear_demand,0)) - (coalesce(prev_coll.prev_coll_amt,0))) + SUM(coalesce(demand.current_demand,0))) as total_demand,

                            (SUM((coalesce(demand.non_hh_arrear_demand,0)) - (coalesce(prev_coll.non_hh_prev_coll_amt,0))) + SUM(coalesce(demand.non_hh_current_demand,0))) as tota_non_hh_demand,
                            (SUM((coalesce(demand.residential_non_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_non_hh_prev_coll_amt,0))) + SUM(coalesce(demand.residential_non_hh_current_demand,0))) as total_residential_non_hh_demand,
                            (SUM((coalesce(demand.commercial_non_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_non_hh_prev_coll_amt,0))) + SUM(coalesce(demand.commercial_non_hh_current_demand,0))) as total_commercial_non_hh_demand,
                            (SUM((coalesce(demand.institutional_non_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_non_hh_prev_coll_amt,0))) + SUM(coalesce(demand.institutional_non_hh_current_demand,0))) as total_institutional_non_hh_demand,
                            (SUM((coalesce(demand.other_non_hh_arrear_demand,0)) - (coalesce(prev_coll.other_non_hh_prev_coll_amt,0))) + SUM(coalesce(demand.other_non_hh_current_demand,0))) as total_other_non_hh_demand,

                            (SUM((coalesce(demand.hh_arrear_demand,0)) - (coalesce(prev_coll.hh_prev_coll_amt,0))) + SUM(coalesce(demand.hh_current_demand,0))) as total_hh_demand,
                            (SUM((coalesce(demand.residential_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_hh_prev_coll_amt,0))) + SUM(coalesce(demand.residential_hh_current_demand,0))) as total_residential_hh_demand,
                            (SUM((coalesce(demand.commercial_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_hh_prev_coll_amt,0))) + SUM(coalesce(demand.commercial_hh_current_demand,0))) as total_commercial_hh_demand,
                            (SUM((coalesce(demand.institutional_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_hh_prev_coll_amt,0))) + SUM(coalesce(demand.institutional_hh_current_demand,0))) as total_institutional_hh_demand,
                            (SUM((coalesce(demand.other_hh_arrear_demand,0)) - (coalesce(prev_coll.other_hh_prev_coll_amt,0))) + SUM(coalesce(demand.other_hh_current_demand,0))) as other_hh_demand,
                        --========END TOTAL DEMAND==========================

                        --SUM(coalesce(prev_coll.prev_coll_amt,0)) as prev_coll_amt,
                        --=======AREAR COLLECTION===========================
                            SUM(coalesce(coll.arrear_collection,0)) as arrear_collection,

                            SUM(coalesce(coll.non_hh_arrear_collection,0)) as non_hh_arrear_collection,
                            SUM(coalesce(coll.residential_non_hh_arrear_collection,0)) as residential_non_hh_arrear_collection,
                            SUM(coalesce(coll.commercial_non_hh_arrear_collection,0)) as commercial_non_hh_arrear_collection,
                            SUM(coalesce(coll.institutional_non_hh_arrear_collection,0)) as institutional_non_hh_arrear_collection,
                            SUM(coalesce(coll.other_non_hh_arrear_collection,0)) as other_non_hh_arrear_collection,

                            SUM(coalesce(coll.hh_arrear_collection,0)) as hh_arrear_collection,
                            SUM(coalesce(coll.residential_hh_arrear_collection,0)) as residential_hh_arrear_collection,
                            SUM(coalesce(coll.commercial_hh_arrear_collection,0)) as commercial_hh_arrear_collection,
                            SUM(coalesce(coll.institutional_hh_arrear_collection,0)) as institutional_hh_arrear_collection,
                            SUM(coalesce(coll.other_hh_arrear_collection,0)) as other_hh_arrear_collection,
                        --=======END AREAR COLLECTION===========================
                        --=======CURENT COLLECTION==============================
                            SUM(coalesce(coll.curr_collection,0)) as curent_collection,

                            SUM(coalesce(coll.non_hh_curent_collection,0)) as non_hh_curent_collection,
                            SUM(coalesce(coll.residential_non_hh_curent_collection,0)) as residential_non_hh_curent_collection,
                            SUM(coalesce(coll.commercial_non_hh_curent_collection,0)) as commercial_non_hh_curent_collection,
                            SUM(coalesce(coll.institutional_non_hh_curent_collection,0)) as institutional_non_hh_curent_collection,
                            SUM(coalesce(coll.other_non_hh_curent_collection,0)) as other_non_hh_curent_collection,

                            SUM(coalesce(coll.hh_curent_collection,0)) as hh_curent_collection,
                            SUM(coalesce(coll.residential_hh_curent_collection,0)) as residential_hh_curent_collection,
                            SUM(coalesce(coll.commercial_hh_curent_collection,0)) as commercial_hh_curent_collection,
                            SUM(coalesce(coll.institutional_hh_curent_collection,0)) as institutional_hh_curent_collection,
                            SUM(coalesce(coll.other_hh_curent_collection,0)) as other_hh_curent_collection,
                        --=======END CURENT COLLECTION==========================
                        --=======TOTAL COLLECTION===============================
                            (SUM(coalesce(coll.arrear_collection,0)) + SUM(coalesce(coll.curr_collection,0))) as total_collection,

                            (SUM(coalesce(coll.non_hh_arrear_collection,0)) + SUM(coalesce(coll.non_hh_curent_collection,0))) as total_non_hh_collection,
                            (SUM(coalesce(coll.residential_non_hh_arrear_collection,0)) +  SUM(coalesce(coll.residential_non_hh_curent_collection,0))) as total_residential_non_hh_collection,
                            (SUM(coalesce(coll.commercial_non_hh_arrear_collection,0)) + SUM(coalesce(coll.commercial_non_hh_curent_collection,0))) as total_commercial_non_hh_collection,
                            (SUM(coalesce(coll.institutional_non_hh_arrear_collection,0)) + SUM(coalesce(coll.institutional_non_hh_curent_collection,0))) as total_institutional_non_hh_collection,
                            (SUM(coalesce(coll.other_non_hh_arrear_collection,0)) + SUM(coalesce(coll.other_non_hh_curent_collection,0))) as total_other_non_hh_collection,

                            (SUM(coalesce(coll.hh_arrear_collection,0)) + SUM(coalesce(coll.hh_curent_collection,0))) as total_hh_collection,
                            (SUM(coalesce(coll.residential_hh_arrear_collection,0)) + SUM(coalesce(coll.residential_hh_curent_collection,0))) as total_residential_hh_collection,
                            (SUM(coalesce(coll.commercial_hh_arrear_collection,0)) + SUM(coalesce(coll.commercial_hh_curent_collection,0))) as total_commercial_hh_collection,
                            (SUM(coalesce(coll.institutional_hh_arrear_collection,0)) + SUM(coalesce(coll.institutional_hh_curent_collection,0))) as total_institutional_hh_collection,
                            (SUM(coalesce(coll.other_hh_arrear_collection,0)) + SUM(coalesce(coll.other_hh_curent_collection,0))) as total_other_hh_collection,
                        --=======END TOTAL COLLECTION===========================
                        --=======AREA OUT STANDING NEXT==============================
                            (SUM((coalesce(demand.arrear_demand,0)) - (coalesce(prev_coll.prev_coll_amt,0))) - SUM(coalesce(coll.arrear_collection,0))) as arear_out_standing_next,

                            (SUM((coalesce(demand.non_hh_arrear_demand,0)) - (coalesce(prev_coll.non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.non_hh_arrear_collection,0))) as arear_non_hh_out_standing_next,
                            (SUM((coalesce(demand.residential_non_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.residential_non_hh_arrear_collection,0))) as arear_residential_non_hh_out_standing_next,
                            (SUM((coalesce(demand.commercial_non_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.commercial_non_hh_arrear_collection,0))) as arear_commercial_non_hh_out_standing_next,
                            (SUM((coalesce(demand.institutional_non_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.institutional_non_hh_arrear_collection,0))) as arear_institutional_non_hh_out_standing_next,
                            (SUM((coalesce(demand.other_non_hh_arrear_demand,0)) - (coalesce(prev_coll.other_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.other_non_hh_arrear_collection,0))) as arear_other_non_hh_out_standing_next,

                            (SUM((coalesce(demand.hh_arrear_demand,0)) - (coalesce(prev_coll.hh_prev_coll_amt,0))) - SUM(coalesce(coll.hh_arrear_collection,0))) as arear_hh_out_standing_next,
                            (SUM((coalesce(demand.residential_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_hh_prev_coll_amt,0))) - SUM(coalesce(coll.residential_hh_arrear_collection,0))) as arear_residential__hh_out_standing_next,
                            (SUM((coalesce(demand.commercial_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_hh_prev_coll_amt,0))) - SUM(coalesce(coll.commercial_hh_arrear_collection,0)))as arear_commercial_hh_out_standing_next,
                            (SUM((coalesce(demand.institutional_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_hh_prev_coll_amt,0))) - SUM(coalesce(coll.institutional_hh_arrear_collection,0))) as arear_institutional_hh_out_standing_next,
                            (SUM((coalesce(demand.other_hh_arrear_demand,0)) - (coalesce(prev_coll.other_hh_prev_coll_amt,0))) - SUM(coalesce(coll.other_hh_arrear_collection,0)))as arear_other_hh_out_standing_next,

                        --=======END AREA OUT STANDING NEXT==========================
                        --=======CURENT OUT STANDING NEXT============================
                            (SUM(coalesce(demand.current_demand,0)) - SUM(coalesce(coll.curr_collection,0))) as current_demand_out_standing_next,

                            (SUM(coalesce(demand.non_hh_current_demand,0)) -SUM(coalesce(coll.non_hh_curent_collection,0))) as non_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.residential_non_hh_current_demand,0)) - SUM(coalesce(coll.residential_non_hh_curent_collection,0))) as residential_non_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.commercial_non_hh_current_demand,0)) - SUM(coalesce(coll.commercial_non_hh_curent_collection,0))) as commercial_non_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.institutional_non_hh_current_demand,0)) - SUM(coalesce(coll.institutional_non_hh_curent_collection,0))) as institutional_non_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.other_non_hh_current_demand,0)) - SUM(coalesce(coll.other_non_hh_curent_collection,0))) as other_non_hh_current_demand_out_standing_next,

                            (SUM(coalesce(demand.hh_current_demand,0)) - SUM(coalesce(coll.hh_curent_collection,0))) as hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.residential_hh_current_demand,0)) - SUM(coalesce(coll.residential_hh_curent_collection,0))) as residential_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.commercial_hh_current_demand,0)) - SUM(coalesce(coll.commercial_hh_curent_collection,0))) as commercial_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.institutional_hh_current_demand,0)) - SUM(coalesce(coll.institutional_hh_curent_collection,0))) as institutional_hh_current_demand_out_standing_next,
                            (SUM(coalesce(demand.other_hh_current_demand,0)) - SUM(coalesce(coll.other_hh_curent_collection,0))) as other_hh_current_demand_out_standing_next,
                        --=======END CURENT OUT STANDING NEXT========================
                        --=======TOTAL OUT STANDING NEXT=============================
                            ((SUM((coalesce(demand.arrear_demand,0)) - (coalesce(prev_coll.prev_coll_amt,0))) - SUM(coalesce(coll.arrear_collection,0))) + (SUM(coalesce(demand.current_demand,0)) - SUM(coalesce(coll.curr_collection,0)))) as arear_out_standing_next,

                            ((SUM((coalesce(demand.non_hh_arrear_demand,0)) - (coalesce(prev_coll.non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.non_hh_arrear_collection,0))) + (SUM(coalesce(demand.non_hh_current_demand,0)) -SUM(coalesce(coll.non_hh_curent_collection,0)))) as arear_non_hh_out_standing_next,
                            ((SUM((coalesce(demand.residential_non_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.residential_non_hh_arrear_collection,0))) + (SUM(coalesce(demand.residential_non_hh_current_demand,0)) - SUM(coalesce(coll.residential_non_hh_curent_collection,0)))) as arear_residential_non_hh_out_standing_next,
                            ((SUM((coalesce(demand.commercial_non_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.commercial_non_hh_arrear_collection,0))) + (SUM(coalesce(demand.commercial_non_hh_current_demand,0)) - SUM(coalesce(coll.commercial_non_hh_curent_collection,0)))) as arear_commercial_non_hh_out_standing_next,
                            ((SUM((coalesce(demand.institutional_non_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.institutional_non_hh_arrear_collection,0))) + (SUM(coalesce(demand.institutional_non_hh_current_demand,0)) - SUM(coalesce(coll.institutional_non_hh_curent_collection,0)))) as arear_institutional_non_hh_out_standing_next,
                            ((SUM((coalesce(demand.other_non_hh_arrear_demand,0)) - (coalesce(prev_coll.other_non_hh_prev_coll_amt,0))) - SUM(coalesce(coll.other_non_hh_arrear_collection,0))) + (SUM(coalesce(demand.other_non_hh_current_demand,0)) - SUM(coalesce(coll.other_non_hh_curent_collection,0)))) as arear_other_non_hh_out_standing_next,

                            ((SUM((coalesce(demand.hh_arrear_demand,0)) - (coalesce(prev_coll.hh_prev_coll_amt,0))) - SUM(coalesce(coll.hh_arrear_collection,0))) + (SUM(coalesce(demand.hh_current_demand,0)) - SUM(coalesce(coll.hh_curent_collection,0)))) as arear_hh_out_standing_next,
                            ((SUM((coalesce(demand.residential_hh_arrear_demand,0)) - (coalesce(prev_coll.residential_hh_prev_coll_amt,0))) - SUM(coalesce(coll.residential_hh_arrear_collection,0))) + (SUM(coalesce(demand.residential_hh_current_demand,0)) - SUM(coalesce(coll.residential_hh_curent_collection,0)))) as arear_residential__hh_out_standing_next,
                            ((SUM((coalesce(demand.commercial_hh_arrear_demand,0)) - (coalesce(prev_coll.commercial_hh_prev_coll_amt,0))) - SUM(coalesce(coll.commercial_hh_arrear_collection,0))) + (SUM(coalesce(demand.commercial_hh_current_demand,0)) - SUM(coalesce(coll.commercial_hh_curent_collection,0)))) as arear_commercial_hh_out_standing_next,
                            ((SUM((coalesce(demand.institutional_hh_arrear_demand,0)) - (coalesce(prev_coll.institutional_hh_prev_coll_amt,0))) - SUM(coalesce(coll.institutional_hh_arrear_collection,0))) + (SUM(coalesce(demand.institutional_hh_current_demand,0)) - SUM(coalesce(coll.institutional_hh_curent_collection,0)))) as arear_institutional_hh_out_standing_next,
                            ((SUM((coalesce(demand.other_hh_arrear_demand,0)) - (coalesce(prev_coll.other_hh_prev_coll_amt,0))) - SUM(coalesce(coll.other_hh_arrear_collection,0))) + (SUM(coalesce(demand.other_hh_current_demand,0)) - SUM(coalesce(coll.other_hh_curent_collection,0))))as arear_other_hh_out_standing_next,

                        --=======TOTAL OUT STANDING NEXT=============================

                            SUM(coalesce(advance_amount,0)) as advance_amount

                    FROM view_ward_mstr
                    LEFT JOIN ( 
                        SELECT tbl_consumer.ward_mstr_id, 
                            count(distinct(tbl_consumer.id)) as total_consumer,
                            count(distinct
                                        ( CASE WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date 
                                            THEN  tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                ) as total_demand_consumer,
                            --==============NON HH==============
                                count(
                                    distinct(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date 
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS non_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date 
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_non_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_non_hh_consumer,

                                count(
                                    distinct(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_non_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date 
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_non_hh_consumer,

                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date 
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_non_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_non_hh_consumer,

                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_non_hh_demand_consumer,
                            --==============END NON HH==============
                            --==============HH==============
                                count( 
                                    distinct(
                                        CASE WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS hh_consumer,

                                count( 
                                    distinct(
                                        CASE WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS hh_demand_consumer,
                            
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_hh_consumer,

                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_hh_demand_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer_demand.generation_date <= '$curr_last_date'::date
                                                AND tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_hh_demand_consumer,
                            --==============END HH==============
                            --==============NON HH DEMAND==============
                                --=========NON HH ARREAR DEMAND======
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer_demand.amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_arrear_demand,
                                --=========END NON HH ARREAR DEMAND======
                                --==========NON HH CURRENT DEMAND=======
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer_demand.amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_current_demand,
                                ---========END NON HH CUNENT DEMAND======
                            --===========END NON HH DEMAND==============
                            --===========HH DEMAND======================
                                --=========HH ARREAR DEMAND======
                                    SUM( 
                                            CASE WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS hh_arrear_demand,
                                
                                    SUM(
                                            CASE WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_arrear_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_arrear_demand,
                                --=========END HH AREAR DEMAND===                            
                                --=========HH CURNT DEMAND===============
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                            THEN tbl_consumer_demand.amount
                                            ELSE NULL::numeric
                                        END) AS hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_current_demand,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_demand.amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_current_demand,
                                ---=======END HH CURENT DEMAND=============
                            --===========END HH DEMAND=================
                            sum(
                                CASE
                                    WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                                    ELSE NULL::numeric
                                END) AS arrear_demand,
                            sum(
                                CASE
                                    WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                                    ELSE NULL::numeric
                                END) AS current_demand
                        FROM tbl_consumer 
                        LEFT JOIN tbl_consumer_demand on tbl_consumer.id=tbl_consumer_demand.consumer_id 
                            and tbl_consumer_demand.status=1
                        WHERE tbl_consumer.status = 1
                            $where
                        GROUP BY tbl_consumer.ward_mstr_id

                    ) demand ON demand.ward_mstr_id = view_ward_mstr.id            

                    LEFT JOIN ( 
                        SELECT tbl_consumer.ward_mstr_id,
                            count(distinct(tbl_consumer.id)) as total_consumer,
                            --==============NON HH==============
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_non_hh_consumer,
                            --==============END NON HH==============
                            --==============HH==============
                                count( 
                                    distinct(
                                        CASE WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS hh_consumer,
                            
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_hh_consumer,
                            --==============END HH==============
                            --==============NON HH COLLECTION==============
                                --=========NON HH ARREAR COLLECTION======
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_arrear_collection,
                                --=========END NON HH ARREAR COLLECTION======
                                --==========NON HH CURRENT COLLECTION=======
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date 
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_curent_collection,
                                ---========END NON HH CUNENT COLLECTION======
                            --===========END NON HH COLLECTION==============
                            --===========HH COLLECTION======================
                                --=========HH ARREAR COLLECTION======
                                    SUM( 
                                            CASE WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS hh_arrear_collection,
                                
                                    SUM(
                                            CASE WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_arrear_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_arrear_collection,
                                --=========END HH AREAR COLLECTION===                            
                                --=========HH CURNT COLLECTION===============
                                    sum(
                                        CASE
                                            WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END) AS hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date 
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_curent_collection,
                                    SUM(
                                            CASE
                                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date
                                                    AND (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_curent_collection,
                                ---=======END HH CURENT COLLECTION=============
                            --===========END HH COLLECTION=================
                            sum(
                                CASE
                                    WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END) AS arrear_collection,
                            sum(
                                CASE
                                    WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END) AS curr_collection
                        FROM tbl_consumer_collection
                    
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                        join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                        join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id                    
                        where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                            AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                            AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                            $where
                        GROUP BY tbl_consumer.ward_mstr_id
                    ) coll ON coll.ward_mstr_id = view_ward_mstr.id
                                    
                    LEFT JOIN (
                        select tbl_consumer.ward_mstr_id,
                                sum(tbl_consumer_collection.amount) as prev_coll_amt,
                                count(distinct(tbl_consumer.id)) as total_consumer,
                            --==============NON HH==============
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_non_hh_consumer,
                            --==============END NON HH==============
                            --==============HH==============
                                count( 
                                    distinct(
                                        CASE WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS hh_consumer,
                            
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_hh_consumer,
                            --==============END HH==============
                            --==============NON HH PRIV COLLECTION==============
                                    sum(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_prev_coll_amt,
                            --=========END NON HH PRIV COLLECTION======
                            --=========HH PRIVE COLLECTION======
                                    SUM( 
                                            CASE WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS hh_prev_coll_amt,
                                
                                    SUM(
                                            CASE WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_prev_coll_amt,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN tbl_consumer_collection.amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_prev_coll_amt                            
                            --===========END HH COLLECTION=================
                        FROM tbl_consumer_collection 
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                        join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                        join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id                    
                        where transaction_date<='$last_date' 
                            AND tbl_transaction.transaction_type::text = 'Demand Collection'::text 
                            AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                            $where
                        group by tbl_consumer.ward_mstr_id
                    ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id

                    LEFT JOIN (
                        select tbl_consumer.ward_mstr_id,
                            sum(advance_amount) as advance_amount,
                            count(distinct(tbl_consumer.id)) as total_consumer,
                            --==============NON HH==============
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_non_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_non_hh_consumer,
                            --==============END NON HH==============
                            --==============HH==============
                                count( 
                                    distinct(
                                        CASE WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS hh_consumer,
                            
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 1
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS residential_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 2
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS commercial_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id = 4
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS institutional_hh_consumer,
                                count(
                                    distinct(
                                        CASE
                                            WHEN tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL 
                                                AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                            THEN tbl_consumer.id
                                            ELSE NULL::bigint
                                            END 
                                        )
                                    )AS other_hh_consumer,
                            --==============END HH==============
                            --==============NON HH PRIV COLLECTION==============
                                    sum(
                                        CASE
                                            WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL) 
                                            THEN advance_amount
                                            ELSE NULL::numeric
                                        END) AS non_hh_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN advance_amount
                                                ELSE 0
                                                END 
                                        )AS residential_non_hh_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN advance_amount
                                                ELSE 0
                                                END
                                        )AS commercial_non_hh_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN advance_amount
                                                ELSE 0
                                                END 
                                        )AS institutional_non_hh_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no = '' OR tbl_consumer.holding_no IS NULL)
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN advance_amount
                                                ELSE 0
                                                END
                                        )AS other_non_hh_advance_amount,
                            --=========END NON HH PRIV COLLECTION======
                            --=========HH PRIVE COLLECTION======
                                    SUM( 
                                            CASE WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                THEN advance_amount
                                                ELSE 0
                                                END
                                        )AS hh_consumer_advance_amount,
                                
                                    SUM(
                                            CASE WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 1
                                                THEN advance_amount
                                                ELSE 0
                                                END
                                        )AS residential_hh_consumer_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 2
                                                THEN advance_amount
                                                ELSE 0
                                                END
                                        )AS commercial_hh_consumer_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id = 4
                                                THEN advance_amount
                                                ELSE 0
                                                END 
                                        )AS institutional_hh_consumer_advance_amount,
                                    SUM(
                                            CASE
                                                WHEN (tbl_consumer.holding_no <> '' AND tbl_consumer.holding_no IS NOT NULL) 
                                                    AND tbl_consumer.property_type_id NOT IN(1,2,4)
                                                THEN advance_amount
                                                ELSE 0
                                                END 
                                        )AS other_hh_consumer_advance_amount                            
                            --===========END HH COLLECTION================= 
                        from tbl_consumer_advance_dtls
                        join tbl_transaction on tbl_transaction.id=tbl_consumer_advance_dtls.transaction_id
                        join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
                        join view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_mstr_id                    
                        where tbl_transaction.status in(1,2) and advance_from!='R' and active_status=1 
                            and advance_from!='N' and transaction_date>'$last_date' and 
                            transaction_date<='$curr_last_date' and transaction_type='Demand Collection'
                            and tbl_consumer.status=1 
                            $where
                        group by tbl_consumer.ward_mstr_id

                    ) as advance on advance.ward_mstr_id=view_ward_mstr.id
                    where view_ward_mstr.ulb_mstr_id=".$this->ulb_id."
                    ";
            // print_var($sql);die;            
            
            $run=$this->db->query($sql);
            $data['ward_wise_dcb'][$fyYear]=$run->getFirstRow("array");
            $data['kyes'][]=$fyYear;
                
            
        }
        
        return view('water/report/Details_for_water_chages', $data);
    }

    public function getAppSummary(){
        $data =$inputs = arrFilterSanitizeString($this->request->getVar());
        $where = "";
        $fromDate = $uptoDate = date('Y-m-d');
        if(isset($inputs["from_date"])){
            $fromDate=$inputs['from_date'];
        }
        if(isset($inputs["upto_date"])){
            $uptoDate=$inputs['upto_date'];
        }
        if($this->request->getMethod()=='post'){
            $applyApplicationSql = "SELECT COUNT(id)
                                    FROM tbl_apply_water_connection
                                    WHERE 1=1 " 
                                    .($fromDate && $uptoDate ? " AND apply_date BETWEEN '$fromDate' AND '$uptoDate' ":"");

            $appApprovedSql = "SELECT count(id)
                                FROM tbl_apply_water_connection
                                JOIN (
                                    SELECT max(tbl_level_pending.id)AS last_id, tbl_apply_water_connection.id AS app_id
                                    FROM tbl_apply_water_connection
                                    JOIN tbl_level_pending ON tbl_level_pending.apply_connection_id = tbl_apply_water_connection.id
                                    WHERE tbl_apply_water_connection.status !=0
                                        AND tbl_level_pending.receiver_user_type_id =16
                                        AND tbl_level_pending.verification_status =1 "                                        
                                    .($fromDate && $uptoDate ? " AND tbl_level_pending.forward_date BETWEEN '$fromDate' AND '$uptoDate' ":"")."
                                    group by tbl_apply_water_connection.id
                                )level_pending ON level_pending.app_id = tbl_apply_water_connection.id " ;

            $appRejectedSql="select count(id)
                            from tbl_apply_water_connection
                            join (
                                SELECT max(tbl_level_pending.id)as last_id, tbl_apply_water_connection.id as app_id
                                FROM tbl_apply_water_connection
                                JOIN tbl_level_pending on tbl_level_pending.apply_connection_id = tbl_apply_water_connection.id
                                WHERE tbl_apply_water_connection.status !=0
                                    AND tbl_level_pending.verification_status =4"
                                    .($fromDate && $uptoDate ? " AND tbl_level_pending.forward_date BETWEEN '$fromDate' AND '$uptoDate' ":"")."
                                group by tbl_apply_water_connection.id
                            )level_pending ON level_pending.app_id = tbl_apply_water_connection.id " ;

            $paymentModeSql = "SELECT DISTINCT (UPPER(payment_mode))payment_mode FROM tbl_transaction";

            $collectionSql = "SELECT SUM(paid_amount)AS paid_amount,UPPER(payment_mode)payment_mode
                              FROM tbl_transaction
                              WHERE  status IN (1,2)"
                                    .($fromDate && $uptoDate ? " AND transaction_date BETWEEN '$fromDate' AND '$uptoDate' ":"")."
                              GROUP BY UPPER(payment_mode)";
            
            $applyApplication = $this->db->query($applyApplicationSql)->getFirstRow();
            $appApproved = $this->db->query($appApprovedSql)->getFirstRow();
            $appRejected = $this->db->query($appRejectedSql)->getFirstRow();            
            $paymentMode = $this->db->query($paymentModeSql)->getResultArray();
            $collection = $this->db->query($collectionSql)->getResultArray();

            $data["applyApplication"] =$applyApplication->count??0;
            $data["appApproved"] =$appApproved->count??0;
            $data["appRejected"] =$appRejected->count??0;
            $data["paymentMode"] =array_map(function($val){
                return $val["payment_mode"];
            },$paymentMode);
            $data["collection"] =$collection;
            foreach($paymentMode as $key=>$val){
                $payment_mode = $val["payment_mode"];
                $amount = array_map(function($col)use($payment_mode){
                    $amount =  $col["payment_mode"]==$payment_mode ? $col["paid_amount"]:0;  
                    return $amount;
                },$collection);
                $data["collectionSummary"][$key]=[
                    "payment_mode"=>$val["payment_mode"],
                    "paid_amount"=>array_sum($amount),
                ];
            }
            return $data;            
        }
    }
    
    
}
<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_pagination;
use App\Models\model_ward_mstr;

use App\Models\WaterReportModel;
use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterConsumerSummaryReport extends AlphaController
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
        
        $this->water_report_model=new WaterReportModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);

    }
    
    public function index()
    {   
    	
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        // $data['view']=$view;

        $where=" where 1=1";
        if($this->request->getMethod()=='post')
        {   


            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];
            
            
            if($data['ward_id']!="")
            {
                $where=" where ward_id=".$data['ward_id']." and level_date between '".$data['date_from']."' and '".$data['date_upto']."'";
                
            }
            else
            {
                $where=" where level_date between '".$data['date_from']."' and '".$data['date_upto']."'";
            }

           
        }
        else
        {    
             $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
             
        }
        
        $data['consumer_summary']=$this->water_report_model->consumerSummary($where);
        $sql = "WITH supper_Dray AS ( 
                    SELECT DISTINCT(tbl_consumer.id) AS consumer_id, 
                        tbl_transaction.id AS transaction_id 
                    FROM tbl_consumer 
                    LEFT JOIN tbl_transaction ON tbl_transaction.related_id = tbl_consumer.id 
                        AND tbl_transaction.status IN (1,2) AND tbl_transaction.transaction_type = 'Demand Collection' 
                    WHERE tbl_consumer.status=1 AND tbl_transaction.id isnull 
                ),
                meter AS (
                SELECT tbl_consumer.id,
                    meter.connection_type
                FROM (tbl_consumer
                    LEFT JOIN ( SELECT tbl_meter_status.connection_type,
                            tbl_meter_status.consumer_id
                        FROM tbl_meter_status
                        WHERE (tbl_meter_status.id IN ( SELECT max(tbl_meter_status_1.id) AS max
                                FROM (tbl_meter_status tbl_meter_status_1
                                    JOIN tbl_consumer tbl_consumer_1 ON ((tbl_consumer_1.id = tbl_meter_status_1.consumer_id)))
                                WHERE (tbl_meter_status_1.status = 1)
                                GROUP BY tbl_consumer_1.id))) meter ON ((meter.consumer_id = tbl_consumer.id)))
                WHERE (tbl_consumer.status = 1)
                ),
                metered_and_non_metered_consumer AS (
                    SELECT tbl_consumer.id,
                            CASE
                                WHEN ((meter.connection_type = 3) OR (meter.connection_type IS NULL)) THEN count(tbl_consumer.id)
                                ELSE NULL::bigint
                            END AS non_metered,
                            CASE
                                WHEN ((meter.connection_type = 1) OR (meter.connection_type = 2)) THEN count(tbl_consumer.id)
                                ELSE NULL::bigint
                            END AS metered
                    FROM (tbl_consumer
                        JOIN meter ON ((meter.id = tbl_consumer.id)))
                    WHERE (tbl_consumer.status = 1)
                    GROUP BY tbl_consumer.id, meter.connection_type
                ),
                consumer_type AS(
                    SELECT  tbl_consumer.id,
                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                ELSE  'Supper Dry Consumer'
                            END AS consumer_Type
                    FROM tbl_consumer
                    LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id 
                    where 1=1 AND tbl_consumer.status = 1
                )
                SELECT  DISTINCT(consumer_Type) AS consumer_Type, count(consumer_type.id)  AS count,
                    count(non_metered) as non_metered,count(metered) as metered
                FROM consumer_type
                LEFT JOIN metered_and_non_metered_consumer on metered_and_non_metered_consumer.id = consumer_type.id 
                GROUP BY consumer_Type
                ORDER BY consumer_Type
        ";
        $data['consumer_type'] = $this->water_report_model->row_sql($sql);    
      
        return view('water/report/water_consumer_summary',$data);
        
        
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
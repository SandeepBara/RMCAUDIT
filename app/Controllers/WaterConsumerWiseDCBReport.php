<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use App\Models\model_datatable;
use App\Models\model_view_ward_permission;
use Exception;


class WaterConsumerWiseDCBReport extends AlphaController
{   
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    protected $water_report_model;
    
    public function __construct()
    {   
        parent::__construct();
        helper(['db_helper','from_helper','url','utility_helper']);
        ini_set('memory_limit', '-1');
        helper(['php_office_helper']);
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['db_helper','form','form_helper','url_helper','utility_helper','qr_code_generator_helper']);
        helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_report_model=new WaterReportModel($this->db);
        $this->model_datatable = new model_datatable($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);

    }
    
    public function __destruct()
    {
        if($this->db)   $this->db->close();
        if($this->dbSystem)   $this->dbSystem->close();
    }
    public function index()
    {
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        $whereClause="where 1=1 ";
        $join=NULL;
        $data['consumer_wise_dcb']=[];
        if($this->request->getMethod()=='post')
        {
            $inputs=$this->request->getVar();
            //print_var($inputs);
            if($inputs["ward_id"]!="")
            {
                $whereClause .= " and ward_mstr_id=".$inputs["ward_id"];
            }
            if($inputs["property_type"]!="")
            {
                if($inputs["property_type"] == "Residential")
                {
                    $join=" join view_residential_consumer on view_consumer_wise_dcb.id=view_residential_consumer.id";
                    //$prop="'Residential', 'Apartment/Multi Stored Unit'";
                }
                if($inputs["property_type"] == "Non-Residential")
                {
                    $join=" join view_non_residential_consumer on view_non_residential_consumer.id=view_consumer_wise_dcb.id";
                    //$prop="'Commercial', 'Industrial', 'SSI Unit', 'Trust & NGO', 'Institutional'";
                }
                if($inputs["property_type"] == "Government")
                {
                    $join=" join view_gov_consumer on view_gov_consumer.id=view_consumer_wise_dcb.id ";
                    //$prop="'Goverment & PSU'";
                }
                
                //$whereClause .= " and property_type in ($prop)";
            }
            if($inputs["category"]!="")
            {
                $whereClause .= " and category='".$inputs["category"]."'";
            }
            if($inputs["connection_type"]!="")
            {
                if($inputs["connection_type"] == "Meter")
                {
                    $conn="1";
                }
                if($inputs["connection_type"] == "Non-Meter")
                {
                    $conn="2,3";
                }
                $whereClause .= " and connection_type in ($conn)";
            }
            $data['consumer_wise_dcb']=$this->water_report_model->consumerWiseDCB($whereClause, $join);
        }
        return view('water/report/consumer_wise_dcb', $data);
    }

    public function WaterConsumerWiseDCBReportExportExcel()
    {
        try
        {
            $whereQuery = " where consumer_no is not null and applicant_name is not null ";
            
            
            $selectStatement = "consumer_no, applicant_name, property_type, outstanding_at_begin, current_demand, 
            (outstanding_at_begin+current_demand) as total_demand, arrear_coll, curr_coll, old_due, old_due, curr_due, outstanding ";

            $sql = "SELECT  ".$selectStatement." from view_consumer_wise_dcb";

            $fetchSql = $sql.$whereQuery;
            $records = $this->water_report_model->getRecords($fetchSql);

            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Consumer No');
                            $activeSheet->setCellValue('B1', 'Consumer Name');
                            $activeSheet->setCellValue('C1', 'Property Type');
                            $activeSheet->setCellValue('D1', 'Outstanding at the begining');
                            $activeSheet->setCellValue('E1', 'Current Demand');
                            $activeSheet->setCellValue('F1', 'Total Demand');
                            $activeSheet->setCellValue('G1', 'Old Due Collection');
                            $activeSheet->setCellValue('H1', 'Current Collection');
                            $activeSheet->setCellValue('I1', 'Old Due');
                            $activeSheet->setCellValue('J1', 'Current Due');
                            $activeSheet->setCellValue('K1', 'Outstanding Total due');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Extention $e){
            print_r($e);
        }
    }
    

    
    public function WaterConsumerWiseDCBReportAjax()
    {
        if($this->request->getMethod()=='post')
        {
            try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" )
                    $columnName='consumer_no';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
               
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $whereQueryWithSearch = " where consumer_no is not null and applicant_name is not null ";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (consumer_no ILIKE '%".$searchValue."%'
                                    OR applicant_name ILIKE '%".$searchValue."%')";
                }
               
               
                $selectStatement = "consumer_no, applicant_name, property_type, outstanding_at_begin, current_demand, 
                (outstanding_at_begin+current_demand) as total_demand, arrear_coll, curr_coll, old_due, old_due, curr_due, outstanding ";

                $sql = "SELECT  ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no, ".$selectStatement." from view_consumer_wise_dcb";
                
                ## Total number of records without filtering
                $totalRecords = $this->water_report_model->getTotalRecords($sql);
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->water_report_model->getTotalRecords($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->water_report_model->getRecords($fetchSql);
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
                echo json_encode($response);
            }catch(Extention $e){

            }
        }
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
            }catch(Extention $e){

            }
        } else {
            echo "GET";
        }
    }
    
    public function index_copy()
    {
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        
        $whereClause="where 1=1 AND tbl_consumer.status = 1 ";
        $join=NULL;
        $data['consumer_wise_dcb']=[];            
        $fy = explode('-',getFY());        
        $from =$fy[0].'-03-31'; 
        $to = $fy[0].'-04-01';
        $sql = " select * from tbl_property_type_mstr where status = 1 ";
        $data['property_list'] = $this->water_report_model->row_sql($sql);
        
        return view('water/report/consumer_wise_dcb_copy', $data);
    }
    
    public function WaterConsumerWiseDCBReportAjax2()
    {
        try
        {
            $fy = explode('-',getFY());         
            if($this->request->getVar('fyear'))
            {
                $fy = explode('-',$this->request->getVar('fyear'));
            }      
            $from =$fy[0].'-03-31'; 
            $to = $fy[1].'-03-31';
            //PRINT_var("from = ".$from);PRINT_var("to = ".$to);die;
            $whereClause="where 1=1 AND tbl_consumer.status = 1 ";

            $inputs=$this->request->getVar();
            $start = sanitizeString($this->request->getVar('start'));
                
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName=="consumer_no" )
                $columnName='tbl_consumer.consumer_no';
            else if ($columnName=="applicant_name")
                $columnName = 'owner.applicant_name';
            else if ($columnName=="property_type")
                $columnName = 'tbl_consumer.property_type_id';            
            else
                $columnName = 'tbl_consumer.id ';

            //$columnName = "tbl_transaction.tran_date";
            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
            $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
            
            // Date filterarchValue = sanitizeString($this->request->getVar('search')['value']);
            $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
            $limit = " LIMIT ".($rowperpage==-1?"ALL":$rowperpage)." OFFSET ".$start;            
            // echo"$limit";die;
            if($inputs["ward_id"]!="")
            {
                $whereClause .= " AND tbl_consumer.ward_mstr_id=".$inputs["ward_id"];
            }
            if($inputs["property_type"]!="")
            {                
                $whereClause.=" AND tbl_consumer.property_type_id =".$inputs["property_type"]." " ;   
            }
            if($inputs["category"]!="")//"APL or BPL";
            {
                $whereClause .= " and tbl_consumer.category='".$inputs["category"]."'";
            }
            if($inputs["connection_type"]!="")
            {
                if($inputs["connection_type"] == "Meter")
                {
                    $conn="1,2";
                }
                if($inputs["connection_type"] == "Non-Meter")
                {
                    $conn="3";
                }
                $whereClause .= " and meter_type.connection_type in ($conn)";
            }

            $whereQueryWithSearch = "";
            if ($searchValue!='') 
            {
                $whereQueryWithSearch = " AND (tbl_consumer.consumer_no ILIKE '%".$searchValue."%'
                                OR owner.applicant_name ILIKE '%".$searchValue."%'
                                OR tbl_property_type_mstr.property_type ILIKE '%".$searchValue."%'
                                 )";
            }
            $base_url = base_url();
            
            $with = "with owner as (
                            SELECT tbl_consumer_details.consumer_id,
                                string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name
                            FROM tbl_consumer_details
                            join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                            where tbl_consumer.status =1 and tbl_consumer_details.status=1
                            GROUP BY tbl_consumer_details.consumer_id
                    ),
                    demand as ( 
                        SELECT tbl_consumer_demand.consumer_id,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_demand.amount       
                                ELSE NULL::numeric
                                END
                            ) AS arrear_demand,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                    AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                    THEN tbl_consumer_demand.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_demand
                        FROM tbl_consumer_demand
                        WHERE tbl_consumer_demand.status = 1
                        GROUP BY tbl_consumer_demand.consumer_id
                    ),
                    coll as ( 
                        SELECT tbl_consumer_collection.consumer_id,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS arrear_coll,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                    AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_coll
                        FROM tbl_consumer_collection
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                        WHERE tbl_transaction.transaction_date > '$from'::date 
                            AND tbl_transaction.transaction_date <= '$to'::date
                            AND tbl_transaction.status in(1,2)
                        GROUP BY tbl_consumer_collection.consumer_id
                    ),
                    prev_coll_amount as ( 
                            SELECT tbl_transaction.related_id,
                                sum(tbl_consumer_collection.amount) AS prev_coll
                            FROM tbl_consumer_collection
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                            WHERE tbl_transaction.transaction_date <= '$from'::date 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                AND tbl_transaction.status in(1,2)
                            GROUP BY tbl_transaction.related_id
                    ),
                    meter_type as ( 
                        SELECT tbl_meter_status.id,
                        tbl_meter_status.consumer_id,
                        tbl_meter_status.connection_type
                        FROM tbl_meter_status
                        WHERE (
                                tbl_meter_status.id IN ( 
                                                    SELECT max(tbl_meter_status_1.id) AS id
                                                    FROM tbl_meter_status tbl_meter_status_1
                                                    where status = 1
                                                    GROUP BY tbl_meter_status_1.consumer_id
                                                    ORDER BY (max(tbl_meter_status_1.id))
                                                    )
                            )                        
                    ),
                    all_advance as(
                        select tbl_consumer.id, sum(tbl_advance_mstr.amount) as amount 
                            from tbl_advance_mstr
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
                            join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
                            where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                                AND tbl_transaction.status in (1, 2) 
                                AND (tbl_transaction.transaction_date <= '$to'::date) 
                                AND tbl_advance_mstr.module='consumer' 
                            group by tbl_consumer.id
                    ),
                    all_adjustment as (
                        select tbl_consumer.id, sum(tbl_adjustment_mstr.amount) as amount 
                            from tbl_adjustment_mstr
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
                            join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
                            where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                                AND tbl_transaction.status in (1, 2) 
                                AND (tbl_transaction.transaction_date <= '$to'::date) 
                                AND tbl_adjustment_mstr.module='consumer' 
                            group by tbl_consumer.id
                    )
            ";                        
            $select = " SELECT ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                        tbl_consumer.id,
                        tbl_consumer.consumer_no,
                        tbl_property_type_mstr.property_type,
                        tbl_consumer.ward_mstr_id,
                        tbl_consumer.category,
                        case when meter_type.connection_type in(1,2) then 'Meter' else 'Fixed' end as connection_type,
                        owner.applicant_name,
                        
                        COALESCE(
                            COALESCE(demand.arrear_demand, 0::numeric) 
                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                        ) AS outstanding_at_begin,
                        
                        COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                        COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                        COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                        COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                        
                        (COALESCE(
                                COALESCE(demand.arrear_demand, 0::numeric) 
                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                            ) 
                            - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                            
                        (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                        
                        (
                            COALESCE(
                                COALESCE(demand.curr_demand, 0::numeric) 
                                + (
                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                ), 0::numeric
                            ) 
                            - COALESCE(
                                COALESCE(coll.curr_coll, 0::numeric) 
                                + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                            )
                        ) AS outstanding ,
                        ((coalesce(all_advance.amount,0))) as advance_amount
            ";

            $from_tbl = " FROM tbl_consumer
                    LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                    LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                    LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                    LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                    LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                    LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id  
                    LEFT JOIN  all_advance on all_advance.id = tbl_consumer.id
                    LEFT JOIN  all_adjustment on all_adjustment.id = tbl_consumer.id 
                                      
                    $whereClause
            ";
           
            $totalRecords = $this->model_datatable->getTotalRecords($from_tbl,false,$with);
            if ($totalRecords>0) 
            {
                
                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from_tbl.$whereQueryWithSearch,false,$with);
                
                ## Fetch records                
               $fetchSql = $with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit;
                
                $result = $this->model_datatable->getRecords($fetchSql,false);                
                
                $records = [];
                if ($result) 
                {
                    foreach ($result AS $key=>$tran_dtl) 
                    {
                        $records[] = [
                            's_no'=>$tran_dtl['s_no'],
                            'consumer_no'=>$tran_dtl['consumer_no'],
                            'applicant_name'=>$tran_dtl['applicant_name'],
                            'property_type'=>$tran_dtl['property_type'],
                            'connection_type'=> $tran_dtl["connection_type"] ,
                            'outstanding_at_begin'=>$tran_dtl['outstanding_at_begin'],
                            'current_demand'=>$tran_dtl['current_demand'],
                            'total'=>$tran_dtl['outstanding_at_begin']+$tran_dtl['current_demand'],
                            'arrear_coll'=>$tran_dtl['arrear_coll'],
                            'curr_coll'=>$tran_dtl['curr_coll'],                            
                            'advance_amount'=> $tran_dtl["advance_amount"] ,
                            'old_due'=>$tran_dtl['old_due'],
                            'curr_due'=>$tran_dtl['curr_due'],
                            'outstanding'=>$tran_dtl['outstanding'],
                            // 'link'=>$tran_dtl['link'],
                            
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

            );
            return json_encode($response); 
            
        }
        catch(Exception $e)
        {
            print_r($e);
        }

    }

    public function WaterConsumerWiseDCBReportExcel2($search_ward_mstr_id=null,$property_type=null,$category=null,$connection_type=null,$fyear = null)
    {   
        try
        {    
                $fy = explode('-',getFY()); 
                if($fyear)
                {
                    $fy = explode('-',$fyear);
                }       
                $from =$fy[0].'-03-31'; 
                $to = $fy[1].'-03-31';
                $whereQuery = " where 1=1 AND tbl_consumer.status = 1 ";
            
                if ($search_ward_mstr_id != 'ALL' && $search_ward_mstr_id !=null) 
                {
                    $whereQuery .= " AND  tbl_consumer.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if($property_type!=""  && $property_type != 'ALL')
                {                    
                    $whereQuery.=" AND tbl_consumer.property_type_id =".$property_type." "; 
                }
                if($category!="" && $category !='ALL')//"APL or BPL";
                {
                    $whereQuery .= " and tbl_consumer.category='".$category."'";
                }
                if($connection_type!="" && $connection_type != 'ALL')
                {
                    if($connection_type== "Meter")
                    {
                        $conn="1,2";
                    }
                    if($connection_type== "Non-Meter")
                    {
                        $conn="3";
                    }
                    $whereQuery .= " and meter_type.connection_type in ($conn)";
                }
                

                $selectStatement = "SELECT 
                                        tbl_consumer.id,
                                        tbl_consumer.consumer_no,
                                        tbl_property_type_mstr.property_type,
                                        tbl_consumer.ward_mstr_id,
                                        tbl_consumer.category,
                                        case when meter_type.connection_type in(1,2) then 'Meter' else 'Fixed' end as connection_type,
                                        owner.applicant_name,
                                        
                                        COALESCE(
                                            COALESCE(demand.arrear_demand, 0::numeric) 
                                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                                        ) AS outstanding_at_begin,
                                        
                                        COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                                        COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                                        COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                                        COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                                        
                                        (COALESCE(
                                                COALESCE(demand.arrear_demand, 0::numeric) 
                                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                                            ) 
                                            - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                                            
                                        (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                                        
                                        (
                                            COALESCE(
                                                COALESCE(demand.curr_demand, 0::numeric) 
                                                + (
                                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                                ), 0::numeric
                                            ) 
                                            - COALESCE(
                                                COALESCE(coll.curr_coll, 0::numeric) 
                                                + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                                            )
                                        ) AS outstanding ,
                                        ((coalesce(all_advance.amount,0))) as advance_amount
                            ";

                $with_query ="with owner as (
                                    SELECT tbl_consumer_details.consumer_id,
                                        string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name
                                    FROM tbl_consumer_details
                                    join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                                    where tbl_consumer.status =1 and tbl_consumer_details.status=1
                                    GROUP BY tbl_consumer_details.consumer_id
                            ),
                            demand as ( 
                                SELECT tbl_consumer_demand.consumer_id,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                            THEN tbl_consumer_demand.amount       
                                        ELSE NULL::numeric
                                        END
                                    ) AS arrear_demand,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                            AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                            THEN tbl_consumer_demand.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS curr_demand
                                FROM tbl_consumer_demand
                                WHERE tbl_consumer_demand.status = 1
                                GROUP BY tbl_consumer_demand.consumer_id
                            ),
                            coll as ( 
                                SELECT tbl_consumer_collection.consumer_id,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS arrear_coll,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                            AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS curr_coll
                                FROM tbl_consumer_collection
                                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                                WHERE tbl_transaction.transaction_date > '$from'::date 
                                    AND tbl_transaction.transaction_date <= '$to'::date
                                    AND tbl_transaction.status in(1,2)
                                GROUP BY tbl_consumer_collection.consumer_id
                            ),
                            prev_coll_amount as ( 
                                    SELECT tbl_transaction.related_id,
                                        sum(tbl_consumer_collection.amount) AS prev_coll
                                    FROM tbl_consumer_collection
                                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                    JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                                    WHERE tbl_transaction.transaction_date <= '$from'::date 
                                        AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                        AND tbl_transaction.status in(1,2)
                                    GROUP BY tbl_transaction.related_id
                            ),
                            meter_type as ( 
                                SELECT tbl_meter_status.id,
                                tbl_meter_status.consumer_id,
                                tbl_meter_status.connection_type
                                FROM tbl_meter_status
                                WHERE (
                                        tbl_meter_status.id IN ( 
                                                            SELECT max(tbl_meter_status_1.id) AS id
                                                            FROM tbl_meter_status tbl_meter_status_1
                                                            where status = 1
                                                            GROUP BY tbl_meter_status_1.consumer_id
                                                            ORDER BY (max(tbl_meter_status_1.id))
                                                            )
                                    )                        
                            ),
                            all_advance as(
                                select tbl_consumer.id, sum(tbl_advance_mstr.amount) as amount 
                                    from tbl_advance_mstr
                                    JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
                                    join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
                                    where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                                        AND tbl_transaction.status in (1, 2) 
                                        AND (tbl_transaction.transaction_date <= '$to'::date) 
                                        AND tbl_advance_mstr.module='consumer' 
                                    group by tbl_consumer.id
                            ),
                            all_adjustment as (
                                select tbl_consumer.id, sum(tbl_adjustment_mstr.amount) as amount 
                                    from tbl_adjustment_mstr
                                    JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
                                    join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
                                    where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                                        AND tbl_transaction.status in (1, 2) 
                                        AND (tbl_transaction.transaction_date <= '$to'::date) 
                                        AND tbl_adjustment_mstr.module='consumer' 
                                    group by tbl_consumer.id
                            )
                ";
                $sql ="FROM tbl_consumer
                        LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                        LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                        LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                        LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                        LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id  
                        LEFT JOIN  all_advance on all_advance.id = tbl_consumer.id
                        LEFT JOIN  all_adjustment on all_adjustment.id = tbl_consumer.id                  
                        $whereQuery
                ";

            $fetchSql = $with_query.$selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'consumer_no'=>$tran_dtl['consumer_no'],
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'property_type'=>$tran_dtl['property_type'],
                        'connection_type'=> $tran_dtl["connection_type"] ,
                        'outstanding_at_begin'=>$tran_dtl['outstanding_at_begin'],
                        'current_demand'=>$tran_dtl['current_demand'],
                        'total'=>$tran_dtl['outstanding_at_begin']+$tran_dtl['current_demand'],
                        'arrear_coll'=>$tran_dtl['arrear_coll'],
                        'curr_coll'=>$tran_dtl['curr_coll'],
                        'advance_amount'=> $tran_dtl["advance_amount"] ,
                        'old_due'=>$tran_dtl['old_due'],
                        'curr_due'=>$tran_dtl['curr_due'],
                        'outstanding'=>$tran_dtl['outstanding'],

                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Consumer No.');
                            $activeSheet->setCellValue('B1', 'Consumer Name');
                            $activeSheet->setCellValue('C1', 'Property Type');
                            $activeSheet->setCellValue('D1', 'Connection Type');
                            $activeSheet->setCellValue('E1', 'Outstanding at the beginning');
                            $activeSheet->setCellValue('F1', 'Current Demand');
                            $activeSheet->setCellValue('G1', 'Total Demand');
                            $activeSheet->setCellValue('H1', 'Old Due Collection');
                            $activeSheet->setCellValue('I1', 'Current Collection');
                            $activeSheet->setCellValue('J1', 'Advance Collection');
                            $activeSheet->setCellValue('K1', 'Old Due');
                            $activeSheet->setCellValue('L1', 'Current Due');
                            $activeSheet->setCellValue('M1', 'Outstanding Due');


                            $activeSheet->fromArray($records, NULL, 'A3');

            $filename = "ConsumerWiseDCB_".date('Ymd-hisa').".xlsx";
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
    
    
    
}
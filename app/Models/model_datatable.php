<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_datatable extends Model
{
	protected $db;
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getSumAmount($totalRecordsSql, $getQuery = false, $totalRecordsSql0=""){
        try{
            $builder = $this->db->query($totalRecordsSql0."SELECT sum(tbl_transaction.payable_amt) as totalamount ".$totalRecordsSql);
            $totalRecords = $builder->getResultArray()[0]['allcount'];
            if ($getQuery===true) {
                echo $this->db->getLastQuery();
            }
            return $totalRecords;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getCount($query){
        try{
            $sql = "SELECT COUNT(*) AS total_count FROM (".$query.") AS tbl";
            $builder = $this->db->query($sql);
            $total_count = $builder->getFirstRow("array")['total_count'];

            return $total_count;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getDesignationWithUserId($user_type_mstr_id)
    {
        try
        {
            
            $sql = "SELECT * FROM view_emp_details WHERE user_type_id=$user_type_mstr_id";
            $builder = $this->query($sql);

            return $result = $builder->getResultArray()[0];

        }
        catch(Exception $e){
           return $e->getMessage();
        }
    }

    public function getTotalRecords($totalRecordsSql, $getQuery = false, $totalRecordsSql0=""){

        try{
            $builder = $this->db->query($totalRecordsSql0."SELECT count(*) as allcount ".$totalRecordsSql);
            $totalRecords = $builder->getResultArray()[0]['allcount'];
            if ($getQuery===true) {
                echo $this->db->getLastQuery();
            }
            return $totalRecords;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getTotalRecordwithFilter($totalRecordwithFilterSql, $getQuery = false, $totalRecordsSql0=""){

        try{
            $builder = $this->db->query($totalRecordsSql0."SELECT count(*) as allcount ".$totalRecordwithFilterSql);
            $totalRecordwithFilter = $builder->getResultArray()[0]['allcount'];
            if ($getQuery===true) {
                echo $this->db->getLastQuery();
            }
            return $totalRecordwithFilter;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getRecords($fetchSql, $getQuery = false){

        // echo $fetchSql;die();
        try{
            $builder = $this->db->query($fetchSql);
            $records = $builder->getResultArray();
            if ($getQuery===true) {
                echo $this->db->getLastQuery();
            }
            return $records;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getSumRecords($fetchSql, $getQuery = false){
        try{
            $builder = $this->db->query($fetchSql);
            $records = $builder->getFirstRow("array");
            
            if ($getQuery===true) {
                echo $this->db->getLastQuery();
            }
            return $records;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getWardWiseCollectionSummery($inputs, $getQuery = false){
        try{
            $JOIN = ($inputs['ward_type_report']=='all_ward')?"LEFT":"INNER";
            $fetchSql = "with CURRENT_DEMAND AS (
                            SELECT
                                tbl_prop_dtl.ward_mstr_id AS ward_mstr_id,
                                COALESCE(SUM(tbl_collection.amount), 0) AS current_collection,
                                COUNT(distinct(tbl_transaction.prop_dtl_id)) AS total_holding
                            FROM tbl_collection
                            INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_collection.transaction_id
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Property' 
                            AND tbl_transaction.tran_date BETWEEN '".$inputs['from_date']."' AND '".$inputs['upto_date']."'
                            AND tbl_collection.fy_mstr_id=".$inputs['currentFyID']."
                            AND tbl_collection.status=1
                            AND tbl_transaction.status IN (1,2)
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        ARREAR_DEMAND AS (
                            SELECT
                                tbl_prop_dtl.ward_mstr_id AS ward_mstr_id,
                                COALESCE(SUM(tbl_collection.amount), 0) AS arrear_collection
                            FROM tbl_collection
                            INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_collection.transaction_id
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Property' 
                            AND tbl_transaction.tran_date BETWEEN '".$inputs['from_date']."' AND '".$inputs['upto_date']."'
                            AND tbl_collection.fy_mstr_id<".$inputs['currentFyID']."
                            AND tbl_collection.status=1
                            AND tbl_transaction.status IN (1,2)
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        )
                        SELECT
                            WARD.ward_no,
                            COALESCE(CURRENT_DEMAND.total_holding, 0) AS total_holding,
                            COALESCE(CURRENT_DEMAND.current_collection, 0) AS current_collection,
                            COALESCE(ARREAR_DEMAND.arrear_collection, 0) AS arrear_collection,
                            COALESCE(CURRENT_DEMAND.current_collection, 0)+COALESCE(ARREAR_DEMAND.arrear_collection, 0) AS total_collection
                        FROM view_ward_mstr WARD
                        ".$JOIN." JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=WARD.id
                        LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=WARD.id
                        ORDER BY WARD.id ASC";
            $builder = $this->db->query($fetchSql);
            $records = $builder->getResultArray();
            if ($getQuery===true) {
                print_var($this->db->getLastQuery());
            }
            return $records;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getDatatable($sqlLocal, $show_data = null)
    {
    	try 
        {
            if(is_null($show_data)) {
                $show_data = limitInPagination();
            }
            $uri_string = uri_string();
            if(isset($_GET['page'])) {
                $page = intval($_GET['page'])-1;
                if($page<0) $page = 0;
            } else {
                $page = 0;
            }
            $start_page = $page*$show_data;

            $sql = $sqlLocal." LIMIT $show_data OFFSET $start_page;";
            $resultBuilder = $this->db->query($sql);

            // die;
            
            $sql = "SELECT COUNT(*) AS total_count FROM (".$sqlLocal.") AS tbl";
            $builder = $this->db->query($sql);
            $total_count = $builder->getFirstRow("array")['total_count'];
            $result = [
                'result' => $resultBuilder->getResultArray(),
                'count' => $total_count,
				'offset' => $start_page,
            ];
            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    
    public function getDatatablCount($sql)
    {
    	try
        {
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array")['total_count'];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function single_with_limit( $with,$with_aleas ,$select,$from,$show_data = null) #please pass with query without with and bracket()
    {
        try 
        {
            if(is_null($show_data)) {
                $show_data = limitInPagination();
            }
            $uri_string = uri_string();
            if(isset($_GET['page'])) 
            {
                $page = intval($_GET['page'])-1;
                if($page<0) $page = 0;
            } 
            else 
            {
                $page = 0;
            }
            $start_page = $page*$show_data;

            $sql = " with $with_aleas as 
                    (".$with ." 
                    LIMIT $show_data OFFSET $start_page 
                    ) 
                    $select 
                    FROM $with_aleas $from ";
            //print_var($sql);
            $resultBuilder = $this->db->query($sql);
            
            $sqlLocal = " with $with_aleas as 
                        (".$with ."                          
                        ) 
                        $select 
                        FROM $with_aleas $from ";

            $sql = "SELECT COUNT(*) AS total_count FROM (".$sqlLocal.") AS tbl";
            $builder = $this->db->query($sql);
            $total_count = $builder->getFirstRow("array")['total_count'];
            $result = [
                'result' => $resultBuilder->getResultArray(),
                'count' => $total_count,
				'offset' => $start_page,
            ];
            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}
?>
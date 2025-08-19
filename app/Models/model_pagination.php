<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class model_pagination extends Model
{
    protected $table = 'view_ward_mstr';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'ward_no', 'ulb_mstr_id', 'status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getWard(array $postData){
        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = 0;//$postData['start'];
        $rowperpage = 10;//$postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (emp_name like '%".$searchValue."%' or email like '%".$searchValue."%' or city like'%".$searchValue."%' ) ";
        }
   
        ## Total number of records without filtering
        $builder = $this->db->table('view_ward_mstr');
        $builder = $builder->select('count(*) as allcount');
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $totalRecords = $builder->getFirstRow('array')['allcount'];

        ## Total number of record with filtering
        $builder = $this->db->table('view_ward_mstr');
        $builder = $builder->select('count(*) as allcount');
        if($searchQuery != '')
            $builder = $builder->where($searchQuery);
        $builder = $builder->get();
        $totalRecordwithFilter = $builder->getFirstRow('array')['allcount'];

        
        $builder = $this->db->table('view_ward_mstr');
        $builder = $builder->select('*');
        /* if($searchQuery != '')
            $builder = $builder->where($searchQuery);
        $builder = $$builder->orderBy($columnName, $columnSortOrder);
        $builder = $$builder->limit($rowperpage, $start); */
        $builder = $builder->get();
        $records = $builder->getResultArray();
        
        
        foreach($records as $record ){

            $data[] = array( 
               "ward_id"=>$record['id'],
               "ward_no"=>$record['ward_no'],
               "ulb_mstr_id"=>$record['ulb_mstr_id'],
               "status"=>$record['status']
            ); 
        }
        ## Response
       /*  $response = array(
            //"draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ); */

        return $data;
        
        
    }
	
}

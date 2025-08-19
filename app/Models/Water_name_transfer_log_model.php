<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Water_name_transfer_log_model extends Model
{
    protected $db;
    protected $table = 'tbl_name_transfer_log';
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
       //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        //print_var($this->db);
        return $insert_id;

    }
    public function updateData($data,$ids)
    {
        $data = $this->db->table($this->table)
                    ->where('id',$ids)
                    ->update($data);
        return $data;
    }

    public function getData($consumer_id)
    {
        $data = $this->db->table($this->table)
                        ->select('*')
                        ->where('consumer_id',$consumer_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }

    public function insertData_tbl_consumer_details_log($data)
    {
        $result= $this->db->table('tbl_consumer_details_log')
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        //print_var($this->db);
        return $insert_id;
    }

    public function updateData_tbl_consumer_details_log($data,$ids)
    {
        $data = $this->db->table('tbl_consumer_details_log')
                    ->where('id',$ids)
                    ->update($data);
        return $data;
    }

    public function getData_tbl_consumer_details_log($consumer_id)
    {
        $data = $this->db->table('tbl_consumer_details_log')
                        ->select('*')
                        ->where('consumer_id',$consumer_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }

    public function insertData_tbl_consumer_log($data)
    {
        $result= $this->db->table('tbl_consumer_log')
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        //print_var($this->db);
        return $insert_id;
    }

    public function updateData_tbl_consumer_log($data,$ids)
    {
        $data = $this->db->table('tbl_consumer_log')
                    ->where('id',$ids)
                    ->update($data);
        return $data;
    }

    public function getData_tbl_consumer_log($consumer_id)
    {
        $data = $this->db->table('tbl_consumer_log')
                        ->select('*')
                        ->where('consumer_id',$consumer_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }



    public function insertData_tbl_consumer_document_details($data)
    {
        $result= $this->db->table('tbl_consumer_document_details')
                 ->insert($data);       
        // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        //print_var($this->db);
        return $insert_id;
    }

    public function updateData_tbl_consumer_document_details($data,$ids)
    {
        $data = $this->db->table('tbl_consumer_document_details')
                    ->where('id',$ids)
                    ->update($data);
                    //echo $this->getLastQuery();die;
        return $data;
    }

    public function getData_tbl_consumer_document_details($consumer_id)
    {
        $data = $this->db->table('tbl_consumer_document_details')
                        ->select('*')
                        ->where('consumer_id',$consumer_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }
    public function checkdate_tbl_consumer_document_details($where)
    {
        $data = $this->db->table('tbl_consumer_document_details')
                        ->select('count(*)')
                        ->where($where)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        //echo $this->getLastQuery();
        return $data['count'];
    }

    public function get_specific_data($where)
    {
        $data = $this->db->table('tbl_consumer_document_details')
                        ->select('*')
                        ->where($where)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }
    public function Existing_document_get($consumer_id)
    {
        $sql = " select tbl_consumer_document_details.doc_for, file_name as document,tbl_document_mstr.document_name,
                    creation_on::date as creation_on , view_emp_details.emp_name
                from tbl_consumer_document_details
                join tbl_document_mstr on tbl_document_mstr.id = tbl_consumer_document_details.doc_detail_id
                join view_emp_details on view_emp_details.id = tbl_consumer_document_details.emp_details_id
                where tbl_consumer_document_details.status =1 and tbl_consumer_document_details.uplode_type='Existing' and tbl_consumer_document_details.consumer_id ='$consumer_id'";
        
        $data = $this->db->query($sql)                        
                        ->getResultArray();
        return $data;
    }

    public function insertData_tbl_consumer_demand_audit($data)
    {
        $result= $this->db->table('tbl_consumer_demand_audit')
                 ->insert($data);       
        // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        //print_var($this->db);
        return $insert_id;
    }

    public function get_data_tbl_consumer_demand_audit($consumer_id)
    {
        $data = $this->db->table('tbl_consumer_demand_audit')
                        ->select('*')
                        ->where('consumer_id',$consumer_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        return $data;
    }
}
?> 
<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_applicant_details extends Model 
{
    protected $db;
    protected $table = 'tbl_applicant_details';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apply_connection_id', 'applicant_name', 'father_name', 'city', 'district', 'state', 'mobile_no', 'email_id','emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function applicantdetails($water_conn_id)
    {      
      try{

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id', $water_conn_id)
                        ->get(); 
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function applicantdetails_md5($water_conn_id)
    {      
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(apply_connection_id::text)', $water_conn_id)
                        ->get(); 
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}
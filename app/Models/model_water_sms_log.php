<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_water_sms_log extends Model 
{
    protected $db;
    protected $table = 'tbl_sms_log';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['id','apply_connection_id','doc_for','document_id','document_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function insert_sms_log($data=array())
    {   
        try
        {
            $this->db->table($this->table)->insert($data);            
            return $this->db->insertID();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }

    public function update_sms_log($where=array(),$data=array())
    {   
        try
        {
            $this->db->table($this->table)->where($where)->update($data);
            //print_var($db->affectedRows());
            return$this->db->affectedRows();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }

    
}
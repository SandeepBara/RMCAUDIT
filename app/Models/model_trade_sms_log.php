<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_sms_log extends Model 
{
    protected $db;
    protected $table = 'tbl_sms_log';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','emp_id','ref_id','ref_type','mobile_no','purpose','template_id','message','response','smgid','stampdate','status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function insert_sms_log($data=array())
    {   
        try
        {
            $this->db->table($this->table)->insert($data); 
            //echo $this->db->getLastQuery();die;           
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
            //print_var($this->db);die;
            return $this->db->affectedRows() ?? false;

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }
}
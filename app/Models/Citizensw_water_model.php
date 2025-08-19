<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Citizensw_water_model extends Model
{
    protected $db;
    protected $table = 'tbl_single_window_apply';   

    public function __construct(ConnectionInterface $db)
    {
        $session=session();
        $this->db = $db;
    }
    public function InsertData(array $inputs)
    {
        $this->db->table('tbl_single_window_apply')
                ->insert($inputs);
        // echo $this->db->getLastQuery();die;
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    public function updateData(array $inputs, array $where)
    {
        try{
            $data = $this->db->table($this->table)
                    ->where($where)
                    ->update($inputs);
            return $data;
                
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            
        }
    }
    public function getData(array $where)
    {
        $data = $this->db->table($this->table)
                ->select('*')
                ->where($where)
                ->orderBy('id','desc')
                ->get()
                ->getFirstRow('array');
        return $data; 
        
    }
    public function row_sql($sql)
    {
        try
        {
            $data = $this->db->query($sql)->getResultArray();
            // echo $this->db->getLastQuery(); 
            // print_var($data);
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }
    public function new_connection()
    {        
        try
        {
            $sql="select * from tbl_single_window_log where cust_id='".$_SESSION["custId"]."' and service_id='".$_SESSION['serviceId']."' order by id desc limit 1";
            $data = $this->db->query($sql)->getResultArray();
            // echo $this->db->getLastQuery(); 
            // print_var($data);            
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }

    }
    public function insertResponse(array $inputs)
    {
        try{
            $data = $this->db->table('tbl_single_window_response')
                        ->insert($inputs);
            return $this->db->insertID();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
  
}
?>
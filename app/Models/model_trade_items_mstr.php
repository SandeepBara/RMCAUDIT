<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_items_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_trade_items_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'trade_item', 'status', 'trade_code'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function nature_business($data)
    {      
        //print_var($data);return;

        $arr = array();
        $arr = explode(',', $data);
        try
        {
            $builder = $this->db->table($this->table)
                        ->select("STRING_AGG(trade_item, ', ') AS trade_item")
                        ->whereIn('id', $arr)
                        ->get(); 
                        // echo $this->db->getLastQuery();die;
           return $builder->getFirstRow('array');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function get_nature_business($data)
    {      $wherein = "id in ($data)";
      try{
            //print_var($wherein);exit;
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where($wherein)
                        ->get(); 
                       //echo $this->db->getLastQuery();exit;
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function get_nature_businessItemCode($data)
    {      
        $wherein = "id in ($data)";
        try
        {
            if(!trim($data))
                return [];
            $builder = $this->db->table($this->table)
                        ->select("concat(trade_item,' (',trade_code,') <br/>') as trade_item")
                        ->where($wherein)
                        ->get(); 
                        // echo $this->db->getLastQuery();exit;
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}
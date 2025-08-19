<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_view_licence_trade_items extends Model 
{
    protected $db;
    protected $table = 'view_licence_trade_items';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'licence_id','trade_items_id', 'trade_item', 'trade_code', 'created_on','emp_details_id', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

   
    public function get_details($id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('md5(licence_id::text)',$id)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
   

   
   
    


}
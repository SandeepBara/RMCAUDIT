<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_item_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_trade_item_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'trade_item', 'trade_code', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

     public function getitemname($apply_licence_id){
        $sql = "select t.id,t.trade_item, t.trade_code from tbl_application_trade_items a join tbl_trade_items_mstr t on(a.trade_items_id=t.id) WHERE a.apply_licence_id='$apply_licence_id' order by t.id asc";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
       // echo $this->db->getLastQuery();

        return $result;
         }
    }
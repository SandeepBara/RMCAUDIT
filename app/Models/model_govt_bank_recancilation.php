<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_govt_bank_recancilation extends Model
{
	protected $db;
  protected $table = 'tbl_govt_bank_reconcilation';
  protected $allowedFields = ['id','created_on','emp_details_id','cheque_dtl_id','transaction_id','reason','amount','status'];
  public function __construct(ConnectionInterface $db){
    $this->db = $db;
  }
 
   public function insertData(array $data)
   {

      $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

   }
   
}
?>
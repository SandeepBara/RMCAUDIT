<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ModelCashVerification extends Model 
{
    protected $table = 'tbl_doc_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['doc_name'];
    
   
    public function cash_verification_pending_list()
    {
        $sql="select * from tbl_transaction where cash_verify_status is NULL and tran_date=".$data['tran_date'];
        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }
}
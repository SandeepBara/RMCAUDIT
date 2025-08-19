<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_doc_mstr extends Model
{
    protected $db;
    protected $table = 'tbl_doc_mstr';
    protected $allowedFields = ['id','doc_name','doc_type','doc_id','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function documentList()
    {
        try{
            $builder = $this->db->table('view_doc_mstr')
                        ->select('*')
                        ->where('status',1)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?> 
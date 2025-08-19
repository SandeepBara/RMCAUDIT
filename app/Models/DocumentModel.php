<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class DocumentModel extends Model 
{
    protected $table = 'tbl_doc_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['doc_name'];
    
    public function getAll()
    {
        $this->orderBy('id', 'DESC')->findAll();
    }

    public function createNew(array $data)
    {
        $this->insert($data);
    }
    public function updatedata($id, $data=array())
    {
        $this->update($id,$data);
    }
    public function getbyid($id)
    {
        $this->where('id',$id)->first();
    }

    public function deletebyid($id)
    {
        $this->where('id',$id)->delete();
    }

}
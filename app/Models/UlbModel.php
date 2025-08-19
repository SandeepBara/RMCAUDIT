<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class UlbModel extends Model 
{
    protected $table = 'ulb_list';

    protected $primaryKey = '_id';

    protected $allowedFields = ['ulb_name'];
    
    public function getAll()
    {
        $this->orderBy('_id', 'DESC')->findAll();
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
        $this->where('_id',$id)->first();
    }

    public function deletebyid($id)
    {
        $this->where('_id',$id)->delete();
    }
}
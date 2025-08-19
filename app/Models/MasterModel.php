<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class MasterModel extends Model
{

    protected $table = 'tbl_ward_mstr';
    
    protected $allowedFields = ['id', 'ward_no'];
	
	public function addward(array $data)
    {
        $this->insert($data);
    }
    public function updateward($id, $data=array())
    {
        $this->update($id,$data);
    }
    public function getwardbyid($id)
    {
        $this->where('id',$id)->first();
    }
    public function deletewardbyid($id)
    {
        $this->where('id',$id)->delete();
    }

    public function ward_list()
    {
        $result=$this->table($this->table)
            ->select('*')
            ->get()
            ->getResultArray();

        return $result;
    }
}
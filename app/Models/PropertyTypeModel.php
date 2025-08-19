<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class PropertyTypeModel extends Model 
{
    protected $table = 'tbl_prop_type_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['property_type'];
    
    public function getPropertyTypeList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, property_type')
                        ->where('status', 1)
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

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
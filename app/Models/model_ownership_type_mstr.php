<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_ownership_type_mstr extends Model
{
		protected $db;
    protected $table = 'tbl_ownership_type_mstr';
    protected $allowedFields = ['id','ownership_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function getOwnershipTypeList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, ownership_type')
                        ->where('status', 1)
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "ownership_type"=>$input["ownership_type"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('ownership_type', $input['ownership_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,ownership_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('ownership_type', $input['ownership_type']);
		$builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
		//echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

	public function updatedataById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'ownership_type'=>$input['ownership_type']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }

    public function ownership_type($data)
    {
      try{

            $builder = $this->db->table($this->table)
                        ->select('id, ownership_type')
                        ->where('status', 1)
                        ->where('id', $data["ownership_type_mstr_id"])
                        ->get();

           return $builder->getResultArray()[0];


        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>

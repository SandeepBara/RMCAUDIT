<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_colony_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_colony_mstr';
    protected $allowedFields = ['id','colony_name','colony_address'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }


    public function getColonyList()
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, colony_name,colony_address')
                        ->where('status', 1)
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function insertData($input)
    {
        $builder = $this->db->table($this->table)
                            ->insert([
                                        "colony_name"=> $input["colony_name"],
                                        "colony_address"=> $input["colony_address"],
                                    ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }


	public function checkdata($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('colony_name', $input['colony_name']);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        return $builder = $builder->getFirstRow('array');
    }


    public function getdatabyid($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id,colony_name,colony_address');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		return $builder = $builder->getFirstRow('array');

    }


    public function checkupdatedata($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('colony_name', $input['colony_name']);
		$builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
		//echo $this->db->getLastQuery();
        return $builder = $builder->getFirstRow('array');

    }

	public function updatedataById($input)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'colony_name'=>$input['colony_name'],
                                    'colony_address'=>$input['colony_address'],
                                    "update_on"=> 'NOW()'
                                    ]);
        //echo $this->db->getLastQuery();
        return $builder;

    }


    public function deletedataById($id)
    {
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

            return $builder = $builder->getFirstRow('array');


        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>

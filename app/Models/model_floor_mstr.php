<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_floor_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_floor_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'floor_name', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getFloorList(){
        try{
            $client = new \Predis\Client();
            $floor_mstr = $client->get("floor_mstr");
            if (!$floor_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, floor_name')
                            ->where('status', 1)
                            ->orderBy('id', 'ASC')
                            ->get();
                $floor_mstr = $builder->getResultArray();
                $client->set("floor_mstr", json_encode($floor_mstr));
                return $floor_mstr;
            } else {
                return json_decode($floor_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    /*public function getDbDetailsById($input){
        $builder = $this->db->table($this->table);
        $builder->select('id AS ulb_mstr_id, db_property AS property, db_water AS water');
        $builder->where('id', $input['ulb_mstr_id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }*/

    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "floor_name"=>$input["floor_name"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('floor_name', $input['floor_name']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,floor_name,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		return $builder = $builder->getFirstRow("array");

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('floor_name', $input['floor_name']);
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
                                    'floor_name'=>$input['floor_name']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
}
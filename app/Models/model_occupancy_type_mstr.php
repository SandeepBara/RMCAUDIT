<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_occupancy_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_occupancy_type_mstr';
    protected $allowedFields = ['id','property_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getOccupancyTypeList(){
        try{
            $client = new \Predis\Client();
            $occupancy_type_mstr = $client->get("occupancy_type_mstr");
            if (!$occupancy_type_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, occupancy_name, mult_factor')
                            ->where('status', 1)
                            ->get();
                $occupancy_type_mstr = $builder->getResultArray();
                $client->set("occupancy_type_mstr", json_encode($occupancy_type_mstr));
                return $occupancy_type_mstr;
            } else {
                return json_decode($occupancy_type_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getOccupancyMultFactById($_id){
        try{
            return $this->db->table($this->table)
                        ->select('mult_factor')
                        ->where('id', $_id)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "occupancy_name"=>$input["occupancy_name"],
                  "mult_factor"=>$input["mult_factor"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('occupancy_name', $input['occupancy_name']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,occupancy_name,mult_factor,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('occupancy_name', $input['occupancy_name']);
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
                                    'occupancy_name'=>$input['occupancy_name'],
                                    'mult_factor'=>$input['mult_factor']
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
?>
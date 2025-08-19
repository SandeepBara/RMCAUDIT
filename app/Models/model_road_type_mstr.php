<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_road_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_road_type_mstr';
    protected $allowedFields = ['id','road_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getRoadTypeList(){
        // print_var($this->db->getDatabase());
        try{
            $client = new \Predis\Client();
            $road_type_mstr = $client->get("road_type_mstr");
            if (!$road_type_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, road_type')
                            ->where('status', 1)
                            ->get();
                $road_type_mstr = $builder->getResultArray();
                $client->set("road_type_mstr", json_encode($road_type_mstr));
                return $road_type_mstr;
            } else {
                return json_decode($road_type_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "road_type"=>$input["road_type"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('road_type', $input['road_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){        
        $builder = $this->db->table($this->table);
        $builder->select('id,road_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();        
		$builder = $builder->getResultArray();
        return $builder[0] ?? [];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('road_type', $input['road_type']);
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
                                    'road_type'=>$input['road_type']
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
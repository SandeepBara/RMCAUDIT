<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_const_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_const_type_mstr';
    protected $allowedFields = ['id','construction_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getConstTypeList(){
        try{
            $client = new \Predis\Client();
            $const_type_mstr = $client->get("const_type_mstr");
            if (!$const_type_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, construction_type')
                            ->where('status', 1)
                            ->get();
                $const_type_mstr = $builder->getResultArray();
                $client->set("const_type_mstr", json_encode($const_type_mstr));
                return $const_type_mstr;
            } else {
                return json_decode($const_type_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "construction_type"=>$input["construction_type"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('construction_type', $input['construction_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,construction_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('construction_type', $input['construction_type']);
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
                                    'construction_type'=>$input['construction_type']
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
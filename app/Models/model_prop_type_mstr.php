<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_prop_type_mstr';
    protected $allowedFields = ['id','property_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getPropTypeList(){
        try{
            $client = new \Predis\Client();
            $prop_type_mstr = $client->get("prop_type_mstr");
            if (!$prop_type_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, property_type')
                            ->where('status', 1)
                            ->get();
                $prop_type_mstr = $builder->getResultArray();
                $client->set("prop_type_mstr", json_encode($prop_type_mstr));
                return $prop_type_mstr;
            } else {
                return json_decode($prop_type_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
     public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "property_type"=>$input["property_type"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('property_type', $input['property_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,property_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getFirstRow('array');
        return $builder;

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('property_type', $input['property_type']);
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
                                    'property_type'=>$input['property_type']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }

    public function property_type($data)
    {      
      try{
        
            $builder = $this->db->table($this->table)
                        ->select('id, property_type')
                        ->where('status', 1)
                        ->where('id', $data["prop_type_mstr_id"])
                        ->get(); 

           return $builder->getResultArray()[0];


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?>
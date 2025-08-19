<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_transfer_mode_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_transfer_mode_mstr';
    protected $allowedFields = ['id','transfer_mode	','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getTransferModeList(){
        try{
            $client = new \Predis\Client();
            $transfer_mode_mstr = $client->get("transfer_mode_mstr");
            if (!$transfer_mode_mstr) {

                $builder = $this->db->table($this->table)
                            ->select('id, transfer_mode')
                            ->where('status', 1)
                            ->get();
                $transfer_mode_mstr = $builder->getResultArray();
                $client->set("transfer_mode_mstr", json_encode($transfer_mode_mstr));
                return $transfer_mode_mstr;
            } else {
                return json_decode($transfer_mode_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
     public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "transfer_mode"=>$input["transfer_mode"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('transfer_mode', $input['transfer_mode']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,transfer_mode,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('transfer_mode', $input['transfer_mode']);
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
                                    'transfer_mode'=>$input['transfer_mode']
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
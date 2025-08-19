<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_usage_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_usage_type_mstr';
    protected $allowedFields = ['id','usage_type','usage_code','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getUsageTypeList(){
        try{
            $client = new \Predis\Client();
            $usage_type_mstr = $client->get("usage_type_mstr");
            if (!$usage_type_mstr) {
                $builder = $this->db->table($this->table)
                            ->select('id, usage_type, usage_code')
                            ->where('status', 1)
                            ->get();
                $usage_type_mstr = $builder->getResultArray();
                $client->set("usage_type_mstr", json_encode($usage_type_mstr));
                return $usage_type_mstr;
            } else {
                return json_decode($usage_type_mstr, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getUsageTypeFactorList(){
        try{
            $builder = $this->db->table("tbl_usage_type_mstr")
                        ->select('tbl_usage_type_mstr.usage_type AS usage_type,
                                    tbl_usage_type_dtl.mult_factor AS mult_factor')
                        ->join('tbl_usage_type_dtl', 'tbl_usage_type_dtl.usage_type_mstr_id = tbl_usage_type_mstr.id')
                        ->where('tbl_usage_type_mstr.status', 1)
                        ->where('tbl_usage_type_dtl.status', 1)
                        ->orderBY('tbl_usage_type_mstr.id', 'ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getUsageTypeFactorCVList(){
        try{
            $builder = $this->db->table("tbl_usage_type_mstr")
                        ->select('tbl_usage_type_mstr.usage_type AS usage_type,
                                    tbl_usage_type_dtl.cv_mult_factor AS mult_factor')
                        ->join('tbl_usage_type_dtl', 'tbl_usage_type_dtl.usage_type_mstr_id = tbl_usage_type_mstr.id')
                        ->where('tbl_usage_type_mstr.status', 1)
                        ->where('tbl_usage_type_dtl.status', 1)
                        ->orderBY('tbl_usage_type_mstr.id', 'ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

	public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "usage_type"=>$input["usage_type"],
                  "usage_code"=>$input["usage_code"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('usage_type', $input['usage_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,usage_type,usage_code,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0] ?? [];

    }
    public function getolddatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,usage_type,usage_code,status');
        $builder->where('id', $id);
        $builder = $builder->get();
		return $builder->getFirstRow("array");

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('usage_type', $input['usage_type']);
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
                                    'usage_type'=>$input['usage_type'],
                                    'usage_code'=>$input['usage_code']
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
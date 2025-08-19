<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_tran_mode_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_tran_mode_mstr';
    protected $allowedFields = ['id','transaction_mode	','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getTranModeList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, transaction_mode')
                        ->where('status', 1)
                        ->get();
                        //echo $this->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
     public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "transaction_mode"=>$input["transaction_mode"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('transaction_mode', $input['transaction_mode']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,transaction_mode,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
	
	public function getpayModeList($data){
        $builder = $this->db->table($this->table);
        $builder->select('id,transaction_mode,status');
        $builder->where('id', $data);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
	
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('transaction_mode', $input['transaction_mode']);
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
                                    'transaction_mode'=>$input['transaction_mode']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
    public function getTransactionMode($id)
    {
        try{        
             $builder = $this->db->table($this->table)
                        ->select('transaction_mode')
                        ->where('id',$id)
                        ->where('status', 1)
                        ->get();
                      // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["transaction_mode"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
?>
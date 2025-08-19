<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_g_saf extends Model
{
		protected $db;
    protected $table = 'tbl_govt_saf_dtl';
    //protected $allowedFields = ['id','colony_name','colony_address'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function getApplicationDetail($application_no)
    {
        $sql="select * from view_gbsaf_detail where upper(application_no)='".$application_no."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;

    }
    public function getApplicationDetailbyId($id)
    {
        $sql="select * from view_gbsaf_detail where md5(id::text)='".$id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;

    }

    public function getColonyList()
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('id, colony_name,colony_address')
                        ->where('status', 1)
                        ->get();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
	public function insertData($input)
    {
        $builder = $this->db->table($this->table)
                            ->insert([
                                        "colony_name"=>$input["colony_name"],
                                        "colony_address"=>$input["colony_address"],
                                        "created_on"=>date('y-m-d')
                            ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('colony_name', $input['colony_name']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,colony_name,colony_address');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('colony_name', $input['colony_name']);
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
                                    'colony_name'=>$input['colony_name'],
																		 'colony_address'=>$input['colony_address'],
																		 "update_on"=>date('y-m-d')
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
    public function updateTransactionStatus($id,$status)
    {
        $sql="update tbl_govt_saf_dtl set is_transaction_done=$status where id=$id";
        $run=$this->db->query($sql);
    }
    public function updateAppStatus($id)// document uploaded
    {
        $sql="update tbl_govt_saf_dtl set app_status=2 where id=$id";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();
       
    }
}
?>

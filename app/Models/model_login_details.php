<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_login_details extends Model 
{
    protected $db;
    protected $table = 'tbl_login_details';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'emp_details_id', 'device_type', 'imei_no', 'ip_address', 'token', 'created_on', 'status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input)
    {
        $builder = $this->db->table($this->table)
                            ->insert($input);
        return $insert_id = $this->db->insertID();
    }

    public function updateTokenById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['login_details_id'])
                            ->update([
                                    'token'=>$input['token']
                                    ]);
    }
	
	
	
	public function login_details($crntDate, $emp_id)
    {

        $sql = "select ip_address,created_on
				from tbl_login_details
				where status=1 AND emp_details_id='".$emp_id."' AND created_on::date='".$crntDate."' limit 4";
        $ql= $this->query($sql);
		//echo $this->db->getLastQuery();
		if($ql){
			return $ql->getResultArray();
		}else{
			return false;
		} 

    }

    public function lastLoginDtls($emp_id,$device_type=null,$session_id=null)
    {
         $builder = $this->db->table($this->table)
                        ->where('emp_details_id', $emp_id);
        if($device_type)
        {
            $builder = $builder->where('device_type',"$device_type");
        }
        if($session_id)
        {
            $builder = $builder->where('session_id',"$session_id");
        }
        $builder = $builder
                   ->orderBy("id","DESC")
                    ->get()
                    ->getFirstRow('array');
        return $builder;
    }
    public function updateSessionId($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'session_id'=>$input['session_id']
                                    ]);
    }
	
	
	
	
}
<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_level_pending_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_level_pending_dtl';

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        try{
            $this->db->table($this->table)
                                ->insert($input);
                //echo $this->db->getLastQuery();
            return $this->db->insertID();
        } catch(Exception $e) {

        }
    }

    public function checkExists($gov_saf_id)
    {
        $sql="select count(id) as count from tbl_govt_saf_level_pending_dtl where md5(govt_saf_dtl_id::text)='".$gov_saf_id."' and receiver_user_type_id=9";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['count'];

    }

    public function getLastRecord($gov_saf_dtl_id)
    {
        try
        {
            $builder=$this->db->table($this->table)
                        ->select('*')
                        ->where("md5(govt_saf_dtl_id::text)", $gov_saf_dtl_id)
                        ->where("status", 1)
                        ->orderBy('id desc')
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function UpdateLevelTable($gov_saf_dtl_id, $input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                            ->where('govt_saf_dtl_id', $gov_saf_dtl_id)
                            ->where('status', 1)
                            ->update([
                                        'verification_status' => $input['verification_status'],
                                        'status'=> $input['status'],
                                        'msg_body'=> $input['msg_body'],
                                        'forward_date'=> 'NOW()',
                                        'forward_time'=> 'NOW()',
                                    ]);
            return $builder;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
	
}
?>

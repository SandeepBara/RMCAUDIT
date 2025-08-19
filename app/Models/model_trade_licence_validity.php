<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_licence_validity extends Model 
{
    protected $db;
    protected $table = 'tbl_licence_validity';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'licence_id','generated_date','validity', 'created_on','emp_details_id', 'status','from_date','to_date'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertdata($input){
		$builder = $this->db->table($this->table)
                ->insert([
                  "licence_id"=>$input["licence_id"],
                  "generated_date"=>$input["generated_date"],
                  "validity"=>$input["validity"],
                  "created_on"=>$input["created_on"],
                  "emp_details_id"=>$input["emp_details_id"],
                  "from_date"=>$input["from_date"],
                  "to_date"=>$input["to_date"],
                  "status"=>'1'
				  ]);
              //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updateinsertdata($input){
       $this->db->table($this->table)
                            ->where('licence_id', $input['licence_id'])
                            ->where('status',1)
                            ->update([
                                    'status'=>0
                                    ]);

    }
    public function get_licence_validity_dtl($licence_id)
    {
        try{
            //  return $this->db->table($this->table)
            //             ->select('*')
            //             ->where('licence_id',$licence_id)
            //             ->orderBy('id','DESC')
            //             ->get()
            //             ->getResultArray()[0];
            return $this->db->table($this->table)
            ->select('*')
            ->where('licence_id',$licence_id)
            ->orderBy('id','DESC')
            ->get()
            ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function deactivateRecord($licence_id){
      try{
            return $builder = $this->db->table($this->table)
                             ->where('licence_id',$licence_id)
                             ->update([
                                        'status'=>0
                                      ]);
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
}
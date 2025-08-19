<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_water_consumer_initial_meter extends Model
{
	protected $db;
    protected $table = 'tbl_consumer_initial_meter';
    protected $allowedFields = ['id', 'consumer_id', 'initial_reading', 'emp_details_id', 'created_on', 'status'];

	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

     public function insertData($consumer_id,$emp_details_id,$created_on){

        $builder = $this->db->table($this->table)
                            ->insert([
                  "consumer_id"=>$consumer_id,
                  "initial_reading"=>'0.00',
                  "emp_details_id"=>$emp_details_id,
                  "created_on"=>$created_on,
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();

         }
    
    public function consumerinitialDetails($consumer_id){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('consumer_id',$consumer_id)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            $data = $builder->getResultArray();
            if(count($data)>0)
                return $builder->getResultArray()[0];
            else
                return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

     public function consumerInitialMeterDetailsbymd5($consumer_id){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('md5(consumer_id::text)',$consumer_id)
                     ->where('status',1)
                     ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function insertInitialReading(array $data)
    {

        $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }

    public function getLastMeterReading($consumer_id)
    {
        $sql="select initial_reading from tbl_consumer_initial_meter where status=1 and md5(consumer_id::text)='".$consumer_id."' order by id desc limit 1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result['initial_reading'];

    }



}
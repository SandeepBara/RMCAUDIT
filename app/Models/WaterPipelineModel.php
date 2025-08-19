<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterPipelineModel extends Model
{

    protected $table = 'tbl_pipeline_type_mstr';
    protected $allowedFields = ['pipeline_type'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function pipeline_list()
    {
        $client = new \Predis\Client();
        $water_pipeline_list = $client->get("water_pipeline_list");
        if (!$water_pipeline_list) {
           $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->get();
                    //echo $this->db->getLastQuery();exit;
            $water_pipeline_list = $builder->getResultArray();
            $client->set("water_pipeline_list", json_encode($water_pipeline_list));
            return $water_pipeline_list;
        } else {
            return json_decode($water_pipeline_list, true);
        }
    }

    public function checkdata(array $data)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('pipeline_type',$data['pipeline_type'])  
                    ->where('id!=',$data['id'])
    				->get()
    				->getFirstRow("array");

    				//echo ($result['id']);
    		//	echo $this->getLastQuery();
    	return $result['id'];
    }
    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
                 echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function deleteData($id)
    {
    	$sql="delete from tbl_pipeline_type_mstr where id=".$id;
    	$this->query($sql);

    }
    public function getData($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
    				->where('status',1)
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");

                    //echo $this->getLastQuery();
    	return $result;

    }
    public function getPipelineTypebyId($id)
    {


        $result=$this->db->table($this->table)
                    ->select('pipeline_type')
                    ->where('status',1)
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");

                  // echo $this->getLastQuery();
                return $result;

    }
    public function updateData(array $data)
    {

        $sql="update tbl_pipeline_type_mstr set pipeline_type='".$data['pipeline_type']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
        //echo $this->getLastQuery();
    }
    public function getPipelineType($id){
        try
        {        
            $builder = $this->db->table($this->table)
                        ->select('pipeline_type ')
                        ->where('id',$id)
                        ->where('status',1)
                        ->get();
                      // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['pipeline_type'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
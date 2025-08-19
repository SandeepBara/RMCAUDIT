<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConnectionTypeModel extends Model
{

    protected $table = 'tbl_connection_type_mstr';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function conn_type_list()
    {
        $client = new \Predis\Client();
        $water_conn_type_list = $client->get("water_conn_type_list");
        if (!$water_conn_type_list) {
           $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->get();
                    //echo $this->db->getLastQuery();exit;
            $water_conn_type_list = $builder->getResultArray();
            $client->set("water_conn_type_list", json_encode($water_conn_type_list));
            return $water_conn_type_list;
        } else {
            return json_decode($water_conn_type_list, true);
        }
    }

    public function checkdata($connection_type)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('connection_type',$connection_type)  
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
                // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function deleteData($id)
    {
    	$sql="delete from tbl_connection_type_mstr where id=".$id;
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
    public function getConnectionTypebyId($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");

                    //echo $this->getLastQuery();
        return $result;

    }


    public function updateData(array $data)
    {

        $sql="update tbl_connection_type_mstr set connection_type='".$data['connection_type']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
        //echo $this->getLastQuery();
    }
    public function getconnectionType($id){
        try
        {        
            $builder = $this->db->table($this->table)
                        ->select('connection_type ')
                        ->where('id',$id)
                        ->where('status',1)
                        ->get();
                      // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['connection_type'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
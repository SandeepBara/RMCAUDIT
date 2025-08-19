<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterPropertyModel extends Model
{
    protected $db;
    protected $table = 'tbl_property_type_mstr';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function property_list()
    {
        try{
            $client = new \Predis\Client();
            $water_property_list = $client->get("water_property_list");
            if (!$water_property_list) {
               $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('status',1)
                            ->where("id<=",8)
                            ->get();
                        //echo $this->db->getLastQuery();exit;
                $water_property_list = $builder->getResultArray();
                $client->set("water_property_list", json_encode($water_property_list));
                return $water_property_list;
            } else {
                return json_decode($water_property_list, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function checkdata($property_type)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('property_type',$property_type)  
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
    	$sql="delete from tbl_property_type_mstr where id=".$id;
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
    public function getPropertyTypebyId($id)
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

        $sql="update tbl_property_type_mstr set property_type='".$data['property_type']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
        //echo $this->getLastQuery();
    }

   
}
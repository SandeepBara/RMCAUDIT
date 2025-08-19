<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterFerruleTypeModel extends Model
{

    protected $table = 'tbl_ferrule_type_mstr';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function ferrule_type_list()
    {

        return $result=$this->db->table($this->table)
                                ->select("id,from_area,upto_area,ferrule_type")
                                ->where("status",1)
                                ->orderby("ferrule_type asc")
                                ->get()
                                ->getResultArray();
        
    }

    public function checkdata(array $data)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('from_area',$data['from_area'])  
                    ->where('upto_area',$data['upto_area'])  
                    ->where('ferrule_type',$data['ferrule_type'])   
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
          //  echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
        
    }
    public function deleteData($id)
    {
    	$sql="delete from tbl_ferrule_type_mstr where id=".$id;
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

                   // echo $this->getLastQuery();
    	return $result;

    }
    public function updateData(array $data)
    {

        $sql="update tbl_ferrule_type_mstr set from_area=".$data['from_area'].",upto_area=".$data['upto_area'].",emp_details_id=".$data['emp_details_id'].",created_on='".$data['created_on']."',ferrule_type=".$data['ferrule_type']." where md5(id::text)='".$data['id']."'";

        $this->query($sql);
      //  echo $this->getLastQuery();
    }
}
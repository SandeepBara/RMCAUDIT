<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterRoadAppartmentFeeModel extends Model
{

    protected $table = 'tbl_road_appartment_fee';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function road_app_fee_list()
    {

        return $result=$this->db->table($this->table)
                                ->select("id,road_fee,appartment_fee,effect_date")
                                ->where("status",1)
                                ->get()
                                ->getResultArray();
        
    }

    public function checkdata(array $data)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('effect_date',$data['effect_date'])  
                    ->where('road_fee',$data['road_fee'])  
                    ->where('appartment_fee',$data['appartment_fee'])   
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
    	$sql="delete from tbl_road_appartment_fee where id=".$id;
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

        $sql="update tbl_road_appartment_fee set road_fee=".$data['road_fee'].",appartment_fee=".$data['appartment_fee'].",emp_details_id=".$data['emp_details_id'].",created_on='".$data['created_on']."',effect_date='".$data['effect_date']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
        //  echo $this->getLastQuery();
    }
    public function getLastRow()
    {
        $sql="select * from tbl_road_appartment_fee where status=1 order by id desc limit 1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
    }

}
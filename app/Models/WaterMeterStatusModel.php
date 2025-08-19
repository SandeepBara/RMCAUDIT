<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterMeterStatusModel extends Model
{
    
    protected $table = 'tbl_meter_status';
    
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    
    
    
    public function insertData(array $data)
    {
    	$result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();die();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    
    public function meter_destroy_active_count($consumer_id)
    {

        $sql="select count(id) as count from tbl_meter_status where consumer_id=".$consumer_id." and status=1 and meter_ok_date is NULL";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;

    }

    public function getConnectionDetails($consumer_id)
    {

        $sql="select * from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 order by id desc";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
         
    }

    public function getAllConnectionDetails($consumer_id)
    {

        $sql="select * from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 order by id asc";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
         
    }
    public function getLastConnectionDetails($consumer_id)
    {
        $sql="select * from tbl_meter_status where (consumer_id)='".$consumer_id."' and status=1 order by id desc limit 1";
        $run=$this->db->query($sql);
       //echo $this->db->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    
    public function getNextConnectionDetails($consumer_id,$connection_id)
    {

        $sql="select * from tbl_meter_status where (consumer_id)='".$consumer_id."' and status=1 and id>$connection_id order by id asc limit 1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
        
    }
    public function getPreviousConnectionDetails($consumer_id,$connection_id)
    {

        $sql="select * from tbl_meter_status where (consumer_id)='".$consumer_id."' and status=1 and id<$connection_id order by id desc limit 1";
        $run=$this->db->query($sql);
        // echo $this->db->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
        
    }

    // check if same cnnection type exists before, while tc update the conn type if he neters same conn type then will not allow
    public function check_exists_same_connection_type_before($consumer_id)
    {

        $sql="select * from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 order by id desc limit 1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
         
    }

    public function updateMeterDocumentName($consumer_id,$meter_doc)
    {

        $sql="update tbl_meter_status set meter_doc='".$meter_doc."' where id='".$consumer_id."'";
        $run=$this->db->query($sql);
        
    }
    

    public function check_exists_connection_on_same_date($consumer_id,$connection_date)
    {
        $sql="select connection_date from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 and connection_date='".$connection_date."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['connection_date'];
        
    }
    
    public function getLastConnectionDetailsbyMd5($consumer_id)
    {
        $sql="select * from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 order by id desc limit 1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
        
    }
    
    public function getMeterDocUploadedofFirstConnection($consumer_id) 
    {
        $sql="select * from tbl_meter_status where md5(consumer_id::text)='".$consumer_id."' and status=1 order by id asc limit 1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function getMeterDocUploadedofFirstConnection2($consumer_id) 
    {
        $sql="select * from tbl_meter_status where consumer_id=".$consumer_id." and status=1 order by id asc limit 1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function updateMeterDetails($meter_id,$meter_no)
    {
        $sql="update tbl_meter_status set meter_no='".$meter_no."' where md5(id::text)='".$meter_id."'";
        return $run=$this->db->query($sql);
        // $this->getLastQuery();
    }

    public function updateMeterDetailsWithDate($meter_id, $meter_no, $meter_connection_date) {
        $sql = "UPDATE tbl_meter_status SET meter_no = '".$meter_no."', connection_date = '".$meter_connection_date."' WHERE md5(id::text) = '".$meter_id."'";
        
        return $run = $this->db->query($sql);
        // dd($this->getLastQuery());
    }

    public function updateMeterDocumentNamebyMd5($meter_id,$meter_doc)
    {
        $sql="update tbl_meter_status set meter_doc='".$meter_doc."' where md5(id::text)='".$meter_id."'";
        $run=$this->db->query($sql);
    }


}
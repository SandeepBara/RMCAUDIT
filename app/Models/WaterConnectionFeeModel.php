<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConnectionFeeModel extends Model
{

    protected $table = 'tbl_water_connection_fee_mstr';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function conn_fee_list()
    {

    	$sql="select tbl_water_connection_fee_mstr.id,category,effect_date,property_type,connection_type,connection_through,pipeline_type,proc_fee,sec_fee,app_fee,conn_fee,reg_fee

        from tbl_water_connection_fee_mstr join tbl_property_type_mstr on 
        tbl_property_type_mstr.id=tbl_water_connection_fee_mstr.property_type_id join tbl_pipeline_type_mstr 
        on tbl_pipeline_type_mstr.id=tbl_water_connection_fee_mstr.pipeline_type_id join tbl_connection_type_mstr 
        on tbl_connection_type_mstr.id=tbl_water_connection_fee_mstr.connection_type_id join tbl_connection_through_mstr 
        on tbl_connection_through_mstr.id=tbl_water_connection_fee_mstr.connection_through_id
        ";

        $run=$this->query($sql);
        $result=$run->getResultArray();

               //  echo $this->getLastQuery();
    	return $result;
    }

    public function checkdata(array $data)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('property_type_id',$data['property_type_id'])
                    ->where('pipeline_type_id',$data['pipeline_type_id'])
                    ->where('connection_type_id',$data['connection_type_id'])
                    ->where('connection_through_id',$data['connection_through_id'])
                    ->where('category',$data['category'])
                    ->where('effect_date',$data['effect_date'])  
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
    	$sql="delete from tbl_water_connection_fee_mstr where id=".$id;
    	$this->query($sql);

    }
    public function getData($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");

                    //echo $this->getLastQuery();
    	return $result;

    }
    public function updateData(array $data)
    {

        $sql="update tbl_water_connection_fee_mstr set property_type_id=".$data['property_type_id'].",pipeline_type_id=".$data['pipeline_type_id'].",connection_type_id=".$data['connection_type_id'].",connection_through_id=".$data['connection_through_id'].",category='".$data['category']."',reg_fee=".$data['reg_fee'].",proc_fee=".$data['proc_fee'].",app_fee=".$data['app_fee'].",sec_fee=".$data['sec_fee'].",conn_fee=".$data['conn_fee'].",effect_date='".$data['effect_date']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
    echo $this->getLastQuery();
    }
}
<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class waterConnectionReportModel extends Model 
{
    protected $db;
    protected $table = 'tbl_apply_water_connection';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function WaterConnection($where)
    {
    	$sql="select count(id) from tbl_apply_water_connection where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }


    public function wardWisewaterConnection($where,$viewWardWhere)
    {
    	$sql="select view_ward_mstr.id,ward_no,count(watercon.id) from view_ward_mstr left join 
        (select * from tbl_apply_water_connection where $where) as watercon
        on view_ward_mstr.id = watercon.ward_id where $viewWardWhere group by ward_no,view_ward_mstr.id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function waterConnectionDetailsByWard($where)
    {
    	$sql="select * from view_water_application_details 
        left join tbl_apply_water_connection 
        on view_water_application_details.id = tbl_apply_water_connection.id where $where and tbl_apply_water_connection.apply_from = 'TC' order by view_water_application_details.id desc";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }
}
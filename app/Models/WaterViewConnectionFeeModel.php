<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterViewConnectionFeeModel extends Model
{

    protected $table = 'tbl_apply_water_connection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function fetch_water_con_details($water_conn_id)
    {

       
        $sql="select * from tbl_apply_water_connection where md5(id::text)='".$water_conn_id."' and status in(1,2)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;

    }
    public function conn_fee_charge($water_conn_id)
    {
        
        $sql="select * from tbl_connection_charge where md5(apply_connection_id::text)='".$water_conn_id."' and paid_status=0 order by id desc";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->db->getLastQuery();

        return $result;

    }

    public function getPenaltyPaid($water_conn_id)
    {
        
        $sql="select coalesce(penalty,0) as penalty from tbl_connection_charge where md5(apply_connection_id::text)='".$water_conn_id."' and paid_status!=0";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();

        return $result['penalty'];
        
    }

    public function conn_fee_charge_details($rate_id)
    {
         $sql="select * from tbl_water_connection_fee_mstr where (id)='".$rate_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();
        return $result;
    }

    public function updatePadeToUnpadet($water_conn_id,$charge_for)
    {
        try{
            $this->db->table('tbl_connection_charge')
                    ->where('apply_connection_id',$water_conn_id)
                    ->where('charge_for',$charge_for)
                    ->update(['paid_status'=>0]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function updateUnpadeToPadet($water_conn_id,$charge_for)
    {
        try{
            $this->db->table('tbl_connection_charge')
                    ->where('apply_connection_id',$water_conn_id)
                    ->where('charge_for',$charge_for)
                    ->update(['paid_status'=>1]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateoUnpadetstatusApp($water_conn_id)
    {
        try{
            $this->db->table('tbl_apply_water_connection')
                    ->where('id',$water_conn_id)                    
                    ->update(['payment_status'=>0]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateoPadetstatusApp($water_conn_id)
    {
        try{
            $this->db->table('tbl_apply_water_connection')
                    ->where('id',$water_conn_id)                    
                    ->update(['payment_status'=>1]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    

}
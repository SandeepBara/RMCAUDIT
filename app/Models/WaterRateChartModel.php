<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterRateChartModel extends Model
{

    protected $table = 'tbl_fixed_meter_rate';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    
    
    public function rate_chart_list()
    {

    	$sql="select tbl_fixed_meter_rate.id,tbl_fixed_meter_rate.type,tbl_fixed_meter_rate.range_from,tbl_fixed_meter_rate.range_upto,tbl_fixed_meter_rate.amount,tbl_fixed_meter_rate.effective_date,tbl_property_type_mstr.property_type from tbl_fixed_meter_rate join tbl_property_type_mstr on tbl_property_type_mstr.id=tbl_fixed_meter_rate.property_type_id where tbl_fixed_meter_rate.status=1";

        $run=$this->query($sql);
        $result=$run->getResultArray();
    	return $result;
    }

    public function checkdata(array $data)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('type',$data['type'])  
                    ->where('property_type_id',$data['property_type_id'])  
                    ->where('range_from',$data['range_from'])  
                    ->where('range_upto',$data['range_upto'])  
                    ->where('amount',$data['amount'])   
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
    	$sql="delete from tbl_fixed_meter_rate where id=".$id;
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

        $sql="update tbl_fixed_meter_rate set type='".$data['type']."',property_type_id=".$data['property_type_id'].",range_from=".$data['range_from'].",range_upto=".$data['range_upto'].",emp_details_id=".$data['emp_details_id'].",created_on='".$data['created_on']."',amount=".$data['amount']." where md5(id::text)='".$data['id']."'";

        $this->query($sql);
      //  echo $this->getLastQuery();
    }
    public function getRate($connection_type,$property_type_id,$area_sqmt)
    {
        $sql="select * from tbl_fixed_meter_rate where type='$connection_type' and $area_sqmt>=range_from and $area_sqmt<=range_upto and status=1 and property_type_id=$property_type_id order by effective_date desc limit 1";
        $run=$this->db->query($sql);
       // echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;

    }

    public function SingleWindowgetFixdeRates()
    {
        $sql="select  tbl_fixed_meter_rate.range_from AS range_from,tbl_fixed_meter_rate.range_upto As range_to,
                     tbl_fixed_meter_rate.amount As rate,tbl_fixed_meter_rate.effective_date::TIMESTAMP AS effective_date,
                     tbl_property_type_mstr.property_type AS property_type
             from tbl_fixed_meter_rate 
             join tbl_property_type_mstr on tbl_property_type_mstr.id=tbl_fixed_meter_rate.property_type_id 
             where tbl_fixed_meter_rate.status=1 AND tbl_fixed_meter_rate.type = 'Fixed' 
             ORDER BY tbl_property_type_mstr.property_type,tbl_fixed_meter_rate.effective_date,
                tbl_fixed_meter_rate.range_from,tbl_fixed_meter_rate.id
            ";

        $run=$this->query($sql);
        $result=$run->getResultArray();
    	return $result;
    }

    public function SingleWindowgetMeterRates()
    {
        $sql="select tbl_meter_rate_chart.category,tbl_meter_rate_chart.from_unit AS range_from,tbl_meter_rate_chart.upto_unit As range_to,
                    (tbl_meter_rate_chart.amount*9) As rate,tbl_meter_rate_chart.effective_date::TIMESTAMP AS effective_date,
                    tbl_property_type_mstr.property_type AS property_type,tbl_property_type_mstr.id
            from tbl_meter_rate_chart 
            join tbl_property_type_mstr on tbl_property_type_mstr.id=tbl_meter_rate_chart.property_type_id 
            where tbl_meter_rate_chart.status=1 and tbl_meter_rate_chart.property_type_id not In(7,8)
            ORDER BY tbl_property_type_mstr.id,tbl_meter_rate_chart.category,tbl_meter_rate_chart.effective_date,
                tbl_meter_rate_chart.from_unit
            ";

            $run=$this->query($sql);
            $result=$run->getResultArray();
            return $result;
    }

    public function SingleWindowConnectionFeeOld()
    {
        $sql="select category AS category,
                effect_date::TIMESTAMP AS effective_date,
                property_type AS property_type,
                connection_type AS connection_type,
                connection_through AS connection_thru,
                pipeline_type AS pipe_type,
                proc_fee AS proc_fee,
                sec_fee AS sec_fee,
                app_fee AS app_fee,
                conn_fee AS conn_fee,
                reg_fee AS reg_fee
            from tbl_water_connection_fee_mstr 
            join tbl_property_type_mstr on tbl_property_type_mstr.id=tbl_water_connection_fee_mstr.property_type_id 
            join tbl_pipeline_type_mstr on tbl_pipeline_type_mstr.id=tbl_water_connection_fee_mstr.pipeline_type_id 
            join tbl_connection_type_mstr on tbl_connection_type_mstr.id=tbl_water_connection_fee_mstr.connection_type_id 
            join tbl_connection_through_mstr on tbl_connection_through_mstr.id=tbl_water_connection_fee_mstr.connection_through_id
        ";

        $run=$this->query($sql);
        $result=$run->getResultArray();
    	return $result;
    }

    public function SingleWindowConnectionFeeNew()
    {
        $sql ="
            select property_type AS property_type,
                tbl_revised_water_conn_fee_mstr.effective_date::TIMESTAMP AS effective_date,
                tbl_revised_water_conn_fee_mstr.area_from_sqft,
                tbl_revised_water_conn_fee_mstr.area_upto_sqft,
                tbl_revised_water_conn_fee_mstr.conn_fee,
                tbl_revised_water_conn_fee_mstr.calculation_type
            from tbl_revised_water_conn_fee_mstr
            join tbl_property_type_mstr on tbl_property_type_mstr.id=tbl_revised_water_conn_fee_mstr.property_type_id 
            order by tbl_revised_water_conn_fee_mstr.property_type_id,
                tbl_revised_water_conn_fee_mstr.area_from_sqft,
                tbl_revised_water_conn_fee_mstr.effective_date        
        ";
        $run=$this->query($sql);
        $result=$run->getResultArray();
    	return $result;
    }

}
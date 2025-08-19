<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_water_consumer extends Model
{
	protected $db;
    protected $table = 'tbl_consumer';
    protected $allowedFields = ['id', 'consumer_no', 'apply_connection_id', 'connection_type_id', 'connection_through_id', 'property_type_id', 'category', 'ward_mstr_id', 'prop_dtl_id', 'area_sqmt', 'area_sqft', 'pipeline_type_id', 'flat_count', 'k_no', 'bind_book_no', 'account_no', 'electric_category_type', 'emp_details_id', 'created_on', 'status'];

	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertconsumerdetbyconid($input)
    {
       
       $count="select count(*) as count
               from tbl_site_inspection 
               where status=1 and verified_by='AssistantEngineer' 
               and apply_connection_id='".$input['apply_connection_id']."'";
       $count = $this->db->query($count)
                         ->getFirstRow('array')['count'];        
       if($count==0)
       {
           $ae_insert_sql = "insert into tbl_site_inspection 
                           (apply_connection_id,property_type_id,pipeline_type_id,connection_type_id,
                           connection_through_id,category,flat_count,ward_id,
                           area_sqft,area_sqmt,rate_id,emp_details_id,status,payment_status,pipeline_size,
                           pipeline_size_type,pipe_size,pipe_type,ferrule_type_id,road_type,inspection_date,scheduled_status,
                           verified_by,road_app_fee_id,verified_status,created_on,inspection_time,ts_map,water_lock_arng,
                           gate_valve,order_officer)
                           
                           select apply_connection_id,property_type_id,pipeline_type_id,connection_type_id,
                           connection_through_id,category,flat_count,ward_id,
                           area_sqft,area_sqmt,rate_id,emp_details_id,status,payment_status,pipeline_size,
                           pipeline_size_type,pipe_size,pipe_type,ferrule_type_id,road_type,inspection_date,scheduled_status,
                           'AssistantEngineer' as verified_by ,road_app_fee_id,verified_status,created_on,inspection_time,ts_map,
                           water_lock_arng,gate_valve,order_officer
                           from tbl_site_inspection je 
                           where je.status=1 and je.verified_by='JuniorEngineer' and je.apply_connection_id='".$input['apply_connection_id']."'
                           order by id desc limit 1 ";
           $this->db->query($ae_insert_sql);
           
       }
      
       $sql_prop="insert into tbl_consumer(apply_connection_id, connection_type_id, connection_through_id, 
       property_type_id, category, ward_mstr_id, area_sqmt, area_sqft, pipeline_type_id, flat_count, k_no, 
       bind_book_no, account_no, electric_category_type, emp_details_id, created_on, status, 
       holding_no, prop_dtl_id,saf_no,saf_dtl_id,address, apply_from)
       select apply_connection_id, tbl_site_inspection.connection_type_id, tbl_site_inspection.connection_through_id, 
       tbl_site_inspection.property_type_id,  tbl_site_inspection.category, tbl_site_inspection.ward_id, tbl_site_inspection.area_sqmt, tbl_site_inspection.area_sqft, tbl_site_inspection.pipeline_type_id, tbl_site_inspection.flat_count, '".$input['k_no']."', 
       '".$input['bind_book_no']."', '".$input['account_no']."', '".$input['electric_category_type']."', '".$input['emp_details_id']."', '".$input['created_on']."', tbl_site_inspection.status, 
       holding_no,prop_dtl_id,saf_no, saf_dtl_id,address,apply_from 
       from tbl_site_inspection 
       join tbl_apply_water_connection on tbl_apply_water_connection.id=tbl_site_inspection.apply_connection_id 
       where apply_connection_id='".$input['apply_connection_id']."' and tbl_site_inspection.status=1 and verified_by='AssistantEngineer'";

       //  echo $sql_prop = "insert into tbl_consumer(apply_connection_id, connection_type_id, connection_through_id, property_type_id, category, 
       //  ward_mstr_id, prop_dtl_id, area_sqmt, area_sqft, pipeline_type_id, flat_count, k_no, bind_book_no, account_no, electric_category_type, 
       //  emp_details_id, created_on, status, consumer_no,prop_dtl_id,saf_no,saf_dtl_id,address,apply_from) 
       //  select apply_connection_id, connection_type_id, connection_through_id, property_type_id, category, ward_id, 
       //  '0', area_sqmt, area_sqft, pipeline_type_id, flat_count, '".$input['k_no']."', '".$input['bind_book_no']."', '".$input['account_no']."', '".$input['electric_category_type']."', '".$input['emp_details_id']."',
       //  '".$input['created_on']."', status, '".$input['consumer_no']."',
       //  holding_no,prop_dtl_id,saf_no,saf_dtl_id,address,apply_from 
       //  from tbl_site_inspection 
       //  join tbl_apply_water_connection on tbl_apply_water_connection.id=tbl_site_inspection.apply_connection_id
       //  where apply_connection_id='".$input['apply_connection_id']."' and status=1 and verified_by='AssistantEngineer'";
        $this->db->query($sql_prop);
       // echo $this->db->getLastQuery();
       $con_dtl_id = $this->db->insertID();
       if($con_dtl_id)
       {
           $this->db->table('tbl_apply_water_connection')
                   ->where('id',$input['apply_connection_id'])
                   ->update(['status'=>'5']);
       }
        return $con_dtl_id;
    }


     public function UpdateConsumerNo($consumer_id, $consumer_no)
     {
         $sql_prop = "update tbl_consumer set consumer_no='$consumer_no' where id=$consumer_id";
         $this->db->query($sql_prop);
     }

     public function insertData(array $data)
     {

        $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->db->getLastQuery();exit();
        $insert_id=$this->db->insertID();
        return $insert_id;

     }

    /*public function updateconsumernobyconid($consumer_id,$consumer_no){
         return $this->db->table($this->table)
                            ->where('id', $consumer_id)
                            ->update(
                                ['consumer_no'=>$consumer_no
                            ]);
    }*/

    public function consumerDetails($apply_connection_id){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('apply_connection_id',$apply_connection_id)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            $data = $builder->getResultArray();
            if(count($data)>0)
                return $data[0];
            else 
                return $data;
            
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function check_exists($apply_connection_id)
    {
        $sql="select count(id) as count from tbl_consumer where apply_connection_id=$apply_connection_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['count'];
        
    }
    public function consumerDetailsbyid($consumer_id){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('id',$consumer_id)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();

            $data = $builder->getResultArray();
            if(count($data)>0)
                return $data[0];
            else 
                return $data;
            //return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function consumerDetailsbymd5id($consumer_id){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('md5(id::text)',$consumer_id)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            $data = $builder->getResultArray();
            if(count($data)>0)
                return $data[0];
            else 
                return $data;
            //return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function count_ward_by_wardid($ward_mstr_id)
    {
        try{
            // return  $this->db->table($this->table)
            //             ->select('count(id) as ward_cnt')
            //             ->where('ward_mstr_id',$ward_mstr_id)
            //             ->get()
            //             ->getResultArray()[0];
            $builder=  $this->db->table($this->table)
                        ->select('count(id) as ward_cnt')
                        ->where('ward_mstr_id',$ward_mstr_id)
                        ->get();
                    
            $data = $builder->getResultArray();
            if(count($data)>0)
                return $data[0];
            else 
                return $data;
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function waterConsumerDetails($consumer_no){
        try{
             $builder=$this->db->table($this->table)
                           ->select('*')
                           ->like('upper(consumer_no)',$consumer_no)
                           ->where('status',1)
                           ->orwhere('status',2)
                           ->get();
                           //echo $this->db->getLastQuery();
                return $builder->getResultArray(); 
        }catch(Exception $e){
            echo $e->geMessage();
        } 
    }
    public function waterConsumerDetailsByConsumerNo($consumer_no){
        try{
             $builder=$this->db->table($this->table)
                           ->select('*')
                           ->where('md5(consumer_no::text)',$consumer_no)
                           ->where('status',1)
                           ->orwhere('status',2)
                           ->get();
                           //echo $this->db->getLastQuery();
            $data = $builder->getResultArray();
            if(count($data)>0)
                return $data[0];
            else 
                return $data;
                //return $builder->getResultArray()[0]; 
        }catch(Exception $e){
            echo $e->geMessage();
        } 
    }
    public function updateConsumerStatus($id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                      'status'=>0
                                    ]);
                            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function validate_consumer($ward_id,$consumer_no)
    {
         $sql="select id from tbl_consumer where ward_mstr_id=".$ward_id." and upper(consumer_no)='".$consumer_no."' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['id'];
        
    }
   

    public function GetWaterConsumerDetails($consumer_id)
    {
        $sql="select id from tbl_consumer_details where consumer_id=$consumer_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function cout_holding($holding_no)
    {
        $data=$this->db->table($this->table)
                ->select('count(id)')
                ->where('holding_no',$holding_no)
                ->where('status',1)
                ->get()
                ->getFirstRow('array');
                return $data['count']??null;
    }
    public function cout_saf($saf_no)
    {
        $data=$this->db->table($this->table)
                ->select('count(id)')
                ->where('saf_no',$saf_no)
                ->where('status',1)
                ->get()
                ->getFirstRow('array');
                return $data['count']??null;
    }
    public function updateNonMapHoldingNO($water_consumer_no, $holding_no, $prop_dtl_id)
    {
        try {
            $data = $this->db->table($this->table)
                ->set('holding_no', $holding_no)
                ->set('prop_dtl_id', $prop_dtl_id)
                ->where('consumer_no', $water_consumer_no)
                ->update();
                return true;
        }catch(Exception $e){
            log_message('error', 'Error updating holding number: ' . $e->getMessage());
            return false;
        }
    }
}
?>

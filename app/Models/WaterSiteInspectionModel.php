<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterSiteInspectionModel extends Model
{

    protected $table = 'tbl_site_inspection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    

    public function check_exists_verification_by_ae($apply_connection_id,$where=array())
    {
    	$result=$this->db->table($this->table)
    				->select('count(id) as count')
    				->where('status',1)
                    ->where('apply_connection_id',$apply_connection_id) 
                    ->where('verified_by','AssistantEngineer');
        if(!empty($where))
        {
            $result=$result->where($where);
        } 
    	$result=$result->get()
    			->getFirstRow("array");

		//echo ($result['id']);
		// echo $this->db->getLastQuery();die();
    	return $result['count'];
    }


    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
                echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function update_level_pending_status($apply_water_conn_id,$user_type_mstr_id)
    {

        return $builder=$this->db->table("tbl_level_pending")
                                ->where('apply_connection_id',$apply_water_conn_id)
                                ->where('receiver_user_type_id',13)
                                ->where('verification_status',0)
                                ->update([
                                    "verification_status"=>1
                                ]);

                              //  echo $this->getLastQuery();


    }
    public function checkdata($apply_water_conn_id)
    {
        $sql = " SELECT count(id) 
        FROM tbl_site_inspection 
        WHERE apply_connection_id = $apply_water_conn_id AND status = 1 AND scheduled_status = 1 
        AND verified_by = 'JuniorEngineer' AND verified_status isnull ";
        $result = $this -> db->query($sql)->getFirstRow('array');

        // $result=$this->db->table($this->table)
        //             ->select('count(id)')
        //             ->where('apply_connection_id',$apply_water_conn_id)
        //             ->where('status',1)
        //             ->where('scheduled_status',1)
        //             ->where("verified_by",'JuniorEngineer')
        //             ->where("verified_status ",'isNULL')  
        //             ->get()
        //             ->getFirstRow("array");

                    //echo ($result['id']);
            //   echo $this->getLastQuery();
        return $result['count'];
    }
    public function get_si_id($apply_water_conn_id)
    {
        $sql = " SELECT id 
                FROM tbl_site_inspection 
                WHERE apply_connection_id = $apply_water_conn_id AND status = 1 AND scheduled_status = 1 
                AND verified_by = 'JuniorEngineer' AND verified_status isnull 
                order by id desc ";
        $result = $this -> db->query($sql)->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result['id'];
    }
    public function check_exists(array $data)
    {
        $result=$this->db->table("tbl_level_pending")
                    ->selectCount('id')
                    ->where('verification_status',0)
                    ->where('receiver_user_type_id',$data['receiver_user_type_id'])
                    ->where('apply_connection_id',$data['apply_connection_id'])  
                    ->get()
                    ->getFirstRow("array");

                    //echo ($result['id']);
              //echo $this->getLastQuery();
        return $result['id'];
    }
    public function insert_level_pending(array $data)
    {


        $result= $this->db->table("tbl_level_pending")
                 ->insert($data);       
                // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    public function insert_level_pending_last_id($apply_water_conn_id)
    {


        $result= $this->db->table("tbl_level_pending")
                 ->select('id')
                 ->where('apply_connection_id',$apply_water_conn_id)
                 ->orderBy('id','desc')  
                 ->get()
                 ->getFirstRow('array');    
                // echo $this->getLastQuery();        
        return $result['id'];
    }
   
    public function getData($site_inspection_id)
    {
    	$result=$this->db->table("view_site_inspection_details")
    				->select('*')
    				->where('status',1)
                    ->where('md5(id::text)',$site_inspection_id)
    				->get()
    				->getFirstRow("array");

                    //echo $this->getLastQuery();
    	return $result;

    }

    public function insertTechnicalSection(array $data,$where=array())
    {
        
        $sql="insert into tbl_site_inspection(property_type_id,pipeline_type_id,connection_type_id,connection_through_id,category,flat_count,ward_id,area_sqft,area_sqmt,rate_id ,emp_details_id,created_on,status,apply_connection_id ,payment_status ,  pipeline_size,pipeline_size_type ,pipe_size,pipe_type ,
    ferrule_type_id ,road_type,inspection_date ,inspection_time ,scheduled_status,water_lock_arng,gate_valve,verified_by)

    select property_type_id,pipeline_type_id,connection_type_id,connection_through_id,category,flat_count,ward_id,area_sqft,area_sqmt,rate_id ,".$data['emp_details_id'].",created_on,status,apply_connection_id ,payment_status ,  '".$data['pipeline_size']."',pipeline_size_type ,'".$data['pipe_size']."' ,pipe_type ,
    ".$data['ferrule_type_id']." ,road_type,'".date('Y-m-d')."' ,'".date('H:i:s')."' ,scheduled_status,'".$data['water_lock_arng']."','".$data['gate_valve']."','AssistantEngineer'  from tbl_site_inspection where apply_connection_id=".$data['apply_connection_id']." and verified_by='JuniorEngineer' and status=1 
        
        ";
        if(!empty($where))
        {
            foreach($where as $key=>$val)
            {
                $sql.=(' and '.$key."=".$val.' ');
            }
        }
        $sql.='returning id as insert_id';
        $this->db->query($sql);
        //echo $this->db->getLastQuery(); die();

        return $result=$this->db->insertID();

    }

    public function updateTechnicalSectionDetails(array $data,$where=array())
    {
        
        
        $sql="update tbl_site_inspection set

                property_type_id=subquery.property_type_id,pipeline_type_id=subquery.pipeline_type_id,connection_type_id=subquery.connection_type_id,
                connection_through_id=subquery.connection_through_id,category=subquery.category,flat_count=subquery.flat_count,ward_id=subquery.ward_id,area_sqft=subquery.area_sqft,area_sqmt=subquery.area_sqmt,
                rate_id=subquery.rate_id ,emp_details_id=".$data['emp_details_id'].",created_on='".date('Y-m-d H:i:s')."',status=subquery.status,apply_connection_id=subquery.apply_connection_id,payment_status=subquery.payment_status ,  pipeline_size='".$data['pipeline_size']."',pipeline_size_type=subquery.property_type_id,pipe_size='".$data['pipe_size']."',pipe_type=subquery.pipe_type,
    ferrule_type_id =".$data['ferrule_type_id'].",road_type=subquery.road_type,inspection_date='".date('Y-m-d')."' ,inspection_time='".date("H:i:s")."',scheduled_status=subquery.scheduled_status,water_lock_arng='".$data['water_lock_arng']."',gate_valve='".$data['gate_valve']."',verified_by='AssistantEngineer'

            
         from 

            (select property_type_id,pipeline_type_id,connection_type_id,connection_through_id,category,flat_count,ward_id,area_sqft,area_sqmt,rate_id ,emp_details_id,'".date('Y-m-d H:i:s')."',status,apply_connection_id ,payment_status ,  pipeline_size,pipeline_size_type ,pipe_size,pipe_type ,
    ferrule_type_id ,road_type,inspection_date ,inspection_time ,scheduled_status,water_lock_arng,gate_valve,verified_by from tbl_site_inspection where apply_connection_id=".$data['apply_connection_id']." and verified_by='JuniorEngineer' and status=1)
            as subquery where tbl_site_inspection.apply_connection_id=".$data['apply_connection_id']." and tbl_site_inspection.verified_by='AssistantEngineer'";

            if(!empty($where))
            {
                foreach($where as $key => $val)
                {
                    $sql.=(' and '.$key.'='.$val.' ');
                } 
            }
           $run= $this->db->query($sql);
            //echo $this->db->getLastQuery();die();

       
    }

    public function getSiteInspectionDetailsbyJE($apply_connection_id,$where=array())
    {
        $result=$this->db->table("view_site_inspection_details")
                    ->select('*')
                    ->where('status', 1)
                    ->where('md5(apply_connection_id::text)', $apply_connection_id)
                    ->where('verified_by', 'JuniorEngineer');
        if(!empty($where))
        {
            $result = $result->where($where);
        }

        $result=$result->orderBy('id','DESC')
                    ->get()
                    ->getFirstRow("array");

                  //  echo $this->getLastQuery();
        return $result;

    }

    public function getSiteInspectionDetailsbyJENew($apply_connection_id,$where=array())
    {
        $result=$this->db->table("view_site_inspection_details_new")
                    ->select('*')
                    ->where('status', 1)
                    ->where('md5(apply_connection_id::text)', $apply_connection_id)
                    ->where('verified_by', 'JuniorEngineer');
        if(!empty($where))
        {
            $result = $result->where($where);
        }

        $result=$result->orderBy('id','DESC')
                    ->get()
                    ->getFirstRow("array");

                  //  echo $this->getLastQuery();
        return $result;

    }
    
    public function getSiteInspectionDetailsbyAE($apply_connection_id,$where=array())
    {
        $result=$this->db->table("view_site_inspection_details")
                    ->select('*')
                    ->where('status', 1)
                    ->where('md5(apply_connection_id::text)', $apply_connection_id)
                    ->where('verified_by', 'AssistantEngineer');
        if(!empty($where))
            $result = $result->where($where);
        $result=$result->orderBy('id','DESC')
                    ->get()
                    ->getFirstRow("array");

                  //  echo $this->getLastQuery();
        return $result;

    }

    
    public function application_site_inspection_payment_detls($apply_connection_id)
    {
        
        $result=$this->db->table("view_site_inspection_details")
                    ->selectCount('id')
                    ->where('status',1)
                    ->where('payment_status',0)
                    ->where('md5(apply_connection_id::text)',$apply_connection_id)
                    ->get()
                    ->getFirstRow("array");

                    //echo $this->getLastQuery();
        return $result['id'];
    }
    public function update_site_ins_pay_status($water_conn_id)
    {
        return $result=$this->db->table($this->table)
                                ->where("apply_connection_id",$water_conn_id)
                                ->update(["payment_status"=>1]);
    }

   

	public function SI_date_time($apply_connection_id,$where=array())
    {
		try{
            $builder = $this->db->table($this->table)
                    ->select('inspection_date,inspection_time')
                    ->where('scheduled_status',1)
                    ->where('apply_connection_id',$apply_connection_id);
                    if(!empty($where))
                    {
                        $builder = $builder ->where($where) ;
                    }
                    $builder=$builder->orderBy('id','desc');
                    $builder=$builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
	
	
	public function si_verify_dtls($apply_connection_id)
    {
		try{
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('scheduled_status',1)
                    ->where('md5(apply_connection_id::text)',$apply_connection_id)
                    ->where('verified_by','JuniorEngineer')
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->get();
                   //echo $this->db->getLastQuery();
             //$data = $builder->getResultArray()[0];echo"hearrrrrrrr ";print_r($data);
            $data = $builder->getFirstRow('array');
            return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
	public function checkPaymentDone($apply_connection_id,$where=array())
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('count(id) as count')
                    ->where('payment_status',1)
                    ->where('md5(apply_connection_id::text)',$apply_connection_id)
                    ->where('verified_by','JuniorEngineer')
                    ->where('status',1);
            if(!empty($where))
                $builder = $builder->where($where);

            $builder=$builder->get();
                  echo $this->db->getLastQuery();
            $result= $builder->getFirstRow("array");
            return $result['count'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    
	
	public function SI_date_timeins($input){
		$result = $this->db->table($this->table)->
			insert([
				  "emp_details_id"=>$input["user_id"],
				  "apply_connection_id"=>$input['related_id'],
				  "inspection_date"=>$input["date"],
				  "inspection_time"=>$input["time"],
				  "scheduled_status"=>1,
                  "verified_by"=>'JuniorEngineer'
				  ]);
	}
	
	public function SI_date_timeupdt($input,$where=array()){
		 $builder=$this->db->table($this->table)
                                ->where('apply_connection_id',$input['apply_connection_id'])
                                ->where('verified_by','JuniorEngineer')
                                ->where('status',1);
            if(!empty($where))
            {
                $builder=$builder->where($where);
            }

              $data=$builder->update([
                                    "property_type_id"=>$input["property_type_id"],
                                    "pipeline_type_id"=>$input["pipeline_type_id"],
                                    "connection_type_id"=>$input["connection_type_id"],
                                    "connection_through_id"=>$input['connection_through_id'],
                                    "category"=>$input["category"],
                                    "flat_count"=>$input["flat_count"],
									"area_sqft"=>$input["area_sqft"],
                                    "area_sqmt"=>$input['area_sqmt'],
									"rate_id"=>$input['rate_id'],
                                    "payment_status"=>$input["payment_status"],
                                    "pipeline_size"=>$input["pipeline_size"],
									"pipeline_size_type"=>$input["pipeline_size_type"],
									"pipe_size"=>$input["pipe_size"],
									"pipe_type"=>$input["pipe_type"],
									"ferrule_type_id"=>$input["ferrule_type_id"],
									"road_type"=>$input["road_type"],
                                    "road_app_fee_id"=>$input["road_app_fee_id"],
                                    "verified_status"=>1,
                                    "created_on"=>$input["created_on"],
                                    "ward_id"=>$input['ward_id'],
                                    "ts_map"=>$input['ts_map']??null,

                                ]);
                                //print_r($where);
                                //echo $this->db->getLastQuery();die();
            return $data;
	}
	
	
	public function SI_date_timecancel($input,$where=array()){
        
		 $builder=$this->db->table($this->table,$water=array())
                                ->where('apply_connection_id',$input['related_id'])
                                ->where('verified_by','JuniorEngineer')
                                ->where('status',1);
            if(!empty($where))
            {
                $builder=$builder->where($where);
            }

             return $builder->update([
                                    "scheduled_status"=>0,
                                    "status"=>0
                                ]);
	}


    public function siteInspectionDetailsReport($where,$user_type=null)
    {
        if(!$user_type)
            $user_type = "JuniorEngineer";

        $sql="select * from view_site_inspection_details where $where and verified_by='$user_type' ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }

    public function getAllRecords($apply_connection_id)
    {
        return $this->db->table($this->table)
                                ->select('id, apply_connection_id, verified_by, inspection_date,emp_details_id')
                                ->where('apply_connection_id', $apply_connection_id)
                                ->where('status', 1)
                                ->orderBy('id','ASC')
                                ->get()
                                ->getResultArray();
            
    }

    public function updateRmarks($where=array(),$data=array())
    {

        try{
            $data = $this->db->table($this->table)
                    ->where('status',1)
                    ->where($where)
                    ->update($data);
                   //echo $this->db->getLastQuery();
             //print_r($data);
            
            return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function tbl_ae_meter_inspection_insert($data)
    {
        try{
            $this->db->table('tbl_ae_meter_inspection')
                    ->insert($data);
                    
            
            return $this->db->insertID();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function tbl_ae_meter_inspection_update($where,$data)
    {
        try{
            $data = $this->db->table('tbl_ae_meter_inspection')
                    ->where($where)
                    ->update($data);                    
            
            return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function tbl_ae_meter_inspection_get($where)
    {
        try{
            $data = $this->db->table('tbl_ae_meter_inspection')
                    ->select('*')
                    ->where($where)
                    ->orderBy('id','desc')
                    ->get()
                    ->getFirstRow("array");
            return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    
}
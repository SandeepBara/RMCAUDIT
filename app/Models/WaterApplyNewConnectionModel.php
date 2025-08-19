<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use Exception;

class WaterApplyNewConnectionModel extends Model
{

    protected $table = 'tbl_apply_water_connection';
    protected $obj_sit;
    protected $conn_fee;

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
        $this->obj_sit = new WaterSiteInspectionModel($db);
        $this->conn_fee=new WaterViewConnectionFeeModel($db);

        
	}

    public function validate_application($ward_id,$application_no)
    {
        

        $sql="select id from tbl_apply_water_connection where ward_id=".$ward_id." and upper(application_no)='".$application_no."' and status in(1,2)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['id'];
        
        
    }
   
    public function GetWaterApplicantDetails($apply_connection_id)
    {
        $sql="select * from tbl_applicant_details where apply_connection_id=$apply_connection_id and status=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function check_holding_exists($prop_id)
    {
        
        return $result['id']=$this->db->table($this->table)
                        ->select("count(id) as count_prop")
                        ->where("prop_dtl_id",$prop_id)
                        ->whereIn("status",[1,2])
                        ->get()
                        ->getFirstRow("array");

    }
    public function check_saf_exists($saf_id)
    {
         $result=$this->db->table($this->table)
                        ->select("count(id) as count_saf")
                        ->where("saf_dtl_id",$saf_id)
                        ->whereIn("status",[1,2])
                        ->get()
                        ->getFirstRow("array");
        return $result['count_saf'];
    }
    public function check_owner_holding_water_conn($prop_id)
    {
      
          $sql="select count(id) as count_prop from tbl_apply_water_connection where prop_dtl_id=$prop_id and status in(1,2) and owner_type='OWNER'";
          $run=$this->db->query($sql);
          $result=$run->getFirstRow("array");
          //echo $this->getLastQuery();
          return $result;



    }
    
    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function insert_owner(array $data)
    {

        $result= $this->db->table("tbl_applicant_details")
                 ->insert($data);       
         //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function delet_owner($apply_connection_id)
    {


        $result= $this->db->table("tbl_applicant_details") 
                            ->where('apply_connection_id',$apply_connection_id)
                            ->delete();                   
         echo $this->getLastQuery();
       
        return $result;

    }
    public function insert_conn_fee(array $data)
    {


        $result= $this->db->table("tbl_connection_charge")
                 ->insert($data);       
         // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function delet_conn_fee($apply_connection_id)
    {


        $result= $this->db->table("tbl_connection_charge") 
                            ->where('apply_connection_id',$apply_connection_id)
                            ->delete();                   
        //  echo $this->getLastQuery();
       
        return $result;

    }

    public function get_rate_id($pipeline_type_id,$property_type_id,$connection_through_id,$connection_type_id,$category)
    {


        /*  $result=$this->db->table("tbl_water_connection_fee_mstr")
                            ->select("*")
                            ->where('property_type_id', $data['property_type_id'])
                            ->where('pipeline_type_id', $data['pipeline_type_id'])
                            ->where('connection_type_id', $data['connection_type_id'])
                            ->where('connection_through_id', $data['connection_through_id'])
                            ->where('category', $data['category'])

                            ->get()
                            ->getFirstRow("array");*/


        $sql="select * from tbl_water_connection_fee_mstr where property_type_id=".$property_type_id." and pipeline_type_id=".$pipeline_type_id." and connection_type_id=".$connection_type_id." and connection_through_id=".$connection_through_id." and category='".$category."' and status=1 order by id desc ";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();
       // print_r($result);
       return $result;

    }

    public function getNewRateId($property_type_id, $where)
    {

        $sql="select * from tbl_revised_water_conn_fee_mstr where property_type_id=$property_type_id $where order by effective_date desc ";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;

    }
    public function getData($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
    				//->where('status',1)
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");

                   // echo $this->getLastQuery();
    	return $result;

    }
    /*public function getApplicationNo($id)
    {
        $result=$this->db->table($this->table)
                    ->select('application_no')
                    //->where('status',1)
                    ->where('md5(id::text)',$id)
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->getLastQuery();
        return $result['application_no'];

    }*/

    public function getConsumerNo($id)
    {
        $result=$this->db->table("tbl_consumer")
                    ->select('consumer_no')
                    //->where('status',1)
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->getLastQuery();
        return $result['consumer_no'];

    }
    public function water_con_list($from_date,$to_date,$ward_permission)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function wardwise_water_con_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();
           
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function update_doc_status($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_connection_id'])
                            ->update([
                                    'doc_status'=> 1
                                    ]);
    }

    public function water_conn_details($insert_id)
    {
        
        $sql="select * from view_water_application_details where md5(id::text)='".$insert_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->db->getLastQuery();
        //print_var($result);exit;
        return $result;

    }

    
    /*public function update_application_no($application_no,$payment_status,$road_app_fee_id,$insert_id)
    {

        $sql="update tbl_apply_water_connection set application_no='".$application_no."',payment_status=".$payment_status.",road_app_fee_id=".$road_app_fee_id." where id=".$insert_id;
        $run=$this->db->query($sql);
        // echo $this->db->getLastQuery();

    }*/

    public function update_application_no($application_no,$payment_status,$insert_id)
    {

        $sql="update tbl_apply_water_connection set application_no='".$application_no."',payment_status=".$payment_status." where id=".$insert_id;
        $run=$this->db->query($sql);
        // echo $this->db->getLastQuery();

    }

    public function water_owner_details($water_conn_id)
    {
        $sql="select tbl_applicant_details.id, tbl_applicant_details.applicant_name,tbl_applicant_details.father_name,tbl_applicant_details.mobile_no,tbl_applicant_details.email_id , tbl_applicant_details.city, tbl_applicant_details.district, tbl_applicant_details.state 
            from view_water_application_details 
            join tbl_applicant_details on tbl_applicant_details.apply_connection_id=view_water_application_details.id 
            where md5(view_water_application_details.id::text)='".$water_conn_id."'";
        
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function water_application_status($water_conn_id)
    {
        $sql="select doc_status,payment_status,status from view_water_application_details where md5(id::text)='".$water_conn_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;

    }
   /* public function level_pending_details($water_conn_id)
    {
        $sql="select user_type,verification_status,receiver_user_type_id from tbl_level_pending join view_emp_details on view_emp_details.user_type_id=tbl_level_pending.receiver_user_type_id where md5(apply_connection_id::text)='".$water_conn_id."' and verification_status in(0,2) order by tbl_level_pending.id desc";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
    // echo $this->getLastQuery();
        return $result;
    }
    */
    public function level_pending_details($water_conn_id)
    {
        $sql="select * from tbl_level_pending where status=1 and md5(apply_connection_id::text)='$water_conn_id' order by id desc";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }
    
    public function update_level_pending_status($input,$status=null)
    {    $builder='';
        if($status)
        {
            $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_connection_id'])
                            ->update([
                                        'level_pending_status'=> $input['level_pending_status'],                                        
                                        'doc_verify_emp_details_id'=> $input['doc_verify_emp_details_id']??null,
                                    ]);
        }
        else
        {
            $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_connection_id'])
                            ->update([
                                        'level_pending_status'=> $input['level_pending_status'],
                                        'doc_verify_status'=> $input['doc_verify_status']??null,
                                        'doc_verify_date'=> "NOW()",
                                        'doc_verify_emp_details_id'=> $input['doc_verify_emp_details_id']??null,
                                    ]);
        }
        
        // echo $this->db->getLastQuery(); die();
        return $builder;
    }

    public function updateLevelPendingStatus($input)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_connection_id'])
                            ->update([
                                        'level_pending_status'=> $input['level_pending_status'],
                                    ]);
        //echo $this->db->getLastQuery();
        return $builder;
    }

     public function update_verify_status($input)
     {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_connection_id'])
                            ->update([
                                    'doc_verify_status'=>$input['doc_verify_status'],
                                    'doc_verify_date'=>$input['doc_verify_date'],
                                    'doc_verify_emp_details_id'=>$input['doc_verify_emp_details_id']
                                    ]);
    }
    public function bo_backtocitizen_list($from_date,$to_date,$ward_permission)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        ->where('level_pending_status', 2)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwisebo_backtocitizen_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('level_pending_status', 2)
                        ->where('ward_id', $ward_mstr_id)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function watercon_details_md5($apply_connection_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $apply_connection_id)
                        ->get();
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function boc_saf_list($from_date,$to_date,$ward_permission)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        ->where('level_pending_status', 2)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwiseboc_saf_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('level_pending_status', 2)
                        ->where('ward_id', $ward_mstr_id)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllData($data){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('apply_date >=',$data['from_date'])
                     ->where('apply_date <=',$data['to_date'])
                     ->where('ward_id',$data['ward_mstr_id'])
                     ->whereIn('status',[1,2])
                     ->get();
                     //echo $this->db->getLastQuery();
                return $builder->getResultArray();     
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllDataBydate($data){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('apply_date >=',$data['from_date'])
                     ->where('apply_date <=',$data['to_date'])
                     ->whereIn('status',[1,2])
                     ->get();
                     //echo $this->db->getLastQuery();
                return $builder->getResultArray();     
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllNewConnection($application_no){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('upper(application_no)', $application_no)
                     ->where('status', 2)
                     ->get();
                     /*echo $this->db->getLastQuery();*/
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function newConnectionDetailsById($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('md5(id::text)',$id)
                     //->where('status', 2)
                     ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllNewConnectionByApplicationNo($application_no){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('md5(application_no::text)',$application_no)
                     ->where('status',2)
                     ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateNewApplyConnectionStatus($id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    "status"=>0
                                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateApplyNewConnectionPaymentStatus($id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    "payment_status"=>0
                                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateApplyNewConnectionPaymentStatusClear($id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    "payment_status"=>1
                                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getApplyConnectionDetails($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('id',$id)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getApplicationNo($id){
        try{        
             $builder = $this->db->table($this->table)
                        ->select('application_no')
                        ->where('id',$id)
                        ->where('status>', 1)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["application_no"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getApplyConnectionDetailForDeactivation($id,$statusData){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('id',$id)
                      ->whereIn('status',$statusData)
                      ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getDataNew($where = array(),$columan=array('*'),$tbl='',$orderBy=array('id'=>'desc'))
    {   
        $data = array();
        try{
                if($tbl!='')
                    $builder = $this->db->table($tbl);
                else
                    $builder = $this->db->table($this->table);
                $builder=$builder->select($columan);

                if(count($where)!=0)
                {
                    $builder = $builder->where($where);
                    
                    
                }
                foreach($orderBy as $key=>$val)
                {
                    $builder = $builder->orderBy($key,$val);
                }
                $data = $builder->get()->getResultArray();
                if(sizeof($data)==1)
                    $data=$data[0];
                
                //print_r($this->db);
                //echo $this->db->getLastQuery();
                return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getDataRowQuery($sql)
    {
        $run=$this->db->query($sql);        
        $result=$run->getResultArray();
        return $result;
    }

    public function application_status($insert_id)
    {
        $get_application_status=$this->water_application_status($insert_id);
        $get_level_pending_dtls=$this->level_pending_details($insert_id);
        //print_r($get_level_pending_dtls);
        $count_site_inspec_diff_pay=$this->obj_sit->application_site_inspection_payment_detls($insert_id);
        $conn_charge = $this->conn_fee->conn_fee_charge($insert_id);

        $app_status=$get_application_status['status'];
        $doc_status=$get_application_status['doc_status'];
        $payment_status=$get_application_status['payment_status'];
        $status='';

       

        if(!empty($conn_charge ))
        {
            $status="Payment Is Due For ".$conn_charge['charge_for'];
        }
        /*  
        else if($doc_status==0 and $payment_status==2)
        {
            $status="Document Not Uploaded and  Payment Done but not cleared";
        }
        else if($doc_status!=0 and $payment_status==0)
        {
            $status="Document Uploaded but Payment Not Done";
        }
        else if(($doc_status==1 or $doc_status==0) and $payment_status==2)
        {
            $status="Document Uploaded and Payment Done but Not Cleared";
        }

        */
        if($app_status==0)
        {
            $status="Application Is Deactivated";
        }

        if($doc_status==0 && $app_status!=0)
        {
            if($payment_status==0)
            {
                $status="Document Not Uploaded and Payment Not Done";
            }
            else if($payment_status==1)
            {
                $status="Document Not Uploaded but Payment Done";
            }
            else if($payment_status==2)
            {
                $status="Document Not Uploaded but Payment Done but Cheque not cleared";
            }
        }
        else if($doc_status==1 and $payment_status!=1 && $app_status!=0)
        {
            if($payment_status==0)
            {
                $status="Document Uploaded but Payment Not Done";
            }
            else if($payment_status==2)
            {
                $status="Document Uploaded and Payment Done but Cheque not cleared";
            }
        }
        if($doc_status==1 and $payment_status==1 && $app_status!=0)
        {	 	
            if($get_level_pending_dtls)
            {  
                $receiver_id=$get_level_pending_dtls['receiver_user_type_id'];
                $verification_status=$get_level_pending_dtls['verification_status'];
               /* $receiver_id=$get_level_pending_dtls['user_type'];
                if($get_level_pending_dtls['verification_status']==0)
                {
                    $status="Pending at ".$receiver_id;
                }
                else if($get_level_pending_dtls['verification_status']==2 and $get_level_pending_dtls['receiver_user_type_id']==12)
                {
                    $status="Sent Back to Citizen by ".$receiver_id;
                }*/
                if($verification_status==0)
                {	
                    if($receiver_id==12)
                    {
                        $status="Pending at Dealing Officer";
                    }
                    else if($receiver_id==13)
                    {
                        if($count_site_inspec_diff_pay>0)
                        {
                            $status="Payment Pending of Diff Amount at Site Inspection";
                        }
                        else
                        {
                            $status="Pending at Junior Engineer";
                        }
                    }
                    else if($receiver_id==14)
                    {
                        $status="Pending at Section Head";
                    }
                    else if($receiver_id==15)
                    {
                        $status="Pending at Assistant Engineer";
                    }
                    else if($receiver_id==16)
                    {
                        $status="Pending at Executive Officer";
                    }
                }
                else if($verification_status==2 and $receiver_id==12)
                {
                    $status="Sent Back to Citizen by Dealing Officer";
                }
                else if($verification_status==2 and $receiver_id==13)
                {
                    $status="Sent Back to Citizen by Junior Engineer";
                }
                else if($verification_status==2 and $receiver_id==14)
                {
                    $status="Sent Back to Citizen by Section Head";
                }
                else if($verification_status==2 and $receiver_id==15)
                {
                    $status="Sent Back to Citizen by Assistant Engineer";
                }
                else if($verification_status==2 and $receiver_id==16)
                {
                    $status="Sent Back to Citizen by Executive Officer";
                }

                else if($verification_status==4 and $receiver_id==12)
                {
                    $status="Application Rejected by Dealing Officer";
                }
                else if($verification_status==4 and $receiver_id==13)
                {
                    $status="Application Rejected by Junior Engineer";
                }
                else if($verification_status==4 and $receiver_id==14)
                {
                    $status="Application Rejected by Section Head";
                }
                else if($verification_status==4 and $receiver_id==15)
                {
                    $status="Application Rejected by Assistant Engineer";
                }
                else if($verification_status==4 and $receiver_id==16)
                {
                    $status="Application Rejected by Executive Officer";
                }

                else if($verification_status==1 and $receiver_id==16)
                {
                    $status="Approved by Executive Officer";
                }
            }
            else
            {
                $status='Payment Is Don And Document Uploaded';
            }
        }
        return $status;
    }


    public function check_holding_exists_another($apply_connection_id,$holding_no)
    {
        try{
            $count = $this->db->table($this->table)
                            ->select('count(*)')
                            ->where('id <>',$apply_connection_id)
                            ->where('holding_no',$holding_no)
                            ->where('status <>',0)
                            ->get()
                            ->getFirstRow('array');
            return $count['count'];
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }

    public function check_saf_exists_another($apply_connection_id,$saf_no)
    {
        try{
            $count = $this->db->table($this->table)
                            ->select('count(*)')
                            ->where('id <>',$apply_connection_id)
                            ->where('holding_no',$saf_no)
                            ->where('status <>',0)
                            ->get()
                            ->getFirstRow('array');
            return $count['count'];
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }

    public function update_application ($apply_connection_id,$inputs)
    {
        //print_var($this->table);die;
        try{
            $result = $this->db->table($this->table)
                            ->where('id', $apply_connection_id)
                            ->update($inputs);
            //echo $this->db->getLastQuery();die;
            return $result;
           

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }

    public function update_owner($applicant_id,$inputs)
    {
        try{
            $data = $this->db->table('tbl_applicant_details')
                            ->where('id',$applicant_id)
                            ->update($inputs);
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }

    public function BPL_transection($conn_id,$payment_mode,$emp_id=null)
    {
        $sql =" insert into tbl_transaction (ward_mstr_id,transaction_type,transaction_date,related_id,
        payment_mode,penalty,rebate,paid_amount".(!empty($emp_id)?",emp_details_id":'').",status,total_amount,created_on)
        select ward_id,'New Connection',now()::date,$conn_id,
        '$payment_mode', 0,0,0".(!empty($emp_id)?",$emp_id":'').", 1 as status, 0,now()
        from tbl_apply_water_connection where id = $conn_id ";
        $this->db->query($sql);
        $trans_id = $this->db->insertID(); 
        
        $trans_no="WTRAN".$trans_id.date('YmdHis');
       $update ="update tbl_transaction set transaction_no = '$trans_no' where id = $trans_id";
        $this->db->query($update);
    }

    public function getPropetyIdByNewHolding($holding_no)
    {
        $sql="select md5(id::text) as id ,id as prop_id,prop_address,ward_no
                from view_prop_detail
                where  upper(new_holding_no) = upper('".$holding_no."')";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result;

    }
    public function getPropertyIdByOldHolding($holding_no)
    {
        $sql="select md5(id::text) as id ,id as prop_id,new_holding_no,prop_address,ward_no
                from view_prop_detail
                where  upper(holding_no) = upper('".$holding_no."')";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        return $result;
    }

    public function getSafIdBySafNo($saf_no)
    {
        $sql="select id,prop_address,ward_no
            from view_saf_detail
            where  upper(saf_no) = upper('".$saf_no."')";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result;

    }

}
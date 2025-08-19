<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_level_pending_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_level_pending_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id','sender_user_type_id','receiver_user_type_id','forward_date','forward_time', 'created_on','remarks','verification_status', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input) {
        try{
            $data = $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery().'<br />';
            return $data;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function dl_remarks_by_saf_id($saf_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',6)
                        //->where('verification_status ',0)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function backtocitizen_dl_remarks_by_saf_id($saf_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',0)
                        ->where('receiver_user_type_id',6)
                        ->where('verification_status ',2)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function forward_remarks_by_saf_id($saf_dtl_id,$sender_user_type_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function ulbtc_remarks_by_saf_id($saf_dtl_id,$receiver_user_type_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',7)
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('verification_status ',0)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function ulbtc_remarks_si_by_saf_id($saf_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',7)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status ',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function sectioninchrg_remarks_by_saf_id($saf_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',9)
                        ->where('verification_status',0)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status ',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function updatebacktocitizenById($input){
        return $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['level_pending_dtl_id'])
                            ->update([
                                    'remarks'=> $input['remarks'],
                                    'verification_status'=> $input['verification_status'],
                                    "sender_emp_details_id"=> $input['sender_emp_details_id'],
                                    ]);
    }
    public function updatesisafById($input)
    {
        $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['level_pending_dtl_id'])
                            ->update([
                                        'verification_status'=> $input['verification_status'],
                                        'receiver_emp_details_id'=> $input['receiver_emp_details_id'],
                                        'status'=> $input['status']

                                    ]);
        //echo $this->db->getLastQuery();
        //print_var($builder);
        return $builder;
    }
    public function updatebacktocitizensisafById($input){
        return $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['level_pending_dtl_id'])
                            ->update([
                                        'remarks'=>$input['remarks'],
                                        'verification_status'=> $input['verification_status']
                                    ]);
    }

    public function updatelevelpendingById($input)
    {
        $update_data=array("verification_status"=> $input['verification_status']);
        if(isset($input["status"]))
            $update_data["status"]=$input["status"];
        // if(isset($input["remarks"]))
        //     $update_data["remarks"]=$input["remarks"];
        $update_data["receiver_emp_details_id"]=$input["receiver_emp_details_id"];

        $builder = $this->db->table($this->table);
        if(is_numeric($input['level_pending_dtl_id']))
        {
            $builder= $builder->where('id', $input['level_pending_dtl_id']);
        }
        else
        {                                
            $builder= $builder->where('md5(id::text)', $input['level_pending_dtl_id']);
        }
        $builder= $builder->update($update_data);
        //echo $this->db->getLastQuery();die;
        return $builder;

    }



    /*public function getproptaxId($prop_dtl_id){
        $sql = "SELECT id FROM tbl_prop_tax WHERE prop_dtl_id='$prop_dtl_id'";
        $q = $this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();

        return $result;
    }*/

    public function insrtlevelpendingdtl($input){
		//print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  "forward_date"=> $input["forward_date"],
                  "forward_time"=> $input["forward_time"],
                  "remarks"=> $input["remarks"],
                  "created_on"=> $input["created_on"],
                  "sender_emp_details_id"=> $input["sender_emp_details_id"]
				  ]);
				  //echo $this->db->getLastQuery();
			//$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function insrtSILevelFinaldtl($input){
        //print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  "forward_date"=> $input["forward_date"],
                  "forward_time"=> $input["forward_time"],
                  "remarks"=> $input["remarks"],
                  "verification_status"=> $input["verification_status"],
                  "status"=> $input["status"],
                  "created_on"=> $input["created_on"],
                  "sender_emp_details_id"=> $input["sender_emp_details_id"],
                  "receiver_emp_details_id"=> $input["receiver_emp_details_id"]
                  ]);
                  //echo $this->db->getLastQuery();
            //$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    
    public function record_back_to_citizen($level_record){
		//print_r($input);
        $builder = $this->db->table('tbl_level_sent_back_dtl')
                ->insert([
                    'saf_dtl_id' => $level_record["saf_dtl_id"],
                    'level_pending_dtl_id' => $level_record["level_pending_dtl_id"],
                    'sender_user_type_id' => $level_record["sender_user_type_id"],
                    'sender_emp_details_id' => $level_record["sender_emp_details_id"],
                    'sender_ip_address' => $level_record["sender_ip_address"],
                    'remarks' =>$level_record["remarks"],
                    'status' => $level_record["status"]
				  ]);
				  //echo $this->db->getLastQuery();
			//$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insrtFinalEo($input){
		//print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  "forward_date"=> $input["forward_date"],
                  "forward_time"=> $input["forward_time"],
                  "remarks"=> $input["remarks"],
                  "created_on"=> $input["created_on"],
                  "status"=> 0,
                  'verification_status' => 1,
                  "sender_emp_details_id"=> $input["sender_emp_details_id"]
				  ]);
				  //echo $this->db->getLastQuery();
			//$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function insrtFinalEo_new($input){
		//print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  "forward_date"=> $input["forward_date"],
                  "forward_time"=> $input["forward_time"],
                  "remarks"=> $input["remarks"],
                  "created_on"=> $input["created_on"],
                  "status"=> $input["status"],
                  'verification_status' => $input["final_verification_status"],
                  "sender_emp_details_id"=> $input["sender_emp_details_id"]
				  ]);
				  //echo $this->db->getLastQuery();
			//$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    
    public function getDealingAssistantStatus($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status')
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->where('receiver_user_type_id',6)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   //echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAgencyTcStatus($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status')
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->where('receiver_user_type_id',5)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getUlbTcStatus($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status')
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->where('receiver_user_type_id',7)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getSectionInchargeStatus($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status')
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->where('receiver_user_type_id',9)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getExecutiveOfficerStatus($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status')
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->where('receiver_user_type_id',10)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }


	public function field_verification($from_date,$to_date){
		$sql = "SELECT count(id) as total_field_verify_id, (SELECT count(id) as total_final_approval_id
			    FROM tbl_level_pending_dtl WHERE verification_status=1 AND receiver_user_type_id=10)
			    FROM tbl_level_pending_dtl
			    WHERE verification_status=1 AND receiver_user_type_id=7 AND created_on::date BETWEEN '".$from_date."' and '".$to_date."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;

    }
	/*
	public function final_approval(){
		$sql = "SELECT count(id) as total_final_approval_id
			    FROM tbl_level_pending_dtl
			    WHERE verification_status=1 AND receiver_user_type_id=10";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;

    }
	*/
	public function msglevelPending($data)
    {

    	try{
            $builder = $this->db->table($this->table)
                        ->select('receiver_user_type_id')
                        ->where('status', 1)
						->where('verification_status', 0)
                        ->where('md5(saf_dtl_id::text)', $data['id'])
						->orderBy('id','DESC')
                        ->get();
						//echo $this->db->getLastQuery();
           return $builder->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getAllLevelVerificationListBySafDtlId($input)
    {
    	try
        {
            $builder = $this->db->table('tbl_level_pending_dtl')
                        ->select('
                            tbl_level_pending_dtl.id as id,
                            tbl_level_pending_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_level_pending_dtl.sender_user_type_id as sender_user_type_id,
                            view_user_type_mstr.user_type as sender_user_type,
                            tbl_level_pending_dtl.receiver_user_type_id as receiver_user_type_id,
                            tbl_level_pending_dtl.forward_date as forward_date,
                            tbl_level_pending_dtl.forward_time as forward_time,
                            tbl_level_pending_dtl.created_on as created_on,
                            tbl_level_pending_dtl.status as status,
                            tbl_level_pending_dtl.remarks as remarks,
                            tbl_level_pending_dtl.verification_status as verification_status'
                        )
                        ->join('view_user_type_mstr', 'view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id')
                        ->where('view_user_type_mstr.status', 1)
                        ->where('view_user_type_mstr.status', 1)
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->get();
			//echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

	public function hide_rmc_btn($data)
    {
    	try{
            $builder = $this->db->table($this->table)
                        ->select('verification_status')
                        ->where('status', 1)
                        ->where('saf_dtl_id', $data)
						->orderBy('id','DESC')
                        ->get();
						//echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function updateverfystatusDocUpload($input){
		//print_r($input);
        return $builder = $this->db->table($this->table)
						->where('saf_dtl_id', $input['saf_id'])
						->update([
								'verification_status'=>3
							]);
    }





	public function gewardlevelatc($ward_id)
    {
        $sql="SELECT count(tbl_level_pending_dtl.id) as no_of_app, tbl_level_pending_dtl.receiver_user_type_id
		FROM public.tbl_level_pending_dtl
		left join tbl_saf_dtl on tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
		where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and tbl_saf_dtl.ward_mstr_id=".$ward_id."
		group by tbl_level_pending_dtl.receiver_user_type_id=5";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	public function gewardlevelda($ward_id)
    {
        $sql="SELECT count(tbl_level_pending_dtl.id) as no_of_app, tbl_level_pending_dtl.receiver_user_type_id
		FROM public.tbl_level_pending_dtl
		left join tbl_saf_dtl on tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
		where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and tbl_saf_dtl.ward_mstr_id=".$ward_id."
		group by tbl_level_pending_dtl.receiver_user_type_id=6";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	public function gewardlevelutc($ward_id)
    {
        $sql="SELECT count(tbl_level_pending_dtl.id) as no_of_app, tbl_level_pending_dtl.receiver_user_type_id
		FROM public.tbl_level_pending_dtl
		left join tbl_saf_dtl on tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
		where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and tbl_saf_dtl.ward_mstr_id=".$ward_id."
		group by tbl_level_pending_dtl.receiver_user_type_id=7";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	public function gewardlevelsi($ward_id)
    {
        $sql="SELECT count(tbl_level_pending_dtl.id) as no_of_app, tbl_level_pending_dtl.receiver_user_type_id
		FROM public.tbl_level_pending_dtl
		left join tbl_saf_dtl on tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
		where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and tbl_saf_dtl.ward_mstr_id=".$ward_id."
		group by tbl_level_pending_dtl.receiver_user_type_id=9";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	public function gewardleveleo($ward_id)
    {
        $sql="SELECT count(tbl_level_pending_dtl.id) as no_of_app, tbl_level_pending_dtl.receiver_user_type_id
		FROM public.tbl_level_pending_dtl
		left join tbl_saf_dtl on tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
		where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and tbl_saf_dtl.ward_mstr_id=".$ward_id."
		group by tbl_level_pending_dtl.receiver_user_type_id=10";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getlevelatc()
    {
        $sql="SELECT count(id) as no_of_app
			FROM tbl_level_pending_dtl where status=1 and verification_status=0 and receiver_user_type_id=5";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getlevelda()
    {
        $sql="SELECT count(id) as no_of_app
			FROM tbl_level_pending_dtl where status=1 and verification_status=0 and receiver_user_type_id=6";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getlevelutc()
    {
        $sql="SELECT count(id) as no_of_app
			FROM tbl_level_pending_dtl where status=1 and verification_status=0 and receiver_user_type_id=7";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getlevelsi()
    {
        $sql="SELECT count(id) as no_of_app
			FROM tbl_level_pending_dtl where status=1 and verification_status=0 and receiver_user_type_id=9";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getleveleo()
    {
        $sql="SELECT count(id) as no_of_app
			FROM tbl_level_pending_dtl where status=1 and verification_status=0 and receiver_user_type_id=10";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }

	public function getLastRecord($input)
    {
        $sql="SELECT * FROM tbl_level_pending_dtl where saf_dtl_id=$input[saf_dtl_id] and status=1 and verification_status=0 and receiver_user_type_id=$input[receiver_user_type_id]";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function getLastBkctznRecord($input)
    {
        $sql="SELECT * FROM tbl_level_pending_dtl where md5(saf_dtl_id::text)='$input[id]' and status=1 and verification_status=2";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }
    public function getLastBkctznRecordwithLevelId($input)
    {
        $sql="SELECT * FROM tbl_level_pending_dtl where md5(id::text)='$input[id]' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

	public function getlevelwiseform() {
        $sql="SELECT 
                    COUNT(DISTINCT saf_dtl_id) AS levelform , 
                    receiver_user_type_id , 
                    user_type
                FROM tbl_level_pending_dtl
                JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id
                JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                WHERE 
                    tbl_level_pending_dtl.status=1
                    AND tbl_level_pending_dtl.receiver_user_type_id NOT IN (7,11)
                    AND tbl_level_pending_dtl.verification_status=0 
                    AND view_user_type_mstr.status=1
                    AND tbl_saf_dtl.saf_pending_status=0 
                    AND tbl_saf_dtl.status=1
                GROUP BY receiver_user_type_id, user_type
                ORDER BY receiver_user_type_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function getlevelwiseformUTC() {
        $sql="SELECT 
                    COUNT(DISTINCT saf_dtl_id) AS levelform , 
                    receiver_user_type_id , 
                    user_type
                FROM tbl_level_pending_dtl
                JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id
                JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                JOIN (
					SELECT
						geotag_dtl_id
					FROM tbl_saf_geotag_upload_dtl
					WHERE status=1
					GROUP BY geotag_dtl_id
				) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                WHERE 
                    tbl_level_pending_dtl.status=1
                    AND tbl_level_pending_dtl.receiver_user_type_id=7
                    AND tbl_level_pending_dtl.verification_status=0 
                    AND view_user_type_mstr.status=1
                    AND tbl_saf_dtl.saf_pending_status=0 
                    AND tbl_saf_dtl.status=1
                GROUP BY receiver_user_type_id, user_type
                ORDER BY receiver_user_type_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function getlevelwiseformbackOffice() {
        $sql="SELECT 
                    COUNT(DISTINCT saf_dtl_id) AS levelform , 
                    receiver_user_type_id , 
                    user_type
                FROM tbl_level_pending_dtl
                JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id
                JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                WHERE 
                    tbl_level_pending_dtl.status=1
                    AND tbl_level_pending_dtl.verification_status=2
                    AND view_user_type_mstr.status=1
                    AND tbl_saf_dtl.status=1
                    AND tbl_level_pending_dtl.receiver_user_type_id=11
                GROUP BY receiver_user_type_id, user_type
                ORDER BY receiver_user_type_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function DeactivateAll($saf_dtl_id)
    {
        return $this->db->table($this->table)
                        ->where("saf_dtl_id", $saf_dtl_id)
                        ->update(["verification_status"=> 1, "status"=> 0]);
    }

    public function sendBackToCitizen($input)
    {
        $builder= $this->db->table($this->table)
                        ->where("id", $input["id"])
                        ->where("saf_dtl_id", $input["saf_dtl_id"])
                        ->where("status", 1)
                        ->where("verification_status", 0)
                        ->update(
                            [
                                "sender_user_type_id"=> $input["sender_user_type_id"],
                                "receiver_user_type_id"=> $input["receiver_user_type_id"],
                                "verification_status"=> 2, //backtocitizen
                                "remarks"=> $input["remarks"],
                                "status"=> 1,
                                "forward_date"=> "NOW()",
                                "forward_time"=> "NOW()",
                                'sender_emp_details_id' => $input['sender_emp_details_id'],
                            ]
                        );
        //echo $this->db->getLastQuery();
        return $builder;
    }

    public function BackToCitizenToULB($input)
    {
        return $this->db->table($this->table)
                        ->where("id", $input["id"])
                        ->where("saf_dtl_id", $input["saf_dtl_id"])
                        ->where("status", 1)
                        ->where("verification_status", 2)
                        ->update(
                            [
                                "sender_user_type_id"=> $input["sender_user_type_id"],
                                "receiver_user_type_id"=> $input["receiver_user_type_id"],
                                "verification_status"=> 0, //not verified
                                "remarks"=> $input["remarks"],
                                "status"=> 1,
                                "forward_date"=> "NOW()",
                                "forward_time"=> "NOW()",
                                'sender_emp_details_id' => $input['sender_emp_details_id'],
                            ]
                        );
    }

    public function updateLastRecord($input)
    {
        return $this->db->table($this->table)
                        ->where("id", $input["id"])
                        ->where("saf_dtl_id", $input["saf_dtl_id"])
                        ->where("status", 1)
                        ->where("verification_status", 0)
                        ->update(
                            [
                                "status"=> 0,
                                "verification_status"=> 1,
                                //"remarks"=> $input["remarks"],
                                //"forward_date"=> "NOW()",
                                //"forward_time"=> "NOW()",
                                "receiver_emp_details_id"=> $input['receiver_emp_details_id']
                            ]
                        );
    }
	
	// Added By Shashi Start
	public function bugfix_level_pending($input)
    {
        $this->db->table('tbl_bugfix_level_pending_dtl')
                        ->where("saf_dtl_id", $input["saf_dtl_id"])
                        ->where("status", 1)
                        ->where("verification_status", 0)
                        ->update(
                            [
                                "status"=> 0,
                                "verification_status"=> 1,
                                "receiver_emp_details_id"=> $input['sender_emp_details_id']
                            ]
                        );

        $levelolddata = [
            'saf_dtl_id' => $input["saf_dtl_id"],
            'sender_user_type_id' => $input["sender_user_type_id"],     
            'receiver_user_type_id' => $input["receiver_user_type_id"], 
            'forward_date' => $input["forward_date"],
            'forward_time' => $input["forward_time"],
            'created_on' => $input["created_on"],
            'status' => isset($input["eo_verify"])?$input["eo_verify"]:1,
            'verification_status' => isset($input["final_verification_status"])?$input["final_verification_status"]:0,
            'remarks' => $input["remarks"],
            'sender_emp_details_id' => $input["sender_emp_details_id"]
        ];
        $this->db->table('tbl_bugfix_level_pending_dtl')->insert($levelolddata);
        
    }

    public function bugfix_level_pending_new($input)
    {
        $this->db->table('tbl_bugfix_level_pending_dtl')
                        ->where("saf_dtl_id", $input["saf_dtl_id"])
                        ->where("status", 1)
                        ->where("verification_status", 0)
                        ->update(
                            [
                                "status"=> 0,
                                "verification_status"=> 1,
                                "receiver_emp_details_id"=> $input['sender_emp_details_id']
                            ]
                        );

        $levelolddata = [
            'saf_dtl_id' => $input["saf_dtl_id"],
            'sender_user_type_id' => $input["sender_user_type_id"],     
            'receiver_user_type_id' => $input["receiver_user_type_id"], 
            'forward_date' => $input["forward_date"],
            'forward_time' => $input["forward_time"],
            'created_on' => $input["created_on"],
            'status' => isset($input["eo_verify"])?$input["eo_verify"]:$input["status"],
            'verification_status' => isset($input["final_verification_status"])?$input["final_verification_status"]:$input["verification_status"],
            'remarks' => $input["remarks"],
            'sender_emp_details_id' => $input["sender_emp_details_id"]
        ];
        $this->db->table('tbl_bugfix_level_pending_dtl')->insert($levelolddata);
        
    }
	// Added By Shashi end

    public function getAllRecords($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                        ->select('tbl_level_pending_dtl.*, user_type')
                        ->join('view_user_type_mstr', 'view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id')
                        ->where("saf_dtl_id", $saf_dtl_id)
                        ->orderBy("id")
                        ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray('array');
    }

    public function getAllLevelDtl($saf_dtl_id)
    {
        $sql = "SELECT
                  tbl_level_pending_dtl.id,
                  tbl_level_pending_dtl.saf_dtl_id,
                  tbl_level_pending_dtl.sender_user_type_id,
                  tbl_level_pending_dtl.forward_date,
                  tbl_level_pending_dtl.forward_time,
                  tbl_level_pending_dtl.created_on,
                  tbl_level_pending_dtl.status,
                  tbl_level_pending_dtl.remarks,
                  tbl_level_pending_dtl.status,
                  tbl_level_pending_dtl.verification_status,
                  tbl_level_pending_dtl.sender_emp_details_id	,
                  view_user_type_mstr.user_type, view_emp_details.emp_name
                  FROM (
                      SELECT
                      tbl_level_pending_dtl.id,
                      tbl_level_pending_dtl.saf_dtl_id,
                      tbl_level_pending_dtl.sender_user_type_id,
                      tbl_level_pending_dtl.forward_date,
                      tbl_level_pending_dtl.forward_time,
                      tbl_level_pending_dtl.created_on,
                      tbl_level_pending_dtl.status,
                      tbl_level_pending_dtl.remarks,
                      tbl_level_pending_dtl.verification_status,
                      tbl_level_pending_dtl.sender_emp_details_id
                  FROM tbl_level_pending_dtl WHERE saf_dtl_id = '".$saf_dtl_id."'
                  UNION ALL
                  SELECT
                      tbl_level_sent_back_dtl.id,
                      tbl_level_sent_back_dtl.saf_dtl_id,
                      tbl_level_sent_back_dtl.sender_user_type_id,
                      tbl_level_sent_back_dtl.created_on::DATE AS forward_date,
                      tbl_level_sent_back_dtl.created_on::TIME AS forward_time,
                      tbl_level_sent_back_dtl.created_on,
                      tbl_level_sent_back_dtl.status,
                      tbl_level_sent_back_dtl.remarks,
                      1 AS verification_status,
                      tbl_level_sent_back_dtl.sender_emp_details_id
                  FROM tbl_level_sent_back_dtl WHERE saf_dtl_id = '".$saf_dtl_id."'
                  ORDER BY created_on
              )  AS tbl_level_pending_dtl
              JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
              LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending_dtl.sender_emp_details_id
              ORDER BY tbl_level_pending_dtl.created_on";
        //echo $this->db->getLastQuery();
        return $this->db->query($sql)->getResultArray();
    }
    public function getAllLevelDtl2($saf_dtl_id)
    {
        $sql ="SELECT 
            tbl_level_pending_dtl.id, 
            tbl_level_pending_dtl.saf_dtl_id, 
            tbl_level_pending_dtl.sender_user_type_id,  
            tbl_level_pending_dtl.receiver_user_type_id,  
            tbl_level_pending_dtl.forward_date, 
            tbl_level_pending_dtl.forward_time, 
            LAG(forward_date) OVER (ORDER BY forward_date) AS prev_date,
            forward_date- LAG(forward_date) OVER (ORDER BY forward_date) AS date_difference,
            tbl_level_pending_dtl.created_on, 
            tbl_level_pending_dtl.status, 
            tbl_level_pending_dtl.remarks, 
            tbl_level_pending_dtl.status,
            tbl_level_pending_dtl.verification_status,
            tbl_level_pending_dtl.sender_emp_details_id	, 
            view_user_type_mstr.user_type, view_emp_details.emp_name
            FROM (
                SELECT
                tbl_bugfix_level_pending_dtl.id,
                tbl_bugfix_level_pending_dtl.saf_dtl_id, 
                tbl_bugfix_level_pending_dtl.sender_user_type_id, 
                tbl_bugfix_level_pending_dtl.receiver_user_type_id, 
                tbl_bugfix_level_pending_dtl.forward_date, 
                tbl_bugfix_level_pending_dtl.forward_time, 
                tbl_bugfix_level_pending_dtl.created_on, 
                tbl_bugfix_level_pending_dtl.status, 
                tbl_bugfix_level_pending_dtl.remarks, 
                tbl_bugfix_level_pending_dtl.verification_status,
                tbl_bugfix_level_pending_dtl.sender_emp_details_id
            FROM tbl_bugfix_level_pending_dtl WHERE saf_dtl_id = ".$saf_dtl_id."
        )  AS tbl_level_pending_dtl
        JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending_dtl.sender_emp_details_id
        ORDER BY forward_date ASC, tbl_level_pending_dtl.created_on ASC";
        return $this->db->query($sql)->getResultArray();
    }

    public function levelwisecount($whereDateRange,$to_date){
        $totalsaf="select count(*) from tbl_saf_dtl where tbl_saf_dtl.status = '1' AND tbl_saf_dtl.payment_status= '1' $whereDateRange";
        $totalsaf=$this->db->query($totalsaf)->getResultArray();
        $data['totalsaf']=$totalsaf[0]['count'];

        $dealingsql="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform,receiver_user_type_id,user_type FROM tbl_level_pending_dtl
                        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                        WHERE tbl_level_pending_dtl.status=1
                         AND tbl_level_pending_dtl.verification_status=0 AND view_user_type_mstr.status=1
                         AND tbl_saf_dtl.saf_pending_status=0 AND tbl_saf_dtl.status=1
                     AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=6 
                   $whereDateRange GROUP BY receiver_user_type_id, user_type";
        $dealingpendingtotalsaf=$this->db->query($dealingsql)->getResultArray();
        $data['dealing_pending']=$dealingpendingtotalsaf[0]['pending_levelform']??"0";

        $dealingdonesql="SELECT COUNT(DISTINCT saf_dtl_id) AS done_levelform,sender_user_type_id,user_type FROM tbl_level_pending_dtl
                     JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.sender_user_type_id
                     JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE tbl_level_pending_dtl.status IN (0,1) 
                     AND tbl_level_pending_dtl.receiver_user_type_id NOT IN (11) AND tbl_level_pending_dtl.verification_status IN (0,1) 
                     AND view_user_type_mstr.status=1 AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1
                     AND sender_user_type_id=6 $whereDateRange GROUP BY sender_user_type_id, user_type;";
        $dealingdonetotalsaf=$this->db->query($dealingdonesql)->getResultArray();
        $data['dealing_done']=$dealingdonetotalsaf[0]['done_levelform']??"0";

        $bapendingsqlselect="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform,receiver_user_type_id,user_type";
        $bapendingsql=" FROM tbl_level_pending_dtl
        JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id 
                        WHERE tbl_level_pending_dtl.status=1 
                         AND tbl_level_pending_dtl.verification_status IN (1,2) AND view_user_type_mstr.status=1
                          AND tbl_saf_dtl.status=1
                     AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=11 
                    $whereDateRange ";
        $bapendingsql1=$bapendingsqlselect.$bapendingsql."GROUP BY receiver_user_type_id, user_type";
        $pendingbasql="SELECT count(distinct tbl_saf_dtl.id) FROM tbl_saf_dtl LEFT JOIN tbl_level_pending_dtl on
         tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id where tbl_saf_dtl.status = '1' 
                        --RIGHT join tbl_transaction txn on txn.prop_dtl_id=tbl_saf_dtl.id
                        --WHERE txn.status=1 AND txn.tran_type='Saf' AND txn.tran_date<'".$to_date."'
                         $whereDateRange
                        AND tbl_saf_dtl.payment_status='1' AND tbl_level_pending_dtl.saf_dtl_id IS NULL";
        $pendingba_total=$this->db->query($pendingbasql)->getResultArray();
        $pendingbatotal=$pendingba_total[0]['count']??0;
        $bapendingtotalsaf=$this->db->query($bapendingsql1)->getResultArray();
        $data['batca_pending']=($bapendingtotalsaf[0]['pending_levelform']??"0")+$pendingbatotal;
        //dd($pendingbatotal,$data['batca_pending']);
        // $badonesql="Select count(*) AS done_levelform from (SELECT DISTINCT saf_dtl_id AS done_levelform,sender_user_type_id,user_type FROM tbl_level_pending_dtl
        //              JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.sender_user_type_id
        //              JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE tbl_level_pending_dtl.status IN (0,1)
        //              AND tbl_level_pending_dtl.verification_status IN (0,1)
        //              AND view_user_type_mstr.status=1 AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1
        //              AND sender_user_type_id=11 $whereDateRange GROUP BY sender_user_type_id, user_type,saf_dtl_id
        //               EXCEPT SELECT DISTINCT saf_dtl_id AS pending_levelform,receiver_user_type_id,user_type".$bapendingsql."GROUP BY
        //               sender_user_type_id, user_type,saf_dtl_id)";
        $badonesql="Select count(*) AS done_levelform from (SELECT DISTINCT saf_dtl_id AS done_levelform
        FROM tbl_level_pending_dtl
        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.sender_user_type_id
        JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE tbl_level_pending_dtl.status IN (0,1)
        AND tbl_level_pending_dtl.verification_status IN (0,1)
        AND view_user_type_mstr.status=1 AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1
        AND sender_user_type_id=11 $whereDateRange 
        GROUP BY sender_user_type_id, user_type,saf_dtl_id
        EXCEPT SELECT DISTINCT saf_dtl_id AS pending_levelform 
        FROM tbl_level_pending_dtl JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id 
        JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
        WHERE tbl_level_pending_dtl.status=1 
        AND tbl_level_pending_dtl.verification_status IN (1,2) AND view_user_type_mstr.status=1
        AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=11 
        $whereDateRange 
        GROUP BY sender_user_type_id, user_type,saf_dtl_id) as tbl_level_pending_dtl                      
        ";
        $badonesqldonetotalsaf=$this->db->query($badonesql)->getResultArray();
        $data['batca_done']=$badonesqldonetotalsaf[0]['done_levelform']??0;
        //dd($data['batca_done'],$badonesqldonetotalsaf);

        $tcapendingsql="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform from
(select distinct tbl_saf_dtl.id as saf_dtl_id from
 tbl_saf_dtl left join tbl_saf_geotag_upload_dtl geotag_dtl on 
geotag_dtl.geotag_dtl_id = tbl_saf_dtl.id Where tbl_saf_dtl.status=1
AND tbl_saf_dtl.saf_pending_status IN (1) AND tbl_saf_dtl.payment_status=1 $whereDateRange AND 
geotag_dtl.geotag_dtl_id IS NULL UNION
SELECT DISTINCT saf_dtl_id FROM tbl_bugfix_level_pending_dtl 
JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_bugfix_level_pending_dtl.receiver_user_type_id 
JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_bugfix_level_pending_dtl.saf_dtl_id 
WHERE tbl_bugfix_level_pending_dtl.status=1 AND tbl_bugfix_level_pending_dtl.verification_status=0 
AND view_user_type_mstr.status=1 AND tbl_saf_dtl.status=1 
AND tbl_saf_dtl.saf_pending_status IN (0,1) AND tbl_saf_dtl.payment_status=1
 AND tbl_bugfix_level_pending_dtl.receiver_user_type_id=5 $whereDateRange 
GROUP BY saf_dtl_id) as tcapending";
        $tcapendingtotalsaf=$this->db->query($tcapendingsql)->getResultArray();
        $data['tca_pending']=$tcapendingtotalsaf[0]['pending_levelform']??0;

        $tcadonesql="select count(*) as done_levelform from (select distinct tbl_saf_dtl.id from tbl_saf_dtl 
            left join tbl_level_pending_dtl on tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
            where tbl_saf_dtl.status=1 $whereDateRange  
                AND sender_user_type_id=5 
                AND receiver_user_type_id IN (7,5)
                AND tbl_level_pending_dtl.status=0
                and tbl_saf_dtl.payment_status=1
            union
            select distinct tbl_saf_dtl.id from tbl_saf_dtl 
            left join tbl_bugfix_level_pending_dtl on tbl_bugfix_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
            where tbl_saf_dtl.status=1 $whereDateRange
            AND sender_user_type_id=5 
            AND receiver_user_type_id IN (7,5)
            AND tbl_bugfix_level_pending_dtl.status in (0,1)
            and tbl_saf_dtl.payment_status=1) as tcapaneding 
            JOIN (
            SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id
    ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tcapaneding.id";
        $tcadonesqldonetotalsaf=$this->db->query($tcadonesql)->getResultArray();
        $data['tca_done']=$tcadonesqldonetotalsaf[0]['done_levelform']??0;

        $ulbpendingsql="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform,receiver_user_type_id,user_type FROM tbl_bugfix_level_pending_dtl 
                        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_bugfix_level_pending_dtl.receiver_user_type_id JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_bugfix_level_pending_dtl.saf_dtl_id
                        WHERE tbl_bugfix_level_pending_dtl.status=1 
                         AND tbl_bugfix_level_pending_dtl.verification_status=0 AND view_user_type_mstr.status=1
                          AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status=0
                     AND tbl_saf_dtl.payment_status=1 AND tbl_bugfix_level_pending_dtl.receiver_user_type_id=7 
                    $whereDateRange GROUP BY receiver_user_type_id, user_type";
        $ulbpendingtotalsaf=$this->db->query($ulbpendingsql)->getResultArray();
        $data['ulb_pending']=$ulbpendingtotalsaf[0]['pending_levelform']??"0";

        $ulbdonesql="SELECT COUNT(*) AS done_levelform from (SELECT saf_dtl_id AS done_levelform FROM tbl_bugfix_level_pending_dtl 
                    JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_bugfix_level_pending_dtl.sender_user_type_id 
                    JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_bugfix_level_pending_dtl.saf_dtl_id WHERE tbl_bugfix_level_pending_dtl.status
                    IN (0,1) AND tbl_bugfix_level_pending_dtl.verification_status IN (0,1) AND view_user_type_mstr.status=1 
                    AND tbl_saf_dtl.saf_pending_status IN (0,1) AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1
                    AND sender_user_type_id=7 AND receiver_user_type_id IN (9) $whereDateRange GROUP BY saf_dtl_id
                    except
                    SELECT DISTINCT saf_dtl_id AS pending_levelform FROM tbl_bugfix_level_pending_dtl 
                    JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_bugfix_level_pending_dtl.receiver_user_type_id 
                    JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_bugfix_level_pending_dtl.saf_dtl_id WHERE
                    tbl_bugfix_level_pending_dtl.status=1 AND tbl_bugfix_level_pending_dtl.verification_status=0
                    AND view_user_type_mstr.status=1 AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status=0
                    AND tbl_saf_dtl.payment_status=1 AND tbl_bugfix_level_pending_dtl.receiver_user_type_id=7 
                    $whereDateRange GROUP BY saf_dtl_id) as ulbdone
                ";
//
        $ulbdonesqldonetotalsaf=$this->db->query($ulbdonesql)->getResultArray();
        $data['ulb_done']=$ulbdonesqldonetotalsaf[0]['done_levelform']??0;

        $sipendingsql="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform,receiver_user_type_id,user_type FROM tbl_level_pending_dtl
                        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                        WHERE tbl_level_pending_dtl.status=1 
                         AND tbl_level_pending_dtl.verification_status=0 AND view_user_type_mstr.status=1
                          AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status=0
                     AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=9 
                    $whereDateRange GROUP BY receiver_user_type_id, user_type";
        $sipendingtotalsaf=$this->db->query($sipendingsql)->getResultArray();
        $data['si_pending']=$sipendingtotalsaf[0]['pending_levelform']??"0";

        $sidonesql="SELECT COUNT(*) AS done_levelform from(SELECT DISTINCT saf_dtl_id AS done_levelform
 FROM tbl_level_pending_dtl JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.sender_user_type_id 
JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE tbl_level_pending_dtl.status IN (0,1) 
AND tbl_level_pending_dtl.verification_status IN (0,1) AND view_user_type_mstr.status=1 AND tbl_saf_dtl.saf_pending_status 
IN (0,1) AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1 AND tbl_level_pending_dtl.sender_user_type_id=9 AND 
receiver_user_type_id IN (10,9,5) $whereDateRange GROUP BY saf_dtl_id
except
SELECT 
DISTINCT saf_dtl_id AS pending_levelform FROM tbl_level_pending_dtl
JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id 
JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE tbl_level_pending_dtl.status=1 
AND tbl_level_pending_dtl.verification_status=0 AND view_user_type_mstr.status=1 AND tbl_saf_dtl.status=1 
AND tbl_saf_dtl.saf_pending_status=0 AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=9 
$whereDateRange GROUP BY saf_dtl_id) as sidone";
        $sidonesqldonetotalsaf=$this->db->query($sidonesql)->getResultArray();
        $data['si_done']=$sidonesqldonetotalsaf[0]['done_levelform']??0;

        $dmcpendingsql="SELECT COUNT(DISTINCT saf_dtl_id) AS pending_levelform,receiver_user_type_id,user_type FROM tbl_level_pending_dtl
                        JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                        WHERE tbl_level_pending_dtl.status=1 
                         AND tbl_level_pending_dtl.verification_status=0 AND view_user_type_mstr.status=1
                          AND tbl_saf_dtl.status=1
                     AND tbl_saf_dtl.payment_status=1 AND tbl_level_pending_dtl.receiver_user_type_id=10 
                    $whereDateRange GROUP BY receiver_user_type_id, user_type";
        $dmcpendingtotalsaf=$this->db->query($dmcpendingsql)->getResultArray();
        $data['dmc_pending']=$dmcpendingtotalsaf[0]['pending_levelform']??"0";

        $dmcdonesql="SELECT COUNT(DISTINCT saf_dtl_id) AS done_levelform,sender_user_type_id,user_type FROM tbl_level_pending_dtl
                     JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.sender_user_type_id
                     JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id WHERE 
                     tbl_level_pending_dtl.status IN (0,1)
                     AND tbl_level_pending_dtl.verification_status IN (0,1)
                     AND view_user_type_mstr.status=1 AND tbl_saf_dtl.payment_status=1 AND tbl_saf_dtl.status=1
                     AND sender_user_type_id=10 AND receiver_user_type_id IN (10,5) $whereDateRange 
                     GROUP BY sender_user_type_id, user_type";
        $dmcdonesqldonetotalsaf=$this->db->query($dmcdonesql)->getResultArray();
        $data['dmc_done']=$dmcdonesqldonetotalsaf[0]['done_levelform']??0;
        $data['ulb_done']="u:".$data['ulb_done']."s:".$data['si_done']."d:".$data['dmc_done'];
        return $data;
    }
}

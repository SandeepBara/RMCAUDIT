<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_apply_licence extends Model
{

    protected $table = 'tbl_apply_licence';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function verifyDocument($input)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_licence_id'])
                            ->update([
                                    'doc_verify_status'=> $input['doc_verify_status'],
                                    'doc_verify_date'=> $input['doc_verify_date'],
                                    'doc_verify_emp_details_id'=> $input['doc_verify_emp_details_id'],
                                    ]);
    }

    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

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
    public function getDatabyid($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");

                    //echo $this->getLastQuery();
        return $result;

    }
     public function get_licence_firm_name($firm_name)
    {
        try{        
         //    $builder = $this->db->table($this->table)
         //                ->select('id')
         //                ->where('status', 1)
         //                ->whereIn('update_status',[0,null])
                        // ->orwhere('application_no', $application_no)
         //                ->orwhere('license_no', $application_no)
         //                ->get();

            
            return $sql = "SELECT 
                        al.id,
                        al.firm_name,
                        al.application_no,
                        al.apply_date,
                        al.apply_from,
                        vwm.ward_no,
                        CASE WHEN al.license_no isnull THEN 'N/A' else license_no  END as license_no,
                        OWNER.applicant_name,
                        CASE WHEN length(OWNER.father_name) <= 0 THEN 'N/A' else father_name END as father_name,
                        OWNER.mobile_no,
                        CASE WHEN al.valid_upto isnull THEN 'N/A' else valid_upto::text  END as  validity
                        FROM tbl_apply_licence as al
                        JOIN (SELECT tbl_firm_owner_name.apply_licence_id,
                                string_agg(tbl_firm_owner_name.owner_name::text, ','::text) AS applicant_name,
                                string_agg(tbl_firm_owner_name.guardian_name::text, ','::text) AS father_name,
                                string_agg(tbl_firm_owner_name.mobile::text, ','::text) AS mobile_no
                            FROM tbl_firm_owner_name 
                            JOIN tbl_apply_licence as al 
                                ON al.id=tbl_firm_owner_name.apply_licence_id AND  al.firm_name ~~* '$firm_name%'
                            WHERE tbl_firm_owner_name.status=1
                            GROUP BY tbl_firm_owner_name.apply_licence_id
                            ) OWNER  on OWNER.apply_licence_id=al.id 
                        JOIN view_ward_mstr as vwm on vwm.id=al.ward_mstr_id 
                        WHERE (al.status = 1 AND al.update_status IN (0,NULL))
                            AND  al.firm_name ~~* '$firm_name%'
                       ";
            
            
            $run=$this->db->query($sql);
                        // echo $this->db->getLastQuery();die();
                        
           return $run->getResultArray('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function trade_con_list($from_date,$to_date,$ward_permission)
    {
    	try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function wardwise_trade_con_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function update_doc_status($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_licence_id'])
                            ->update([
                                    'document_upload_status'=> 1,
                                    'doc_verify_status'=> 0,
                                    'pending_status'=> 0
                                    ]);
    }
	
	/*get id by application number*/
	 public function get_licence_id($application_no)
    {
    	try{        
         //    $builder = $this->db->table($this->table)
         //                ->select('id')
         //                ->where('status', 1)
         //                ->whereIn('update_status',[0,null])
					    // ->orwhere('application_no', $application_no)
         //                ->orwhere('license_no', $application_no)
         //                ->get();

     
           $sql = "SELECT 
                        al.id,
                        al.firm_name,
                        al.application_no,
                        al.apply_date,
                        al.apply_from,
                        vwm.ward_no,
                        CASE WHEN al.license_no isnull THEN 'N/A' else license_no  END as license_no,
                        OWNER.applicant_name,
                        CASE WHEN length(OWNER.father_name) <= 0 THEN 'N/A' else father_name END as father_name,
                        OWNER.mobile_no,
                        CASE WHEN al.valid_upto isnull THEN 'N/A' else valid_upto::text  END as  validity
                        FROM tbl_apply_licence as al
                        JOIN (SELECT tbl_firm_owner_name.apply_licence_id,
                                string_agg(tbl_firm_owner_name.owner_name::text, ','::text) AS applicant_name,
                                string_agg(tbl_firm_owner_name.guardian_name::text, ','::text) AS father_name,
                                string_agg(tbl_firm_owner_name.mobile::text, ','::text) AS mobile_no
                               FROM tbl_firm_owner_name 
                               WHERE tbl_firm_owner_name.status=1
                               GROUP BY tbl_firm_owner_name.apply_licence_id
                               ) OWNER  on OWNER.apply_licence_id=al.id 
                        JOIN view_ward_mstr as vwm on vwm.id=al.ward_mstr_id  
                        WHERE (al.status = 1 AND al.update_status IN (0,NULL))
                            AND (OWNER.mobile_no ilike '%$application_no' 
                            OR al.application_no ilike '%$application_no'
                            OR al.license_no ilike '%$application_no')
                        LIMIT 10";
            
            
            
            $run=$this->db->query($sql);
                        // echo $this->db->getLastQuery();die();
                        
           return $run->getResultArray('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

     public function get_licence_id_for_application_update($application_no)
    {
        try{        
         //    $builder = $this->db->table($this->table)
         //                ->select('id')
         //                ->where('status', 1)
         //                ->whereIn('update_status',[0,null])
                        // ->orwhere('application_no', $application_no)
         //                ->orwhere('license_no', $application_no)
         //                ->get();

            $sql = "SELECT 
                        id,
                        ward_no,
                        application_no,
                        CASE WHEN license_no isnull THEN 'N/A' END license_no,
                        applicant_name,
                        father_name,
                        mobile_no,
                        firm_name,
                        CASE WHEN validity isnull THEN 'N/A' END validity
                    FROM view_apply_licence_owner WHERE status = 1 
                        AND update_status IN (0,NULL) 
                        and application_no = '$application_no'";
            $run=$this->db->query($sql);
                        // echo $this->db->getLastQuery();die();
                        
           return $run->getResultArray('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

	/* public function update_doc_pending_status_by_back($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_licence_id'])
                            ->update([
									'pending_status'=>0
                                    ]);
    } */
    public function water_conn_details($insert_id)
    {
        $sql="select * from view_water_application_details where md5(id::text)='".$insert_id."' and status in(1,2)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();
        return $result;
    }


    public function update_level_pending_status($input)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $input['apply_licence_id'])
                            ->update([
                                    'pending_status'=> $input['level_pending_status']
                                    ]);
        //print_var($builder);
        return $builder;
    }

    public function bo_backtocitizen_list($from_date,$to_date,$ward_permission)
    {
    	try
        {
            $builder = "select * from view_backtocitizenlist where forward_date>='".$from_date."' and forward_date<='".$to_date."' and ward_mstr_id in (".$ward_permission.")";    
            $ql= $this->query($builder);
            return $ql->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function bo_allbacktocitizen_list($ward_permission)
    {
        try
        {
            $builder = "select * from view_backtocitizenlist where ward_mstr_id in (".$ward_permission.")";    
            $ql= $this->query($builder);
            return $ql->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function wardwisebo_backtocitizen_list($from_date,$to_date,$ward_mstr_id)
    {
    	try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('pending_status', 2)
                        ->where('ward_mstr_id', $ward_mstr_id)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	public function allward_backtocitizen_list($from_date,$to_date)
    {
    	try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('pending_status', 2)
                       // ->where('ward_mstr_id', $ward_mstr_id)
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
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function applyLicenseDetails($apply_license_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $apply_license_id)
                        ->get();
           return $builder->getResultArray()[0];

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
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('pending_status', 2)
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
                        ->where('pending_status', 2)
                        ->where('ward_mstr_id', $ward_mstr_id)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function updateByLevel($id,$area_in_sqft,$ward_mstr_id){
		try{
			return $builder = $this->db->table($this->table)
							->where('id',$id)
							->update([
										'area_in_sqft'=>$area_in_sqft,
										'ward_mstr_id'=>$ward_mstr_id
									]);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getholding($application_no)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('holding_no,licence_for_years,landmark,address,establishment_date,nature_of_bussiness,category_type_id,apply_date')
                        //->where('status', 1)
                        ->where('application_no', $application_no)
                        ->get();
						
                     
						//echo $this->db->getLastQuery();

           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getHoldingNo($id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('holding_no')
                    ->where('md5(id::text)',$id)
                    ->where('status',1)
                    ->get();
            $builder = $builder->getFirstRow("array");
            return $builder['holding_no'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateWard($inputs){
        try{
             return $builder = $this->db->table($this->table)
                        ->where('md5(id::text)',$inputs['apply_licence_id'])
                        ->update([
                                "ward_mstr_id"=>$inputs['ward_mstr_id']
                                ]);
                       /* echo $this->db->getLastQuery();*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }


    public function updateArea($inputs){
        try{
             return $builder = $this->db->table($this->table)
                        ->where('md5(id::text)',$inputs['apply_licence_id'])
                        ->update([
                                "area_in_sqft"=>$inputs['area_in_sqrt']
                                ]);
                       /* echo $this->db->getLastQuery();*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function edit($id,$data=[]){
        return $builder = $this->db->table($this->table)
                        ->where('id',$id)
                        ->update($data);
    }

    public function updateRejectStatus($id,$emp_details_id){
        try
        {
            return $builder = $this->db->table($this->table)
                      ->where('md5(id::text)',$id)
                      ->update([
                                'emp_details_id'=>$emp_details_id,
                                'pending_status'=> 4, // Reject
                                'license_date' => "NOW()"
                                ]);
            # echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function updatePaidStatus($apply_license_id)
    {
        $sql="update tbl_apply_licence set payment_status=0 where id=$apply_license_id";
        $run=$this->db->query($sql);
    }

    public function updatePaymentDone($apply_license_id)
    {
        $sql="update tbl_apply_licence set payment_status=1 where id=$apply_license_id";
        $run=$this->db->query($sql);
    }

    public function updatePendingPaymentDone($apply_license_id)
    {
        $sql="update tbl_apply_licence set payment_status=2 where id=$apply_license_id";
        $run=$this->db->query($sql);
    }

    public function count_ward_by_wardid($ward_mstr_id)
    {
        try
        {
            $builder= $this->db->table($this->table)
                       ->select('count(id) as ward_cnt')
                       ->where('ward_mstr_id', $ward_mstr_id)
                       ->get()
                       ->getFirstRow();
            echo $this->db->getLastQuery();
            return $builder;
       }
       catch(Exception $e)
       {
           return $e->getMessage();
       }
    }
}
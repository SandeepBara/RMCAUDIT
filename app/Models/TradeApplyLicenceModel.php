<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeApplyLicenceModel extends Model
{
	protected $db;
    protected $table = 'tbl_apply_licence';
    protected $allowedFields = ['id','application_no','firm_type_id','application_type_id','ownership_type_id','ward_mstr_id','prop_dtl_id','firm_name','area_in_sqft','k_no','bind_book_no','account_no','payment_status','document_upload_status','pending_status','doc_verify_status','doc_verify_date','doc_verify_emp_details_id','emp_details_id','created_on','status','establishment_date','rate_id','address','landmark','pin_code','property_type','licence_for_years','apply_date','holding_no,otherfirmtype','provisional_licence_no','category_type_id','nature_of_bussiness','update_status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
        session();
    }

    public function validUpto($application_type_id)
    {
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('valid_upto')             
                    ->where('id', $application_type_id)                                 
                    ->get();
            //echo $this->db->getLastQuery();exit;
            return $result = $builder->getFirstrow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function nature_of_business_list(){
        try
        {
            $builder = $this->db->table('tbl_trade_items_mstr')
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'desc')                                   
                    ->get();
            // echo $this->db->getLastQuery();exit;
            return $result = $builder->getResultArray('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getlicencedata2($license_no){
        try
        {
            $builder = $this->db->table('tbl_apply_licence as al')
                    ->select('al.*,im.trade_item')    
                    ->join('tbl_trade_items_mstr as im','im.trade_code = al.nature_of_bussiness')                
                    ->where('al.license_no', $license_no)
                    ->where('al.status', 1)
                    ->where('al.update_status',0)
                    ->where('al.pending_status',5)
                    ->where('al.valid_upto <=','now()')
                    ->orderBy('al.id', 'desc')                                   
                    ->get();
            // echo $this->db->getLastQuery();exit;
            return $result = $builder->getFirstrow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function update_nature_of_business($nob,$apply_id){
         try
        {
            $sql = "update tbl_apply_licence set nature_of_bussiness = $nob where id = '$apply_id' and update_status=0 and pending_status=5 and status=1";
            $builder = $this->db->query($sql);
                                                  
                    // ->get();
            // echo $this->db->getLastQuery();exit;
            return $builder;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function insertapplyexcel($input)
    {
         $builder = $this->db->table($this->table)
                            ->insert([
                              "application_no"=>($input["application_no"]<>'')?$input["application_no"]:null,
                  "firm_type_id"=>($input["firm_type_id"]<>'')?$input["firm_type_id"]:null,
                  "application_type_id"=>($input["application_type_id"]<>'')?$input["application_type_id"]:null,
                  "ownership_type_id"=>($input["ownership_type_id"]<>'')?$input["ownership_type_id"]:null,
                  "ward_mstr_id"=>($input["ward_mstr_id"]<>'')?$input["ward_mstr_id"]:null,
                  "firm_name"=>($input["firm_name"]<>'')?$input["firm_name"]:null,
                  "area_in_sqft"=>($input["area_in_sqft"]<>'')?$input["area_in_sqft"]:null,
                  "establishment_date"=>($input["firm_date"]<>'')?$input["firm_date"]:null,                  
                  "address"=>($input["address"]<>'')?$input["address"]:null,
                  "landmark"=>($input["landmark"]<>'')?$input["landmark"]:null,
                   "pin_code"=>($input["pin_code"]<>'')?$input["pin_code"]:null,
                  "property_type"=>'PROPERTY',
                  "emp_details_id"=>$input["emp_details_id"],                 
                  "created_on"=>$input["created_on"],
                  "licence_for_years"=>($input["licence_for_years"]<>'')?$input["licence_for_years"]:null,
                  "apply_date"=>($input["curdate"]<>'')?$input["curdate"]:null,
                  "k_no"=>($input["k_no"]<>'')?$input["k_no"]:null,
                  "bind_book_no"=>($input["bind_book_no"]<>'')?$input["bind_book_no"]:null,
                  "account_no"=>($input["account_no"]<>'')?$input["account_no"]:null,
                  "holding_no"=>($input["holding_no"]<>'')?$input["holding_no"]:null            
                  
                  ]);
                           // echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function insertapply($input){

        // print_var($input);die();
        $builder = $this->db->table($this->table)
			->insert([
				"firm_type_id"=>$input["firm_type_id"],
				"application_type_id"=>$input["application_type_id"],
				"ownership_type_id"=>$input["ownership_type_id"],
				"ward_mstr_id"=>$input["ward_mstr_id"], 
				"new_ward_mstr_id"=>$input["new_ward_mstr_id"],                 
				"firm_name"=>$input["firm_name"],
				"category_type_id"=>$input["category_type_id"] ?? NULL,
				"prop_dtl_id"=>$input["prop_dtl_id"] ?? 0,
				"area_in_sqft"=>$input["area_in_sqft"],
				"establishment_date"=>$input["firm_date"],
				"rate_id"=>$input["rate_id"],
				"address"=>$input["address"],
				"landmark"=>$input["landmark"] ?? null,
				"pin_code"=>$input["pin_code"],
				"property_type"=>$input["property_type"],
				"emp_details_id"=>$input["emp_details_id"],
				"created_on"=>$input["created_on"],
				"licence_for_years"=>$input["licence_for_years"],
				"apply_date"=> $input["curdate"],                  
				"holding_no"=> $input["holding_no"],
				"otherfirmtype"=> $input["otherfirmtype"],
				"premises_owner_name"=> $input["owner_business_premises"],
                "brife_desp_firm"=> $input["brife_desp_firm"] ?? NULL,
				"nature_of_bussiness"=> $input["nature_of_bussiness"],
				"tobacco_status"=> $input["tobacco_status"],
				"apply_from"=> $input["apply_from"] ?? "JSK",
				"update_status"=> $input["update_status"] ?? 0,
				"valid_from"=> (empty($input["valid_from"]))?$input["curdate"]:$input["valid_from"],
				"valid_upto"=> $input["valid_upto"] ?? NULL,
				"license_no"=> $input["license_no"] ?? NULL,
                "nature_of_bussiness" =>$input['nature_of_bussiness']??null
			]);
        //echo($this->db->getLastQuery());die;
        return $insert_id = $this->db->insertID();
    }

   public function insertrenewdata($input)
   {
       
        $sql_prop = "INSERT INTO tbl_apply_licence(
			firm_type_id, application_type_id, ownership_type_id, ward_mstr_id, prop_dtl_id, firm_name, area_in_sqft, k_no, bind_book_no, account_no, emp_details_id, created_on, establishment_date, rate_id, address, landmark, pin_code, property_type, payment_status, licence_for_years, apply_date, holding_no, otherfirmtype, new_ward_mstr_id, premises_owner_name, brife_desp_firm, pan_no, tin_no, salestax_no, street_name, category_type_id, nature_of_bussiness, update_status, license_no, turnover, valid_from, apply_from)
			SELECT firm_type_id, '".$input["application_type_id"]."', ownership_type_id, ward_mstr_id, prop_dtl_id, firm_name, '".$input["area_in_sqft"]."', k_no, bind_book_no, account_no, '".$input["emp_details_id"]."', '".$input["curdate"]."', establishment_date, '".$input["rate_id"]."', address, landmark, pin_code, property_type, '".($input["payment_status"] ?? 0)."', '".$input["licence_for_years"]."', '".$input["curdate"]."', holding_no, otherfirmtype, new_ward_mstr_id, premises_owner_name, brife_desp_firm, pan_no, tin_no, salestax_no, street_name,  category_type_id, nature_of_bussiness, 0, license_no,  turnover, '".date("Y-m-d")."', '".($input["apply_from"] ?? "JSK")."'
			FROM tbl_apply_licence where id='".$input['licence_id']."' returning id";
		$this->db->query($sql_prop);
        //echo $this->db->getLastQuery();
        $con_dtl_id = $this->db->insertID();
        return $con_dtl_id;
    }


    public function getIdByapplicationno($application_no){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('application_no',$application_no)
                    ->where('status',1)                                       
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function getlicencedata($license_no)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')                    
                    ->where('license_no', $license_no)
                    ->where('status', 1)
                    ->where('update_status',0)
                    ->orderBy('id', 'desc')                                   
                    ->get();
            //echo $this->db->getLastQuery();exit;
            return $result = $builder->getFirstrow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }
    public function getlicencedataMobi($license_no,array $ward_ids)
    {
        try
        {
            if(!$ward_ids)
            {
                return[];
            }
            $builder = $this->db->table("view_trade_licence")
                    ->select('*')                    
                    ->where('license_no', $license_no)
                    ->whereIn("ward_mstr_id",$ward_ids)
                    ->where('status', 1)
                    ->where('update_status',0)
                    ->orderBy('id', 'desc')                                   
                    ->get();
            //echo $this->db->getLastQuery();exit;
            return $result = $builder->getFirstrow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getlicencedataSurrendered($license_no, $application__type_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')                    
                    ->where('license_no', $license_no)
                    ->where('application_type_id', $application__type_id)
                    ->where('status', 1)
                    ->where('pending_status', 5)
                    ->orderBy('id', 'desc')                                   
                    ->get();
            //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }

    public function getUpdateDataId($id){
        try{
             $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('md5(id::text)', $id)
                      ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getIdtyByapplicationno($application_no)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('id,application_type_id')
                    ->where('application_no',$application_no)
                    ->where('status',1)                                       
                    ->get();
            // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function count_ward_by_wardid($ward_mstr_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('count(id) as ward_cnt')
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->get()
                        ->getResultArray()[0];
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function updateLicenseNo($data)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->where('id', $data['apply_license_id'])
                        ->update([
                            'license_no'=> $data['license_no'],
                            'license_date'=> $data['license_date']
                            
                            ]);
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    
    public function updateLicenseValidity($data)
    {
        try
        {
            $update_data=[
                'license_date'=> $data['license_date'],
                'valid_upto'=> $data['valid_upto'],
                "approved_by"=>$data["approved_by"]??(session()->get("emp_details")["id"]??null),
            ];
            if(isset($data['valid_from'], $data['valid_from']))
                $update_data['valid_from']=$data['valid_from'];
            

            $builder = $this->db->table($this->table)
                        ->where('id', $data['apply_license_id'])
                        ->update($update_data);
           // echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function approveLicense($apply_license_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->where('id', $apply_license_id)
                        ->update([
                            'pending_status'=> 5,
                            ]);
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

   public function update_application_no($application_no,$insert_id,$payment_status)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $insert_id)
                            ->update([
                                    'application_no'=>$application_no,
                                    'payment_status'=>$payment_status,
                                    ]);
        
       //echo $this->db->getLastQuery();
       return $builder;

    }

    public function update_doc_data($insert_id)
    {
         $builder = $this->db->table($this->table)
                            ->where('MD5(id::text)', $insert_id)
                            ->update([
                                    'document_upload_status'=> 2,                                    
                                    ]);
        
       // echo $this->db->getLastQuery();
         return $builder;

    }

    public function update_status_id($new_apply_license_id, $old_apply_license_id)
    {
         $builder = $this->db->table($this->table)
                            ->where('id', $old_apply_license_id)
                            ->update([
                                    'update_status'=> $new_apply_license_id,                                    
                                    ]);
        
        //echo $this->db->getLastQuery();
        return $builder;

    }

    public function update_application_no_excel($insert_id,$payment_status)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $insert_id)
                            ->update([                                   
                                    'payment_status'=>$payment_status,
                                    ]);
        
       // echo $this->db->getLastQuery();

    }
    public function fetch_all_application_data($applyid)
    {

        $sql="select * from tbl_apply_licence join (select apply_licence_id,string_agg(owner_name,',') as applicant_name,string_agg(guardian_name,',') as father_name,string_agg(mobile::text,',') as mobile_no from tbl_firm_owner_name group by apply_licence_id) as owner on owner.apply_licence_id=tbl_apply_licence.id where md5(tbl_apply_licence.id::text)='".$applyid."'";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->db->getLastQuery();exit;
        return $result;

    }
	
	

    public function apply_licence_md5($applyid)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $applyid)
                        ->get();
                       //echo $this->db->getLastQuery();//die;
           return $builder->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getLicenceDetails($applicationNo)
    {
         try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('application_no', $applicationNo)
                        ->get();
                       //echo $this->db->getLastQuery();exit;
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDetailsByLicence($input)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('license_no', strtoupper($input["licence_no"]))
                        ->orderBy('id','desc')
                        ->limit(1)
                        ->get();
                       // echo $this->db->getLastQuery();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getApplyLicenseByPropId($prop_dtl_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('coalesce(license_no, application_no) as applic_no, *')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('payment_status', 1)
                        ->where('update_status', 0)
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function apply_licence_last($applyid)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('update_staus', $applyid)
                        ->orderBy('id','desc')
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }


    public function apply_licence_md5id($applyid)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $applyid)
                        ->get();
                        //echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    /*public function getApplicationNo($applyid)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('application_no')
                        ->where('status', 1)
                        ->where('md5(id::text)', $applyid)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder->getFirstRow("array");
            return $builder['application_no'];


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }*/
    
   public function updateApplyLicencePaymentStatusClear($id)
   {
      try
      {
          return $builder = $this->db->table($this->table)
                          ->where('id', $id)
                          ->update([
                            "payment_status"=>1
                          ]);
      }
      catch(Exception $e)
      {
        echo $e->getMessage();
      }
   }
   
   public function updateApplyLicencePaymentStatusNotClear($id){
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
    public function applyLicenceDetails($id){
      try{
          $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('id',$id)
                    ->where('status',1)
                    ->get();
          return $builder->getResultArray()[0];
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    public function getApplicationNo($id){
       try{        
             $builder = $this->db->table($this->table)
                        ->select('application_no')
                        ->where('id',$id)
                        ->where('status', 1)
                        ->get();
                       // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["application_no"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
	
	
	public function gateApplicationNo(){
		$sql = "SELECT count(id),application_type_id
		FROM tbl_transaction
		group by transaction_type
		";
        $ql= $this->query($sql, [$data['id']]);
		$result = $ql->getResultArray();
		return $result;
        
    }

    public function newapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and application_type_id=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	public function renewapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and application_type_id=2";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	

    public function amendapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and application_type_id=3";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

	public function surrendapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and application_type_id=4";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }


    public function newapplyLicensereport($where)
    {
        $sql="select   count( DISTINCT related_id)
		from tbl_transaction where $where and status in(1,2) and transaction_type='NEW LICENSE' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function renewapplyLicensereport($where)
    {
		$sql="select count(DISTINCT related_id)
		from tbl_transaction where $where and status in(1,2) and transaction_type='RENEWAL' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function amendapplyLicensereport($where)
    {
        $sql="select count(DISTINCT related_id)
		from tbl_transaction where $where and status in(1,2) and transaction_type='AMENDMENT' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

    public function surrendapplyLicensereport($where)
    {
        $sql="select count(DISTINCT related_id)
		from tbl_transaction where $where and status in(1,2) and transaction_type='SURRENDER' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }
  
    public function pending_at_jsk($where)
    {
        $sql="select count(id)
        from tbl_apply_licence where $where and status=1 and payment_status = 0";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

	public function totalapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and application_type_id between 1 and 4";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	

    public function pndjskapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and document_upload_status=0";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	public function pndingjskapplyLicense($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	
	public function gatenewapplication($frm_date,$to_date){
        $sql = "SELECT count(id) AS newapp
		FROM tbl_apply_licence
		where status=1 and payment_status in (1,2) and application_type_id=1 and apply_date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }

   
	
	public function gaterenewalapplication($frm_date,$to_date){
        $sql = "SELECT count(id) AS renewalapp
		FROM tbl_apply_licence
		where status=1 and payment_status in (1,2) and application_type_id=2 and apply_date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gateamendmentapplication($frm_date,$to_date){
        $sql = "SELECT count(id) AS amendmentapp
		FROM tbl_apply_licence
		where status=1 and payment_status in (1,2) and application_type_id=3 and apply_date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gatesurrenderappapplication($frm_date,$to_date){
        $sql = "SELECT count(id) AS surrenderapps
		FROM tbl_apply_licence
		where status=1 and payment_status in (1,2) and application_type_id=4 and apply_date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
    public function updateLicenceYears($id,$licence_for_years){
      try{
         return $builder = $this->db->table($this->table)
                          ->where('md5(id::text)',$id)
                          ->update([
                                    "licence_for_years" =>$licence_for_years,
                                    "payment_status"=>1
                                  ]);
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }

    public function updateNewApplyLicence($data)
    {
        try
        {
            if(isset($data['holding_no']) && $data['holding_no'] !="")
            {
                $builder= $this->db->table($this->table)
                    ->where('md5(id::text)', $data['id'])
                    ->update([
                                "firm_type_id" =>$data['firmtype_id'],
                                "ownership_type_id" =>$data['ownership_type_id'],                               
                                "ward_mstr_id" =>$data['ward_mstr_id'],
                                "new_ward_mstr_id" =>$data['new_ward_mstr_id'],
                                "firm_name" =>$data['firm_name'],
                                "area_in_sqft" =>$data['area_in_sqft'],
                                "establishment_date" =>$data['firm_date'],
                                "address" =>$data['firmaddress'],
                                "landmark" =>$data['landmark'],
                                "pin_code" =>$data['pin_code'],
                                "nature_of_bussiness" => $data['nature_of_bussiness'],
                                "category_type_id" =>$data['category_type_id'],
                                "otherfirmtype" =>$data['firmtype_other'],
                                "premises_owner_name" =>$data['premises_owner_name'],
                                "holding_no" =>$data['holding_no'],
                                "prop_dtl_id" =>!empty(trim($data['prop_dtl_id']))?$data['prop_dtl_id']:null,                                      
                                "document_upload_status" =>2
                            ]);
            }
            else
            {
                $builder= $this->db->table($this->table)
                    ->where('md5(id::text)', $data['id'])
                    ->update([
                                "firm_type_id" =>$data['firmtype_id'],
                                "ownership_type_id" =>$data['ownership_type_id'],                               
                                "ward_mstr_id" =>$data['ward_mstr_id'],
                                "new_ward_mstr_id" =>$data['new_ward_mstr_id'],
                                "firm_name" =>$data['firm_name'],
                                "area_in_sqft" =>$data['area_in_sqft'],
                                "establishment_date" =>$data['firm_date'],
                                "address" =>$data['firmaddress'],
                                "landmark" =>$data['landmark'],
                                "pin_code" =>$data['pin_code'],
                                "nature_of_bussiness" => $data['nature_of_bussiness'],
                                "category_type_id" =>$data['category_type_id'],
                                "otherfirmtype" =>$data['firmtype_other'],
                                "premises_owner_name" =>$data['premises_owner_name'],
                                "holding_no" =>null,
                                "prop_dtl_id" =>null,                                      
                                "document_upload_status" =>2
                            ]);

            }
             
            
            // if(session()->get('emp_details')['id']==1016)
            // {
                echo $this->db->getLastQuery();
            // }
            return $builder;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateAmendementLicence($data)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                    ->where('md5(id::text)', $data['id'])
                    ->update([
                                "firm_type_id" =>$data['firmtype_id'],
                                "ownership_type_id" =>$data['ownership_type_id'],
                                "ward_mstr_id" =>$data['ward_mstr_id'],
                                "nature_of_bussiness" => $data['nature_of_bussiness'],
                                "category_type_id" =>$data['category_type_id'],
                                "otherfirmtype" =>$data['firmtype_other'],
                                "document_upload_status" =>2
                            ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function update_prov_no($id,$prov_no){
      try{
          return $builder = $this->db->table($this->table)
                    ->where('id',$id)
                    ->update([
                              "provisional_license_no"=> $prov_no
                            ]);
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    public function getprovisinalno($id){
        try{      
             return $this->db->table($this->table)
                        ->select('provisional_licence_no')
                        ->where('md5(id::text)',$id)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updateCategory($id,$category_type_id){
      try{
        $builder = $this->db->table($this->table)
                         ->where('md5(id::text)',$id)
                         ->update([
                                    "category_type_id"=>$category_type_id
                                  ]);
                         /*echo $this->getLastQuery();*/
                         return $builder;
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    public function getNatureOfBusinessId($id){
      try{
          $builder = $this->db->table($this->table)
                    ->select('nature_of_bussiness')
                    ->where('md5(id::text)', $id)
                    ->get();
          $builder= $builder->getFirstRow('array');
          //echo $this->db->getLastQuery();die;
          return $builder['nature_of_bussiness']??null;
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
	
	
	
	public function newLicenseListward($data)
    {
        $sql="select *
		from tbl_apply_licence 
		where ward_mstr_id='".$data['ward_id']."' and apply_date between '".$data['fromDate']."' and '".$data['todate']."' and status=1 and application_type_id=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	public function newLicenseList($data)
    {
        $sql="select *
		from tbl_apply_licence 
		where apply_date between '".$data['fromDate']."' and '".$data['todate']."' and status=1 and application_type_id=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	
	public function gatedeactiveLicence($currntdy)
    {
        $sql="select count(id) as deactiveLic from tbl_apply_licence 
		where valid_upto<'".$currntdy."' and update_status=0 and license_no!=''";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
	
	public function gateactiveLicence($currntdy)
    {
        $sql="select count(id) as activeLic from tbl_apply_licence 
		where valid_upto>='".$currntdy."' and update_status=0 and license_no!=''";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }



    public function get_licence_by_ward($where, $application__type_id, $ward_mstr_id=null)
    {   
       if($application__type_id == 'rej')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=4) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=4) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
         
       }
       elseif($application__type_id == 'levl')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status not in (2,4,5)) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status not in (2,4,5)) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
        
       }
       elseif($application__type_id == 'bo')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=2) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id";
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=2) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id";
        }
          
       }
       elseif(in_array($application__type_id ,['jsk','bco']))
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join (select count(id) as no_new ,ward_mstr_id from tbl_apply_licence 
            where $where and status=1 
            group by ward_mstr_id
            ) as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join (select count(id) as no_new ,ward_mstr_id from tbl_apply_licence 
            where $where and status=1 
            group by ward_mstr_id
            ) as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 

        }
        
       }
       elseif($application__type_id == '5')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=5) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id";
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=5) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
          
       }
       elseif($application__type_id == 'levl')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status not in (2,4,5)) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w 
            left join 
            (select count(apply_licence_id) as no_new,tbl_apply_licence.ward_mstr_id from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and status not in (2,4,5)) level_pending 
            left join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id 
            where $where GROUP  BY tbl_apply_licence.ward_mstr_id )         
            as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
         
       }
       elseif($application__type_id == 'prov')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w left join 
            (select count(id) as no_new ,ward_mstr_id  from tbl_apply_licence where 
            $where 
            and provisional_license_no is not null and status = 1 and payment_status = 1 GROUP BY ward_mstr_id ) as tbllevel 
            on tbllevel.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w left join 
            (select count(id) as no_new ,ward_mstr_id  from tbl_apply_licence where 
            $where 
            and provisional_license_no is not null and status = 1 and payment_status = 1 GROUP BY ward_mstr_id ) as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
        
       }
       elseif($application__type_id == 'da' || $application__type_id == 'td' || $application__type_id == 'sh' || $application__type_id == 'eo')
       {
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,levelpending.no_new  from view_ward_mstr w left join
            (select count(tbl_level_pending.id) as no_new,tbl_apply_licence.ward_mstr_id from tbl_level_pending left join tbl_apply_licence 
            on tbl_level_pending.apply_licence_id = tbl_apply_licence.id 
            where  $where  
             and tbl_level_pending.id in (select max(id) from tbl_level_pending group by apply_licence_id)
             group by tbl_apply_licence.ward_mstr_id) as levelpending
             on levelpending.ward_mstr_id = w.id order by w.id"; 
        }
        else
        {
            $sql="select w.id,w.ward_no,tbllevel.no_new from view_ward_mstr w left join 
            (select count(id) as no_new ,ward_mstr_id  from tbl_apply_licence where 
            $where 
            and provisional_license_no is not null and status = 1 and payment_status = 1 GROUP BY ward_mstr_id ) as tbllevel 
            on tbllevel.ward_mstr_id = w.id where w.id = $ward_mstr_id order by w.id"; 
        }
        
       }
       else
       {
            if($ward_mstr_id == null)
            {
                
                $sql="select w.id,w.ward_no,coalesce(newl.no_new,0) as no_new 
                from view_ward_mstr w 
                left join 
                (select count(distinct related_id) as no_new, ward_mstr_id 
                from tbl_transaction 
                where  status in (1,2) and $where group by
                tbl_transaction.ward_mstr_id) newl 
                on newl.ward_mstr_id=w.id order 
                by w.id ";
            }
            else
            {

                $sql="select w.id,w.ward_no,coalesce(newl.no_new,0) as no_new 
                from view_ward_mstr w 
                left join 
                (select count(distinct related_id) as no_new, ward_mstr_id 
                from tbl_transaction 
                where  status in (1,2) and $where group by
                tbl_transaction.ward_mstr_id) newl 
                on newl.ward_mstr_id=w.id where w.id = $ward_mstr_id order 
                by w.id ";
            }
        
       }
       
       // echo $sql.$application__type_id;die;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();exit;
         return $result;
    }

    public function getCollection_by_ward($where,$ward_mstr_id=null)
    {  
        if($ward_mstr_id == null)
        {
            $sql="select w.id,w.ward_no,coalesce(newl.no_new,0) as no_new from view_ward_mstr w left join 
            (select sum(paid_amount) as no_new, ward_mstr_id from tbl_transaction where status in (1,2) 
            and $where   
            group by tbl_transaction.ward_mstr_id) newl on newl.ward_mstr_id=w.id order by w.id";
        }
        else
        {
            $sql="select w.id,w.ward_no,coalesce(newl.no_new,0) as no_new from view_ward_mstr w left join 
            (select sum(paid_amount) as no_new, ward_mstr_id from tbl_transaction where status in (1,2) 
            and $where   
            group by tbl_transaction.ward_mstr_id) newl on newl.ward_mstr_id=w.id and w.id = $ward_mstr_id order by w.id";
        }
         
       
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
         return $result;
    }

    public function getlicenceID($applyid)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('status', 1)
                        ->where('md5(id::text)', $applyid)
                        ->get();
                       // echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function get_all_licence_by_ward($where)
    {
        $sql="select w.id,w.ward_no,coalesce(newl.no_new,0) as no_new,coalesce(newl.new_paid,'0.00') 
        as new_paid,coalesce(renewl.no_renew,0) as  no_renew,coalesce(renewl.renew_paid,'0.00') as renew_paid,
        coalesce(amendl.no_amend,0) as no_amend, coalesce(amendl.amend_paid,'0.00') as amend_paid,coalesce(surl.no_sur,0) as no_sur,
        coalesce(surl.sur_paid,'0.00') as sur_paid from view_ward_mstr w 
        left join (select count(related_id) as no_new,sum(tbl_transaction.paid_amount)as new_paid, ward_mstr_id 
        from tbl_transaction where transaction_type='NEW LICENSE' and status in (1,2) and $where group by
         tbl_transaction.ward_mstr_id) newl on newl.ward_mstr_id=w.id									   
        left join (select count(related_id) as no_renew,sum(tbl_transaction.paid_amount)as renew_paid,ward_mstr_id 
            from tbl_transaction where transaction_type='RENEWAL' and status in (1,2) and $where group by
        tbl_transaction.ward_mstr_id) renewl on renewl.ward_mstr_id=w.id
        left join (select count(related_id) as no_amend,sum(tbl_transaction.paid_amount)as amend_paid,ward_mstr_id 
            from tbl_transaction where transaction_type='AMENDMENT' and status in (1,2) and $where group by
        tbl_transaction.ward_mstr_id) amendl on amendl.ward_mstr_id=w.id
        left join (select count(related_id) as no_sur,sum(tbl_transaction.paid_amount)as sur_paid,ward_mstr_id 
            from tbl_transaction where transaction_type='SURRENDER' and status in (1,2) and $where group by
        tbl_transaction.ward_mstr_id) surl on surl.ward_mstr_id=w.id order by w.id ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();exit;
         return $result;
    }

    public function get_all_licence_by_ward1($ward_id,$where)
    {
        $sql="select count(related_id) as no_of_application,ward_mstr_id,
        sum(paid_amount) as amount
        from tbl_transaction
        where $where
        and status in (1,2) and ward_mstr_id = ".$ward_id."  group by ward_mstr_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
      // echo $this->getLastQuery();exit;
         return $result;
    }

    public function get_licence_by_single_ward($ward_id,$where)
    {

        $sql="select count(tbl_apply_licence.id) as no_of_application,tbl_apply_licence.ward_mstr_id 
		from tbl_apply_licence 
        JOIN tbl_transaction on tbl_transaction.related_id=tbl_apply_licence.id
        where $where and tbl_apply_licence.status=1  and tbl_apply_licence.ward_mstr_id = ".$ward_id."
         group by tbl_apply_licence.ward_mstr_id";
         // var_dump($sql);die();
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();exit;
         return $result;
    }


    public function get_collection_by_ward($ward_id,$where)
    {
        $sql="select sum(paid_amount) as total_paid_amount,ward_mstr_id 
		from tbl_transaction where $where and status=1  and ward_mstr_id = ".$ward_id."
         group by ward_mstr_id";
         $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function get_collection_by_single_ward($ward_id,$where)
    {
        $sql="select sum(paid_amount) as total_paid_amount,ward_mstr_id 
		from tbl_transaction where $where and status=1  and ward_mstr_id = ".$ward_id."
         group by ward_mstr_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function newlicence_collection($where)
    {
        $sql="select sum(paid_amount)
		from tbl_transaction where $where and status in(1,2) and transaction_type='NEW LICENSE' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function renewlicence_collection($where)
    {
        $sql="select sum(paid_amount)
		from tbl_transaction where $where and status in(1,2) and transaction_type='RENEWAL' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function amendment_collection($where)
    {
        $sql="select sum(paid_amount)
		from tbl_transaction where $where and status in(1,2) and transaction_type='AMENDMENT' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function surrender_collection($where)
    {
        $sql="select sum(paid_amount)
		from tbl_transaction where $where and status in(1,2) and transaction_type = 'SURRENDER' and ward_mstr_id IS NOT NULL";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function get_Alllicence_by_ward($ward_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')                    
                    ->where('md5(ward_mstr_id::text)', $ward_id)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')                                   
                    ->get();
            //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }

    public function view_licence($where)
    {
        $sql="select * from  
        (select DISTINCT(related_id) from tbl_transaction where $where and status in(1,2) and ward_mstr_id IS NOT NULL ) as tran
        left join tbl_apply_licence
        on tran.related_id = tbl_apply_licence.id  where tbl_apply_licence.apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function AmountCollection($where)
    {
        $sql="select * from  
        (select related_id,paid_amount from tbl_transaction where $where and status in(1,2) and ward_mstr_id IS NOT NULL ) as tran
        left join tbl_apply_licence
        on tran.related_id = tbl_apply_licence.id where tbl_apply_licence.apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }


    public function pendingJskView($where)
    {
        $sql="select *
		from tbl_apply_licence where $where and status=1 and document_upload_status=0 and apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function newapplyLicensereportTc($where)
    {
        $sql=" select  count( DISTINCT related_id)  from 
        (select DISTINCT (related_id)  from tbl_transaction where $where and status in(1,2) and transaction_type='NEW LICENSE' 
        and ward_mstr_id IS NOT NULL) as tran
        left join tbl_apply_licence on tran.related_id = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC';
        ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function renewapplyLicensereportTc($where)
    {
		$sql="select  count( DISTINCT related_id)  from 
        (select DISTINCT (related_id)  from tbl_transaction where $where and status in(1,2) and transaction_type='RENEWAL' 
        and ward_mstr_id IS NOT NULL) as tran
        left join tbl_apply_licence on tran.related_id = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit; 
        return $result;
    }

    public function amendapplyLicensereportTc($where)
    {
        $sql="select  count( DISTINCT related_id)  from 
        (select DISTINCT (related_id)  from tbl_transaction where $where and status in(1,2) and transaction_type='AMENDMENT' 
        and ward_mstr_id IS NOT NULL) as tran
        left join tbl_apply_licence on tran.related_id = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result; 
    }

    public function surrendapplyLicensereportTc($where)
    {
        $sql="select  count( DISTINCT related_id)  from 
        (select DISTINCT (related_id)  from tbl_transaction where $where and status in(1,2) and transaction_type='SURRENDER' 
        and ward_mstr_id IS NOT NULL) as tran
        left join tbl_apply_licence on tran.related_id = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function pndjskapplyLicenseTc($where)
    {
        $sql="select count(id)
		from tbl_apply_licence where $where and status=1 and document_upload_status=0 and apply_from = 'TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       // echo $this->getLastQuery();exit;
        return $result;
    }

    public function newlicence_collectionTc($where)
    {
        $sql="select sum(tran.paid_amount) from 
        (select related_id,paid_amount from tbl_transaction where $where and status in(1,2) 
        and transaction_type='NEW LICENSE' and ward_mstr_id IS NOT NULL) as tran 
        left join tbl_apply_licence on tran.related_id =  tbl_apply_licence.id where apply_from ='TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
      // echo $this->getLastQuery();exit;
        return $result;
    }

    public function renewlicence_collectionTc($where)
    {
        $sql="select sum(tran.paid_amount) from 
        (select related_id,paid_amount from tbl_transaction where $where and status in(1,2) 
        and transaction_type='RENEWAL' and ward_mstr_id IS NOT NULL) as tran 
        left join tbl_apply_licence on tran.related_id =  tbl_apply_licence.id where apply_from ='TC'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function amendment_collectionTc($where)
    {
        $sql="select sum(tran.paid_amount) from 
        (select related_id,paid_amount from tbl_transaction where $where and status in(1,2) 
        and transaction_type='AMENDMENT' and ward_mstr_id IS NOT NULL) as tran 
        left join tbl_apply_licence on tran.related_id =  tbl_apply_licence.id where apply_from ='TC';";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       //echo $this->getLastQuery();exit;
        return $result;
    }

    public function surrender_collectionTc($where)
    {
        $sql="select sum(tran.paid_amount) from 
        (select related_id,paid_amount from tbl_transaction where $where  and status in(1,2) 
        and transaction_type='SURRENDER' and ward_mstr_id IS NOT NULL) as tran 
        left join tbl_apply_licence on tran.related_id =  tbl_apply_licence.id where apply_from ='TC';";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
       
        return $result;
    }

	public function updateApplicnDetails($data)
    {
        try
        {
            return  $builder = $this->db->table($this->table)
                    ->where('id', $data['id'])
                    ->update([
                                "firm_type_id" =>$data['firmtype_id'],
                                "ownership_type_id" =>$data['ownership_type_id'],
                                "holding_no" =>$data['holding_no'],
                                "ward_mstr_id" =>$data['ward_mstr_id'],
                                "new_ward_mstr_id" =>$data['new_ward_mstr_id'],
                                "firm_name" =>$data['firm_name'],
                                "area_in_sqft" =>$data['area_in_sqft'],
                                "establishment_date" =>$data['firm_date'],
                                "address" =>$data['firmaddress'],
                                "landmark" =>$data['landmark'],
                                "pin_code" =>$data['pin_code'],
                                "nature_of_bussiness" => $data['nature_of_bussiness'],
                                "category_type_id" =>$data['category_type_id'],
                                "otherfirmtype" =>$data['firmtype_other'],
                                "premises_owner_name" =>$data['premises_owner_name'],
                                "document_upload_status" =>2,
                                'emp_details_id'=>$data["emp_details_id"],
                                'created_on'=>$data["curdate"]
                            ]);
                   // echo $this->getLastQuery();exit;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateData($id,array $data)
    {
        try{

            $builder = $this->db->table($this->table);
            if(!is_numeric($id))
            {
                $builder = $builder->where('MD5(id::text)', $id);
            }
            else{
                $builder = $builder->where('id', $id);
            }        
            return  $builder->update($data);
        }
        catch(Exception $e)
        {
            print_var($data);
            echo $this->db->getLastQuery();
            echo $e->getMessage();
        }
    }
    
    public function rowQury($sql,$parm=array())
    {
        $query=$this->db->query($sql,$parm)->getFirstRow('array');
        //echo $this->db->getLastQuery();
        return $query;
    }

    public function getLicenseData($license_no)
    {
        try {
            $sql = "SELECT * 
                FROM tbl_apply_licence 
                INNER JOIN tbl_trade_items_mstr 
                ON tbl_apply_licence.nature_of_bussiness = tbl_trade_items_mstr.trade_code
                WHERE tbl_apply_licence.application_no = ?";

            // Execute the query
            $query = $this->db->query($sql, array($license_no));

            // Return the first row as an array
            return $query->getFirstRow('array');
        }
        catch (Exception $e) {
            // Handle the exception and display the error message
            echo $e->getMessage();
        }
    }
    public function update_nature_of_business2($nob, $apply_id, $license_no)
    {
        try {
            // Initialize the query builder for the 'tbl_apply_licence' table
            $builder = $this->db->table('tbl_apply_licence');

            // Set the new value for 'nature_of_bussiness'
            $builder->set('nature_of_bussiness', $nob);

            // Apply the where condition
            $builder->where('license_no', $license_no);
            $builder->where('update_status', 0);
            $builder->where('pending_status', 5);
            $builder->where('status', 1);

            // Execute the update query
            $result = $builder->update();

            // Get the last executed query for debugging purposes
            $lastQuery = (string) $this->db->getLastQuery();

            // Check if any rows were affected
            $affectedRows = $this->db->affectedRows();

            // Fetch the matched rows to understand the issue
            $matchedRows = $this->db->table('tbl_apply_licence')
                ->where('license_no', $license_no)
                ->where('update_status', 0)
                ->where('pending_status', 5)
                ->where('status', 1)
                ->get()
                ->getResultArray();

            // Return the executed query, the result status, and affected rows
            return [
                'query' => $lastQuery,
                'status' => $result,
                'affectedRows' => $affectedRows,
                'matchedRows' => $matchedRows
            ];
        } catch (Exception $e) {
            // Handle the exception and display the error message
            return [
                'error' => $e->getMessage()
            ];
        }
    }
	
}
?>
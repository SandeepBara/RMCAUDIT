<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_licence extends Model 
{
    protected $db;
    protected $table = 'tbl_licence';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apply_licence_id','ward_mstr_id','licence_no','apply_licence_id','application_no','firm_type_id', 'application_type_id','ownership_type_id','prop_dtl_id','firm_name','area_in_sqft','establishment_date','firm_address', 'landmark','pin_code','property_type', 'k_no', 'bind_book_no', 'account_no','created_on','emp_details_id', 'status','holding_no'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertdata($input)
    {

         $sql_prop = "insert into tbl_licence (apply_licence_id,ward_mstr_id,licence_no,application_no,firm_type_id,application_type_id,
         ownership_type_id, prop_dtl_id,firm_name,area_in_sqft,establishment_date,firm_address,landmark,pin_code,property_type,k_no,bind_book_no,account_no,emp_details_id,created_on,status) select '".$input['apply_licence_id']."', 
         ward_mstr_id, '".$input['licence_no']."', application_no,firm_type_id, application_type_id,ownership_type_id,prop_dtl_id,firm_name,area_in_sqft,establishment_date,address,landmark,pin_code,property_type,k_no,bind_book_no,account_no,
         '".$input['emp_details_id']."','".$input['created_on']."', '1' from tbl_apply_licence where id='".$input['apply_licence_id']."'";
        $this->db->query($sql_prop);
        //echo $this->db->getLastQuery();
        $con_dtl_id = $this->db->insertID();
       
         return $con_dtl_id;
     }
    public function count_ward_by_wardid($ward_mstr_id)
    {
        try{
            //  return $this->db->table($this->table)
            //             ->select('count(id) as ward_cnt')
            //             ->where('ward_mstr_id',$ward_mstr_id)
            //             ->get()
            //             ->getResultArray()[0];
            return $this->db->table($this->table)
                        ->select('count(id) as ward_cnt')
                        ->where('ward_mstr_id',$ward_mstr_id)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function updatelicencenobyconid($licence_id,$licence_no){
         return $this->db->table($this->table)
                            ->where('id', $licence_id)
                            ->update(
                                ['licence_no'=>$licence_no
                            ]);
    }

    public function updateareabyid($inputs){
         return $this->db->table($this->table)
                            ->where('id', $inputs["licence_id"])
                            ->update(
                                ['area_in_sqft'=>$inputs["area_in_sqft"]
                            ]);
    }

    public function updatestatusbyid($inputs){
         return $this->db->table($this->table)
                            ->where('id', $inputs["licence_id"])
                            ->update(
                                ['status'=>0
                            ]);
    }

    public function get_licence_dtl($apply_licence_id)
    {
        
        try{
            //  return $this->db->table("view_licence_dtl")
            //             ->select('*')                        
            //             ->where('apply_licence_id', $apply_licence_id)
            //              ->orderBy('id','DESC')
            //             ->get()                       
            //             ->getResultArray()[0];
            return $this->db->table("view_licence_dtl")
                        ->select('*')                        
                        ->where('apply_licence_id', $apply_licence_id)
                         ->orderBy('id','DESC')
                        ->get()                       
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_licence_md5dtl($id)
    {
        try{
            //  return $this->db->table($this->table)
            //             ->select('*')
            //             ->where('status>',0)
            //             ->where('md5(id::text)',$id)
            //             ->get()
            //             ->getResultArray()[0];
            return $this->db->table($this->table)
                        ->select('*')
                        ->where('status>',0)
                        ->where('md5(id::text)',$id)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_licence_list($from_date, $to_date, $ward_permission)
    {
        try
        {
             return $this->db->table("view_licence_dtl")
                        ->select('*')
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function get_wardwiselicence_list($from_date,$to_date,$ward_permission)
    {
        try{
             return $this->db->table("view_licence_dtl")
                        ->select('*')
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_permission)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	
	public function licence_listdate($data)
	{
        $sql = "SELECT tbl_licence.id,tbl_licence.licence_no,tbl_licence.establishment_date,tbl_licence.firm_name,
		tbl_licence_owner_name.owner_name,tbl_licence_owner_name.mobile
		FROM tbl_licence
		left join tbl_licence_owner_name on tbl_licence.id = tbl_licence_owner_name.licence_id
		where tbl_licence.status IN (1,2,3) and
		tbl_licence.establishment_date between '".$data['from_date']."' and '".$data['to_date']."'";
        $ql= $this->db->query($sql);
		//echo $this->db->getLastQuery();
        $result =$ql->getResultArray();
        return $result;
	}
	public function licence_listkeyword($where)
	{
        $sql = "SELECT tbl_licence.id,tbl_licence.licence_no,tbl_licence.establishment_date,tbl_licence.firm_name,
		tbl_licence_owner_name.owner_name,tbl_licence_owner_name.mobile
		FROM tbl_licence
		left join tbl_licence_owner_name on tbl_licence.id = tbl_licence_owner_name.licence_id
		where ".$where;
        $ql= $this->db->query($sql);
		//echo $this->db->getLastQuery();
        $result =$ql->getResultArray();
        return $result;
	}
	
	
    public function getLicenceByLicenceNo($licenceNoData){
    try{
      $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(licence_no::text)',$licenceNoData)
                ->where('status',1)
                ->get();
               // echo $this->db->getLastQuery();
        //return $builder->getResultArray()[0];
        return $builder->getFirstRow('array');
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
public function getLicenceById($data){
    try{
        $builder = $this->db->table($this->table)
                 ->select('*')
                 ->where('md5(id::text)',$data['id'])
                 ->where('status',1)
                 ->get();
       //return  $builder->getResultArray()[0];
       return  $builder->getFirstRow('array');
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
public function updateDeativateStatus($id){
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

 public function validate_license($ward_id,$licence_no)
    {
      

        $sql="select id from tbl_licence where ward_mstr_id=".$ward_id." and upper(licence_no)='".$licence_no."' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['id'];


    }
    public function debarredLicence($id){
        try
        {
            return $builder = $this->db->table($this->table)
                              ->where('md5(id::text)',$id)
                              ->update([
                                        'status'=>3
                                        ]);
                              //echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function getAllDataByApplyLicenceId($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                       ->select('*')
                       ->where('md5(apply_licence_id::text)',$apply_licence_id)
                       ->get();
                      // echo $this->db->getLastQuery();
            return $builder=$builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function insertLicenceData($data){
         try{
            $builder = $this->db->table($this->table)
                            ->insert([
                                "apply_licence_id" => $data['apply_licence_id'],
                                "ward_mstr_id" => $data['ward_mstr_id'],
                                "application_no" => $data['application_no'],
                                "licence_no" => $data['licence_no'],
                                "firm_type_id" => $data['firm_type_id'],
                                "application_type_id" => $data['application_type_id'],
                                "prop_dtl_id" => $data['prop_dtl_id'],
                                "firm_name" => $data['firm_name'],
                                "area_in_sqft" => $data['area_in_sqft'],
                                "firm_address" => $data['firm_address'],
                                "landmark" => $data['landmark'],
                                "pin_code" => $data['pin_code'],
                                "property_type" => $data['property_type'],
                                "k_no" => $data['k_no'],
                                "bind_book_no" => $data['bind_book_no'],
                                "account_no" => $data['account_no'],
                                "emp_details_id" => $data['emp_details_id'],
                                "created_on" => $data['created_on'],
                                "establishment_date" => $data['establishment_date'],
                                "holding_no" => $data['holding_no'],
                                "ownership_type_id" =>$data['ownership_type_id']
                            ]);
                          // echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function checkLicenceIdExists($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id')
                      ->where('md5(apply_licence_id::text)',$apply_licence_id)
                      ->where('status',1)
                      ->get();
            $builder = $builder->getFirstRow("array");
            return $builder['id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateHoldingNo($holding_no,$apply_licence_id){
        try{
             $builder = $this->db->table($this->table)
                      ->where('md5(apply_licence_id::text)',$apply_licence_id)
                      ->update([
                                'holding_no'=>$holding_no
                                ]);
                     echo $this->db->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function checkDebarredLicence($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id')
                      ->where('md5(id::text)',$id)
                      ->where('status',3)
                      ->get();
                     // echo $this->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return $builder['id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateTradeItem($data){
        try{
            return $builder = $this->db->table($this->table)
                      ->where('id',$data['licence_id'])
                      ->update([
                                "nature_of_business" =>$data['nature_of_business']
                                ]);
                      /*echo $this->db->getLastQuery();*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}

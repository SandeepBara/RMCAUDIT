<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeViewApplyLicenceOwnerModel extends Model
{
	protected $db;
    #protected $table = 'view_apply_licence_owner';
    protected $table = 'view_trade_licence';
    protected $allowedFields = ['id','application_no','firm_type_id','application_type_id','ownership_type_id','ward_mstr_id','prop_dtl_id','firm_name','area_in_sqft','k_no','bind_book_no','account_no','payment_status','document_upload_status','pending_status','doc_verify_status','doc_verify_date','doc_verify_emp_details_id','emp_details_id','created_on','status','establishment_date','rate_id','address','landmark','pin_code','property_type','licence_for_years','holding_no','applicant_name','father_name','mobile_no','ward_no','apply_date','provisional_licence_no','category_type_id','application_type'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
    public function fetch_aaplication_detailsward($data)
    {
   	    try
        {
            $builder = $this->db->table($this->table)
                    ->select('id,ward_no,application_no,holding_no,applicant_name,firm_name,mobile_no,application_type')
                    ->where('ward_mstr_id',$data["ward_mstr_id"])
                    ->where('apply_date>=',$data["fromdate"])
                    ->where('apply_date<=',$data["todate"])
                    //->like($data['likestmt'])
                    ->where('status',1)
                    ->orderBy('id', 'desc')                   
                    ->get();
            // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
   }

   public function fetch_license_details_by_keyword2($data)
    {
        $id=$data['id'];

        try
        {
            $keyword = $data['keyword'];
            $sql="select * from view_trade_licence where status=1 and update_status=0 and (application_no ilike '%$keyword%' or license_no ilike '%$keyword%' or firm_name ilike '%$keyword%' or mobile ilike '%$keyword%') order by id desc limit 30";

            // echo $sql; die;
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
     public function fetch_license_details_by_keyword($data)
    {
        // $id=$data['id'];

        try
        {
            $keyword = $data['keyword'];
            $wardid = null;
            if(isset($data['ward_id']))
            {
                $wardId = $data['ward_id'];
            }
            $sql="select * from view_trade_licence 
            where status=1 and pending_status=5 and update_status=0 
                and (application_no ilike '%$keyword%' or license_no ilike '%$keyword%' or firm_name ilike '%$keyword%' or mobile ilike '%$keyword%') 
                ".($wardId?" AND ward_mstr_id IN ($wardId)":"")."
            order by id desc limit 30";

            // echo $sql; die;
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function fetch_aaplication_details_by_keyword($data)
    {
        try
        {
            $keyword = $data['keyword'];
            $wardid = null;
            if(isset($data['ward_id']))
            {
                $wardId = $data['ward_id'];
            }
            $sql="select * from view_trade_licence 
                  where status=1 and (application_no like '%$keyword%' or firm_name like '%$keyword%' or mobile like '%$keyword%') 
                  ".($wardId?" AND ward_mstr_id IN ($wardId)":"")."
                  order by id desc limit 30";
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();exit;
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


   public function fetch_aaplication_details($data)
   {
        try
        {
            $wardid = null;
            if(isset($data['ward_id']))
            {
                $wardId = $data['ward_id'];
            }
            $builder = $this->db->table($this->table)
                    ->select('*')
					->where('status', 1)
                    ->where('apply_date>=',$data["fromdate"])
                    ->where('apply_date<=',$data["todate"])
                    ->orderBy('id', 'desc') ;
            if($wardId)  
            {
                $builder = $builder->whereIn('ward_mstr_id',explode(",",$wardId));

            }      
            $builder = $builder->get();
                    //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
   }

   public function fetch_license_details($data)
   {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
					->where('status', 1)
                    ->where('pending_status', 5)
                    ->where('update_status', 0)
                    ->where('apply_date>=', $data["fromdate"])
                    ->where('apply_date<=', $data["todate"])
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


    public function fetch_aaplication_citizendetail($where)
    {
        $sql = "SELECT id,ward_no,application_no,license_no,applicant_name,father_name,mobile_no,firm_name
		FROM view_apply_licence_owner
		where ".$where;
        $ql= $this->db->query($sql);
		//echo $this->db->getLastQuery();exit;
        $result =$ql->getResultArray();
        return $result;
	}
	
	public function get_licenceDetails($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");
                    //echo $this->db->getLastQuery();exit;
        return $result;

    }
    
   public function getdetails($id){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('id',$id)
                    ->orderBy('id', 'ASC')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getDatabyid($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('md5(id::text)',$id)
                    ->get()
                    ->getFirstRow("array");

                   //echo $this->db->getLastQuery();
        return $result;

    }

    //get details by id 
    public function get_Data($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('id',$id)
                    ->get()
                    ->getFirstRow("array");
                  // echo  $this->db->getLastQuery(); exit;
        return $result;

    }
	
	//get id by application number
	public function getDatabyapplno($id)
    {
        $result=$this->db->table($this->table)
                    ->select('id')
                    //->where('status',1)
                    ->where('application_no',$id)
                    ->get()
                    ->getFirstRow("array");

                   //echo $this->db->getLastQuery();
        return $result;

    }
	public function getFirmData($data)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('id',$data)
                    ->get()
                    ->getFirstRow("array");

                   //echo $this->db->getLastQuery();
        return $result;

    }
   public function getlicencedatabydate($data){
    try{
        $builder = $this->db->table($this->table)
                  ->select('*')
                  ->where('apply_date>=',$data['from_date'])
                  ->where('apply_date<=',$data['to_date'])
                  ->where('status',1)
                  ->orderBy('apply_date','ASC')
                  ->get();
                 //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }catch(Exception $e){
        echo $e->getMessage();
    }
}

public function getUpdateDataId($id){
        try{
             $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('md5(id::text)',$id)
                      ->get();
           // echo $this->db->getLastQuery();
             return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getApplyLicenceId($data){
       try{        
             $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('upper(application_no)',$data['application_no'])
                        ->where('ward_mstr_id',$data['ward_mstr_id'])
                        ->where('status',1)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["id"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getApplyLicenceDetails($data){
      try{
            $builder = $this->db->table($this->table)
                          ->select('id,ward_no,application_no,holding_no,applicant_name,mobile_no')
                          ->where('upper(application_no)',$data['application_no'])
                          ->where('ward_mstr_id',$data['ward_mstr_id'])
                          ->where('status',1)
                          ->get();
            return $builder->getResultArray();
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    public function getNewLicenceByLicenceNo($licence_no)
    {
        try
        {
            $result = $this->db->table($this->table)
                        ->select("*")
                        ->where('license_no',$licence_no)
                        ->where('update_status',0)
                        ->where('status',1)
                        ->orderBy('id','desc')
                        ->get()
                        ->getFirstRow('array');   
            //echo $this->db->getLastQuery();
            return $result;                     
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
}
?>
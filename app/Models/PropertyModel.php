<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class PropertyModel extends Model
{
    protected $db;
    protected $table = 'tbl_prop_dtl';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    
    public function chkholding_exist(array $data)
    {
            
         /*$result=$this->db->table($this->table)
                    ->select("count(id) as id")
                    ->where('ward_mstr_id', $data['ward_id'])
                    ->where('holding_no', $data['holding_no'])
                    ->get()
                    ->getFirstRow("array");

                   return $result['id'];*/
                   
                   
       /* $sql="select tbl_prop_dtl.id,owner_name,mobile_no,guardian_name,email from tbl_prop_dtl join (select prop_dtl_id,string_agg(owner_name,',') as owner_name,
            string_agg(mobile_no::text,',') as mobile_no,string_agg(guardian_name,',') as guardian_name,string_agg(email,',') as email from tbl_prop_owner_detail group by prop_dtl_id) as owner 
            on 
            owner.prop_dtl_id=tbl_prop_dtl.id where ward_mstr_id=".$data['ward_id']." and holding_no='".$data['holding_no']."' and status=1";
        */
            
         $sql="select tbl_prop_dtl.id,tbl_prop_dtl.ward_mstr_id,area_of_plot,ward_no,owner_name,coalesce(mobile_no::text,'') as mobile_no,coalesce(guardian_name,'') as guardian_name,coalesce(email,'') as email,elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,prop_address,prop_pin_code from tbl_prop_dtl join tbl_prop_owner_detail as owner 
            on 
            owner.prop_dtl_id=tbl_prop_dtl.id join view_ward_mstr on view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id where  holding_no='".$data['holding_no']."' and tbl_prop_dtl.status=1";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;


    }

    public function chksaf_exist(array $data)
    {
        
      $sql="select tbl_saf_dtl.id,tbl_saf_dtl.ward_mstr_id,ward_no,tbl_saf_dtl.prop_dtl_id,elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,payment_status,owner_name,guardian_name,mobile_no,email from tbl_saf_dtl join tbl_saf_owner_detail as safowner on safowner.saf_dtl_id=tbl_saf_dtl.id join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id 
        where  saf_no='".$data['saf_no']."' and tbl_saf_dtl.status=1 and safowner.status=1 ";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        //print_r($result);

        return $result;
        

    }

    public function validate_holding($ward_id, $holding_no)
    {
        $sql="select owner_name from view_prop_dtl_owner_ward_prop_type_ownership_type where ward_mstr_id=".$ward_id." and upper(holding_no)='".$holding_no."' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['owner_name'];

    }
   


    
}
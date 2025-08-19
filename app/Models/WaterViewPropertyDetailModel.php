<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterViewPropertyDetailModel extends Model
{
    protected $db;
    protected $table = 'view_prop_detail';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function chkholding_exist($holding_no)
    {
        // this view contains only activated holdings so no need to check whether holding is deactivated or activated
         $sql="select id,holding_no,is_old,holding_type,ward_mstr_id,ward_no,prop_type_mstr_id,area_of_plot,
               elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,prop_address,prop_pin_code,
               owner_name,mobile_no,guardian_name,email,total_area_sqft from view_prop_detail
               where  new_holding_no='".$holding_no."' and is_old!=1";

         $run=$this->db->query($sql);
         $result=$run->getResultArray();
         //echo $this->getLastQuery();
         return $result;
    }

    public function checkHoldingExists($ward_id,$holding_no)
    {
        $sql="select id,holding_no,is_old,holding_type,ward_mstr_id,ward_no,prop_type_mstr_id,area_of_plot,
               elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,prop_address,prop_pin_code,
               coalesce(owner_name,'') as owner_name,coalesce(mobile_no,null) as mobile_no,coalesce(guardian_name,'') as guardian_name,coalesce(email,'') as email,total_area_sqft from view_prop_detail where ward_mstr_id=$ward_id and holding_no='$holding_no' and prop_type_mstr_id!=4";
        // print_var($sql);die;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //print_var($this->db);die();
        //echo $this->db->getLastQuery();die;
        return $result;

      
    }

    public function checkNeHoldingExists($ward_id,$holding_no)
    {
        $sql="select id,holding_no,is_old,holding_type,ward_mstr_id,ward_no,prop_type_mstr_id,area_of_plot,
               elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,prop_address,prop_pin_code,
               coalesce(owner_name,'') as owner_name,coalesce(mobile_no,null) as mobile_no,coalesce(guardian_name,'') as guardian_name,coalesce(email,'') as email,total_area_sqft 
               from view_prop_detail where ward_mstr_id=$ward_id and new_holding_no='$holding_no' and prop_type_mstr_id!=4 and is_old!=1 ";
        //print_var($sql);die;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //print_var($this->db);die();
        //echo $this->db->getLastQuery();die;
        return $result;

      
    }

    

    
    
}
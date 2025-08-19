<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterViewSAFDetailModel extends Model
{
    protected $db;
    protected $table = 'view_saf_detail';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function chksaf_exist($saf_no)
    {
            
        // this view contains only activated holdings so no need to check whther holding is deactivated or activated

         $sql="select  id,prop_dtl_id,saf_no,ward_mstr_id,ward_no,prop_type_mstr_id,area_of_plot,
               elect_consumer_no,elect_acc_no,elect_bind_book_no,elect_cons_category,prop_address,prop_pin_code,
               owner_name,mobile_no,guardian_name,email,total_area_sqft,payment_status from view_saf_detail

               where  saf_no='".$saf_no."'";
        //print_var($sql);die;
         $run=$this->db->query($sql);
         $result=$run->getResultArray();
         //echo $this->db->getLastQuery();die;
         return $result;


    }

   

    
    
}
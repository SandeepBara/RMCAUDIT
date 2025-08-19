<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_saf_dtl extends Model
{
	protected $db;
    protected $table = 'view_saf_dtl';
    protected $allowedFields = ['saf_dtl_id', 'has_previous_holding_no', 'previous_holding_id', 'previous_ward_mstr_id', 'is_owner_changed', 'transfer_mode_mstr_id', 'transfer_mode', 'saf_no', 'holding_no', 'ward_mstr_id', 'ward_no', 'ownership_type_mstr_id', 'ownership_type', 'prop_type_mstr_id', 'property_type', 'appartment_name', 'flat_registry_date', 'zone_mstr_id', 'no_electric_connection', 'elect_consumer_no', 'elect_acc_no', 'elect_bind_book_no', 'elect_cons_category', 'building_plan_approval_no', 'building_plan_approval_date', 'water_conn_no', 'water_conn_date', 'khata_no', 'plot_no', 'village_mauja_name', 'road_type_mstr_id', 'road_type', 'area_of_plot', 'prop_address', 'prop_city', 'prop_dist', 'prop_pin_code', 'is_corr_add_differ', 'corr_address', 'corr_city', 'corr_dist', 'corr_pin_code', 'is_mobile_tower', 'tower_area', 'tower_installation_date', 'is_hoarding_board', 'hoarding_area', 'hoarding_installation_date', 'is_petrol_pump', 'under_ground_area', 'petrol_pump_completion_date', 'is_water_harvesting', 'land_occupation_date', 'payment_status', 'doc_verify_status', 'doc_verify_date', 'doc_verify_emp_details_id', 'doc_verify_cancel_remarks', 'field_verify_status', 'field_verify_date', 'field_verify_emp_details_id', 'emp_details_id', 'created_on', 'updated_on', 'status', 'apply_date', 'saf_pending_status', 'assessment_type', 'doc_upload_status', 'saf_distributed_dtl_id', 'prop_dtl_id', 'prop_state', 'corr_state', 'holding_type', 'ip_address'];

	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function get_saf_full_details($saf_dtl_id_md5)
    {
        try
        {
            if (is_numeric($saf_dtl_id_md5)) {
                $sql="select * from get_saf_full_details(".$saf_dtl_id_md5.");";
            } else {
                $sql="select * from get_saf_full_details('$saf_dtl_id_md5');";    
            }
            
            $query=$this->db->query($sql);
            return $query->getFirstRow('array');
            //echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

		public function get_saf_full_details_test($saf_dtl_id_md5)
    {
        try
        {
            $sql="select * from get_saf_full_details(".$saf_dtl_id_md5.");";
            $query=$this->db->query($sql);
            return $query->getFirstRow('array');
            //echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function get_saf_status($saf_dtl_id_md5)
    {
        try
        {
            if (is_numeric($saf_dtl_id_md5)) {
                $sql="select * from get_saf_status(".$saf_dtl_id_md5.");";
            } else {
                $sql="select * from get_saf_status('$saf_dtl_id_md5');";    
            }
            $query=$this->db->query($sql);
            return $query->getFirstRow('array')["get_saf_status"];
            //echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getSafDtlByMD5SafDtlId($saf_dtl_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('md5(saf_dtl_id::text)', $saf_dtl_id)
                            ->where('status', 1)
                            ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getSafDtlBySafDtlId($saf_dtl_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('status', 1)
                            ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }


	public function getSafDtlByMD5Safno($saf_no){

        try{
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('md5(saf_no::text)', $saf_no)
                            ->where('status', 1)
                            ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function getSafDtlBySafno($saf_no){

        try{
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where("saf_no ILIKE '".$saf_no."'")
                            ->where('status', 1)
                            ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function basic_details($saf_dtl_id_md5)
    {
        try{
            $builder = $this->db->table("view_saf_owner_detail")
                            ->select('*')
                            ->where('md5(saf_dtl_id::text)', $saf_dtl_id_md5)
                            ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();
        }

    }

}
?>

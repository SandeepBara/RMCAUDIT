<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_dtl';
    protected $allowedFields = ['id', 'assessment_type'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        try{
            $this->db->table($this->table)
                                ->insert($input);
                //echo $this->db->getLastQuery();
            return $this->db->insertID();
        } catch(Exception $e) {

        }
    }
    public function getLastApplicationNoByGovtSafDtlId($input){
        try{
            return $this->db->table($this->table)
                        ->select('application_no')
                        ->where('ward_mstr_id', $input['ward_mstr_id'])
                        ->orderBy('id', 'DESC')
                        ->limit(1)
                        ->get()
                        //echo $this->db->getLastQuery();
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

	
	public function govSaf_details($input)
    { 
		$builder = "SELECT tbl_govt.id,tbl_govt.assessment_type,view_ward_mstr.ward_no, tbl_govt.application_no,tbl_govt.building_colony_name,tbl_govt.office_name,tbl_govt.building_colony_address,tbl_govt.application_type
					FROM tbl_govt_saf_dtl tbl_govt
					JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
					where tbl_govt.status=1 and tbl_govt.ward_mstr_id=".$input['ward_mstr_id']." and (tbl_govt.application_no ilike '%".$input['keyword']."%' or tbl_govt.building_colony_address ilike '%".$input['keyword']."%')";
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
	
	
	public function basic_details($input)
    { 
		$builder = "SELECT tbl_govt.id,tbl_govt.id as saf_dtl_id,tbl_govt.apply_date,tbl_govt.prop_usage_type_mstr_id,tbl_govt.assessment_type,owner_typ.ownership_type,prop_typ.property_type,
					tbl_govt.ward_mstr_id,view_ward_mstr.ward_no, tbl_govt.application_no,tbl_govt.building_colony_name,tbl_govt.office_name,tbl_govt.building_colony_address,
					tbl_govt.address,tbl_govt.is_water_harvesting ,offcr_dtl.designation,offcr_dtl.officer_name,tbl_govt.holding_no
					FROM tbl_govt_saf_dtl tbl_govt
					left JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id 
					left JOIN tbl_govt_saf_officer_dtl offcr_dtl ON tbl_govt.id = offcr_dtl.govt_saf_dtl_id 
					left JOIN tbl_ownership_type_mstr owner_typ ON tbl_govt.ownership_type_mstr_id = owner_typ.id 
					left JOIN tbl_prop_type_mstr prop_typ ON tbl_govt.prop_type_mstr_id = prop_typ.id 
					where md5(tbl_govt.id::text)='".$input."'";
		$builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray()[0];
    }
    
	
	public function paidsts($input){
		if($input["payment_mode"]=='2' || $input["payment_mode"]=='3'){
			$psy_stats = 2;
		}else{ $psy_stats = 1; }
		$paidsts = "UPDATE tbl_govt_saf_dtl 
				SET is_transaction_done =?
				WHERE id =?";
				$ql= $this->query($paidsts, [$psy_stats,$input["custm_id"]]);
				return $paidsts;
	}
	
	
	public function paybasic_details($input)
    { 
		$builder = "SELECT tbl_govt.id,tbl_govt.application_type,tbl_govt.application_no,owner_typ.ownership_type,
					tbl_govt.building_colony_address,offc_dtl.* 
					FROM tbl_govt_saf_dtl tbl_govt
					left JOIN tbl_govt_saf_officer_dtl offc_dtl ON tbl_govt.id = offc_dtl.govt_saf_dtl_id 
					left JOIN tbl_ownership_type_mstr owner_typ ON tbl_govt.ownership_type_mstr_id = owner_typ.id 
					where md5(tbl_govt.id::text)='".$input."'";
		$builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray()[0];
    }
	
	
	public function appbasic_details($gb_saf_dtl_id_md5)
    {
        try{
            return $this->db->table("view_gbsaf_detail")
                        ->select('*')
                        ->where('md5(id::text)', $gb_saf_dtl_id_md5)
                        ->get()
                    //echo $this->db->getLastQuery();
                    ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getSafDtlByMD5SafDtlId($saf_dtl_id)
    {
        try
        {
            $builder = $this->db->table('view_gbsaf_detail')
                            ->select('*')
                            ->where('md5(id::text)', $saf_dtl_id)
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
            $builder = $this->db->table('view_gbsaf_detail')
                            ->select('*')
                            ->where('id', $saf_dtl_id)
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
    public function getWardDetail($id)
    {
        try
        {
            $builder = $this->db->table('tbl_govt_saf_dtl')
                            ->select('*')
                            ->where('id', $id)
                            ->where('status', 1)
                            ->get();
            
            $nwardno = '';
            $newward = $builder->getFirstRow("array");

            if(isset($newward['new_ward_mstr_id']))
            {
                $nward= $this->db->table('view_ward_mstr')
                ->select('ward_no')
                ->where('id', $newward['new_ward_mstr_id'])
                ->where('status', 1)
                ->get();
                $nwardno = $nward->getFirstRow("array")['ward_no'];
            }

            return $nwardno;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
	
	
}
?>

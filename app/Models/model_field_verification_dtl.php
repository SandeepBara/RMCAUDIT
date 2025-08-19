<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_field_verification_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_field_verification_dtl';

   

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getUlbDataBySafDtlId($input) {
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('verified_by', 'ULB TC')
                        ->where('status', 1)
                        ->orderBy("id","DESC")
                        ->get();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();   
        }
    }
   
    public function getLeveldata($inputs,$user_type_id,$user_ward){
         /* $builder ="with
        data as (select lpd.id,lpd.saf_dtl_id,lpd.verification_status,saf.saf_no,saf.ward_mstr_id,saf.assessment_type,CONCAT(saf.prop_address,',',saf.prop_city,',',saf.prop_dist,'-',saf.prop_pin_code) as saf_address,saf.prop_dtl_id,proptype.property_type from tbl_level_pending_dtl lpd 
        inner join tbl_saf_dtl saf on lpd.saf_dtl_id=saf.id and lpd.receiver_user_type_id='".$user_type_id."' and lpd.forward_date>='".$inputs['fromdate']."' and lpd.forward_date<='".$inputs['todate']."'  and lpd.status=1 and saf.ward_mstr_id in (".$user_ward.")
        inner join tbl_prop_type_mstr proptype on saf.prop_type_mstr_id=proptype.id)
        ,owner as (select string_agg(owner_name, ',') as owner_name,string_agg(mobile_no::varchar,',') as mobile_no,saf_dtl_id from tbl_saf_owner_detail group by saf_dtl_id)
        ,property as (select id,holding_no from tbl_prop_dtl)
        select data.*,holding_no,owner_name,mobile_no from data,owner,property where data.saf_dtl_id=owner.saf_dtl_id and property.id=data.prop_dtl_id and data.verification_status=0 and (assessment_type ilike '%".$inputs['keyword']."%' or property_type ilike '%".$inputs['keyword']."%' or saf_address ilike '%".$inputs['keyword']."%'  or owner_name like '%".$inputs['keyword']."%' or mobile_no ilike '%".$inputs['keyword']."%' or saf_no ilike '%".$inputs['keyword']."%'or holding_no ilike '%".$inputs['keyword']."%' )"; */

        $sql = "SELECT 
                    LEVEL_DTL.*,
                    SAF_DTL.*,
                    OWNER_DTL.*,
                    PROPERTY_DTL.*,
                    PROP_MSTR_DTL.*
                FROM (
                    SELECT 
                        id AS level_pending_dtl_id,
                        saf_dtl_id 
                    FROM tbl_level_pending_dtl 
                    WHERE receiver_user_type_id='".$user_type_id."' 
                            AND forward_date>='".$inputs['fromdate']."' 
                            AND forward_date<='".$inputs['todate']."' 
                            AND status=1
                            AND verification_status=0) AS LEVEL_DTL
                INNER JOIN (
                    SELECT
                        id,
                        saf_no,
                        ward_mstr_id,
                        assessment_type,
                        CONCAT(prop_address, ',', prop_city, ',', prop_dist,'-', prop_pin_code) as saf_address,
                        prop_dtl_id
                    FROM tbl_saf_dtl
                    WHERE ward_mstr_id IN (".$user_ward.")) AS SAF_DTL ON SAF_DTL.id=LEVEL_DTL.saf_dtl_id
                INNER JOIN (SELECT 
                                string_agg(owner_name, ',') as owner_name,
                                string_agg(mobile_no::varchar,',') as mobile_no,
                                saf_dtl_id 
                            FROM tbl_saf_owner_detail
                            group by saf_dtl_id) AS OWNER_DTL ON OWNER_DTL.saf_dtl_id=LEVEL_DTL.saf_dtl_id
                INNER JOIN (SELECT prop_type_mstr_id, holding_no, new_holding_no, saf_dtl_id FROM tbl_prop_dtl) AS PROPERTY_DTL ON SAF_DTL.id=PROPERTY_DTL.saf_dtl_id
                INNER JOIN (SELECT id AS prop_type_mstr_id, property_type FROM tbl_prop_type_mstr) AS PROP_MSTR_DTL ON PROP_MSTR_DTL.prop_type_mstr_id=PROPERTY_DTL.prop_type_mstr_id
                WHERE 
                    SAF_DTL.assessment_type ILIKE '%".$inputs['keyword']."%' 
                    OR PROP_MSTR_DTL.property_type ILIKE '%".$inputs['keyword']."%' 
                    OR SAF_DTL.saf_address ILIKE '%".$inputs['keyword']."%' 
                    OR OWNER_DTL.owner_name ILIKE '%".$inputs['keyword']."%' 
                    OR OWNER_DTL.mobile_no ILIKE '%".$inputs['keyword']."%' 
                    OR SAF_DTL.saf_no ILIKE '%".$inputs['keyword']."%'
                    OR PROPERTY_DTL.holding_no ILIKE '%".$inputs['keyword']."%'";
        $ql= $this->query($sql);
        //echo $this->getLastQuery();
        $result = $ql->getResultArray();      
        return $result;
    }

     public function insertData($input){
        try{
            $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery();exit;
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

     public function getdatabysafid($id)
    {
        try{

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $id)
                        ->where('status', 1) 
                        ->orderBy('id', 'DESC')                       
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getFieldDtlBYSAFId($id)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, verified_by, created_on')
                        ->where('saf_dtl_id', $id)
                        ->where('status', 1) 
                        ->orderBy('id', 'ASC')                       
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getulbdatabysafid($id, $tc_user_for)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $id)
                        ->like('verified_by', $tc_user_for)
                        ->where('status',1)                        
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getulbdatabysafidobjection($id, $tc_user_for)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $id)
                        ->whereIn('verified_by', $tc_user_for)
                        ->where('status',1)
                        ->orderby("verified_by")
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getFieldVerificationDtlBySafDtlIdAndVerifiedBy($field_verification_id)
    {
        try
        {
            $builder = $this->db->table('tbl_field_verification_dtl')
                        ->select('
                            tbl_field_verification_dtl.id as id, 
                            tbl_field_verification_dtl.saf_dtl_id as saf_dtl_id, 
                            tbl_field_verification_dtl.prop_type_mstr_id as prop_type_mstr_id, 
                            tbl_prop_type_mstr.property_type as property_type,
                            tbl_field_verification_dtl.road_type_mstr_id as road_type_mstr_id, 
                            tbl_road_type_mstr.road_type as road_type,
                            tbl_field_verification_dtl.area_of_plot as area_of_plot, 
                            tbl_field_verification_dtl.verified_by_emp_details_id as verified_by_emp_details_id, 
                            tbl_field_verification_dtl.created_on as created_on, 
                            tbl_field_verification_dtl.status as status, 
                            tbl_field_verification_dtl.ward_mstr_id as ward_mstr_id,
                            view_ward_mstr.ward_no as ward_no,
                            tbl_field_verification_dtl.is_mobile_tower as is_mobile_tower, 
                            tbl_field_verification_dtl.tower_area as tower_area, 
                            tbl_field_verification_dtl.tower_installation_date as tower_installation_date, 
                            tbl_field_verification_dtl.is_hoarding_board as is_hoarding_board, 
                            tbl_field_verification_dtl.hoarding_area as hoarding_area, 
                            tbl_field_verification_dtl.hoarding_installation_date as hoarding_installation_date, 
                            tbl_field_verification_dtl.is_petrol_pump as is_petrol_pump, 
                            tbl_field_verification_dtl.under_ground_area as under_ground_area, 
                            tbl_field_verification_dtl.petrol_pump_completion_date as petrol_pump_completion_date, 
                            tbl_field_verification_dtl.is_water_harvesting as is_water_harvesting, 
                            tbl_field_verification_dtl.verified_by as verified_by, 
                            view_emp_details.emp_name,
                            tbl_field_verification_dtl.zone_mstr_id as zone_mstr_id,
							tbl_field_verification_dtl.percentage_of_property_transfer,
                        ')
                        ->join('view_ward_mstr', 'view_ward_mstr.id = tbl_field_verification_dtl.ward_mstr_id')
                        ->join('view_emp_details', 'view_emp_details.id = tbl_field_verification_dtl.verified_by_emp_details_id', 'left')
                        ->join('tbl_prop_type_mstr', 'tbl_prop_type_mstr.id = tbl_field_verification_dtl.prop_type_mstr_id')
                        ->join('tbl_road_type_mstr', 'tbl_road_type_mstr.id = tbl_field_verification_dtl.road_type_mstr_id')
                        ->where('md5(tbl_field_verification_dtl.id::text)', $field_verification_id)
                        ->where('tbl_field_verification_dtl.status', 1)                        
                        ->get();
            //echo ($this->db->getLastQuery());
            return $builder->getFirstRow('array');

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    
    public function getAllFieldVerification($input)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id']) 
                        ->where('status', 1)   
                        ->orderBy('id')                        
                        ->get();
            //echo $this->db->getLastQuery();
			return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getFieldVerification($field_verification_id)
    {
        try{
            

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('md5(id::text)', $field_verification_id)
                        ->where('status', 1) 
                        ->orderBy('id', 'DESC')                       
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getDtlBySafDtlIdMD5($saf_dtl_id) {
        $sql = "SELECT * FROM ".$this->table." WHERE md5(saf_dtl_id::text)='".$saf_dtl_id."' AND status=1";
        $query = $this->db->query($sql);
        return $query->getFirstRow('array');
    }

    public function DeactivateAll($saf_dtl_id)
    {
        return $this->db->table($this->table)
                        ->where("saf_dtl_id", $saf_dtl_id)
                        ->update(["status"=> 0]);
    }
}
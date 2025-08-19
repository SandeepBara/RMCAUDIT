<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_field_verification_floor_details extends Model 
{
    protected $db;
    protected $table = 'tbl_field_verification_floor_details';

   

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
   
     

     public function insertData($input){
        try{
            $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery(); 
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataBymstrId($field_verification_dtl_id){
        try{
            $sql = "SELECT sfd.id,
    sfd.field_verification_dtl_id,
    sfd.saf_dtl_id,
    sfd.saf_floor_dtl_id,
    sfd.floor_mstr_id,
    floor.floor_name,
    sfd.usage_type_mstr_id,
    usage.usage_type,
    sfd.const_type_mstr_id,
    const.construction_type,
    sfd.occupancy_type_mstr_id,
    occup.occupancy_name,
    sfd.builtup_area,
    sfd.date_from,
    sfd.date_upto,
    sfd.emp_details_id,
    sfd.created_on,
    sfd.status,
    sfd.carpet_area
   FROM tbl_field_verification_floor_details sfd
     JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
     JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id AND usage.status = 1
     JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id AND const.status = 1
     JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id AND occup.status = 1
     where sfd.field_verification_dtl_id=?";
        $ql= $this->db->query($sql, [$field_verification_dtl_id]);
        $result =$ql->getResultArray();
        return $result;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getagencyDataBymstrId($field_verification_dtl_id, $tc_user_for)
    {
        try
        {
            $sql = "SELECT sfd.id,
            sfd.field_verification_dtl_id,
            sfd.saf_dtl_id,
            sfd.saf_floor_dtl_id,
            sfd.floor_mstr_id,
            floor.floor_name,
            sfd.usage_type_mstr_id,
            usage.usage_type,
            sfd.const_type_mstr_id,
            const.construction_type,
            sfd.occupancy_type_mstr_id,
            occup.occupancy_name,
            sfd.builtup_area,
            sfd.date_from,
            sfd.date_upto,
            sfd.emp_details_id,
            sfd.created_on,
            sfd.status,
            sfd.carpet_area
            FROM tbl_field_verification_floor_details sfd
            left JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
            left JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id
            left JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id AND const.status = 1
            left JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id AND occup.status = 1
            where sfd.field_verification_dtl_id=? and sfd.verified_by=?";

            $ql= $this->db->query($sql, [$field_verification_dtl_id, $tc_user_for]);
            //echo $this->db->getLastQuery();
            $result =$ql->getResultArray();
            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }


    public function getExtraFloorAddedByTC($field_verification_dtl_id, $tc_user_for)
    {
        try
        {
            $sql = "SELECT sfd.id,
            sfd.field_verification_dtl_id,
            sfd.saf_dtl_id,
            sfd.saf_floor_dtl_id,
            sfd.floor_mstr_id,
            floor.floor_name,
            sfd.usage_type_mstr_id,
            usage.usage_type,
            sfd.const_type_mstr_id,
            const.construction_type,
            sfd.occupancy_type_mstr_id,
            occup.occupancy_name,
            sfd.builtup_area,
            sfd.date_from,
            sfd.date_upto,
            sfd.emp_details_id,
            sfd.created_on,
            sfd.status,
            sfd.carpet_area
            FROM tbl_field_verification_floor_details sfd
            left JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
            left JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id
            left JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id AND const.status = 1
            left JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id AND occup.status = 1
            where sfd.field_verification_dtl_id=? and sfd.verified_by=? and (saf_floor_dtl_id=0 or saf_floor_dtl_id is null)";

            $ql= $this->db->query($sql, [$field_verification_dtl_id, $tc_user_for]);
            //print_var($this->db->getLastQuery());
            $result =$ql->getResultArray();
            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }


     public function getDataBymstsaffloorId($field_verification_dtl_id,$saf_floor_dtl_id)
     {
        try
        {
            $sql = "SELECT sfd.id,
                sfd.field_verification_dtl_id,
                sfd.saf_dtl_id,
                sfd.saf_floor_dtl_id,
                sfd.floor_mstr_id,
                floor.floor_name,
                sfd.usage_type_mstr_id,
                usage.usage_type,
                sfd.const_type_mstr_id,
                const.construction_type,
                sfd.occupancy_type_mstr_id,
                occup.occupancy_name,
                sfd.builtup_area,
                sfd.date_from,
                sfd.date_upto,
                sfd.emp_details_id,
                sfd.created_on,
                sfd.status,
                sfd.carpet_area
            FROM tbl_field_verification_floor_details sfd
                JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
                JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id AND usage.status = 1
                JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id AND const.status = 1
                JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id AND occup.status = 1
                where sfd.field_verification_dtl_id=? and sfd.saf_floor_dtl_id=?";
            $ql= $this->db->query($sql, [$field_verification_dtl_id,$saf_floor_dtl_id]);
            //echo $this->db->getLastQuery();
            $result =$ql->getResultArray();
            // print_r($result);
            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getUlbDataBySafDtlId($input) {
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('verified_by', 'ULB TC')
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function CheckUlbDataIsSameBySafFloorDtl($input) {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('field_verification_dtl_id', $input['field_verification_dtl_id'])
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('saf_floor_dtl_id', $input['saf_floor_dtl_id'])
                        ->where('floor_mstr_id', $input['floor_mstr_id'])
                        ->where('usage_type_mstr_id', $input['usage_type_mstr_id'])
                        ->where('const_type_mstr_id', $input['const_type_mstr_id'])
                        ->where('occupancy_type_mstr_id', $input['occupancy_type_mstr_id'])
                        ->where('builtup_area', $input['builtup_area'])
                        ->where('date_from', $input['date_from'])
                        ->where('date_upto', $input['date_upto'])
                        ->where('verified_by', 'ULB')
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function getDtlBySafDtlId($saf_dtl_id, $field_verification_dtl_id) {
        $sql = "SELECT * FROM ".$this->table." WHERE saf_dtl_id='".$saf_dtl_id."' AND field_verification_dtl_id='".$field_verification_dtl_id."' AND status=1 AND verified_by='ULB TC'";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }


    public function getFloorDataBymstrId($field_verification_dtl_id){
        try{
            $sql = "SELECT sfd.id,
            sfd.field_verification_dtl_id,
            sfd.saf_dtl_id,
            sfd.saf_floor_dtl_id,
            sfd.floor_mstr_id,
            floor.floor_name,
            sfd.usage_type_mstr_id,
            usage.usage_type,
            sfd.const_type_mstr_id,
            const.construction_type,
            sfd.occupancy_type_mstr_id,
            occup.occupancy_name,
            sfd.builtup_area,
            sfd.date_from,
            sfd.date_upto,
            sfd.emp_details_id,
            sfd.created_on,
            sfd.status,
            sfd.carpet_area
        FROM tbl_field_verification_floor_details sfd
            JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1 AND sfd.status=1
            JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id
            JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id
            JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id
            where sfd.field_verification_dtl_id=?";
                $ql= $this->db->query($sql, [$field_verification_dtl_id]);
                $result =$ql->getResultArray();
                return $result;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


}
<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class ObjectionModel extends Model
{
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function ObjectionStatus($objection_id_md5)
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->where("md5(id::text)", $objection_id_md5)
                            ->where("status", 1)
                            ->get();
        $objection = $builder->getFirstRow('array');
        if($objection["level_status"]==0)
        {
            return "Objection is Rejected";
        }
        elseif($objection["level_status"]==1)
        {
            return "Objection Process Pending At IT Head";
        }
        elseif ($objection["level_status"]==2)
        {
            return "Objection Process Pending At Tax Collector";
        }
        elseif ($objection["level_status"]==3)
        {
            return "Objection Process Pending At Section Head";
        }
        elseif ($objection["level_status"]==4)
        {
            return "Objection Process Pending At Executive Officer";
        }
        elseif ($objection["level_status"]==5)
        {
            return "Objection Approved Successfully";
        }
    }
    
    public function objectionTypeList()
    {
        $builder = $this->db->table('tbl_objection_type_mstr')
                    
                    ->where('status', 1)
                    ->orderBy('id')
                    ->get();
        return $builder->getResultArray();
    }

    public function InsertObjection($input)
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->insert($input);
        return $this->db->InsertID();
    }

    public function UpdateObjection($input)
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->where("id", $input["id"])
                            ->Update($input);
                            
        //echo $this->db->GetLastQuery();exit;
    }

    public function GetObjection($objection_id_md5)
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->where("md5(id::text)", $objection_id_md5)
                            ->where("status", 1)
                            ->get();
        return $builder->getFirstRow('array');
    }

    
    public function ObjectionList()
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->join("view_prop_dtl_owner_ward_prop_type_ownership_type", "view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_property_objection.prop_dtl_id")
                            ->where("tbl_property_objection.status", 1)
                            ->OrderBy("tbl_property_objection.id desc")
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getResultArray();
    }

    # List Which is pending at IT Head
    public function ObjectionMail1List()
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->join("view_prop_dtl_owner_ward_prop_type_ownership_type", "view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_property_objection.prop_dtl_id")
                            ->where("tbl_property_objection.level_status", 1)
                            ->where("tbl_property_objection.status", 1)
                            ->OrderBy("tbl_property_objection.id desc")
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getResultArray();
    }
    

    # List Which is pending at Section Head
    public function ObjectionMailSH()
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->join("view_prop_dtl_owner_ward_prop_type_ownership_type", "view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_property_objection.prop_dtl_id")
                            ->where("tbl_property_objection.level_status", 3)
                            ->where("tbl_property_objection.status", 1)
                            ->OrderBy("tbl_property_objection.id desc")
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getResultArray();
    }


    # List Which is pending at Executive
    public function ObjectionMailEO()
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->Select("tbl_property_objection.*, ward_no, tbl_property_objection.holding_no, tbl_property_objection.holding_no as new_holding_no, prop_address, assessment_type, , property_type, view_prop_dtl_owner_ward_prop_type_ownership_type.owner_name, view_prop_dtl_owner_ward_prop_type_ownership_type.mobile_no")
                            ->join("view_prop_dtl_owner_ward_prop_type_ownership_type", "view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_property_objection.prop_dtl_id")
                            ->where("tbl_property_objection.level_status", 4)
                            ->where("tbl_property_objection.status", 1)
                            ->OrderBy("tbl_property_objection.id desc")
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getResultArray();
    }

    # List Which is pending at Tax Collector
    public function ObjectionMailListTC() {
        $builder = $this->db->table('tbl_property_objection')
                            ->Select("tbl_property_objection.*, ward_no, saf_no, view_saf_dtl.holding_no, prop_address, assessment_type, , property_type, view_saf_owner_detail.owner_name, view_saf_owner_detail.mobile_no")
                            ->join("view_saf_dtl", "view_saf_dtl.saf_dtl_id=tbl_property_objection.saf_dtl_id")
                            ->join("view_saf_owner_detail", "view_saf_owner_detail.saf_dtl_id=tbl_property_objection.saf_dtl_id")
                            ->where("tbl_property_objection.level_status", 2)
                            ->where("tbl_property_objection.status", 1)
                            ->OrderBy("tbl_property_objection.id desc")
                            ->get();
        //echo $this->db->GetLastQuery();
        return [];
        return $builder->getResultArray();
    }

    public function GetObjectionByPropId($prop_dtl_id)
    {
        $builder = $this->db->table('tbl_property_objection')
                            ->where("prop_dtl_id", $prop_dtl_id)
                            ->where("status", 1)
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getFirstRow('array');
    }

    public function GetObjectionDetails($objection_id)
    {
        $builder = $this->db->table('tbl_property_objection_details')
                            ->select("tbl_property_objection_details.*, tbl_objection_type_mstr.type")
                            ->join("tbl_objection_type_mstr", "tbl_objection_type_mstr.id=tbl_property_objection_details.objection_type_id")
                            ->where("objection_id", $objection_id)
                            ->where("tbl_property_objection_details.status", 1)
                            ->get();
        //echo $this->db->GetLastQuery();
        return $builder->getResultArray();
    }

    public function GetObjectionFloorDetailsCitizen($objection_id)
    {
        $sql ="SELECT tbl_property_objection_floor_details.*,
                    tbl_usage_type_mstr.usage_type,
                    tbl_const_type_mstr.construction_type,
                    tbl_occupancy_type_mstr.occupancy_name,
                    tbl_floor_mstr.floor_name
                FROM tbl_property_objection_floor_details
                JOIN tbl_usage_type_mstr ON tbl_property_objection_floor_details.usage_type_mstr_id = tbl_usage_type_mstr.id
                JOIN tbl_const_type_mstr ON tbl_property_objection_floor_details.const_type_mstr_id = tbl_const_type_mstr.id
                JOIN tbl_occupancy_type_mstr ON tbl_property_objection_floor_details.occupancy_type_mstr_id = tbl_occupancy_type_mstr.id
                JOIN tbl_floor_mstr ON tbl_property_objection_floor_details.floor_mstr_id = tbl_floor_mstr.id
                WHERE tbl_property_objection_floor_details.objection_id=$objection_id AND objection_by='Citizen' AND tbl_property_objection_floor_details.status=1
                order by tbl_property_objection_floor_details.id;";
        $builder = $this->db->query($sql);
        return $builder->getResultArray();
    }

    public function GetObjectionFloorDetailsAssessment($objection_id)
    {
        $sql ="SELECT tbl_property_objection_floor_details.*,
                    tbl_usage_type_mstr.usage_type,
                    tbl_const_type_mstr.construction_type,
                    tbl_occupancy_type_mstr.occupancy_name,
                    tbl_floor_mstr.floor_name
                FROM tbl_property_objection_floor_details
                JOIN tbl_usage_type_mstr ON tbl_property_objection_floor_details.usage_type_mstr_id = tbl_usage_type_mstr.id
                JOIN tbl_const_type_mstr ON tbl_property_objection_floor_details.const_type_mstr_id = tbl_const_type_mstr.id
                JOIN tbl_occupancy_type_mstr ON tbl_property_objection_floor_details.occupancy_type_mstr_id = tbl_occupancy_type_mstr.id
                JOIN tbl_floor_mstr ON tbl_property_objection_floor_details.floor_mstr_id = tbl_floor_mstr.id
                WHERE tbl_property_objection_floor_details.objection_id=$objection_id AND objection_by='Assessment' AND tbl_property_objection_floor_details.status=1
                order by tbl_property_objection_floor_details.id;";
        $builder = $this->db->query($sql);
        return $builder->getResultArray();
    }

    public function InsertObjectionDetails($input)
    {
        $builder = $this->db->table('tbl_property_objection_details')
                            ->insert($input);
        //echo $this->getlastquery();
        return $this->db->InsertID();
    }

    public function InsertFloorObjectionDetails($input)
    {
        $builder = $this->db->table('tbl_property_objection_floor_details')
                            ->insert($input);
        //echo $this->getlastquery();
        return $this->db->InsertID();
        
    }
}
?>
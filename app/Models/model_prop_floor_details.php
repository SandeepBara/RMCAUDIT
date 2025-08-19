<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_floor_details extends Model
{
    protected $table = 'tbl_prop_floor_details';
    protected $allowedFields = [''];

	
    public function occupancy_detail($data)
    { 
        // echo "prop_dtl_id".$data;
		/*try{        
            $builder = $this->db->table("view_saf_floor_details")
                        ->select('*')
                        ->where('saf_dtl_id', $data)
                        ->get();

           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }*/
        // $sql = "SELECT *
		// FROM view_prop_floor_details
		// where prop_dtl_id=? ";
        //  $sql = "select view_prop_floor_details.*,tbl_prop_floor_details.date_from,tbl_prop_floor_details.date_upto from 
        // view_prop_floor_details inner join tbl_prop_floor_details on view_prop_floor_details.prop_dtl_id=
        // tbl_prop_floor_details.prop_dtl_id
		// where view_prop_floor_details.prop_dtl_id=? ";
         $sql = "select * from view_prop_floor_details where prop_dtl_id=? AND status=1 order by floor_mstr_id";
        $ql= $this->query($sql, [$data]);
        $result =$ql->getResultArray();
        return $result;
    }

    public function insertpropfloordetbysafid($input, $prop_dtl_id)
    {
        $sql_floor = "INSERT INTO tbl_prop_floor_details
                        (prop_dtl_id, floor_mstr_id, usage_type_mstr_id,
                        const_type_mstr_id, occupancy_type_mstr_id, builtup_area,
                        date_from, date_upto, carpet_area, 
                        emp_details_id, created_on, status)
                    SELECT 
                        '".$prop_dtl_id."', floor_mstr_id, usage_type_mstr_id,
                        const_type_mstr_id, occupancy_type_mstr_id, builtup_area,
                        date_from, date_upto, carpet_area,
                        '".$input['emp_details_id']."', '".$input['created_on']."', status 
                    FROM tbl_saf_floor_details WHERE saf_dtl_id='".$input['saf_dtl_id']."'";
            $this->db->query($sql_floor);
            return true;
    }

    

    public function insertpropfloordetbygbsafid($input, $prop_dtl_id)
    {
        $sql_floor = "INSERT INTO tbl_prop_floor_details
                        (prop_dtl_id, floor_mstr_id, usage_type_mstr_id,
                        const_type_mstr_id, occupancy_type_mstr_id, builtup_area,
                        date_from, date_upto, carpet_area, 
                        emp_details_id, created_on, status)
                    SELECT 
                        '".$prop_dtl_id."', floor_mstr_id, usage_type_mstr_id,
                        const_type_mstr_id, occupancy_type_mstr_id, builtup_area,
                        date_from, date_upto, carpet_area,
                        '".$input['emp_details_id']."', '".$input['created_on']."', status 
                    FROM tbl_govt_saf_floor_dtl WHERE govt_saf_dtl_id='".$input['govt_saf_dtl_id']."'";
            $this->db->query($sql_floor);
            //echo $this->db->getLastQuery();
            return true;
     }

   

    public function updatepropfloorBypropdetId($prop_dtl_id){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->update([
                                    'saf_deactive_status'=>1
                                    ]);
        //echo $this->db->getLastQuery();
    }
    public function floorDtlDeactivatedByPropDtlId($prop_dtl_id){
        return $this->db->table($this->table)
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->update(['status'=>0]);
    }

    public function getViewPropFloorDtlByPropDtlId($input) {
        $sql = "SELECT tbl_prop_floor_details.id,
                    tbl_prop_floor_details.prop_dtl_id,
                    tbl_prop_floor_details.floor_mstr_id,
                    tbl_prop_floor_details.builtup_area,
                    tbl_prop_floor_details.usage_type_mstr_id,
                    tbl_usage_type_mstr.usage_type,
                    tbl_floor_mstr.floor_name,
                    tbl_prop_floor_details.carpet_area,
                    tbl_prop_floor_details.const_type_mstr_id,
                    tbl_const_type_mstr.construction_type,
                    tbl_prop_floor_details.occupancy_type_mstr_id,
                    tbl_occupancy_type_mstr.occupancy_name,
                    tbl_prop_floor_details.date_from,
					tbl_prop_floor_details.date_upto
                FROM tbl_prop_floor_details
                JOIN tbl_usage_type_mstr ON tbl_prop_floor_details.usage_type_mstr_id = tbl_usage_type_mstr.id
                JOIN tbl_const_type_mstr ON tbl_prop_floor_details.const_type_mstr_id = tbl_const_type_mstr.id
                JOIN tbl_occupancy_type_mstr ON tbl_prop_floor_details.occupancy_type_mstr_id = tbl_occupancy_type_mstr.id
                JOIN tbl_floor_mstr ON tbl_prop_floor_details.floor_mstr_id = tbl_floor_mstr.id
                WHERE tbl_prop_floor_details.prop_dtl_id=".$input['prop_dtl_id']." AND tbl_prop_floor_details.status=1;";
        $builder = $this->db->query($sql);
        
        return $builder->getResultArray($input);

    }

    public function getFloorByPropId($input)
    {
        $builder = $this->db->table($this->table)
                            ->where("prop_dtl_id", $input["prop_dtl_id"])
                            ->where("status", 1)
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

    public function isTrust($prop_dtl_id)
    {
        try{
            $builder= $this->db->table($this->table)
                            ->select('count(id) as floorcount')
                            ->where('prop_dtl_id', $prop_dtl_id)
                            ->where('status', 1)
                            ->whereIn('usage_type_mstr_id', [43,12])
                            ->get()
                            ->getFirstRow('array');
            //echo $this->db->getLastQuery();
            if($builder['floorcount']>0) {$retn = true;}else{$retn = false;}
            return $retn;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}
?>
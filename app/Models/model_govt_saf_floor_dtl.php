<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_floor_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_floor_dtl';
    protected $allowedFields = ['id','building_type'];

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
	
	
	public function appfloor_detail($input){
		
		$builder = "select flr_dtl.builtup_area,flr_dtl.carpet_area,flr_dtl.date_from,flr_dtl.date_upto,
					colmst.colony_name,flrmst.floor_name,usgtyp.usage_type,constyp.construction_type,occtyp.occupancy_name
					from tbl_govt_saf_floor_dtl flr_dtl
					left join tbl_colony_mstr colmst on flr_dtl.colony_mstr_id = colmst.id
					left join tbl_floor_mstr flrmst on flr_dtl.floor_mstr_id = flrmst.id
					left join tbl_usage_type_mstr usgtyp on flr_dtl.usage_type_mstr_id = usgtyp.id
					left join tbl_const_type_mstr constyp on flr_dtl.const_type_mstr_id = constyp.id
					left join tbl_occupancy_type_mstr occtyp on flr_dtl.occupancy_type_mstr_id = occtyp.id
					where govt_saf_dtl_id='".$input."'";
		$builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }

    public function countRowByGBSafDtlId($govt_saf_dtl_id){
        try{
            return $this->db->table($this->table)
                            ->select('COUNT(DISTINCT(usage_type_mstr_id)) as count_row')
                            ->where('govt_saf_dtl_id', $govt_saf_dtl_id)
                            ->where('status', 1)
                            ->get()
                            ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataByGBSafDtlId($govt_saf_dtl_id)
    {
        try{
            $builder= $this->db->table('tbl_govt_saf_floor_dtl')
                            ->select('floor_mstr_id, usage_type_mstr_id, const_type_mstr_id, occupancy_type_mstr_id, builtup_area, date_from, date_upto')
                            ->where('govt_saf_dtl_id', $govt_saf_dtl_id)
                            ->where('status', 1)
                            ->orderBy('date_from', 'ASC')
                            ->get()
                            ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getusagecodeByGBSafId($govt_saf_dtl_id)
    {
        $sql = "SELECT u.usage_code FROM tbl_govt_saf_floor_dtl s join tbl_usage_type_mstr u on(s.usage_type_mstr_id=u.id) where s.govt_saf_dtl_id='$govt_saf_dtl_id'";
        $q = $this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }
}
?>

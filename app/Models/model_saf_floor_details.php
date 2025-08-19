<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_floor_details extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_floor_details';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id', 'floor_mstr_id', 'usage_type_mstr_id', 'const_type_mstr_id', 'occupancy_type_mstr_id', 'builtup_area', 'date_from', 'date_upto', 'emp_details_id', 'created_on', 'status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function occupancy_detail($data)
    { 
		/*try{        
            $builder = $this->db->table("view_saf_floor_details")
                        ->select('*')
                        ->where('saf_dtl_id', $data)
                        ->get();

           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }*/
        $sql = "SELECT *
		FROM view_saf_floor_details
		where saf_dtl_id=? ";
        $ql= $this->query($sql, [$data]);
        $result =$ql->getResultArray();
        return $result;
    }

    public function insertData($input){
        try{
            return $this->db->table($this->table)
                            ->insert($input);  
                //echo $this->db->getLastQuery();         
            //return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataBySafDtlId($input){
        try{
            return $this->db->table($this->table)
                            ->select('id, floor_mstr_id, usage_type_mstr_id, const_type_mstr_id, occupancy_type_mstr_id, builtup_area, date_from, date_upto')
                            ->where('saf_dtl_id', $input['saf_dtl_id'])
                            ->where('status', 1)
                            ->orderBy('date_from', 'ASC')
                            ->get()
                            ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataBySafDtlIdd($saf_dtl_id){
        try{
            $builder= $this->db->table($this->table)
                            ->select('floor_mstr_id, usage_type_mstr_id, const_type_mstr_id, occupancy_type_mstr_id, builtup_area, date_from, date_upto')
                            ->where('saf_dtl_id', $saf_dtl_id)
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

    public function countRowBySafDtlId($saf_dtl_id){
        try{
            return $this->db->table($this->table)
                            ->select('COUNT(DISTINCT(usage_type_mstr_id)) as count_row')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('status', 1)
                            ->get()
                            ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
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


    public function getusagecodeBySafId($saf_dtl_id){
        $sql = "SELECT u.usage_code FROM tbl_saf_floor_details s join tbl_usage_type_mstr u on(s.usage_type_mstr_id=u.id) where s.saf_dtl_id='$saf_dtl_id'";
        $q = $this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }

    public function getusagecodeByGBSafId($govt_saf_dtl_id)
    {
        $sql = "SELECT u.usage_code FROM tbl_govt_saf_floor_dtl s join tbl_usage_type_mstr u on(s.usage_type_mstr_id=u.id) where s.govt_saf_dtl_id='$govt_saf_dtl_id'";
        $q = $this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }


	public function floordate_dtl($data)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('min(date_from) as date_from')
                        ->where('saf_dtl_id', $data)
                        ->get();

           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
	}

    public function floorresdtl($data)
    {
        try{                
            $builder = $this->db->table($this->table)
                        ->distinct()
                        ->select('usage_type_mstr_id')
                        ->where('saf_dtl_id', $data)
                        ->orderBy('usage_type_mstr_id', 'ASC')
                        ->get();
               
            // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function safflrdtl_deactive($data) {
         return $this->db->table($this->table)
                ->where('saf_dtl_id', $data)
                ->update([
                    'status'=>0
                ]); 
    }

    public function isTrust($saf_dtl_id)
    {
        try{
            $builder= $this->db->table($this->table)
                            ->select('count(id) as floorcount')
                            ->where('saf_dtl_id', $saf_dtl_id)
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
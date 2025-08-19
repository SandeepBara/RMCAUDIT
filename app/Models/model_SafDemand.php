<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_SafDemand extends Model
{
		protected $db;
    protected $table = 'tbl_govt_saf_dtl';
    //protected $allowedFields = ['id','colony_name','colony_address'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


	public function checkdata($input){
		//print_r($input);
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('application_no', $input['application_no']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray()[0];
        return $builder;
    }

		public function checkdata_dcrypt($id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('application_no')
                    ->where('status',1)
                    ->where('md5(id::text)',$id)
                    ->get();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

		public function getdata($input){

        try{
            return $this->db->table("tbl_govt_saf_dtl")
             ->select('tbl_govt_saf_dtl.*,tbl_govt_building_type_master.building_type,
						 tbl_prop_usage_type_mstr.prop_usage_type,tbl_ownership_type_mstr.ownership_type,
						 view_ward_mstr.ward_no')
             ->join('tbl_govt_building_type_master', 'tbl_govt_saf_dtl.govt_building_type_mstr_id=tbl_govt_building_type_master.id')
						->join('tbl_prop_usage_type_mstr', 'tbl_govt_saf_dtl.prop_usage_type_mstr_id=tbl_prop_usage_type_mstr.id')
						->join('tbl_ownership_type_mstr', 'tbl_govt_saf_dtl.prop_type_mstr_id=tbl_ownership_type_mstr.id')
						->join('view_ward_mstr', 'tbl_govt_saf_dtl.ward_mstr_id=view_ward_mstr.id')
						->where('tbl_govt_saf_dtl.application_no', $input)
            ->where('tbl_govt_saf_dtl.status', 1)
            ->get()
            ->getResultArray();
         }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function getfy($id){
        try{
						  return $this->db->table("tbl_govt_saf_demand_dtl")
                        ->select('view_fy_mstr.fy,view_fy_mstr.id')
                         ->join('view_fy_mstr', 'tbl_govt_saf_demand_dtl.fy_mstr_id=view_fy_mstr.id')
												->where('tbl_govt_saf_demand_dtl.govt_saf_dtl_id', $id)
                        ->where('tbl_govt_saf_demand_dtl.paid_status', 0)
 												->groupby('view_fy_mstr.fy,view_fy_mstr.id')
												->orderby('view_fy_mstr.id','asc')
                         ->get()
                        ->getResultArray();
												  $this->db->getLastQuery();
         }catch(Exception $e){
            return $e->getMessage();
        }
    }

		//get designation
		public function getdesignation($id){
        try{
                return $this->db->table("tbl_govt_saf_officer_dtl")
                        ->select('tbl_govt_saf_officer_dtl.designation')
 												->where('tbl_govt_saf_officer_dtl.govt_saf_dtl_id',$id)
                        ->get()
                        ->getResultArray();
         }catch(Exception $e){
            return $e->getMessage();
        }
    }

 // get quarter
		public function getquarter($ackn,$fy_id){
        try{
            $builder = $this->db->table("tbl_govt_saf_dtl")
										->select('tbl_govt_saf_demand_dtl.id,tbl_govt_saf_demand_dtl.qtr')
										->join('tbl_govt_saf_demand_dtl', 'tbl_govt_saf_dtl.id=tbl_govt_saf_demand_dtl.govt_saf_dtl_id')
										->where('tbl_govt_saf_dtl.application_no', $ackn)
										->Where('tbl_govt_saf_demand_dtl.fy_mstr_id', $fy_id)
										->Where('tbl_govt_saf_demand_dtl.paid_status', 0)
										->orderby('tbl_govt_saf_demand_dtl.id','asc')
										 ->get();
                     // return $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

public function getdemand($fy,$qtr,$id){
		$sql1 = "SELECT sum(amount), govt_saf_tax_dtl_id, MIN(id) AS govt_saf_demand_dtl_id FROM tbl_govt_saf_demand_dtl
		       WHERE govt_saf_dtl_id=$id AND paid_status='0' AND  id<=(SELECT id FROM tbl_govt_saf_demand_dtl
					 WHERE govt_saf_dtl_id=$id AND fy_mstr_id=$fy AND qtr=$qtr AND paid_status='0')
           GROUP BY govt_saf_tax_dtl_id ORDER BY govt_saf_tax_dtl_id";

		$q1 = $this->db->query($sql1);
		$array1 = $q1->getResultArray();

		$WhereIn = "";
		$demand_id="";
		foreach ($array1 as $key => $value) {
			if($key==0) {
				$WhereIn .= $value['govt_saf_tax_dtl_id'];
				$demand_id .= $value['govt_saf_demand_dtl_id'];
			} else {
				$WhereIn .= ", ".$value['govt_saf_tax_dtl_id'];
				$demand_id .=", ". $value['govt_saf_demand_dtl_id'];
			}
		}


		$sql2 = "select tbl_govt_saf_tax_dtl.*,view_fy_mstr.fy , sum (holding_tax+water_tax+education_cess+health_cess+latrine_tax) as quarter
		 from tbl_govt_saf_tax_dtl
		 join view_fy_mstr on tbl_govt_saf_tax_dtl.fy_mstr_id = view_fy_mstr.id
		 where tbl_govt_saf_tax_dtl.govt_saf_dtl_id =$id AND tbl_govt_saf_tax_dtl.id IN (".$WhereIn.")
		 group by (tbl_govt_saf_tax_dtl.id,view_fy_mstr.fy)";

		$q2 = $this->db->query($sql2);
		$array2 = $q2->getResultArray();

		$sql3 = "select view_fy_mstr.fy as financial_year,tbl_govt_saf_demand_dtl.qtr as qtrs
		 from tbl_govt_saf_demand_dtl
		 join view_fy_mstr on tbl_govt_saf_demand_dtl.fy_mstr_id = view_fy_mstr.id
		 where  tbl_govt_saf_demand_dtl.id IN (".$demand_id.")
		 group by (view_fy_mstr.fy,tbl_govt_saf_demand_dtl.qtr)";

		 $q3 = $this->db->query($sql3);
		 $array3 = $q3->getResultArray();

		$result = array_map(function($array1,$array2,$array3){
	return array_merge(isset($array1) ? $array1 : array(), isset($array2) ? $array2 : array(),isset($array3) ? $array3 : array());
},$array1,$array2,$array3);

		return $result;
}

public function getpenalty($id){
		try{
				$builder = $this->db->table("tbl_govt_saf_demand_dtl")
								->select('view_fy_mstr.*,tbl_govt_saf_demand_dtl.amount,tbl_govt_saf_demand_dtl.qtr')
								->join('view_fy_mstr', 'tbl_govt_saf_demand_dtl.fy_mstr_id=view_fy_mstr.id')
								->where('tbl_govt_saf_demand_dtl.govt_saf_dtl_id', $id)
								->orderby('tbl_govt_saf_demand_dtl.id','asc')
								 ->get();

								 // return $this->db->getLastQuery();
			 return $builder->getResultArray();
		}catch(Exception $e){
				echo $e->getMessage();
		}
}


}
?>

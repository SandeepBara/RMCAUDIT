<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_saf_collection extends Model
{
	protected $db;
    protected $table = 'tbl_saf_collection';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


	public function safcollection_dtl(){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('status', 1);
		$builder->where('qtr', 4);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder;
    }
	
	
	public function collection_dtl($data){
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_saf_collection
			    where transaction_id=?
				Group by transaction_id";
				$ql= $this->db->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	public function updateStatusClear($transaction_id){
    	try{
    		return $builder = $this->db->table($this->table)
    				 ->where('transaction_id',$transaction_id)
    				 ->update([
    				 			'status'=>1
    				 		  ]);
    	}catch(Exception $e){
			echo $e->getMessage();
    	}
    }
    public function updateStatusNotClear($transaction_id){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('transaction_id',$transaction_id)
                     ->update([
                                'status'=>3
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	public function safDetails($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id,saf_demand_id')
                      ->where('transaction_id',$transaction_id)
                      ->where('status',1)
                      ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	public function demandCollection($data)
	{ $date_cls= date("Y-m-d H:i:s");
        $sql1 = "INSERT INTO tbl_saf_collection (saf_dtl_id,transaction_id,saf_demand_id,fy_mstr_id,qtr,
			amount,holding_tax,water_tax,education_cess,health_cess,latrine_tax,additional_tax,
			collection_type,created_on,ward_mstr_id,fine_months,fine_amt)
			SELECT saf_dtl_id,".$data['insertPayment'].",id,fy_id,qtr,total_tax,holding_tax,water_tax,
			education_cess,health_cess,latrine_tax,additional_tax,'SAF','$date_cls',ward_mstr_id,
			".$data['pntmnth'].",".$data['tol_pent']."
			FROM view_saf_demand
			WHERE id=".$data['resultid']['id'];
			$ql= $this->query($sql1);
			//$result1 =$ql->getResultArray();
			
			
    }
	
	
	/*
	public function total_current_collection_amount($data){
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax,
			    (SELECT count(saf_dtl_id) as crrnthouse FROM tbl_saf_collection WHERE status=1 AND fy_mstr_id=".$data['fyid'].")
				FROM tbl_saf_collection
			    where fy_mstr_id=".$data['fyid'];
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	
	public function total_arrear_collection_amount($data){
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax,
			    (SELECT count(saf_dtl_id) as arrearhouse FROM tbl_saf_collection WHERE status=1 AND fy_mstr_id<".$data['fyid'].")
				FROM tbl_saf_collection
			    where status=1 AND fy_mstr_id<".$data['fyid'];
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	*/
	/*
	public function current_saf_collection_house($data){
		
		$demand_amnt = "SELECT count(saf_dtl_id) as house FROM tbl_saf_collection
		WHERE status=1 AND fy_mstr_id=?
		group by saf_dtl_id
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	public function arrear_saf_collection_house($data){
		
		$demand_amnt = "SELECT count(saf_dtl_id) as arrearhouse FROM tbl_saf_collection
		WHERE status=1 AND fy_mstr_id<?
		group by saf_dtl_id
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    } 
*/
	public function current_saf_month_collection_amount(){
		//print_r($data);
		$month_from = date("Y-m-01");
		$month_to = date("Y-m-t");
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_saf_collection
				where created_on::date BETWEEN '$month_from' AND '$month_to'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	
	public function current_saf_month_house_hold(){
		$month_from = date("Y-m-01");
		$month_to = date("Y-m-t");
		$demand_amnt = "SELECT count(saf_dtl_id) as house FROM tbl_saf_collection
		WHERE status=1 AND created_on::date BETWEEN '$month_from' AND '$month_to'
		group by saf_dtl_id
		";
		$ql= $this->query($demand_amnt);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	/*
	public function current_fy_saf_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_saf_collection
				where created_on::date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	
	public function dy_saf_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_saf_collection
				where created_on::date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');;
				return $result;
       
    }
	
	*/
	public function updateStatus($transaction_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('transaction_id',$transaction_id)
                            ->where('status',1)
                            ->update([
                                'status'=>0
                            ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	public function getAllDemandIdthroughTransactionId($transaction_id)
    {
        $sql="select string_agg(saf_demand_id::text,',') as demand_id from tbl_saf_collection where transaction_id=$transaction_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['demand_id'];

    }
}
?>
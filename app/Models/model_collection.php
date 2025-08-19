<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_collection extends Model
{
	protected $db;
    protected $table = 'tbl_collection';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


	public function collection_dtl(){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('status', 1);
		$builder->where('qtr', 4);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder;
    }
	
	public function collection_propdtl($data){
		// print_r('transaction_id '.$data);
        // return;
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax,
				sum(amount) as demand_amount 
			    FROM tbl_collection
			    where transaction_id=?
				Group by transaction_id";
				$ql= $this->db->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }

    public function Transaction_Collection_Details($trxn){
        
        $tran_fy=getFY($trxn["tran_date"]);
        $sql="SELECT DISTINCT first_value(qtr) OVER wmin AS min_quarter, 
            first_value(fyear) OVER wmin AS min_year, 
            first_value(qtr) OVER wmax AS max_quarter, 
            first_value(fyear) OVER wmax AS max_year
            FROM tbl_collection where transaction_id=$trxn[id] and collection_type ='$trxn[tran_type]'
            WINDOW wmin AS (PARTITION BY prop_dtl_id ORDER BY fyear ASC, qtr ASC), 
            wmax AS (PARTITION BY prop_dtl_id ORDER BY fyear DESC, qtr DESC)";
        $sql= $this->db->query($sql);
		$paid_fy = $sql->getFirstRow('array');
        //print_var($paid_fy);
        
        //paid upto current fy
        if($tran_fy==$paid_fy["max_year"])
        {

            $sql="SELECT DISTINCT first_value(qtr) OVER wmin AS min_quarter, 
                        first_value(fyear) OVER wmin AS min_year, 
                        first_value(qtr) OVER wmax AS max_quarter, 
                        first_value(fyear) OVER wmax AS max_year
                FROM tbl_collection where transaction_id=$trxn[id] and collection_type ='$trxn[tran_type]' and fyear!='$paid_fy[max_year]'
                WINDOW wmin AS (PARTITION BY prop_dtl_id ORDER BY fyear ASC, qtr ASC), 
                wmax AS (PARTITION BY prop_dtl_id ORDER BY fyear DESC, qtr DESC)";
            $sql= $this->db->query($sql);
            $arrear_fy = $sql->getFirstRow('array');

            $sql="SELECT sum( case when holding_tax > amount then amount else holding_tax end) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
                    sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax 
                from tbl_collection where transaction_id=$trxn[id] and collection_type ='$trxn[tran_type]' and tbl_collection.fyear!='$paid_fy[max_year]'";  
            $sql= $this->db->query($sql);
            $arrear_amount = $sql->getFirstRow('array');


            $sql="SELECT DISTINCT first_value(qtr) OVER wmin AS min_quarter, 
                        first_value(fyear) OVER wmin AS min_year, 
                        first_value(qtr) OVER wmax AS max_quarter, 
                        first_value(fyear) OVER wmax AS max_year
                FROM tbl_collection where transaction_id=$trxn[id] and collection_type ='$trxn[tran_type]' and fyear='$paid_fy[max_year]'
                WINDOW wmin AS (PARTITION BY prop_dtl_id ORDER BY fyear ASC, qtr ASC), 
                wmax AS (PARTITION BY prop_dtl_id ORDER BY fyear DESC, qtr DESC)";
            $sql= $this->db->query($sql);
            $current_fy = $sql->getFirstRow('array');

            $sql="SELECT sum( case when holding_tax > amount then amount else holding_tax end) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
                    sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax 
                from tbl_collection where transaction_id=$trxn[id] and collection_type ='$trxn[tran_type]' and tbl_collection.fyear='$paid_fy[max_year]'";  
            $sql= $this->db->query($sql);
            $current_amount = $sql->getFirstRow('array');

        }
        // only arrear payment paid
        else
        {
            $arrear_fy=$paid_fy;
            $sql="SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
                    sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax 
                from tbl_collection where transaction_id=$trxn[id] and collection_type ='Property' and tbl_collection.fyear!='$paid_fy[max_year]'";  
            $sql= $this->db->query($sql);
            $arrear_amount = $sql->getFirstRow('array');
            $current_fy=[];
            $current_amount=[];
        }

        return ["arrear_fy"=> $arrear_fy, "arrear_amount"=> $arrear_amount, "current_fy"=> $current_fy, "current_amount"=> $current_amount];
    }
	
    
    public function insertpropcolldetbysafid($saf_dtl_id,$prop_dtl_id,$prop_demand_id,$created_on){
        $sql_col = "insert into tbl_collection(prop_dtl_id,transaction_id,prop_demand_id,fy_mstr_id,qtr,amount,holding_tax,water_tax,
        education_cess,health_cess,lighting_tax,latrine_tax,additional_tax,collection_type,created_on,status)
        select '".$prop_dtl_id."',transaction_id,'".$prop_demand_id."',fy_mstr_id,qtr,amount,holding_tax,water_tax,education_cess,health_cess,0,latrine_tax,
        additional_tax,collection_type,'".$created_on."',status from tbl_saf_collection where saf_dtl_id='".$saf_dtl_id."'";
            $this->db->query($sql_col);
        //echo $this->db->getLastQuery();
            $prop_col_details_id = $this->db->insertID();
        //echo $this->db->getLastQuery();
         //return $prop_col_details_id;
     }
    public function updateStatusClear($transaction_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('transaction_id',$transaction_id)
                            ->where('status',1)
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
                            ->where('status',1)
                            ->update([
                                'status'=>3
                            ]);
                              //echo $this->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function collectionDetails($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id,prop_demand_id')
                      ->where('transaction_id',$transaction_id)
                      ->where('status',1)
                      ->get();
                      //echo $this->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function demandCollection($data)
	{ $date_crnt = date("Y-m-d H:i:s");
        $sql1 = "INSERT INTO tbl_collection (prop_dtl_id,transaction_id,prop_demand_id,fy_mstr_id,qtr,amount,holding_tax,water_tax,education_cess,health_cess,latrine_tax,additional_tax,collection_type,created_on,ward_mstr_id,fine_months,fine_amt)
			SELECT prop_dtl_id, ".$data['insertPayment'].", id,fy_id,qtr,amount,holding_tax,water_tax,education_cess,health_cess,latrine_tax,additional_tax,'Property', '$date_crnt',ward_mstr_id,".$data['pntmnth'].",".$data['tol_pent']."
			FROM view_prop_demand
			WHERE id=".$data['resultid']['id'];
			$ql= $this->query($sql1);
			//$result1 =$ql->getResultArray();
			//echo $this->getLastQuery();
    }
	
	public function total_current_collection_amount($data){
		//print_r($data);
		$sql = "select sum(amnt)as crntcollamnt, count(prop_dtl_id)as crncollthh from 
				(select sum(collection_amount)as amnt,prop_dtl_id from view_prop_dtl_collection 
				where deactive_status=0 AND fy_mstr_id=? group by prop_dtl_id) tblcoll";
				$ql= $this->db->query($sql, [$data['fyid']]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	public function total_arrear_collection_amount($data){
		//print_r($data);
		$sql = "select sum(amnt)as arrearcollamnt, count(prop_dtl_id)as arrearcollthh from 
				(select sum(collection_amount)as amnt,prop_dtl_id from view_prop_dtl_collection 
				where deactive_status=0 AND fy_mstr_id<? group by prop_dtl_id) tblcoll";
				$ql= $this->db->query($sql, [$data['fyid']]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	public function current_month_collection_amount(){
		//print_r($data);
		$month_from = date("Y-m-01");
		$month_to = date("Y-m-t");
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_collection
				where created_on::date BETWEEN '$month_from' AND '$month_to'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	public function current_month_house_hold(){
		$month_from = date("Y-m-01");
		$month_to = date("Y-m-t");
		$demand_amnt = "SELECT count(prop_dtl_id) as house FROM tbl_collection
		WHERE status=1 AND created_on::date BETWEEN '$month_from' AND '$month_to'
		group by prop_dtl_id
		";
		$ql= $this->query($demand_amnt);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	/*
	public function current_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(collection_amount) as fy_coll
			    FROM view_prop_dtl_collection
				where deactive_status=0 AND created_on::date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	public function dy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "SELECT sum(collection_amount) as dy_coll
			    FROM view_prop_dtl_collection
				where deactive_status=0 AND created_on::date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	*/
	public function updateStatus($transaction_id)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                            ->where('transaction_id',$transaction_id)
                            ->where('status',1)
                            ->set('status',0)
                            ->set('deactive_status','1')
                            ->update();
                            // ->update([
                            //     'status'=> 0
                            // ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function getTotalCancelCash($data){
    	try{
            $builder = $this->db->table($this->table);
            $builder=$builder->select('COALESCE(SUM(amount),0) as amount,COALESCE(COUNT(DISTINCT prop_dtl_id),0) as holding');
            $builder=$builder->where('date(created_on) >=',$data['from_date']);
            $builder=$builder->where('date(created_on) <=',$data['to_date']);
            $builder=$builder->where('collection_type','Property');
            if($data['ward_mstr_id']!=""){
              $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder=$builder->where('status',3);
            $builder=$builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getHoldingTax($data){
    	try{
            $builder = $this->db->table($this->table);
            $builder=$builder->select('COALESCE(SUM(holding_tax),0) as holding_tax,COALESCE(SUM(water_tax),0) as water_tax,COALESCE(SUM(latrine_tax),0) as latrine_tax,COALESCE(SUM(education_cess),0) as education_cess,COALESCE(SUM(health_cess),0) as health_cess');
            $builder=$builder->where('date(created_on) >=',$data['from_date']);
            $builder=$builder->where('date(created_on) <=',$data['to_date']);
            $builder=$builder->where('collection_type','Property');
            if($data['ward_mstr_id']!=""){
              $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder=$builder->where('status',1);
            $builder=$builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllDemandIdthroughTransactionId($transaction_id)
    {   
        $sql="select string_agg(prop_demand_id::text,',') as demand_id from tbl_collection where transaction_id=$transaction_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['demand_id'];

    }
	
	
	
	public function total_dcbprop(){
		$sql = "select sum(amnt)as crntcollamnt from 
			(select sum(collection_amount)as amnt from view_prop_dtl_collection 
			where deactive_status=0 group by prop_dtl_id) tblcoll";
			$ql= $this->db->query($sql);
			//echo $this->db->getLastQuery();
			$result =$ql->getResultArray()[0];
			return $result;
       
    }
    
    
}
?>
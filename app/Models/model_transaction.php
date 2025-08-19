<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_transaction extends Model
{
	protected $db;
    protected $table = 'tbl_transaction';
    protected $allowedFields = ['tran_no', 'penalty_amt', 'from_fy_mstr_id', 'from_qtr', 'remarks', 'tran_verify_by_emp_details_id', 'tran_verify_date_time', 'prop_dtl_id', 'tran_by_emp_details_id', 'upto_fy_mstr_id', 'upto_qtr', 'created_on', 'discount_amt', 'payable_amt', 'tran_mode_mstr_id', 'tran_date','tran_type','ward_mstr_id','verify_date','verified_by','verify_status','notification_id'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function saf_pay_now($input, $cheque_dtl=[])
    {
        $sql="select * from saf_pay_now($input[saf_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount]);";

        $builder=$this->db->query($sql);
        $trxn_id= $builder->getFirstRow('array')['saf_pay_now'];

        if($trxn_id && !empty($cheque_dtl))
        {
            $this->db->table("tbl_cheque_details")
                        ->Insert([
                            "prop_dtl_id"=> $input["saf_dtl_id"],
                            "transaction_id"=> $trxn_id,
                            "cheque_date"=> $cheque_dtl["cheque_date"],
                            "bank_name"=> $cheque_dtl["bank_name"],
                            "branch_name"=> $cheque_dtl["branch_name"],
                            "bounce_status"=> 0, // Not Bounced
                            "status"=> 2, // Not Cleared
                            "created_on"=> "NOW()",
                            "cheque_no"=> $cheque_dtl["cheque_no"],
                            "tran_type"=> "Saf",
                        ]);
            $this->db->insertID();
        }
        $sql = "SELECT assessment_type, previous_holding_id FROM tbl_saf_dtl WHERE id=".$input["saf_dtl_id"];
        if($safResult = $this->db->query($sql)->getFirstRow("array")) {
            if ($safResult["assessment_type"]=="Reassessment") {
                $sql = "UPDATE tbl_prop_demand SET paid_status=7, status=0 WHERE paid_status=0 AND status=1 AND prop_dtl_id=".$safResult["previous_holding_id"];
                $this->db->query($sql);
            }
        }
        return $trxn_id;
    }

    public function prop_pay_now_old($input, $cheque_dtl=[])
    {
        

        $sql="select * from prop_pay_now($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount]);";

        $builder=$this->db->query($sql);
        $trxn_id= $builder->getFirstRow('array')['prop_pay_now'];

        if($trxn_id && !empty($cheque_dtl)) {
            $this->db->table("tbl_cheque_details")
                        ->Insert([
                            "prop_dtl_id"=> $input["prop_dtl_id"],
                            "transaction_id"=> $trxn_id,
                            "cheque_date"=> $cheque_dtl["cheque_date"],
                            "bank_name"=> $cheque_dtl["bank_name"],
                            "branch_name"=> $cheque_dtl["branch_name"],
                            "bounce_status"=> 0, // Not Bounced
                            "status"=> 2, // Not Cleared
                            "created_on"=> "NOW()",
                            "cheque_no"=> $cheque_dtl["cheque_no"],
                            "tran_type"=> "Property",
                        ]);
            $this->db->insertID();
        }
        
        return $trxn_id;
    }

    public function prop_pay_now($input, $cheque_dtl=[])
    {
        $unpaidDemandArr = $this->db->query("select * from tbl_prop_demand where status =1 and paid_status = 0 and prop_dtl_id=".$input["prop_dtl_id"]." order by  fyear,qtr ASC")->getResultArray();

        $sql="select * from prop_pay_now_new($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount]);";

        $builder=$this->db->query($sql);
        $trxn_id= $builder->getFirstRow('array')['prop_pay_now_new'];

        if($trxn_id && !empty($cheque_dtl)) {
            $this->db->table("tbl_cheque_details")
                        ->Insert([
                            "prop_dtl_id"=> $input["prop_dtl_id"],
                            "transaction_id"=> $trxn_id,
                            "cheque_date"=> $cheque_dtl["cheque_date"],
                            "bank_name"=> $cheque_dtl["bank_name"],
                            "branch_name"=> $cheque_dtl["branch_name"],
                            "bounce_status"=> 0, // Not Bounced
                            "status"=> 2, // Not Cleared
                            "created_on"=> "NOW()",
                            "cheque_no"=> $cheque_dtl["cheque_no"],
                            "tran_type"=> "Property",
                        ]);
            $this->db->insertID();
        }

        $testDemndSql = "select * from tbl_prop_demand where status =1 and paid_status = 0 and prop_dtl_id=".$input["prop_dtl_id"]." order by  fyear,qtr ASC";
        $testDemand = $this->db->query($testDemndSql)->getFirstRow("array");
        $flag = db_connect(dbSystem())->table("site_maintenance")->get()->getFirstRow("array");
        $certificateFlag = ($flag && $flag["property_certificate"]=="t")?true:false;

        if((!$certificateFlag) && $input["fy"]==getFY() && getQtr() <=3 && !$testDemand){
            $pamentMode = $input["payment_mode"];
            $qtr = getQtr();
            $type=certificateType($pamentMode,$qtr);
            $this->db->table("tbl_certificate")->insert(
                [
                    "is_full_payment"=>true,"tran_id"=>$trxn_id,
                    "demand_json"=>json_encode($unpaidDemandArr),
                    "certificate_id"=>$trxn_id."-".$input["prop_dtl_id"],
                    "type"=>$type,
                    "fyear"=>getFY(),
                    "is_date"=>date("Y-m-d")
                ]
            );
        }
        return $trxn_id;
    }


    public function prop_pay_now_online($input, $cheque_dtl=[])
    {
        $sql="select * from prop_pay_now_online($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount], '$input[payment_date]');";

        $builder=$this->db->query($sql);
        $trxn_id= $builder->getFirstRow('array')['prop_pay_now_online'];

        if($trxn_id && !empty($cheque_dtl)) {
            $this->db->table("tbl_cheque_details")
                        ->Insert([
                            "prop_dtl_id"=> $input["prop_dtl_id"],
                            "transaction_id"=> $trxn_id,
                            "cheque_date"=> $cheque_dtl["cheque_date"],
                            "bank_name"=> $cheque_dtl["bank_name"],
                            "branch_name"=> $cheque_dtl["branch_name"],
                            "bounce_status"=> 0, // Not Bounced
                            "status"=> 2, // Not Cleared
                            "created_on"=> "NOW()",
                            "cheque_no"=> $cheque_dtl["cheque_no"],
                            "tran_type"=> "Property",
                        ]);
            $this->db->insertID();
        }
        return $trxn_id;
    }

    // Using In Cash Verification
    public function PropertyPaymentList($tran_by_emp_details_id, $tran_date)
    {
        $sql="select tbl_transaction.id as transaction_id, tran_no, tran_mode, from_qtr, from_fyear, upto_qtr, upto_fyear, payable_amt, tbl_transaction.prop_dtl_id, tran_type, new_ward_no, owner_name, coalesce(new_holding_no, holding_no) as holding_no, prop_address, verify_status, emp_name as verified_by, verify_date
            from tbl_transaction
            join view_prop_dtl_owner_ward_prop_type_ownership_type on view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_transaction.prop_dtl_id
            left join view_emp_details on view_emp_details.id=tbl_transaction.verified_by
            where tbl_transaction.tran_by_emp_details_id=$tran_by_emp_details_id and tbl_transaction.tran_date='$tran_date'
            and tbl_transaction.status in (1, 2) and tbl_transaction.tran_type='Property'
            order by tbl_transaction.tran_mode";
        $sql= $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
    }

    // Using In Cash Verification
    public function SafPaymentList($tran_by_emp_details_id, $tran_date)
    {
        $sql="select tbl_transaction.id as transaction_id, tran_no, tran_mode, from_qtr, from_fyear, upto_qtr, upto_fyear, payable_amt, tbl_transaction.prop_dtl_id, tran_type, ward_no, owner_name, saf_no, prop_address, verify_status, emp_name as verified_by, verify_date
        from tbl_transaction
        join view_saf_dtl_owner_ward_prop_type_ownership_type on view_saf_dtl_owner_ward_prop_type_ownership_type.saf_dtl_id=tbl_transaction.prop_dtl_id
        left join view_emp_details on view_emp_details.id=tbl_transaction.verified_by
        where tbl_transaction.tran_by_emp_details_id=$tran_by_emp_details_id and tbl_transaction.tran_date='$tran_date'
        and tbl_transaction.status in (1, 2) and tbl_transaction.tran_type='Saf'
        order by tbl_transaction.tran_mode";
        //print_var($sql);
        $sql= $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
    }


    // Using In Cash Verification
    function CashVerify($property_trxn_ids, $verified_by)
    {
        $builder = $this->db->table($this->table)
    				 ->wherein('id', $property_trxn_ids)
    				 ->update([
    				 			'verify_status' => 1,
                                'verified_by' => $verified_by,
                                'verify_date' => 'NOW()',
    				 		  ]);
        //echo $this->db->getLastQuery().'prop';
        return $builder;
    }

	public function payment_detail($prop_id)
    {

		try{
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', [$prop_id])
						->where('tran_type', 'Property')
                        ->whereIn('status', [1,2])
						->orderBy("tran_type","ASC")
                        ->get();
			//echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function payment_detail_saf($saf_id)
    {
		try{
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', [$saf_id])
						->where('tran_type', 'Saf')
                        ->whereIn('status', [1,2])
						->orderBy("tran_type","ASC")
                        ->get();
			//echo $this->db->getLastQuery();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function payment_detailsaf($saf_id)
    {
		try{
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', $saf_id)
                        ->whereIn('status', [1,2])
						->orderBy("tran_type","Saf")
                        ->get();
			//echo $this->db->getLastQuery();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function getTranDtlBySafDtlId($input)
    {
		try
        {
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', $input['saf_dtl_id'])
                        ->where('tran_type', 'Saf')
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

     public function getTranDtlByPropDtlId($input)
     {
        try
        {
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', $input['prop_dtl_id'])
                        ->where('tran_type', 'Property')
                        ->orderBy('id')
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

	public function jskProp_payment_detail($data)
    {
		try{
            $builder = $this->db->table("view_transaction")
                        ->select('*')
                        ->where('prop_dtl_id', $data)
                        ->get();

           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();
        }
       /* $sql = "SELECT *
		FROM view_transaction
		where prop_dtl_id=? ";
        $ql= $this->query($sql, [$data['id']]);
        $result =$ql->getResultArray();
        return $result;*/
    }
	public function getTrandtlList($data) {
		try{
            /*
            {
                $sql = "SELECT
                        tbl_transaction.id,
                        tbl_transaction.tran_date,
                        tbl_transaction.tran_no,
                        tbl_transaction.tran_mode,
                        tbl_transaction.from_fyear,
                        tbl_transaction.from_qtr,
                        tbl_transaction.upto_fyear,
                        tbl_transaction.upto_qtr,
                        tbl_prop_dtl.holding_no,
                        tbl_prop_dtl.new_holding_no,
                        tbl_prop_dtl.prop_address,
                        owner_dtl.owner_name,
                        owner_dtl.mobile_no
                    FROM tbl_transaction
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Property'
                    INNER JOIN (
                        SELECT
                            prop_dtl_id,
                            STRING_AGG(tbl_prop_owner_detail.owner_name, ',') AS owner_name,
                            STRING_AGG(tbl_prop_owner_detail.mobile_no::TEXT, ',') AS mobile_no
                        FROM tbl_prop_owner_detail
                        WHERE status=1
                        GROUP BY prop_dtl_id
                    ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                    LEFT JOIN view_ward_mstr AS view_ward_mstr_new ON view_ward_mstr_new.id=tbl_prop_dtl.new_ward_mstr_id
                    WHERE md5(tbl_transaction.id::text)='".$data."'";
                return $this->db->query($sql)->getFirstRow('array');
            }  */ 

            $builder = $this->db->table("tbl_transaction")
                        ->select('*')
                        ->where('md5(id::text)', $data)
                        ->get();
            return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();
        }

    }

	public function emp_list($data)
    {

		/*$sql = "SELECT tran_by_emp_details_id, SUM(payable_amt), tran_by_emp_details_name
			    FROM view_tc_transaction_details
			    where tran_date ='".$data['date_from']."' and verify_status is NULL
				GROUP BY tran_by_emp_details_id,tran_by_emp_details_name";

	*/




		/*$sql="
SELECT tran_by_emp_details_id, (payable_amt), tran_by_emp_details_name,ward_no
			    FROM view_tc_transaction_details
				join
				 (SELECT emp_details_id,ward_no

FROM dblink('host=localhost user=postgres password=aadrika#123 dbname=db_system'::text,
'SELECT emp_details_id,ward_no FROM tbl_ward_mstr join
			tbl_ward_permission on tbl_ward_permission.ward_mstr_id=tbl_ward_mstr.id $where
			'::text)
tbl_ward_mstr(emp_details_id integer,ward_no text)) as ward on ward.emp_details_id=
view_tc_transaction_details.tran_by_emp_details_id  where tran_date ='".$data['date_from']."' and verify_status is NULL and tran_mode_mstr_id=1
group by tran_by_emp_details_id,tran_by_emp_details_name,ward_no,payable_amt
";*/

	$sql="

select sum(payable_amt),emp_name as tran_by_emp_details_name,tran_by_emp_details_id,tran_date from tbl_transaction
join (select id,emp_name from
dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_system' ::text,
	  'select id,emp_name from tbl_emp_details'::text) emp(id integer,emp_name text)) as emp on emp.id=tbl_transaction.tran_by_emp_details_id
where
tran_date='".$data['date_from']."' and verify_status is NULL ".$data['where']." group by emp_name,tran_by_emp_details_id,tran_date
";

				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray();
				return $result;

    }



	public function pay_statustrans($data)
	{

		$sql1 = "SELECT prop_dtl_id as trans_prop_dtl_id
				FROM tbl_transaction
				where prop_dtl_id=?";
				$ql= $this->query($sql1, [$data["prop_dtl_id"]]);
				$demand_amnt =$ql->getFirstRow('array');
				//echo $this->db->getLastQuery();
				return $demand_amnt;
	}

	public function payDetails($data)
	{

		$sql1 = "SELECT prop_dtl_id as trans_prop_dtl_id
				FROM tbl_transaction
				where tran_type='Saf' and deactive_status='0' and status in (1, 2) and prop_dtl_id=?";
				$ql= $this->query($sql1, [$data]);
				$demand_amnt =$ql->getFirstRow('array');
				return $demand_amnt;
	}



	/*
	<!--------------------- Property payment --------------------------------!>
	*/
	public function insertPayment($input){
		$input["total_penalty"] = $input["tol_pently"] + $input["difference_Penalty"] ;
		$input["total_discount"] = $input["total_rebate"] + $input["total_pa_onjsk"] ;
		//print_r($input);
        $result = $this->db->table($this->table)->
            insert([
				  "tran_no"=>NULL,
				  "penalty_amt"=>isset($input["total_penalty"])?$input["total_penalty"]:0,
				  "from_fy_mstr_id"=>$input["from_fy_year"],
				  "from_qtr"=>$input["from_fy_qtr"],
				  "remarks"=>'Payment Done By Counter',
                  "prop_dtl_id"=>$input["custm_id"],
				  "tran_by_emp_details_id"=>$input['emp_details_id'],
				  "upto_fy_mstr_id"=>$input["due_upto_year"],
				  "upto_qtr"=>$input["date_upto_qtr"],
				  "created_on"=>$input["date"],
				  "discount_amt"=>isset($input["total_discount"])?$input["total_discount"]:0,
				  "payable_amt"=>$input["total_payabl"],
				  "tran_mode_mstr_id"=>$input["payment_mode"],
				  "tran_date"=>$input["date"],
				  "tran_type"=>"Property",
				  "tran_by_type"=>$input["tran_by_type"],
				  "from_fyear"=>$input["from_fyear"],
				  "upto_fyear"=>$input["upto_fyear"],
				  "tran_mode"=>$input["tran_mode"],
				  "round_off"=>isset($input["round_off"])?$input["round_off"]:0,
				  "status"=>($input["payment_mode"]==1)?"1":"2",
				  "ward_mstr_id"=>($input["ward_mstr_id"]!="")?$input["ward_mstr_id"]:null
				  ]);

				//echo $this->getLastQuery();
			$result = $this->db->insertID();

			if($input['emp_details']['user_type_mstr_id']== 4 || $input['emp_details']['user_type_mstr_id']== 5){
				$input["tran_no"]= "TRAN".date('d').$result.date('Y').date('m').date('s');
			}else{
				$input["tran_no"]= "CNT".date('d').$result.date('Y').date('m').date('s');
			}
			$this->db->table($this->table)
				     ->where('id', $result)
				     ->set(['tran_no' => $input["tran_no"]])
				     ->update();
				     //echo $this->db->getLastQuery();


				     //print_r($result);
			//die();


		return $result;
	}


	/*
	<!--------------------- saf payment --------------------------------!>
	*/

	public function safinsertPayment($input){

		$input["pnlty"] =$input['land_occupancy_delay_fine'] + $input['tol_pently'];
        $result = $this->db->table($this->table)->
            insert([
				  "tran_no"=>NULL,
				  "penalty_amt"=>isset($input["pnlty"])?$input["pnlty"]:0,
				  "from_fy_mstr_id"=>$input["from_fy_year"],
				  "from_qtr"=>$input["from_fy_qtr"],
				  "remarks"=>'Payment Done By Counter',
                  "prop_dtl_id"=>$input["custm_id"],
				  "tran_by_emp_details_id"=>$input['emp_details']['id'],
				  "upto_fy_mstr_id"=>$input["due_upto_year"],
				  "upto_qtr"=>$input["date_upto_qtr"],
				  "created_on"=>$input["date"],
				  "discount_amt"=>$input["rebate"],
				  "payable_amt"=>$input["total_payabl"],
				  "tran_mode_mstr_id"=>$input["payment_mode"],
				  "tran_date"=>$input["date"],
				  "tran_type"=>"Saf",
				  "tran_by_type"=>$input["tran_by_type"],
				  "from_fyear"=>$input["from_fyear"],
				  "upto_fyear"=>$input["upto_fyear"],
				  "tran_mode"=>$input["tran_mode"],
				  "round_off"=>$input["round_off"],
				  "status"=>($input["payment_mode"]==1)?"1":"2",
				  "ward_mstr_id"=>($input["ward_mstr_id"]!="")?$input["ward_mstr_id"]:null
				  ]);
							//echo $this->db->getLastQuery();

			$result = $this->db->insertID();
			if($input['emp_details']['user_type_mstr_id']== 4 || $input['emp_details']['user_type_mstr_id']== 5){
				$input["tran_no"]= "TRAN".date('d').$result.date('Y').date('m').date('s');
			}else{
				$input["tran_no"]= "CNT".date('d').$result.date('Y').date('m').date('s');
			}
			$this->db->table($this->table)
				     ->where('id', $result)
				     ->set(['tran_no' => $input["tran_no"]])
				     ->update();
			/*print_r($result);
			die();*/

			//echo $this->db->getLastQuery();
			return $result;

	}

	/*
	<!--------------------- Online payment --------------------------------!>
	*/


	public function onlineinsertPayment($input){
		$input["total_penalty"] = $input["tol_pently"];
		$input["total_discount"] = $input["rebate"] + $input["total_pa_onlin"] ;
		//print_r($input);
        $result = $this->db->table($this->table)->
            insert([
				  "tran_no"=>NULL,
				  "penalty_amt"=>isset($input["total_penalty"])?$input["total_penalty"]:0,
				  "from_fy_mstr_id"=>$input["from_fy_year"],
				  "from_qtr"=>$input["from_fy_qtr"],
				  "remarks"=>'Payment Done By Citizen',
                  "prop_dtl_id"=>$input["custm_id"],
				  "tran_by_emp_details_id"=>00,
				  "upto_fy_mstr_id"=>$input["due_upto_year"],
				  "upto_qtr"=>$input["date_upto_qtr"],
				  "created_on"=>$input["current_date"],
				  "discount_amt"=>isset($input["total_discount"])?$input["total_discount"]:0,
				  "payable_amt"=>$input["total_payabl"],
				  "tran_mode_mstr_id"=>4,
				  "tran_date"=>$input["current_date"],
				  "tran_type"=>"Property",
				  "tran_by_type"=>"ONLINE",
				  "round_off"=>isset($input["round_off"])?$input["round_off"]:0,
				  "status"=>1,
				  "ward_mstr_id"=>($input["ward_mstr_id"]!="")?$input["ward_mstr_id"]:null
				  ]);

				//echo $this->getLastQuery();
			$result = $this->db->insertID();

			$input["tran_no"]= "OLP".date('d').$result.date('Y').date('m').date('s');

			$this->db->table($this->table)
				     ->where('id', $result)
				     ->set(['tran_no' => $input["tran_no"]])
				     ->update();
				     //echo $this->db->getLastQuery();

			return $result;
	}


	public function total_collection_amount()
    {
		try{
            $builder = $this->db->table("tbl_transaction")
                        ->select('*')
						->whereIn('status',[1,2])
						->orderBy("id","ASC")
                        ->get();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();
        }

    }
    public function calculateSum($from_date,$to_date)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getEmpDetailsId($data)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('tran_by_emp_details_id')
                        ->where('tran_date >=',$data['from_date'])
                        ->where('tran_date <=',$data['to_date'])
                        ->whereIn('status',[1,2])
                        ->groupby('tran_by_emp_details_id')
                        ->get();
                       // echo $this->db->getLastQuery();
             return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function calculateSumByTransactionEmpDetailsId($emp_id,$from_date,$to_date)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_by_emp_details_id',$emp_id)
                        ->whereIn('status',[1,2])
                        ->get();
                       // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function calculateSumByTransactionMode($from_date,$to_date,$tran_mode_mstr_id)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_mode_mstr_id',$tran_mode_mstr_id)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function calculateSumForSafAndProperty($from_date,$to_date,$tran_type)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type',$tran_type)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function calculateSumForProperty($from_date,$to_date,$tran_type)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type',$tran_type)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTransactionDetails($transaction_id){
    	try{
    		$builder = $this->db->table($this->table)
    				  ->select('*')
    				  ->where('id',$transaction_id )
    				 // ->where('status',2)
    				  ->get();
    		return $builder->getFirstRow('array');
    	}catch(Exception $e){
    		echo $e->getMessage();
    	}
    }
   public function updateStatusClear($transaction_id){
    	try{
    		return $builder = $this->db->table($this->table)
    				 ->where('id',$transaction_id)
    				 ->update([
    				 			'status' =>1
    				 		  ]);
    	}catch(Exception $e){
			echo $e->getMessage();
    	}
    }
    public function updateStatusNotClear($transaction_id){
    	try{
    		return $builder = $this->db->table($this->table)
    				 ->where('id',$transaction_id)
    				 ->update(["bounce_status"=> 1, 'status'=>3]);
    	}catch(Exception $e){
			echo $e->getMessage();
    	}
    }
    public function calculateSumForTCSafAndProperty($from_date,$to_date,$tran_type,$tran_by_emp_details_id)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type',$tran_type)
                        ->where('tran_by_emp_details_id',$tran_by_emp_details_id)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function calculatewardwiseSumForTCSafAndProperty($from_date,$to_date,$ward_id,$tran_by_emp_details_id)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as total')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('ward_id',$ward_id)
                        ->whereIn('status',[1,2])
                        ->where('tran_by_emp_details_id',$tran_by_emp_details_id)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function get_trans_mode_id_by_prop_id($prop_dtl_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('tran_mode_mstr_id')
                        ->where('tran_mode_mstr_id',2)
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->whereIn('status',[1,2])
                        //->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }



	public function total_today_collection_amount($data){
		$sql = "SELECT coalesce(sum(payable_amt), 0) as today_collection_amount
			    FROM tbl_transaction
			    WHERE status In(1,2) and tran_date=?";
				$ql= $this->db->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');
				return $result;

    }

    public function checkSafDtlIsPaymentOrNot($input){
		try{
            return $this->db->table($this->table)
                       ->select('id')
                       ->where('prop_dtl_id', $input['prop_dtl_id'])
                       ->where('tran_type', 'Saf')
                       ->where('status !=', 0)
                       ->get()
                       ->getFirstRow('array');
       }catch(Exception $e){
           return $e->getMessage();
       }

    }
    public function getTransactionDetailsForChequeBounce($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('id',$transaction_id )
                      ->where('status',3)
                      ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getModeWiseCollectionByTcByOneMode($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date>=',$data['from_date'])
                      ->where('tran_date<=',$data['to_date'])
                      ->whereIn('ward_mstr_id',$data['wardPermission'])
                      ->where('tran_mode_mstr_id',$data['tran_mode_mstr_id'])
                      ->where('tran_by_emp_details_id',$data['id'])
                      ->whereIn('status',[1,2])
                      ->orderBy('ward_mstr_id,tran_date','ASC')
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function getModeWiseCollectionByOneMode($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date>=',$data['from_date'])
                      ->where('tran_date<=',$data['to_date'])
                      ->where('tran_mode_mstr_id',$data['tran_mode_mstr_id'])
                      ->whereIn('status',[1,2])
                      ->orderBy('ward_mstr_id,tran_date','ASC')
                      ->get();
                     // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getModeWiseCollectionByTcForAll($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date>=',$data['from_date'])
                      ->where('tran_date<=',$data['to_date'])
                      ->whereIn('ward_mstr_id',$data['wardPermission'])
                      ->where('tran_by_emp_details_id',$data['id'])
                      ->whereIn('status',[1,2])
                      ->orderBy('ward_mstr_id,tran_date','ASC')
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getModeWiseCollectionForAll($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date>=',$data['from_date'])
                      ->where('tran_date<=',$data['to_date'])
                      ->whereIn('status',[1,2])
                      ->orderBy('tran_date','ASC')
                      ->get();
                    //  echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalAmount($employee_id,$tran_date){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('md5(tran_by_emp_details_id::text)',$employee_id)
                      ->where('tran_date',$tran_date)
                      ->where('verify_status',NULL)
                      ->whereIn('status',[1,2])
                      ->get();
                    //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalAmountCash($employee_id,$tran_date){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('md5(tran_by_emp_details_id::text)',$employee_id)
                      ->where('tran_date',$tran_date)
                      ->where('verify_status',NULL)
                      ->where('tran_mode_mstr_id',1)
                      ->whereIn('status',[1,2])
                      ->get();
                    // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getChequeDetailsByEmpId($id,$tran_date){
        try{
            $builder =$this->db->table($this->table)
                    ->select('*')
                    ->where('md5(tran_by_emp_details_id::text)',$id)
                    ->where('status',2)
                    ->where('verify_status',NULL)
                    ->where('tran_date',$tran_date)
                    ->whereIn('tran_mode_mstr_id',[1,2])
                    ->get();
                  // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateVerificationStatus($id, $transaction_id, $verified_date)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                     ->where('id',$transaction_id)
                     ->update([
                                'verify_status' =>1,
                                'verify_date' =>$verified_date,
                                'verified_by' =>$id
                              ]);
                    // echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getAllNotVarifiedDataByEmpId($id,$tran_date)
    {
        try
        {
            $builder =$this->db->table($this->table)
                    ->select('*')
                    ->where('md5(tran_by_emp_details_id::text)',$id)
                    ->whereIn('status',[1,2])
                    ->where('verify_status',NULL)
                    ->where('notification_id',NULL)
                    ->where('tran_date',$tran_date)
                    ->get();
                  //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getDdDetailsByEmpId($id,$tran_date){
        try{
            $builder =$this->db->table($this->table)
                    ->select('*')
                    ->where('md5(tran_by_emp_details_id::text)',$id)
                    ->where('status',2)
                    ->where('verify_status',NULL)
                    ->where('tran_date',$tran_date)
                    ->where('tran_mode_mstr_id',3)
                    ->get();
                  //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateVerificationStatuCashCollection($employee_id,$verified_date,$id,$tran_date){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('md5(tran_by_emp_details_id::text)',$employee_id)
                     ->where('tran_mode_mstr_id',1)
                     ->where('tran_date',$tran_date)
                     ->update([
                                'verify_status' =>1,
                                'verify_date' =>$verified_date,
                                'verified_by' =>$id
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function UpdateVerifiedStatus($inserted_id,$transaction_id){
        try{
             $builder = $this->db->table($this->table)
                        ->where('id',$transaction_id)
                        ->update([
                                    'notification_id' =>$inserted_id
                                ]);

                        echo $this->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalAmountByVerifyStatus($verify_status){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('verify_status',$verify_status)
                      ->whereIn('status',[1,2])
                      ->get();
                    //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getEmployeeId($verify_status){
        try{
            $builder = $this->db->table($this->table)
                    ->select('tran_by_emp_details_id')
                    ->where('md5(verify_status::text)',$verify_status)
                    ->whereIn('status',[1,2])
                    ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getNotVerifiedAmountProperty($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('md5(verify_status::text)',$id)
                      ->whereIn('status',[1,2])
                      ->get();
                    //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllNotVerifiedChequeDetails($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->whereIn('md5(verify_status::text)',$id)
                      ->whereIn('status',[1,2])
                      ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getEmpDetailsIdForOneEmployee($data)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('tran_by_emp_details_id')
                        ->where('tran_date >=',$data['from_date'])
                        ->where('tran_date <=',$data['to_date'])
                        ->where('tran_by_emp_details_id',$data['tran_by_emp_details_id'])
                        ->whereIn('status',[1,2])
                        ->groupby('tran_by_emp_details_id')
                        ->get();
                       // echo $this->db->getLastQuery();
             return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalBydate($from_date,$transaction_mode_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('tran_date',$from_date)
                      ->where('tran_mode_mstr_id',$transaction_mode_id)
                      ->whereIn('status',[1,2])
                      ->get();
                    //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllTotalBydate($from_date){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(payable_amt),0) as totalamount')
                      ->where('tran_date',$from_date)
                      ->whereIn('status',[1,2])
                      ->get();
                    //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['totalamount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

	public function getTranDtlByTranNo($tran_no){
        try{
            $sql = "SELECT
                            tbl_transaction.*,
                            tbl_cheque_details.id AS cheque_id,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            tbl_cheque_details.bounce_status,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.clear_bounce_date,
                            tbl_cheque_details.remarks,
                            tbl_cheque_details.bounce_amount,
                            tbl_cheque_details.verification_date,
                            tbl_cheque_details.status AS cheque_status,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN prop_dtl.holding_no ELSE saf_dtl.saf_no END AS app_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN prop_dtl.ward_no ELSE saf_dtl.ward_no END AS ward_no
                        FROM tbl_transaction
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                tbl_prop_dtl.id AS prop_dtl_id,
                                view_ward_mstr.ward_no,
                                CASE WHEN tbl_prop_dtl.new_holding_no='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END AS holding_no
                            FROM tbl_prop_dtl
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        ) AS prop_dtl ON prop_dtl.prop_dtl_id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Property'
                        LEFT JOIN (
                            SELECT
                                tbl_saf_dtl.id AS saf_dtl_id,
                                view_ward_mstr.ward_no,
                                tbl_saf_dtl.saf_no
                            FROM tbl_saf_dtl
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                        ) AS saf_dtl ON saf_dtl.saf_dtl_id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Saf'
                        WHERE
                            UPPER(tran_no)='".$tran_no."'
                            AND tbl_transaction.status IN (1,2);";
            return $this->db->query($sql)->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getTransactionByTranNo($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('upper(tran_no)',$data['tran_no'])
                      ->whereIn('status',[1,2])
                      ->get();
                      /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getCheckDtlByno($data)
    {
        try{
            $builder = $this->db->table('tbl_cheque_details')
                      ->select('*')
                      ->where('upper(cheque_no)',$data['cheque_no'])
                      ->whereIn('status',[1,2])
                      ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
          echo $e->getMessage();
        }
    }

    public function getCheckDtlBytrid($data)
    {
        try{
            $builder = $this->db->table('tbl_cheque_details')
                      ->select('*')
                      ->where('md5(transaction_id::text)',$data['transaction_id'])
                      ->whereIn('status',[1,2])
                      ->orderBy('id','desc')
                      ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
          echo $e->getMessage();
        }
    }


    public function getCheckDtlByTrxn_id($transaction_id)
    {
        try{
            $builder = $this->db->table('tbl_cheque_details')
                      ->select('*')
                      ->where('transaction_id', $transaction_id)
                      ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
          echo $e->getMessage();
        }
    }

    public function getTransactionById($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('md5(id::text)', $id)
                      ->whereIn('status', [1,2])
                      ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getLatestTransaction($id){
        try{
            $sql = "select max(tbl_transaction.id) as id from tbl_transaction
            inner join tbl_transaction as tr on tbl_transaction.prop_dtl_id=tr.prop_dtl_id
            where tr.id='".$id."' and tbl_transaction.status=1";
            $qry_run = $this->db->query($sql);
            // print_r($qry_run->getFirstRow('array'));
            // die;
            return $qry_run->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }


    public function getTransactionByTrxn_id($transaction_id)
    {
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('id', $transaction_id)
                      ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updatePropertyTransactionStatus($transaction_id){
        try{
             return $builder = $this->db->table($this->table)
                       ->where('id',$transaction_id)
                       ->update([
                                "status"=>0

                                ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updatePropertyTransactionDeactivet($transaction_id)
    {
        try{
             return $builder = $this->db->table($this->table)
                       ->where('id',$transaction_id)
                       ->update([
                                "status"=>0,
                                "deactive_status"=>1

                                ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
	public function roqQuery($sql)
    {
        try
        {

            $data = $this->db->query($sql)->getResultArray();
            return $data;

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }


	public function checkPayment($data)
	{
		$sql = "SELECT prop_dtl_id as saf_dtl_id
				FROM tbl_transaction
				where prop_dtl_id=? AND tran_type='Saf' AND status In(1,2)";
				$ql= $this->query($sql, [$data["custm_id"]]);
				$result =$ql->getFirstRow('array');
				return $result;
	}

	public function checkpropPayment($data)
	{
		$sql = "SELECT prop_dtl_id
				FROM tbl_transaction
				where prop_dtl_id=? AND tran_date=? AND tran_type='Property' AND status In(1,2)";
				$ql= $this->query($sql, [$data["custm_id"],$data['current_date']]);
				$result =$ql->getFirstRow();
				return $result;
	}

     public function getDeactivatedTransactionDetails($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('id',$transaction_id)
                      ->where('status',0)
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
     }


	public function checkPaymentfordoc($data)
	{

		$sql = "SELECT tran_mode_mstr_id,status
				FROM tbl_transaction
				where prop_dtl_id=? ";
				$ql= $this->query($sql, [$data["saf_dtl_id"]]);
				$result =$ql->getFirstRow('array');
				return $result;
	}
	public function getBulkPrintData($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date >=',$data['from_date'])
                      ->where('tran_date <=',$data['to_date'])
                      ->whereIn('status',[1,2])
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	public function getTotalCashCollectionBetweenDateCash($data){
        try{
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(payable_amt),0) as cash,COALESCE(COUNT(DISTINCT prop_dtl_id),0) as holding,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('tran_date >=',$data['from_date']);
            $builder = $builder->where('tran_date <=',$data['to_date']);
            $builder = $builder->where('tran_type','Property');
            $builder = $builder->where('tran_mode_mstr_id',1);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status',[1,2]);
            $builder = $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDateCheque($data){
        try{
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(payable_amt),0) as cheque,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('tran_date >=',$data['from_date']);
            $builder = $builder->where('tran_date <=',$data['to_date']);
            $builder = $builder->where('tran_type','Property');
            $builder = $builder->where('tran_mode_mstr_id',2);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status',[1,2]);
            $builder = $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDateDD($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as dd,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder= $builder ->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',3);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDateCard($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as card,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',4);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDateOnline($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as online,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder ->where('tran_mode_mstr_id',5);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDateFund($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as fund,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',6);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollectionBetweenDatei_sure($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as i_sure,COUNT(DISTINCT prop_dtl_id) as holding,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',7);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelCheque($data){
         try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as cheque,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',2);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelDd($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as dd,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',3);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelCard($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as card,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',4);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelOnline($data){
         try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as online,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',5);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelFund($data){
         try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as fund,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',6);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalCancelI_Sure($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as i_sure,COUNT(DISTINCT prop_dtl_id) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',7);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->where('status',3);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getNetPayment($data){
          try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as netPayment,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',8);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getRTGSPayment($data){
        try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(SUM(payable_amt),0) as rtgsPayment,COALESCE(COUNT(id),0) as id');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            $builder= $builder->where('tran_mode_mstr_id',9);
            if($data['ward_mstr_id']!=""){
                $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTotalHolding($data){
         try{
            $builder = $this->db->table($this->table);
            $builder= $builder->select('COALESCE(COUNT(DISTINCT prop_dtl_id),0) as holding');
            $builder= $builder->where('tran_date >=',$data['from_date']);
            $builder= $builder->where('tran_date <=',$data['to_date']);
            $builder= $builder->where('tran_type','Property');
            if($data['ward_mstr_id']!=""){
               $builder = $builder->where('ward_mstr_id',$data['ward_mstr_id']);
            }
            $builder= $builder->whereIn('status',[1,2]);
            $builder= $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getTransaction($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id')
                      ->where('ward_mstr_id',$data['ward_mstr_id'])
                      ->where('tran_date >=',$data['from_date'])
                      ->where('tran_date <=',$data['to_date'])
                      ->where('tran_type','Property')
                      ->whereIn('status',[1,2])
                      ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getPropId($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('DISTINCT prop_dtl_id')
                      ->where('ward_mstr_id',$data['ward_mstr_id'])
                      ->where('tran_date >=',$data['from_date'])
                      ->where('tran_date <=',$data['to_date'])
                      ->where('tran_type','Property')
                      ->whereIn('status',[1,2])
                      ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }



    public function getTotalPaidAmountwithCountTrans($from_date,$to_date)
    {
         $sql="select tran_type,count(id) as count,coalesce(sum(payable_amt),0) as paid_amount from tbl_transaction where (tran_date between '$from_date' and '$to_date') and status in(1,2) group by tran_type";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function getGBSAFTotalPaidAmountwithCountTrans($from_date,$to_date)
    {
         $sql="select count(id) as count, coalesce(sum(payable_amt),0) as paid_amount from tbl_govt_saf_transaction where (tran_date between '$from_date' and '$to_date') and status in(1,2)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;

    }


	public function current_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(payable_amt) as fy_coll
			    FROM tbl_transaction
				where deactive_status=0 AND status=1 AND  tran_date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');
				return $result;

    }


	public function dy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "SELECT sum(payable_amt) as dy_coll
			    FROM tbl_transaction
				where deactive_status=0 AND status=1 AND tran_date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');
				return $result;

    }

	public function insertdy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "INSERT INTO public.dashboard_daily_collection(FY,date, amount)
				SELECT
				'2021-2022','".$fromdate."',sum(payable_amt)
			    FROM tbl_transaction
				where deactive_status=0 AND status=1 AND tran_date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray();
				return $result;

    }

    public function getPropTransactionWithChequeDetails($where)
    {
        $sql="select tbl_transaction.*,transaction_mode as payment_mode,
                tbl_cheque_details.id as cheque_dtl_id,
                tbl_cheque_details.clear_bounce_date as clear_bounce_date,
                tbl_cheque_details.remarks as clear_bounce_remarks,tbl_cheque_details.cheque_no,
                tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name
            from tbl_transaction
            join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
            join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }



	public function propgetTotalPaidAmountwithCountTransbyempid($from_date,$to_date,$tax_collector_id)
    {

        $sql="select count(id) as propcount,coalesce((sum(payable_amt)+sum(round_off)),0) as proppaid_amount,tran_by_emp_details_id
		from tbl_transaction
		where tran_date between '$from_date' and '$to_date'  and status in(1,2)
		and tran_by_emp_details_id='$tax_collector_id' and tran_type='Property'
		group by tran_by_emp_details_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
       // /  echo $this->getLastQuery();
        return $result;

    }

	public function safgetTotalPaidAmountwithCountTransbyempid($from_date,$to_date,$tax_collector_id)
    {
        $sql="select count(id) as safcount,coalesce((sum(payable_amt)+sum(round_off)),0) as safpaid_amount,tran_by_emp_details_id
		from tbl_transaction
		where tran_date between '$from_date' and '$to_date'  and status in(1,2)
		and tran_by_emp_details_id='$tax_collector_id' and tran_type='Saf'
		group by tran_by_emp_details_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
       // /  echo $this->getLastQuery();
        return $result;
    }

	public function adjust_tran_dtl($data)
    {

		try{
            $builder = $this->db->table("tbl_transaction")
					->select('id,prop_dtl_id,payable_amt')
					->where('md5(id::text)', $data)
					->get();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }

    }


	public function payment_adjust_detail($prop_id)
    {
		try{
			$sql="select *
			from view_transaction
			where prop_dtl_id='".$prop_id."' and tran_type='Property' and status in(1,2)
			order by tran_type ASC";
            $run=$this->db->query($sql);
			$result=$run->getResultArray();
		    //echo $this->getLastQuery();//inner join tbl_rest_advance_amount on tbl_rest_advance_amount.transaction_id != view_transaction.id
			return $result;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function row_sql($sql)
    {
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->db->getLastQuery();
        return $result;
    }
    public function getTransDataByLimit($inputs){
        try{
                $sql = "SELECT
                            tbl_transaction.id,
                            tbl_transaction.tran_type,
                            tbl_transaction.tran_mode,
                            tbl_transaction.payable_amt,
                            tbl_transaction.round_off,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.from_fyear,
                            tbl_transaction.from_qtr,
                            tbl_transaction.upto_fyear,
                            tbl_transaction.upto_qtr,
                            tbl_transaction.status AS tran_staus,
                            cheque_details.cheque_no,
                            cheque_details.cheque_date,
                            cheque_details.bank_name,
                            cheque_details.branch_name,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.prop_id ELSE tbl_saf.prop_id END AS prop_id,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.application_no ELSE tbl_saf.application_no END AS application_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.new_application_no ELSE tbl_saf.new_application_no END AS new_application_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.ward_no ELSE tbl_saf.ward_no END AS ward_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.new_ward_no ELSE tbl_saf.new_ward_no END AS new_ward_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.owner_name ELSE tbl_saf.owner_name END AS owner_name,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.mobile_no ELSE tbl_saf.mobile_no END AS mobile_no,
                            CASE WHEN tbl_transaction.tran_type='Property' THEN tbl_prop.prop_address ELSE tbl_saf.prop_address END AS prop_address
                        FROM tbl_transaction
                        LEFT JOIN (SELECT
                                            transaction_id,
                                            cheque_no,
                                        cheque_date,
                                        bank_name,
                                        branch_name
                                    FROM tbl_cheque_details

                        ) AS cheque_details ON cheque_details.transaction_id=tbl_transaction.id
                        LEFT JOIN (SELECT
                                            tbl_prop_dtl.id AS prop_id,
                                            tbl_prop_dtl.holding_no AS application_no,
                                            tbl_prop_dtl.new_holding_no AS new_application_no,
                                            view_ward_mstr.ward_no,
                                            new_ward.ward_no AS new_ward_no,
                                            prop_owner_dtl.owner_name,
                                            prop_owner_dtl.mobile_no,
                                            tbl_prop_dtl.prop_address
                                    FROM tbl_prop_dtl
                                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                                    INNER JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
                                        INNER JOIN (
                                        SELECT
                                        prop_dtl_id,
                                            STRING_AGG(CONCAT(owner_name, ' ', guardian_name, ' ', relation_type), ',')	AS owner_name,
                                            STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                                        FROM tbl_prop_owner_detail WHERE status=1 GROUP BY prop_dtl_id
                                    ) AS prop_owner_dtl ON prop_owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                        ) AS tbl_prop ON tbl_prop.prop_id=tbl_transaction.prop_dtl_id AND tran_type='Property'
                        LEFT JOIN (SELECT
                                            tbl_saf_dtl.id AS prop_id,
                                            tbl_saf_dtl.saf_no AS application_no,
                                            '' AS new_application_no,
                                            view_ward_mstr.ward_no,
                                            new_ward.ward_no AS new_ward_no,
                                            saf_owner_dtl.owner_name,
                                            saf_owner_dtl.mobile_no,
                                            tbl_saf_dtl.prop_address
                                    FROM tbl_saf_dtl
                                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                                    INNER JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_saf_dtl.new_ward_mstr_id
                                        INNER JOIN (
                                        SELECT
                                        saf_dtl_id,
                                            STRING_AGG(CONCAT(owner_name, ' ', guardian_name, ' ', relation_type), ',')	AS owner_name,
                                            STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                                        FROM tbl_saf_owner_detail WHERE status=1 GROUP BY saf_dtl_id
                                    ) AS saf_owner_dtl ON saf_owner_dtl.saf_dtl_id=tbl_saf_dtl.id
                        ) AS tbl_saf ON tbl_saf.prop_id=tbl_transaction.prop_dtl_id AND tran_type='Saf'
                        WHERE
                            tbl_transaction.tran_date='".$inputs['date']."'
                            AND tbl_transaction.tran_mode_mstr_id=".$inputs['tran_mode_mstr_id']."
                            AND tbl_transaction.tran_by_emp_details_id=".$inputs['tc_list_id']."";
            //    $sql = "select id as tran_id,* from tbl_transaction where status=1 and deactive_status=0 and tran_date='".$inputs['date']."' and ward_mstr_id=".$inputs['ward_mstr_id']." and tran_mode_mstr_id=".$inputs['tran_mode_mstr_id']." order by id desc";
            //  die;
            $qry_run = $this->db->query($sql);
        //  print_r($qry_run->getResultArray());
            // die;
            return $qry_run->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function updateChequeData($id,$data){
        return $this->db->table("tbl_cheque_details")
                ->where("id",$id)
                ->update(
                    [
                        "cheque_no"=>$data["cheque_no"],
                        "cheque_date"=>$data["cheque_date"],
                        "bank_name"=>$data["bank_name"],
                        "branch_name"=>$data["branch_name"]
                    ]
                );
    }
    
    public function updateGbSafChequeData($id,$data){
        return $this->db->table("tbl_govt_saf_transaction_details")
                ->where("id",$id)
                ->update(
                    [
                        "cheque_no"=>$data["cheque_no"],
                        "cheque_date"=>$data["cheque_date"],
                        "bank_name"=>$data["bank_name"],
                        "branch_name"=>$data["branch_name"]
                    ]
                );
    }
    
    public function update_cheque($cheque_tbl_id,$cheuqe_no_to_update)
    {
         $sql="update tbl_cheque_details set cheque_no='".$cheuqe_no_to_update."' where id=".$cheque_tbl_id."";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }
    public function update_gb_cheque($cheque_tbl_id,$cheuqe_no_to_update)
    {
         $sql="update tbl_govt_saf_transaction_details set cheque_no='".$cheuqe_no_to_update."' where id=".$cheque_tbl_id."";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

	public function getBasicDtlForReceipt($transaction_id) {
            if (is_numeric($transaction_id)) {
                $whereTrnId = " AND tbl_transaction.id=".$transaction_id;
            } else {
                $whereTrnId = " AND md5(tbl_transaction.id::TEXT)='".$transaction_id."'";
            }
            
            $sql = "SELECT
                    transaction_fine_rebet_details.fine_rebet_dtl,
                    tbl_transaction.id,
                    tbl_transaction.tran_type,
                    tbl_transaction.tran_mode,
                    tbl_transaction.payable_amt,
                    tbl_transaction.round_off,
                    tbl_transaction.tran_no,
                    tbl_transaction.tran_date,
                    tbl_transaction.from_fyear,
                    tbl_transaction.from_qtr,
                    tbl_transaction.upto_fyear,
                    tbl_transaction.upto_qtr,
                    tbl_transaction.status AS tran_staus,
                    collection_dtl.holding_tax,
                    collection_dtl.water_tax,
                    collection_dtl.education_cess,
                    collection_dtl.health_cess,
                    collection_dtl.latrine_tax,
                    collection_dtl.additional_tax,
                    tbl_cheque_details.cheque_no,
                    tbl_cheque_details.cheque_date,
                    tbl_cheque_details.bank_name,
                    tbl_cheque_details.branch_name,
                    tbl_saf_dtl.id AS saf_id,
                    tbl_saf_dtl.saf_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_saf_dtl.prop_address,
                    view_ward_mstr.ward_no,
                    new_ward_mstr.ward_no AS new_ward_no,
                    saf_owner_dtl.owner_name,
                    saf_owner_dtl.mobile_no
                FROM tbl_transaction
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Saf'
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                LEFT JOIN view_ward_mstr AS new_ward_mstr ON new_ward_mstr.id=tbl_saf_dtl.new_ward_mstr_id
                INNER JOIN (
                    SELECT
                        saf_dtl_id,
                        STRING_AGG(CONCAT(owner_name, ' ', relation_type, ' ', guardian_name), ',' ORDER BY id ASC) AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                    FROM tbl_saf_owner_detail WHERE status=1
                    GROUP BY saf_dtl_id
                ) AS saf_owner_dtl ON saf_owner_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN (
                    SELECT
                        transaction_id,
                        SUM(holding_tax) AS holding_tax,
                        SUM(water_tax) AS water_tax,
                        SUM(education_cess) AS education_cess,
                        SUM(health_cess) AS health_cess,
                        SUM(latrine_tax) AS latrine_tax,
                        SUM(additional_tax) AS additional_tax
                    FROM tbl_saf_collection
                    Group by transaction_id
                ) AS collection_dtl ON collection_dtl.transaction_id=tbl_transaction.id AND tbl_transaction.tran_type='Saf'
                LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                LEFT JOIN (
                    SELECT
                        transaction_id,
                        json_agg(json_build_object('head_name', head_name, 'amount', amount)) AS fine_rebet_dtl
                    FROM tbl_transaction_fine_rebet_details
                    GROUP BY transaction_id
                ) AS transaction_fine_rebet_details ON transaction_fine_rebet_details.transaction_id=tbl_transaction.id
                WHERE
                    tbl_transaction.status IN (1,2) ".$whereTrnId;
        return $this->db->query($sql)->getFirstRow("array");
    }

}
?>

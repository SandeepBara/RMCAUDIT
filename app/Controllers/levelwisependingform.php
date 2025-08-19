<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_level_pending_dtl;
use App\Models\model_datatable;
use App\Models\model_saf_dtl;
use App\Models\model_ward_mstr;
use DateTime;

class levelwisependingform extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_level_pending_dtl;
	protected $model_datatable;
	protected $model_saf_dtl;
	protected $model_ward_mstr;
	
    public function __construct(){
		/* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); */

        parent::__construct();
    	helper(['db_helper','utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
		if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }

	function __destruct() {
		if ($this->db) $this->db->close();
	}


    public function reportlevelpending(){

        $data =(array)null;
        
		$data['levelpending'] = $this->model_level_pending_dtl->getlevelwiseform();
		$data['levelpending_utc'] = $this->model_level_pending_dtl->getlevelwiseformUTC();
		$data['levelpending_back_office'] = $this->model_level_pending_dtl->getlevelwiseformbackOffice();
		
		return view('report/levelwisependingform',$data);
    }
	public function reportleveltimetaken2_old(){

		$data = (array)null;
		$records = (array)null;
		$Session = Session();
		$ulb_dtl = $Session->get('ulb_dtl');
		$ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
		$data['totalsaf']=0;

		$request = arrFilterSanitizeString($this->request->getVar());
		$from_date="2024-02-07";
		$to_date=date('Y-m-d',strtotime('-1 day'));
		$whereDateRange="";
		if(isset($request['from_date']))
		{
			$from_date=$request['from_date'];
		}
		if(isset($request['to_date']))
		{
//			$to_date=date('Y-m-d',strtotime($request['to_date'].'-1 day')) ;
			$to_date=date('Y-m-d',strtotime($request['to_date'])) ;
		}
		if ($from_date != "" && $to_date != "") {
			$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
		}
		if (isset($this->request->getVar()['report_type']) && empty($this->request->getVar()['filter_type'])) {
			return view('report/pendinglistsaf',$data);
		}

//		$totalsaf="select * from tbl_saf_dtl where tbl_saf_dtl.status = '1' AND tbl_saf_dtl.payment_status= '1' $whereDateRange";
//		$totalsaf=$this->db->query($totalsaf)->getResultArray();
//		dd(isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date']));

		if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
			ini_set('memory_limit', '-1');
			try {
				$orderBY = " order by tbl_saf_dtl.id asc";//" ORDER BY " . $columnName . " " . $columnSortOrder;
				$from_date = $this->request->getVar("from_date");
				$to_date = $this->request->getVar("to_date");
				$limit ="";
//				$limit =" OFFSET 50 LIMIT 100";
				if ($from_date != "" && $to_date != "") {
					$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
				}
				$whereQuery=$whereDateRange;
				$selectStatement="SELECT 
						ROW_NUMBER () OVER (ORDER BY " . "tbl_saf_dtl.id" . " DESC) AS s_no,
						tbl_saf_dtl.id,holding_no,saf_no,tbl_saf_dtl.ward_mstr_id,apply_date,assessment_type,holding_type,
						(select txn.tran_date from tbl_transaction txn where txn.prop_dtl_id=tbl_saf_dtl.id AND txn.status= '1' 
AND txn.tran_type='Saf' limit 1)as payment_date,
						'' as approve_date,
						0 as backoffice,0 as backofficedonel,0 as backofficedoneg,
						0 as dealingassistant,0 as dealingassistantdonel,0 as dealingassistantdoneg,
						0 as taxcollector,0 as taxcollectordonel,0 as taxcollectordoneg,
						0 as ulbtaxcollector,0 as ulbtaxcollectordonel,0 as ulbtaxcollectordoneg,
						0 as propertysectionincharge,0 as propertysectioninchargedonel,0 as propertysectioninchargedoneg,
						0 as executiveofficer,0 as executiveofficerdonel,0 as executiveofficerdoneg,
						0 as total,
						0 as totalcount
						";
				$sql = " FROM tbl_saf_dtl
					WHERE 
						 tbl_saf_dtl.status= '1' AND
						 tbl_saf_dtl.payment_status= '1' AND 
						 tbl_saf_dtl.saf_pending_status!='2'
					" . $whereQuery;
				$fetchSql = $selectStatement . $sql . $orderBY.$limit;
				$records = $this->db->query($fetchSql);
				$records = $records->getResultArray();
				$data['totalsaf']=count($records);
//				dd($records);
				foreach ($records as $k=>$result_)
				{
					$saftime=$this->model_level_pending_dtl->getAllLevelDtl2($result_['id']);
					$records[$k]['leveldetails']=$saftime;
					if(count($saftime)>0){
						if (isset($this->request->getVar()['report_type'])) {
							$usertype=$this->request->getVar()['filter_type'];
							$calssaftime=$this->calssaftime3($saftime,$result_['payment_date']);
						}else{
							$calssaftime=$this->calssaftime3($saftime,$result_['payment_date']);
						}
						$records[$k]= array_merge($records[$k],$calssaftime);
					}else{
						$payment_date = new DateTime($result_['payment_date']);
						$payment_date->modify('+1 day');
						$tillDate = new DateTime();
						$diffdaystillDate = $payment_date->diff($tillDate)->format("%r%a");
						if($diffdaystillDate=="-0"){$diffdaystillDate=0;}
						$records[$k]['backoffice']=$diffdaystillDate;

						if(2>=$diffdaystillDate){
							
							$records[$k]['backofficeprogressl']=1;
							$records[$k]["backofficeprogressl_list"][$result_['id']]['saf_dtl_id']=$result_['id'];

						}else{
							$saf_dtl_id=$result_['id'];
							$input['saf_dtl_id']=$saf_dtl_id;

							$safdtl=$this->model_saf_dtl->getSafDtlById2($input);
							$records[$k]['backofficependingg']=1;
//							$records[$k]["backofficependingg_list"][]=$result_['id'];
							$records[$k]["backofficependingg_list"][$result_['id']]['saf_dtl_id']=$saf_dtl_id;
							$records[$k]["backofficependingg_list"][$result_['id']]['saf_no']=$safdtl['saf_no'];
							//if(empty($result_['emp_name']))
							//{
								$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],'11',$safdtl['ward_no']);
								$emp_name=$employeedetails['emp_name'];
						//	}else{
//								$emp_name=$result_['emp_name'];
					//		}
							$records[$k]["backofficependingg_list"][$result_['id']]['ward_no']=$safdtl['ward_no'];
							$records[$k]["backofficependingg_list"][$result_['id']]['assessment_type']=$safdtl['assessment_type'];
							$records[$k]["backofficependingg_list"][$result_['id']]['apply_date']=$safdtl['apply_date'];
							$records[$k]["backofficependingg_list"][$result_['id']]['forward_date']=$result_['payment_date'];
							$records[$k]["backofficependingg_list"][$result_['id']]['receiver_emp_name']=$emp_name;
							$records[$k]["backofficependingg_list"][$result_['id']]['receiver_user_type_id']=11;
						}
						$records[$k]['total']=$diffdaystillDate;
					}
				}
//				dd(array_column($records,'backofficeprogressl'),array_column($records,'backofficeprogressl_list'));
				if (isset($this->request->getVar()['report_type']) && $this->request->getVar()['report_type']=='list') {
					$saflist="";
					if(!isset($_GET['filter_type']))
					{
						return view('report/pendinglistsaf',$data);
					}
					$data['filter_type']=$_GET['filter_type'];

					$key=strtolower(str_replace(' ','',$data['filter_type']));
					$column=$key."pendingg_list";
					$datalists=array_values(array_column($records, $column));

					$data['lists']=$datalists;
					$data['from_date']=$from_date;
					$data['to_date']=$to_date;

//					$data['backofficependingg_list']=array_column($records, 'backofficependingg_list');
//					$data['dealingassistantpendingg_list']=array_column($records, 'dealingassistantpendingg_list');
//					$data['taxcollectorpendingg_list']=array_column($records, 'taxcollectorpendingg_list');
//					$data['ulbtaxcollectorpendingg_list']=array_column($records, 'ulbtaxcollectorpendingg_list');
//					$data['propertysectioninchargependingg_list']=array_column($records, 'propertysectioninchargependingg_list');
//					$data['executiveofficerpendingg_list']=array_column($records, 'executiveofficerpendingg_list');
////					dd($data['backofficependingg_list'],
//						$data['dealingassistantpendingg_list'],
//						$data['taxcollectorpendingg_list'],
//						$data['ulbtaxcollectorpendingg_list'],
//						$data['propertysectioninchargependingg_list'],
//						$data['executiveofficerpendingg_list']
//					);
//					dd($data['lists']);

					return view('report/pendinglistsaf',$data);

				
				}
			}catch (Exception $e)
			{
				$records=[];
			}
		}
		//$report=$this->model_level_pending_dtl->levelwisecount($whereDateRange,$to_date);
//		dd(array_column($records, 'backofficedoneg'),
//			array_column($records, 'backofficedonel'),
//			array_column($records, 'backofficeprogressl'),
//			array_column($records, 'backofficependingg')
//		);
		$data['backofficedonel']=array_sum(array_column($records, 'backofficedonel'));
		$data['backofficedoneg']=array_sum(array_column($records, 'backofficedoneg'));


		$data['backofficeprogressl']=array_sum(array_column($records, 'backofficeprogressl'));
		$data['backofficependingg']=array_sum(array_column($records, 'backofficependingg'));

//		dd($data['backofficedonel'],$data['backofficedoneg'],$data['backofficeprogressl'],
//		$data['backofficependingg']);

		$data['backofficependingg_list']=array_column($records, 'backofficependingg_list');
//		dd($data['backofficependingg_list']);
//		dd($records, 'backofficependingg_list');

		$data['dealingassistantdonel']=array_sum(array_column($records, 'dealingassistantdonel'));
		$data['dealingassistantdoneg']=array_sum(array_column($records, 'dealingassistantdoneg'));
		$data['dealingassistantprogressl']=array_sum(array_column($records, 'dealingassistantprogressl'));
		$data['dealingassistantpendingg']=array_sum(array_column($records, 'dealingassistantpendingg'));

		$data['taxcollectordonel']=array_sum(array_column($records, 'taxcollectordonel'));
		$data['taxcollectordoneg']=array_sum(array_column($records, 'taxcollectordoneg'));
		$data['taxcollectorprogressl']=array_sum(array_column($records, 'taxcollectorprogressl'));
		$data['taxcollectorpendingg']=array_sum(array_column($records, 'taxcollectorpendingg'));

		$data['ulbtaxcollectordonel']=array_sum(array_column($records, 'ulbtaxcollectordonel'));
		$data['ulbtaxcollectordoneg']=array_sum(array_column($records, 'ulbtaxcollectordoneg'));
		$data['ulbtaxcollectorprogressl']=array_sum(array_column($records, 'ulbtaxcollectorprogressl'));
		$data['ulbtaxcollectorpendingg']=array_sum(array_column($records, 'ulbtaxcollectorpendingg'));

		$data['propertysectioninchargedonel']=array_sum(array_column($records, 'propertysectioninchargedonel'));
		$data['propertysectioninchargedoneg']=array_sum(array_column($records, 'propertysectioninchargedoneg'));
		$data['propertysectioninchargeprogressl']=array_sum(array_column($records, 'propertysectioninchargeprogressl'));
		$data['propertysectioninchargependingg']=array_sum(array_column($records, 'propertysectioninchargependingg'));

		$data['executiveofficerdonel']=array_sum(array_column($records, 'executiveofficerdonel'));
		$data['executiveofficerdoneg']=array_sum(array_column($records, 'executiveofficerdoneg'));
		$data['executiveofficerprogressl']=array_sum(array_column($records, 'executiveofficerprogressl'));
		$data['executiveofficerpendingg']=array_sum(array_column($records, 'executiveofficerpendingg'));

		$data['from_date']=$from_date;
		$data['to_date']=date('Y-m-d',strtotime($to_date.'+1 day'));
		$data['to_date']=$to_date;//date('Y-m-d',strtotime($to_date.'+1 day'));
		return view('report/reportleveltimetaken2',$data);
	}

	public function calssaftime3($leveldtl,$paymentDate){
		$paymentDate=new DateTime($paymentDate);
		$paymentDate->modify('+1 day');
		$user_type=['Back Office','Dealing Assistant','Tax Collector','ULB Tax Collector','Property Section Incharge','Executive Officer'];
		$user_typetimeline=['backoffice'=>2,'dealingassistant'=>5,'taxcollector'=>8,'ulbtaxcollector'=>5,'propertysectionincharge'=>3,'executiveofficer'=>2];
		$leveltimetaken=[];
		$leveltimetaken['backoffice']=0;
		$leveltimetaken['taxcollector']=0;
		$leveltimetaken['ulbtaxcollector']=0;
		$leveltimetaken['dealingassistant']=0;
		$leveltimetaken['executiveofficer']=0;
		$leveltimetaken['propertysectionincharge']=0;
		$totaldays=0;
		$pendingdays=0;
		$firstkey='';
		$lstind=count($leveldtl)-1;
		//back case
		$lastlevel=$leveldtl[$lstind];

		//
		$setid=7;
		//
		$saf_dtl_id=$leveldtl[0]['saf_dtl_id'];
		$input['saf_dtl_id']=$saf_dtl_id;
		$safdtl=$this->model_saf_dtl->getSafDtlById2($input);
//		dd($safdtl);
		foreach ($leveldtl as $k=>$dtl)
		{
//			if($dtl['receiver_user_type_id']!=$setid && $dtl['sender_user_type_id']!=$setid)
//			{
//				continue;
//			}
			$key=strtolower(str_replace(' ','',$dtl['user_type']));
			//initial condition
			$leveltimetaken["$key"."progressl"]=0;
			$leveltimetaken["$key"."pendingg"]=0;
			$leveltimetaken["$key"."donel"]=0;
			$leveltimetaken["$key"."doneg"]=0;

			if($lastlevel['receiver_user_type_id']==11){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				if($user_typetimeline['backoffice']>=$tdiffdays){
					$leveltimetaken["backofficeprogressl"]=1;
//					$leveltimetaken["backofficeprogressl_list"][]=$dtl['saf_dtl_id'];

					$leveltimetaken["backofficeprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safdtl['saf_no'];
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
					//if(empty($dtl['emp_name']))
					//{
						$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],$dtl['receiver_user_type_id'],$safdtl['ward_no']);
						$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['ward_no']=$safdtl['ward_no'];
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$safdtl['assessment_type'];
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['apply_date']=$safdtl['apply_date'];;
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
					$leveltimetaken["backofficependingg"]=1;
				}

//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']==11 && $lastlevel['receiver_user_type_id']==6){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				if($user_typetimeline['backoffice']>=$tdiffdays){
					$leveltimetaken["backofficedonel"]=1;
					$leveltimetaken["backofficedonel_list"][]=$dtl['saf_dtl_id'];
				}else{
					$leveltimetaken["backofficedoneg_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken["backofficedoneg"]=1;
				}

///
///
				if($user_typetimeline['dealingassistant']>=$tdiffdays){
					$leveltimetaken["dealingassistantprogressl"]=1;
//					$leveltimetaken["dealingassistantprogressl_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken["dealingassistantprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$safdtl['ward_no'];
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$safdtl['assessment_type'];
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$safdtl['apply_date'];;
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safdtl['saf_no'];
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
					//if(empty($dtl['emp_name']))
					//{
						$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],$dtl['receiver_user_type_id'],$safdtl['ward_no']);
						$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;

					//$leveltimetaken["dealingassistantpendingg_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken["dealingassistantpendingg"]=1;
				}
//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']!=11 && $lastlevel['sender_user_type_id']>$lastlevel['receiver_user_type_id']) {
				if($dtl['sender_user_type_id']==$lastlevel['receiver_user_type_id'])
				{
					$toDate = new DateTime();
					$forward_date = new DateTime($lastlevel['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
					if($user_typetimeline[$key]>=$tdiffdays){
						$leveltimetaken[$key."progressl"]=1;
						$leveltimetaken["$key"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
//						$leveltimetaken["$key"."progressl_list"][]=$dtl['saf_dtl_id'];
					}else{
						$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safdtl['saf_no'];
						$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
							$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],$dtl['receiver_user_type_id'],$safdtl['ward_no']);
							$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}

						$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
						$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];

						$leveltimetaken[$key."pendingg"]=1;
						//dd($leveltimetaken);
					}
					continue;
				}
			}


			if($dtl['status']==0){ //verification_status
				if($dtl['date_difference']==NULL && $dtl['sender_user_type_id']==11){
					$created = new DateTime($dtl['forward_date']);
					$diffdays = $paymentDate->diff($created)->format("%r%a");
					$dtl['date_difference']=$diffdays;
				//	if($dtl['saf_dtl_id']=='310699'){
//						dd($diffdays,$created,$paymentDate,$dtl['date_difference'],$dtl['sender_user_type_id'],$key);
				//	}
				}
				if($user_typetimeline[$key]>=$dtl['date_difference']){
//					dd($dtl['saf_dtl_id'],$key,$user_typetimeline[$key],$dtl);
					$leveltimetaken["$key"."donel_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken[$key."donel"]=1;
//					$leveltimetaken[$key."progressl"]=0;
				}else{
					$leveltimetaken["$key"."doneg_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken[$key."doneg"]=1;
//					$leveltimetaken[$key."pendingg"]=0;
				}
			}

			if(array_key_exists($key,$leveltimetaken))
			{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] += $dtl['date_difference'];
			}else{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] = $dtl['date_difference'];
			}

			if($k==0)
			{
				$firstkey=$key;
				$created = new DateTime($dtl['created_on']);
				$paymentDate = $paymentDate; //new DateTime($paymentDate);
				$diffdays = $paymentDate->diff($created)->format("%r%a");
				$totaldays += $diffdays;
				$leveltimetaken[$firstkey] += $diffdays;

				//

				if($dtl['status']==1){ //verification_status
					$senderuserkey=strtolower(str_replace(' ','',$dtl["user_type"]));

					if($user_typetimeline[$senderuserkey]>=$diffdays){
						$leveltimetaken["$senderuserkey"."donel_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken["$senderuserkey"."donel"]=1;
					}else{
						$leveltimetaken["$senderuserkey"."doneg_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken["$senderuserkey"."doneg"]=1;
					}
					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$dtl[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					$toDate = new DateTime();
					$forward_date = new DateTime($dtl['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
						$leveltimetaken[$lstuserkey."progressl"]=1;
						//if($dtl['saf_dtl_id']=='312073')
						//	{
						//			dd($dtl['saf_dtl_id']);
						//	}
//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
					}else{
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safdtl['saf_no'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
							$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],$dtl['receiver_user_type_id'],$safdtl['ward_no']);
							$emp_name=$employeedetails['emp_name'];
						//}else{
							$emp_name=$dtl['emp_name'];
						//}
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$safdtl['ward_no'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$safdtl['assessment_type'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$safdtl['apply_date'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
//						$leveltimetaken["$lstuserkey"."pendingg_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."pendingg"]=1;
					}
				}else{
					if($user_typetimeline[$key]>=$diffdays){
//						if($dtl['saf_dtl_id']=='310411'){
//							dd($user_typetimeline[$key],$paymentDate,$created,$diffdays,$user_typetimeline[$key]>=$diffdays);
//						}
						$leveltimetaken["$key"."donel_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken[$key."donel"]=1;
					}else{
						$leveltimetaken["$key"."doneg_list"][]=$dtl['saf_dtl_id'];
						$leveltimetaken[$key."doneg"]=1;
					}
				}
			}

			if($k==$lstind && $k!=0){
//				dd($leveldtl[$lstind],$dtl);
				//$lstsql="select * from tbl_bugfix_level_pending_dtl where id='".$dtl['id']."'";
				//$lstexe=$this->db->query($lstsql);
				$lstresult=$dtl;//$lstexe->getFirstRow('array');
				$forward_date = new DateTime($lstresult['forward_date']);
				$toDate = new DateTime();
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				$pendingdays += $tdiffdays;
				if($lstresult['receiver_user_type_id']!=$lstresult['sender_user_type_id']){

					$senderuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[sender_user_type_id]");
					$senderuser=$senderuser->getResultArray();
					$senderuserkey=strtolower(str_replace(' ','',$senderuser[0]["user_type"]));
					$pre_date = new DateTime($lstresult['prev_date']);
					$sender_tdiffdays = $pre_date->diff($forward_date)->format("%r%a");
					if($user_typetimeline[$senderuserkey]>=$sender_tdiffdays){
						$leveltimetaken["$senderuserkey"."donel_list"][]=$lstresult['saf_dtl_id'];
						$leveltimetaken["$senderuserkey"."donel"]=1;
					}else{
						$leveltimetaken["$senderuserkey"."doneg_list"][]=$lstresult['saf_dtl_id'];
						$leveltimetaken["$senderuserkey"."doneg"]=1;
					}
					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					//print_r($lstuserkey);
					$leveltimetaken["$lstuserkey"] += $pendingdays;
					$totaldays += $pendingdays;

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$lstresult['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."progressl_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."progressl"]=1;
					}else{
						$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_no']=$safdtl['saf_no'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
							$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],$dtl['receiver_user_type_id'],$safdtl['ward_no']);
							$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}

						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$safdtl['ward_no'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$safdtl['assessment_type'];
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$safdtl['apply_date'];;
						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];

						//$leveltimetaken["$lstuserkey"."pendingg_list"][]=$lstresult['saf_dtl_id'];

						$leveltimetaken["$lstuserkey"."pendingg"]=1;
					}
				}
				//$leveltimetaken[$firstkey] += $diffdays;
			}
		}
		$lgetmemo=$this->db->query("SELECT created_on FROM tbl_saf_memo_dtl WHERE saf_dtl_id = $dtl[saf_dtl_id] AND memo_type = 'FAM'");
		$lgetmemono=$lgetmemo->getFirstRow('array');

		$leveltimetaken['approve_date']=isset($lgetmemono['created_on'])?date('Y-m-d',strtotime($lgetmemono['created_on'])):"NA";
		$leveltimetaken['total']=$totaldays;
		$leveltimetaken["totalcount"]=$lgetmemono['created_on']??"";
		return $leveltimetaken;
	}


		//2-2-2024/adee
		public function reportleveltimetaken()
		{
			$data = (array)null;
			$result = (array)null;
			$Session = session();
			$ulb_dtl = $Session->get('ulb_dtl');
			$ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
			$data = arrFilterSanitizeString($this->request->getVar());
			$data['wardList'] = $this->model_ward_mstr->getWardListWithSession(["ulb_mstr_id"=>$ulb_mstr_id], $Session);
			return view('report/reportleveltimetaken',$data);
		}
		public function reportleveltimetakenAjax(){
			$data = (array)null;
			if ($this->request->getMethod() == 'post') {
				 try {
					 $start = sanitizeString($this->request->getVar('start'));
	
					 $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
	
					 $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
					 $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
					 $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
					 $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
					 $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
					 $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
					 $whereQuery = "";
					 $wardNoWhere="";
					 $whereSafNo="";
					 $whereDateRange="";
					 $saf_no = $this->request->getVar("saf_no");
					 $from_date = $this->request->getVar("search_from_date");
					 $to_date = $this->request->getVar("search_upto_date");
					 $ward_mstr_id = $this->request->getVar("search_ward_mstr_id");
					 if ($saf_no!="") {
						 $whereSafNo = " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
					 } else {
						 if ($ward_mstr_id != "All") {
							 $wardNoWhere = " AND tbl_saf_dtl.ward_mstr_id='" . $ward_mstr_id . "'";
						 }
						 if ($from_date != "" && $to_date != "") {
							 $whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
						 }
					 }
					$whereQuery=$whereDateRange.$wardNoWhere.$whereSafNo;
					 $whereQueryWithSearch = "";
					if ($searchValue != '') {
						if ($searchValue!='') {
							$whereQueryWithSearch .= " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
						}
					}
	
					 $selectStatement="SELECT 
						ROW_NUMBER () OVER (ORDER BY " . "tbl_saf_dtl.id" . " DESC) AS s_no,
						tbl_saf_dtl.id,holding_no,saf_no,tbl_saf_dtl.ward_mstr_id,apply_date,assessment_type,holding_type,
						(select txn.tran_date from tbl_transaction txn where txn.prop_dtl_id=tbl_saf_dtl.id AND txn.status='1' AND txn.tran_type='Saf' limit 1) as payment_date,
						'' as approve_date,
						0 as backoffice,
						0 as dealingassistant,
						0 as taxcollector,0 as ulbtaxcollector,
						0 as propertysectionincharge,0 as executiveofficer,
						0 as total,
						0 as totalcount
						";
					 $sql = " FROM tbl_saf_dtl
					 --RIGHT join (select * from tbl_transaction group by tbl_transaction.prop_dtl_id,tbl_transaction.id limit 1) txn on txn.prop_dtl_id=tbl_saf_dtl.id
					 --RIGHT join tbl_transaction txn on txn.prop_dtl_id=tbl_saf_dtl.id
					--join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id 
				   -- RIGHT join tbl_level_pending_dtl tbllevel on tbllevel.saf_dtl_id=tbl_saf_dtl.id
					WHERE 
						 tbl_saf_dtl.status= '1' AND
						 tbl_saf_dtl.payment_status= '1' 
						 AND tbl_saf_dtl.saf_pending_status!='2'
						-- txn.status=1 AND txn.tran_type='Saf' 
					" . $whereQuery;
					$totalRecords = $this->model_datatable->getTotalRecords($sql);
					if ($totalRecords > 0)
					{
						//return $sql . $whereQueryWithSearch;
						$totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
						## Fetch records
						$fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
	//					$fetchSql = $selectStatement . $sql . $whereQueryWithSearch  . $limit;
						//return $fetchSql;
						$records = $this->model_datatable->getRecords($fetchSql, false);
					}
					else
					{
						$totalRecordwithFilter = 0;
						$records = [];
					}
					foreach ($records as $k=>$result_)
					{
						$saftime=$this->model_level_pending_dtl->getAllLevelDtl2($result_['id']);
						$records[$k]['leveldetails']=$saftime;
						if(count($saftime)>0){
							$calssaftime=$this->calssaftime($saftime,$result_['payment_date']);
							$records[$k]=array_merge($records[$k],$calssaftime);
						}else{
							$payment_date = new DateTime($result_['payment_date']);
							$payment_date->modify('+1 day');
							$tillDate = new DateTime();
							$diffdaystillDate = $payment_date->diff($tillDate)->format("%r%a");
							if($diffdaystillDate=="-0"){$diffdaystillDate=0;}
							$records[$k]['backoffice']=$diffdaystillDate;
							$records[$k]['total']=$diffdaystillDate;
						}
					}
					 $response = array(
						 "iTotalRecords" => $totalRecords,
						 "iTotalDisplayRecords" => $totalRecordwithFilter,
						 "aaData" => $records,
						 "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
					 );
						 return json_encode($response);
					 }
				catch (Exception $e)
				{
					print_var($e->getMessage());die;
				}
			}
		}
		public function calssaftime($leveldtl,$paymentDate){
			$paymentDate=new DateTime($paymentDate);
			$paymentDate->modify('+1 day');
			$user_type=['Back Office','Dealing Assistant','Tax Collector','ULB Tax Collector','Property Section Incharge','Executive Officer'];
			$leveltimetaken=[];
			$totaldays=0;
			$pendingdays=0;
			$firstkey='';
			$lstind=count($leveldtl)-1;
			foreach ($leveldtl as $k=>$dtl)
			{
				$key=strtolower(str_replace(' ','',$dtl['user_type']));
				if(array_key_exists($key,$leveltimetaken))
				{
					$totaldays += $dtl['date_difference'];
					$leveltimetaken[$key] += $dtl['date_difference'];
				}else{
					$totaldays += $dtl['date_difference'];
					$leveltimetaken[$key] = $dtl['date_difference'];
				}
				if($k==0)
				{
					$firstkey=$key;
					$created = new DateTime($dtl['created_on']);
					$paymentDate = $paymentDate;//new DateTime($paymentDate);
					$diffdays = $paymentDate->diff($created)->format("%r%a");
					$totaldays += $diffdays;
					$leveltimetaken[$firstkey] += $diffdays;
				}
				if($k==$lstind){
					$lstsql="select * from tbl_bugfix_level_pending_dtl where id='".$dtl['id']."'";
					$lstexe=$this->db->query($lstsql);
					$lstresult=$lstexe->getFirstRow('array');
	
					$forward_date = new DateTime($lstresult['forward_date']);
					$toDate = new DateTime();
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
					$pendingdays += $tdiffdays;
	//				if(!in_array($lstresult['receiver_user_type_id'],[9,10])){
					if($lstresult['receiver_user_type_id']!=$lstresult['sender_user_type_id']){
						$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=".$lstresult['receiver_user_type_id']);
						$lstuser=$lstuser->getFirstRow('array');
						$lstuserkey=strtolower(str_replace(' ','',$lstuser['user_type']));
						$leveltimetaken[$lstuserkey] += $pendingdays;
						$totaldays += $pendingdays;
					}
					//$leveltimetaken[$firstkey] += $diffdays;
				}
			}
			$lgetmemo=$this->db->query("SELECT created_on FROM tbl_saf_memo_dtl WHERE saf_dtl_id = $dtl[saf_dtl_id] AND memo_type = 'FAM'");
			$lgetmemono=$lgetmemo->getFirstRow('array');
	
			$leveltimetaken['approve_date']=isset($lgetmemono['created_on'])?date('Y-m-d',strtotime($lgetmemono['created_on'])):"NA";
			$leveltimetaken['total']=$totaldays;
			$leveltimetaken['totalcount']=$lgetmemono['created_on'];
			return $leveltimetaken;
		}
		public function exportreportleveltotaldays()
		{
			$data =(array)null;
			helper(['form']);
			$session = session();
			$whereQuery = "";
			$wardNoWhere = "";
			$whereDateRange = "";
			$whereSafNo = "";
	//        dd($_POST);
			$saf_no = $this->request->getVar("saf_no");
			$from_date = $this->request->getVar("from_date");
			$to_date = $this->request->getVar("to_date");
			$ward_mstr_id = $this->request->getVar("ward_mstr_id");
			if ($saf_no!="") {
				$whereSafNo = " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
			} else {
				if ($ward_mstr_id != "All") {
					$wardNoWhere = " AND tbl_saf_dtl.ward_mstr_id='" . $ward_mstr_id . "'";
				}
				if ($from_date != "" && $to_date != "") {
					$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
				}
			}
			$whereQuery=$whereDateRange.$wardNoWhere.$whereSafNo;
			$whereQueryWithSearch = "";
	
			$selectStatement="SELECT 
						ROW_NUMBER () OVER (ORDER BY " . "tbl_saf_dtl.id" . " DESC) AS s_no,
						tbl_saf_dtl.id,saf_no,apply_date,
						(select txn.tran_date from tbl_transaction txn where txn.prop_dtl_id=tbl_saf_dtl.id AND txn.status='1' AND txn.tran_type='Saf' limit 1) as payment_date,
						'' as approve_date,
						0 as backoffice,
						0 as dealingassistant,0 as dealingassistant,
						0 as taxcollector,0 as ulbtaxcollector,
						0 as propertysectionincharge,0 as executiveofficer,
						0 as total,
						0 as totalcount
						";
			$sql = " FROM tbl_saf_dtl
					WHERE 
						 tbl_saf_dtl.status= '1' AND
						 tbl_saf_dtl.payment_status= '1'
						 AND tbl_saf_dtl.saf_pending_status!='2'
					" . $whereQuery;
			$fetchSql = $selectStatement . $sql . $whereQueryWithSearch;
			$results = $this->db->query($fetchSql, false);
			$records=$results->getResultArray();
			foreach ($records as $k=>$result_)
			{
				$saftime=$this->model_level_pending_dtl->getAllLevelDtl2($result_['id']);
				$records[$k]['leveldetails']=$saftime;
				if(count($saftime)>0){
					$calssaftime=$this->calssaftime($saftime,$result_['payment_date']);
					$records[$k]=array_merge($records[$k],$calssaftime);
				}else{
					$payment_date = new DateTime($result_['payment_date']);
					$payment_date->modify('+1 day');
					$tillDate = new DateTime();
					$diffdaystillDate = $payment_date->diff($tillDate)->format("%r%a");
					if($diffdaystillDate=="-0"){$diffdaystillDate=0;}
					$records[$k]['backoffice']=$diffdaystillDate;
					$records[$k]['total']=$diffdaystillDate;
				}
			}
			$data['results']=$records;
	
	//        dd($data);
			if($data['results'] > 0){
				$delimiter = ",";
				$filename = "levelwisetotaldaystimetakenlist_" . date('Y-m-d') . ".csv";
	
				// Create a file pointer
				$f = fopen('php://memory', 'w');
	
				// Set column headers
				$fields = array('Sl No.', 'Saf No', 'Apply Date','Payment Date','Digitisation (TCA)',
					'Document Verification(ULB)','Geotagging(TCA)','ULB Tax Collector Stage1',
					'Property Section Incharge Stage2','DMC/AMC Stage3','Approve Date','Total Days');
				fputcsv($f, $fields, $delimiter);
				$j=1;
				foreach($data['results'] as $leveldata){
					$rowdata=[$leveldata['s_no'],$leveldata['saf_no'],$leveldata['apply_date'],$leveldata['payment_date'],
						$leveldata['backoffice'],$leveldata['dealingassistant'],$leveldata['taxcollector'],
						$leveldata['ulbtaxcollector'],$leveldata['propertysectionincharge'],
						$leveldata['executiveofficer'],$leveldata['approve_date'],$leveldata['total']];
					fputcsv($f, $rowdata, $delimiter);
				}
	
				fseek($f, 0);
	
				// Set headers to download file rather than displayed
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="' . $filename . '";');
	
				//output all remaining data on a file pointer
				fpassthru($f);
	
			}
			exit;
		}
	
	public function reportUserWiseLevelPending($userType){
		$userTypeId = null;
		$geotagJoin = "";
		$saf_pending_status = " AND tbl_saf_dtl.saf_pending_status=0 ";
		$verification_status = " AND tbl_level_pending_dtl.verification_status=0 ";
        if ($userType=="Tax Collector") $userTypeId = 5;
		if ($userType=="Dealing Assistant") $userTypeId = 6;
		if ($userType=="ULB Tax Collector"){ 
			$userTypeId = 7;
			$geotagJoin = "JOIN (
						SELECT
							geotag_dtl_id
						FROM tbl_saf_geotag_upload_dtl
						WHERE status=1
						GROUP BY geotag_dtl_id
					) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id";
		}
		if ($userType=="Property Section Incharge") $userTypeId = 9;
		if ($userType=="Executive Officer") $userTypeId = 10;
		if ($userType=="Back Office"){ 
			$userTypeId = 11; 
			$saf_pending_status = "";
			$verification_status = " AND tbl_level_pending_dtl.verification_status=2 ";
		}

		if ($userType!=null) {
			$data = [];
			$data["user_type"] = $userType;
			$sql = "SELECT  
						COUNT(DISTINCT saf_dtl_id) AS levelform , 
						receiver_user_type_id , 
						view_user_type_mstr.user_type,
						users.emp_dtl_id,
						emp_name
					FROM tbl_level_pending_dtl
					JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id
					JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id ".$geotagJoin."
					JOIN (
						SELECT DISTINCT(view_ward_permission.emp_details_id) AS emp_details_id, 
							view_ward_permission.ward_mstr_id ,
							user_type_id,view_emp_details.id AS emp_dtl_id, emp_name
						FROM view_emp_details
						JOIN view_ward_permission ON view_ward_permission.emp_details_id = view_emp_details.id 
							AND view_ward_permission.status =1
						WHERE view_emp_details.user_type_id=".$userTypeId."
							AND view_emp_details.user_mstr_lock_status=0
							AND view_emp_details.user_mstr_status =1
							AND view_emp_details.id NOT IN (1016, 44, 985, 294)
						GROUP BY view_ward_permission.emp_details_id,view_emp_details.user_type_id,
							view_emp_details.emp_name, view_emp_details.id ,view_ward_permission.ward_mstr_id
					) users ON  users.user_type_id = tbl_level_pending_dtl.receiver_user_type_id 
					AND tbl_saf_dtl.ward_mstr_id=users.ward_mstr_id
					WHERE 
						tbl_level_pending_dtl.status=1 ".$verification_status."
						AND view_user_type_mstr.status=1 ".$saf_pending_status."
						AND tbl_saf_dtl.status=1 
						AND receiver_user_type_id=".$userTypeId."
					GROUP BY receiver_user_type_id, view_user_type_mstr.user_type, users.emp_name, users.emp_dtl_id
					ORDER BY receiver_user_type_id;";
					//print_var($sql);
			if($result = $this->db->query($sql)->getResultArray()) {
				//print_var($result);
				$data["user_wise_pending_result"] = $result;
			}
			return view('report/userWiseLevelPendingDetails', $data);
			
		}

        
		
    }

	public function reportUserWiseWardWireLevelPending($userTypeId, $empDtlId, $empName = null){
		$geotagJoin = "";
		$saf_pending_status = " AND tbl_saf_dtl.saf_pending_status=0 ";
		$verification_status = " AND tbl_level_pending_dtl.verification_status=0 ";

		$safApplyDate = "";
		$inputs = arrFilterSanitizeString($this->request->getVar());
		if (isset($inputs["step_type"]) && isset($inputs["from_date"]) && isset($inputs["upto_date"])) {
			if ($inputs["step_type"]=="by_date_range" && $inputs["from_date"]!="" && $inputs["upto_date"]!="") {
				$from_date = $inputs["from_date"];
				$upto_date = $inputs["upto_date"];
				$safApplyDate = " AND tbl_saf_dtl.apply_date BETWEEN '".$from_date."' AND '".$upto_date."'";
			}
		}

		if ($userTypeId==7){ 
			$geotagJoin = "JOIN (
						SELECT
							geotag_dtl_id
						FROM tbl_saf_geotag_upload_dtl
						WHERE status=1
						GROUP BY geotag_dtl_id
					) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id";
		}
		if ($userTypeId==11){ 
			$saf_pending_status = "";
			$verification_status = " AND tbl_level_pending_dtl.verification_status=2 ";
		}

		if ($userTypeId!=null) {
			$data["empname"] = $empName;
			$sql = "SELECT  
						COUNT(DISTINCT saf_dtl_id) AS levelform, 
						view_ward_mstr.ward_no,
						tbl_saf_dtl.ward_mstr_id
					FROM tbl_level_pending_dtl
					JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_level_pending_dtl.receiver_user_type_id
					JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id".$safApplyDate."
					JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$geotagJoin."
					JOIN (
						SELECT DISTINCT(view_ward_permission.emp_details_id) AS emp_details_id, 
							view_ward_permission.ward_mstr_id ,
							user_type_id,view_emp_details.id AS emp_dtl_id, emp_name
						FROM view_emp_details
						JOIN view_ward_permission ON view_ward_permission.emp_details_id = view_emp_details.id 
							AND view_ward_permission.status =1
						WHERE view_emp_details.user_type_id=".$userTypeId."
							AND view_emp_details.user_mstr_lock_status=0
							AND view_emp_details.user_mstr_status =1
							AND view_emp_details.id=".$empDtlId."
						GROUP BY view_ward_permission.emp_details_id,view_emp_details.user_type_id,
							view_emp_details.emp_name, view_emp_details.id ,view_ward_permission.ward_mstr_id
					) users ON  users.user_type_id = tbl_level_pending_dtl.receiver_user_type_id 
					AND tbl_saf_dtl.ward_mstr_id=users.ward_mstr_id
					WHERE 
						tbl_level_pending_dtl.status=1 ".$verification_status."
						AND view_user_type_mstr.status=1 ".$saf_pending_status."
						AND tbl_saf_dtl.status=1 
						AND receiver_user_type_id=".$userTypeId."
					GROUP BY tbl_saf_dtl.ward_mstr_id, view_ward_mstr.ward_no;";
				//print_var($sql);
			if($result = $this->db->query($sql)->getResultArray()) {
				//print_var($result);
				$data["user_wise_pending_result"] = $result;
			}
			return view('report/userWiseWardWiseLevelPendingDetails', $data);
			
		}

        
		
    }
	
	public function exportreportlevelpending()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        
		$data['levelpending'] = $this->model_level_pending_dtl->getlevelwiseform();
		if($data['levelpending'] > 0){ 
			$delimiter = ","; 
			$filename = "levelwisependingformlist_" . date('Y-m-d') . ".csv"; 
			 
			// Create a file pointer 
			$f = fopen('php://memory', 'w'); 
			 
			// Set column headers 
			$fields = array('Sl No.', 'Level', 'Total No of Form(s)'); 
			fputcsv($f, $fields, $delimiter); 
			$j=1;
			for($i=0;$i<=4;$i++){
				if($data['levelpending'][$i]['receiver_user_type_id']==5){
					$data['agency_tc'] = $data['levelpending'][$i]['levelform'];
					$lineData = array($j, 'Agency TC', $data['agency_tc']); 
				}else if($data['levelpending'][$i]['receiver_user_type_id']==6){
					$data['dealing'] = $data['levelpending'][$i]['levelform'];
					$lineData = array($j, 'Dealing Assistant', $data['dealing']);
				}else if($data['levelpending'][$i]['receiver_user_type_id']==7){
					$data['ulb_tc'] = $data['levelpending'][$i]['levelform'];
					$lineData = array($j, 'ULB Tax Collector', $data['ulb_tc']);
				}else if($data['levelpending'][$i]['receiver_user_type_id']==9){
					$data['section_incharge'] = $data['levelpending'][$i]['levelform'];
					$lineData = array($j, 'Section Incharge', $data['section_incharge']);
				}else if($data['levelpending'][$i]['receiver_user_type_id']==10){
					$data['executive_officer'] = $data['levelpending'][$i]['levelform'];
					$lineData = array($j, 'Executive Officer', $data['executive_officer']);
				}
				$j++;
				fputcsv($f, $lineData, $delimiter); 
			}
			
			fseek($f, 0); 
		 
			// Set headers to download file rather than displayed 
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' . $filename . '";'); 
			 
			//output all remaining data on a file pointer 
			fpassthru($f); 
		
		}
		exit; 
    }
	
	public function levelformdetail($ID=null)
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
		$data['id'] = $ID;

		$verification_status = 0;
		if(md5(11) == $data['id'])
		{
			$verification_status = 2;
		}
		
        $sql="SELECT
                    distinct tbl_saf_dtl.id,
                    view_ward_mstr.ward_no AS ward_no,
                    tbl_saf_dtl.saf_no AS saf_no,
					tbl_saf_dtl.prop_address AS address,
                    saf_owner_detail.owner_name AS owner_name,
                    saf_owner_detail.mobile_no AS mobile_no,
					tbl_prop_type_mstr.property_type
                FROM tbl_saf_dtl
				LEFT JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
				LEFT JOIN tbl_prop_type_mstr on tbl_saf_dtl.prop_type_mstr_id=tbl_prop_type_mstr.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::text, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                where tbl_level_pending_dtl.status=1 and tbl_saf_dtl.status=1 and tbl_level_pending_dtl.verification_status=".$verification_status." and md5(tbl_level_pending_dtl.receiver_user_type_id::text)='".$data['id']."'";
				
		$result = $this->model_datatable->getDatatable($sql);
		$result_list = $result['result'];

		$data['levelpending'] = $result_list;
		$data['pager'] = $result['count'];
		
		return view('report/levelformlist',$data);
    }
	
	public function exportlevelformdetail($ID=null)
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
		$data['id'] = $ID;
        		
		$data['levelformdetail'] = $this->model_saf_dtl->levelformdetail($data['id']);	
		$length = sizeof($data['levelformdetail']);
		
		if($data['levelformdetail'] > 0){ 
			$delimiter = ","; 
			$filename = "levelwisependingformdetail_" . date('Y-m-d') . ".csv"; 
			 
			// Create a file pointer 
			$f = fopen('php://memory', 'w'); 
			 
			// Set column headers 
			$fields = array('Sl No.', 'Ward No.', 'SAF No.', 'Property Type', 'Owner Name', 'Mobile No.', 'Address');  
			fputcsv($f, $fields, $delimiter); 
			$j=1;
			for($i=0;$i<$length;$i++){
				$lineData = array($j, $data['levelformdetail'][$i]['ward_no'], $data['levelformdetail'][$i]['saf_no'], $data['levelformdetail'][$i]['property_type'], $data['levelformdetail'][$i]['owner_name'], $data['levelformdetail'][$i]['mobile_no'], $data['levelformdetail'][$i]['address']);
				$j++;
				fputcsv($f, $lineData, $delimiter); 
			}
			
			fseek($f, 0); 
		 
			// Set headers to download file rather than displayed 
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' . $filename . '";'); 
			 
			//output all remaining data on a file pointer 
			fpassthru($f); 
		
		}
		exit;
    }



	///////////////////////////////////////////////////////

	public function reportleveltimetakenv3(){

		$data = (array)null;
		$records = (array)null;
		$Session = Session();
		$ulb_dtl = $Session->get('ulb_dtl');
		$ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
		$data['totalsaf']=0;
		$data['totalpendingsaf']=0;
		$request = arrFilterSanitizeString($this->request->getVar());
		$from_date="2024-02-07";
		$to_date=date('Y-m-d',strtotime('-1 day'));
		$whereDateRange="";
		if(isset($request['from_date']))
		{
			$from_date=$request['from_date'];
		}
		if(isset($request['to_date']))
		{
//			$to_date=date('Y-m-d',strtotime($request['to_date'].'-1 day')) ;
			$to_date=date('Y-m-d',strtotime($request['to_date'])) ;
		}
		if ($from_date != "" && $to_date != "") {
			$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
		}
		if (isset($this->request->getVar()['report_type']) && empty($this->request->getVar()['filter_type'])) {
			return view('report/pendinglistsaf',$data);
		}

//		$totalsaf="select * from tbl_saf_dtl where tbl_saf_dtl.status = '1' AND tbl_saf_dtl.payment_status= '1' $whereDateRange";
//		$totalsaf=$this->db->query($totalsaf)->getResultArray();
//		dd(isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date']));

		if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
			ini_set('memory_limit', '-1');
			try {
				$orderBY = " order by tbl_saf_dtl.id asc";//" ORDER BY " . $columnName . " " . $columnSortOrder;
				$from_date = $this->request->getVar("from_date");
				$to_date = $this->request->getVar("to_date");
				$limit ="";
//				$limit =" OFFSET 50 LIMIT 100";
				if ($from_date != "" && $to_date != "") {
					$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
				}
				$whereQuery=$whereDateRange;
				$selectStatement_total="SELECT count(*) FROM tbl_saf_dtl
					WHERE 
						 tbl_saf_dtl.status= '1' AND
						 tbl_saf_dtl.payment_status= '1' AND 
						 tbl_saf_dtl.saf_pending_status!='2'
					" . $whereQuery;
				$fetchSql = $selectStatement_total.$limit;
				$records = $this->db->query($fetchSql);
				$records = $records->getResultArray();
				$data['totalsaf']=$records[0]['count'];


				$selectStatement_pending="SELECT 
						ROW_NUMBER () OVER (ORDER BY " . "tbl_saf_dtl.id" . " DESC) AS s_no,
						tbl_saf_dtl.id,holding_no,saf_no,tbl_saf_dtl.ward_mstr_id,apply_date,assessment_type,holding_type,
						(select txn.tran_date from tbl_transaction txn where txn.prop_dtl_id=tbl_saf_dtl.id AND txn.status= '1' 
AND txn.tran_type='Saf' limit 1)as payment_date,
						'' as approve_date,
						0 as backoffice,
						0 as juniorengineer,
						0 as dealingassistant,
						0 as taxcollector,
						0 as ulbtaxcollector,
						0 as propertysectionincharge,
						0 as executiveofficer,
						0 as total,
						0 as totalcount
						";
				$sql = " FROM tbl_saf_dtl
					WHERE 
						 tbl_saf_dtl.status= '1' AND
						 tbl_saf_dtl.payment_status= '1' AND 
						 tbl_saf_dtl.saf_pending_status NOT IN (1,2)
					" . $whereQuery;

				$fetchSql = $selectStatement_pending.$sql.$limit;
				$records = $this->db->query($fetchSql);
				$records = $records->getResultArray();
				$data['totalpendingsaf']=count($records);

				foreach ($records as $k=>$result_)
				{
					$saftime=$this->model_level_pending_dtl->getAllLevelDtl2($result_['id']);
					$records[$k]['leveldetails']=$saftime;
					if(count($saftime)>0){
						if (isset($this->request->getVar()['report_type'])) {
							$usertype=$this->request->getVar()['filter_type'];
							$calssaftime=$this->calssaftime4($saftime,$result_['payment_date']);
						}else{
							$calssaftime=$this->calssaftimev3($saftime,$result_['payment_date'],
							$result_['id'],$result_['saf_no'],$result_['ward_mstr_id'],$result_['assessment_type'],$result_['ward_mstr_id'],$result_['apply_date']);
						}
						$records[$k]= array_merge($records[$k],$calssaftime);
//						dd($records[$k]);
					}else{
						$payment_date = new DateTime($result_['payment_date']);
						$payment_date->modify('+1 day');
						$tillDate = new DateTime();
						$diffdaystillDate = $payment_date->diff($tillDate)->format("%r%a");
						if($diffdaystillDate=="-0"){$diffdaystillDate=0;}
						$records[$k]['backoffice']=$diffdaystillDate;

						if(2>=$diffdaystillDate){
							if($result_['id']=='312073')
							{
								//	dd($result_['id']);
							}
							$records[$k]['backofficeprogressl']=1;
							$records[$k]["backofficeprogressl_list"][$result_['id']]['saf_dtl_id']=$result_['id'];
						}else{
							$saf_dtl_id=$result_['id'];
							$input['saf_dtl_id']=$saf_dtl_id;

							$safdtl=$this->model_saf_dtl->getSafDtlById2($input);
							$records[$k]['backofficependingg']=1;
//							$records[$k]["backofficependingg_list"][]=$result_['id'];
							$records[$k]["backofficependingg_list"][$result_['id']]['saf_dtl_id']=$safdtl['id'];
							$records[$k]["backofficependingg_list"][$result_['id']]['saf_no']=$safdtl['saf_no'];
							//if(empty($result_['emp_name']))
							//{
							$employeedetails=$this->model_level_pending_dtl->employeedetails($safdtl['ward_mstr_id'],'11',$safdtl['ward_no']);
							$emp_name=$employeedetails['emp_name'];
							//	}else{
//								$emp_name=$result_['emp_name'];
							//		}
							$records[$k]["backofficependingg_list"][$result_['id']]['ward_no']=$safdtl['ward_no'];
							$records[$k]["backofficependingg_list"][$result_['id']]['assessment_type']=$safdtl['assessment_type'];
							$records[$k]["backofficependingg_list"][$result_['id']]['apply_date']=$safdtl['apply_date'];
							$records[$k]["backofficependingg_list"][$result_['id']]['forward_date']=$result_['payment_date'];
							$records[$k]["backofficependingg_list"][$result_['id']]['receiver_emp_name']=$emp_name;
							$records[$k]["backofficependingg_list"][$result_['id']]['receiver_user_type_id']=11;
						}
						$records[$k]['total']=$diffdaystillDate;
					}
				}
//				dd(array_column($records,'backofficeprogressl'),array_column($records,'backofficeprogressl_list'));
				if (isset($this->request->getVar()['report_type']) && $this->request->getVar()['report_type']=='list') {
					$saflist="";
					if(!isset($_GET['filter_type']))
					{
						return view('report/pendinglistsaf',$data);
					}
					$data['filter_type']=$_GET['filter_type'];

					$key=strtolower(str_replace(' ','',$data['filter_type']));
					$column=$key."pendingg_list";
					$datalists=array_values(array_column($records, $column));

					$data['lists']=$datalists;
					$data['from_date']=$from_date;
					$data['to_date']=$to_date;

//					$data['backofficependingg_list']=array_column($records, 'backofficependingg_list');
//					$data['dealingassistantpendingg_list']=array_column($records, 'dealingassistantpendingg_list');
//					$data['taxcollectorpendingg_list']=array_column($records, 'taxcollectorpendingg_list');
//					$data['ulbtaxcollectorpendingg_list']=array_column($records, 'ulbtaxcollectorpendingg_list');
//					$data['propertysectioninchargependingg_list']=array_column($records, 'propertysectioninchargependingg_list');
//					$data['executiveofficerpendingg_list']=array_column($records, 'executiveofficerpendingg_list');
////					dd($data['backofficependingg_list'],
//						$data['dealingassistantpendingg_list'],
//						$data['taxcollectorpendingg_list'],
//						$data['ulbtaxcollectorpendingg_list'],
//						$data['propertysectioninchargependingg_list'],
//						$data['executiveofficerpendingg_list']
//					);
					dd($data);

					return view('report/pendinglistsaf',$data);

					dd($data['lists']);
					dd($records);
				}
			}catch (Exception $e)
			{
				$records=[];
			}
		}
		//$report=$this->model_level_pending_dtl->levelwisecount($whereDateRange,$to_date);
//		dd(array_column($records, 'backofficedoneg'),
//			array_column($records, 'backofficedonel'),
//			array_column($records, 'backofficeprogressl'),
//			array_column($records, 'backofficependingg')
//		);
		$data['backofficedonel']=array_sum(array_column($records, 'backofficedonel'));
		$data['backofficedoneg']=array_sum(array_column($records, 'backofficedoneg'));
		$data['backofficeprogressl']=array_sum(array_column($records, 'backofficeprogressl'));
		$data['backofficependingg']=array_sum(array_column($records, 'backofficependingg'));

//		dd($data['backofficedonel'],$data['backofficedoneg'],$data['backofficeprogressl'],
//		$data['backofficependingg']);

		$data['backofficependingg_list']=array_column($records, 'backofficependingg_list');
//		dd($data['backofficependingg_list']);
//		dd($records, 'backofficependingg_list');

		$data['juniorengineerdonel']=array_sum(array_column($records, 'juniorengineerdonel'));
		$data['juniorengineerdoneg']=array_sum(array_column($records, 'juniorengineerdoneg'));
		$data['juniorengineerprogressl']=array_sum(array_column($records, 'juniorengineerprogressl'));
		$data['juniorengineerpendingg']=array_sum(array_column($records, 'juniorengineerpendingg'));

		$data['dealingassistantdonel']=array_sum(array_column($records, 'dealingassistantdonel'));
		$data['dealingassistantdoneg']=array_sum(array_column($records, 'dealingassistantdoneg'));
		$data['dealingassistantprogressl']=array_sum(array_column($records, 'dealingassistantprogressl'));
		$data['dealingassistantpendingg']=array_sum(array_column($records, 'dealingassistantpendingg'));

		if($data['juniorengineerdonel']>$data['dealingassistantdonel']){
			$data['dealingassistantdonel']=$data['juniorengineerdonel'];
		}
		if($data['juniorengineerdoneg']>$data['dealingassistantdoneg']){
			$data['dealingassistantdoneg']=$data['juniorengineerdoneg'];
		}
		if($data['juniorengineerprogressl']>$data['dealingassistantprogressl']){
			$data['dealingassistantprogressl']=$data['juniorengineerprogressl'];
		}
		if($data['juniorengineerpendingg']>$data['dealingassistantpendingg']){
			$data['dealingassistantpendingg']=$data['juniorengineerpendingg'];
		}

		$data['taxcollectordonel']=array_sum(array_column($records, 'taxcollectordonel'));
		$data['taxcollectordoneg']=array_sum(array_column($records, 'taxcollectordoneg'));
		$data['taxcollectorprogressl']=array_sum(array_column($records, 'taxcollectorprogressl'));
		$data['taxcollectorpendingg']=array_sum(array_column($records, 'taxcollectorpendingg'));

		$data['ulbtaxcollectordonel']=array_sum(array_column($records, 'ulbtaxcollectordonel'));
		$data['ulbtaxcollectordoneg']=array_sum(array_column($records, 'ulbtaxcollectordoneg'));
		$data['ulbtaxcollectorprogressl']=array_sum(array_column($records, 'ulbtaxcollectorprogressl'));
		$data['ulbtaxcollectorpendingg']=array_sum(array_column($records, 'ulbtaxcollectorpendingg'));

		$data['propertysectioninchargedonel']=array_sum(array_column($records, 'propertysectioninchargedonel'));
		$data['propertysectioninchargedoneg']=array_sum(array_column($records, 'propertysectioninchargedoneg'));
		$data['propertysectioninchargeprogressl']=array_sum(array_column($records, 'propertysectioninchargeprogressl'));
		$data['propertysectioninchargependingg']=array_sum(array_column($records, 'propertysectioninchargependingg'));

		$data['executiveofficerdonel']=array_sum(array_column($records, 'executiveofficerdonel'));
		$data['executiveofficerdoneg']=array_sum(array_column($records, 'executiveofficerdoneg'));
		$data['executiveofficerprogressl']=array_sum(array_column($records, 'executiveofficerprogressl'));
		$data['executiveofficerpendingg']=array_sum(array_column($records, 'executiveofficerpendingg'));

		$data['callcenterexecutivedonel']=array_sum(array_column($records, 'callcenterexecutivedonel'));
		$data['callcenterexecutivedoneg']=array_sum(array_column($records, 'callcenterexecutivedoneg'));
		$data['callcenterexecutiveprogressl']=array_sum(array_column($records, 'callcenterexecutiveprogressl'));
		$data['callcenterexecutivependingg']=array_sum(array_column($records, 'callcenterexecutivependingg'));

		$data['admindonel']=array_sum(array_column($records, 'admindonel'));
		$data['admindoneg']=array_sum(array_column($records, 'admindoneg'));
		$data['adminprogressl']=array_sum(array_column($records, 'adminprogressl'));
		$data['adminpendingg']=array_sum(array_column($records, 'adminpendingg'));


		$data['from_date']=$from_date;
		$data['to_date']=date('Y-m-d',strtotime($to_date.'+1 day'));
		$data['to_date']=$to_date;//date('Y-m-d',strtotime($to_date.'+1 day'));
		//dd($data);
		return view('report/reportleveltimetaken2',$data);
	}
	public function calssaftimev3old($leveldtl,$paymentDate,$safid,$safno,$ward_mstr_id,$assessment_type,$wardno,$apply_date){
		$paymentDate=new DateTime($paymentDate);
		$paymentDate->modify('+1 day');
		$user_type=['Back Office','Dealing Assistant','Tax Collector','ULB Tax Collector','Property Section Incharge','Executive Officer'];
		$user_typetimeline=['backoffice'=>2,'dealingassistant'=>5,'taxcollector'=>8,'ulbtaxcollector'=>5,'propertysectionincharge'=>3,'executiveofficer'=>2,
			'admin'=>2,'juniorengineer'=>2,'callcenterexecutive'=>2,'jsk'=>2];
		$leveltimetaken=[];
		$leveltimetaken['backoffice']=0;
		$leveltimetaken['taxcollector']=0;
		$leveltimetaken['ulbtaxcollector']=0;
		$leveltimetaken['dealingassistant']=0;
		$leveltimetaken['executiveofficer']=0;
		$leveltimetaken['propertysectionincharge']=0;
		$totaldays=0;
		$pendingdays=0;
		$firstkey='';
		$lstind=count($leveldtl)-1;
		//back case
		$lastlevel=$leveldtl[$lstind];
		//
		$setid=7;
		//
		foreach ($leveldtl as $k=>$dtl)
		{
//			if($dtl['receiver_user_type_id']!=$setid && $dtl['sender_user_type_id']!=$setid)
//			{
//				continue;
//			}
			$key=strtolower(str_replace(' ','',$dtl['user_type']));
			//initial condition
			$leveltimetaken["$key"."progressl"]=0;
			$leveltimetaken["$key"."pendingg"]=0;
			$leveltimetaken["$key"."donel"]=0;
			$leveltimetaken["$key"."doneg"]=0;

			if($lastlevel['receiver_user_type_id']==11){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				if($user_typetimeline['backoffice']>=$tdiffdays){
					$leveltimetaken["backofficeprogressl"]=1;
//					$leveltimetaken["backofficeprogressl_list"][]=$dtl['saf_dtl_id'];

				//--	$leveltimetaken["backofficeprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
					//if(empty($dtl['emp_name']))
					//{
		//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$ward_no??0);
		//--			$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
		//--
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['ward_no']=$ward_no??0;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//					$leveltimetaken["backofficependingg"]=1;
	//--
				}
				if($dtl['saf_dtl_id']=='310699'){
//					dd($diffdays,$created,$paymentDate,$dtl['date_difference'],$leveltimetaken);
				}
//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']==11 && $lastlevel['receiver_user_type_id']==6){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
///
				if($user_typetimeline['dealingassistant']>=$tdiffdays){
					$leveltimetaken["dealingassistantprogressl"]=1;
//					$leveltimetaken["dealingassistantprogressl_list"][]=$dtl['saf_dtl_id'];
		//--			$leveltimetaken["dealingassistantprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
		//--
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
		//--
					//if(empty($dtl['emp_name']))
					//{
			//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
			//--		$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
			//--		$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;

					//$leveltimetaken["dealingassistantpendingg_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken["dealingassistantpendingg"]=1;
				}
//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']!=11 && $lastlevel['sender_user_type_id']>$lastlevel['receiver_user_type_id']) {
				if($dtl['sender_user_type_id']==$lastlevel['receiver_user_type_id'])
				{
					$toDate = new DateTime();
					$forward_date = new DateTime($lastlevel['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
					if($user_typetimeline[$key]>=$tdiffdays){
						$leveltimetaken[$key."progressl"]=1;
		//--				$leveltimetaken["$key"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
//						$leveltimetaken["$key"."progressl_list"][]=$dtl['saf_dtl_id'];
					}else{
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
					//--	$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
					//--	$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}

				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];

						$leveltimetaken[$key."pendingg"]=1;
						//dd($leveltimetaken);
					}
					continue;
				}
			}


			if($dtl['status']==0){ //verification_status
				if($dtl['date_difference']==NULL && $dtl['sender_user_type_id']==11){
					$created = new DateTime($dtl['forward_date']);
					$diffdays = $paymentDate->diff($created)->format("%r%a");
					$dtl['date_difference']=$diffdays;
				}
			}

			if(array_key_exists($key,$leveltimetaken))
			{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] += $dtl['date_difference'];
			}else{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] = $dtl['date_difference'];
			}

			if($k==0)
			{
				$firstkey=$key;
				$created = new DateTime($dtl['created_on']);
				$paymentDate = $paymentDate; //new DateTime($paymentDate);
				$diffdays = $paymentDate->diff($created)->format("%r%a");
				$totaldays += $diffdays;
				$leveltimetaken[$firstkey] += $diffdays;
//
				if($dtl['status']==1){ //verification_status
					$senderuserkey=strtolower(str_replace(' ','',$dtl["user_type"]));

					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$dtl[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					$toDate = new DateTime();
					$forward_date = new DateTime($dtl['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
						$leveltimetaken[$lstuserkey."progressl"]=1;

//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$dtl['saf_dtl_id'];
				//--		$leveltimetaken["$lstuserkey"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
					}else{
				//--		$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
				//--		$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
					//--	$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
					//--	$emp_name=$employeedetails['emp_name'];
						//}else{
					//--	$emp_name=$dtl['emp_name'];
						//}
		//---
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
//						$leveltimetaken[$lstuserkey."pendingg"]=1;
	//--
					}
				}else{
					if($user_typetimeline[$key]>=$diffdays){
//						if($dtl['saf_dtl_id']=='310411'){
//							dd($user_typetimeline[$key],$paymentDate,$created,$diffdays,$user_typetimeline[$key]>=$diffdays);
//						}
			//			$leveltimetaken["$key"."donel_list"][]=$dtl['saf_dtl_id'];
				//		$leveltimetaken[$key."donel"]=1;
					}else{
				//		$leveltimetaken["$key"."doneg_list"][]=$dtl['saf_dtl_id'];
			//			$leveltimetaken[$key."doneg"]=1;
					}
				}
			}

			if($k==$lstind && $k!=0){
//				dd($leveldtl[$lstind],$dtl);
				//$lstsql="select * from tbl_bugfix_level_pending_dtl where id='".$dtl['id']."'";
				//$lstexe=$this->db->query($lstsql);
				$lstresult=$dtl;//$lstexe->getFirstRow('array');
				$forward_date = new DateTime($lstresult['forward_date']);
				$toDate = new DateTime();
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				$pendingdays += $tdiffdays;
				if($lstresult['receiver_user_type_id']!=$lstresult['sender_user_type_id']){

					$senderuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[sender_user_type_id]");
					$senderuser=$senderuser->getResultArray();
					$senderuserkey=strtolower(str_replace(' ','',$senderuser[0]["user_type"]));
					$pre_date = new DateTime($lstresult['prev_date']);
					$sender_tdiffdays = $pre_date->diff($forward_date)->format("%r%a");
					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					//print_r($lstuserkey);
					$leveltimetaken["$lstuserkey"] += $pendingdays;
					$totaldays += $pendingdays;

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$lstresult['saf_dtl_id'];
			//--			$leveltimetaken["$lstuserkey"."progressl_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."progressl"]=1;
					}else{
			//--			$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_no']=$safno;
			//--			$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
				//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
			//--			$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}
//--
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];

						//$leveltimetaken["$lstuserkey"."pendingg_list"][]=$lstresult['saf_dtl_id'];
//--
						$leveltimetaken["$lstuserkey"."pendingg"]=1;
					}
				}
				//$leveltimetaken[$firstkey] += $diffdays;
			}
		}
//		$lgetmemo=$this->db->query("SELECT created_on FROM tbl_saf_memo_dtl WHERE saf_dtl_id = $dtl[saf_dtl_id] AND memo_type = 'FAM'");
//		$lgetmemono=$lgetmemo->getFirstRow('array');
//
//		$leveltimetaken['approve_date']=isset($lgetmemono['created_on'])?date('Y-m-d',strtotime($lgetmemono['created_on'])):"NA";
	//	$leveltimetaken['total']=$totaldays;
		//$leveltimetaken["totalcount"]=$lgetmemono['created_on']??"";
		return $leveltimetaken;
	}

	public function reportleveltimetaken2(){
		$data = (array)null;
		$records = (array)null;
		$Session = Session();
		$ulb_dtl = $Session->get('ulb_dtl');
		$ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
		$data['totalsaf']=0;

		$request = arrFilterSanitizeString($this->request->getVar());
		list($fromYear,$uptoYear)= explode("-",getFY());
		$from_date="2024-02-07";
		$from_date = $fromYear."-04-01";
		$to_date=date('Y-m-d',strtotime('-1 day'));
		$whereDateRange="";
		if(isset($request['from_date']))
		{
			$from_date=$request['from_date'];
		}
		if(isset($request['to_date']))
		{
			// $to_date=date('Y-m-d',strtotime($request['to_date'].'-1 day')) ;
			$to_date=date('Y-m-d',strtotime($request['to_date'])) ;
		}
		if ($from_date != "" && $to_date != "") {
			$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
		}
		$totalsaf="select * from tbl_saf_dtl where tbl_saf_dtl.status = '1' AND tbl_saf_dtl.payment_status= '1' $whereDateRange";
		$totalsaf=$this->db->query($totalsaf)->getResultArray();
		if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
			ini_set('memory_limit', '-1');
			try {
				$orderBY = " order by tbl_saf_dtl.id asc";//" ORDER BY " . $columnName . " " . $columnSortOrder;
				$from_date = $this->request->getVar("from_date");
				$to_date = $this->request->getVar("to_date");
				$limit = "";//" LIMIT 20";
				if ($from_date != "" && $to_date != "") {
					$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
				}
				$whereQuery=$whereDateRange;
				$selectStatement="SELECT 
							ROW_NUMBER () OVER (ORDER BY " . "tbl_saf_dtl.id" . " DESC) AS s_no,
							tbl_saf_dtl.id,holding_no,saf_no,tbl_saf_dtl.ward_mstr_id,apply_date,assessment_type,holding_type,
							(
								select txn.tran_date 
								from tbl_transaction txn 
								where txn.prop_dtl_id=tbl_saf_dtl.id AND txn.status= '1' 
									AND txn.tran_type='Saf' limit 1
							)as payment_date,
							'' as approve_date,
							0 as backoffice,0 as backofficedonel,0 as backofficedoneg,
							0 as dealingassistant,0 as dealingassistantdonel,0 as dealingassistantdoneg,
							0 as taxcollector,0 as taxcollectordonel,0 as taxcollectordoneg,
							0 as ulbtaxcollector,0 as ulbtaxcollectordonel,0 as ulbtaxcollectordoneg,
							0 as propertysectionincharge,0 as propertysectioninchargedonel,0 as propertysectioninchargedoneg,
							0 as executiveofficer,0 as executiveofficerdonel,0 as executiveofficerdoneg,
							0 as total,
							0 as totalcount
							";
				$sql = " FROM tbl_saf_dtl
						-- RIGHT join tbl_transaction txn on txn.prop_dtl_id=tbl_saf_dtl.id
						WHERE 
							tbl_saf_dtl.status= '1' AND
							tbl_saf_dtl.payment_status= '1'
							AND
							tbl_saf_dtl.saf_pending_status!='2'
						" . $whereQuery;
				$fetchSql = $selectStatement . $sql . $orderBY.$limit;
				$records = $this->db->query($fetchSql);
				$records = $records->getResultArray();
				$data['totalsaf']=count($records);
				

				$levelsql="with level as (
					SELECT 
							row_number() OVER (
								PARTITION BY tbl_level_pending_dtl.saf_dtl_id
							ORDER BY 
								tbl_level_pending_dtl.forward_date DESC, 
								tbl_level_pending_dtl.created_on DESC
							) AS serial,
							case when tbl_level_pending_dtl.receiver_user_type_id = 11 then 2 
								when tbl_level_pending_dtl.receiver_user_type_id = 6 then 5
								when tbl_level_pending_dtl.receiver_user_type_id = 5 then 8
								when tbl_level_pending_dtl.receiver_user_type_id = 7 then 5
								when tbl_level_pending_dtl.receiver_user_type_id = 9 then 3
								when tbl_level_pending_dtl.receiver_user_type_id = 10 then 2
								end as holdes_days,
							case when tbl_level_pending_dtl.receiver_user_type_id = 11 then 1 
								when tbl_level_pending_dtl.receiver_user_type_id = 6 then 2
								when tbl_level_pending_dtl.receiver_user_type_id = 5 then 3
								when tbl_level_pending_dtl.receiver_user_type_id = 7 then 4
								when tbl_level_pending_dtl.receiver_user_type_id = 9 then 5
								when tbl_level_pending_dtl.receiver_user_type_id = 10 then 6
								end as role_sl_no,
							current_date - tbl_level_pending_dtl.forward_date::date as day_diff,
							
							tbl_level_pending_dtl.id,
							tbl_level_pending_dtl.saf_dtl_id,
							tbl_level_pending_dtl.sender_user_type_id,
							tbl_level_pending_dtl.receiver_user_type_id,
							tbl_level_pending_dtl.forward_date,
							tbl_level_pending_dtl.forward_time,
							lag(tbl_level_pending_dtl.forward_date) OVER (ORDER BY tbl_level_pending_dtl.forward_date) AS prev_date,
							tbl_level_pending_dtl.forward_date - lag(tbl_level_pending_dtl.forward_date) OVER (ORDER BY tbl_level_pending_dtl.forward_date) AS date_difference,
							tbl_level_pending_dtl.created_on,
							tbl_level_pending_dtl.status,
							tbl_level_pending_dtl.verification_status,
							tbl_level_pending_dtl.sender_emp_details_id,
							tbl_level_pending_dtl.receiver_emp_details_id,
							view_user_type_mstr.user_type
						FROM ( SELECT tbl_bugfix_level_pending_dtl.id,
									tbl_bugfix_level_pending_dtl.saf_dtl_id,
									tbl_bugfix_level_pending_dtl.sender_user_type_id,
									tbl_bugfix_level_pending_dtl.receiver_user_type_id,
									tbl_bugfix_level_pending_dtl.forward_date,
									tbl_bugfix_level_pending_dtl.forward_time,
									tbl_bugfix_level_pending_dtl.created_on,
									tbl_bugfix_level_pending_dtl.status,
									tbl_bugfix_level_pending_dtl.verification_status,
									tbl_bugfix_level_pending_dtl.sender_emp_details_id,
									tbl_bugfix_level_pending_dtl.receiver_emp_details_id
								FROM tbl_bugfix_level_pending_dtl
								) tbl_level_pending_dtl
							JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
							JOIN tbl_saf_dtl ON tbl_saf_dtl.id = tbl_level_pending_dtl.saf_dtl_id
							LEFT JOIN view_emp_details ON view_emp_details.id = tbl_level_pending_dtl.sender_emp_details_id
						WHERE tbl_saf_dtl.status = 1 AND tbl_saf_dtl.payment_status = 1
							AND (tbl_saf_dtl.saf_pending_status <> ALL (ARRAY[1, 2])) 
							$whereDateRange
						ORDER BY tbl_saf_dtl.id
							),
							saflevel as (
				SELECT tbl_saf_dtl.id,
						tbl_saf_dtl.holding_no,
						tbl_saf_dtl.saf_no,
						tbl_saf_dtl.ward_mstr_id,
						tbl_saf_dtl.apply_date,
						tbl_saf_dtl.assessment_type,
						tbl_saf_dtl.holding_type,
						( SELECT txn.tran_date
							FROM tbl_transaction txn
							WHERE txn.prop_dtl_id = tbl_saf_dtl.id AND txn.status = 1 AND txn.tran_type::text = 'Saf'::text
							LIMIT 1
						) AS payment_date,
						( SELECT current_date-txn.tran_date
							FROM tbl_transaction txn
							WHERE txn.prop_dtl_id = tbl_saf_dtl.id AND txn.status = 1 AND txn.tran_type::text = 'Saf'::text
							LIMIT 1
						) AS bo_days,
					level.day_diff,
					level.holdes_days,level.receiver_user_type_id,
						(case When level.receiver_user_type_id =11 and level.holdes_days >= level.day_diff then 'Proccess'
							When level.receiver_user_type_id =11 and level.holdes_days < level.day_diff then 'Pending'
							When level.receiver_user_type_id !=11 and level.role_sl_no >=1 then 'Done' 
							else null end 
						) as bo,
						(case When level.receiver_user_type_id =6 and level.holdes_days >= level.day_diff then 'Proccess'
							When level.receiver_user_type_id =6 and level.holdes_days < level.day_diff then 'Pending'
							When level.receiver_user_type_id !=6 and level.role_sl_no >=2 then 'Done' 
							else null end 
						) as da,
						(case When level.receiver_user_type_id =5 and level.holdes_days >= level.day_diff then 'Proccess'
							When level.receiver_user_type_id =5 and level.holdes_days < level.day_diff then 'Pending'
							When level.receiver_user_type_id !=5 and level.role_sl_no >=3 then 'Done' 
							else null end 
						) as tc,
						(case When level.receiver_user_type_id =7 and level.holdes_days >= level.day_diff then 'Proccess'
							When level.receiver_user_type_id =7 and level.holdes_days < level.day_diff then 'Pending'
							When level.receiver_user_type_id !=7 and level.role_sl_no >=4 then 'Done' 
							else null end 
						) as utc,
						(case When level.receiver_user_type_id =9 and level.holdes_days >= level.day_diff then 'Proccess'
						When level.receiver_user_type_id =9 and level.holdes_days < level.day_diff then 'Pending'
						When level.receiver_user_type_id !=9 and level.role_sl_no >=5 then 'Done' 
						else null end 
					) as si,
					(case When level.receiver_user_type_id =10 and level.holdes_days >= level.day_diff then 'Proccess'
						When level.receiver_user_type_id =10 and level.holdes_days < level.day_diff then 'Pending'
						When level.receiver_user_type_id !=10 and level.role_sl_no >=6 then 'Done' 
						else null end 
					) as eo,
						0 AS total,
						0 AS totalcount
					FROM tbl_saf_dtl
					left join level on level.saf_dtl_id = tbl_saf_dtl.id and  level.serial =1
					WHERE tbl_saf_dtl.status = 1 AND
					tbl_saf_dtl.payment_status = 1 AND (tbl_saf_dtl.saf_pending_status <> ALL (ARRAY[1, 2])) $whereDateRange
					order by level.day_diff ASC )
					select 
					(select count((case When holdes_days is null and 2 < saflevel.bo_days then 'Pending' 
							else null end 
						))) as bopending,
					(select count((case When holdes_days is null and 2 >= saflevel.bo_days then 'Proccess'
							else null end 
						))) as boprocess,
					(select count(*) from saflevel where bo='Pending') as backoffice_pending,
					(select count(*) from saflevel where bo='Proccess') as backoffice_process,
					(select count(*) from saflevel where da='Pending') as da_pending,
					(select count(*) from saflevel where da='Proccess') as da_process,
					(select count(*) from saflevel where tc='Pending') as tc_pending,
					(select count(*) from saflevel where tc='Proccess') as tc_process,

					(select count(*) from saflevel where utc='Pending') as utc_pending,
					(select count(*) from saflevel where utc='Proccess') as utc_process,
					(select count(*) from saflevel where si='Pending') as sitc_pending,
					(select count(*) from saflevel where si='Proccess') as sitc_process,
					(select count(*) from saflevel where eo='Pending') as eotc_pending,
					(select count(*) from saflevel where eo='Proccess') as eotc_process
					from saflevel;
					";
					$records = $this->db->query($levelsql);
					$records = $records->getResultArray();
					$records = $records[0];
					$remainsaf=$data['totalsaf'];
					//$remainsaf=0;
					//bo
					$bopending=$records['bopending']+$records['backoffice_pending'];
					$data['bopending']=$bopending;
					$data['boprocess']=$records['backoffice_process']+$records['boprocess'];
					$remainsaf=$data['bodone']=$remainsaf-($bopending+$data['boprocess']);
					//da
					$data['da_pending']=$records['da_pending'];
					$data['da_process']=$records['da_process'];
					$remainsaf=$data['dadone']=$remainsaf-($data['da_pending']+$data['da_process']);
					//tca
					$data['tc_pending']=$records['tc_pending'];
					$data['tc_process']=$records['tc_process'];
					$remainsaf=$data['tcdone']=$remainsaf-($data['tc_pending']+$data['tc_process']);
					//ulbtc
					$data['utc_pending']=$records['utc_pending'];
					$data['utc_process']=$records['utc_process'];
					$remainsaf=$data['utcdone']=$remainsaf-($data['utc_pending']+$data['utc_process']);
					//si
					$data['sitc_pending']=$records['sitc_pending'];
					$data['sitc_process']=$records['sitc_process'];
					$remainsaf=$data['sidone']=$remainsaf-($data['sitc_pending']+$data['sitc_process']);
					//eo
					$data['eotc_pending']=$records['eotc_pending'];
					$data['eotc_process']=$records['eotc_process'];
					$remainsaf=$data['eodone']=$remainsaf-($data['eotc_pending']+$data['eotc_process']);
					
			}catch (Exception $e)
			{
				$records=[];
			}
		}
		$data['from_date']=$from_date;
		$data['to_date']=date('Y-m-d',strtotime($to_date.'+1 day'));
		$data['to_date']=$to_date;//date('Y-m-d',strtotime($to_date.'+1 day'));
		return view('report/reportleveltimetakenv4',$data);
	}
	public function exportreportleveltimetaken2(){
		$data = (array)null;
		$records = (array)null;
		$Session = Session();
		$ulb_dtl = $Session->get('ulb_dtl');
		$ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
		$data['totalsaf']=0;

		$request = arrFilterSanitizeString($this->request->getVar());
		$from_date="2024-02-07";
		$to_date=date('Y-m-d',strtotime('-1 day'));
		$whereDateRange="";
		if(isset($request['from_date']))
		{
			$from_date=$request['from_date'];
		}
		if(isset($request['to_date']))
		{
//			$to_date=date('Y-m-d',strtotime($request['to_date'].'-1 day')) ;
			$to_date=date('Y-m-d',strtotime($request['to_date'])) ;
		}
		if ($from_date != "" && $to_date != "") {
			$whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
		}
			if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
				ini_set('memory_limit', '-1');
				try {
					$levelsql="with level as (
						SELECT 
								 row_number() OVER (
									PARTITION BY tbl_level_pending_dtl.saf_dtl_id
								  ORDER BY 
									tbl_level_pending_dtl.forward_date DESC, 
									tbl_level_pending_dtl.created_on DESC
								) AS serial,
								 case when tbl_level_pending_dtl.receiver_user_type_id = 11 then 2 
									 when tbl_level_pending_dtl.receiver_user_type_id = 6 then 5
									 when tbl_level_pending_dtl.receiver_user_type_id = 5 then 8
									 when tbl_level_pending_dtl.receiver_user_type_id = 7 then 5
									when tbl_level_pending_dtl.receiver_user_type_id = 9 then 3
									 when tbl_level_pending_dtl.receiver_user_type_id = 10 then 2
									 end as holdes_days,
								case when tbl_level_pending_dtl.receiver_user_type_id = 11 then 1 
									 when tbl_level_pending_dtl.receiver_user_type_id = 6 then 2
									 when tbl_level_pending_dtl.receiver_user_type_id = 5 then 3
									 when tbl_level_pending_dtl.receiver_user_type_id = 7 then 4
									when tbl_level_pending_dtl.receiver_user_type_id = 9 then 5
									 when tbl_level_pending_dtl.receiver_user_type_id = 10 then 6
									 end as role_sl_no,
								current_date - tbl_level_pending_dtl.forward_date::date as day_diff,
								
								tbl_level_pending_dtl.id,
								tbl_level_pending_dtl.saf_dtl_id,
								tbl_level_pending_dtl.sender_user_type_id,
								tbl_level_pending_dtl.receiver_user_type_id,
								tbl_level_pending_dtl.forward_date,
								tbl_level_pending_dtl.forward_time,
								lag(tbl_level_pending_dtl.forward_date) OVER (ORDER BY tbl_level_pending_dtl.forward_date) AS prev_date,
								tbl_level_pending_dtl.forward_date - lag(tbl_level_pending_dtl.forward_date) OVER (ORDER BY tbl_level_pending_dtl.forward_date) AS date_difference,
								tbl_level_pending_dtl.created_on,
								tbl_level_pending_dtl.status,
								tbl_level_pending_dtl.verification_status,
								tbl_level_pending_dtl.sender_emp_details_id,
								tbl_level_pending_dtl.receiver_emp_details_id,
								view_user_type_mstr.user_type
							   FROM ( SELECT tbl_bugfix_level_pending_dtl.id,
										tbl_bugfix_level_pending_dtl.saf_dtl_id,
										tbl_bugfix_level_pending_dtl.sender_user_type_id,
										tbl_bugfix_level_pending_dtl.receiver_user_type_id,
										tbl_bugfix_level_pending_dtl.forward_date,
										tbl_bugfix_level_pending_dtl.forward_time,
										tbl_bugfix_level_pending_dtl.created_on,
										tbl_bugfix_level_pending_dtl.status,
										tbl_bugfix_level_pending_dtl.verification_status,
										tbl_bugfix_level_pending_dtl.sender_emp_details_id,
										tbl_bugfix_level_pending_dtl.receiver_emp_details_id
									   FROM tbl_bugfix_level_pending_dtl
									) tbl_level_pending_dtl
								 JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
								 JOIN tbl_saf_dtl ON tbl_saf_dtl.id = tbl_level_pending_dtl.saf_dtl_id
								 LEFT JOIN view_emp_details ON view_emp_details.id = tbl_level_pending_dtl.sender_emp_details_id
							  WHERE tbl_saf_dtl.status = 1 AND tbl_saf_dtl.payment_status = 1
								 AND (tbl_saf_dtl.saf_pending_status <> ALL (ARRAY[1, 2])) 
								 $whereDateRange
							ORDER BY tbl_saf_dtl.id
								 ),
								 saflevel as (
					SELECT tbl_saf_dtl.id,
							tbl_saf_dtl.holding_no,
							tbl_saf_dtl.saf_no,
							tbl_saf_dtl.ward_mstr_id,
							view_ward_mstr.ward_no,
							tbl_saf_dtl.apply_date,
							tbl_saf_dtl.assessment_type,
							tbl_saf_dtl.holding_type,
							( SELECT txn.tran_date
								   FROM tbl_transaction txn
								  WHERE txn.prop_dtl_id = tbl_saf_dtl.id AND txn.status = 1 AND txn.tran_type::text = 'Saf'::text
								 LIMIT 1
							) AS payment_date,
							( SELECT current_date-txn.tran_date
								   FROM tbl_transaction txn
								  WHERE txn.prop_dtl_id = tbl_saf_dtl.id AND txn.status = 1 AND txn.tran_type::text = 'Saf'::text
								 LIMIT 1
							) AS bo_days,
						level.day_diff,
						level.holdes_days,level.receiver_user_type_id,
							(case When level.receiver_user_type_id =11 and level.holdes_days >= level.day_diff then 'Proccess'
								 When level.receiver_user_type_id =11 and level.holdes_days < level.day_diff then 'Pending'
								 When level.receiver_user_type_id !=11 and level.role_sl_no >=1 then 'Done' 
								 else null end 
							 ) as bo,
							 (case When level.receiver_user_type_id =6 and level.holdes_days >= level.day_diff then 'Proccess'
								 When level.receiver_user_type_id =6 and level.holdes_days < level.day_diff then 'Pending'
								 When level.receiver_user_type_id !=6 and level.role_sl_no >=2 then 'Done' 
								 else null end 
							 ) as da,
							 (case When level.receiver_user_type_id =5 and level.holdes_days >= level.day_diff then 'Proccess'
								 When level.receiver_user_type_id =5 and level.holdes_days < level.day_diff then 'Pending'
								 When level.receiver_user_type_id !=5 and level.role_sl_no >=3 then 'Done' 
								 else null end 
							 ) as tc,
							 (case When level.receiver_user_type_id =7 and level.holdes_days >= level.day_diff then 'Proccess'
								 When level.receiver_user_type_id =7 and level.holdes_days < level.day_diff then 'Pending'
								 When level.receiver_user_type_id !=7 and level.role_sl_no >=4 then 'Done' 
								 else null end 
							 ) as utc,
							 (case When level.receiver_user_type_id =9 and level.holdes_days >= level.day_diff then 'Proccess'
							 When level.receiver_user_type_id =9 and level.holdes_days < level.day_diff then 'Pending'
							 When level.receiver_user_type_id !=9 and level.role_sl_no >=5 then 'Done' 
							 else null end 
						 ) as si,
						 (case When level.receiver_user_type_id =10 and level.holdes_days >= level.day_diff then 'Proccess'
							 When level.receiver_user_type_id =10 and level.holdes_days < level.day_diff then 'Pending'
							 When level.receiver_user_type_id !=10 and level.role_sl_no >=6 then 'Done' 
							 else null end 
						 ) as eo,
							0 AS total,
							0 AS totalcount
						   FROM tbl_saf_dtl
						   join view_ward_mstr on view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
						   left join level on level.saf_dtl_id = tbl_saf_dtl.id and  level.serial =1
						  WHERE tbl_saf_dtl.status = 1 AND
						   tbl_saf_dtl.payment_status = 1 AND (tbl_saf_dtl.saf_pending_status <> ALL (ARRAY[1, 2])) $whereDateRange
						order by level.day_diff ASC )
						";
					if($request['filter_type']=="Back Office")
					{
						$sql=$levelsql." select * from saflevel where saflevel.bo='Pending'";
					}	
					if($request['filter_type']=="Dealing Assistant")
					{
						$sql=$levelsql." select * from saflevel where saflevel.da='Pending'";
					}	
					if($request['filter_type']=="Tax Collector")
					{
						$sql=$levelsql." select * from saflevel where saflevel.tc='Pending'";
					}	
					if($request['filter_type']=="ULB Tax Collector")
					{
						$sql=$levelsql." select * from saflevel where saflevel.utc='Pending'";
					}		
					if($request['filter_type']=="Section Incharge")
					{
						$sql=$levelsql." select * from saflevel where saflevel.si='Pending'";
					}		
					if($request['filter_type']=="Executive Officer")
					{
						$sql=$levelsql." select * from saflevel where saflevel.eo='Pending'";
					}				
						$records = $this->db->query($sql);
						$records = $records->getResultArray();
					if($request['filter_type']=="Back Office")
					{
						$sql1=$levelsql." select * from saflevel where holdes_days is null and 2 < saflevel.bo_days";
						$records1 = $this->db->query($sql1);
						$records1 = $records1->getResultArray();
						$records=array_merge($records,$records1);
					}	
				}catch (Exception $e)
				{
					$records=[];
				}
			}
			// dd($records);
		$data['from_date']=$from_date;
		$data['to_date']=$to_date;//date('Y-m-d',strtotime($to_date.'+1 day'));
        $data['lists']=$records;
		return view('report/pendinglistsaf',$data);
	}

	public function calssaftimev3($leveldtl,$paymentDate,$safid,$safno,$ward_mstr_id,$assessment_type,$wardno,$apply_date){
		$paymentDate=new DateTime($paymentDate);
		$paymentDate->modify('+1 day');
		$user_type=['Back Office','Dealing Assistant','Tax Collector','ULB Tax Collector','Property Section Incharge','Executive Officer'];
		$user_typetimeline=['backoffice'=>2,'dealingassistant'=>5,'taxcollector'=>8,'ulbtaxcollector'=>5,'propertysectionincharge'=>3,'executiveofficer'=>2,
			'admin'=>2,'juniorengineer'=>2,'callcenterexecutive'=>2,'jsk'=>2];
		$leveltimetaken=[];
		$leveltimetaken['backoffice']=0;
		$leveltimetaken['taxcollector']=0;
		$leveltimetaken['ulbtaxcollector']=0;
		$leveltimetaken['dealingassistant']=0;
		$leveltimetaken['executiveofficer']=0;
		$leveltimetaken['propertysectionincharge']=0;
		$totaldays=0;
		$pendingdays=0;
		$firstkey='';
		$lstind=count($leveldtl)-1;
		//back case
		$lastlevel=$leveldtl[$lstind];
		//
		$setid=7;
		//
		foreach ($leveldtl as $k=>$dtl)
		{
//			if($dtl['receiver_user_type_id']!=$setid && $dtl['sender_user_type_id']!=$setid)
//			{
//				continue;
//			}
			$key=strtolower(str_replace(' ','',$dtl['user_type']));
			//initial condition
			$leveltimetaken["$key"."progressl"]=0;
			$leveltimetaken["$key"."pendingg"]=0;
			$leveltimetaken["$key"."donel"]=0;
			$leveltimetaken["$key"."doneg"]=0;

			if($lastlevel['receiver_user_type_id']==11){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				if($user_typetimeline['backoffice']>=$tdiffdays){
					$leveltimetaken["backofficeprogressl"]=1;
//					$leveltimetaken["backofficeprogressl_list"][]=$dtl['saf_dtl_id'];

				//--	$leveltimetaken["backofficeprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
			//--		$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
					//if(empty($dtl['emp_name']))
					//{
		//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$ward_no??0);
		//--			$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
		//--
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['ward_no']=$ward_no??0;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;;
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
//					$leveltimetaken["backofficependingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//					$leveltimetaken["backofficependingg"]=1;
	//--
				}
				if($dtl['saf_dtl_id']=='310699'){
//					dd($diffdays,$created,$paymentDate,$dtl['date_difference'],$leveltimetaken);
				}
//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']==11 && $lastlevel['receiver_user_type_id']==6){
				$toDate = new DateTime();
				$forward_date = new DateTime($lastlevel['forward_date']);
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
///
				if($user_typetimeline['dealingassistant']>=$tdiffdays){
					$leveltimetaken["dealingassistantprogressl"]=1;
//					$leveltimetaken["dealingassistantprogressl_list"][]=$dtl['saf_dtl_id'];
		//--			$leveltimetaken["dealingassistantprogressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
				}else{
		//--
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
//					$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
		//--
					//if(empty($dtl['emp_name']))
					//{
			//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
			//--		$emp_name=$employeedetails['emp_name'];
					//}else{
					//	$emp_name=$dtl['emp_name'];
					//}
			//--		$leveltimetaken["dealingassistantpendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;

					//$leveltimetaken["dealingassistantpendingg_list"][]=$dtl['saf_dtl_id'];
					$leveltimetaken["dealingassistantpendingg"]=1;
				}
//				dd($leveltimetaken);
				continue;
			}
			if($lastlevel['sender_user_type_id']!=11 && $lastlevel['sender_user_type_id']>$lastlevel['receiver_user_type_id']) {
				if($dtl['sender_user_type_id']==$lastlevel['receiver_user_type_id'])
				{
					$toDate = new DateTime();
					$forward_date = new DateTime($lastlevel['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
					if($user_typetimeline[$key]>=$tdiffdays){
						$leveltimetaken[$key."progressl"]=1;
		//--				$leveltimetaken["$key"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
//						$leveltimetaken["$key"."progressl_list"][]=$dtl['saf_dtl_id'];
					}else{
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
					//--	$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
					//--	$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}

				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
				//--		$leveltimetaken["$key"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];

						$leveltimetaken[$key."pendingg"]=1;
						//dd($leveltimetaken);
					}
					continue;
				}
			}


			if($dtl['status']==0){ //verification_status
				if($dtl['date_difference']==NULL && $dtl['sender_user_type_id']==11){
					$created = new DateTime($dtl['forward_date']);
					$diffdays = $paymentDate->diff($created)->format("%r%a");
					$dtl['date_difference']=$diffdays;
				}
			}

			if(array_key_exists($key,$leveltimetaken))
			{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] += $dtl['date_difference'];
			}else{
				$totaldays += $dtl['date_difference'];
				$leveltimetaken[$key] = $dtl['date_difference'];
			}

			if($k==0)
			{
				$firstkey=$key;
				$created = new DateTime($dtl['created_on']);
				$paymentDate = $paymentDate; //new DateTime($paymentDate);
				$diffdays = $paymentDate->diff($created)->format("%r%a");
				$totaldays += $diffdays;
				$leveltimetaken[$firstkey] += $diffdays;
//
				if($dtl['status']==1){ //verification_status
					$senderuserkey=strtolower(str_replace(' ','',$dtl["user_type"]));

					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$dtl[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					$toDate = new DateTime();
					$forward_date = new DateTime($dtl['forward_date']);
					$tdiffdays = $forward_date->diff($toDate)->format("%r%a");

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
						$leveltimetaken[$lstuserkey."progressl"]=1;

//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$dtl['saf_dtl_id'];
				//--		$leveltimetaken["$lstuserkey"."progressl_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
					}else{
				//--		$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_no']=$safno;
				//--		$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
					//--	$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
					//--	$emp_name=$employeedetails['emp_name'];
						//}else{
					//--	$emp_name=$dtl['emp_name'];
						//}
		//---
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];
//						$leveltimetaken[$lstuserkey."pendingg"]=1;
	//--
					}
				}else{
					if($user_typetimeline[$key]>=$diffdays){
//						if($dtl['saf_dtl_id']=='310411'){
//							dd($user_typetimeline[$key],$paymentDate,$created,$diffdays,$user_typetimeline[$key]>=$diffdays);
//						}
			//			$leveltimetaken["$key"."donel_list"][]=$dtl['saf_dtl_id'];
				//		$leveltimetaken[$key."donel"]=1;
					}else{
				//		$leveltimetaken["$key"."doneg_list"][]=$dtl['saf_dtl_id'];
			//			$leveltimetaken[$key."doneg"]=1;
					}
				}
			}

			if($k==$lstind && $k!=0){
//				dd($leveldtl[$lstind],$dtl);
				//$lstsql="select * from tbl_bugfix_level_pending_dtl where id='".$dtl['id']."'";
				//$lstexe=$this->db->query($lstsql);
				$lstresult=$dtl;//$lstexe->getFirstRow('array');
				$forward_date = new DateTime($lstresult['forward_date']);
				$toDate = new DateTime();
				$tdiffdays = $forward_date->diff($toDate)->format("%r%a");
				$pendingdays += $tdiffdays;
				if($lstresult['receiver_user_type_id']!=$lstresult['sender_user_type_id']){

					$senderuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[sender_user_type_id]");
					$senderuser=$senderuser->getResultArray();
					$senderuserkey=strtolower(str_replace(' ','',$senderuser[0]["user_type"]));
					$pre_date = new DateTime($lstresult['prev_date']);
					$sender_tdiffdays = $pre_date->diff($forward_date)->format("%r%a");
					$lstuser=$this->dbSystem->query("select user_type from tbl_user_type_mstr where id=$lstresult[receiver_user_type_id]");
					$lstuser=$lstuser->getResultArray();
					$lstuserkey=strtolower(str_replace(' ','',$lstuser[0]["user_type"]));
					//print_r($lstuserkey);
					$leveltimetaken["$lstuserkey"] += $pendingdays;
					$totaldays += $pendingdays;

					if($user_typetimeline[$lstuserkey]>=$tdiffdays){
//						$leveltimetaken["$lstuserkey"."progressl_list"][]=$lstresult['saf_dtl_id'];
			//--			$leveltimetaken["$lstuserkey"."progressl_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						$leveltimetaken["$lstuserkey"."progressl"]=1;
					}else{
			//--			$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_no']=$safno;
			//--			$leveltimetaken["$lstuserkey"."pendingg_list"][$lstresult['saf_dtl_id']]['saf_dtl_id']=$dtl['saf_dtl_id'];
						//if(empty($dtl['emp_name']))
						//{
				//--		$employeedetails=$this->model_level_pending_dtl->employeedetails($ward_mstr_id,$dtl['receiver_user_type_id'],$wardno);
			//--			$emp_name=$employeedetails['emp_name'];
						//}else{
						//	$emp_name=$dtl['emp_name'];
						//}
//--
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_emp_name']=$emp_name;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['receiver_user_type_id']=$dtl['receiver_user_type_id'];
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['ward_no']=$wardno;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['assessment_type']=$assessment_type;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['apply_date']=$apply_date;
//						$leveltimetaken["$lstuserkey"."pendingg_list"][$dtl['saf_dtl_id']]['forward_date']=$dtl['forward_date'];

						//$leveltimetaken["$lstuserkey"."pendingg_list"][]=$lstresult['saf_dtl_id'];
//--
						$leveltimetaken["$lstuserkey"."pendingg"]=1;
					}
				}
				//$leveltimetaken[$firstkey] += $diffdays;
			}
		}
//		$lgetmemo=$this->db->query("SELECT created_on FROM tbl_saf_memo_dtl WHERE saf_dtl_id = $dtl[saf_dtl_id] AND memo_type = 'FAM'");
//		$lgetmemono=$lgetmemo->getFirstRow('array');
//
//		$leveltimetaken['approve_date']=isset($lgetmemono['created_on'])?date('Y-m-d',strtotime($lgetmemono['created_on'])):"NA";
	//	$leveltimetaken['total']=$totaldays;
		//$leveltimetaken["totalcount"]=$lgetmemono['created_on']??"";
		return $leveltimetaken;
	}



	
	
}
?>

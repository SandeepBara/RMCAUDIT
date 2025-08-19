<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\ModelTransactionDeactivate;


class TradeCollectionReport extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_transaction;
	protected $model_emp_details;
	//protected $db_name;
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");	
		
		$this->trans_dtls=new ModelTransactionDeactivate($this->db);
    }
	public function transaction_deactivate()
	{	
		$data=array();
		$curr_date=date('Y-m-d');
		$session=session();
		$emp_details=$session->get('emp_details');
		$session_user_id=$emp_details['id'];
		if($this->request->getMethod()=='post')
		{

			$inputs = arrFilterSanitizeString($this->request->getVar());
			//echo $inputs['deactivate'];
			$data['trans_no']=$inputs['trans_no'];
			$transaction_details=$this->trans_dtls->transaction_details($data);
			//print_r($transaction_details);
			$data['transaction_details']=$transaction_details;
			$trans_id=$transaction_details['transaction_id'];
			//print_r($transaction_details);
			$data['trans_id']=$trans_id;
			
			if($trans_id!="" || $inputs['deactivate']=='Deactivate')
			{

				$from_fy=$this->trans_dtls->getFyYearById($transaction_details['from_fy_mstr_id']);
				$upto_fy=$this->trans_dtls->getFyYearById($transaction_details['upto_fy_mstr_id']);

				$prop_id=$transaction_details['prop_dtl_id'];
				$tran_type=$transaction_details['tran_type'];
				$data['tran_type']=$tran_type;

				if($tran_type=='Saf')
				{
					$table="tbl_saf_dtl";
					$table2="tbl_saf_owner_detail";
					$col="saf_dtl_id";

				}
				else
				{
					$table="tbl_prop_dtl";
					$table2="tbl_prop_owner_detail";
					$col="prop_dtl_id";
				}

				$prop_dtls=$this->trans_dtls->get_prop_details($prop_id,$table,$table2,$col);
				//print_r($prop_dtls);


				$data['from_fy']=$from_fy;
				$data['upto_fy']=$upto_fy;
				$chq_dtls=$this->trans_dtls->cheque_details($trans_id);
				$data['chq_dtls']=$chq_dtls;
				$data['prop_dtls']=$prop_dtls;
				//print_r($inputs);
				if($inputs['deactivate']=='Deactivate')
				{

					$trans_deactivate=array();
					$trans_deactivate['transaction_id']=$inputs['transaction_id'];
					$trans_deactivate['deactivated_by']=$session_user_id;
					$trans_deactivate['deactive_date']=date('Y-m-d');
					$trans_deactivate['created_on']=date('Y-m-d H:i:s');
					$trans_deactivate['reason']=$inputs['reason'];
				
					$data2=array();
					$data2['insert_id']=$this->trans_dtls->insert_trans_deactivate($trans_deactivate);

					$data2['transaction_id']=$inputs['transaction_id'];

					if($data2['insert_id'])
					{

						$rules = ['file' => 'uploaded[file]|max_size[file,1024]|ext_in[file,pdf]'];
		                if($this->validate($rules))
		                {
		                    $file = $this->request->getFile('file');
		                    $extension = $file->getExtension();
		                    
		                    if($file->isValid() && !$file->hasMoved())
		                    {
		                        $data2['newName'] = md5($insert_id).".".$extension;
		                       
		                        if($file->move(WRITEPATH.'uploads/RANCHI/transaction_deactivate',$data2['newName']))
		                        {
		                            $this->trans_dtls->updateFileName($data2);
		                        }
		                    }
		                }
		                
		                flashToast('transaction','Transaction Deactivated Successfully!!');

	            }

				}

			}

		}

		return view('property/transaction_deactivate',$data);

	}
	
	public function transaction_deactivation_report()
	{
		$data=array();

		if($this->request->getMethod()=='post')
		{
			$data['date_from']=$_POST['date_from'];
			$data['date_upto']=$_POST['date_upto'];
		}
		else
		{
			$data['date_from']=date('Y-m-d');
			$data['date_upto']=date('Y-m-d');
		}
		
		$data['report']=$this->trans_dtls->trans_deactive_report($data);
		

		
		return view('report/transaction_deactivation_report',$data);
	}
}

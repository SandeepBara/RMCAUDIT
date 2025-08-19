<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\denial_details_model;
use App\Models\model_ulb_mstr;
use App\Models\model_trade_level_pending_dtl;
use App\Models\TradeApplyDenialModel;
use App\Models\model_prop_dtl;
use App\Models\model_view_ward_permission;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_visiting_dtl;


class denial extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
	protected $denial_details_model;
	protected $model_ulb_mstr;
	protected $model_trade_level_pending_dtl;
	protected $TradeApplyDenialModel;
	protected $model_prop_dtl;
    protected $TradeApplyLicenceModel;
	protected $model_view_ward_permission;
	protected $model_visiting_dtl;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'form','qr_code_generator_helper','form_helper']);
        if($db_name = dbConfig("trade"))
		{
            $this->db = db_connect($db_name); 
        }
        if($db_system = dbSystem())
		{
            $this->dbSystem = db_connect($db_system); 
        }
		if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr=new model_ulb_mstr($this->dbSystem);
		$this->denial_details_model=new denial_details_model($this->db);
		$this->model_trade_level_pending_dtl=new model_trade_level_pending_dtl($this->db);
		$this->TradeApplyDenialModel=new TradeApplyDenialModel($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
		$this->TradeApplyLicenceModel= new TradeApplyLicenceModel($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);

    }

	public function index()
	{
		$data=array();
        helper(['form']);
		$session = session();
		$ulb_mstr = $session->get("ulb_dtl");
		$folder=$ulb_mstr['city'];
		$get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
		
		$emp_user_type_id =$get_emp_details['user_type_mstr_id'];
        $location=array();
		$data['ward_list']=$this->model_ward_mstr->getWardList($ulb_mstr);
		if($get_emp_details["user_type_mstr_id"]==5)        
        {
            $ward_list = $this->model_view_ward_permission->getPermittedWard($emp_id);
            $data['ward_list'] =  array_map(function($val){
                return["id"=>$val["ward_mstr_id"],"ward_no"=>$val['ward_no']];
            },$ward_list);
          
        } 
        if($this->request->getMethod()=="post")
         {  
			$this->db->transBegin();
                         $rules=[
                                'firm_Name'=>'required',
                                'address' =>'required', 
                                'new_ward_id'  =>'required',
                                'landmark' => 'required',                 
                                'city' =>'required',  
                                'remarks' =>'required',
                                'pin_code' =>'required|min_length[6]|max_length[6]',                     
 								'images'=>'uploaded[images]|max_size[images,30720]|ext_in[images,pdf,jpg,png]',
                             ];
					
                	if(!$this->validate($rules))
					{
                        $data['validation']=$this->validator;                        
						return view('mobile/trade/denialForm',$data);
                    }
                    else
                    {   
						$inputs = filterSanitizeStringtoUpper($this->request->getVar()); 
 						  
 						$data["firm_Name"]=$inputs['firm_Name'];
						$data["owner_name"]=$inputs['owner_name'];
						$data["new_ward_id"]=$inputs['new_ward_id'];                        
						$data["holding_no"]=$inputs['holding_no'];
						$data["address"]=$inputs['address'];
						$data["landmark"]=$inputs['landmark'];
  						$data["city"]=$inputs['city'];
 						$data["pin_code"]=$inputs['pin_code'];
						$data["licence_no"]=$inputs['licence_no'];
						$data["mobileno"]=$inputs['mobileno'];
						$data["ipaddress"]=$inputs['ipaddress'];
                        // $getloc = json_decode(file_get_contents("http://ipinfo.io/"));
					    // $coordinates = explode(",", $getloc->loc);
					    // $data['latitude'] = $coordinates[0]; // latitude
					    // $data['longitude'] = $coordinates[1]; // longitude
 						if($data["mobileno"]=="")
						{
							$data["mobileno"]=null;
						}
 						$data["remarks"]=$inputs['remarks'];
                        $data['emp_details_id']=$emp_id;                                    
                        $data['created_on']=date('Y-m-d H:i:s');
                        $denial_id = $this->TradeApplyDenialModel->insertdenialapply($data);
                        $doc_path = "";
                        if($denial_id)
                        {                             
                                $doc_path_image = $this->request->getFile('images');
								try 
								{
									
									$newFileName = md5($denial_id);
									$file_ext = $doc_path_image->getExtension();
									// $path_images = 'RANCHI'."/"."denial_image";
									$path_images = $folder."/"."denial_image";
									$doc_path_image->move(WRITEPATH.'uploads/'.$path_images.'/',$newFileName.'.'.$file_ext);
									$doc_path_save = $path_images."/".$newFileName.'.'.$file_ext;
									$this->TradeApplyDenialModel->updatedocpathById($denial_id, $doc_path_save);
									
								} 
								catch (Exception $e) 
								{ }
            
                            //send to RMC
                            $data['apply_denial_id']=$denial_id;
                            $leveldata = [
                            'denial_id' => $data['apply_denial_id'],
                            'sender_id' => $emp_id,
							'sender_user_type_id' => $emp_user_type_id,
                            'receiver_user_type_id' => 19,
							'remarks' => $data["remarks"],				
                            'created_on' =>date('Y-m-d H:i:s'),
                            ];
                            $level_pending_insrt=$this->model_trade_level_pending_dtl->sendToEO($leveldata);
                            //end
							$firstActiveEoSql = " select * from view_emp_details where lock_status=0 and user_type_mstr_id=19 order by id ASC limit 1 "; 
							$firstEo = $this->dbSystem->query($firstActiveEoSql)->getFirstRow("array");
							
							# Approve Application
							{ 		  
								$data = [
										'remarks' => $this->request->getVar('remarks'),
										 'mail_id' => md5($level_pending_insrt),//$mailID,
										 'denial_id' => md5($denial_id),//$id,
										 'forward_date' => date('Y-m-d'),
										 'forward_time' => date('H:i:s'),
										 'emp_details_id' => $emp_user_type_id,
										 'created_on' => date('Y-m-d H:i:s'),
										 'denial_ID'  => $denial_id,//$data['denial_details']['id'],
										 'status' => 5,
										 "approved_by"=>$firstEo["id"]??null,
									 ];
									if($updateMail = $this->model_trade_level_pending_dtl->updateMail($data))  //  update  mail table
									{
									   if($updateConsumer = $this->TradeApplyDenialModel->updateStatus($data)) // update status of consumer table
									   {   
										  $insertID =  $this->TradeApplyDenialModel->insertNoticeData($data);   //insert data into notice table  
										  $noticeNO = "NOT/".date('dmy').$denial_id.$insertID ;
										  $this->TradeApplyDenialModel->updateNoticeNo($insertID,$noticeNO);										
									   }                            
				
									}
							}
							$this->dbSystem->transBegin();
							if(isset($noticeNO) && $noticeNO)
							{     
								$application['notice_no'] = $noticeNO;  
								$application['id'] = $insertID;                 
								$vistingRepostInput = tradeNoticApplyVisit($application,$this->request->getVar()); 
								$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
								
							}
							if($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								$this->dbSystem->transRollback();
								flashToast("denialForm", "Something Went Wrong. Please Try Again!");
								return $this->response->redirect(base_url('denial/index/'));
							}
							else
							{
								$this->db->transCommit();
								$this->dbSystem->transCommit();
								flashToast("denialForm", "Denail Form Submitted Succesfully!");
								return $this->response->redirect(base_url('MobiTradeReport/viewDetails/'.md5($denial_id)));
								return $this->response->redirect(base_url('denial/index/'));
							}
                       } 
                    }
            }

		return view('mobile/trade/denialForm',$data);
	}


	public function CitizenDtlView($ID=null) {
        $data =(array)null;
		$data['id']=$ID;
		
		$data['denial_dtls'] = $this->denial_details_model->getdenial_details($data['id']);
		//print_r($data['denial_dtls']);
		return view('mobile/trade/denialView',$data);
	}

	public function validate_holding_no()
    {              
        if($this->request->getMethod()=="post")
        {
           $data=array();
           $inputs = arrFilterSanitizeString($this->request->getVar());  
          
           $propdet = $this->model_prop_dtl->getPropertyDetails($inputs);
		   if($propdet!=null)
		   {
			$response = ['response'=>true, 'pp'=>$propdet];
		   }
		   else
		   {
			$response = ['response'=>false];
		   }
        }  
        return json_encode($response);
    }

	public function validate_licence_no()
    {              
        if($this->request->getMethod()=="post")
        {
           $data=array();
           $inputs = arrFilterSanitizeString($this->request->getVar());  
		   $licence_details=$this->TradeApplyLicenceModel->getDetailsByLicence($inputs); 
		  // echo count($licence_details);
		  if($licence_details)
		  {
			$response = ['response'=>true, 'pp'=>$licence_details];
		  }
		  else
		  {
			$response = ['response'=>false];
		  }
         } 
        return json_encode($response);
    }


 	public function notice($id=null)
	{
		$data =(array)null;
		$Session = Session();
		date_default_timezone_set('Asia/Kolkata');
		$data['ulb_dtl']=$Session->get('ulb_dtl');
		$data['denialNoticeDetails']=$this->TradeApplyDenialModel->fetchNoticeDetails($id);
		$degignation ="उप प्रशासक";
		if($data["denialNoticeDetails"]["approved_by"]=='1720'){
			$degignation ="अपर प्रशासक";
		}
		$data["degignation"]=$degignation;

		$data["signature_path"]=base_url('/writable/eo_sign/notice_signature.png');
		if($data["denialNoticeDetails"]["approved_by"]){
			$empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data["denialNoticeDetails"]["approved_by"])->getFirstRow("array");
			$data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
		}

		return view('mobile/trade/viewNotice', $data);
	}
	
}
?>

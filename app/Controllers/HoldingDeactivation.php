<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_saf_deactivation;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_demand;

class HoldingDeactivation extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_saf_deactivation;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
	protected $model_prop_demand;
	
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_saf_deactivation = new model_prop_saf_deactivation($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }
	
	
    public function detail($holding_no=null)
    {
        $data =(array)null;
        $propertyDetailsList = [];
        if($this->request->getMethod()=='post')
        {
            //Cheque Details
           $data['holding_no'] = $this->request->getVar('holding_no');
            if ($propertyDetails = $this->model_prop_dtl->propertyDetails($data['holding_no'])) {
                $propertyDetailsList[0]['id'] = $propertyDetails['id'];
                $propertyDetailsList[0]['holding_no'] = $propertyDetails['holding_no'];
                $propertyDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($propertyDetails['ward_mstr_id']);
                $property = $this->model_prop_owner_detail->ownerDetailsData($propertyDetails['id']);
                $propertyDetailsList[0]['owner_name'] = $property['owner_name'];
                $propertyDetailsList[0]['mobile_no'] = $property['mobile_no'];
                $data['propertyDetailsList'] = $propertyDetailsList;
               
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('property/holding_deactivation', $data);
        }
        else if(isset($holding_no)){
            if ($propertyDetails = $this->model_prop_dtl->propertyDetailsHolding($holding_no)) {
                $propertyDetailsList[0]['id'] = $propertyDetails['id'];
                $propertyDetailsList[0]['holding_no'] = $propertyDetails['holding_no'];
                $propertyDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($propertyDetails['ward_mstr_id']);
                $property = $this->model_prop_owner_detail->ownerDetailsData($propertyDetails['id']);
                $propertyDetailsList[0]['owner_name'] = $property['owner_name'];
                $propertyDetailsList[0]['mobile_no'] = $property['mobile_no'];
                $data['propertyDetailsList'] = $propertyDetailsList;
                $data['holding_no'] = $propertyDetails['holding_no'];
               
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
			//print_r($data);
            return view('property/holding_deactivation', $data);
        }
        else
        {
           return view('property/holding_deactivation',$data);
        } 
    }

    // public function create()
    // {
    //     $session = session();
    //     $emp_details = $session->get('emp_details');
    //     $ulb_dtl = $session->get('ulb_dtl');
    //     $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
    //     $emp_details_id = $emp_details['id'];
    //     if($this->request->getMethod()=='post'){
    //         $input = [
    //                     'remark' => $this->request->getVar('remark'),
    //                     'deactivation_date' =>date('Y-m-d'),
    //                     'created_on' =>date('Y-m-d H:i:s'),
    //                     'prop_type' =>'Property',
	// 					'status' => $this->request->getVar('status'),
	// 					'paid_status' => $this->request->getVar('paid_status'),
    //                     'ward_mstr_id' => $this->request->getVar('ward_mstr_id')
    //                 ];
    //         $prop_dtl_id = $this->request->getVar('prop_dtl_id');
    //         $input['emp_details_id'] = $emp_details_id;
    //         $input['prop_dtl_id'] = $prop_dtl_id;
    //         // Prevent Double Posting
    //         if($input['paid_status']==0){
	// 			$check_status = 7;
	// 		}else if($input['paid_status']==7){
	// 			$check_status = 0;
	// 		}
	// 		$insert_id = $this->model_prop_saf_deactivation->insertData($input);
	// 		if($insert_id)
	// 		{
	// 			$rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
	// 			if($this->validate($rules))
	// 			{
	// 				$file = $this->request->getFile('doc_path');
	// 				$extension = $file->getExtension();
					
	// 				if($file->isValid() && !$file->hasMoved()){
	// 					$newName = md5($insert_id).".".$extension;
					   
	// 					if($file->move(WRITEPATH.'uploads/'.$city['city'].'/holding_deactivate_doc',$newName))
	// 					{
	// 						$this->model_prop_saf_deactivation->uploadDocument($newName,$insert_id);
	// 					}
	// 				}
	// 			}
	// 			$this->model_prop_dtl->updatePropDtlStatus($prop_dtl_id,$input['status']);
	// 			$this->model_prop_demand->updatePropdemandpaidStatus($prop_dtl_id,$check_status,$input['paid_status']);
	// 			flashToast('holding','Holding Deactivated Successfully!!');
	// 			return $this->response->redirect(base_url('jsk/jsk_due_details/'.md5($input['prop_dtl_id'])));
	// 			//return view('property/holding_deactivation');
	// 		}else{
	// 			flashToast('holding','Something Is Wrong!!!');
	// 			return $this->response->redirect(base_url('jsk/jsk_due_details/'.md5($input['prop_dtl_id'])));
	// 			//return view('property/holding_deactivation');
	// 		}
    //     }
    // }

    public function deactivateActivateHolding()
    {
      
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        $emp_details_id = $emp_details['id'];
        if($this->request->getMethod()=='post'){
            $inputs = $this->request->getVar();


                 if(isset($inputs['btn_activate'])){
                     $activate_deactivate_status='Activated';
                 }else{
                    $activate_deactivate_status='Deactivated';
                 }
                //  echo $activate_deactivate_status;
                //  die;
            $input2 = [
                "prop_dtl_id" => $this->request->getVar('prop_dtl_id'),
                "prop_type" => "Property",
                "deactivation_date" => date('Y-m-d'),
                "remark" => $this->request->getVar('remark'),
                "emp_details_id" => $emp_details_id,
                "created_on" => date('Y-m-d H:i:s'),
                "ward_mstr_id" => $this->request->getVar('ward_mstr_id'),
                "activate_deactivate_status" => $activate_deactivate_status
            ];

           
            $prop_dtl_id = $this->request->getVar('prop_dtl_id');
          
            if($inputs['paid_status']==0){
				$check_status = 7;
                $demand_status=1;
                
			}else if($inputs['paid_status']==7){
				$check_status = 0;
                $demand_status=0;

			}
           
			$insert_id = $this->model_prop_saf_deactivation->insertDeactivationData($input2);
			if($insert_id)
			{
				$rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
				if($this->validate($rules))
				{
					$file = $this->request->getFile('doc_path');
					$extension = $file->getExtension();
					
					if($file->isValid() && !$file->hasMoved()){
						$newName = md5($insert_id).".".$extension;
					   
						if($file->move(WRITEPATH.'uploads/'.$city['city'].'/holding_deactivate_doc',$newName))
						{
							$this->model_prop_saf_deactivation->uploadDocument($newName,$insert_id);
						}
					}
				}
				$this->model_prop_dtl->updatePropDtlStatus($prop_dtl_id,$demand_status);
				$this->model_prop_demand->updatePropdemandpaidStatusActDeact($prop_dtl_id,$check_status,$inputs['paid_status'],$demand_status);
                if( $demand_status==0){
                    flashToast('holding','Holding Deactivated Successfully!!');
                }else{
                    flashToast('holding','Holding Activated Successfully!!');

                }
              
				return $this->response->redirect(base_url('HoldingDeactivation/view/'.md5($prop_dtl_id)));
			}else{
				flashToast('holding','Something Is Wrong!!!');
				return $this->response->redirect(base_url('jsk/jsk_due_details/'.md5($prop_dtl_id)));
				//return view('property/holding_deactivation');
			}
        }
    }
    
    public function view($id=null){
        $data =(array)null;
        $data['id'] = $id;
        $basic_details =  $this->model_prop_dtl->prop_basic_details($data);
        $data['basic_details'] = $basic_details;
        $data['owner_details'] = $this->model_prop_owner_detail->propownerdetails($data['basic_details']['prop_dtl_id']);
        

        $data['basic_details_data']=array(
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['apartment_name'])?$basic_details['apartment_name']:'N/A',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
        // print_var($data);
        // return;
		return view('property/holding_deactivation_view',$data);
    }
}
?>

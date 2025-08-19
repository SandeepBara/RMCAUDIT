<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\PropertyModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterFerruleTypeModel;


class WaterTechnicalSiteInspection extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $user_type_mstr_id;
	protected $emp_details_id;
	protected $site_ins_model;
    protected $apply_waterconn_model;
    protected $ferrule_model;

	public function __construct()
    {

    	$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $this->ulb_mstr_id=$ulb_mstr["ulb_mstr_id"];
        

        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("water")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db_property = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 

        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
	
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->ferrule_model=new WaterFerruleTypeModel($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }

	

	public function index($apply_connection_id)
	{

		$data=array();
		$data['user_type']=$this->user_type_mstr_id;
		$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($apply_connection_id);
        $data['ae_site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyAE($apply_connection_id);
        $data['ferrule_list']=$this->ferrule_model->ferrule_type_list();
        
        $data['water_connection_details']=$this->apply_waterconn_model->water_conn_details($apply_connection_id);

        //echo"<pre>";print_r($data);echo"</pre>";
        
        if($this->request->getMethod()=='post')
        {
            $rules=[
                'ci_size'=>'required',
                'ferrule_type_id' =>'required',
                'water_lock_arng' =>'required',
                'gate_valve' =>'required',
                'pipe_size' =>'required',
                // 'meter_no' =>'required',
                // 'meter_img' =>'uploaded[meter_img]|max_size[meter_img,1024]|ext_in[meter_img,pdf,jpg,jpeg]',
                // 'init_reading' =>'required|numeric',
            ];
            $inputs=arrFilterSanitizeString($this->request->getVar());
            if(!$this->validate($rules))
            {
                $data['validation']=$this->validator;                
                $data['error'] = $this->validator->getErrors();
                
            }
            else
            {    
               
                
                if($inputs['save'])
                {
                    $data['emp_details_id']=$this->emp_details_id;
                    $data['pipeline_size']=$inputs['ci_size'];
                    $data['ferrule_type_id']=$inputs['ferrule_type_id'];
                    $data['water_lock_arng']=$inputs['water_lock_arng'];
                    $data['gate_valve']=$inputs['gate_valve'];
                    $data['pipe_size']=$inputs['pipe_size'];
                    $data['apply_connection_id']=$data['water_connection_details']['id'];

                    // $data['meter_no']= $inputs['meter_no'];   
                    // $data['init_reading']= $inputs['init_reading'];              
                    $where = array();
                    if(!empty($data['ae_site_inspection_details']))
                        $where=array('id'=>$data['ae_site_inspection_details']['id']);
                    $count=$this->site_ins_model->check_exists_verification_by_ae($data['apply_connection_id'],$where);

                
                    if($count==0)
                    {   
                        $where = array();
                        if(!empty($data['site_inspection_details']))
                            $where=array('id'=>$data['site_inspection_details']['id']);
                        $si=$this->site_ins_model->insertTechnicalSection($data,$where);
                    }
                    else
                    {
                        $where = array();
                        if(!empty($data['ae_site_inspection_details']))
                        $where=array('id'=>$data['ae_site_inspection_details']['id']);
                        $si=$data['ae_site_inspection_details']['id'];
                        $this->site_ins_model->updateTechnicalSectionDetails($data,$where);
                    }
                    #meter readining
                    {   
                        // $file = $this->request->getFile('meter_img');
                        // $extension = $file->getExtension();  
                        // $move=false;
                        // $ae_meter_data=[
                        //     'water_connection_id'=>$data['water_connection_details']['id'],
                        //     'site_inspection_id'=>$si,
                        //     'meter_no'=>$data['meter_no'],
                        //     'init_meter_reading'=> $data['init_reading'],                                
                        // ];
                        // $ae_id = $this->site_ins_model->tbl_ae_meter_inspection_insert($ae_meter_data);
                        // $city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
                        
                        // if($file->isValid() && !$file->hasMoved())
                        // {

                        //     $newName = $si."_si.".$extension;
                        //     $move=true;
                        //     $path = 'uploads/'.$city['city'].'/meter_image';
                        //     if($file->move(WRITEPATH.'uploads/'.$city['city'].'/meter_image',$newName))
                        //     {
                                
                                
                        //         $temp=['doc_path'=>$path,
                        //                 'file'=>$newName,
                        //             ];
                                
                        //         $this->site_ins_model->tbl_ae_meter_inspection_update(['id'=>$ae_id],$temp);
                        //         flashToast('success', 'Updated Successfully!!');
                        //     }
                            
                        
                        // }
                    }
                    echo'<script> window.opener.location.reload(true);window.close();</script>';

                
                }
            }
            
        }

       
		return view('water/water_connection/technical_section_details',$data);

	}

	
    public function view($apply_connection_id,$si_id='')
	{
		$data=array();
		$data['user_type']=$this->user_type_mstr_id;
        $data['ae_site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyAE($apply_connection_id);
        if($si_id!='')
        {
            $where=array('md5(id::text)'=>$si_id);
            $data['ae_site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyAE($apply_connection_id,$where);

        }
        $data['ferrule_list']=$this->ferrule_model->ferrule_type_list();
        
        $data['water_connection_details']=$this->apply_waterconn_model->water_conn_details($apply_connection_id);
		return view('water/water_connection/technical_section_details_view', $data);
	}

	

}

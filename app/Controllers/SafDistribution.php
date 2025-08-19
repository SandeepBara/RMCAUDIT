<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_collected_dtl;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_ward_permission;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use Exception;

class SafDistribution extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_view_ward_permission;
    protected $model_ward_mstr;
    protected $model_ward_permission;
    protected $model_ulb_mstr;
    protected $model_saf_distributed_dtl;
    protected $model_saf_doc_collected_dtl;
    protected $model_doc_mstr;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
	protected $model_datatable;
    
    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper','geotagging_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }

        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
        $this->model_saf_doc_collected_dtl = new model_saf_doc_collected_dtl($this->db);
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

    public function form_distribute_list()
    {
        $data =(array)null;
        helper(['form']);
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];  
        
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        //print_r($data['wardList']);
        $empdata = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
            $data['saf_no'] = $inputs['saf_no'];
            $data['datefrom'] = $inputs['datefrom'];
            $data['dateto'] = $inputs['dateto'];
			if($data['saf_no']!="")
            {
				$where = "saf_no='".$data['saf_no']."' AND survey_by_emp_details_id='".$emp_details_id."' 
				AND status='1' ORDER BY id DESC";
            }
            else if($data['ward_mstr_id']!="")
            {
				$where = "ward_mstr_id=".$data['ward_mstr_id']." AND survey_by_emp_details_id='".$emp_details_id."' 
				AND date(created_on) >='".$data['datefrom']."' AND date(created_on) <='".$data['dateto']."'
				AND status='1' ORDER BY id DESC";
            }
			 
            else
            {
				$where = "survey_by_emp_details_id='".$emp_details_id."' 
				AND date(created_on) >='".$data['datefrom']."' AND date(created_on) <='".$data['dateto']."'
				AND status='1' ORDER BY id DESC";
            }
            $Session->set('ward_mstr_id', $inputs['ward_mstr_id']);
            $Session->set('saf_no', $inputs['saf_no']);

        }
        else
        {   
            $data['datefrom']=date('Y-m-d'); 
            $data['dateto']=date('Y-m-d'); 
            $where = "survey_by_emp_details_id='".$emp_details_id."' 
			AND date(created_on) >='".$data['datefrom']."' AND date(created_on) <='".$data['dateto']."'
			AND status='1' ORDER BY id DESC";
        }
		$data['datefrom']=date('Y-m-d'); 
        $data['dateto']=date('Y-m-d');


        $Session->set('datefrom', $data['datefrom']);
		$Session->set('dateto', $data['dateto']);
		return $this->response->redirect(base_url('SafDistribution/list_of_form_distribute/'));
    }
	
	public function list_of_form_distribute()
    {
        $data =(array)null;
        helper(['form']);
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];  
        
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $empdata = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id); 
        $results_per_page = 5; 
        $totalRecords = $this->model_datatable->getTotalRecords("from view_saf_distribution"); 
        $where = $Session->get('where');
        $number_of_page = ceil ($totalRecords / $results_per_page);  
        
        //updated
		$sql = "SELECT * FROM view_saf_distribution $where";
		$result = $this->model_datatable->getDatatable($sql);
		$data['posts'] = isset($result['result'])?$result['result']:null;
		$data['safdetail'] = isset($data['posts'])?$data['posts']:null;
		$data['pager'] = isset($result['result'])?count($result['result']):0;
        $data['count'] = isset($result['count'])?$result['count']:0;
        $data['offset'] = isset($result['offset'])?$result['offset']:0;
        
        $data['datefrom'] = $Session->get('datefrom');
        $data['dateto'] = $Session->get('dateto');
        $data['ward_mstr_id'] = $Session->get('ward_mstr_id');
        $data['saf_no'] = $Session->get('saf_no');
        $data['number_of_page'] = $number_of_page;
        
		return view('mobile/property/saf/saf_distributed_List', $data);
    }

    public function saf_opt()
	{
		return view('mobile/Property/Saf/saf_opt');
	}
    public function form_distribute()
	{
        $data =(array)null;

        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $survey_by_emp_details_id = $emp_mstr["id"];  
		
        $data["wardList"] = $this->model_ward_permission->getWardList($survey_by_emp_details_id);
        //print_r($data["wardList"]);
        if($this->request->getMethod()=='post')
        {

            //Data preparation For Insert
            $data = [
                    'form_no' => $this->request->getVar('form_no'),
                    'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                    'owner_name' => $this->request->getVar('owner_name'),
                    'phone_no' => $this->request->getVar('phone_no'),
                    'owner_address' => $this->request->getVar('owner_address'),
                    'survey_by_emp_details_id'=> $survey_by_emp_details_id,
                    'saf_no'=> NUll,
                    'created_on' => date('Y-m-d H:i:s')
                ];
            
            $data_exist=$this->model_saf_distributed_dtl->CheckDataExists($data);
            $form_exist=$this->model_saf_distributed_dtl->CheckFormNoExists($data);
            
            if($form_exist)
            {
                $Session = Session();
                $ulb_mstr = $Session->get("ulb_dtl");
                $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                $data['err_msg'] = 'This Form No Already Received';
                return view('mobile/Property/Saf/form_distribute', $data);
            }
            else if($data_exist)
            {
                $Session = Session();
                $ulb_mstr = $Session->get("ulb_dtl");
                $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                $data['err_msg'] = 'This Owner & Mobile No have already taken SAF Form!';
                return view('mobile/Property/Saf/form_distribute', $data);
            }
            else
            {
                $insert_last_id = $this->model_saf_distributed_dtl->insertData($data);
                $wardlist = $this->model_ward_mstr->getdatabyid($this->request->getVar('ward_mstr_id'));           
                $saf_no='SAF'.$wardlist['ward_no'].$insert_last_id.date('s');
                $saf_no=NULL;
                $update = [
                        'saf_distributed_dtl_id' => $insert_last_id,
                        'saf_no' => $saf_no
                ];


                $this->model_saf_distributed_dtl->updateSafNoById($update);
                if($insert_last_id)
                {
                    return $this->response->redirect(base_url('safdistribution/form_distribute_view/'.md5($insert_last_id)));
                }
                else
                {
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_distribute', $data);
                }
            }
        }
        else
        {
            return view('mobile/Property/Saf/form_distribute', $data);
        }
    }

    public function form_distribute_view($id)
	{
        $data =(array)null;
        $data['form'] = $this->model_saf_distributed_dtl->getDetailsById($id);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        return view('mobile/Property/Saf/form_distribute_view', $data);
	}

    public function form_distribute_upload($id=null)
	{
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['form'] = $this->model_saf_distributed_dtl->getDetailsById($id);
        //print_r($data['form']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $upload_type='distributed';
            $left_direction_type='left';
            $data['left_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$left_direction_type);
            $right_direction_type='right';
            $data['right_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$right_direction_type);
            $front_direction_type='front';
            $data['front_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$front_direction_type);
            if($data['left_image_exists'] && $data['right_image_exists'] && $data['front_image_exists'])
            {
                $data['btn_show']='true';
            }
        if($this->request->getMethod()=='post'){
            //left image upload code starts
            if(isset($_POST['btn_left_img_upload']))
            {
                $rules=[
                        'left_image_path'=>'uploaded[left_image_path]|max_size[left_image_path,1024000]|ext_in[left_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $leftfile=$this->request->getFile('left_image_path');
                    //left image upload
                        if($leftfile->IsValid() && !$leftfile->hasMoved()){
                            $left_dt=date('dmYHis');
                            $ltrand = mt_rand();
                            $lttmpFileName = md5($left_dt.$ltrand);
                            $ltfile_ext = $leftfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($leftfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$lttmpFileName.'.'.$ltfile_ext)){
                                $left_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$lttmpFileName.'.'.$ltfile_ext;

                                //get location
                                $imgltLocation = get_image_location($left_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $ltimgLat = $imgltLocation['latitude'];
                                    $ltimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$lttmpFileName.'.'.$ltfile_ext;
                                    $ltPath = $destination_path."/".$lttmpFileName.'.'.$ltfile_ext;
                                    if(rename($left_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $ltPath,
                                            'latitude' => $ltimgLat,
                                            'longitude' => $ltimgLng,
                                            'direction_type' => 'left',
                                            'upload_type' => 'distributed',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_distribute_upload/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_distribute_upload',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_distribute_upload',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_distribute_upload',$data);
                }
            }
            //right image upload code starts
            if(isset($_POST['btn_right_img_upload']))
            {
                $rules=[
                        'right_image_path'=>'uploaded[right_image_path]|max_size[right_image_path,1024000]|ext_in[right_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $rightfile=$this->request->getFile('right_image_path');
                    //left image upload
                        if($rightfile->IsValid() && !$rightfile->hasMoved()){
                            $right_dt=date('dmYHis');
                            $rtrand = mt_rand();
                            $rttmpFileName = md5($right_dt.$rtrand);
                            $rtfile_ext = $rightfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($rightfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$rttmpFileName.'.'.$rtfile_ext)){
                                $right_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$rttmpFileName.'.'.$rtfile_ext;

                                //get location
                                $imgltLocation = get_image_location($right_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $rtimgLat = $imgltLocation['latitude'];
                                    $rtimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$rttmpFileName.'.'.$rtfile_ext;
                                    $rtPath = $destination_path."/".$rttmpFileName.'.'.$rtfile_ext;
                                    if(rename($right_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $rtPath,
                                            'latitude' => $rtimgLat,
                                            'longitude' => $rtimgLng,
                                            'direction_type' => 'right',
                                            'upload_type' => 'distributed',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_distribute_upload/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_distribute_upload',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_distribute_upload',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_distribute_upload',$data);
                }
            }
            //front image upload code starts
            if(isset($_POST['btn_front_img_upload']))
            {
                $rules=[
                        'front_image_path'=>'uploaded[front_image_path]|max_size[front_image_path,1024000]|ext_in[front_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $frontfile=$this->request->getFile('front_image_path');
                    //left image upload
                        if($frontfile->IsValid() && !$frontfile->hasMoved()){
                            $front_dt=date('dmYHis');
                            $ftrand = mt_rand();
                            $fttmpFileName = md5($front_dt.$ftrand);
                            $ftfile_ext = $frontfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($frontfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$fttmpFileName.'.'.$ftfile_ext)){
                                $front_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$fttmpFileName.'.'.$ftfile_ext;

                                //get location
                                $imgltLocation = get_image_location($front_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $ftimgLat = $imgltLocation['latitude'];
                                    $ftimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$fttmpFileName.'.'.$ftfile_ext;
                                    $ftPath = $destination_path."/".$fttmpFileName.'.'.$ftfile_ext;
                                    if(rename($front_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $ftPath,
                                            'latitude' => $ftimgLat,
                                            'longitude' => $ftimgLng,
                                            'direction_type' => 'front',
                                            'upload_type' => 'distributed',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_distribute_upload/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_distribute_upload',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_distribute_upload',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_distribute_upload',$data);
                }
            }
        }
        else
        {

            //print_r( $data['right_image_exists']);
            return view('mobile/Property/Saf/form_distribute_upload',$data);
        }


	}


    public function receive_form_search()
	{
        $data =(array)null;
        helper(['form']);
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data["wardList"] = $this->model_ward_mstr->getWardList($data);
        //print_r($data["wardList"]);

        if($this->request->getMethod()=='post'){
            $data = [
                         'saf_no'=>$this->request->getVar('saf_no'),
                         'ward_mstr_id'=>$this->request->getVar('ward_mstr_id'),
                        'phone_no'=>$this->request->getVar('phone_no')
                    ];
            if($data['saf_no'])
            {

                $data['last_saf_no']=$this->model_saf_distributed_dtl->getDetailsBySAFNo($data);
                //print_r($data['last_saf_no']);
                if($data['last_saf_no'])
                {
                    $data['data_exist']=$this->model_saf_distributed_dtl->CheckEmpidExists($data);

                    if($data['data_exist'])
                    {
                        $data["saf_list"] = $this->model_saf_distributed_dtl->getDetailsBygensafno($data);
                        foreach($data['saf_list'] as $key => $value){
                            $ward = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                            $data['saf_list'][$key]['ward_no'] = $ward['ward_no'];
                        }
                        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                        $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                        //print_r($data["wardList"]);
                        return view('mobile/Property/Saf/receive_form_view',$data);
                    }
                    else
                    {
                        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                        $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                        $data['err_msg']='Data Already Exists!!';
                        return view('mobile/Property/Saf/receive_form_view',$data);
                    }

                }
                else
                {
                    $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                    $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                    $data['err_msg']='SAF No. does not exist';
                    return view('mobile/Property/Saf/receive_form_view', $data);
                }
            }
            else if(($data['ward_mstr_id']) && ($data['phone_no']))
            {
                $data['last_saf_no']=$this->model_saf_distributed_dtl->getDetailsBywardphoneNo($data);
                //print_r($data['last_saf_no']);
                if($data['last_saf_no'])
                {
                    $data['data_exist']=$this->model_saf_distributed_dtl->CheckEmpidwardExists($data);

                    if($data['data_exist'])
                    {
                         $data["saf_list"] = $this->model_saf_distributed_dtl->getDetailsBygenwardphno($data);
                        foreach($data['saf_list'] as $key => $value){
                            $ward = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                            $data['saf_list'][$key]['ward_no'] = $ward['ward_no'];
                        }
                        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                        $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                        //print_r($data["saf_list"]);
                        return view('mobile/Property/Saf/receive_form_view',$data);

                    }
                    else
                    {
                        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                        $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                        $data['err_msg']='Data Already Exists!!';
                        return view('mobile/Property/Saf/receive_form_view',$data);
                    }

                }
                else
                {
                    $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                    $data["wardList"] = $this->model_ward_mstr->getWardList($data);
                    $data['err_msg']='SAF No. does not exist';
                    return view('mobile/Property/Saf/receive_form_view',$data);
                }
            }

        }else{

            return view('mobile/Property/Saf/receive_form_search',$data);
        }

	}


    public function form_receive($id)
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $doc_received_by_emp_details_id = $emp_mstr["id"]; 
        $data['form'] = $this->model_saf_distributed_dtl->getDetailsById($id);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        //Transfer Mode code
        $transfer_mode['doc_type']="transfer_mode";
        $data['transfer_mode'] = $this->model_doc_mstr->getdatabydoc_type($transfer_mode);
        //Property Type code
        $property_type['doc_type']="property_type";
        $data['property_type'] = $this->model_doc_mstr->getdatabydoc_type($property_type);

        date_default_timezone_set('Asia/Kolkata');
        if($this->request->getMethod()=='post'){
            $data = [
                        'saf_distributed_dtl_id' => $this->request->getVar('saf_distributed_dtl_id'),
                        'trans_doc_mstr_id' => $this->request->getVar('trans_doc_mstr_id'),
                        'prop_doc_mstr_id' => $this->request->getVar('prop_doc_mstr_id'),
                        'doc_received_by_emp_details_id'=>$doc_received_by_emp_details_id,
                        'created_on'=>date('Y-m-d H:i:s')
                    ];
            $trinsert_data=$this->model_saf_doc_collected_dtl->trinsertData($data);
             if($prinsert_data=$this->model_saf_doc_collected_dtl->prinsertData($data))
             {
                 $updaterow = $this->model_saf_distributed_dtl->updatedocreceivedetById($data);
                 return $this->response->redirect(base_url('safdistribution/form_receive_view/'.md5($data['saf_distributed_dtl_id'].'')));
             }
            else{
                $data['err_msg']='Error Occurs!!';
                return view('safdistribution/form_receive',$data);
            }



        }
        else{

		return view('mobile/Property/Saf/form_receive',$data);
            }
	}
    public function getTrDocumentName(){
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$data = ['doc_type'=>$inputs['doc_type_val']];
                $doc = $this->model_doc_mstr->getdatabytrmode($data);
                $response = ["response"=>true, "data"=>$doc];
				echo json_encode($response);
			}catch(Exception $e){

			}
		}else{
			$response = ["response"=>false];
			echo json_encode($response);
		}
	}
    public function getPrDocumentName(){
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$data = ['doc_type'=>$inputs['doc_type_val']];
                $doc = $this->model_doc_mstr->getdatabyprmode($data);
                $response = ["response"=>true, "data"=>$doc];
				echo json_encode($response);
			}catch(Exception $e){

			}
		}else{
			$response = ["response"=>false];
			echo json_encode($response);
		}
	}
    public function form_receive_view($id=null)
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"]; 
        $data['form'] = $this->model_saf_distributed_dtl->getDetailsById($id);
        //echo $data['form']['id'];
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['transfer_mode'] = $this->model_saf_doc_collected_dtl->get_tr_mode_data($id);
        $data['property_type'] = $this->model_saf_doc_collected_dtl->get_pr_mode_data($id);
        //$data['other_doc'] = $this->model_saf_doc_collected_dtl->get_other_data($id);
        $upload_type='receive';
        $left_direction_type='left';
        $data['left_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$left_direction_type);
        $right_direction_type='right';
        $data['right_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$right_direction_type);
        $front_direction_type='front';
        $data['front_image_exists']=$this->model_saf_geotag_upload_dtl->check_distributed_image_details($id,$upload_type,$front_direction_type);
        if($data['left_image_exists'] && $data['right_image_exists'] && $data['front_image_exists'])
        {
            $data['btn_show']='true';
        }
        if($this->request->getMethod()=='post'){
            //left image upload code starts
            if(isset($_POST['btn_left_img_upload']))
            {
                $rules=[
                        'left_image_path'=>'uploaded[left_image_path]|max_size[left_image_path,1024000]|ext_in[left_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $leftfile=$this->request->getFile('left_image_path');
                    //left image upload
                        if($leftfile->IsValid() && !$leftfile->hasMoved()){
                            $left_dt=date('dmYHis');
                            $ltrand = mt_rand();
                            $lttmpFileName = md5($left_dt.$ltrand);
                            $ltfile_ext = $leftfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($leftfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$lttmpFileName.'.'.$ltfile_ext)){
                                $left_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$lttmpFileName.'.'.$ltfile_ext;

                                //get location
                                $imgltLocation = get_image_location($left_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $ltimgLat = $imgltLocation['latitude'];
                                    $ltimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$lttmpFileName.'.'.$ltfile_ext;
                                    $ltPath = $destination_path."/".$lttmpFileName.'.'.$ltfile_ext;
                                    if(rename($left_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $ltPath,
                                            'latitude' => $ltimgLat,
                                            'longitude' => $ltimgLng,
                                            'direction_type' => 'left',
                                            'upload_type' => 'receive',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_receive_view/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_receive_view',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_receive_view',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_receive_view',$data);
                }
            }
            //right image upload code starts
            if(isset($_POST['btn_right_img_upload']))
            {
                $rules=[
                        'right_image_path'=>'uploaded[right_image_path]|max_size[right_image_path,1024000]|ext_in[right_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $rightfile=$this->request->getFile('right_image_path');
                    //left image upload
                        if($rightfile->IsValid() && !$rightfile->hasMoved()){
                            $right_dt=date('dmYHis');
                            $rtrand = mt_rand();
                            $rttmpFileName = md5($right_dt.$rtrand);
                            $rtfile_ext = $rightfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($rightfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$rttmpFileName.'.'.$rtfile_ext)){
                                $right_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$rttmpFileName.'.'.$rtfile_ext;

                                //get location
                                $imgltLocation = get_image_location($right_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $rtimgLat = $imgltLocation['latitude'];
                                    $rtimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$rttmpFileName.'.'.$rtfile_ext;
                                    $rtPath = $destination_path."/".$rttmpFileName.'.'.$rtfile_ext;
                                    if(rename($right_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $rtPath,
                                            'latitude' => $rtimgLat,
                                            'longitude' => $rtimgLng,
                                            'direction_type' => 'right',
                                            'upload_type' => 'receive',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_receive_view/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_receive_view',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_receive_view',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_receive_view',$data);
                }
            }
            //front image upload code starts
            if(isset($_POST['btn_front_img_upload']))
            {
                $rules=[
                        'front_image_path'=>'uploaded[front_image_path]|max_size[front_image_path,1024000]|ext_in[front_image_path,jpg,jpeg]',
                ];
                if($this->validate($rules)){
                      $frontfile=$this->request->getFile('front_image_path');
                    //left image upload
                        if($frontfile->IsValid() && !$frontfile->hasMoved()){
                            $front_dt=date('dmYHis');
                            $ftrand = mt_rand();
                            $fttmpFileName = md5($front_dt.$ftrand);
                            $ftfile_ext = $frontfile->getExtension();
                            $temp_path = "saf_distributed_dtl_tmp";
                            $destination_path = "saf_distributed_dtl";
                            if($frontfile->move(WRITEPATH.'uploads/'.$temp_path.'/',$fttmpFileName.'.'.$ftfile_ext)){
                                $front_image_temp_path = WRITEPATH.'uploads/'.$temp_path."/".$fttmpFileName.'.'.$ftfile_ext;

                                //get location
                                $imgltLocation = get_image_location($front_image_temp_path);
                                if(!empty($imgltLocation)){
                                    //latitude & longitude
                                    $ftimgLat = $imgltLocation['latitude'];
                                    $ftimgLng = $imgltLocation['longitude'];
                                    $destinationFilePath = WRITEPATH.'uploads/'.$destination_path."/".$fttmpFileName.'.'.$ftfile_ext;
                                    $ftPath = $destination_path."/".$fttmpFileName.'.'.$ftfile_ext;
                                    if(rename($front_image_temp_path, $destinationFilePath))
                                    {
                                        $data = [
                                            'geotag_dtl_id' => $data['form']['id'],
                                            'image_path' => $ftPath,
                                            'latitude' => $ftimgLat,
                                            'longitude' => $ftimgLng,
                                            'direction_type' => 'front',
                                            'upload_type' => 'receive',
                                            'created_by_emp_details_id' => $login_emp_details_id,
                                            'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                        if($insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data)){
                                            return $this->response->redirect(base_url('safdistribution/form_receive_view/'.$id.''));
                                        }
                                        else{
                                            $data['err_msg']='Error Occurs!!';
                                            return view('mobile/Property/Saf/form_receive_view',$data);
                                        }
                                    }
                                }
                                else{
                                        $data['err_msg']='Error Occurs!!';
                                        return view('mobile/Property/Saf/form_receive_view',$data);
                                }
                            }
                        }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('mobile/Property/Saf/form_receive_view',$data);
                }
            }
        }else{
            return view('mobile/Property/Saf/form_receive_view',$data);
        }


     }

}

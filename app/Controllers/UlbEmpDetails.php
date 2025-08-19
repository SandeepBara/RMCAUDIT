<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_emp_details;
use App\Models\model_user_type_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_user_mstr;
use App\Models\model_ulb_permission;
use App\Models\model_user_hierarchy;
use App\Models\model_view_user_hierarchy;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;

class UlbEmpDetails extends AlphaController
{
    protected $dbSystem;
    protected $model_emp_details;
    protected $model_user_type_mstr;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_user_mstr;
    protected $model_ulb_permission;
    protected $model_user_hierarchy;
    protected $model_view_user_hierarchy;
    protected $model_ward_permission;
    protected $model_view_emp_details;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'form']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_user_mstr = new model_user_mstr($this->dbSystem);
        $this->model_ulb_permission = new model_ulb_permission($this->dbSystem);
        $this->model_user_hierarchy = new model_user_hierarchy($this->dbSystem);
        $this->model_view_user_hierarchy = new model_view_user_hierarchy($this->dbSystem);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
    }
    function __destruct() {
		if($this->dbSystem)$this->dbSystem->close();
	}

    public function ulbEmpList()
    {     
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $data['user_type_mstr_id'] = $session->get('emp_details')["user_type_mstr_id"];
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ulbEmpList'] = $this->model_view_emp_details->empUlbList($ulb_mstr_id);
        /*print_r($data['ulbEmpList']);*/
        return view('system/ulbEmpDetailsList',$data);
    }
    public function resetPasswordUlb($user_mstr_id, $user_name)
    {     
        $sql = "UPDATE tbl_user_mstr SET user_pass='12345' WHERE user_name='".$user_name."' AND status=1 AND lock_status=0";
        $this->dbSystem->query($sql);
        flashToast('empList','Password Reset Successfully !!!');
        $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
    }
    public function ulbAddUpdate_old($id=null)
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $created_by_emp_details_id = $emp_details['id'];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        //User Type List
        $user_type_list = $this->model_user_type_mstr->ulbUserList();
        $data['user_type_list'] = $user_type_list;  

        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="") // insert
            {

                $input =[
                            'personal_phone_no' =>$this->request->getVar('personal_phone_no'),
                            'email_id' => $this->request->getVar('email_id'),
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                            'created_on' =>date('Y-m-d H:i:s')
                       ];
                //First Name
                $emp_name = $this->request->getVar('emp_name');
               // $emp_name = trim(strtolower($emp_name));
                $emp_name = trim(ucwords($emp_name));

                //Gaurdian Name
                 $designation =$this->request->getVar('designation');
                 $designation = trim(ucwords($designation));
                //Input Field

                $counter = $this->model_user_mstr->countAllResults();
                $input['emp_name'] = $emp_name;
                $input['designation'] = $designation;
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(strtolower($emp_name));
                $emp_name = str_replace(' ', '', $emp_name);
                $user_name = $emp_name.".".$counter;

                while($this->model_user_mstr->where("user_name",$user_name)->countAllResults()!=0){
                    $counter+=1;
                    $user_name = $emp_name.".".$counter;
                }

                $input['user_name'] = $user_name;

                $user_mstr_id = $this->model_user_mstr->insertUlbData($input); //User Mstr Table Operation

                if($user_mstr_id)
                {
                    $ulb_permission ="";
                    $input['user_mstr_id'] = $user_mstr_id;
                    $emp_id = $this->model_emp_details->insertUlbData($input); //ULB Employee Details Table Operation

                    $emp_name = $this->request->getVar('emp_name');
                    $emp_name = trim(strtolower($emp_name));
                    $emp_name = str_replace(' ', '', $emp_name);
                    // $user_name = $emp_name.".".$user_mstr_id;

                    //Update User Name
                    // $this->model_user_mstr->updateUserName($user_name,$user_mstr_id);
                    if($emp_id) 
                    { 
                        //ULB Permission
                        $ulb_mstr_id = $ulb_mstr_id;
                        $created_on =date('Y-m-d H:i:s');
                        $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                        //Insert Ward Permission
                        $ward_id = $this->request->getVar('ward_mstr_id'); 
                        $len = sizeof($ward_id);
                        for($i=0;$i<$len;$i++)
                        {
                            $input=[
                                        'ward_mstr_id' => $ward_id[$i],
                                        'emp_details_id' => $emp_id,
                                        'created_on' => date('Y-m-d H:i:s')
                                    ];
                            $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                            $ward_permission = $this->model_ward_permission->insertData($input);
                        }
                        
                        if($ulb_permission)
                        {
                            flashToast('empList','ULB Employee Added!!!');
                            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
                        }
                        else
                        {
                            echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                            $input['user_type_list'] = $user_type_list;
                            return view('system/ulb_emp_details_add_update',$input);
                        }
                    }
                    else
                    {
                        echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                        $input['user_type_list'] = $user_type_list;
                        return view('system/ulb_emp_details_add_update',$input);
                    }   
                }
                else
                {
                    echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                    $input['user_type_list'] = $user_type_list;
                    return view('system/ulb_emp_details_add_update',$input);
                }
            }
            else //Update Operation
            {
                $input =[
                            'personal_phone_no' =>$this->request->getVar('personal_phone_no'),
                            'email_id' => $this->request->getVar('email_id'),
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                       ];
                $id = $this->request->getVar('id');
                $input['id'] = $id;
                //Name
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(ucwords($emp_name));
                $input['emp_name'] = $emp_name;

                //designation
                $designation =$this->request->getVar('designation');
                $designation = trim(ucwords($designation));

                //Input Field
                $input['designation'] = $designation;

                //Get user_mstr_id
               /* $getUserMstrIdByEmpDetailsId = $this->model_ulb_employee_details->getUserMstrIdByEmpDetailsId($id);
              
                $user_mstr_id = $getUserMstrIdByEmpDetailsId['user_mstr_id'];*/

                $updateEmpDetailsById = $this->model_emp_details->updateUlbEmpDetailsById($input);
                if($updateEmpDetailsById)
                {
                    //Retrieve Data From session
                    $session = session();
                    $emp_details = $session->get('emp_details');
                    $created_by_emp_details_id = $emp_details['id'];

                    //Update Ulb Permission
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                    //Set Status Zero For Update Ward Permission
                    $status = $this->model_ward_permission->setWardPermissionStatusZero($id);
                    $ward_id = $this->request->getVar('ward_mstr_id'); 
                    $len = sizeof($ward_id);
                    for($i=0;$i<$len;$i++)
                    {
                        $ward_mstr_id = $ward_id[$i];
                        $isExists = $this->model_ward_permission->checkIsExists($ward_mstr_id,$id);
                        if($isExists)
                        {
                            $updateWardByEmpDetailsId = $this->model_ward_permission->updateWardByEmpDetailsId($id,$ward_mstr_id);
                        }
                        else
                        {
                            $input=[
                                    'ward_mstr_id' => $ward_id[$i],
                                    'emp_details_id' => $id,
                                    'created_on' => date('Y-m-d H:i:s')
                                ];
                            $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                            $ward_permission = $this->model_ward_permission->insertData($input);
                        }
                    }
                    flashToast('empUpdate','Ulb Employee Updated!!!');
                    $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
                }
                else
                {
                    echo "<script>alert('Fail To Update Ulb Emplopee Details!!!');</script>";
                    return view('system/ulb_emp_details_add_update',$input);
                }
            }
        }
        else if(isset($id)) //Retrieve Data By Id
        {
            $data = $this->model_emp_details->getEmpDetailsById($id);
            $data['user_type_list'] = $user_type_list;
            return view('system/ulb_emp_details_add_update',$data);
        }
        else
        {
            $data['user_type_list'] = $user_type_list;
            return view('system/ulb_emp_details_add_update',$data);
        }
    }

    public function ulbAddUpdate($id=null)
    {
        $session = session();
        $rules = array(
            'email_id'    => 'required|max_length[254]|valid_email'
        );
        if(!$id){
            $rules['signature_path'] ='uploaded[signature_path]|max_size[signature_path,1024]|ext_in[signature_path,png]';
        }

        $emp_details = $session->get('emp_details');
        $created_by_emp_details_id = $emp_details['id'];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        //User Type List
        $user_type_list = $this->model_user_type_mstr->ulbUserList();
        $data['user_type_list'] = $user_type_list;

        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="") // insert
            {
                if (! $this->validate($rules)) {
                    return redirect()->back()->withInput()->with('validation',$this->validator);
                }
                $input =[
                            'personal_phone_no' =>sanitizeString($this->request->getVar('personal_phone_no')),
                            'email_id' => sanitizeString($this->request->getVar('email_id')),
                            'user_type_mstr_id' => sanitizeString($this->request->getVar('user_type_mstr_id')),
                            'created_on' =>date('Y-m-d H:i:s')
                       ];
                //First Name
                $emp_name = $this->request->getVar('emp_name');
               // $emp_name = trim(strtolower($emp_name));
                $emp_name = trim(ucwords($emp_name));

                //Gaurdian Name
                 $designation =$this->request->getVar('designation');
                 $designation = trim(ucwords($designation));
                //Input Field

                $counter = $this->model_user_mstr->countAllResults();
                $input['emp_name'] = $emp_name;
                $input['designation'] = $designation;
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(strtolower($emp_name));
                $emp_name = str_replace(' ', '', $emp_name);
                $user_name = $emp_name.".".$counter;

                while($this->model_user_mstr->where("user_name",$user_name)->countAllResults()!=0){
                    $counter+=1;
                    $user_name = $emp_name.".".$counter;
                }

                $input['user_name'] = $user_name;

                $user_mstr_id = $this->model_user_mstr->insertUlbData($input); //User Mstr Table Operation

                if($user_mstr_id)
                {
                    $ulb_permission ="";
                    $input['user_mstr_id'] = $user_mstr_id;
                    $emp_id = $this->model_emp_details->insertUlbData($input); //ULB Employee Details Table Operation

                    $emp_name = $this->request->getVar('emp_name');
                    $emp_name = trim(strtolower($emp_name));
                    $emp_name = str_replace(' ', '', $emp_name);
                    // $user_name = $emp_name.".".$user_mstr_id;

                    //Update User Name
                    // $this->model_user_mstr->updateUserName($user_name,$user_mstr_id);
                    if($emp_id) 
                    { 
                        $this->validator->reset();
                        $rules = ['signature_path' => 'uploaded[signature_path]|max_size[signature_path,1024]|ext_in[signature_path,png]'];
                        if($this->validate($rules))
                        {
                            $signature = $this->request->getFile('signature_path');
                            $signatureExtension = $signature->getExtension();

                            if($signature->isValid() && !$signature->hasMoved()){
                                $newName = ($emp_id).".".$signatureExtension;
                                if($signature->move(WRITEPATH.'uploads/emp_signature',$newName))
                                {
                                    $this->model_emp_details->uploadSignature($newName,$emp_id);
                                } 
                            }
                        }
                        //ULB Permission
                        $ulb_mstr_id = $ulb_mstr_id;
                        $created_on =date('Y-m-d H:i:s');
                        $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                        //Insert Ward Permission
                        $ward_id = $this->request->getVar('ward_mstr_id'); 
                        $len = sizeof($ward_id);
                        for($i=0;$i<$len;$i++)
                        {
                            $input=[
                                        'ward_mstr_id' => $ward_id[$i],
                                        'emp_details_id' => $emp_id,
                                        'created_on' => date('Y-m-d H:i:s')
                                    ];
                            $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                            $ward_permission = $this->model_ward_permission->insertData($input);
                        }
                        
                        if($ulb_permission)
                        {
                            flashToast('empList','ULB Employee Added!!!');
                            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
                        }
                        else
                        {
                            echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                            $input['user_type_list'] = $user_type_list;
                            return view('system/ulb_emp_details_add_update',$input);
                        }
                    }
                    else
                    {
                        echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                        $input['user_type_list'] = $user_type_list;
                        return view('system/ulb_emp_details_add_update',$input);
                    }   
                }
                else
                {
                    echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                    $input['user_type_list'] = $user_type_list;
                    return view('system/ulb_emp_details_add_update',$input);
                }
            }
            else //Update Operation
            {
                if (! $this->validate($rules)) {
                    return redirect()->back()->withInput()->with('validation',$this->validator);
                }
                $input =[
                            'personal_phone_no' =>sanitizeString($this->request->getVar('personal_phone_no')),
                            'email_id' => sanitizeString($this->request->getVar('email_id')),
                            'user_type_mstr_id' => sanitizeString($this->request->getVar('user_type_mstr_id')),
                       ];
                $id = $this->request->getVar('id');
                $input['id'] = $id;
                //Name
                $emp_name = sanitizeString($this->request->getVar('emp_name'));
                $emp_name = trim(ucwords($emp_name));
                $input['emp_name'] = $emp_name;

                //designation
                $designation =sanitizeString($this->request->getVar('designation'));
                $designation = trim(ucwords($designation));

                //Input Field
                $input['designation'] = $designation;

                //Get user_mstr_id
               /* $getUserMstrIdByEmpDetailsId = $this->model_ulb_employee_details->getUserMstrIdByEmpDetailsId($id);
              
                $user_mstr_id = $getUserMstrIdByEmpDetailsId['user_mstr_id'];*/

                $updateEmpDetailsById = $this->model_emp_details->updateUlbEmpDetailsById($input);
                if($updateEmpDetailsById)
                {
                    $this->validator->reset();
                    $rules = ['signature_path' => 'uploaded[signature_path]|max_size[signature_path,1024]|ext_in[signature_path,png]'];
                    if($this->validate($rules))
                    {
                        $signature = $this->request->getFile('signature_path');
                        $signatureExtension = $signature->getExtension();

                        if($signature->isValid() && !$signature->hasMoved()){
                            $newName = ($id).".".$signatureExtension;
                            if($signature->move(WRITEPATH.'uploads/emp_signature',$newName))
                            {
                                $this->model_emp_details->uploadSignature($newName,$id);
                            } 
                        }
                    }
                    //Retrieve Data From session
                    $session = session();
                    $emp_details = $session->get('emp_details');
                    $created_by_emp_details_id = $emp_details['id'];

                    //Update Ulb Permission
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                    //Set Status Zero For Update Ward Permission
                    $status = $this->model_ward_permission->setWardPermissionStatusZero($id);
                    $ward_id = $this->request->getVar('ward_mstr_id'); 
                    $len = sizeof($ward_id);
                    for($i=0;$i<$len;$i++)
                    {
                        $ward_mstr_id = $ward_id[$i];
                        $isExists = $this->model_ward_permission->checkIsExists($ward_mstr_id,$id);
                        if($isExists)
                        {
                            $updateWardByEmpDetailsId = $this->model_ward_permission->updateWardByEmpDetailsId($id,$ward_mstr_id);
                        }
                        else
                        {
                            $input=[
                                    'ward_mstr_id' => $ward_id[$i],
                                    'emp_details_id' => $id,
                                    'created_on' => date('Y-m-d H:i:s')
                                ];
                            $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                            $ward_permission = $this->model_ward_permission->insertData($input);
                        }
                    }
                    flashToast('empUpdate','Ulb Employee Updated!!!');
                    $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
                }
                else
                {
                    echo "<script>alert('Fail To Update Ulb Emplopee Details!!!');</script>";
                    return view('system/ulb_emp_details_add_update',$input);
                }
            }
        }
        else if(isset($id)) //Retrieve Data By Id
        {
            $data = $this->model_emp_details->getEmpDetailsById($id);
            $data['user_type_list'] = $user_type_list;
            return view('system/ulb_emp_details_add_update',$data);
        }
        else
        {
            $data['user_type_list'] = $user_type_list;
            return view('system/ulb_emp_details_add_update',$data);
        }
    }


    public function ajax_wardList()
    {
        if($this->request->getMethod()=='post'){
            $session = session();
            $ulb_dtl = $session->get('ulb_dtl');
            $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
            //Retrieve data for Update
            $id = $this->request->getVar('id');
            $gateWardDataByEmpdetailsId = $this->model_ward_permission->gateWardDataByEmpdetailsId($id);

            //Retrive Single Data
            $result = $this->model_ward_mstr->ajax_wardList($ulb_mstr_id);
            if($result){
                $div ="";
                foreach ($result as $key => $value) {
                    $isCheck = "";
                    foreach ($gateWardDataByEmpdetailsId as $val) {
                        if($value['id']==$val['ward_mstr_id']){
                            $isCheck = "checked";
                        }
                    }
                    $div .= '<div class="col-sm-3">';
                        $div .= '<div class="checkbox">';
                            $div .= '<input type="checkbox" id="ward_mstr_id'.$key.'" name="ward_mstr_id[]" class="magic-checkbox" value="'.$value['id'].'"'.$isCheck.'/>';
                            $div .= '<label for="ward_mstr_id'.$key.'">'.$value['ward_no'].'</label>';
                        $div .= '</div>';
                    $div .= '</div>';
                }
                $response = ['response'=>true, 'data'=>$div];
            }
            else{
                $response = ['response'=>false];
            }
            echo json_encode($response);
        }
        else{
                $response = ['response'=>false, 'data'=>'response is not post'];
                echo json_encode($response);
        }
    }
    public function lockEmployee($user_mstr_id)
    {
        $lock = $this->model_user_mstr->lockEmployee($user_mstr_id);
        if($lock){
            flashToast('lock','Employee Services Locked!!!');
            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
        }
        else
        {
            flashToast('fail_lock','Fail To Lock Employee Services!!!');
            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
        }
    }
    public function unlockEmployee($user_mstr_id)
    {
        $unlock = $this->model_user_mstr->unlockEmployee($user_mstr_id);
        if($unlock){
            flashToast('unlock','Employee Services Unlocked!!!');
            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
        }
        else
        {
            flashToast('fail_unlock','Fail To Unlock Employee Services!!!');
            $this->response->redirect(base_url('UlbEmpDetails/ulbEmpList'));
        }
    }
}
?>

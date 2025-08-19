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

class EmpDetails extends AlphaController
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
    
    public function empList()
    {
        $session=session();
        $emp_mstr = $session->get("emp_details");
        $data['user_type_mstr_id'] = $session->get('emp_details')["user_type_mstr_id"];
        if(!in_array($data['user_type_mstr_id'],[1,2,38])){
            return redirect()->to('/home');
        }

        $actionBtn = $this->request->getVar("action");
        if (isset($actionBtn) && $actionBtn=="refreshEmp") {
            $this->model_emp_details->refreshEmpMaterialView();
            flashToast('message','Employee list updated!!!');
            $this->response->redirect(base_url('EmpDetails/empList'));
        }
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $emplist = $this->model_view_emp_details->empList($ulb_mstr_id );
        $data['emplist'] = $emplist;
        return view('system/empDetailsList',$data);
    }

    
    public function add_update_old($id=null)
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $created_by_emp_details_id = $emp_details['id'];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        //User Type List
        $user_type_list = $this->model_user_type_mstr->userList();
        $data['user_type_list'] = $user_type_list;
       // print_r($user_type_list);

        //Ulb List
        $ulbList = $this->model_ulb_mstr->ulb_list();
        $data['ulbList'] = $ulbList;

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
                $report_to = $this->request->getVar('report_to');
                if($report_to==""){
                    $input['report_to'] = NULL;
                }
                else{
                    $input['report_to'] = $report_to;
                }
                $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                //First Name
                $emp_name = $this->request->getVar('emp_name');
               // $emp_name = trim(strtolower($emp_name));
                $emp_name = trim(ucwords($emp_name));
                //Middle Name
                $middle_name =$this->request->getVar('middle_name');
                $middle_name = trim(ucwords($middle_name));

                //Last Name
                $last_name = $this->request->getVar('last_name');

                $last_name = trim(ucwords($last_name));
                //Gaurdian Name
                $guardian_name =$this->request->getVar('guardian_name');
                $guardian_name = trim(ucwords($guardian_name));
                //Input Field
                $input['emp_name'] = $emp_name;
                $input['middle_name'] = $middle_name;
                $input['last_name'] = $last_name;
                $input['guardian_name'] = $guardian_name;

                $counter = $this->model_user_mstr->countAllResults();
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(strtolower($emp_name));
                $emp_name = str_replace(' ', '', $emp_name);
                $user_name = $emp_name.".".$counter;
                while($this->model_user_mstr->where("user_name",$user_name)->countAllResults()!=0){
                    $counter+=1;
                    $user_name = $emp_name.".".$counter;
                }

                $input['user_name'] = $user_name;
                
                $user_mstr_id = $this->model_user_mstr->insertData($input); //User Mstr Table Operation

                $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                if($user_mstr_id){
                    $ulb_permission="";
                    $input['user_mstr_id'] = $user_mstr_id;
                    $employee_code ="EMP-0000".$user_mstr_id;
                    $input['employee_code'] = $employee_code;
                    $emp_id = $this->model_emp_details->insertData($input); //Emp Details Table Operation
                    //Update User Name Into User Mstr Table
                   $rules = ['photo_path' => 'uploaded[photo_path]|max_size[photo_path,1024]|ext_in[photo_path,png,jpg,gif]'];
                        if($this->validate($rules))
                        {
                             $file = $this->request->getFile('photo_path');
                              $extension = $file->getExtension();

                             if($file->isValid() && !$file->hasMoved()){
                                $newName = md5($emp_id).".".$extension;
                                if($file->move(WRITEPATH.'uploads/emp_image',$newName))
                                {
                                    $this->model_emp_details->uploadImage($newName,$emp_id);
                                }
                             }
                        }
                    $emp_name = $this->request->getVar('emp_name');
                    $emp_name = trim(strtolower($emp_name));
                    $emp_name = str_replace(' ', '', $emp_name);
                    $last_name = $this->request->getVar('last_name');
                    $last_name = trim(strtolower($last_name));
                    $last_name = str_replace(' ', '', $last_name);
                    // $user_name = $emp_name.".".$last_name.$user_mstr_id;
                    // $this->model_user_mstr->updateUserName($user_name,$user_mstr_id);
                    if($emp_id) //ULB Permission
                    {
                        if($user_type_mstr_id==4 || $user_type_mstr_id==5 || $user_type_mstr_id==11)
                        {
                            $ulb_mstr_id = $ulb_mstr_id;
                            $created_on =date('Y-m-d H:i:s');
                            $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                        }
                        else
                        {
                            $ulb_mstr_data = $this->request->getVar('ulb_mstr_id');
                            $len_ulb = sizeof($ulb_mstr_data);
                            for($i=0;$i<$len_ulb;$i++)
                            {
                                $ulb_mstr_id = $ulb_mstr_data[$i];
                                $created_on =date('Y-m-d H:i:s');
                                $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                            }
                        }
                        if($user_type_mstr_id==4 || $user_type_mstr_id==5 || $user_type_mstr_id==11)
                        {
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
                            $sql = "SELECT RefreshAllWardPermissionMV();";
                            $this->dbSystem->query($sql);
                        }
                        if($ulb_permission)
                        {
                            
                            flashToast('empList','Employee Added!!!');
                            $this->response->redirect(base_url('EmpDetails/empList'));
                        }
                        else
                        {
                            echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                            $input['user_type_list'] = $user_type_list;
                            return view('system/emp_details_add_update',$input);
                        }
                    }
                    else
                    {
                        echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                        $input['user_type_list'] = $user_type_list;
                        return view('system/emp_details_add_update',$input);
                    }
                }
                else
                {
                    echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                    $input['user_type_list'] = $user_type_list;
                    return view('system/emp_details_add_update',$input);
                }
            }
            else //Update Operation
            {
                $input =[
                            'personal_phone_no' =>$this->request->getVar('personal_phone_no'),
                            'email_id' => $this->request->getVar('email_id'),
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                            'updated_on' =>date('Y-m-d H:i:s')
                       ];
                $report_to = $this->request->getVar('report_to');
                if($report_to==""){
                    $input['report_to'] = NULL;
                }
                else
                {
                    $input['report_to'] = $report_to;
                }

                $id = $this->request->getVar('id');
                $input['id'] = $id;
                //First Name
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(ucwords($emp_name));
                //Middle Name
                $middle_name =$this->request->getVar('middle_name');
                $middle_name = trim(ucwords($middle_name));
                //Last Name
                $last_name = $this->request->getVar('last_name');
                 $last_name = trim(ucwords($last_name));
                //Gaurdian Name
                 $guardian_name =$this->request->getVar('guardian_name');
                 $guardian_name = trim(ucwords($guardian_name));
                //Input Field
                $input['emp_name'] = $emp_name;
                $input['middle_name'] = $middle_name;
                $input['last_name'] = $last_name;
                $input['guardian_name'] = $guardian_name;
                //Get user_mstr_id
                $getUserMstrIdByEmpDetailsId = $this->model_emp_details->getUserMstrIdByEmpDetailsId($id);
                $user_mstr_id = $getUserMstrIdByEmpDetailsId['user_mstr_id'];

                $updateEmpDetailsById = $this->model_emp_details->updateEmpDetailsById($input);
                if($updateEmpDetailsById)
                {
                    //Update Employee Image
                     $rules = ['photo_path' => 'uploaded[photo_path]|max_size[photo_path,1024]|ext_in[photo_path,png,jpg,gif]'];
                        if($this->validate($rules))
                        {
                            $file = $this->request->getFile('photo_path');
                            $extension = $file->getExtension();

                            if($file->isValid() && !$file->hasMoved()){
                                $newName = md5($id).".".$extension;
                                if($file->move(WRITEPATH.'uploads/emp_image',$newName))
                                {
                                    $this->model_emp_details->uploadImage($newName,$id);
                                }
                                /*else
                                {
                                    echo '<p>Fail to upload Image</p>';
                                }*/
                             }
                        }
                    //Retrieve Data From session
                    $session = session();
                    $emp_details = $session->get('emp_details');
                    $created_by_emp_details_id = $emp_details['id'];
                    //Update Ulb Permission
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                    if($user_type_mstr_id!=4 && $user_type_mstr_id!=5 && $user_type_mstr_id!=11)
                    {
                        //Set Status Zero For Update Record
                        $status = $this->model_ulb_permission->setStatusZeroForUpdateRecord($id);
                        $ulb_mstr_data = $this->request->getVar('ulb_mstr_id');
                        $len_ulb = sizeof($ulb_mstr_data);
                        for($i=0;$i<$len_ulb;$i++)
                        {
                            $ulb_mstr_id = $ulb_mstr_data[$i];
                            $isExists = $this->model_ulb_permission->checkIsExists($ulb_mstr_id,$id);
                            if($isExists) //Update Ulb Record
                            {
                                $updateUlbByEmpDetailsId = $this->model_ulb_permission->updateUlbByEmpDetailsId($id,$ulb_mstr_id);
                            }
                            else //Insert New Record
                            {

                                $ulb_mstr_id = $ulb_mstr_data[$i];
                                $created_on =date('Y-m-d H:i:s');
                                $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$id);
                            }
                        }
                        $sql = "SELECT RefreshAllWardPermissionMV();";
                        $this->dbSystem->query($sql);
                    }
                    else
                    {
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
                        $sql = "SELECT RefreshAllWardPermissionMV();";
                        $this->dbSystem->query($sql);
                    }
                    flashToast('empUpdate','Employee Updated!!!');
                    $this->response->redirect(base_url('EmpDetails/empList'));
                }
                else
                {
                    echo "<script>alert('Fail To Update Emplopee Details!!!');</script>";
                    return view('system/emp_details_add_update',$input);
                }
            }
        }
        else if(isset($id)) //Retrieve Data By Id
        {
            $data = $this->model_emp_details->getEmpDetailsById($id);
            $data['user_type_list'] = $user_type_list;
            return view('system/emp_details_add_update',$data);
        }
        else
        {
            $data['user_type_list'] = $user_type_list;
            return view('system/emp_details_add_update',$data);
        }
    }

    public function add_update($id=null)
    {
        $session = session(); 
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details["user_type_mstr_id"];
        $created_by_emp_details_id = $emp_details['id'];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        if(!in_array($emp_details_id,[1,2,38]))
        {
            return redirect()->to('/home');
        }


        $data =(array)null;
        //User Type List
        $user_type_list = $this->model_user_type_mstr->userList();
        $data['user_type_list'] = $user_type_list;
       // print_r($user_type_list);

        //Ulb List
        $ulbList = $this->model_ulb_mstr->ulb_list();
        $data['ulbList'] = $ulbList;

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
                $report_to = $this->request->getVar('report_to');
                if($report_to==""){
                    $input['report_to'] = NULL;
                }
                else{
                    $input['report_to'] = $report_to;
                }
                $input['created_by_emp_details_id'] = $created_by_emp_details_id;
                //First Name
                $emp_name = $this->request->getVar('emp_name');
               // $emp_name = trim(strtolower($emp_name));
                $emp_name = trim(ucwords($emp_name));
                //Middle Name
                $middle_name =$this->request->getVar('middle_name');
                $middle_name = trim(ucwords($middle_name));

                //Last Name
                $last_name = $this->request->getVar('last_name');

                $last_name = trim(ucwords($last_name));
                //Gaurdian Name
                $guardian_name =$this->request->getVar('guardian_name');
                $guardian_name = trim(ucwords($guardian_name));
                //Input Field
                $input['emp_name'] = $emp_name;
                $input['middle_name'] = $middle_name;
                $input['last_name'] = $last_name;
                $input['guardian_name'] = $guardian_name;
                $counter = $this->model_user_mstr->countAllResults();
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(strtolower($emp_name));
                $emp_name = str_replace(' ', '', $emp_name);
                $user_name = $emp_name.".".$counter;
                while($this->model_user_mstr->where("user_name",$user_name)->countAllResults()!=0){
                    $counter+=1;
                    $user_name = $emp_name.".".$counter;
                }

                $input['user_name'] = $user_name;

                $user_mstr_id = $this->model_user_mstr->insertData($input); //User Mstr Table Operation

                $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                if($user_mstr_id){
                    $ulb_permission="";
                    $input['user_mstr_id'] = $user_mstr_id;
                    $employee_code ="EMP-0000".$user_mstr_id;
                    $input['employee_code'] = $employee_code;
                    $emp_id = $this->model_emp_details->insertData($input); //Emp Details Table Operation
                    //Update User Name Into User Mstr Table
                    $rules = ['photo_path' => 'uploaded[photo_path]|max_size[photo_path,1024]|ext_in[photo_path,png,jpg,gif]'];
                    if($this->validate($rules))
                    {
                            $file = $this->request->getFile('photo_path');
                            $extension = $file->getExtension();

                            if($file->isValid() && !$file->hasMoved()){
                            $newName = md5($emp_id).".".$extension;
                            if($file->move(WRITEPATH.'uploads/emp_image',$newName))
                            {
                                $this->model_emp_details->uploadImage($newName,$emp_id);
                            }
                            }
                    }
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
                    $emp_name = $this->request->getVar('emp_name');
                    $emp_name = trim(strtolower($emp_name));
                    $emp_name = str_replace(' ', '', $emp_name);
                    $last_name = $this->request->getVar('last_name');
                    $last_name = trim(strtolower($last_name));
                    $last_name = str_replace(' ', '', $last_name);
                    // $user_name = $emp_name.".".$last_name.$user_mstr_id;
                    // $this->model_user_mstr->updateUserName($user_name,$user_mstr_id);
                    if($emp_id) //ULB Permission
                    {
                        if($user_type_mstr_id==4 || $user_type_mstr_id==5 || $user_type_mstr_id==11)
                        {
                            $ulb_mstr_id = $ulb_mstr_id;
                            $created_on =date('Y-m-d H:i:s');
                            $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                        }
                        else
                        {
                            $ulb_mstr_data = $this->request->getVar('ulb_mstr_id');
                            $len_ulb = sizeof($ulb_mstr_data);
                            for($i=0;$i<$len_ulb;$i++)
                            {
                                $ulb_mstr_id = $ulb_mstr_data[$i];
                                $created_on =date('Y-m-d H:i:s');
                                $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_id);
                            }
                        }
                        if($user_type_mstr_id==4 || $user_type_mstr_id==5 || $user_type_mstr_id==11)
                        {
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
                            $sql = "SELECT RefreshAllWardPermissionMV();";
                            $this->dbSystem->query($sql);
                        }
                        if($ulb_permission)
                        {
                            
                            flashToast('empList','Employee Added!!!');
                            $this->response->redirect(base_url('EmpDetails/empList'));
                        }
                        else
                        {
                            echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                            $input['user_type_list'] = $user_type_list;
                            return view('system/emp_details_add_update',$input);
                        }
                    }
                    else
                    {
                        echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                        $input['user_type_list'] = $user_type_list;
                        return view('system/emp_details_add_update',$input);
                    }
                }
                else
                {
                    echo "<script>alert('Fail To Add Emplopee Details!!!');</script>";
                    $input['user_type_list'] = $user_type_list;
                    return view('system/emp_details_add_update',$input);
                }
            }
            else //Update Operation
            { 
                $input =[
                            'personal_phone_no' =>$this->request->getVar('personal_phone_no'),
                            'email_id' => $this->request->getVar('email_id'),
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                            'updated_on' =>date('Y-m-d H:i:s')
                       ];
                $report_to = $this->request->getVar('report_to');
                if($report_to==""){
                    $input['report_to'] = NULL;
                }
                else
                {
                    $input['report_to'] = $report_to;
                }

                $id = $this->request->getVar('id');
                $input['id'] = $id;
                //First Name
                $emp_name = $this->request->getVar('emp_name');
                $emp_name = trim(ucwords($emp_name));
                //Middle Name
                $middle_name =$this->request->getVar('middle_name');
                $middle_name = trim(ucwords($middle_name));
                //Last Name
                $last_name = $this->request->getVar('last_name');
                 $last_name = trim(ucwords($last_name));
                //Gaurdian Name
                 $guardian_name =$this->request->getVar('guardian_name');
                 $guardian_name = trim(ucwords($guardian_name));
                //Input Field
                $input['emp_name'] = $emp_name;
                $input['middle_name'] = $middle_name;
                $input['last_name'] = $last_name;
                $input['guardian_name'] = $guardian_name;
                //Get user_mstr_id
                $getUserMstrIdByEmpDetailsId = $this->model_emp_details->getUserMstrIdByEmpDetailsId($id);
                $user_mstr_id = $getUserMstrIdByEmpDetailsId['user_mstr_id'];

                $updateEmpDetailsById = $this->model_emp_details->updateEmpDetailsById($input);
                if($updateEmpDetailsById)
                {
                    //Update Employee Image
                    $rules = ['photo_path' => 'uploaded[photo_path]|max_size[photo_path,1024]|ext_in[photo_path,png,jpg,gif]'];
                    if($this->validate($rules))
                    {
                        $file = $this->request->getFile('photo_path');
                        $extension = $file->getExtension();

                        if($file->isValid() && !$file->hasMoved()){
                            $newName = md5($id).".".$extension;
                            if($file->move(WRITEPATH.'uploads/emp_image',$newName))
                            {
                                $this->model_emp_details->uploadImage($newName,$id);
                            }
                            /*else
                            {
                                echo '<p>Fail to upload Image</p>';
                            }*/
                            }
                    }
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
                    if($user_type_mstr_id!=4 && $user_type_mstr_id!=5 && $user_type_mstr_id!=11)
                    {
                        //Set Status Zero For Update Record
                        $status = $this->model_ulb_permission->setStatusZeroForUpdateRecord($id);
                        $ulb_mstr_data = $this->request->getVar('ulb_mstr_id');
                        $len_ulb = sizeof($ulb_mstr_data);
                        for($i=0;$i<$len_ulb;$i++)
                        {
                            $ulb_mstr_id = $ulb_mstr_data[$i];
                            $isExists = $this->model_ulb_permission->checkIsExists($ulb_mstr_id,$id);
                            if($isExists) //Update Ulb Record
                            {
                                $updateUlbByEmpDetailsId = $this->model_ulb_permission->updateUlbByEmpDetailsId($id,$ulb_mstr_id);
                            }
                            else //Insert New Record
                            {

                                $ulb_mstr_id = $ulb_mstr_data[$i];
                                $created_on =date('Y-m-d H:i:s');
                                $ulb_permission = $this->model_ulb_permission->insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$id);
                            }
                        }
                        $sql = "SELECT RefreshAllWardPermissionMV();";
                        $this->dbSystem->query($sql);
                    }
                    else
                    {
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
                        $sql = "SELECT RefreshAllWardPermissionMV();";
                        $this->dbSystem->query($sql);
                    }
                    flashToast('empUpdate','Employee Updated!!!');
                    $this->response->redirect(base_url('EmpDetails/empList'));
                }
                else
                {
                    echo "<script>alert('Fail To Update Emplopee Details!!!');</script>";
                    return view('system/emp_details_add_update',$input);
                }
            }
        }
        else if(isset($id)) //Retrieve Data By Id
        {
            $data = $this->model_emp_details->getEmpDetailsById($id);
            $data['user_type_list'] = $user_type_list;
            return view('system/emp_details_add_update',$data);
        }
        else
        {
            $data['user_type_list'] = $user_type_list;
            return view('system/emp_details_add_update',$data);
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
    public function ajax_repotingList(){
        if($this->request->getMethod()=='post'){
            $option ="";
            $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
            $id = $this->request->getVar('id');
            $getReportToDataByEmpdetailsId = $this->model_emp_details->getReportToDataByEmpdetailsId($id);
          // print_r($getReportToDataByEmpdetailsId);
            $result = $this->model_view_user_hierarchy->ajax_reporting_data($user_type_mstr_id);
            if($result){
                $option = "<option value=''>--select--</option>";
                foreach ($result as $value){
                   $reportingList = $this->model_emp_details->reportingList($value['user_type_mstr_id']);
                    foreach ($reportingList as $values) {
                        $selected = "";
                        if($getReportToDataByEmpdetailsId){
                            if($getReportToDataByEmpdetailsId['report_to']==$values['id']) {
                                $selected = "selected";
                            }
                        }
                        $option .= "<option value='".$values['id']."' ".$selected.">".$values['emp_name']." ".$values['last_name']."/".$values['guardian_name']."/".$values['personal_phone_no']."</option>";
                    }
                }
                $response = ['response'=>true,'data'=>$option];
            }
            else
            {
                $response = ['response'=>false];
            }
            echo json_encode($response);
        }
        else
        {
            $response = ['response'=>false,'data'=>'Response is not post'];
            echo json_encode($response);
        }
    }
    public function ajax_ulbList(){
        if($this->request->getMethod()=='post'){
            //Retrieve data for Update
             $id = $this->request->getVar('id');
             $gateUlbDataByEmpdetailsId = $this->model_ulb_permission->gateUlbDataByEmpdetailsId($id);
            //Retrieve Single Data
            $result = $this->model_ulb_mstr->ulb_list();
            if($result){
                $div ="";
                foreach ($result as $key => $value){
                        $isCheck = "";
                    foreach ($gateUlbDataByEmpdetailsId as $val) {
                        if($value['id']==$val['ulb_mstr_id']){
                            $isCheck = "checked";
                        }
                    }
                    $div .= '<div class="col-sm-3">';
                        $div .= '<div class="checkbox">';
                            $div .= '<input type="checkbox" id="ulb_mstr_id'.$key.'" name="ulb_mstr_id[]" class="magic-checkbox" value="'.$value['id'].'" '.$isCheck.'/>';
                            $div .= '<label for="ulb_mstr_id'.$key.'">'.$value['ulb_name'].'</label>';
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
    public function lockEmployee($user_mstr_id){
        $lock = $this->model_user_mstr->lockEmployee($user_mstr_id);
        if($lock){
            flashToast('lock','Employee Services Locked!!!');
            $this->response->redirect(base_url('EmpDetails/empList'));
        }
        else{
            flashToast('fail_lock','Fail To Lock Employee Services!!!');
            $this->response->redirect(base_url('EmpDetails/empList'));
        }
    }
    public function unlockEmployee($user_mstr_id){
        $unlock = $this->model_user_mstr->unlockEmployee($user_mstr_id);
        if($unlock){
            flashToast('unlock','Employee Services Unlocked!!!');
            $this->response->redirect(base_url('EmpDetails/empList'));
        }
        else{
            flashToast('fail_unlock','Fail To Unlock Employee Services!!!');
            $this->response->redirect(base_url('EmpDetails/empList'));
        }
    }
}
?>

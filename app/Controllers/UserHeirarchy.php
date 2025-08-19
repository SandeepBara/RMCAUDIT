<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_user_type_mstr;
use App\Models\model_user_hierarchy;
use App\Models\model_view_user_hierarchy;
class UserHeirarchy extends AlphaController
{
    protected $dbsystem;
    protected $model_user_type_mstr;
    protected $model_user_hierarchy;
    protected $model_view_user_hierarchy;
	public function __construct(){
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
        $this->model_user_hierarchy = new model_user_hierarchy($this->dbSystem);
        $this->model_view_user_hierarchy = new model_view_user_hierarchy($this->dbSystem);
    }
	public function userHeirarchyList()
	{
       $data['userHierarchyList'] = $this->model_view_user_hierarchy->userHierarchyList();
       foreach ($data['userHierarchyList'] as $key => $value) {
           $user_type_mstr_id = $value['user_type_mstr_id'];
           if($result = $this->model_view_user_hierarchy->under_userHierarchyList($user_type_mstr_id)){
            $data['userHierarchyList'][$key]['under_user'] = $result;
           }
       }
      return view('system/user_heirarchy_list',$data);
	}
	public function add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        //User Type List
        $userTypeList = $this->model_user_type_mstr->userTypeList();
        $data['userTypeList'] = $userTypeList;
       // print_r($data['userTypeList']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") //Insert Operation 
            {
                //Data preparation For Insert
                $input = [
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                            'under_user_type_mstr_id' => $this->request->getVar('under_user_type_mstr_id'),
                            'created_on'=>date('Y-m-d H:i:s')
                        ];
                $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                if($type_mstr_id = $this->model_user_hierarchy->chackdata($user_type_mstr_id))
                {
                    echo"<script>alert('Record Already Exists!!!');</script>";
                    $input['userTypeList'] = $userTypeList;
                    return view('system/user_heirarchy_add_update',$input); 
                }
                else
                {
                    $under_user_type_mstr_id = $this->request->getVar('under_user_type_mstr_id');
                    $len = sizeof($under_user_type_mstr_id);
                    if( $len!=0)
                    {
                        $result ="";
                       /* print_r($input);
                            die();*/
                        for($i=0;$i<$len;$i++)
                        {
                            $input = [
                                        'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                                        'under_user_type_mstr_id' => $under_user_type_mstr_id[$i],
                                        'created_on'=>date('Y-m-d H:i:s')
                                    ];
                           $result = $this->model_user_hierarchy->insertUserTypedata($input);
                        }
                        if($result)
                        {
                            flashToast('user_heirarchy_list','User Hierarchy Generated!!!');
                            $this->response->redirect(base_url('UserHeirarchy/userHeirarchyList'));
                        }
                        else
                        {
                            echo"<script>alert('Fail To Insert Record');</script>";
                            return view('system/user_heirarchy_add_update',$input);   
                        }
                    }
                    else
                    {
                        echo"<script>alert('checked atleast one user Type');</script>";
                        return view('system/user_heirarchy_add_update',$input);
                    }
                }
            }
            else //Update Operation
            {    
                $input = [
                            'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                            'under_user_type_mstr_id' => $this->request->getVar('under_user_type_mstr_id'),
                            'created_on'=>date('Y-m-d H:i:s')
                        ];
                $user_type_mstr_id = $this->request->getVar('user_type_mstr_id'); 
                //Set Status Zero
                $this->model_user_hierarchy->updateStatusZero($user_type_mstr_id);
                $under_user_type_mstr_id = $this->request->getVar('under_user_type_mstr_id');
                $len = sizeof($under_user_type_mstr_id);
                $result ="";
                for($i=0;$i<$len;$i++)
                {
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id'); 
                    $under_user_type_mstr_id = $this->request->getVar('under_user_type_mstr_id')[$i];
                    //echo  $under_user_type_mstr_id;
                    
                    $isExists = $this->model_user_hierarchy->checkIsExists($user_type_mstr_id,$under_user_type_mstr_id);
                    $checked_id = $isExists['id'];
                    if($checked_id)
                    {
                        $result = $this->model_user_hierarchy->updateUserTypeData($user_type_mstr_id,$under_user_type_mstr_id);
                        echo $user_type_mstr_id;
                       echo $under_user_type_mstr_id;
                       echo "<br>";
                    }
                    else
                    {
                        $input = [
                                'user_type_mstr_id' => $this->request->getVar('user_type_mstr_id'),
                                'under_user_type_mstr_id' => $this->request->getVar('under_user_type_mstr_id')[$i],
                                'created_on'=>date('Y-m-d H:i:s')
                            ];
                         $result = $this->model_user_hierarchy->insertUserTypedata($input);
                    }
                    if($result){
                        flashToast('update','User Hierarchy Updated!!!');
                        $this->response->redirect(base_url('UserHeirarchy/userHeirarchyList'));
                    }
                    else{
                        echo"<script>alert('Fail To Update User Hierarchy!!!');</script>";
                        return view('system/user_heirarchy_add_update',$input);
                    }
                }
            }
        }
        else if(isset($id))
        {
            $user_type_mstr_id = $id; 
            //Retrive Single Data
            $data = $this->model_user_hierarchy->gateDataById($user_type_mstr_id);
            $under_user_list = $this->model_user_hierarchy->userHierarchyList($user_type_mstr_id);
            $data['userTypeList'] = $userTypeList;
			return view('system/user_heirarchy_add_update',$data);
        }
        else
        {
	       return view('system/user_heirarchy_add_update',$data);
        }
    }
    public function DeleteUserType($id=null)
    {
        $this->model_user_hierarchy->DeleteUserType($id);
        return $this->response->redirect(base_url('UserHeirarchy/userHeirarchyList'));
    }
	public function ajax_data(){
		if($this->request->getMethod()=='post'){
			$user_type_mstr_id = $this->request->getVar('user_type_mstr_id');

            //Retrive Single Data
            $under_user_list = $this->model_user_hierarchy->userHierarchyList($user_type_mstr_id);

			$result = $this->model_user_type_mstr->ajax_data($user_type_mstr_id);
			if($result){
				$div ="";
				foreach ($result as $key => $value) {
                    $isCheck = "";
                    foreach ($under_user_list as $val) {
                        if($value['id']==$val['under_user_type_mstr_id']){
                            $isCheck = "checked";
                        }
                    }
					$div .= '<div class="col-sm-3">';
	                    $div .= '<div class="checkbox">';
	                        $div .= '<input type="checkbox" id="under_user_type_mstr_id'.$key.'" name="under_user_type_mstr_id[]" class="magic-checkbox" value="'.$value['id'].'" '.$isCheck.' />';
	                        $div .= '<label for="under_user_type_mstr_id'.$key.'">'.$value['user_type'].'</label>';
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
    
}
?>

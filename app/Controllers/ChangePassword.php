<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_user_mstr;
use App\Models\model_emp_change_pass_dtl; 
use Exception;

class ChangePassword extends AlphaController
{
	protected $dbSystem;
	protected $model_user_mstr;
	protected $model_emp_change_pass_dtl;
	protected $encrypter ;

    public function __construct(){
		parent::__construct();
    	helper(['db_helper',"form_helper"]);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name);
        }
        $this->model_user_mstr = new model_user_mstr($this->dbSystem);
        $this->model_emp_change_pass_dtl = new model_emp_change_pass_dtl($this->dbSystem);
		$this->encrypter =  \Config\Services::encrypter();
		
        
    }

	public function oldPassChk_old() {
		$session = session();
		$emp_details = $session->get('emp_details');
		$id = $emp_details['user_mstr_id'];
		$old_user_pass = $this->request->getVar('old_user_pass');

		if($this->model_user_mstr->checkOldPassword($id, $old_user_pass)) {
			$response = ["response"=>true, "data"=>"OK"];
		} else {
			$response = ["response"=>false, "data"=>"Your old password is not match."];
		}

		echo json_encode($response);
	}

	public function oldPassChk() {
		$session = session();
		$emp_details = $session->get('emp_details');
		$id = $emp_details['user_mstr_id'];
		$old_user_pass = $this->request->getVar('old_user_pass');
		$userDtl = $this->model_user_mstr->where("id",$id)->get()->getFirstRow("array");
        $oldPass = $this->encrypter->decrypt(base64_decode($userDtl["user_pass"]));
		if($oldPass == $old_user_pass) {
			$response = ["response"=>true, "data"=>"OK"];
		} else {
			$response = ["response"=>false, "data"=>"Your old password is not match."];
		}

		echo json_encode($response);
	}

    public function changePwd() {
    	return view('property/dashboard');

    }

	public function changePassword() {
		$session = session();
		$emp_details = $session->get('emp_details');
		//print_r($emp_details);
		$id = $emp_details['user_mstr_id'];
		//Get Usr Details
		if($this->request->getMethod()=='post')
		{
			$password_policy = "^\S*(?=\S{5,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=.*[@#$%^\-+=()])\S*$";
			$rules=[
				'old_user_pass'=>'required',
				'new_pwd' =>"required|regex_match[/$password_policy/]",
				'cnfpassword' =>"required|matches[new_pwd]|regex_match[/$password_policy/]",
			];
			
			if(!$this->validate($rules))
			{
				$data= $this->request->getVar();
				flashToast('message',"No Valide Password Supply");
				$data['validator']=$this->validator;
				return view('users/change_password', $data);
			}				
			$input = [
				'old_user_pass' => $this->request->getVar('old_user_pass'),
				'new_pwd' => $this->request->getVar('new_pwd'),
				'cnfpassword' => $this->request->getVar('cnfpassword')
			];
			
	        $old_user_pass = $this->request->getVar('old_user_pass');
	        $cnfpassword = $this->request->getVar('cnfpassword');
	        $new_pwd = $this->request->getVar('new_pwd');
	        $created_on = date('Y-m-d H:i:s');
	        $input['created_on'] = $created_on;
	        $input['id'] = $id;
	        if($new_pwd==$cnfpassword)
	        {
		        
				$userDtl = $this->model_user_mstr->where("id",$id)->get()->getFirstRow("array");
				$oldPass = $this->encrypter->decrypt(base64_decode($userDtl["user_pass"]));
				// if($this->model_user_mstr->checkOldPassword($id, $old_user_pass))
				if($oldPass==$old_user_pass)
				{ 
					$new_pwd=base64_encode($this->encrypter->encrypt($new_pwd));
					if($this->model_user_mstr->changePassword($id,$new_pwd,$created_on))
					{
		           		$this->model_emp_change_pass_dtl->insertData($input);
		           		flashToast('message', 'Password changed successfully!!!');
						return redirect()->to(base_url('Login/logout'));
		            	// $this->response->redirect(base_url('Dashboard/welcome'));
		            }
					else
					{
						flashToast('message', 'Fail To Update Password!!!');
		            	return view('users/change_password', $input);
		            }
		        }
		        else
		        {
					flashToast('message', 'Incorrect old password you have entered!!!');
		            return view('users/change_password', $input);
	            }	
	        }
	        else
	        {
				flashToast('message', 'Confirm Password Does Not Match!!');
				return view('users/change_password', $input);
	        }
		}
		else
		{
			/*$data = $this->$model_user_mstr->getUserName($id);*/
			return view('users/change_password');
		}
	}



	public function resetPassword($user_mstr_id){
		if($this->request->isAJAX()){
			$response=[
				"status"=>false,
				"message"=>"Something went wrong!!!",
			];
			try{
				$new_pwd=base64_encode($this->encrypter->encrypt("12345"));
				$sql = "UPDATE tbl_user_mstr SET user_pass='$new_pwd' WHERE id='$user_mstr_id' ";
				$this->dbSystem->query($sql);
				$response["status"]=true;
				$response["message"]='Password Reset Successfully !!!';
			}catch(Exception $e){

			}
			return json_encode($response);
		}
	}
}
?>

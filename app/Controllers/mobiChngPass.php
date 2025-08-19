<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_user_mstr;
use App\Models\model_emp_change_pass_dtl; 

class mobiChngPass extends MobiController
{
	protected $dbSystem;
	protected $model_user_mstr;
	protected $model_emp_change_pass_dtl;
    protected $encrypter ;
    public function __construct(){
		parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_user_mstr = new model_user_mstr($this->dbSystem);
        $this->model_emp_change_pass_dtl = new model_emp_change_pass_dtl($this->dbSystem);
        $this->encrypter =  \Config\Services::encrypter();
        
    }
    public function changePwd() {
		$session = session();
        $emp_details = $session->get('emp_details');
		$user_mstr_id = $emp_details['user_mstr_id'];
		if($this->request->getMethod()=='post') {
            
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $created_on = date('Y-m-d H:i:s');

            $errMsg = [];
            if (!isset($inputs['old_password']) || $inputs['old_password']=="") {
                $errMsg['old_password'] = "you must enter old password.";
            }
            if (!isset($inputs['new_password']) || $inputs['new_password']=="") {
                $errMsg['new_password'] = "you must enter new password.";
            }
            if (!isset($inputs['confirm_password']) || $inputs['confirm_password']=="") {
                $errMsg['confirm_password'] = "you must enter confirm password.";
            }
            if (isset($inputs['new_password']) && $inputs['new_password']!="" && isset($inputs['confirm_password']) && $inputs['confirm_password']!="") {
                if  ($inputs['new_password']!=$inputs['confirm_password']) {
                    $errMsg['confirm_password'] = "confirm password does not match.";
                }
            }
            $userDtl = $this->model_user_mstr->where("id",$user_mstr_id)->get()->getFirstRow("array");
            $oldPass = $this->encrypter->decrypt(base64_decode($userDtl["user_pass"]));
            if (empty($errMsg)) {
                if ($oldPass=!$inputs['old_password']) {
                    $errMsg['old_password'] = "old password does not match.";
                }
            }
            if (empty($errMsg)) {
                $inputs["new_password"]=base64_encode($this->encrypter->encrypt($inputs["new_password"]));
                if ($this->model_user_mstr->changePassword($user_mstr_id, $inputs['new_password'], $created_on)) {
                    $input = [
                        'id' => $user_mstr_id,
                        'old_user_pass' => $inputs['old_password'],
                        'created_on' => $created_on
                    ];
                    $this->model_emp_change_pass_dtl->insertData($input);
                    flashToast('changePwd','Password updated.');
                    $this->response->redirect(base_url('mobiChngPass/changePwd'));
                } else {
                    $errMsg['other'] = "Fail To Update Password.";
                    $data['errMsg'] = $errMsg;
                    return view('mobile/users/changePwd',$data);
                }
            } else {
                $data['errMsg'] = $errMsg;
                return view('mobile/users/changePwd',$data);
            }
		} else {
			return view('mobile/users/changePwd');
		}
	}
}
?>

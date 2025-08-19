<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DbSystem\TblOtp;

use App\Models\filePermission;
use App\Models\model_user_mstr;
use App\Models\model_emp_details;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ulb_permission;
use App\Models\model_login_details;
use App\Models\model_emp_change_pass_dtl;
use App\Models\model_menu_mstr;
use App\Models\model_view_ward_permission;
use CodeIgniter\HTTP\Response;
use DateTime;
use Exception;

class Login extends BaseController
{
	protected $dbSystem;
	protected $model_user_mstr;
	protected $model_emp_details;
	protected $model_view_ulb_permission;
	protected $model_login_details;
	protected $model_emp_change_pass_dtl;
	protected $model_menu_mstr;
	protected $model_ulb_mstr;
	protected $model_view_ward_permission;
	/*protected $model_ulb_employee_details;*/
	protected $filePermission;
	protected $encrypter ;
	protected $model_Otp;
	public function __construct()
	{
		
		helper(['db_helper', 'form', "cookie"]);
		if ($db_name = dbSystem()) {
			$this->dbSystem = db_connect($db_name);
		}
		$this->encrypter =  \Config\Services::encrypter();
		$this->model_user_mstr = new model_user_mstr($this->dbSystem);
		$this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_view_ulb_permission = new model_view_ulb_permission($this->dbSystem);
		$this->model_login_details = new model_login_details($this->dbSystem);
		$this->model_emp_change_pass_dtl = new model_emp_change_pass_dtl($this->dbSystem);
		$this->model_menu_mstr = new model_menu_mstr($this->dbSystem);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		/*$this->model_ulb_employee_details = new model_ulb_employee_details($this->dbSystem);*/
		$this->filePermission =  new filePermission($this->dbSystem);
		$this->model_Otp = new TblOtp($this->dbSystem);
	}


	public function refrechLoginCaptcha() {
		return loginCaptcha();
	}
	public function index_old()
	{		
		/*$emp =  !empty(session()->get('emp_details')) ? true : false;
		if ($emp) {
			if (!in_array(session()->get('emp_details')['user_type_mstr_id'], [5]))
				return redirect()->to(base_url('Dashboard/welcome'));
			elseif(in_array(session()->get('emp_details')['user_type_mstr_id'], [5]))
				return redirect()->to(base_url('mobi/home'));
			else
				return view('users/login');
		}*/
		if ($this->request->getMethod() == 'post') {

			try {
				$this->dbSystem->transBegin();
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$user_name = $inputs['user_name'];
				$user_pass = $inputs['user_pass'];
				$ip_address = $inputs['ip_address']?$inputs['ip_address']:getClientIp();
				$captcha_code = $inputs['captcha_code'];
				$loginCaptchaSession = cGetCookie("loginCaptchaSession");
				cDeleteCookie("loginCaptchaSession");
				$data = ['user_name' => $user_name, 'user_pass' => $user_pass];
				if ($loginCaptchaSession != null && $loginCaptchaSession == $captcha_code) {

					if ($user_mstr_id = $this->model_user_mstr->verifyUserNamePassMD5($data)) {

						$data['user_mstr_id'] = $user_mstr_id['id'];
						$emp_details = "";
						$emp_details = $this->model_emp_details->getLoginEmpDetails($data);
						$mobileUserTypeID = [5];
						if (!in_array($emp_details['user_type_mstr_id'], $mobileUserTypeID)) {
							if ($emp_details) {
								$data['emp_details_id'] = $emp_details['id'];
								$ulb_mstr = [0=>[
										"ulb_permission_id"=>1,
										"ulb_mstr_id"=>1,
										"ulb_name"=>"Ranchi Municipal Corporation",
										"short_ulb_name"=>"RMC"
									]
								];
								$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
								$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'ip_address' => $ip_address, 'token' => $token, 'created_on' => date('Y-m-d H:i:s')];
								if ($this->model_login_details->insertData($newPost)) {
									
									$emp_details['token'] = $token;
									$emp_details['ip_address'] = $ip_address;
									$emp_details['ulb_list'] = $ulb_mstr;

									
									$user_type_mstr_id = $emp_details['user_type_mstr_id'];
									if (!is_null($user_type_mstr_id)) {
										//$menuList = cache("menu_list_".$emp_details["user_type_mstr_id"]);
										$client = new \Predis\Client();
										$menuList = $client->get("menu_list_".$emp_details["user_type_mstr_id"]);
										if (!$menuList) {
											$menuList = $this->model_menu_mstr->getMenuMstrListByUserTypeMstrId($user_type_mstr_id);
											if ($menuList) {
												foreach ($menuList as $key => $value) {
													if ($value['parent_menu_mstr_id'] == 0) {
														$subMenuList = $this->model_menu_mstr->getMenuSubListByUserTypeMstrId($user_type_mstr_id, $value['id']);
														if ($subMenuList) {
															$menuList[$key]['sub_menu'] = $subMenuList;
															foreach ($subMenuList as $keyy => $valueSub) {
																$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $valueSub['id']);
																if ($subMenuList) {
																	$menuList[$key]['sub_menu'][$keyy]['link_menu'] = $linkMenuList;
																}
															}
														}
													}
												}
												foreach ($menuList as $key => $value) {
													$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $value['id']);
													if ($linkMenuList) {
														$menuList[$key]['link_menu'] = $linkMenuList;
													}
												}
												$client->set("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList));
												//cache()->save("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList), 14400);
											}
										}
									}
									$session = session();
									$session->set('emp_details', $emp_details);
									$session->set('login_token', $token);
									$session->set('ulb_dtl', getUlbDtl());
									$client = new \Predis\Client();
									$filePermission = null;//$client->get("filePermession_".$emp_details["user_type_mstr_id"]);
									if(!$filePermission)
									{
										$filePermission = $this->filePermission->getPermitedFile($emp_details['user_type_mstr_id']);
										
										$temp = (array)null;
										foreach ($filePermission as $key => $val) {
											$temp[strtoupper($val["class_name"])][] = $val;
										}
										$filePermission = $temp;
										$client->set("filePermession_" . $emp_details["user_type_mstr_id"], json_encode($filePermission));
									}
									$this->dbSystem->transCommit();
									return redirect()->to(base_url('Dashboard/welcome'));
								}else{
									//print_var($newPost);
								}
							} else {
								$data['errMsg'] = "Something wrong!!!";
								return view('users/login', $data);
							}
						} else {
							$data['errMsg'] = "Mobile user can't login !!!";
							return view('users/login', $data);
						}
					} else {
						$data['errMsg'] = "User Name & Password does not match.";
						return view('users/login', $data);
					}
				} else {
					$data['errMsg'] = "Captcha code does not match.";
					return view('users/login', $data);
				}
				if ($this->dbSystem->transStatus() === FALSE) {
					$this->dbSystem->transRollback();
				} else {
					$this->dbSystem->transCommit();
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$session = session();
			$session->destroy();
			return view('users/login');
		}
	}


	public function index()
	{		
		if ($this->request->getMethod() == 'post') {

			try {
				$this->dbSystem->transBegin();
				$inputs = arrFilterSanitizeString($this->request->getVar());
                $user_name = $inputs['user_name'];
                $user_pass = $inputs['user_pass'];
				$ip_address = $inputs['ip_address']?$inputs['ip_address']:getClientIp();
				$captcha_code = $inputs['captcha_code'];
				$loginCaptchaSession = cGetCookie("loginCaptchaSession");
				cDeleteCookie("loginCaptchaSession");
				$data = ['user_name' => $user_name, 'user_pass' => $user_pass];
				if ($loginCaptchaSession != null && $loginCaptchaSession == $captcha_code) {
					$userDtl = $this->model_user_mstr->where("user_name",$data["user_name"])->where("lock_status",0)->get()->getFirstRow("array");					
					if($userDtl){
						$pass = $this->encrypter->decrypt(base64_decode($userDtl["user_pass"]));
						if (md5($pass)==$data["user_pass"]) 
						{	
							$flags = $this->dbSystem->table("site_maintenance")->get()->getFirstRow("array");
							$isDoubleStepLogin = ($flags["tow_step_login"]??"f")=="t"?true:false;
							if($isDoubleStepLogin){
								$this->dbSystem->transRollback();
								return($this->twoStepLogin("WEB",$userDtl['id']));
							}
							$data['user_mstr_id'] = $userDtl['id'];
							$emp_details = "";
							$emp_details = $this->model_emp_details->getLoginEmpDetails($data);
							$mobileUserTypeID = [5];
							if (!in_array($emp_details['user_type_mstr_id'], $mobileUserTypeID)) {
								if ($emp_details) {
									$data['emp_details_id'] = $emp_details['id'];
									$ulb_mstr = [0=>[
											"ulb_permission_id"=>1,
											"ulb_mstr_id"=>1,
											"ulb_name"=>"Ranchi Municipal Corporation",
											"short_ulb_name"=>"RMC"
										]
									];
									$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
									$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'ip_address' => $ip_address, 'token' => $token, 'created_on' => date('Y-m-d H:i:s')];
									if ($this->model_login_details->insertData($newPost)) {
										
										$emp_details['token'] = $token;
										$emp_details['ip_address'] = $ip_address;
										$emp_details['ulb_list'] = $ulb_mstr;
	
										
										$user_type_mstr_id = $emp_details['user_type_mstr_id'];
										if (!is_null($user_type_mstr_id)) {
											//$menuList = cache("menu_list_".$emp_details["user_type_mstr_id"]);
											$client = new \Predis\Client();
											$menuList = $client->get("menu_list_".$emp_details["user_type_mstr_id"]);
											if (!$menuList) {
												$menuList = $this->model_menu_mstr->getMenuMstrListByUserTypeMstrId($user_type_mstr_id);
												if ($menuList) {
													foreach ($menuList as $key => $value) {
														if ($value['parent_menu_mstr_id'] == 0) {
															$subMenuList = $this->model_menu_mstr->getMenuSubListByUserTypeMstrId($user_type_mstr_id, $value['id']);
															if ($subMenuList) {
																$menuList[$key]['sub_menu'] = $subMenuList;
																foreach ($subMenuList as $keyy => $valueSub) {
																	$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $valueSub['id']);
																	if ($subMenuList) {
																		$menuList[$key]['sub_menu'][$keyy]['link_menu'] = $linkMenuList;
																	}
																}
															}
														}
													}
													foreach ($menuList as $key => $value) {
														$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $value['id']);
														if ($linkMenuList) {
															$menuList[$key]['link_menu'] = $linkMenuList;
														}
													}
													$client->set("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList));
													//cache()->save("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList), 14400);
												}
											}
										}
										$session = session();
										$session->set('emp_details', $emp_details);
										$session->set('ulb_dtl', getUlbDtl());
										$session->set("login_token",$token);
										$client = new \Predis\Client();
										$filePermission = null;//$client->get("filePermession_".$emp_details["user_type_mstr_id"]);
										if(!$filePermission)
										{
											$filePermission = $this->filePermission->getPermitedFile($emp_details['user_type_mstr_id']);
											
											$temp = (array)null;
											foreach ($filePermission as $key => $val) {
												$temp[strtoupper($val["class_name"])][] = $val;
											}
											$filePermission = $temp;
											$client->set("filePermession_" . $emp_details["user_type_mstr_id"], json_encode($filePermission));
										}
										$this->dbSystem->transCommit();
										return redirect()->to(base_url('Dashboard/welcome'));
									}else{
										//print_var($newPost);
									}
								} else {
									$data['errMsg'] = "Something wrong!!!";
									return view('users/login', $data);
								}
							} else {
								$data['errMsg'] = "Mobile user can't login !!!";
								return view('users/login', $data);
							}
						} else {
							$data['errMsg'] = "User Name & Password does not match.";
							return view('users/login', $data);
						}
					}else{
						$data['errMsg'] = "Invalid User Name";
						return view('users/login', $data);
					}
				} else {
					$data['errMsg'] = "Captcha code does not match.";
					return view('users/login', $data);
				}
				if ($this->dbSystem->transStatus() === FALSE) {
					$this->dbSystem->transRollback();
				} else {
					$this->dbSystem->transCommit();
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$session = session();
			$session->destroy();
			return view('users/login');
		}
	}

	public function logout()
	{
		$session = session();
		if ($session->has("emp_details")) {
			$emp_mstr = $session->get("emp_details");
	        $login_emp_details_id = $emp_mstr["id"];
			$currentSessionId = $session->get('session_id');
			archive_login_session($currentSessionId);
	        $client = new \Predis\Client();
			if ($client->get("get_permitted_ward".$login_emp_details_id)) {
				$client->del("get_permitted_ward".$login_emp_details_id);
			}
			$session->remove('emp_details');
			$session->destroy();
		}
		$session->remove('ulb_dtl');
		return redirect()->to(base_url('Login'));
	}

	public function ulbChnage($_id = null)
	{
		if (is_null($_id)) {
		} else {
			$data = ['ulb_mstr_id' => $_id];
			if ($ulb_dtl = $this->model_ulb_mstr->getULBDetailsByMD5Id($data)) {
				$session = session();
				$session->set('ulb_dtl', $ulb_dtl);
				return redirect()->to($_SERVER['HTTP_REFERER']);
				return redirect()->to(base_url('Dashboard'));
			} else {
			}
		}
	}

	public function mobi_old() {
		
		/* $emp =  !empty(session()->get('emp_details')) ? true : false;//print_var(session()->get('emp_details'));die;
		if ($emp) {
			if(in_array(session()->get('emp_details')['user_type_mstr_id'], [4,5,7,13]))
				return redirect()->to(base_url('mobi/home'));
			elseif(!in_array(session()->get('emp_details')['user_type_mstr_id'], [5,20]))
				return redirect()->to(base_url('Dashboard/welcome'));
			else
				return view('mobile/login');			
		} */

		$data = (array)null;
		if ($this->request->getMethod() == 'post') {
			try {
				$this->dbSystem->transBegin();
				$data = [
					'user_name' => $this->request->getVar('user_id'),
					'user_pass' => $this->request->getVar('password'),
					'ip_address' => $this->request->getVar('ip_address')?$this->request->getVar('ip_address'):getClientIp(),
				];
			
				if ($user_mstr_id = $this->model_user_mstr->verifyUserNamePass($data)) {					$data['user_mstr_id'] = $user_mstr_id['id'];
					if ($emp_details = $this->model_emp_details->getLoginEmpDetails($data)) {
						$data['emp_details_id'] = $emp_details['id'];
						
						$ulb_mstr = [0=>[
								"ulb_permission_id"=>1,
								"ulb_mstr_id"=>1,
								"ulb_name"=>"Ranchi Municipal Corporation",
								"short_ulb_name"=>"RMC"
							]
						];	
						$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
						$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'token' => 'none', 'ip_address' => $data['ip_address'], 'token'=>$token, 'created_on' => date('Y-m-d H:i:s')];
						if ($this->model_login_details->insertData($newPost)) {
							$emp_details['token'] = $token;
							$emp_details['ulb_list'] = $ulb_mstr;
							$emp_details['ip_address'] = $data['ip_address'];
							
							$user_type_mstr_id = $emp_details['user_type_mstr_id'];
							if ($user_type_mstr_id == 4 || $user_type_mstr_id == 5 || $user_type_mstr_id == 7 || $user_type_mstr_id == 13 || $user_type_mstr_id == 20) {
								$session = session();
								$session->set('emp_details', $emp_details);
								$session->set('login_token', $token);
								$session->set('ulb_dtl', getUlbDtl());
								$this->dbSystem->transCommit();
								return redirect()->to(base_url('mobi/home'));
							} else {
								$session = session();
								$session->destroy();
								$data['errMsg'] = "Only TC, TL, TD And ULB Tax Collector Are Permitted";
								return view('mobile/login', $data);
							}
						}
					} else {
						$session = session();
						$session->destroy();
						$data['errMsg'] = "Something wrong!!!";
						return view('mobile/login', $data);
					}
				} else {
					$session = session();
					$session->destroy();
					$data['errMsg'] = "User Name & Password does not match.";
					return view('mobile/login', $data);
				}
				if ($this->dbSystem->transStatus() === FALSE) {
					$this->dbSystem->transRollback();
				} else {
					$this->dbSystem->transCommit();
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$session = session();
			$session->destroy();
			return view('mobile/login');
		}
	}

	public function mobi() {
		$data = (array)null;
		if ($this->request->getMethod() == 'post') {
			try {
				$this->dbSystem->transBegin();
				$data = [
					'user_name' => $this->request->getVar('user_id'),
					'user_pass' => $this->request->getVar('password'),
					'ip_address' => $this->request->getVar('ip_address')?$this->request->getVar('ip_address'):getClientIp(),
				];
				$userDtl = $this->model_user_mstr->where("user_name",$data["user_name"])->where("lock_status",0)->get()->getFirstRow("array");	
				if($userDtl){
					$pass = $this->encrypter->decrypt(base64_decode($userDtl["user_pass"]));
					if ($pass==$data["user_pass"]) {
											
						$flags = $this->dbSystem->table("site_maintenance")->get()->getFirstRow("array");
						$isDoubleStepLogin = ($flags["tow_step_login"]??"f")=="t"?true:false;
						if($isDoubleStepLogin){
							$this->dbSystem->transRollback();
							return($this->twoStepLogin("MOBI",$userDtl['id']));
						}
						$data['user_mstr_id'] = $userDtl['id'];
						if ($emp_details = $this->model_emp_details->getLoginEmpDetails($data)) {
							$data['emp_details_id'] = $emp_details['id'];
							
							$ulb_mstr = [0=>[
									"ulb_permission_id"=>1,
									"ulb_mstr_id"=>1,
									"ulb_name"=>"Ranchi Municipal Corporation",
									"short_ulb_name"=>"RMC"
								]
							];	
							$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
							$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'token' => 'none', 'ip_address' => $data['ip_address'], 'token'=>$token, 'created_on' => date('Y-m-d H:i:s')];
							if ($this->model_login_details->insertData($newPost)) {
								$emp_details['token'] = $token;
								$emp_details['ulb_list'] = $ulb_mstr;
								$emp_details['ip_address'] = $data['ip_address'];
								
								$user_type_mstr_id = $emp_details['user_type_mstr_id'];
								if (in_array($user_type_mstr_id,[4,5,7,13,20])) {
									$session = session();
									$session->set('emp_details', $emp_details);
									$session->set('login_token', $token);
									$session->set('ulb_dtl', getUlbDtl());
									$this->dbSystem->transCommit();
									return redirect()->to(base_url('mobi/home'));
								} else {
									$session = session();
									$session->destroy();
									$data['errMsg'] = "Only TC, TL, TD And ULB Tax Collector Are Permitted";
									return view('mobile/login', $data);
								}
							}
						} else {
							$session = session();
							$session->destroy();
							$data['errMsg'] = "Something wrong!!!";
							return view('mobile/login', $data);
						}
					} else {
						$session = session();
						$session->destroy();
						$data['errMsg'] = "User Name & Password does not match.";
						return view('mobile/login', $data);
					}
				}else{
					$data['errMsg'] = "Invalid User Name";
					return view('mobile/login', $data);
				}
				if ($this->dbSystem->transStatus() === FALSE) {
					$this->dbSystem->transRollback();
				} else {
					$this->dbSystem->transCommit();
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$session = session();
			$session->destroy();
			return view('mobile/login');
		}
	}

	public function mobilogout()
	{
		$session = session();
		$session = session();
		if ($session->has("emp_details")) {
			$emp_mstr = $session->get("emp_details");
	        $login_emp_details_id = $emp_mstr["id"];
			$currentSessionId = $session->get('session_id');
			archive_login_session($currentSessionId);
	        $client = new \Predis\Client();
			if ($client->get("get_permitted_ward".$login_emp_details_id)) {
				$client->del("get_permitted_ward".$login_emp_details_id);
			}
		}
		$session->remove('emp_details');
		$session->remove('ulb_dtl');
		$session->destroy();
		return redirect()->to(base_url('Login/mobi'));
	}



	public function forGotPassword($from="WEB"){
		if($this->request->isAJAX()){
			$userDtl = $this->model_user_mstr->where("user_name",$this->request->getVar("user_name"))->get()->getFirstRow("array");
			$response =[
				"status"=>false,
				"message"=>"Invalid User Name",
			];
			if(!$userDtl){
				return json_encode($response);
			}
			$emp_details = $this->model_emp_details->getLoginEmpDetails(["user_mstr_id"=>$userDtl["id"]]);
			$purpose="Forgot Password";
			if($this->request->getVar("send_otp") && $this->request->getVar("user_name")){
				//send otp
				$otp =$this->model_Otp->generateOtp();
				$currentDateTime = new DateTime();
				$expireAt = (clone $currentDateTime)->modify('+10 minutes');
				$input=[
					"mobile_no"=>$emp_details["personal_phone_no"],
					"email"=>$emp_details["email_id"],
					"ref_table"=>"tbl_user_mstr",
					"ref_id"=>$userDtl["id"],
					"otp"=>$otp,
					"purpose"=>$purpose,
					"valid_upto"=>$expireAt->format("Y-m-d H:i:s"),
				];
				$emp_full_name = trim($emp_details["emp_name"]);
				
				$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR 0. This OPT is valid for 10 minutes only.";		
				$templateid = "1307161908198113240";
				$mobileRespons = send_sms($input["mobile_no"],$message,$templateid);
				$mobileOtpStatus = $mobileRespons["response"];
		
				$content="<h1>Dear $emp_full_name</h1><p>Your otp for reset password is <b>$otp</b>. Please Do not share this with any one. This is valid for 10 minutes only</p>";
				$emailOtpStatus= sendMail("sandeepbara830@gmail.com",$content,"Forgot Password");
				
				if (!$mobileOtpStatus && !$emailOtpStatus) {
					$response["message"]= "Sorry, OTP could not send . Please try again later";
				}else{
					$this->dbSystem->table($this->model_Otp->table)
					->where("mobile_no",$input["mobile_no"])
					->where("email",$input["email"])
					->where("ref_table",$input["ref_table"])
					->where("ref_id",$input["ref_id"])
					->where("purpose",$input["purpose"])
					->where("consume_on",null)
					->update(["status"=>0]);
					$id = $this->model_Otp->store($input);
					$response["id"]=$id;
					$response["status"]=true;
					$response["message"]=  "OTP sent on ".$input["mobile_no"]." and ".$input["email"]." successfully";
					if(!$id){
						$response["message"]="Server Error!!!";
					}				
				}
				
			}elseif($this->request->getVar("update_password")){
				$currentDateTime = new DateTime();
				$otp = $this->request->getVar("otp");
				$newPassword = $this->request->getVar("password");
				$conformPassword = $this->request->getVar("conform_password");
				$input=[
					"mobile_no"=>$emp_details["personal_phone_no"],
					"email"=>$emp_details["email_id"],
					"ref_table"=>"tbl_user_mstr",
					"ref_id"=>$userDtl["id"],
					"otp"=>$otp,
					"purpose"=>$purpose,
				];
				$otp = $this->model_Otp->where($input)->get()->getFirstRow("array");
				if($newPassword!=$conformPassword){
					$response["message"]="New Password and Conform Password Not Match";
				}
				elseif(!$otp){
					$response["message"]="Invalid OTP";
				}elseif($otp["valid_upto"]< $currentDateTime->format("Y-m-d H:i:s")){
					$response["message"]="OTP Is Expired";
				}else{
					$this->model_Otp->updateData($otp["id"],["status"=>0,"consume_on"=>$currentDateTime->format("Y-m-d H:i:s")]);
					$hash = base64_encode($this->encrypter->encrypt($newPassword));
					$this->dbSystem->table("tbl_user_mstr")
						->where("id", $userDtl["id"])
						->update(["user_pass" => $hash]);
						$response["status"]=true;
						$response["message"]="Password Change Successfully";
						$response["url"]=$from!="WEB"?base_url("Login/mobi"):base_url("Login/index");
				}
			}
			return json_encode($response);
		}
		return view("users/forgotPassword");
	}
	
	public function twoStepLogin($from,$userId){

		$userDtl = $this->dbSystem->table("tbl_user_mstr")->where("id",$userId)->get()->getFirstRow("array");
		$data["user_mstr_id"]=$userDtl["id"]??null;
		$emp_dtl = $this->model_emp_details->getLoginEmpDetails($data);
		if($userDtl && $emp_dtl){
			$otp = $this->generateOtp();
			$token=bin2hex(date("ymdHis") . "_" . $emp_dtl['id'] . "_" . rand(100, 999));
			$currentDateTime = new DateTime();
			$expireAt = (clone $currentDateTime)->modify('+30 minutes')->format("Y-m-d:H:i:s");
			$otpExpireAt = (clone $currentDateTime)->modify('+10 minutes')->format("Y-m-d:H:i:s");
			$input=[
				"token_valid_upto"=>$expireAt,
				"app_type"=>$from,
				"user_id"=>$userDtl["id"],
				"otp"=>$otp,
				"valid_upto"=>$otpExpireAt,
			];
			$this->dbSystem->table("tbl_login_token_otp")->insert($input);
			$insert_id=$this->dbSystem->insertID();
			$token=$token."_".$insert_id;
			$this->dbSystem->table("tbl_login_token_otp")->where("id",$insert_id)->update(["token"=>$token]);

			$emp_full_name = trim($emp_dtl["emp_name"]);
		
			$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR 0. This OPT is valid for 10 minutes only.";		
			$templateid = "1307161908198113240";
			$mobileRespons = send_sms($emp_dtl["personal_phone_no"],$message,$templateid);
			$mobileOtpStatus = $mobileRespons["response"];
	
			$content="<h1>Dear $emp_full_name</h1><p>Your otp for login is <b>$otp</b>. Please Do not share this with any one. This is valid for 10 minutes only</p>";
			$emailOtpStatus= sendMail("sandeepbara830@gmail.com",$content,"Login OTP");
			flashToast("message","OTP sent on ".$emp_dtl["personal_phone_no"]." and ".$emp_dtl["email_id"]." successfully");
			return redirect()->to(base_url('Login/otpBaseLogin?token='.$token));
		}else{
			return redirect()->back();
		}
	}

	private function generateOtp(){
		$otp = str_pad(rand(100000, 999999),6,"0");
        return $otp;
	}


	// public function
	public function otpBaseLogin(){
		
		$currentDateTime = new DateTime();
		$currenTimestamp = $currentDateTime->format("Y-m-d:H:i:s");
		$token = $this->request->getVar("token");
		$tokenDtl = $this->dbSystem->table("tbl_login_token_otp")->where("token",$token)->get()->getFirstRow("array");
		if(!$tokenDtl){
			return redirect()->back();
		}
		$data["user_mstr_id"]=$tokenDtl["user_id"]??null;
		$emp_dtl = $this->model_emp_details->getLoginEmpDetails($data);
		$expireAt = new DateTime($tokenDtl["token_valid_upto"]);
		$isValidToken = $currentDateTime < $expireAt;
		if(!$isValidToken){
			$this->dbSystem->table("tbl_login_token_otp")->where("id",$tokenDtl["id"])->delete();
			if($tokenDtl["app_type"]=="WEB"){
				return redirect()->to("Login/index");
			}
			elseif($tokenDtl["app_type"]=="MOBI"){
				return redirect()->to("Login/mobi");
			}
			return redirect()->back();
		}
		if($this->request->isAJAX() && $this->request->getVar("resendOtp")){
			$response=[
				"status"=>false,
				"message"=>"OTP Send"
			];
			$otpExpireAt = (clone $currentDateTime)->modify('+10 minutes')->format("Y-m-d:H:i:s");
			$otp = $this->generateOtp();
			$updataData=[
				"otp"=>$otp,
				"valid_upto"=>$otpExpireAt,
			];
			$this->dbSystem->table("tbl_login_token_otp")->where("id",$tokenDtl["id"])->update($updataData);
			$emp_full_name = trim($emp_dtl["emp_name"]);
		
			$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR 0. This OPT is valid for 10 minutes only.";		
			$templateid = "1307161908198113240";
			$mobileRespons = send_sms($emp_dtl["personal_phone_no"],$message,$templateid);
			$mobileOtpStatus = $mobileRespons["response"];
	
			$content="<h1>Dear $emp_full_name</h1><p>Your otp for login is <b>$otp</b>. Please Do not share this with any one. This is valid for 10 minutes only</p>";
			$emailOtpStatus= sendMail("sandeepbara830@gmail.com",$content,"Login OTP");
			$response["status"]=true;
			$response["mobileOtpStatus"]=$mobileOtpStatus;
			$response["emailOtpStatus"]=$emailOtpStatus;
			return json_encode($response);
		}	
		elseif ($this->request->getMethod()=="post") {
			try {
				$this->dbSystem->transBegin();
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$ip_address=$inputs["ip_address"]?$inputs["ip_address"]:getClientIp();
				$otpExpireAt = new DateTime($tokenDtl["valid_upto"]);
				$isValidOtp=$currentDateTime < $otpExpireAt;;
				if($inputs["otp"]==$tokenDtl["otp"] && $isValidOtp){
					$this->dbSystem->table("tbl_login_token_otp")->where("id",$tokenDtl["id"])->delete();
					if($tokenDtl["app_type"]=="WEB"){ /// WEB LOGIN
						$emp_details = $emp_dtl;
						$mobileUserTypeID = [5];
						if (!in_array($emp_details['user_type_mstr_id'], $mobileUserTypeID)) {
							if ($emp_details) {
								$data['emp_details_id'] = $emp_details['id'];
								$ulb_mstr = [0=>[
										"ulb_permission_id"=>1,
										"ulb_mstr_id"=>1,
										"ulb_name"=>"Ranchi Municipal Corporation",
										"short_ulb_name"=>"RMC"
									]
								];
								$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
								$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'ip_address' => $ip_address, 'token' => $token, 'created_on' => date('Y-m-d H:i:s')];
								if ($this->model_login_details->insertData($newPost)) {
									
									$emp_details['token'] = $token;
									$emp_details['ip_address'] = $ip_address;
									$emp_details['ulb_list'] = $ulb_mstr;
		
									
									$user_type_mstr_id = $emp_details['user_type_mstr_id'];
									if (!is_null($user_type_mstr_id)) {
										//$menuList = cache("menu_list_".$emp_details["user_type_mstr_id"]);
										$client = new \Predis\Client();
										$menuList = $client->get("menu_list_".$emp_details["user_type_mstr_id"]);
										if (!$menuList) {
											$menuList = $this->model_menu_mstr->getMenuMstrListByUserTypeMstrId($user_type_mstr_id);
											if ($menuList) {
												foreach ($menuList as $key => $value) {
													if ($value['parent_menu_mstr_id'] == 0) {
														$subMenuList = $this->model_menu_mstr->getMenuSubListByUserTypeMstrId($user_type_mstr_id, $value['id']);
														if ($subMenuList) {
															$menuList[$key]['sub_menu'] = $subMenuList;
															foreach ($subMenuList as $keyy => $valueSub) {
																$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $valueSub['id']);
																if ($subMenuList) {
																	$menuList[$key]['sub_menu'][$keyy]['link_menu'] = $linkMenuList;
																}
															}
														}
													}
												}
												foreach ($menuList as $key => $value) {
													$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $value['id']);
													if ($linkMenuList) {
														$menuList[$key]['link_menu'] = $linkMenuList;
													}
												}
												$client->set("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList));
												//cache()->save("menu_list_".$emp_details["user_type_mstr_id"], json_encode($menuList), 14400);
											}
										}
									}
									$session = session();
									$session->set('emp_details', $emp_details);
									$session->set('token', $token);
									$session->set('ulb_dtl', getUlbDtl());
									$client = new \Predis\Client();
									$filePermission = null;//$client->get("filePermession_".$emp_details["user_type_mstr_id"]);
									if(!$filePermission)
									{
										$filePermission = $this->filePermission->getPermitedFile($emp_details['user_type_mstr_id']);
										
										$temp = (array)null;
										foreach ($filePermission as $key => $val) {
											$temp[strtoupper($val["class_name"])][] = $val;
										}
										$filePermission = $temp;
										$client->set("filePermession_" . $emp_details["user_type_mstr_id"], json_encode($filePermission));
									}
									$this->dbSystem->transCommit();
									return redirect()->to(base_url('Dashboard/welcome'));
								}
							} else {
								$data['errMsg'] = "Something wrong!!!";
								return view('users/login', $data);
							}
						} else {
							$data['errMsg'] = "Mobile user can't login !!!";
							return view('users/login', $data);
						}
					}else{ ///Mobile Login
						$emp_details=$emp_dtl;
						if ($emp_details) {
							$data['emp_details_id'] = $emp_details['id'];
							
							$ulb_mstr = [0=>[
									"ulb_permission_id"=>1,
									"ulb_mstr_id"=>1,
									"ulb_name"=>"Ranchi Municipal Corporation",
									"short_ulb_name"=>"RMC"
								]
							];	
							$token = date("ymdHis") . "_" . $emp_details['id'] . "_" . rand(100, 999);
							$newPost = ['emp_details_id' => $emp_details['id'], 'device_type' => 'WEB', 'token' => 'none', 'ip_address' => $ip_address, 'token'=>$token, 'created_on' => date('Y-m-d H:i:s')];
							if ($this->model_login_details->insertData($newPost)) {
								$emp_details['token'] = $token;
								$emp_details['ulb_list'] = $ulb_mstr;
								$emp_details['ip_address'] = $ip_address;
								
								$user_type_mstr_id = $emp_details['user_type_mstr_id'];
								if (in_array($user_type_mstr_id,[4,5,7,13,20])) {
									$session = session();
									$session->set('emp_details', $emp_details);
									$session->set('token', $token);
									$session->set('ulb_dtl', getUlbDtl());
									$this->dbSystem->transCommit();
									return redirect()->to(base_url('mobi/home'));
								} else {
									$session = session();
									$session->destroy();
									$data['errMsg'] = "Only TC, TL, TD And ULB Tax Collector Are Permitted";
									return view('mobile/login', $data);
								}
							}
						} else {
							$session = session();
							$session->destroy();
							$data['errMsg'] = "Something wrong!!!";
							return view('mobile/login', $data);
						}
					}

				}else{
					flashToast("message","Invalid OTP");
					return redirect()->back();
				}
			} catch (Exception $e) {
				$this->dbSystem->transRollback();
				return view('users/otpLogin');
			}
		}else {
			$data["from"]=$tokenDtl["app_type"];
			return view('users/otpLogin');
		}		
	}
	

	// public function hashPasswordAll($id="All"){
	// 	set_time_limit(1500);
	// 	$userDtls = $this->model_user_mstr;
	// 	if($id!="All"){
	// 		$userDtls->where("id",$id);
	// 	}
	// 	$userDtls = $userDtls->orderBy("id","ASC")->get()->getResultArray();
		
	// 	foreach($userDtls as $user){
	// 		$hash = base64_encode($this->encrypter->encrypt($user["user_pass"]));
	// 		$this->dbSystem->table("tbl_user_mstr")
	// 			->where("id", $user["id"])
	// 			->update(["hash_pass" => $hash]);
	// 			print_var($user["id"]);
	// 	}
	// 	die("complete");
	// }
}
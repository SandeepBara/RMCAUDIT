<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_user_type_mstr;

class User_Type extends AlphaController
{
    protected $dbSystem;
    protected $model_user_type_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
    }
	public function index()
	{ 
        $data['posts'] = $this->model_user_type_mstr->user_type_list();
       return view('master/user_type_list', $data);
	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'user_type'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'user_type' => $this->request->getVar('user_type'),
                        'user_for' => $this->request->getVar('user_for')
                    ];
                    $user_type = $this->request->getVar('user_type');
                    $data['data_exist']=$this->model_user_type_mstr->checkdata($data);
                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/user_type_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_user_type_mstr->insertData($data)){
							return $this->response->redirect(base_url('user_type'));
						}
						else{
							return view('master/user_type_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'user_type'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    //$id = $this->request->getVar('id');
                    $data = [
                        'user_type' => $this->request->getVar('user_type'),
                        'user_for' => $this->request->getVar('user_for'),
						'id' => $this->request->getVar('id')
                    ];
                    $user_type = $this->request->getVar('user_type');

				$data['data_exist']=$this->model_user_type_mstr->checkupdatedata($data);

                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/user_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_user_type_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('user_type'));
						}
						else{
                             echo "<script>alert('Fail To Update');</script>";
							return view('master/user_type_add_update',$data);
						}                       
                    }	
                }
            }
        }
        else if(isset($id))
        {
            //retrive data
			$data=$this->model_user_type_mstr->getdatabyid($id);
            return view('master/user_type_add_update',$data);

        }
        else
        {
            return view('master/user_type_add_update');

        }
    }
    public function delete($id=null)
    {
        $data['user']=$this->model_user_type_mstr->deletedataById($id);
        return $this->response->redirect(base_url('user_type'));
    }

    }
?>

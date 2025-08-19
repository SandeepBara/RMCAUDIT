<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_usage_type_mstr;

class Usage_Type extends AlphaController
{
     protected $db;
    protected $model_usage_type_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_usage_type_mstr->getUsageTypeList();

        return view('master/usage_type_list', $data);

	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'usage_type'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'usage_type' => $this->request->getVar('usage_type'),
                        'usage_code' => $this->request->getvar('usage_code')
                    ];
                    $data['data_exist']=$this->model_usage_type_mstr->checkdata($data);
                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/usage_type_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_usage_type_mstr->insertData($data)){
							return $this->response->redirect(base_url('usage_type'));
						}
						else{
							return view('master/usage_type_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'usage_type'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'usage_type' => $this->request->getVar('usage_type'),
                        'usage_code' => $this->request->getVar('usage_code'),
						'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->model_usage_type_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/usage_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_usage_type_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('usage_type'));
						}
						else{
							return view('master/usage_type_add_update',$data);
						}                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['usage']=$this->model_usage_type_mstr->getdatabyid($id);
            return view('master/usage_type_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/usage_type_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['usage']=$this->model_usage_type_mstr->deletedataById($id);
        return $this->response->redirect(base_url('usage_type'));
    }

    }
?>

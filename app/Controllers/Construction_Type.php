<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_const_type_mstr;

class Construction_Type extends AlphaController
{
    protected $db;
    protected $model_floor_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_const_type_mstr->getConstTypeList();

        return view('master/construction_type_list', $data);

	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'construction_type'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'construction_type' => $this->request->getVar('construction_type')
                    ];
                    $data['data_exist']=$this->model_const_type_mstr->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/construction_type_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_const_type_mstr->insertData($data)){
							return $this->response->redirect(base_url('construction_type'));
						}
						else{
							return view('master/construction_type_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'construction_type'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'construction_type' => $this->request->getVar('construction_type'),
                        'id' => $this->request->getVar('id')
                    ];
                     $data['data_exist']=$this->model_const_type_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/construction_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_const_type_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('construction_type'));
						}
						else{
							return view('master/construction_type_add_update',$data);
						}                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            //$data['ulb']=$this->model->getbyid($id);
            $data['construction']=$this->model_const_type_mstr->getdatabyid($id);
            return view('master/construction_type_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/construction_type_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['construction']=$this->model_const_type_mstr->deletedataById($id);
        return $this->response->redirect(base_url('construction_type'));
    }

    }
?>

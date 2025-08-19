<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_prop_type_mstr;

class Property_Type extends AlphaController
{
    protected $db;
    protected $model_prop_type_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_prop_type_mstr->getPropTypeList();

        return view('master/property_type_list', $data);

	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'property_type'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'property_type' => $this->request->getVar('property_type')
                    ];
                     $data['data_exist']=$this->model_prop_type_mstr->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/property_type_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_prop_type_mstr->insertData($data)){
							return $this->response->redirect(base_url('property_type'));
						}
						else{
							return view('master/property_type_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'property_type'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'property_type' => $this->request->getVar('property_type'),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->model_prop_type_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/property_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_prop_type_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('property_type'));
						}
						else{
							return view('master/property_type_add_update',$data);
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
            $data['property']=$this->model_prop_type_mstr->getdatabyid($id);
            return view('master/property_type_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/property_type_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['property']=$this->model_prop_type_mstr->deletedataById($id);
        return $this->response->redirect(base_url('property_type'));
    }

    }
?>

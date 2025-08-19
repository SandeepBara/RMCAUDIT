<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_floor_mstr;

class Floor extends AlphaController
{
    protected $db;
    protected $model_floor_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_floor_mstr = new model_floor_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_floor_mstr->getFloorList();

        return view('master/floor_list', $data);
        
	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'floor_name'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'floor_name' => $this->request->getVar('floor_name')
                    ];
                    $data['data_exist']=$this->model_floor_mstr->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/floor_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_floor_mstr->insertData($data)){
							return $this->response->redirect(base_url('floor'));
						}
						else{
							return view('master/floor_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'floor_name'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'floor_name' => $this->request->getVar('floor_name'),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->model_floor_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/floor_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_floor_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('floor'));
						}
						else{
							return view('master/floor_add_update',$data);
						}                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['floor']=$this->model_floor_mstr->getdatabyid($id);
            return view('master/floor_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/floor_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['floor']=$this->model_floor_mstr->deletedataById($id);
        return $this->response->redirect(base_url('floor'));
    }

    }
?>

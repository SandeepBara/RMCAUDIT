<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_occupancy_type_mstr;

class Occupancy_Type extends AlphaController
{
    protected $db;
    protected $model_occupancy_type_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
        return view('master/occupancy_type_list', $data);

	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'occupancy_name'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'occupancy_name' => $this->request->getVar('occupancy_name'),
                        'mult_factor' => $this->request->getVar('mult_factor')
                    ];
                    $data['data_exist']=$this->model_occupancy_type_mstr->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/occupancy_type_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_occupancy_type_mstr->insertData($data)){
							return $this->response->redirect(base_url('occupancy_type'));
						}
						else{
							return view('master/occupancy_type_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'occupancy_name'=>'required',
                    'mult_factor'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'occupancy_name' => $this->request->getVar('occupancy_name'),
                        'mult_factor' => $this->request->getVar('mult_factor'),
                        'id' => $this->request->getVar('id')
                    ];
                     $data['data_exist']=$this->model_occupancy_type_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/occupancy_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_occupancy_type_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('occupancy_type'));
						}
						else{
							return view('master/occupancy_type_add_update',$data);
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
            $data['occupancy']=$this->model_occupancy_type_mstr->getdatabyid($id);
            return view('master/occupancy_type_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/occupancy_type_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['occupancy']=$this->model_occupancy_type_mstr->deletedataById($id);
        return $this->response->redirect(base_url('occupancy_type'));
    }

    }
?>

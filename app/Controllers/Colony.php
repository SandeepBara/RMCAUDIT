<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_colony_type_mstr;

class Colony extends AlphaController
{
    protected $db;
    protected $model_colony_type_mstr;
    public function __construct()
    {        
        parent::__construct();
     	helper(['db_helper']);
        if($db_name = dbConfig("property"))
        {
            $this->db = db_connect($db_name);
        }
        $this->model_colony_type_mstr = new model_colony_type_mstr($this->db);

    }


	public function colonylist()
	{
        $data['posts'] = $this->model_colony_type_mstr->getColonyList();
        return view('master/colony', $data);
	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);

        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                            'colony_name'=> 'required',
                            'colony_address'=> 'required',
                        ];
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                    
                }
                else
                {
                    //store the data
                    $data = [
                        'colony_name' => $this->request->getVar('colony_name'),
                        'colony_address' => $this->request->getVar('colony_address'),
                    ];
                    $data['data_exist']=$this->model_colony_type_mstr->checkdata($data);
                    
                    if($data['data_exist'])
                    {
                        flashToast('message', "Data Already Exists");
                        
                        return view('master/colony_add_update', $data);
                    }
                    else
                    {
                        if($insert_last_id = $this->model_colony_type_mstr->insertData($data))
                        {
                            flashToast('message', "Data Inserted Successfully");
                            return $this->response->redirect(base_url('Colony/colonylist'));
                        }
                        else
                        {
                            
                            return view('master/colony', $data);
                        }
                    }
                }
            }
            else
            {
                //update code
                $rules=[
                  'colony_name'=>'required',
                  'colony_address'=>'required',
                ];
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                                'colony_name' => $this->request->getVar('colony_name'),
                                'colony_address' => $this->request->getVar('colony_address'),
                                'id' => $this->request->getVar('id')
                            ];
                    $data['data_exist']=$this->model_colony_type_mstr->checkupdatedata($data);
                    if($data['data_exist'])
                    {
                        flashToast('message', 'Data Already Exists');
                        return view('master/ownership_type_add_update', $data);
                    }
                    else
                    {
                        if($updaterow = $this->model_colony_type_mstr->updatedataById($data))
                        {
                            flashToast('message', "Data Updated  Successfully.");
                         	return $this->response->redirect(base_url('Colony/colonylist'));
                        }
                        else
                        {
                            return view('master/colony_add_update',$data);
                        }
                    }
                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['colony']=$this->model_colony_type_mstr->getdatabyid($id);
            
            return view('master/colony_add_update', $data);

        }
        else
        {
            $data['title']="Add";
            return view('master/colony_add_update',$data);
        }
    }

    public function delete($id=null)
    {
        $data['usage']=$this->model_colony_type_mstr->deletedataById($id);
        flashToast('colony', "Data Deleted  Successfully.");
        return $this->response->redirect(base_url('Colony/colonylist'));
    }


    }
?>

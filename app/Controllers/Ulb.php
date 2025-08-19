<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UlbModel;

class Ulb extends BaseController
{
    public function __construct()
    {
        helper(['form']);
        $this->model = new UlbModel();
    }
	public function index()
	{
        //$data['posts'] = $this->model->getAll();
        //print_r($data);
        $data['posts'] = $this->model->orderBy('_id', 'DESC')->findAll();

        echo view('layout_vertical/header');
        echo view('ulb_list', $data);
        echo view('layout_vertical/footer');
	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'ulb_name'=>'required|min_length[3]|max_length[20]',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'ulb_name' => $this->request->getVar('ulb_name')
                    ];
                    $ulb_name = $this->request->getVar('ulb_name');
                    $data['data_exist']=$this->model->where('ulb_name',$ulb_name)->findAll();

                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        echo view('layout_vertical/header');
                        echo view('ulb_add_update',$data);
                        echo view('layout_vertical/footer');
                    }
                    else{
                        $this->model->createNew($data);
                        return $this->response->redirect(base_url('public/ulb'));
                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'ulb_name'=>'required|min_length[3]|max_length[20]',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'ulb_name' => $this->request->getVar('ulb_name')
                    ];
                    $ulb_name = $this->request->getVar('ulb_name');
                    $data['data_exist']=$this->model->where('_id!=',$id)->where('ulb_name',$ulb_name)->findAll();

                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        echo view('layout_vertical/header');
                        echo view('ulb_add_update',$data);
                        echo view('layout_vertical/footer');
                    }
                    else{
                        $this->model->updatedata($id,$data);
                    return $this->response->redirect(base_url('public/ulb'));
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            //$data['ulb']=$this->model->getbyid($id);
            $data['ulb']=$this->model->where('_id',$id)->first();
            echo view('layout_vertical/header');
            echo view('ulb_add_update',$data);
            echo view('layout_vertical/footer');
        }
        else
        {
            $data['title']="Add";
            echo view('layout_vertical/header');
            echo view('ulb_add_update',$data);
            echo view('layout_vertical/footer');
        }
    }
    public function delete($id=null)
    {
        $data['ulb']=$this->model->deletebyid($id);
        return $this->response->redirect(base_url('public/ulb'));
    }

    }
?>

<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MasterModel;

class master extends BaseController
{
	public function __construct()
    {
        helper(['form']);
        $this->model = new MasterModel();
    }
	public function wardList()
	{
		
        $data['posts'] = $this->model->orderBy('id', 'AESC')->findAll();
		echo view('layout_vertical/header');
        echo view('ward_list', $data);
        echo view('layout_vertical/footer');
	}
    
   /* public function ward_add_update()
	{
		
		echo view('layout_vertical/header');
        echo view('ward_add_update');
        echo view('layout_vertical/footer');
	}*/
	
	  public function ward_add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
               
                    //store the data
                    $data = [
                        'ward_no' => $this->request->getVar('ward_no')
                    ];
                    $this->model->addward($data);
					return $this->response->redirect(base_url('public/master/wardList'));
                
            }
            else
            {
                //update code
               
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'ward_no' => $this->request->getVar('ward_no')
                    ];
                    $this->model->updateward($id,$data);
                    return $this->response->redirect(base_url('public/master/wardList'));
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            //$data['ulb']=$this->model->getbyid($id);
			//$data['wardid']=$id;
            $data['ward']=$this->model->where('id',$id)->first();
            echo view('layout_vertical/header');
			echo view('ward_add_update', $data);
			echo view('layout_vertical/footer');
        }
        else
        {
            $data['title']="Add";
            echo view('layout_vertical/header');
			echo view('ward_add_update');
			echo view('layout_vertical/footer');
        }
    }
    public function deleteward($id=null)
    {
        $data['ward']=$this->model->deletewardbyid($id);
        return $this->response->redirect(base_url('public/master/wardList'));
    }

    
	
	
	//--------------------------------------------------------------------

}

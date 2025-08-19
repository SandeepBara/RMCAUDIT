<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;

class Sub_item extends BaseController
{
	
	public function dashboardin()
	{
		$model = new PostModel();
		$data['posts'] = $model->findAll();
		echo view('layout_vertical/header');
        echo view('pages/sub_itemList', $data);
        echo view('layout_vertical/footer');
		//return view('pages/sub_itemList');
	}

	public function edit()
    {    
        $model = new PostModel();
        $data['posts'] = $model->orderBy('id', 'AESC')->findAll();
		echo view('layout_vertical/header');
        echo view('post', $data);
        echo view('layout_vertical/footer');
        //return view('post', $data);


	}
	public function store()
    {  

        helper(['form', 'url']);

        $model = new PostModel();

        $data = [

            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
			'pass' => $this->request->getVar('pass'),
            'mobile' => $this->request->getVar('mobile'),
			'course' => $this->request->getVar('course'),

            ];

        $save = $model->insert($data);

        return redirect()->to( base_url('public/Sub_item/edit') );
    }
	//--------------------------------------------------------------------

}

<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_tran_mode_mstr;

class Transaction_Mode extends AlphaController
{
    protected $db;
    protected $model_tran_mode_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_tran_mode_mstr->getTranModeList();

        return view('master/transaction_mode_list', $data);

	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                $rules=[
                    'transaction_mode'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'transaction_mode' => $this->request->getVar('transaction_mode')
                    ];
                    $data['data_exist']=$this->model_tran_mode_mstr->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/transaction_mode_add_update',$data);
                     }
                    else{
                        if($insert_last_id = $this->model_tran_mode_mstr->insertData($data)){
							return $this->response->redirect(base_url('transaction_mode'));
						}
						else{
							return view('master/transaction_mode_add_update',$data);
						}

                    }

                }
            }
            else
            {
                //update code
                $rules=[
                    'transaction_mode'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'transaction_mode' => $this->request->getVar('transaction_mode'),
						'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->model_tran_mode_mstr->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/transaction_mode_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_tran_mode_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('transaction_mode'));
						}
						else{
							return view('master/transaction_mode_add_update',$data);
						}                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['transaction']=$this->model_tran_mode_mstr->getdatabyid($id);
            return view('master/transaction_mode_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/transaction_mode_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['transaction']=$this->model_tran_mode_mstr->deletedataById($id);
        return $this->response->redirect(base_url('transaction_mode'));
    }

    }
?>

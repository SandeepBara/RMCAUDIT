<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use CodeIgniter\Session\Session;

class Ward extends AlphaController
{
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }
    public function wardList()
    {
        $Session = session();
        $ulb=$Session->get('ulb_dtl');
        $data['ward']= $this->model_ward_mstr->allWardList(["ulb_mstr_id"=> $ulb['ulb_mstr_id']]);
        return view('system/ward_list', $data);
    }
    public function add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        $ulb_list = $this->model_ulb_mstr->getUlbList();
        $data['ulb_list'] = $ulb_list;
       //print_r($ulb_list);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                    //store the data
                $data = [
                        'ward_no' => $this->request->getVar('ward_no'),
                        'ulb_mstr_id' => $this->request->getVar('ulb_mstr_id')
                    ];
                $ward_no = $this->request->getVar('ward_no');
                $ulb_mstr_id = $this->request->getVar('ulb_mstr_id');
                $data['data_exist']=$this->model_ward_mstr->checkdata($ward_no,$ulb_mstr_id);
                if($data['data_exist'])
                {
                    echo "<script>alert('Data Already Exists');</script>";
                    $data['ulb_list'] = $ulb_list;
                    return view('system/ward_add_update',$data);
                }
                else
                {
                    $input = [
                                'ulb_mstr_id'=>$ulb_mstr_id,
                                'ward_no'=>$ward_no
                            ];
                    if($insert_last_id = $this->model_ward_mstr->insertData($input))
                    {
                        return $this->response->redirect(base_url('Ward/wardList'));
				    }
					else
                    {
                        $data['ulb_list'] = $ulb_list;
						return view('system/ward_add_update',$data);
					}
                }
            }
            else
            {
                //update code
                    $data = [
                        'ward_no' => $this->request->getVar('ward_no'),
                        'ulb_mstr_id' => $this->request->getVar('ulb_mstr_id'),
						'id' => $this->request->getVar('id')
                    ];

				$data['data_exist']=$this->model_ward_mstr->checkupdatedata($data);
                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                            $data['ulb_list'] = $ulb_list;
                        return view('system/ward_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_ward_mstr->updatedataById($data)){
							return $this->response->redirect(base_url('Ward/wardList'));
						}
						else{
                             echo "<script>alert('Fail To Update');</script>";
							return view('system/ward_add_update',$data);
						}                       
                    }	
            }
        }
        else if(isset($id))
        {
            //retrive data
            
			$data=$this->model_ward_mstr->getdatabyid($id);
            $data['title']="Update";
            $data['ulb_list'] = $ulb_list;
            /*print_r($data['wardData']);*/
            return view('system/ward_add_update',$data);

        }
        else
        {
            return view('system/ward_add_update',$data);

        }
    }
    public function deleteward($id=null)
    {
        $data['user']=$this->model_ward_mstr->deleteward($id);
        return $this->response->redirect(base_url('Ward/wardList'));
    }

    }
?>

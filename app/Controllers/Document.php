<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_doc_mstr;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_view_doc_mstr;



class Document extends AlphaController
{
    protected $db;
    protected $model_doc_mstr;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
    protected $model_view_doc_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_view_doc_mstr = new model_view_doc_mstr($this->db);
    }
	public function index()
	{
        $data['posts'] = $this->model_view_doc_mstr->documentList();
        /*print_r($data['posts']);*/
        return view('master/document_list', $data);
	}
    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        $transferModeList = $this->model_transfer_mode_mstr->getTransferModeList();
        $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
        $data['propTypeList'] = $propTypeList;
        $data['transferModeList'] = $transferModeList;
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="") // insert
            {
                //store the data
                $input = [
                        'doc_name' => $this->request->getVar('doc_name'),
                        'doc_type' => $this->request->getVar('doc_type')
                    ];
                    $transfer_mode = $this->request->getVar('transfer_mode');
                    $property_type = $this->request->getVar('property_type');
                    $doc_type = $this->request->getVar('doc_type');
                    if($transfer_mode!="")
                    {
                        $input['doc_id'] = $transfer_mode;
                    }
                    else if($property_type!="")
                    {
                        $input['doc_id'] = $property_type;
                    }
                    else
                    {
                        $input['doc_id'] =0;
                    }
                    if($doc_type=="other")
                    {
                        $data['data_exist']=$this->model_doc_mstr->checkdata_other($input);
                       /* print_r($input);
                        die();*/
                        if($data['data_exist'])
                        {
                            echo "<script>alert('Data Already Exists');</script>";
                            $input['propTypeList'] = $propTypeList;
                            $input['transferModeList'] = $transferModeList;
                            return view('master/document_add_update',$input);
                        }
                        else
                        {
                             if($insert_last_id = $this->model_doc_mstr->insertData($input))
                            {
                                return $this->response->redirect(base_url('document'));
                            }
                            else
                            {
                                echo "<script>alert('Fail To Insert');</script>";
                                $input['propTypeList'] = $propTypeList;
                                $input['transferModeList'] = $transferModeList;
                                return view('master/document_add_update',$input);
                            }
                        }
                    }
                    else
                    {
                        $data['data_exist']=$this->model_doc_mstr->checkdata($input);
                        if($data['data_exist'])
                        {
                            echo "<script>alert('Data Already Exists');</script>";
                            $input['propTypeList'] = $propTypeList;
                            $input['transferModeList'] = $transferModeList;
                            return view('master/document_add_update',$input);
                        }
                        else
                        {
                            if($insert_last_id = $this->model_doc_mstr->insertData($input))
                            {
                                return $this->response->redirect(base_url('document'));
                            }
                            else
                            {
                                echo "<script>alert('Fail To Insert');</script>";
                                $input['propTypeList'] = $propTypeList;
                                $input['transferModeList'] = $transferModeList;
                                return view('master/document_add_update',$input);
                            }
                        }
                    }
            }
            else
            {
                //Update Code
                $input = [
                        'doc_name' => $this->request->getVar('doc_name'),
                        'doc_type' => $this->request->getVar('doc_type')
                    ];
                    $transfer_mode = $this->request->getVar('transfer_mode');
                    $property_type = $this->request->getVar('property_type');
                    $doc_type = $this->request->getVar('doc_type');
                    if($transfer_mode!="")
                    {
                        $input['doc_id'] = $transfer_mode;
                    }
                    else if($property_type!="")
                    {
                        $input['doc_id'] = $property_type;
                    }
                    else
                    {
                        $input['doc_id'] =0;
                    }
                    $id = $this->request->getVar('id');
                    $input['id'] = $id;
                    if($doc_type=="other")
                    {
                       $data['data_exist']=$this->model_doc_mstr->checkupdatedata_other($input);
                        if($data['data_exist'])
                        {
                            echo "<script>alert('Data Already Exists');</script>";
                            return view('master/document_add_update',$input);
                        }
                        else
                        {
                            if($updaterow = $this->model_doc_mstr->updatedataById($input))
                            {
                                return $this->response->redirect(base_url('document'));
                            }
                            else
                            {
                                echo "<script>alert('Fail To Update');</script>";
                                return view('master/document_add_update',$input);
                            } 
                        }                      
                    }
                    else
                    {
                        $data['data_exist']=$this->model_doc_mstr->checkupdatedata($input);
                        if($data['data_exist'])
                        {
                            echo "<script>alert('Data Already Exists');</script>";
                            return view('master/document_add_update',$input);
                        }
                        else
                        {
                            if($updaterow = $this->model_doc_mstr->updatedataById($input))
                            {
                                return $this->response->redirect(base_url('document'));
                            }
                            else
                            {
                                echo "<script>alert('Fail To Update');</script>";
                                return view('master/document_add_update',$input);
                            }                       
                        }
                    }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data=$this->model_doc_mstr->getdatabyid($id);
            $data['propTypeList'] = $propTypeList;
            $data['transferModeList'] = $transferModeList;
            return view('master/document_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/document_add_update',$data);

        }
    }
    public function delete($id=null)
    {
        $data['document']=$this->model_doc_mstr->deletedataById($id);
        return $this->response->redirect(base_url('document'));
    }

    }
?>

<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterMasterModel;


class WaterMaster extends AlphaController
{
    protected $db;
    protected $dbSystem;
    
    //protected $db_name;
    
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->master=new WaterMasterModel($this->db);

        
    }
    
    public function index()
    {
        $data['doc_list']=$this->master->document_list();

        return view('water/master/document_master_list',$data);
    }
    
    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {


                $rules=[

                    'document_type'=>'required',
                    'document_for'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/document_master',$data);
                }
                else
                {


                    //store the data
                    $data = [
                        'document_type' => $this->request->getVar('document_type'),
                         'document_for' => $this->request->getVar('document_for'),
                         
                       
                    ];
                    $document_type = $this->request->getVar('document_type');
                    $document_for=$this->request->getVar('document_for');

                    $data_exist=$this->master->checkdata($document_type,$document_for);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterMaster',$data);
                     }
                    else{

                       
                        $doc_mstr=array();
                        $doc_mstr['doc_for']=$this->request->getVar('document_for');
                        $doc_mstr['document_name']=$this->request->getVar('document_type');

                        if($insert_last_id = $this->master->insertData($doc_mstr)){
                            return $this->response->redirect(base_url('WaterMaster'));
                        }
                        else{
                           return $this->response->redirect(base_url('WaterMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'document_type'=>'required',
                    'document_for'=>'required',
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/document_master',$data);

                }
                else
                {


                    //store the data
                    $data = [
                        'document_type' => $this->request->getVar('document_type'),
                         'document_for' => $this->request->getVar('document_for'),
                         
                       
                    ];
                    $document_type = $this->request->getVar('document_type');
                    $document_for=$this->request->getVar('document_for');
                    $id=$this->request->getVar('id');

                    $data_exist=$this->master->checkdata($document_type,$document_for);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterMaster',$data);
                     }
                    else{

                       
                        $doc_mstr=array();
                        $doc_mstr['doc_for']=$this->request->getVar('document_for');
                        $doc_mstr['document_name']=$this->request->getVar('document_type');
                        $doc_mstr['id']=$id;

                        if($insert_last_id = $this->master->updateData($doc_mstr)){
                          
                            return $this->response->redirect(base_url('WaterMaster'));
                        }
                        else{
                         return $this->response->redirect(base_url('WaterMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->master->getData($id);
            //print_r($getData);
            $data['document_for']=$getData['doc_for'];
            $data['document_type']=$getData['document_name'];
            $data['id']=$getData['id'];
            return view('water/master/document_master',$data);
        }  
        else
        {   

            return view('water/master/document_master');
        }
    }

    public function delete($id)
    {
        $this->master->deleteData($id);
        return $this->response->redirect(base_url('WaterMaster'));
    }
    
    
}

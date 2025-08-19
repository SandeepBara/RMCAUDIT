<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_usage_type_dtl;
use App\Models\model_usage_type_mstr;
use App\Models\model_view_usage_type_dtl;

class Usage_Type_Detail extends AlphaController
{
    protected $db;
    protected $model_usage_type_dtl;
    protected $model_usage_type_mstr;
    protected $model_view_usage_type_dtl;
    public function __construct(){
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_view_usage_type_dtl = new model_view_usage_type_dtl($this->db);
    }
    public function usageTypeDetailsList()
    {
        $usageTypeDetailList = $this->model_view_usage_type_dtl->usageTypeDetailList();
        $data['usageTypeDetailList'] = $usageTypeDetailList;
     /*  print_r($data['usageTypeDetailList']);
        die();*/
        return view('master/usage_type_detail_list',$data);
    }
    public function add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        $usageList = $this->model_usage_type_mstr->getUsageTypeList();
        $data['usageList'] = $usageList;
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                    //store the data
                $input = [
                        'usage_type_mstr_id' => $this->request->getVar('usage_type_mstr_id'),
                        'mult_factor' => $this->request->getVar('mult_factor'),
                        'date_of_effect' =>date('Y-m-d')
                    ];
                $usage_type_mstr_id = $this->request->getVar('usage_type_mstr_id');              
                $data['data_exist']=$this->model_usage_type_dtl->checkdata($usage_type_mstr_id);
                if($data['data_exist'])
                {
                    echo "<script>alert('Data Already Exists');</script>";
                    $input['usageList'] = $usageList;
                    return view('master/usage_type_detail_add_update',$input);
                }
                else
                {
                    if($insert_last_id = $this->model_usage_type_dtl->insertData($input))
                    {
                      return $this->response->redirect(base_url('Usage_Type_Detail/usageTypeDetailsList'));
                    }
                    else
                    {
                        echo "<script>alert('Data Not Inserted!!');</script>";
                        $input['usageList'] = $usageList;
                        return view('master/usage_type_detail_add_update',$input);
                    }
                }
            }
            else
            {   
                //update the data
              $input = [
                        'usage_type_mstr_id' => $this->request->getVar('usage_type_mstr_id'),
                        'mult_factor' => $this->request->getVar('mult_factor'),
                        'id'=>$this->request->getVar('id')
                    ];
                $usage_type_mstr_id = $this->request->getVar('usage_type_mstr_id');
                $id = $this->request->getVar('id');
                    $data['data_exist']=$this->model_usage_type_dtl->checkupdatedata($id,$usage_type_mstr_id);
                    if($data['data_exist'])
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        $input['usageList'] = $usageList;
                        return view('master/usage_type_detail_add_update',$input);
                     }
                    else{
                        if($updaterow = $this->model_usage_type_dtl->updatedataById($input)){
                           return $this->response->redirect(base_url('Usage_Type_Detail/usageTypeDetailsList'));
                        }
                        else{
                            echo "<script>alert('Data Not Updated');</script>";
                            $input['usageList'] = $usageList;
                            return view('master/area_vacant_add_update',$input);
                        }                       
                    }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data=$this->model_usage_type_dtl->getdatabyid($id);
            $data['usageList'] = $usageList;
            return view('master/usage_type_detail_add_update',$data);
        }
        else
        {
            return view('master/usage_type_detail_add_update',$data);
        }
    }
    public function deleteUsageTypeDetail($id=null)
    {
        $this->model_usage_type_dtl->deleteUsageTypeDetail($id);
       return $this->response->redirect(base_url('Usage_Type_Detail/usageTypeDetailsList'));
    }
}
?>

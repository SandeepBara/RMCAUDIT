<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_road_type_mstr;

class Area_Vacant extends AlphaController
{
    protected $db;
    protected $model_arr_vacant_mstr;
    protected $model_road_type_mstr;
    public function __construct(){
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
    }
    public function areaVacantList()
    {
        $data['arrVacantList'] = $this->model_arr_vacant_mstr->arrVacantList();
       // print_r($data['arrVacantList']);
        return view('master/area_vacant_list',$data);
    }
    public function add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        $roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
        $data['roadTypeList'] = $roadTypeList;
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                    //store the data
                $input = [
                        'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                        'rate' => $this->request->getVar('rate'),
                        'date_of_effect' =>date('Y-m-d')
                    ];
                $road_type_mstr_id = $this->request->getVar('road_type_mstr_id');              
            $data['data_exist']=$this->model_arr_vacant_mstr->checkdata($road_type_mstr_id);
                if($data['data_exist'])
                {
                    echo "<script>alert('Data Already Exists');</script>";
                    $data['roadTypeList'] = $roadTypeList;
                    return view('master/area_vacant_add_update',$data);
                }
                else
                {

                    if($insert_last_id = $this->model_arr_vacant_mstr->insertData($input))
                    {
                      return $this->response->redirect(base_url('Area_Vacant/areaVacantList'));
                    }
                    else
                    {
                        echo "<script>alert('Data Not Inserted!!');</script>";
                        $data['roadTypeList'] = $roadTypeList;
                        return view('master/area_vacant_add_update',$data);
                    }
                }
            }
            else
            {   
                    //update the data
                $input = [
                        'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                        'rate' => $this->request->getVar('rate'),
                        'id' => $this->request->getVar('id')
                    ];
                $road_type_mstr_id = $this->request->getVar('road_type_mstr_id');
                $id = $this->request->getVar('id');
                    $data['data_exist']=$this->model_arr_vacant_mstr->checkupdatedata($id,$road_type_mstr_id);
                    if($data['data_exist'])
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        $data['roadTypeList'] = $roadTypeList;
                        return view('master/area_vacant_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_arr_vacant_mstr->updatedataById($input)){
                            return $this->response->redirect(base_url('Area_Vacant/areaVacantList'));
                        }
                        else{
                            echo "<script>alert('Data Not Updated');</script>";
                            $data['roadTypeList'] = $roadTypeList;
                            return view('master/area_vacant_add_update',$data);
                        }                       
                    }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data=$this->model_arr_vacant_mstr->getdatabyid($id);
            $data['roadTypeList'] = $roadTypeList;
            return view('master/area_vacant_add_update',$data);

        }
        else
        {
            return view('master/area_vacant_add_update',$data);
        }
    }
    public function deleteAreaVacant($id=null)
    {
       // echo $id;
        $this->model_arr_vacant_mstr->deleteAreaVacant($id);
        return $this->response->redirect(base_url('Area_Vacant/areaVacantList'));
    }
}
?>

<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_arr_building_mstr;
use App\Models\model_road_type_mstr;
use App\Models\ConstructionTypeModel;

class Area_Building extends AlphaController
{
    protected $db;
    protected $model_area_building_mstr;
    protected $model_road_type_mstr;
    protected $ConstructionTypeModel;
    public function __construct(){
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_area_building_mstr = new model_arr_building_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->ConstructionTypeModel = new ConstructionTypeModel($this->db);
    }
    public function areaBuildingList()
    {
        $data['areaBuildingList'] = $this->model_area_building_mstr->areaBuildingList();
    /*    print_r($data['areaBuildingList']);*/
        return view('master/area_building_list',$data);
    }
    public function add_update($id=null)
    {
        $data =(array)null;
        helper(['form']);
        $roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
        $constTypeList = $this->ConstructionTypeModel->getConstTypeList();
        $data['roadTypeList'] = $roadTypeList;
        $data['constTypeList'] = $constTypeList;
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
                    //store the data
                $input = [
                        'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                        'const_type_mstr_id' => $this->request->getVar('const_type_mstr_id'),
                        'given_rate' => $this->request->getVar('rate'),
                        'cal_rate' => $this->request->getVar('cal_rate'),
                        'date_of_effect' =>date('Y-m-d')
                    ];
                    
                $road_type_mstr_id = $this->request->getVar('road_type_mstr_id');
                $const_type_mstr_id = $this->request->getVar('const_type_mstr_id');
                  
            $data['data_exist']=$this->model_area_building_mstr->checkdata($road_type_mstr_id,$const_type_mstr_id);
                if($data['data_exist'])
                {
                    echo "<script>alert('Data Already Exists');</script>";
                    $data['roadTypeList'] = $roadTypeList;
                    $data['constTypeList'] = $constTypeList;
                    return view('master/area_building_add_update',$data);
                }
                else
                {
                    if($insert_last_id = $this->model_area_building_mstr->insertData($input))
                    {
                      /*echo "<script>alert('Record Inserted Successfully!!'); window.location.href = '".base_url."/Area_Building/areaBuildingList';</script>";*/
                      return $this->response->redirect(base_url('Area_Building/areaBuildingList'));
                    }
                    else
                    {
                        echo "<script>alert('Data Not Inserted!!');</script>";
                        $data['roadTypeList'] = $roadTypeList;
                        $data['constTypeList'] = $constTypeList;
                        return view('master/area_building_add_update',$data);
                    }
                }
            }
            else
            {   
                    //update the data
                $input = [
                        'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                        'const_type_mstr_id' => $this->request->getVar('const_type_mstr_id'),
                        'given_rate' => $this->request->getVar('rate'),
                        'cal_rate' => $this->request->getVar('cal_rate'),
                        'id' => $this->request->getVar('id')
                    ];
                $road_type_mstr_id = $this->request->getVar('road_type_mstr_id');
                $const_type_mstr_id = $this->request->getVar('const_type_mstr_id');
                $id = $this->request->getVar('id');
                    $data['data_exist']=$this->model_area_building_mstr->checkupdatedata($id,$road_type_mstr_id,$const_type_mstr_id);
                    if($data['data_exist'])
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        $data['roadTypeList'] = $roadTypeList;
                        $data['constTypeList'] = $constTypeList;
                        return view('master/area_building_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_area_building_mstr->updatedataById($input)){
                            return $this->response->redirect(base_url('Area_Building/areaBuildingList'));
                        }
                        else{
                            echo "<script>alert('Data Not Updated');</script>";
                            $data['roadTypeList'] = $roadTypeList;
                            $data['constTypeList'] = $constTypeList;
                            return view('master/area_building_add_update',$data);
                        }                       
                    }

                
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data=$this->model_area_building_mstr->getdatabyid($id);
            $data['roadTypeList'] = $roadTypeList;
            $data['constTypeList'] = $constTypeList;
            return view('master/area_building_add_update',$data);

        }
        else
        {
            return view('master/area_building_add_update',$data);
        }
    }
    public function deleteAreaBuilding($id=null)
    {
       // echo $id;
        $this->model_area_building_mstr->deleteAreaBuilding($id);
        return $this->response->redirect(base_url('Area_Building/areaBuildingList'));
    }
}
?>

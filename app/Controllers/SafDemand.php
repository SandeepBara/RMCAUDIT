<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_SafDemand;

class SafDemand extends AlphaController
{
    protected $db;
    protected $model_SafDemand;
     public function __construct(){
        parent::__construct();
     	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }

        $this->model_SafDemand = new model_SafDemand($this->db);
    }

	public function search_application_no()
	{
    $data =(array)null;
    helper(['form']);
    if($this->request->getMethod()=='post')
    {
      $data = [
          'application_no' => $this->request->getVar('application_no'),
       ];

       $data['data_exist']=$this->model_SafDemand->checkdata($data);
       //print_r($data['data_exist']);
       if($data['data_exist'])
           {

           $this->response->redirect(base_url('safDemand/saf_demand_details/'.md5($data['data_exist']['id'])));
            }
           else
           {
               echo "<script>alert('Application number not found!');</script>";
               return view('property/saf/saf_demand_search');
           }
    }
    else {
     return view('property/saf/saf_demand_search');
    }
 	}


    public function saf_demand_details($id=null)
    {
       if(isset($id))
      {
         //check application no. exist or not
      $data['data_exist']=$this->model_SafDemand->checkdata_dcrypt($id);

      if($data['data_exist'])
          {
            $data['saf_demand']=$this->model_SafDemand->getdata($data['data_exist']["application_no"]);
             foreach($data['saf_demand'] as $id)
            {
              $id = $id['id'];
            }
            $data['designation']=$this->model_SafDemand->getdesignation($id);
            $data['fy']=$this->model_SafDemand->getfy($id);
            return view('property/saf/saf_demand',$data);
           }
      else
      {
          echo "<script>alert('Application number not found!');</script>";
          return view('property/saf/saf_demand_search');
      }

      }
      if(isset($_POST['print_review'])) {
        $inputs = arrFilterSanitizeString($this->request->getVar());
        $data = $inputs;
        $data['demand'] = $this->model_SafDemand->getdemand($data['fy'],$data['quarter'],$data['id']);
        $data['penalty'] = $this->model_SafDemand->getpenalty($data['id']);
         return view('property/saf/saf_demand_print',$data);
      }
    }

//getting quarter
    public function get_quarter() {
      if($this->request->getMethod()=='post'){
        try{
          $inputs = arrFilterSanitizeString($this->request->getVar());
          if($quarter = $this->model_SafDemand->getquarter($inputs['ackn'],$inputs['fyid'],$inputs['slct'])) {
            $option = "";
          if(isset($inputs['slct'])) {
            $option = "<option>Select</option>";
            foreach ($quarter as $key => $value) {
                $option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
             }
          }
          else {
            foreach ($quarter as $key => $value) {
              if($value['qtr']=='4')
              {
                $option .= "<option value='".$value['qtr']."' selected>".$value['qtr']."</option>";

              }
              else {
                $option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";

              }            }
          }

            $response = ['response'=>true, 'data'=>$option];
            echo json_encode($response);

          } else {
            $response = ['response'=>false];
            echo json_encode($response);
          }
        }catch (Exception $e){

        }
      } else {
        $response = ['response'=>false];
        echo json_encode($response);
      }
    }

//getting demand
    public function get_demand() {
      if($this->request->getMethod()=='post'){
        try{
          $inputs = arrFilterSanitizeString($this->request->getVar());
          if($demand = $this->model_SafDemand->getdemand($inputs['fy'],$inputs['qtr'],$inputs['id'])) {
            //$response = ['response'=>true, 'data'=>$demand];
            echo json_encode($demand);

          } else {
            $response = ['response'=>false];
            echo json_encode($response);
          }
        }catch (Exception $e){

        }
      } else {
        $response = ['response'=>false];
        echo json_encode($response);
      }
    }


    }
?>

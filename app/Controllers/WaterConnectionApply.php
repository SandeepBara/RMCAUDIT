<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_application_details;
use App\Models\model_ward_mstr;

class WaterConnectionApply extends AlphaController
{
    protected $water;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_view_water_application_details;

    public function __construct()
    {
        parent::__construct();
        helper(['db_helper','form', 'utility_helper']);

        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_water_application_details = new model_view_water_application_details($this->water);
    }

    public function report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //$applyList = [];
        //Transaction Mode List
        // $data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        // //print_r($data);
        // if($this->request->getMethod()=='post')
        // {
        //     $data['from_date'] = $this->request->getVar('from_date');
        //     $data['to_date'] = $this->request->getVar('to_date');
        //     $data['ward_id'] = $this->request->getVar('ward_id');
        //     $data['panding_on']=$this->request->getVar('pending_on');
            
        //     $status=array();
        //     if($data['panding_on']!="All")
        //     {
        //         if($data['panding_on']=="Payment Done But Document Upload Is Pending")
        //         $status=array('payment_status'=>[1],'doc_status'=>[0]);

        //         if($data['panding_on']=="Payment Done And Document Done")
        //         $status=array('payment_status'=>[1],'doc_status'=>[1]);

                
        //         if($data['panding_on']=="Payment Is Pending But Document Upload Done")
        //         $status=array('payment_status'=>[0,2],'doc_status'=>[1]);

        //         if($data['panding_on']=="Payment Pending And Document Upload Pending")
        //         $status=array('payment_status'=>[0,2],'doc_status'=>[0]);
        //     }
            
        //     if($data['ward_id']!="")
        //     {
        //         $data['applyList'] = $this->model_view_water_application_details->applyList($data['from_date'], $data['to_date'], $data['ward_id'],$status);
        //         //$data['count']=
        //     }
        //     else
        //     {
        //         $data['applyList'] = $this->model_view_water_application_details->applyListByDate($data['from_date'], $data['to_date'],$status);
        //     }
        // }
        // else
        // {
        //     $data['from_date'] = date('Y-m-d');
        //     $data['to_date'] = date('Y-m-d');
        //     $data['applyList'] = $this->model_view_water_application_details->applyListByDate($data['from_date'], $data['to_date']);
        //     $data['panding_on']='All';
        // }
        //-----------------------------------------------//SS
        $data =(array)null;
        $Session = Session();
        $status=array();
        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }

        $data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
            //echo('remove');
        }
        $tempData=$Session->get('tempData');
        if($tempData)
        {
            $data['ward_id'] = $tempData['ward_id'];
            $data['from_date'] = $tempData['from_date'];
            $data['to_date'] = $tempData['to_date'];
            $data['pending_on'] = $tempData['pending_on'];
            
            $status=array();
            if($data['pending_on']!="All")
            {
                if($data['pending_on']=="Payment Done But Document Upload Is Pending")
                $status=array('payment_status'=>[1],'doc_status'=>[0]);

                // if($data['pending_on']=="Payment Done And Document Done")
                // $status=array('payment_status'=>[1],'doc_status'=>[1]);

                
                if($data['pending_on']=="Payment Is Pending But Document Upload Done")
                $status=array('payment_status'=>[0,2],'doc_status'=>[1]);
                
                if($data['pending_on']=="Payment Pending And Document Upload Pending")
                $status=array('payment_status'=>[0,2],'doc_status'=>[0]);
                //print_r($status);
            }
           
            if($data['ward_id']!="")
            {
                $data['applyList'] = $this->model_view_water_application_details->applyList($data['from_date'], $data['to_date'], $data['ward_id'],$status);
                
            }
            else
            {
                $data['applyList'] = $this->model_view_water_application_details->applyListByDate($data['from_date'], $data['to_date'],$status);
            }

        }

        return view('water/report/water_connection_apply', $data);
    }
}
?>

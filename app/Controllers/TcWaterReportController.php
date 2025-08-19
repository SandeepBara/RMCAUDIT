<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use App\Models\model_view_ward_permission;
use App\Models\waterConnectionReportModel;
use App\Models\model_emp_details;
use App\Models\model_ulb_mstr;

use Exception;


 class TcWaterReportController extends AlphaController
{   


    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    protected $ward_model;
    protected $water_report_model;
    protected $model_view_ward_permission;
    protected $waterConnectionReportModel;

    public function __construct()
    {   
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        

        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_report_model=new WaterReportModel($this->db);
        $this->model_view_ward_permission=new model_view_ward_permission($this->dbSystem);
        $this->waterConnectionReportModel=new waterConnectionReportModel($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);

    }

    function __destruct()
	{
		$this->db->close();
		$this->dbSystem->close();
	}
    
    public function report()
    {
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
        $ward="";
        $data['ward_id']='';
        $data['newWaterConnection']='';
        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);   
     
        if($this->request->getMethod()=='post')
        {   
            $data['to_date'] = $this->request->getVar('to_date');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['ward_id'] = $this->request->getVar('ward_id');
             
            if($data['ward_id']!="All")
            {
                $wherenew=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and connection_type_id = 1 and apply_from = 'TC' and ward_id=".$data['ward_id'];
                $whereregul=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and connection_type_id = 2 and apply_from = 'TC' and ward_id=".$data['ward_id'];
            }
            else
            {   
                $wherenew=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and connection_type_id = 1 and apply_from = 'TC' and ward_id in($wardPermission)";
                $whereregul=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and connection_type_id = 2 and apply_from = 'TC' and ward_id in($wardPermission)";
            }
            $data['newWaterConnection']=$this->waterConnectionReportModel->WaterConnection($wherenew);
            $data['regularization']=$this->waterConnectionReportModel->WaterConnection($whereregul);

        }
        return view('mobile/water/waterConnectionReport',$data);
    }
  
    public function wardWiseWaterConnection($from_date,$to_date,$ward_id,$status)
    {
        $data =(array)null;
        $Session = Session();

        $from_date = base64_decode($from_date);
        $to_date = base64_decode($to_date);
        $ward_id = base64_decode($ward_id);
         $status = base64_decode($status);
        $data['from_date'] = $from_date;            
        $data['to_date'] = $to_date;
        $data['status'] = $status;

        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
 
        $ward="";
        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);

        if($status == 1)//total new water connection
        {
            if($ward_id=="All")
            {
                 $where=" tbl_apply_water_connection.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_apply_water_connection.connection_type_id = 1 and tbl_apply_water_connection.apply_from = 'TC'";
                 $viewWardWhere = "view_ward_mstr.id in($wardPermission)";
            }
            else 
            {   
                $where=" tbl_apply_water_connection.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_apply_water_connection.connection_type_id = 1 and tbl_apply_water_connection.apply_from = 'TC'";
                $viewWardWhere = "view_ward_mstr.id =$ward_id";
            }
            $data['wcon'] = 'New Water Connection'; 

        }
        elseif($status == 2) //regulization
        {
            if($ward_id=="All"){
                  $where=" tbl_apply_water_connection.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_apply_water_connection.connection_type_id = 2 and tbl_apply_water_connection.apply_from = 'TC'";
                  $viewWardWhere = "view_ward_mstr.id in($wardPermission)";
                }
            else { 
                $where=" tbl_apply_water_connection.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_apply_water_connection.connection_type_id = 2 and tbl_apply_water_connection.apply_from = 'TC'";
                $viewWardWhere = "view_ward_mstr.id =$ward_id";
            }
          $data['wcon'] = 'Regulization'; 
        }
        $data['waterConnection']=$this->waterConnectionReportModel->wardWisewaterConnection($where,$viewWardWhere);
        //print_var($data);die;
        return view('mobile/water/wardWiseWaterConnection', $data);  
    }

    public function viewDetailsByWard($from_date,$to_date,$wardId,$status)
    {  
        $from_date = base64_decode($from_date);
        $to_date = base64_decode($to_date);
        $ward_id = base64_decode($wardId);
        $status = base64_decode($status);
        $data['from_date'] = $from_date;            
        $data['to_date'] = $to_date;
        $data['status'] = $status;
        if($status == 1)//total new water connection
        {
            $where="view_water_application_details.created_on::date between '".$from_date."' and '".$to_date."' and view_water_application_details.connection_type_id = 1 and view_water_application_details.ward_id='".$ward_id."'";
            $data['wcon'] = 'New Water Connection'; 
        }
        elseif($status == 2)  //regulization
        {
            $where="view_water_application_details.created_on::date between '".$from_date."' and '".$to_date."' and view_water_application_details.connection_type_id = 2 and view_water_application_details.ward_id='".$ward_id."'";              
            $data['wcon'] = 'Regulization'; 
        }
        

        $data['waterConnectionDetailsByWard']=$this->waterConnectionReportModel->waterConnectionDetailsByWard($where,$status);

        return view('mobile/water/waterConnectionViewWardWise',$data);
    }

    public function water_connection_view($insert_id)
    {
            
            
            $data['user_type']=$this->user_type;

            $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($insert_id);
            

            $data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($insert_id);
            $data['owner_details']=$this->apply_wtrconn_model->water_owner_details($insert_id);
            
            $data['water_conn_id']=$insert_id;
            //  print_r($data['owner_details']);
            $data['dues']=$this->conn_fee_model->conn_fee_charge($insert_id);
            $data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($insert_id);
            
            $data['application_status']=$this->application_status($insert_id);

            return view('water/water_connection/waterConnectionView',$data);
    }
    
    //------------------sandeep-----------------//

    public function datewise_ward_transaction_report($page=null)
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $tran_by_emp_details_id = $emp_mstr["id"];
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        helper(['form']);
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');    
        $and = '';    
        
        if($this->request->getMethod()=='post' && $page==md5(1))
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            $and = '';
            if($ward_id!='')
            {
                $and = "and t.ward_mstr_id = $ward_id ";
            }
            

        } 
        $where="where t.emp_details_id=$login_emp_details_id 
                    and cast(t.created_on as date) between '$from_date'
                    and '$to_date' $and";

        $tbl = "from tbl_transaction t 
                left join tbl_consumer c on c.id=t.related_id
                left join tbl_apply_water_connection apw on apw.id = t.related_id
                $where";

        $sql_tr="  select t.id as transactin_id,c.id as consumer_id,apw.id as application_id ,t.paid_amount,t.transaction_type,apw.application_no,
                    c.consumer_no,cast(t.created_on as date) as created_on,payment_mode
                    $tbl ";

        $transaction = $this->water_report_model->row_sql($sql_tr);
        $data['transaction']['result']=$transaction ;
        $sql_total = "select sum(paid_amount) $tbl";
        $total = $this->water_report_model->row_sql($sql_total);
        
        return view('mobile/water/reports/datewise_ward_transaction_report',$data);        
    }

    public function tc_team_summary()
    {
        $data = array();
		$data['module']="Water";
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $tran_by_emp_details_id = $emp_mstr["id"];
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        
        helper(['form']);
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');    
        $and = '';    
        $ward_id='';
        if($this->request->getMethod()=='get')
        {
            $inputs = $this->request->getVar();
            if(isset($inputs['from_date']) && trim($inputs['from_date']))
                $from_date = $this->request->getVar('from_date');
            if(isset($inputs['to_date']) && trim($inputs['to_date']))
                $to_date = $this->request->getVar('to_date');
            if(isset($inputs['ward_id']) && trim($inputs['ward_id']))
                $ward_id = $this->request->getVar('ward_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            $and = '';
            if($ward_id!='')
            {
                $and = "and t.ward_mstr_id = $ward_id ";
            }
            

        } 
        $where="where t.emp_details_id=$login_emp_details_id 
                    and cast(t.transaction_date as date) between '$from_date'
                    and '$to_date' $and";

        $tbl = "from tbl_transaction t 
                left join tbl_consumer c on c.id=t.related_id
                left join tbl_apply_water_connection apw on apw.id = t.related_id
                $where";

        $sql_tr="  select t.id as transactin_id,t.transaction_no,c.id as consumer_id,apw.id as application_id ,t.paid_amount,t.transaction_type,apw.application_no,
                    c.consumer_no,cast(t.transaction_date as date) as created_on,payment_mode
                    $tbl ";
        $data['from_date'] = $from_date;
        $data['to_date']= $to_date;
        $transaction = $this->water_report_model->row_sql($sql_tr);
        $data['transaction']=$transaction ;
        $sql_total = "select sum(paid_amount) $tbl";
        $data["total"] = $this->water_report_model->row_sql($sql_total)[0]['sum']??0;
        $data['emp_dtls'] = $this->modelemp->emp_dtls($login_emp_details_id);
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($data['ulb_mstr_id']);            
        return view('mobile/water/reports/tc_team_summary',$data);
    }
}
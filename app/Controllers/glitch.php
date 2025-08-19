<?php
namespace App\Controllers;

use App\Models\model_water_consumer;
use CodeIgniter\Controller;
use App\Models\model_datatable;
use App\Models\model_view_saf_receive_list;
use App\Models\model_ward_mstr;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_dtl_permission;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_doc_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_saf_dtl;
use App\Controllers\Reports\PropReports;
use Exception;

class glitch extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $model_view_saf_dtl;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_emp_dtl_permission;
    protected $model_view_ward_permission;
    protected $model_fy_mstr;
    protected $model_view_saf_receive_list;
    protected $model_view_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_saf_memo_dtl;
    protected $model_saf_floor_details;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_prop_type_mstr;
    protected $model_road_type_mstr;
    protected $model_view_saf_floor_details;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_doc_mstr;
    protected $model_water_consumer;
    protected $model_datatable;
    protected $PropReports;

    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name1 = dbConfig("water")) {
            $this->water = db_connect($db_name1);
        }
        if ($db_trade = dbConfig("trade")) {
            $this->trade = db_connect($db_trade);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->PropReports = new PropReports();
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_water_consumer = new model_water_consumer($this->water);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct()
    {
        if($this->db)$this->db->close();
	    if($this->dbSystem)$this->dbSystem->close();
    }

    public function check()
    {
        echo 'ok';
    }

    public function index()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        //if(!in_array($emp_mstr['user_type_mstr_id'], [1,2,4]))
        if(!in_array($emp_mstr['user_type_mstr_id'], [1,2]))
        {
            $url=base_url('/');
            return $this->response->redirect($url);
        }
        try {
            $result=[];
            if($this->request->getMethod()=='post' && isset($_POST['btn_search']))
            {
                $data = arrFilterSanitizeString($this->request->getVar());
                $sql = "SELECT 
                    tbl_prop_dtl.id,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_field_verification_dtl.id as verification_id,
                    tbl_field_verification_dtl.prop_type_mstr_id,
                    tbl_field_verification_dtl.road_type_mstr_id,
                    tbl_field_verification_dtl.area_of_plot,
                    tbl_field_verification_dtl.ward_mstr_id,
                    tbl_field_verification_dtl.is_mobile_tower,
                    tbl_field_verification_dtl.tower_area,
                    tbl_field_verification_dtl.tower_installation_date,
                    tbl_field_verification_dtl.is_hoarding_board,
                    tbl_field_verification_dtl.hoarding_area,
                    tbl_field_verification_dtl.hoarding_installation_date,
                    tbl_field_verification_dtl.is_petrol_pump,
                    tbl_field_verification_dtl.under_ground_area,
                    tbl_field_verification_dtl.petrol_pump_completion_date,
                    tbl_field_verification_dtl.is_water_harvesting,
                    tbl_field_verification_dtl.zone_mstr_id,
                    tbl_field_verification_dtl.percentage_of_property_transfer,
                    tbl_field_verification_dtl.new_ward_mstr_id,
                    tbl_saf_dtl.land_occupation_date,
                    tbl_prop_dtl.apartment_details_id,
                    tbl_prop_type_mstr.property_type,
                    tbl_road_type_mstr.road_type,
                    tbl_prop_dtl.saf_dtl_id
                FROM tbl_prop_dtl 
                LEFT JOIN tbl_saf_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                LEFT JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_prop_dtl.prop_type_mstr_id
                LEFT JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id=tbl_prop_dtl.road_type_mstr_id
                LEFT JOIN (SELECT max(id) as max_verification_id,saf_dtl_id from tbl_field_verification_dtl where status=1 AND verified_by='ULB TC' group by saf_dtl_id) max_verification ON max_verification.saf_dtl_id=tbl_prop_dtl.saf_dtl_id
                LEFT JOIN tbl_field_verification_dtl ON max_verification.max_verification_id=tbl_field_verification_dtl.id
                WHERE (tbl_prop_dtl.new_holding_no='".$data['holding_no']."') and  tbl_saf_dtl.saf_pending_status=1";
                $record = $this->db->query($sql)->getRowArray();

//                 dd($record);
                if(empty($record)){
                    echo "Holding No. not found.";
                    exit();
                }
                $prop_sql='select * from tbl_prop_dtl LEFT JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_prop_dtl.prop_type_mstr_id
                LEFT JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id=tbl_prop_dtl.road_type_mstr_id where tbl_prop_dtl.id='.$record['id'];
                $property = $this->db->query($prop_sql)->getRowArray();
               // dd($record,$record['id'],$property);

                $result['holding_no']=$data['holding_no'];
                $result['ulbverification']=$record;
                $result['prop_dtl_id']=$record['id'];
                $result['property']=$property;
                $result['propertycorrection']=false;
                if($property['is_water_harvesting']!=$record['is_water_harvesting']
                || $property['prop_type_mstr_id']!=$record['prop_type_mstr_id'] || $property['road_type_mstr_id']!=$record['road_type_mstr_id'])
                {
                    $result['propertycorrection']=true;
                }
//                dd($property);

                //extrafloorDetails check;
                $result['extrafloor']=false;
                $result['extrafloorDetails']=[];
                $extrafloorDetails=$this->extrafloor($record['verification_id']);
                //dd($extrafloorDetails[0]['field_verification_dtl_id']);
                foreach ($extrafloorDetails as $extrafloorcheck){
                    $extrafloor_=$this->compareFloor($record['id'],$extrafloorcheck);
                    if(!empty($extrafloor_)){
                        $result['extrafloorDetails'][]=$extrafloor_;
                    }
                }
                if(count($result['extrafloorDetails'])>0){
                    $result['extrafloor']=true;
                }
              //dd($result['extrafloor'],$result['extrafloorDetails']);
            }
        }catch(Exception $e){
            print_r($e);
        }
        return view('glitch/property', $result);
    }
    public function extrafloor($verificationID){
        $sql='SELECT sfd.id,
            sfd.field_verification_dtl_id,
            sfd.saf_dtl_id,
            sfd.saf_floor_dtl_id,
            sfd.floor_mstr_id,
            floor.floor_name,
            sfd.usage_type_mstr_id,
            usage.usage_type,
            sfd.const_type_mstr_id,
            const.construction_type,
            sfd.occupancy_type_mstr_id,
            occup.occupancy_name,
            sfd.builtup_area,
            sfd.date_from,
            sfd.date_upto,
            sfd.emp_details_id,
            sfd.created_on,
            sfd.status,
            sfd.carpet_area
            FROM tbl_field_verification_floor_details sfd
            left JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
            left JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id
            left JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id AND const.status = 1
            left JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id AND occup.status = 1
            where sfd.field_verification_dtl_id='.$verificationID.' and (saf_floor_dtl_id=0 or saf_floor_dtl_id is null)';
            return $extrafloor = $this->db->query($sql)->getResultArray();
    }
    public function compareFloor($prid,$extrafloor){
       $propertyfloors = $this->model_prop_floor_details->getFloorByPropId(['prop_dtl_id'=>$prid]);
       $addedfloor=[];
       $addedcheck=0;
//       dd($propertyfloors[0]);
       foreach ($propertyfloors as $p_floor)
        {
            if($p_floor['floor_mstr_id']==$extrafloor['floor_mstr_id'] &&
                $p_floor['usage_type_mstr_id']==$extrafloor['usage_type_mstr_id'] &&
                $p_floor['const_type_mstr_id']==$extrafloor['const_type_mstr_id'] &&
                $p_floor['occupancy_type_mstr_id']==$extrafloor['occupancy_type_mstr_id'] &&
                $p_floor['builtup_area']==$extrafloor['builtup_area'] &&
                $p_floor['date_from']==$extrafloor['date_from'] &&
                $p_floor['carpet_area']==$extrafloor['carpet_area'])
            { $addedcheck=1;}
        }
       if($addedcheck==0){
           $addedfloor=$extrafloor;
           return $addedfloor;
       }
       //dd($propertyfloors,$extrafloor);
    }



    //demandDeactivate
    public function demandchecker(){
        $result=[];
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $result['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $result['user_type_id']=$emp_mstr['user_type_mstr_id'];
        //dd($result);
        $login_emp_details_id = $emp_mstr["id"];
        if(!in_array($emp_mstr['user_type_mstr_id'], [1,2]))
        {
            $url=base_url('/');
            return $this->response->redirect($url);
        }
        try {
            if($this->request->getMethod()=='post' && isset($_POST['btn_search']))
            {
                $request=arrFilterSanitizeString($this->request->getVar());
                $sql="select * from tbl_prop_dtl where new_holding_no='".$request['holding_no']."'";
                $record = $this->db->query($sql)->getRowArray();

                if(empty($record)){
                    echo "Holding No. not found.";
                    exit();
                }
                $propertydetl=$record;
                $prop = $this->model_prop_dtl->get_prop_full_details($propertydetl['id']);
                $prop = $prop['get_prop_full_details'];
                $data = json_decode($prop, true);

                $data['paid_demand'] = $this->model_prop_demand->getpaidid_by_propdtlid($data['prop_dtl_id']);

                $result=array_merge($result,$data);
                $result['property']=true;
                $dues=$this->model_prop_demand->geDuesYear($data['prop_dtl_id']);
//                dd($data);
                $sqlupto="select * from tbl_transaction where prop_dtl_id='".$data['prop_dtl_id']."' AND id=(SELECT MAX(id) FROM tbl_transaction where prop_dtl_id='".$data['prop_dtl_id']."' AND status=1)";
                $uptopayment = $this->db->query($sqlupto)->getRowArray();
                //$result['uptoYear']=$this->model_prop_demand->geDuesYear($data['prop_dtl_id']);
                $result['uptoYear']=$uptopayment['upto_fy_mstr_id'];
                $result['uptoQtr']=$uptopayment['upto_qtr'];
                //dd($prop,$uptopayment);
            }
        }catch(Exception $e){
            print_r($e);
            exit();
        }

        return view('glitch/demandchecker', $result);
    }

    public function demandcheckerupdate(){
        $result=[];
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $result['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $result['user_type_id']=$emp_mstr['user_type_mstr_id'];
        //dd($result);
        $login_emp_details_id = $emp_mstr["id"];
        if(!in_array($emp_mstr['user_type_mstr_id'], [1,2]))
        {
            $url=base_url('/');
            return $this->response->redirect($url);
        }
        try {
            if($this->request->getMethod()=='post' && isset($_POST['demandupdate']))
            {
                $request=arrFilterSanitizeString($this->request->getVar());
                if(count($request['demandid'])>0){
                    $demandid=$request['demandid'];
                    $prop_dtl_id=$request['propid'];
                   // dd($demandid,$prop_dtl_id);
                    foreach ($demandid as $demand_id)
                    {
                        $this->db->table('tbl_prop_demand')
                             ->where('prop_dtl_id',$prop_dtl_id)->where('id',$demand_id)
                             ->update(['status'=>0, 'balance'=>0.00]);
                    }
                    flashToast("message", "Demand updated successfully");
                }
            }
        }catch(Exception $e){
            print_r($e);
            exit();
        }
        return $this->response->redirect(base_url('glitch/demandchecker?status=true'));
    }


    public function paymentfix(){
        //$prop_dtl_id,$upto_fy,$upto_qtr,$payable_amt,$payment_date
        $result=[];
//        if($this->request->getMethod()=='post') {
//            $inputs = arrFilterSanitizeString($this->request->getVar());
//            if($inputs['payment_type']=="Property"){
//                $psql="select * from tbl_hdfc_request where order_id='".$inputs['order_id']."'";
//                $record=$this->db->query($psql)->getRowArray();
//                $result['payment_type']='Property';
//                $result['txn_dtl']=$record;
//                $result['order_id']=$inputs['order_id'];
//                $pchecksql="select * from tbl_transaction where tbl_transaction.prop_dtl_id='".$record['prop_dtl_id']."' AND
//                 tran_mode ='ONLINE' AND tran_type='Property' AND payable_amt='".$record['payable_amt']."'
//                AND from_fy_mstr_id='".$record['from_fy_mstr_id']."' AND status='2' AND upto_fy_mstr_id='".$record['upto_fy_mstr_id']."'";
//                $payrecord=$this->db->query($pchecksql)->getRowArray();
//                //dd();
//            }
//            if($inputs['payment_type']=="Water"){
//                $wsql="select * from tbl_hdfc_request where order_id='".$inputs['order_id']."'";
//            }
//            if($inputs['payment_type']=="Trade"){
//                $tsql="select * from tbl_hdfc_request where order_id='".$inputs['order_id']."'";
//            }
//        }
        if($this->request->getMethod()=='post' && $this->request->isAJAX()) {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if($inputs['payment_type']=="Property"){
                $psql="select tbl_hdfc_request.*,tbl_prop_dtl.new_holding_no from tbl_hdfc_request left join tbl_prop_dtl on tbl_prop_dtl.id=tbl_hdfc_request.prop_dtl_id where tbl_hdfc_request.order_id='".$inputs['order_id']."'" ;
                $record=$this->db->query($psql)->getRowArray();
                $psql_r="select tbl_razor_pay_request.*,tbl_prop_dtl.new_holding_no from tbl_razor_pay_request left join tbl_prop_dtl on tbl_prop_dtl.id=tbl_razor_pay_request.prop_dtl_id where tbl_razor_pay_request.order_id='".$inputs['order_id']."'" ;
                $record_r=$this->db->query($psql_r)->getRowArray();
                if(empty($record))
                {
                    if(empty($record_r))
                    {
                        return json_encode(['status'=>false,'message'=>'Invalid Order ID.']);
                    }else{
                        $record=$record_r;
                    }
                }

                $result['payment_type']='Property';
                $result['txn_dtl']=$record;
                $result['order_id']=$inputs['order_id'];
                $result['payment_status']="";
                $pchecksql="select * from tbl_transaction where tbl_transaction.prop_dtl_id='".$record['prop_dtl_id']."' AND
                 tran_mode ='ONLINE' AND tran_type='Property' AND payable_amt='".$record['payable_amt']."'
                AND from_fy_mstr_id='".$record['from_fy_mstr_id']."' AND status='1' AND upto_fy_mstr_id='".$record['upto_fy_mstr_id']."'";
                $payrecord=$this->db->query($pchecksql)->getRowArray();
                if(!empty($payrecord)){
                    $result['payment_status']="PAID";
                }
                //return json_encode($record);
            }
            $view = \Config\Services::renderer();
            $view->setData($result);
            return json_encode(['status'=>true,'data'=>$view->render('glitch/payment_dtl')]);

           // return $view->render('glitch/payment_dtl');
        }
        return view('glitch/paymentfix',$result);

        //dd($trxn_id);
    }
    public function fixpayment(){
        if($this->request->getMethod()=='post' && $this->request->isAJAX()) {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if($inputs['type']=="Property"){
                $psql="select tbl_hdfc_request.* from tbl_hdfc_request where tbl_hdfc_request.order_id='".$inputs['dataitem']['order_id']."'" ;
                $record=$this->db->query($psql)->getRowArray();
                if(empty($record))
                {
                    $psql_r="select tbl_razor_pay_request.*,tbl_prop_dtl.new_holding_no from tbl_razor_pay_request left join tbl_prop_dtl on tbl_prop_dtl.id=tbl_razor_pay_request.prop_dtl_id where tbl_razor_pay_request.order_id='".$inputs['dataitem']['order_id']."'" ;
                    $record=$this->db->query($psql_r)->getRowArray();
                }

                $input = [
                    "prop_dtl_id" => $record['prop_dtl_id'],
                    "fy" => $record['upto_fy'],
                    "qtr" => $record['upto_qtr'],
                    "user_id" => 0,
                    "payment_mode" => "Online",
                    "remarks" => 'Online Payment issue update',
                    "total_payable_amount" => $inputs['dataitem']['total_payable_amount'],
                    "payment_date" => date('Y-m-d',strtotime($record['created_on']))
                ];
                $sql = "select * from prop_pay_now_online($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]','$input[remarks]', $input[total_payable_amount], '$input[payment_date]');";
                $builder = $this->db->query($sql);
            }
            try {
                $trxn_id = $builder->getFirstRow('array')['prop_pay_now_online'];
                if(!empty($trxn_id))
                {
                    return json_encode(['status'=>true,'message'=>'Payment Details Updated.']);
                }else{
                    return json_encode(['status'=>true,'message'=>'Payment already Updated Successfully.']);
                }
            } catch (Exception $e) {
                return ['status'=>false,'Something Went Wrong.'];
            }
        }
    }
































    //
    public function correction(){
        if($this->request->isAJAX()){
            $data = arrFilterSanitizeString($this->request->getVar());
            //return json_encode($data);
            if($data['type']=="FA")
            {
               try{
                    $dataitem = json_encode($data['dataitem']);
                    //$dataitem = $data['dataitem'];
                    $sql = "select * from crossupdate_verification($data[propID],'prop_floorAdd','$dataitem'::json)";
                    $record = $this->db->query($sql)->getFirstRow('array')['crossupdate_verification'];
                    return json_encode(['status' => 'true', 'message' => $record]);
                 }catch(Exception $e){
                    return json_encode(['status' => 'false', 'message' => 'No floor added']);
                }
            }
            if($data['type']=="WH")
            {
               // try{
                    $dataitem = json_encode($data['dataitem']);
                    $sql = "select * from crossupdate_verification($data[propID],'prop_waterH','$dataitem'::json)";
                    $record = $this->db->query($sql)->getFirstRow('array')['crossupdate_verification'];
                    return json_encode(['status' => 'true', 'message' => $record]);
               // }catch(Exception $e){
               //     return json_encode(['status' => 'false', 'message' => 'Correction failed']);
               // }
            }
            if($data['type']=="ST")
            {
               // try{
                    $dataitem = json_encode($data['dataitem']);
                    $sql = "select * from crossupdate_verification($data[propID],'prop_streetT','$dataitem'::json)";
                    $record = $this->db->query($sql)->getFirstRow('array')['crossupdate_verification'];
                    return json_encode(['status' => 'true', 'message' => $record]);
               // }catch(Exception $e){
               //     return json_encode(['status' => 'false', 'message' => 'Correction failed']);
               // }
            }
        }
        return 'ok';
    }

    public function uploadCustomDoc(){
        if($this->request->isAJAX()){
            // strtoupper($this->request->getMethod())=="POST"
            $response=[
                "status"=>false,
                "error"=>""
            ];
            try{
                $file = $this->request->getFile('file');
                $newFileName = $this->request->getVar('name');;
                $file_ext = $file->getExtension();
                $path = trim(trim($this->request->getVar("path"),'/'),"\\");
                if ($file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
                    $response["status"]=true;
                }else{
                    $response["error"]="server Error";
                }
            }catch(Exception $e){
                $response["error"]=$e->getMessage();
            }
            
            return json_encode($response);
        }
        return view('glitch/customDocUpload');
    }
}
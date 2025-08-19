<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_trade_level_pending;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_apply_licence;
use App\Models\model_view_trade_licence;
use App\Models\model_view_application_doc;
use App\Models\TradeTaxdarogaDocumentVerificationModel;
use App\Models\model_application_type_mstr;
use App\Models\model_trade_items_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\trade_view_application_doc_model;
use App\Models\TradeTaxdarogaVerificationModel;
use App\Models\model_datatable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Exception;

class Trade_SH extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_trade_level_pending;
    protected $model_firm_owner_name;
    protected $model_application_doc;
    protected $model_trade_level_pending_dtl;
    protected $model_apply_licence;
    protected $model_view_trade_licence;
    protected $model_view_application_doc;
    protected $TradeTaxdarogaDocumentVerificationModel;
    protected $model_application_type_mstr;
    protected $model_trade_items_mstr;
    protected $TradeTransactionModel;
    protected $TradeChequeDtlModel;
    protected $trade_view_application_doc_model;
    protected $TradeTaxdarogaVerificationModel;
    protected $model_datatable;

    public function __construct()
    {

        parent::__construct();
        helper(['db_helper','form','utility_helper']);
        if($db_name = dbConfig("trade"))
        {
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }

        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_trade_level_pending = new model_view_trade_level_pending($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_view_application_doc = new model_view_application_doc($this->db);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->TradeTaxdarogaDocumentVerificationModel = new TradeTaxdarogaDocumentVerificationModel($this->db);
        $this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
        $this->TradeTaxdarogaVerificationModel = new TradeTaxdarogaVerificationModel($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }

    public function index()
    {
        $Session = Session();
        $ulb_mstr = getUlbDtl();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data = arrFilterSanitizeString($this->request->getVar());
        $data['wardList'] = $wardList;
        
        $imploded_ward_mstr_id= implode(', ', array_map(function ($entry) {
            return $entry['ward_mstr_id'];
          }, $data['wardList']));

        $whereWardNo = "";
        $whereFilter = "";
        if (isset($data['ward_mstr_id']) && $data['ward_mstr_id']!="") {
            $whereWardNo = " AND al.ward_mstr_id='".$data['ward_mstr_id']."'";
        } else {
            $whereWardNo = " AND al.ward_mstr_id IN (".$imploded_ward_mstr_id.")";
        }
        if (isset($data['search_param']) && $data['search_param']!="") {
            $whatever = $data['search_param'];
            $whereFilter = " AND (fown.mobile_no ILIKE '%$whatever%' OR al.application_no ILIKE '%$whatever%')";
        }

        $part = ", vl.*";

        $selectStatementTop = "SELECT 
                        fown.ward_no,
                        al.application_no,
                        fown.mobile_no,
                        fown.application_type,
                        al.firm_name,
                        fown.apply_date,
                        fown.address ";
        $selectStatementBottom = " FROM tbl_level_pending as vl 
                    JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id 
                    JOIN tbl_apply_licence as al on al.id=vl.apply_licence_id ";

        $queryRemaining = $whereWardNo." 
                    WHERE vl.receiver_user_type_id =".$receiver_user_type_id." AND vl.status=1 ".$whereFilter."
                        AND al.pending_status != 5
                    ORDER BY vl.id ASC";

        $sql_qry = "$selectStatementTop $part $selectStatementBottom".$whereWardNo." $queryRemaining" ;

                    // WHERE vl.receiver_user_type_id =".$receiver_user_type_id." AND vl.status=1 ".$whereFilter."
                    // ORDER BY vl.id ASC";
                    
        $result = $this->model_datatable->getDatatable($sql_qry);
        $data['result'] = isset($result['result']) ? $result['result'] : null;
        $data['count'] = $result['count'];
        $data['offset'] = $result['offset'];

        $user_type = $this->model_datatable->getDesignationWithUserId($receiver_user_type_id);
        $data['designation']=$user_type['user_type'];
        $data['user_type_id'] = $user_type['user_type_id'];
        $data['queryTop'] = $selectStatementTop;
        $data['queryMiddle'] = $selectStatementBottom;
        $data['queryRemaining'] = $queryRemaining;

        // print_var($user_type);
        // die();
        return view('trade/Connection/trade_level_inbox', $data);    
    }

    public function index2()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
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

        if($this->request->getMethod()=='post'){
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceivebywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_sh_list', $data);
        }
        else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            $j=0;

            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_sh_list', $data);
        }
    }

    public function outbox()
    {
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        
        // print_var($data['user_type']);die();

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['wardList'] = $wardList;
        
        $imploded_ward_mstr_id= implode(', ', array_map(function ($entry) {
            return $entry['ward_mstr_id'];
          }, $data['wardList']));

        $whereWardNo = "";
        $whereFilter = "";
        if (isset($data['ward_mstr_id']) && $data['ward_mstr_id']!="") {
            $whereWardNo = " AND al.ward_mstr_id='".$data['ward_mstr_id']."'";
        } else {
            $whereWardNo = " AND al.ward_mstr_id IN (".$imploded_ward_mstr_id.")";
        }
        if (isset($data['search_param']) && $data['search_param']!="") {
            $whatever = $data['search_param'];
            $whereFilter = " AND (fown.mobile_no ILIKE '%$whatever%' OR al.application_no ILIKE '%$whatever%')";
        }

       $part = ", vl.*";

        $selectStatementTop = "SELECT 
                        fown.ward_no,
                        al.application_no,
                        fown.mobile_no,
                        fown.application_type,
                        al.firm_name,
                        fown.apply_date,
                        fown.address ";
        $selectStatementBottom = " FROM tbl_level_pending as vl 
                    JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id 
                    JOIN tbl_apply_licence as al on al.id=vl.apply_licence_id ";

        $queryRemaining = $whereWardNo." 
                    WHERE vl.sender_user_type_id =".$receiver_user_type_id." AND vl.status=1 ".$whereFilter."
                    ORDER BY vl.id ASC";

        $sql_qry = "$selectStatementTop $part $selectStatementBottom".$whereWardNo." $queryRemaining" ;

        $result = $this->model_datatable->getDatatable($sql_qry);
        $data['result'] = isset($result['result']) ? $result['result'] : null;
        $data['count'] = $result['count'];
        $data['offset'] = $result['offset'];

        $user_type = $this->model_datatable->getDesignationWithUserId($receiver_user_type_id);
        $data['designation']=$user_type['user_type'];
        $data['queryTop'] = $selectStatementTop;
        $data['queryMiddle'] = $selectStatementBottom;
        $data['queryRemaining'] = $queryRemaining;
        // print_var($data);die();
        return view('trade/Connection/trade_sh_outbox', $data);    
    }   
    
    public function collectionReportExcel()
    {

        if($this->request->getMethod()=='post'){
            $queryTop = "SELECT 
                        fown.ward_no,
                        CONCAT('`',al.application_no) AS application_no,
                        fown.mobile_no,
                        fown.application_type,
                        al.firm_name,
                        fown.apply_date,
                        fown.address ";
            $queryMiddle = $this->request->getVar('query_middle');
            $queryBottom = $this->request->getVar('query_bottom');

            $queryString = $queryTop." ".$queryMiddle." ".$queryBottom; 
            // die();
            // $search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null, $search_tran_mode_mstr_id = null
            try
            {
                
                $selectStatement = $queryString;

                // $fetchSql = $selectStatement.$sql;
                $records = $this->model_datatable->getRecords($selectStatement);
                
                //phpOfficeLoad();
                $spreadsheet = new Spreadsheet();
                
                $activeSheet = $spreadsheet->getActiveSheet();
                                $activeSheet->setCellValue('A1', 'Ward No');
                                $activeSheet->setCellValue('B1', 'Application No');
                                $activeSheet->setCellValue('C1', 'Mobile No');
                                $activeSheet->setCellValue('D1', 'Application Type');
                                $activeSheet->setCellValue('E1', 'Firm Name');
                                $activeSheet->setCellValue('F1', 'Apply Date');
                                $activeSheet->setCellValue('G1', 'Address');
                                // $activeSheet->setCellValue('H1', 'Amount');
                                // $activeSheet->setCellValue('I1', 'Tax Collector');
                                // $activeSheet->setCellValue('J1', 'Tran. No.');
                                // $activeSheet->setCellValue('K1', 'Check/DD No');
                                // $activeSheet->setCellValue('L1', 'Bank');
                                // $activeSheet->setCellValue('M1', 'Branch');
                                $activeSheet->fromArray($records, NULL, 'A2');
                $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $filename = "counter_report_".date('Ymd-hisa').".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                //$writer->save(APPPATH.'/hello world.xlsx');
                ob_end_clean();
                $writer->save('php://output');
            }catch(Exception $e){
                print_r($e);
            }
        }
    }
    public function view($id)
    {
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = getUlbDtl();
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $level_data_sql = " select * from tbl_level_pending where md5(id::text) = '$id' "; 
        $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0]??[];
        if(!$level_data)
        {
            flashToast("licence","Application Not Found");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']==2)
        {
            flashToast("licence","Application Already BTC");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']==3)
        {
            flashToast("licence","Application Already Forword");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']!=1)
        {
            flashToast("licence","Already Taken Acction On This Application");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);

        if($data['form'] && $data['form']['pending_status']==5)
        {
            flashToast("licence","License Already Created Of ".$data['form']['application_no']." Please Contact To Admin !!!!");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $apply_licence_id=$data['basic_details']['id'];
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no']=$data['ward']['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        if (isset($data['basic_details']['holding_no']) && !empty(trim($data['basic_details']['holding_no']))) {
            $prop_id = $this->model_view_trade_licence->getPropetyIdByNewHolding($data['basic_details']['holding_no']);
            if (isset($prop_id['id']))
                $data['PropSafLink'] = base_url() . "/propDtl/full/" . $prop_id['id'];
        }
        $data['dd']=array();
        if($data['holding']['nature_of_bussiness'])
            $data['nature_business'] =$this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
        foreach($data['nature_business'] as $val)
        {
            $data['dd']=$val;
        }
        
        $data['nature_business']['trade_item'] = is_array($data['dd'])? implode('<b>,</b><br>',$data['dd']):$data['dd'];
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
		// $data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls']['id']);
        $data['documents']=$this->trade_view_application_doc_model->getAllActiveDocuments($apply_licence_id);

        # fetch tax daroga verification details
        $data['taxDarogaVerification']=$this->TradeTaxdarogaVerificationModel->siteInspectionRemarks($apply_licence_id);

        //Get All Level Remarks
        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
        $data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);
        //Tax Daroga
        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
        $data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);
        //End Tax Daroga
        //start Section Head
        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
        $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);
        //End Section Head
        //Start Executive 
        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);
        $data['executiveReceiveDate'] = $this->model_trade_level_pending_dtl->getExecutiveReceiveDate($apply_licence_id);
        //End Executive
        if($this->request->getMethod()=='post')
        {

            if(isset($_POST['btn_verify_submit']))
            {
                $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => $id,
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'forward_date' =>date('Y-m-d'),
                    'forward_time' =>date('H:i:s'),
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=>19,
                ];
                if($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($insertverify = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        flashToast('licence','Application Forward To The Executive Officer !!!');
                        return $this->response->redirect(base_url('Trade_SH/index/'));
                    }
                }
            }


            //Backward
            if(isset($_POST['btn_backward']))
            {
                    $apply_licence_id = $this->request->getVar('apply_licence_id');
                    $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForSectionHead($apply_licence_id);
                    $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($apply_licence_id);
                    $id=$data['basic_details']['id'];

                    # new code added on 1st may
                    $data['lastRecord'] = 
                    $this->model_trade_level_pending_dtl->getLastRecord($level_pending_dtl_id);
                    $apply_licence_id2=$data['lastRecord']["apply_licence_id"];
                    $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');

                    $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => md5($level_pending_dtl_id),
                    'apply_licence_id' => $id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=> 20,
                ];

                $btcdata = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_id' => $level_last_deta["id"],
                    'apply_licence_id' => $apply_licence_id2,
                    'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
                    'receiver_user_type_id'=> $level_last_deta["receiver_user_type_id"],
                    'forward_date' =>$level_last_deta["forward_date"],
                    'forward_time' => $level_last_deta["forward_time"],
                    'created_on'=> $level_last_deta["created_on"],
                    'verification_status'=> 2,
                    'emp_details_id'=>$level_last_deta["emp_details_id"],
                    'status'=>$level_last_deta["status"],
                    'send_date' => $level_last_deta["send_date"]??null, 
                    'receiver_user_id' => $login_emp_details_id,
                    'ip_address'=> $_SERVER['REMOTE_ADDR'],
                    ];

                    // print_var($btcdata);
                    // die();

                if($updatebackward = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                {

                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        flashToast('licence','Application Backward To Tax Daroga !!!');
                        return $this->response->redirect(base_url('Trade_SH/index/'));
                    }
                }
            }



                //Back To Cittizen
            if(isset($_POST['btn_backToCitizen']))
            {

                $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForSectionHead(md5($apply_licence_id));
                $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($apply_licence_id));
                $id=$data['basic_details']['id'];
                /*echo $apply_licence_id;*/
                $data = [
                'remarks' => $this->request->getVar('remarks'),
                'level_pending_dtl_id' => md5($level_pending_dtl_id),
                'apply_licence_id' => $apply_licence_id,
                'emp_details_id' => $login_emp_details_id,
                'created_on' =>date('Y-m-d H:i:s'),
                'current_date' =>date('Y-m-d'),
                'forward_date' =>date('Y-m-d'),
                'forward_time' =>date('H:i:s'),
                'sender_user_type_id' => $sender_user_type_id,
                'receiver_user_type_id'=> 0,
                'level_pending_status'=> 2,
                ];

                if($updatebacktocitizen = $this->model_trade_level_pending_dtl->updatebacktocitizenById($data))
                {
                                    // tbl_apply_licence set pending_status=2
                    if($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data))
                    {
                                            //$insrtlevelpending = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data);
                                            //$updateverifystts = $this->model_apply_licence->update_verify_status($data);
                        flashToast('licence','Application Sent Back to Citizen !!!');
                        return $this->response->redirect(base_url('Trade_SH/index/'));
                    }
                }

            }
        }
        else
        {
            return view('trade/Connection/trade_sh_view', $data);
        }
    }


    public function bulkApprove()
    {
        

        if ($this->request->getMethod() == 'post') {

            $data = array();
            $data['items'] = $_POST;

            // print_var($data['items']);

            foreach ($data['items']['selecteditems'] as $val) 
            {
                $level_data_sql = " select * from tbl_level_pending where id  = $val "; 
                $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0]??[];
                // print_var($level_data);
                if(!$level_data)
                {
                    continue;
                }
                elseif(!in_array($level_data['status'],[1]))
                {                    
                    continue;
                }

                $this->db->transBegin();

                // $apply_licence_id = $this->model_trade_level_pending_dtl->getApplyLicenceId($val);
                $apply_id = $level_data['apply_licence_id'];

                $inputs = [
                    'remarks' => 'Verified and forwarded by the section head',
                    'level_pending_dtl_id' => md5($val),
                    'apply_licence_id' => $apply_id,
                    'emp_details_id' => $data['items']['emp_details_id'],
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'sender_user_type_id' => $data['items']['sender_user_type_id'],
                    'receiver_user_type_id' => 19,
                ];
                /** **/
                if ($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($inputs)) {
                    // print_var($updateverify);
                    if ($insertverify = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($inputs)) {
                        // flashToast('licence','Application Forward To The Executive Officer !!!');
                        //return $this->response->redirect(base_url('Trade_SH/index/'));
                        $response = true;
                    }
                }
                if ($this->db->transStatus() === FALSE) {
                    // print_var($this->db);
                    // die('transaction stopped here !');
                    echo "<script>alert('Something went wrong');</script>";
                    $this->db->transRollback();
                } else {
                    $this->db->transCommit();
                }
                // echo $response;

            }
        }
    }
}

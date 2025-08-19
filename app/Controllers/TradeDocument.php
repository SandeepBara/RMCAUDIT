<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\TradeApplyLicence;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_apply_licence;
use App\Models\model_ward_mstr;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_view_trade_licence;
use App\Models\model_trade_document;
use App\Models\model_application_type_mstr;
use App\Models\model_trade_items_mstr;
use App\Models\model_category_type;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\trade_view_application_doc_model;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\model_datatable;

use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use Exception;

class TradeDocument extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_apply_licence;
    protected $model_ward_mstr;
    protected $model_firm_owner_name;
    protected $model_application_doc;
    protected $model_trade_level_pending_dtl;
    protected $model_view_trade_licence;
    protected $model_trade_document;
	protected $model_application_type_mstr;
	protected $model_trade_items_mstr;
	protected $model_category_type;
	protected $TradeTransactionModel;
	protected $TradeChequeDtlModel;
	protected $TradeApplyLicenceModel;
	protected $trade_view_application_doc_model;
    protected $tradeapplicationtypemstrmodel;
    protected $tradefirmtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $model_datatable;
	protected $TradeApplyLicenceController;

    public function __construct()
    {


        parent::__construct();
    	helper(['db_helper', 'upload_helper','form']);
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
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
		$this->model_application_type_mstr = new model_application_type_mstr($this->db);
		$this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
		$this->model_category_type = new model_category_type($this->db);
		$this->TradeTransactionModel = new TradeTransactionModel($this->db);
		$this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
		$this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
		$this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->model_datatable = new model_datatable($this->db);    
		$this->TradeApplyLicenceController = new TradeApplyLicence();
    }

   //report 
    public function index()
    {
        $data =(array)null;
        $session = session();
        //Transaction Mode List
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $ward = [];
        $i=0;
        foreach($wardList as $key => $value){
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
        return view('trade/Connection/trade_connection_list', $data);
    }

    // ajax get trade application list
    public function get_trade_application_list_ajax()
    {
        if($this->request->getMethod()=='post')
        {
            try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
               
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                 if ($columnName=="s_no")
                     $columnName = 'tbl_apply_licence.id';                    
                if ($columnName=="ward_no")
                     $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="application_no")                   
                    $columnName = 'tbl_apply_licence.application_no';
                if ($columnName=="firm_name")
                    $columnName = 'tbl_apply_licence.firm_name';
                if ($columnName=="application_type")
                    $columnName = 'tbl_application_type_mstr.application_type';
                if ($columnName=="apply_date")
                    $columnName = 'tbl_apply_licence.apply_date';
                if ($columnName=="valid_upto")
                    $columnName = 'tbl_apply_licence.valid_upto';
                if ($columnName=="owner_name")
                    $columnName = 'tbl_firm_owner_name.owner_name';
                if ($columnName=="mobile")
                    $columnName = 'tbl_firm_owner_name.mobile';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                $searchQuery = "";
                $whereQuery = "";
                $total=0;
                $getTotalAmount=0;
                                
                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));          
                $ward_id = sanitizeString($this->request->getVar('ward_id'));  
                $data['ward_mstr_id'] =  $ward_id;
                $data['from_date'] = $from_date;
                $data['to_date'] =  $to_date;        
                
                if($ward_id=="all")
                {
                    $whereQuery .= "   tbl_apply_licence.apply_date >=  '".$from_date."'";
                    $whereQuery .= "  AND  tbl_apply_licence.apply_date <=  '".$to_date."'";  
                }
                else
                {
                    $whereQuery .= "  tbl_apply_licence.apply_date >=  '".$from_date."'";
                    $whereQuery .= "  AND tbl_apply_licence.apply_date <=  '".$to_date."'";
                    $whereQuery .= "  AND tbl_apply_licence.ward_mstr_id=  '".$ward_id."'";
                }   
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQueryWithSearch = "";
                if ($searchValue!='')
                {
                    $whereQueryWithSearch = " AND (tbl_apply_licence.application_no ILIKE '%".$searchValue."%'
                        OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                        OR tbl_apply_licence.firm_name ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY ".$columnName." DESC) AS s_no,
                view_ward_mstr.ward_no,
                tbl_apply_licence.id,
                tbl_apply_licence.application_no,
                tbl_apply_licence.firm_name,
                tbl_application_type_mstr.application_type,
                tbl_apply_licence.apply_date,
                tbl_apply_licence.valid_upto,
                case  when tbl_apply_licence.document_upload_status=0 
                then concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=".base_url()."/Trade_Apply_Licence/applynewlicence/',md5(tbl_apply_licence.id::text),' role=button>Update</a>') 
                when tbl_apply_licence.document_upload_status=2 then concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=".base_url()."/TradeDocument/doc_upload/',md5(tbl_apply_licence.id::text),' role=button>Upload</a>') 
                else  concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=".base_url()."/TradeDocument/view/',md5(tbl_apply_licence.id::text),' role=button>View</a>') 
                end  as view
                ";      

                $sql ="from tbl_apply_licence
                left join view_ward_mstr on view_ward_mstr.id=tbl_apply_licence.ward_mstr_id
                left join tbl_application_type_mstr on tbl_application_type_mstr.id=tbl_apply_licence.application_type_id
                where tbl_apply_licence.status = 1 and ".$whereQuery;

                $totalRecords = $this->model_datatable->getTotalRecords($sql);
              // return json_encode([$totalRecords]);
                if ($totalRecords>0) { 
                    ## Total number of records with filtering
                      $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                        $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                         $records = $this->model_datatable->getRecords($fetchSql);
                   // return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
               // print_r($records.id);
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                 );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function doc_upload($id=null)
    {
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        //$ulb_shrt_nm = $ulb_mstr["short_ulb_name"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data =(array)null;
        $data["aid"]=$id;
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $photo_id_proof_doc_for='Identity Proof';
        $data['photo_id_proof_document_list'] = $this->model_trade_document->getDocumentList($photo_id_proof_doc_for);
        $data['trade_conn_dtl'] = $this->model_apply_licence->getData($id);
        $apply_licence_id=$data['trade_conn_dtl']['id'];
        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails_md5($id); 
        $data['trans_details'] = $this->TradeTransactionModel->get_trans_details(md5($data['trade_conn_dtl']["id"])); 
        $data['payment_mode']  =  $data['trans_details']['payment_mode'] ?? NULL;
        if(isset($data['payment_mode']) &&  (!in_array(strtoupper($data['payment_mode']),['CASH','ONLINE'])))
        {
            $data['cheque_details']=$this->TradeChequeDtlModel->get_check_details($data['trans_details']['id']);
        }
        
        //$firm_owner_id="";
        $firm_owner_id=array();
        $l=0;
        foreach($data['owner_list'] as $key => $value)
        {
            //id proof
            $data['doc_details_owner']=$this->model_application_doc->check_doc_exist_owner($apply_licence_id, $value['id']);
            $data['owner_list'][$key]['doc_upload_id'] = $data['doc_details_owner']['id'] ?? NULL;
            $data['owner_list'][$key]['document_path'] = $data['doc_details_owner']['document_path'] ?? NULL;
            $data['owner_list'][$key]['doc_document_id'] = $data['doc_details_owner']['document_id'] ?? NULL;
            $data['owner_list'][$key]['verify_status']=$data['doc_details_owner']['verify_status'] ?? NULL;
            //image
            $document_id=0;
            $data['doc_details_owner_img']=$this->model_application_doc->check_doc_exist_owner($apply_licence_id, $value['id'],$document_id);
            $data['owner_list'][$key]['img_doc_upload_id'] = $data['doc_details_owner_img']['id'] ?? NULL;
            $data['owner_list'][$key]['img_document_path'] = $data['doc_details_owner_img']['document_path'] ?? NULL;
            $data['owner_list'][$key]['img_doc_document_id'] = $data['doc_details_owner_img']['document_id'] ?? NULL;
            $data['owner_list'][$key]['img_verify_status']=$data['doc_details_owner_img']['verify_status'] ?? NULL;
            
            if($l==0)
            {
                //$firm_owner_id=array($value['id']);
                $firm_owner_id[]=$value['id'];
            }
            else
            {   
                array_push($firm_owner_id, $value['id']);
            }
            $l++;
        }
        //print_var($data['owner_list']);
        if($firm_owner_id)
        {
            $string_owner_id = implode(', ', $firm_owner_id);
        }
 
         // get owner doc list   
        $data['owner_doc_list'] = $this->model_firm_owner_name->count_doc_list_owner($id);
        //uploaded doc details 
        $data['doc_details_owner_count']=$this->model_application_doc->count_doc_exist_owner($apply_licence_id,$string_owner_id); 
        $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabyid($data["licencedet"]["application_type_id"]);
        $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencedet"]["firm_type_id"]);
        $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencedet"]["ownership_type_id"]);
        $data['ward_no']=$this->model_ward_mstr->getWardNoById($data["licencedet"]);
        $data['new_ward_no']=$this->model_ward_mstr->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $show='1';
        if($data['trade_conn_dtl']['application_type_id']==1)
        {
            if($data['trade_conn_dtl']['ownership_type_id']==1)
            {
                $show .=','.'2';  
            }
            else
            {
                $show .=','.'3';  
            }
            if($data['trade_conn_dtl']['firm_type_id']==2)
            {
                $show .=','.'4';  
            }
            elseif($data['trade_conn_dtl']['firm_type_id']==3 or $data['trade_conn_dtl']['firm_type_id']==4)
            {
                $show .=','.'5';  
            }
            if($data['trade_conn_dtl']['category_type_id']==2){
                $show .=','.'6';  
            }
        }

      
        $data['doc_details']=$this->model_trade_document->getDocumentDetails($data['trade_conn_dtl']['application_type_id'],$show);
        $data['doc_count']=$this->model_trade_document->getDocumentcount($data['trade_conn_dtl']['application_type_id'],$show);
        
        $data['doc_count_mandatory']=$this->model_trade_document->getDocumentcountMandatory($data['trade_conn_dtl']['application_type_id'],$show); 
        $data['doc_upload_count']=$this->model_application_doc->check_upload_doc_count($apply_licence_id);
        $docmnt_id=[];
        $i=0;
        foreach($data['doc_upload_count'] as $key => $value)
        {
            if($i==0)
            {
                $docmnt_id=array($value['document_id']);
            }
            else
            {
                array_push($docmnt_id, $value['document_id']);
            }
            $i++;
        }
        
        
        if($docmnt_id)
        {
            $string_doc_id = implode(', ', $docmnt_id);
            $data['doc_cnt_mndtry']=$this->model_trade_document->Documentmandatory_count($data["application_type"]['id'], $string_doc_id);
            $data['doc_cnt']=$this->model_trade_document->Document_count($data["application_type"]['id'],$string_doc_id);
        }
        else
        {
            $data['doc_cnt_mndtry']['count']=0;
            $data['doc_cnt']['count']=0;
        }
 
        //count owner document upload details
        foreach($data['doc_details'] as $key1 => $value1)
        {
            $data['doc_details'][$key1]['docfor']=$this->model_trade_document->getDocumentappList($data['trade_conn_dtl']['application_type_id'],$value1['doc_for']);
            $data['doc_details'][$key1]['docexists']=$this->model_application_doc->check_doc_exist($apply_licence_id, $value1['doc_for']);       
        }
        //print_r($data['doc_details']);
        $firm_id = $data['owner_list']['id'] ?? NULL;
        $firm_type_id=$data['trade_conn_dtl']['firm_type_id'];
        $ownership_type_id=$data['trade_conn_dtl']['ownership_type_id'];
        $doc_status=$data['trade_conn_dtl']['document_upload_status'];
        $payment_status=$data['trade_conn_dtl']['payment_status'];
        $data['hide_rmc_btn'] = $this->model_trade_level_pending_dtl->hide_rmc_btn($apply_licence_id);  
        $data['apply_licence_id'] = $apply_licence_id;
        $data['temp'] = $temp ?? NULL;
        if($this->request->getMethod()=='post')
        {   //print_var($_POST);die;
            # Upload Document 
            if(isset($_POST['btn_doc_path']))
            {
                $cnt=$_POST['btn_doc_path'];
                $rules = [
                        'doc_path'=>'uploaded[doc_path'.$cnt.']|max_size[doc_path'.$cnt.',30720]|ext_in[doc_path'.$cnt.',pdf, jpg, jpeg]',
                        'doc_mstr_id'.$cnt.''=>'required',
                    ];
                
               
                if ($this->validate($rules))
                { 
                    $doc_path = $this->request->getFile('doc_path'.$cnt);
                    if ($doc_path->IsValid() && !$doc_path->hasMoved())
                    {
                        try
                        {
                            $this->db->transBegin();
                            $input = [
                                'apply_licence_id' => $apply_licence_id,
                                'doc_for' => $this->request->getVar('doc_for'.$cnt),
                                'document_id' => $this->request->getVar('doc_mstr_id'.$cnt),
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'firm_owner_dtl_id'=> $this->request->getVar('ownrid'),
                            ];
                            
                            if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist($input))
                            {
                                $delete_path = WRITEPATH.'uploads/'.$app_doc_dtl_id['document_path'];
                                // unlink($delete_path);
                                deleteFile($delete_path);
                                $newFileName = md5($app_doc_dtl_id['id']);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);

                            }
                            else if ($app_doc_dtl_id = $this->model_application_doc->insertData($input))
                            {
                                $newFileName = md5($app_doc_dtl_id);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                            }
                            if ($this->db->transStatus() === FALSE)
                            {
                                $this->db->transRollback();
                            }
                            else
                            {
                                $this->db->transCommit();
                                return $this->response->redirect(base_url('tradedocument/doc_upload/'.$id));
                            }
                        }
                        catch (Exception $e) { }

                    }
                    else
                    {
                        $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                        $data['errors'] =   $errMsg;
                        return view('trade/Connection/trade_document_upload', $data);
                    }
                }
                else
                { 
                    $errMsg = $this->validator->listErrors();
                    $data['errors'] =   $errMsg;
                    return view('trade/Connection/trade_document_upload', $data);
                }
            }
            
            # Upload Owner Document Id Proof
            if(isset($_POST['btn_doc_path_owner']))
            { 
                $cnt_owner=$_POST['btn_doc_path_owner'];
                
                $rules = [
                        'doc_path_owner'=>'uploaded[doc_path_owner'.$cnt_owner.']|max_size[doc_path_owner'.$cnt_owner.',30720]|ext_in[doc_path_owner'.$cnt_owner.',pdf]',
                        'idproof'.$cnt_owner.''=>'required',
                    ];
                    
                if ($this->validate($rules))
                {
                    $doc_path = $this->request->getFile('doc_path_owner'.$cnt_owner);
                    if ($doc_path->IsValid() && !$doc_path->hasMoved())
                    {
                        try
                        {
                            $this->db->transBegin();
                            $input = [
                                'firm_owner_dtl_id' => $this->request->getVar('ownrid'),
                                'apply_licence_id' => $apply_licence_id,
                                'doc_for' => $this->request->getVar('doc_for'.$cnt_owner),
                                'document_id' => $this->request->getVar('idproof'.$cnt_owner),
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' =>date('Y-m-d H:i:s'),
                            ];
                            
                            if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist_owner($input))
                            {
                                $delete_path = WRITEPATH.'uploads/'.$app_doc_dtl_id['document_path'];
                                // unlink($delete_path);
                                deleteFile($delete_path);
                                $newFileName = md5($app_doc_dtl_id['id']);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);
                            }
                            
                            else if ($app_doc_dtl_id = $this->model_application_doc->insertData($input))
                            {
                                $newFileName = md5($app_doc_dtl_id);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                            }
                            if ($this->db->transStatus() === FALSE) 
                            {

                                $this->db->transRollback();
                            } 
                            else 
                            {

                                $this->db->transCommit();
                                return $this->response->redirect(base_url('tradedocument/doc_upload/'.$id));
                            }
                        } 
                        catch (Exception $e) 
                        { 

                        }

                    } 
                    else 
                    {
                        $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                        $data['errors'] =   $errMsg;
                        return view('trade/Connection/trade_document_upload', $data);
                    }
                } 
                else 
                {

                    $errMsg = $this->validator->listErrors();
                    $data['errors'] =   $errMsg;
                    return view('trade/Connection/trade_document_upload', $data);

                }
            } 
            // owner image upload hear 
            if(isset($_POST['btn_doc_path_owner_img']))
            {
                
                $cnt_owner=$_POST['btn_doc_path_owner_img'];
                
                $rules = [
                        'doc_path_owner_img'=>'uploaded[doc_path_owner_img'.$cnt_owner.']|max_size[doc_path_owner_img'.$cnt_owner.',30720]|ext_in[doc_path_owner_img'.$cnt_owner.',pdf, png, jpg]',
                        'consumer_photo'.$cnt_owner.''=>'required',
                        'doc_for'.$cnt_owner.''=>'required',
                    ];
                   
                if ($this->validate($rules))
                { 
                    $doc_path = $this->request->getFile('doc_path_owner_img'.$cnt_owner);
                    if ($doc_path->IsValid() && !$doc_path->hasMoved())
                    { 
                        try
                        {
                            $this->db->transBegin();
                            $input = [
                                'firm_owner_dtl_id' => $this->request->getVar('ownrid'),
                                'apply_licence_id' => $apply_licence_id,
                                'doc_for' => $this->request->getVar('doc_for'.$cnt_owner),
                                'document_id' => $this->request->getVar('consumer_photo'.$cnt_owner),
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' =>date('Y-m-d H:i:s'),
                            ];
                            
                            if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist_owner($input,$input['doc_for']))
                            {
                                $delete_path = WRITEPATH.'uploads/'.$app_doc_dtl_id['document_path'];
                                // unlink($delete_path);
                                deleteFile($delete_path);
                                $newFileName = md5($app_doc_dtl_id['id']);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);
                            }
                            
                            elseif ($app_doc_dtl_id = $this->model_application_doc->insertData($input))
                            {
                                $newFileName = md5($app_doc_dtl_id);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm."/"."trade_doc_dtl";
                                $doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                $doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                            }
                            if ($this->db->transStatus() === FALSE) {

                                $this->db->transRollback();
                            } 
                            else 
                            {

                                $this->db->transCommit();
                                return $this->response->redirect(base_url('tradedocument/doc_upload/'.$id));
                            }
                        } 
                        catch (Exception $e) 
                        { 

                        }

                    } 
                    else 
                    {
                        $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                        $data['errors'] =   $errMsg;
                        return view('trade/Connection/trade_document_upload', $data);
                    }
                } 
                else 
                {
                    echo"not valied";
                    // print_var($this->validator->listErrors());
                    // die;
                    $errMsg = $this->validator->listErrors();
                    $data['errors'] =   $errMsg;
                    return view('trade/Connection/trade_document_upload', $data);

                }
            } 
            //owner document upload code end
        }
        else
        {
            $data['hide_rmc_btn'] = $this->model_trade_level_pending_dtl->hide_rmc_btn($apply_licence_id);  
            $data['apply_licence_id'] = $apply_licence_id;
            $data['temp'] = $temp ?? NULL;
            //print_var($data['payment_mode']);
            return view('trade/Connection/trade_document_upload', $data);
        }

    }

	
	public function send_rmc($id=null)
	{
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
		$data =(array)null;
		$data['apply_licence_id']=$id;
		$leveldata = [
				'apply_licence_id' => $data['apply_licence_id'],
				'sender_user_type_id' => 0,
				'receiver_user_type_id' => 17,				
				'created_on' =>date('Y-m-d H:i:s'),
				'remarks' => '',
				'emp_details_id' => $login_emp_details_id,
			];
		//print_r($leveldata);
		$data['doc_upload_stts']=$this->model_apply_licence->update_doc_status($leveldata);
        $sql = "select count(id) from tbl_level_pending where apply_licence_id = ? and status = ?";
        $check = $this->model_trade_level_pending_dtl->rowQuery($sql,array($data['apply_licence_id'],1));
        $check = !empty($check)?$check[0]['count']:0;
        if($check==0)
        {
            $level_pending_insrt=$this->model_trade_level_pending_dtl->insrtlevelpendingdtl($leveldata);
        }
		
		//return $this->response->redirect(base_url('tradedocument/docview/'.md5($data['apply_licence_id']).''));
        return redirect()->to(base_url("trade_da/view_application_details/".md5($data['apply_licence_id'])));
	}
	
	
	
    public function view_copy($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        $data['linkId']=$id;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['category_type'] = $this->model_category_type->category_type($data['holding']['category_type_id']);
        $data['nature_business'] = $this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
        $data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls']['id']);
        //print_r($data['owner_details']);
        $data['licencee']=$this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data["application_status"]=$this->TradeApplyLicenceController->applicationStatus_md5($id);
        return view('trade/Connection/trade_conn_view', $data);
    }
    public function view($md5id=null)
    {
        return redirect()->to(base_url("trade_da/view_application_details/".$md5id));
    }
    public function docview($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status='0';
        foreach($data['owner_details'] as $key => $value)
        {
            $app__doc='Consumer Photo';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);
            $app_doc_type="Identity Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_application_doc->conownerdocdetbyid($data['basic_details']['id'],$value['id'],$app_doc_type);
        }
        //print_r($data['owner_details']);
        //die();
        $apply_licence_id=$data['basic_details']['id'];
        $data['doc_exists']=$this->trade_view_application_doc_model->getdocdet_by_appid($apply_licence_id);
        //print_var($data['doc_exists']);
       //print_r( $data['registration_certificate_doc']);
        return view('trade/Connection/trade_doc_view', $data);
    }

}
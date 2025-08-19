<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_ward_mstr;
use App\Models\model_apply_licence;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_view_application_doc;
use App\Models\model_trade_document;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_ward_permission;
use App\Models\model_datatable;
use App\Models\trade_view_application_doc_model;
use Exception;

class BO_TBackToCitizen extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_permission;
    protected $model_datatable;
    protected $trade_view_application_doc_model;
    protected $model_apply_licence;
    protected $model_trade_level_pending_dtl;

    public function __construct()
    {

        parent::__construct();
        helper(['form_helper', 'url']);
        helper(['db_helper',]);

        if ($db_name = dbConfig("trade")) {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbConfig("property")) {
            $this->property_db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }

        /*$this->db = db_connect("db_rmc_trade"); 
         $this->dbSystem = db_connect("db_system"); */
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_view_application_doc = new model_view_application_doc($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_datatable = new model_datatable($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
    }

    public function index()
    {
        $data = (array)null;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        return view('trade/Connection/bo_backtocitizen_list', $data);
    }


    public function backtocitizenlistAjax()
    {

        if ($this->request->getMethod() == 'post') {
            try {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no")
                    $columnName = 'boc.id';
                if ($columnName == "application_no")
                    $columnName = 'boc.application_no';
                if ($columnName == "ward_no")
                    $columnName = 'boc.ward_no';
                if ($columnName == "firm_name")
                    $columnName = 'boc.firm_name';
                if ($columnName == "application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName == "forward_date")
                    $columnName = 'apply.forward_date';
                if ($columnName == "view")
                    $columnName = 'view';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value



                // Date filter
                $btn_search = sanitizeString($this->request->getVar('btn_search'));
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";


                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
                $whereQuery = "";
                if ($btn_search == 'BY') {
                    $whereQuery .= " AND boc.forward_date BETWEEN '" . $search_from_date . "' AND '" . $search_upto_date . "'";

                    if ($search_ward_mstr_id != '') {
                        $whereQuery .= " AND  boc.ward_mstr_id='" . $search_ward_mstr_id . "'";
                    }
                }

                $whereQueryWithSearch = "";
                if ($searchValue != '') {

                    $whereQueryWithSearch = " AND (boc.application_no ILIKE '%" . $searchValue . "%'
                                        OR boc.ward_no ILIKE '%" . $searchValue . "%'
                                        OR boc.firm_name ILIKE '%" . $searchValue . "%'
                                       ) ";
                }



                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY " . $columnName . " DESC) AS s_no,
                boc.ward_no,
                boc.application_no,                                    
                boc.firm_name,
                tbl_application_type.application_type,
                boc.forward_date,
                boc.remarks, 
                view_user_type_mstr.user_type,               
                concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/BO_TBackToCitizen/view/',md5(boc.apply_licence_id::text),' role=button>View</a>') as view                               
                ";


                $sql =  " FROM view_backtocitizenlist boc 
                        JOIN view_user_type_mstr ON view_user_type_mstr.id = boc.sent_by_user_type_id 
                        INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=boc.application_type_id " . $whereQuery;;
                //'<a class='btn btn-primary' href='". echo base_url('tradeapplylicence/trade_licence_view/'.md5("apply.id"))."' role='button'>View</a>' as view
                //  return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                // return json_encode($sql);   
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                // return json_encode([$totalRecords]);
                if ($totalRecords > 0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
                    //print_var($fetchSql);
                    $records = $this->model_datatable->getRecords($fetchSql);
                    //return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            } catch (Exception $e) {
            }
        }
    }



    public function view($id = null)
    {
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        if ($id <> null) 
        {
            $data = (array)null;
            $data['id'] = $id;
            $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
            $ulb_city_nm = $data['ulb_dtl']['city'];
            $data['trade_conn_dtl'] = $this->model_apply_licence->getData($id);
            $data['doc_path'] = $ulb_city_nm . "/trade_doc_dtl/";

            $apply_licence_id = $data['trade_conn_dtl']['id'];
            $firm_type_id = $data['trade_conn_dtl']['firm_type_id'];
            $ownership_type_id = $data['trade_conn_dtl']['ownership_type_id'];
            $doc_status = $data['trade_conn_dtl']['document_upload_status'];
            $payment_status = $data['trade_conn_dtl']['payment_status'];
            $level_pending_status = $data['trade_conn_dtl']['pending_status'];
            $data['owner_list'] = $this->model_firm_owner_name->applicantdetails_md5($id);
            $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
            $data['level_sent_back_data'] = $this->model_trade_document->sentBackDetailRemark($apply_licence_id);

            

            $iw = 0;
            $iwk = 0;


            //echo $iwk;
            //echo $iw;
            if ($iwk == $iw) 
            {
                $data['owner_doc_upload_stts'] = 'true';
            }

            $temp = true;

            $data['doc_details'] = $this->trade_view_application_doc_model->getdocdet_by_appid($apply_licence_id);

            $data['doc_count'] = $this->model_application_doc->check_upload_bo_doc_count($apply_licence_id);
            //print_var($data['doc_details']);exit;   

            $data['doc_upload_count'] = $this->model_application_doc->check_upload_doc_count_all($apply_licence_id);

            foreach ($data['doc_details'] as $key1 => $value1) 
            {
                $data['doc_details'][$key1]['docfor'] = $this->model_trade_document->getDocumentappList($data['trade_conn_dtl']['application_type_id'], $value1['doc_for']);
                $data['doc_details'][$key1]['countrejectdoc'] = $this->model_application_doc->count_rejected_document($apply_licence_id, $value1['doc_for']);
                $data['doc_details'][$key1]['countuploaddoc'] = $this->model_application_doc->count_upload_document($apply_licence_id, $value1['doc_for']);
            }


            $data['temp'] = $temp;

            if ($this->request->getMethod() == 'post') 
            {   //print_var($_POST);die;
                if (isset($_POST['btn_doc'])) 
                {
                    $cnt = $_POST['btn_doc'];
                    $ownerid =  $this->request->getVar('firm_owner_dtl_id' . $cnt);
                    $doc_name = $_POST['doc_for' . $cnt];
                    if ($ownerid) 
                    {
                        $rules = [
                            'doc_path' . $cnt => [
                                'rules' => 'uploaded[doc_path' . $cnt . ']|max_size[doc_path' . $cnt . ',30720]|ext_in[doc_path' . $cnt . ',pdf]',
                                'errors' => [
                                    "uploaded[doc_path$cnt]" => "$doc_name is required",
                                    "max_size[doc_path$cnt,30720]" => $doc_name . ' Size Not Greater Than 30720 bite',
                                    "ext_in[doc_path$cnt,pdf]" => $doc_name . ' Hase Not Valied extension'
                                ]
                            ],
                            'doc_mstr_id' . $cnt . '' => 'required',
                        ];
                    } 
                    else 
                    {
                        $rules = [
                            "doc_path$cnt" => [
                                'rules' => "uploaded[doc_path$cnt]|max_size[doc_path$cnt,30720]|ext_in[doc_path$cnt,pdf]",
                                'errors' => [
                                    "uploaded[doc_path$cnt]" => "$doc_name is required",
                                    "max_size[doc_path$cnt,30720]" => $doc_name . ' Size Not Greater Than 30720 bite',
                                    "ext_in[doc_path$cnt,pdf]" => $doc_name . ' Hase Not Valied extension'
                                ]
                            ],
                            'doc_mstr_id' . $cnt . '' => 'required',
                        ];
                    }

                    if ($this->validate($rules)) 
                    {
                        $doc_path = $this->request->getFile('doc_path' . $cnt);

                        if ($doc_path->IsValid()  && !$doc_path->hasMoved()) 
                        {
                            try
                            {
                                $this->db->transBegin();
                                $ownerid =  $this->request->getVar('firm_owner_dtl_id' . $cnt);
                                if ($ownerid) 
                                {
                                    $input = [
                                        'apply_licence_id' => $apply_licence_id,
                                        'doc_for' => $this->request->getVar('doc_for' . $cnt),
                                        'document_id' => $this->request->getVar('doc_mstr_id' . $cnt),
                                        'emp_details_id' => $login_emp_details_id,
                                        'firm_owner_dtl_id' => $this->request->getVar('firm_owner_dtl_id' . $cnt),
                                        'created_on' => date('Y-m-d H:i:s'),
                                    ];
                                   
                                } 
                                else 
                                { 
                                    $input = [
                                        'apply_licence_id' => $apply_licence_id,
                                        'doc_for' => $this->request->getVar('doc_for' . $cnt),
                                        'document_id' => $this->request->getVar('doc_mstr_id' . $cnt),
                                        'emp_details_id' => $login_emp_details_id,
                                        'created_on' => date('Y-m-d H:i:s'),
                                    ];
                                }
                                //print_var($this->model_application_doc->check_upload_doc_exist_reupload($input));
                                if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist_reupload($input)) 
                                {
                                    
                                    $delete_path = WRITEPATH . 'uploads/' . $app_doc_dtl_id['document_path'];
                                    //$delete_path = WRITEPATH.'uploads/'. $ulb_city_nm."/"."trade_doc_dtl/".$app_doc_dtl_id['document_path'];
                                    if (file_exists($delete_path))
                                        // @unlink($delete_path);
                                        deleteFile($delete_path);

                                    $newFileName = md5($app_doc_dtl_id['id']);
                                    $file_ext = $doc_path->getExtension();

                                    $path = $ulb_city_nm . "/" . "trade_doc_dtl";
                                    $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                    $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                    $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);
                                } 
                                else if ($app_doc_dtl_id = $this->model_application_doc->insertData($input)) 
                                {

                                    $newFileName = md5($app_doc_dtl_id);
                                    $file_ext = $doc_path->getExtension();
                                    $path = $ulb_city_nm . "/" . "trade_doc_dtl";

                                    $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                    $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                    $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                                }
                                if ($this->db->transStatus() === FALSE) 
                                {   echo"hear";
                                    $this->db->transRollback();
                                } 
                                else 
                                {
                                    $this->db->transCommit();
                                    return $this->response->redirect(base_url('BO_TBackToCitizen/view/' . $id));
                                }
                            } 
                            catch (Exception $e) 
                            {   
                                
                                echo  $e;
                            }
                        } 
                        else 
                        {
                            $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                            $data['errors'] =   $errMsg;
                            return view('trade/Connection/bo_document_upload', $data);
                        }
                    } 
                    else 
                    {
                        $data['vaidater'] = $this->validator;
                        //print_var($this->validator);
                        $errMsg = $this->validator->listErrors();
                        $data['errors'] =   $errMsg;
                        return view('trade/Connection/bo_document_upload', $data);
                    }
                }
            } 
            else 
            {
                //print_var($data['doc_details'] );
                return view('trade/Connection/bo_document_upload', $data);
            }
        }
    }


    public function send_rmc($id = null)
    {
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $data = (array)null;
        $data['apply_licence_id'] = $id;
        $rec = $this->model_trade_level_pending_dtl->getLevelRecordBtczn(md5($id));

        $receiver_user_type_id = $rec['receiver_user_type_id'];

        $leveldata = [
            'apply_licence_id' => $data['apply_licence_id'],
            'sender_user_type_id' => 0,
            'receiver_user_type_id' => $receiver_user_type_id,
            'created_on' => date('Y-m-d H:i:s'),
            'remarks' => 'Send back to officer against back to citizen',
            'emp_details_id' => $login_emp_details_id,
            'verification_status' => 0
        ];

        // tbl_apply_licence set pending_status=0
        $data['doc_upload_stts'] = $this->model_apply_licence->update_doc_status($leveldata);

        $this->trade_view_application_doc_model->deactivateRejectedDocument($data['apply_licence_id']);


        $level_pending_insrt = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($leveldata);
        return $this->response->redirect(base_url('tradedocument/docview/' . md5($data['apply_licence_id']) . ''));
    }
}

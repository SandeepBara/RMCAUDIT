<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_doc_mstr;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_apartment_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_ward_mstr;
use Predis\Client;
class Apartment extends AlphaController
{
    protected $db;
    protected $model_doc_mstr;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
    protected $model_apartment_mstr;
    protected $model_road_type_mstr;
    protected $redis_client;
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_apartment_mstr = new model_apartment_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->redis_client = new Client();
    }

    function __destruct()
    {
        $this->db->close();
        $this->dbSystem->close();
    }

    public function index()
    {
        $apt_list = $this->redis_client->get("apt_list");
        if (!$apt_list) {
            $apt_list = $this->model_apartment_mstr->apartmentList();
            $this->redis_client->set("apt_list", json_encode($apt_list));
        } else {
            $apt_list = json_decode($apt_list, true);
        }
        $data['apt_list'] = $apt_list;
        return view('master/apartment_list', $data);
    }
    public function create($id = null)
    {
        $data = (array)null;
        helper(['form']);
        $data['road_type'] = $this->model_road_type_mstr->getRoadTypeList();
        $Session = session();
        $ulb = $Session->get('ulb_dtl');

        $data['wardList'] = $this->model_ward_mstr->allWardList(["ulb_mstr_id" => $ulb['ulb_mstr_id']]);
        if ($this->request->getMethod() == 'post') {
            $this->redis_client->del("apt_list");
            $this->redis_client->del("saf_master_list");
            if ($this->request->getVar('id') == "") // insert
            {
                $input = [
                    'apt_name' => $this->request->getVar('apt_name'),
                    'apt_code' => $this->request->getVar('apt_code'),
                    'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                    'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                    'apt_address' => $this->request->getVar('apt_address'),
                    'water_hrvst_status' => $this->request->getVar('water_hrvesting'),
                    'no_of_block' => $this->request->getVar('no_of_blocks'),
                    'apt_img_file_path' => $this->request->getFile('apt_img_file_path'),
                    'wtr_img_file_path' => $this->request->getFile('wtr_img_file_path')
                ];

                if ($input['water_hrvst_status'] == 'yes') {
                    $input['water_hrvst_status'] = 1;
                } else {
                    $input['water_hrvst_status'] = 0;
                }
                if ($input['no_of_block'] == 0) {
                    $input['no_of_block'] = 0;
                    $input['is_blocks'] = 0;
                } else {
                    $input['is_blocks'] = 1;
                }
                $input['emp_dtl_id'] = 200; //dummy

                $data['inserted_result'] = $this->model_apartment_mstr->insertApartment($input);
                if ($data['inserted_result']) {

                    if ($input['apt_img_file_path']->isValid() && !$input['apt_img_file_path']->hasMoved()) {
                        $apt_img_ext = $input['apt_img_file_path']->getExtension();
                        $imgName = $data['inserted_result'] . '_apartment' . '.' . $apt_img_ext; //inserted id
                        $input['apt_img_file_path']->move(WRITEPATH . 'uploads/' . $ulb["city"] . '/apartment' . '/', $imgName);
                        // $input['apt_img_file_path'] == $imgName;
                        $img_path['apt_path'] = $imgName;
                    }
                    if ($input['water_hrvst_status'] == 'yes') {
                        if ($input['wtr_img_file_path']->isValid() && !$input['wtr_img_file_path']->hasMoved()) {
                            $wtr_hv_img_ext = $input['wtr_img_file_path']->getExtension();
                            $imgName2 = $data['inserted_result'] . '_water_hv' . '.' . $wtr_hv_img_ext;
                            $input['wtr_img_file_path']->move(WRITEPATH . 'uploads/' . $ulb["city"] . '/apartment' . '/', $imgName2);
                            // $input['wtr_img_file_path'] == $imgName;
                            $img_path['wtr_hv_path'] = $imgName2;
                        }
                    } else {
                        $img_path['wtr_hv_path'] = "";
                    }


                    $data['update_image_path'] = $this->model_apartment_mstr->updateImagePath($data['inserted_result'], $img_path);
                    flashToast("message", "Apartment Added Successfully.");
                    return $this->response->redirect(base_url('Apartment/index'));
                }
            } else {
                $id = $this->request->getVar('id');
                $apt_data_by_id = $this->model_apartment_mstr->getApartmentById($id);
                $water_hrvst_status_old = $apt_data_by_id['water_harvesting_status'];
                $input = [
                    'apt_name' => $this->request->getVar('apt_name'),
                    'apt_code' => $this->request->getVar('apt_code'),
                    'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                    'road_type_mstr_id' => $this->request->getVar('road_type_mstr_id'),
                    'apt_address' => $this->request->getVar('apt_address'),
                    'water_hrvst_status' => $this->request->getVar('water_hrvesting'),
                    'no_of_block' => $this->request->getVar('no_of_blocks'),
                    'apt_img_file_path' => $this->request->getFile('apt_img_file_path'),
                    'wtr_img_file_path' => $this->request->getFile('wtr_img_file_path'),
                    'apt_img_prev_path' => $this->request->getVar('apt_img_prev_path'),
                    'wtr_img_prev_path' => $this->request->getVar('wtr_img_prev_path'),
                ];
                // print_r($input['no_of_block']);
                // return;
                $input['id'] = $id;
                //if previous image is not equal to current image then push
                if ($input['apt_img_file_path'] == '') {
                    $input['apt_path'] = $input['apt_img_prev_path'];
                    echo "file is empty " . $input['apt_path'];
                } else {
                    if ($input['apt_img_file_path']->isValid() && !$input['apt_img_file_path']->hasMoved()) {
                        $apt_img_ext = $input['apt_img_file_path']->getExtension();
                        $imgName =  $input['id'] . '_apartment' . '.' . $apt_img_ext;
                        $input['apt_img_file_path']->move(WRITEPATH . 'uploads/' . $ulb["city"] . '/apartment' . '/', $imgName);
                        $input['apt_path'] = $imgName;
                    }
                }

                if ($input['wtr_img_file_path'] == '') {
                    $input['wtr_hv_path'] = $input['wtr_img_prev_path'];
                    echo "water file is empty " . $input['wtr_hv_path'];
                } else {
                    if ($input['wtr_img_file_path']->isValid() && !$input['wtr_img_file_path']->hasMoved()) {
                        $wtr_hv_img_ext = $input['wtr_img_file_path']->getExtension();
                        $imgName2 =  $input['id'] . '_water_hv' . '.' . $wtr_hv_img_ext;
                        $input['wtr_img_file_path']->move(WRITEPATH . 'uploads/' . $ulb["city"] . '/apartment' . '/', $imgName2);
                        $input['wtr_hv_path'] = $imgName2;
                    }
                }



                if ($input['water_hrvst_status'] == 'yes') {
                    $input['water_hrvst_status'] = 1;
                } else {
                    $input['water_hrvst_status'] = 0;
                }
                if ($input['no_of_block'] == 0) {
                    $input['no_of_block'] = 0;
                    $input['is_blocks'] = 0;
                } else {
                    $input['is_blocks'] = 1;
                }
                $input['emp_dtl_id'] = 200; //dummy

                // $fyr =  getFY(date('Y-m-d'));
                $fyr =  getFY(date('Y-m-d')); //replace via current date



                // echo "old status ".$water_hrvst_status_old;
                // echo "<br/>new status ".$input['water_hrvst_status'];
                // return;
                $id = $this->request->getVar('id');
                $input['id'] = $id;

                $data['updated_result'] = $this->model_apartment_mstr->updateApartment($input['id'], $input);
                // echo "apt updated<br/>";
                // print_r($data['updated_result']);
                // return;

                //update tbl_demand of consumer living in apartment
                // 1 check if water_hrvst_status is same or not if not then run forward
                if ($water_hrvst_status_old != $input['water_hrvst_status']) { //if water hrvst status changed
                    // 2 update tbl_prop_tax
                    // 3 update tbl_prop_demand
                    // echo "status different";
                    // return;
                    // echo "changed status";
                    if ($input['water_hrvst_status'] == 0) {
                        // echo "no water hrvest then additional ++";
                        // die;
                        // echo "000";
                        // return;
                        $data['add_prop_tax'] = $this->model_apartment_mstr->addUpdatePropTax($input['id'], $fyr);
                        $data['add_hrvest_demand'] = $this->model_apartment_mstr->addDemand($input['id'], $fyr);
                        $data['remove_wtr_hrvst'] = $this->model_apartment_mstr->remove_wtr_hrvst($input['id']);

                        flashToast("message", "Rainwater Harvesting removed Successfully !!");
                    } else {
                        // echo "water hrvst no additional -- ";
                        // die;
                        // echo "111";
                        // return;
                        $data['remove_prop_tax'] = $this->model_apartment_mstr->removeUpdatePropTax($input['id'], $fyr);
                        $data['remove_hrvest_demand'] = $this->model_apartment_mstr->removeDemand($input['id'], $fyr);
                        $data['add_wtr_hrvst'] = $this->model_apartment_mstr->add_wtr_hrvst($input['id']);

                        flashToast("message", "Rainwater Harvesting added Successfully !!");

                    }
                } else {
                    // echo "no change in status";
                    // die;
                    flashToast("message", "No Change in status!!");

                }

                // return;
                // flashToast("message", "Apartment Updated Successfully.");
                return $this->response->redirect(base_url('Apartment/index'));
            }
        } else if (isset($id)) {
            //retrive data
            $data['title'] = "Update";
            $data['apartment_data'] = $this->model_apartment_mstr->getApartmentById($id);

            // print_var($data);
            // return;
            return view('master/apartment_add_update', $data);
        } else {


            $data['title'] = "Add";
            return view('master/apartment_add_update', $data);
        }
    }
    public function delete($id = null)
    {
        $data['deleted_data'] = $this->model_apartment_mstr->deleteApartment($id);
        return $this->response->redirect(base_url('index'));
    }
}

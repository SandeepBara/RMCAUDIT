<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_fixed_arr_building_mstr;
use App\Models\model_arr_building_mstr;


class Fixed_Arr_Building extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_fixed_arr_building_mstr;
    protected $model_arr_building_mstr;
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($system_db = dbSystem()){
            $this->dbSystem = db_connect($system_db);
        }
        $this->model_fixed_arr_building_mstr = new model_fixed_arr_building_mstr($this->dbSystem);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
    }

    public function index()
    {
        $data=array();
        $data["annualRentalRate"]=$this->model_arr_building_mstr->areaBuildingList();
        return view('master/arr_list', $data);
    }

    public function fixedArrBuilding()
    {
       if($this->request->getMethod()=='post')
       {
            $mrr = $this->request->getVar('rate'); // Monthly Rental Rate
            $yrr = $mrr*12; // Yearly Rental Rate
            $date_of_effect = date("Y-m-d", strtotime($this->request->getVar('effect_from')));
            $gateArr = $this->model_fixed_arr_building_mstr->gateAllData();
            foreach ($gateArr as $value)
            {
                $road_type_mstr_id = $value['road_type_mstr_id'];
                $const_type_mstr_id = $value['const_type_mstr_id'];
                $rate=$value["rate"];
                $cal_rate = ($rate*$yrr);
                $result = $this->model_arr_building_mstr->insertFixedArrData($road_type_mstr_id,$const_type_mstr_id,$cal_rate,$date_of_effect, $calculated_rate=0);
            }
            if($result)
            {
                flashToast('message', "Annual Rental Rate Successfully");
				return redirect()->to(base_url('Fixed_Arr_Building/index/'));
            }
            else
            {
                flashToast('message', "Fail to Calculate ARR");
            }
       }
       else
       {
           return view('master/fixed_arr_building');
       }
    } 
}
?>

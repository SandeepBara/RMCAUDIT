<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Water_Transaction_Model;

class WaterDailyCollection extends AlphaController
{
    protected $db;
    protected $water;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->db);
    }
    public function report()
    {
        $data =(array)null;
        if($this->request->getMethod()=='post')
        {

            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['transaction_type'] = $this->request->getVar('transaction_type');   
            return view('water/report/water_daily_collection',$data);
        }
        else{
           // return view('water/report/water_daily_collection');
        }
    }
}
?>

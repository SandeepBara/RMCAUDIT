<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeItemsMstrModel;

class traedelicenceapply extends AlphaController
{
    protected $db;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->tradeitemsmstrmodel =  new tradeitemsmstrmodel($this->db);
    }


}
?>

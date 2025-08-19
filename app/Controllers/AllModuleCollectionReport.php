<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\TradeTransactionModel;
use App\Models\Water_Transaction_Model;

class AllModuleCollectionReport extends AlphaController
{
    protected $db;
    protected $water;
    protected $trade;
    protected $model_transaction; 
    protected $TradeTransactionModel; 
    protected $Water_Transaction_Model;    
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form','utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
    }

    function __destruct() {
		$this->db->close();
		$this->trade->close();
        $this->water->close();
	}

    public function paymentModeWiseSummery_old() {
        $session = session();
        
        $data = arrFilterSanitizeString($this->request->getVar());
        $data["ulb_dtl"] = $session->get("ulb_dtl");
        $from_date = isset($data['from_date'])?$data['from_date']:"";
        $upto_date = isset($data['upto_date'])?$data['upto_date']:"";

        if ($from_date!="" && $upto_date!="") {
            /*
            $sql = "(SELECT 'Holding' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft
                    FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Saf' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft
                    FROM tbl_transaction WHERE tran_type='Saf' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL
                    (SELECT 'Government Building' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft
                    FROM tbl_govt_saf_transaction WHERE tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Water' AS mode_type,
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft
                    FROM (
                        SELECT 
                        tbl_water_tran.cash_count, tbl_water_tran.cheque_count, tbl_water_tran.dd_count, tbl_water_tran.online_count, tbl_water_tran.card_count, tbl_water_tran.neft_count,
                        tbl_water_tran.cash, tbl_water_tran.cheque, tbl_water_tran.dd, tbl_water_tran.online, tbl_water_tran.card, tbl_water_tran.neft
                    FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("water")."'::text, 'SELECT 
                        CASE WHEN payment_mode=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN payment_mode=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN payment_mode=''DD'' THEN COUNT(*) END AS dd_count, CASE WHEN payment_mode=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN payment_mode=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN payment_mode=''NEFT'' THEN COUNT(*) END AS neft_count,
                        CASE WHEN payment_mode=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN payment_mode=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN payment_mode=''DD'' THEN SUM(paid_amount) END AS dd, CASE WHEN payment_mode=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN payment_mode=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN payment_mode=''NEFT'' THEN SUM(paid_amount) END AS neft
                        FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY payment_mode'::text) tbl_water_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC,
                        cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC)
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Trade' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft
                    FROM (
                        SELECT tbl_trade_tran.cash_count, tbl_trade_tran.cheque_count, tbl_trade_tran.dd_count, tbl_trade_tran.online_count, tbl_trade_tran.card_count, tbl_trade_tran.neft_count,
                        tbl_trade_tran.cash, tbl_trade_tran.cheque, tbl_trade_tran.dd, tbl_trade_tran.online, tbl_trade_tran.card, tbl_trade_tran.neft
                    FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("trade")."'::text, 'SELECT 
                        CASE WHEN UPPER(payment_mode)=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN COUNT(*) END AS dd_count, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN UPPER(payment_mode)=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN UPPER(payment_mode)=''NEF'' THEN COUNT(*) END AS neft_count,
                        CASE WHEN UPPER(payment_mode)=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN SUM(paid_amount) END AS dd, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN UPPER(payment_mode)=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN UPPER(payment_mode)=''NEF'' THEN SUM(paid_amount) END AS NEFT
                        FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY UPPER(payment_mode)'::text) tbl_trade_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC, cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC)
                    ) AS tbl)
            ";
            */
            $sql = "(SELECT 'Holding' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count,CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft,CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs
                    FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Saf' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count, CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft, CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs
                    FROM tbl_transaction WHERE tran_type='Saf' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL
                    (SELECT 'Government Building' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count, CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft,CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs
                    FROM tbl_govt_saf_transaction WHERE tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Water' AS mode_type,
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs
                    FROM (
                        SELECT 
                        tbl_water_tran.cash_count, tbl_water_tran.cheque_count, tbl_water_tran.dd_count, tbl_water_tran.online_count, tbl_water_tran.card_count, tbl_water_tran.neft_count,tbl_water_tran.rtgs_count,
                        tbl_water_tran.cash, tbl_water_tran.cheque, tbl_water_tran.dd, tbl_water_tran.online, tbl_water_tran.card, tbl_water_tran.neft, tbl_water_tran.rtgs
                    FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("water")."'::text, 'SELECT 
                        CASE WHEN payment_mode=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN payment_mode=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN payment_mode=''DD'' THEN COUNT(*) END AS dd_count, CASE WHEN payment_mode=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN payment_mode=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN payment_mode=''NEFT'' THEN COUNT(*) END AS neft_count,CASE WHEN upper(payment_mode)=''RTGS'' THEN COUNT(*) END AS rtgs_count,
                        CASE WHEN payment_mode=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN payment_mode=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN payment_mode=''DD'' THEN SUM(paid_amount) END AS dd, CASE WHEN payment_mode=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN payment_mode=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN payment_mode=''NEFT'' THEN SUM(paid_amount) END AS neft,CASE WHEN upper(payment_mode)=''RTGS'' THEN SUM(paid_amount) END AS rtgs
                        FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY payment_mode'::text) tbl_water_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC,rtgs_count NUMERIC,
                        cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC,rtgs NUMERIC)
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Trade' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs
                    FROM (
                        SELECT tbl_trade_tran.cash_count, tbl_trade_tran.cheque_count, tbl_trade_tran.dd_count, tbl_trade_tran.online_count, tbl_trade_tran.card_count, tbl_trade_tran.neft_count,tbl_trade_tran.rtgs_count,
                        tbl_trade_tran.cash, tbl_trade_tran.cheque, tbl_trade_tran.dd, tbl_trade_tran.online, tbl_trade_tran.card, tbl_trade_tran.neft, tbl_trade_tran.rtgs
                    FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("trade")."'::text, 'SELECT 
                        CASE WHEN UPPER(payment_mode)=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN COUNT(*) END AS dd_count, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN UPPER(payment_mode)=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN UPPER(payment_mode)=''NEF'' THEN COUNT(*) END AS neft_count,CASE WHEN upper(payment_mode)=''RTGS'' THEN COUNT(*) END AS rtgs_count,
                        CASE WHEN UPPER(payment_mode)=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN SUM(paid_amount) END AS dd, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN UPPER(payment_mode)=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN UPPER(payment_mode)=''NEF'' THEN SUM(paid_amount) END AS NEFT,CASE WHEN upper(payment_mode)=''RTGS'' THEN SUM(paid_amount) END AS rtgs
                        FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY UPPER(payment_mode)'::text) tbl_trade_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC,rtgs_count NUMERIC, cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC,rtgs NUMERIC)
                    ) AS tbl)
            ";
            /* $sqlProp = "SELECT
                        'Property' AS mode_type, COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card
                    FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl"; */
            if ($result = $this->db->query($sql)->getResultArray()) {
                //print_var($result);
                $data["result"] = $result;
            }
            //die();
        }
        return view('report/payment_mode_wise_summery', $data);
    }


    public function paymentModeWiseSummery() {
        $session = session();
        
        $data = arrFilterSanitizeString($this->request->getVar());
        $data["ulb_dtl"] = $session->get("ulb_dtl");
        $from_date = isset($data['from_date'])?$data['from_date']:"";
        $upto_date = isset($data['upto_date'])?$data['upto_date']:"";

        if ($from_date!="" && $upto_date!="") {
            $sql = "(SELECT 'Holding' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(upi_count), 0) AS upi_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs,
                        COALESCE(SUM(upi), 0) AS upi
                    FROM (SELECT 
                            CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count,CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                            CASE WHEN upper(tran_mode)='UPI' THEN COUNT(*) END AS upi_count,
                            CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft,CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs,
                            CASE WHEN upper(tran_mode)='UPI' THEN SUM(payable_amt) END AS upi
                        FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Saf' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(upi_count), 0) AS upi_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs,
                        COALESCE(SUM(upi), 0) AS upi
                    FROM (SELECT 
                            CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count, CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                            CASE WHEN upper(tran_mode)='UPI' THEN COUNT(*) END AS upi_count,
                            CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft, CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs,
                            CASE WHEN upper(tran_mode)='UPI' THEN SUM(payable_amt) END AS upi
                        FROM tbl_transaction WHERE tran_type='Saf' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL
                    (SELECT 'Government Building' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(upi_count), 0) AS upi_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs,
                        COALESCE(SUM(upi), 0) AS upi
                    FROM (SELECT 
                            CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count, CASE WHEN tran_mode='NEFT' THEN COUNT(*) END AS neft_count, CASE WHEN upper(tran_mode)='RTGS' THEN COUNT(*) END AS rtgs_count,
                            CASE WHEN upper(tran_mode)='UPI' THEN COUNT(*) END AS upi_count,
                            CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card, CASE WHEN tran_mode='NEFT' THEN SUM(payable_amt) END AS neft,CASE WHEN upper(tran_mode)='RTGS' THEN SUM(payable_amt) END AS rtgs,
                            CASE WHEN upper(tran_mode)='UPI' THEN SUM(payable_amt) END AS upi
                        FROM tbl_govt_saf_transaction WHERE tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Water' AS mode_type,
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count, COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(upi_count), 0) AS upi_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs,
                        COALESCE(SUM(upi), 0) AS upi
                    FROM (
                        SELECT 
                            tbl_water_tran.cash_count, tbl_water_tran.cheque_count, tbl_water_tran.dd_count, tbl_water_tran.online_count, tbl_water_tran.card_count, tbl_water_tran.neft_count,tbl_water_tran.rtgs_count,
                            tbl_water_tran.upi_count,
                            tbl_water_tran.cash, tbl_water_tran.cheque, tbl_water_tran.dd, tbl_water_tran.online, tbl_water_tran.card, tbl_water_tran.neft, tbl_water_tran.rtgs,
                            tbl_water_tran.upi
                        FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("water")."'::text, 
                            'SELECT 
                                CASE WHEN payment_mode=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN payment_mode=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN payment_mode=''DD'' THEN COUNT(*) END AS dd_count, CASE WHEN payment_mode=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN payment_mode=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN payment_mode=''NEFT'' THEN COUNT(*) END AS neft_count,CASE WHEN upper(payment_mode)=''RTGS'' THEN COUNT(*) END AS rtgs_count,
                                CASE WHEN upper(payment_mode)=''UPI'' THEN COUNT(*) END AS upi_count,
                                CASE WHEN payment_mode=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN payment_mode=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN payment_mode=''DD'' THEN SUM(paid_amount) END AS dd, CASE WHEN payment_mode=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN payment_mode=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN payment_mode=''NEFT'' THEN SUM(paid_amount) END AS neft,CASE WHEN upper(payment_mode)=''RTGS'' THEN SUM(paid_amount) END AS rtgs,
                                CASE WHEN upper(payment_mode)=''UPI'' THEN SUM(paid_amount) END AS upi
                            FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY payment_mode'::text
                        ) 
                        tbl_water_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC,rtgs_count NUMERIC,
                            upi_count NUMERIC,
                            cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC,rtgs NUMERIC,
                            upi NUMERIC
                        )
                    ) AS tbl)
                    UNION ALL 
                    (SELECT 'Trade' AS mode_type, 
                        COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, COALESCE(SUM(neft_count), 0) AS neft_count,COALESCE(SUM(rtgs_count), 0) AS rtgs_count,
                        COALESCE(SUM(upi_count), 0) AS upi_count,
                        COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card, COALESCE(SUM(neft), 0) AS neft, COALESCE(SUM(rtgs), 0) AS rtgs,
                        COALESCE(SUM(upi), 0) AS upi
                    FROM (
                        SELECT tbl_trade_tran.cash_count, tbl_trade_tran.cheque_count, tbl_trade_tran.dd_count, tbl_trade_tran.online_count, tbl_trade_tran.card_count, tbl_trade_tran.neft_count,tbl_trade_tran.rtgs_count,
                            tbl_trade_tran.upi_count,
                            tbl_trade_tran.cash, tbl_trade_tran.cheque, tbl_trade_tran.dd, tbl_trade_tran.online, tbl_trade_tran.card, tbl_trade_tran.neft, tbl_trade_tran.rtgs,
                            tbl_trade_tran.upi
                        FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("trade")."'::text, 
                            'SELECT 
                                CASE WHEN UPPER(payment_mode)=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN COUNT(*) END AS dd_count, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN UPPER(payment_mode)=''CARD'' THEN COUNT(*) END AS card_count, CASE WHEN UPPER(payment_mode)=''NEF'' THEN COUNT(*) END AS neft_count,CASE WHEN upper(payment_mode)=''RTGS'' THEN COUNT(*) END AS rtgs_count,
                                CASE WHEN upper(payment_mode)=''UPI'' THEN COUNT(*) END AS upi_count,
                                CASE WHEN UPPER(payment_mode)=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN UPPER(payment_mode)=''DD''  OR UPPER(payment_mode)=''DEMAND DRAFT'' THEN SUM(paid_amount) END AS dd, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN UPPER(payment_mode)=''CARD'' THEN SUM(paid_amount) END AS card, CASE WHEN UPPER(payment_mode)=''NEF'' THEN SUM(paid_amount) END AS NEFT,CASE WHEN upper(payment_mode)=''RTGS'' THEN SUM(paid_amount) END AS rtgs,
                                CASE WHEN upper(payment_mode)=''UPI'' THEN SUM(paid_amount) END AS upi
                            FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY UPPER(payment_mode)'::text
                        ) 
                        tbl_trade_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, neft_count NUMERIC,rtgs_count NUMERIC, 
                            upi_count NUMERIC,
                            cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC, neft NUMERIC,rtgs NUMERIC,
                            upi NUMERIC
                        )
                    ) AS tbl)";
            /* $sqlProp = "SELECT
                        'Property' AS mode_type, COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                    FROM (SELECT 
                        CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card
                    FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                    ) AS tbl"; */
                    // dd($sql);
            if ($result = $this->db->query($sql)->getResultArray()) {
                //print_var($result);
                $data["result"] = $result;
            }
            //die();
        }
        return view('report/payment_mode_wise_summery', $data);
    }

    public function report()
    {
        $data =(array)null;
        $allModuleCollection=[];
        $total =0;
        $groundTotal =0;
        $totalProperty =0;
        $totalTrade =0;
        $totalWater =0;
        if($this->request->getMethod()=='post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['to_date'] = $inputs['to_date'];
            $data['from_date'] = $inputs['from_date'];

            $water_coll=$this->Water_Transaction_Model->getTotalPaidAmountwithCountTrans($data['from_date'],$data['to_date']);
            $data['water_new_coll']=0.00;
            foreach($water_coll as $val) {
                if($val['transaction_type']=='New Connection' or $val['transaction_type']=='Site Inspection')
                {
                    $data['water_new_coll']=$data['water_new_coll']+$val['paid_amount'];
                    $data['water_new_count']=$val['count'];
                } else {
                    $data['demand_coll']=$val['paid_amount'];
                    $data['water_dmd_count']=$val['count'];
                }
            }
            $prop_coll=$this->model_transaction->getTotalPaidAmountwithCountTrans($data['from_date'],$data['to_date']);
            foreach($prop_coll as $val2) {
                if($val2['tran_type']=='Saf') {
                    $data['saf_coll']=$val2['paid_amount'];
                    $data['saf_count']=$val2['count'];
                } elseif($val2['tran_type']=='Property') {
                    $data['prop_coll']=$val2['paid_amount'];
                    $data['prop_count']=$val2['count'];
                }
            }
            if ($goct_saf_coll=$this->model_transaction->getGBSAFTotalPaidAmountwithCountTrans($data['from_date'],$data['to_date'])) {
                $data['govt_saf_coll']=$goct_saf_coll['paid_amount'];
                $data['govt_saf_count']=$goct_saf_coll['count'];
            }
            
            $trade_coll=$this->TradeTransactionModel->getTotalPaidAmountwithCountTrans($data['from_date'],$data['to_date']);
            $data['trade_count']=$trade_coll['count'];
            $data['trade_coll']=$trade_coll['paid_amount'];
        }
        return view('report/all_module_collection_report',$data);
    }
}
?>

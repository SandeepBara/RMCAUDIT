<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\TradeTransactionModel;
use App\Models\Water_Transaction_Model;

class AllModuleCollection extends AlphaController
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

    public function report(){
        $data =(array)null;  
        $from_date = date('Y-m-d');
        $upto_date = date('Y-m-d');
        $mode = "ALL";   
        //print_var($this->TradeTransactionModel);
        if($this->request->getMethod()=='post'){
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['to_date'] = $inputs['to_date'];
            $data['from_date'] = $inputs['from_date'];
            $data['tran_mode'] = $inputs['tran_mode'];
            $from_date = $data['from_date'];
            $upto_date = $data['to_date'];
            $mode =  strtoupper($data['tran_mode']);
            // $data['allmodulecollection'] = $this->TradeTransactionModel->getAllTotalBydate($data['tran_mode'],$data['from_date'],$data['to_date']);
            // return view('report/all_module_collection',$data);
        }
        else
        {
            $to_date = date('Y-m-d');
            // $data['allmodulecollection'] = $this->TradeTransactionModel->getAllTotalBydate('ALL',$to_date,$to_date); 
            // return view('report/all_module_collection',$data);
        }
        $ttran_mode="";
        $wtran_mode ="";
        $ptran_mode ="";
        if($mode!="ALL")
        {
            $wtran_mode = " AND UPPER(payment_mode) =UPPER (''$mode'')";
            $ttran_mode = " AND UPPER(payment_mode) =UPPER ('$mode')";
            $ptran_mode = " AND UPPER(tran_mode) =UPPER (''$mode'')";            
        }
        /* $sql = "(SELECT 'Holding' AS mode_type, 
                    COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count,
                    COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                FROM (SELECT 
                    CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count,
                    CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card
                FROM tbl_transaction WHERE tran_type='Property' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_date
                ) AS tbl)
                UNION ALL 
                (SELECT 'Saf' AS mode_type, 
                    COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count,
                    COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                FROM (SELECT 
                    CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count,
                    CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card
                FROM tbl_transaction WHERE tran_type='Saf' AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                ) AS tbl)
                UNION ALL
                (SELECT 'Government Building' AS mode_type, 
                    COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count,
                    COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                FROM (SELECT 
                    CASE WHEN tran_mode='CASH' THEN COUNT(*) END AS cash_count, CASE WHEN tran_mode='CHEQUE' THEN COUNT(*) END AS cheque_count, CASE WHEN tran_mode='DD' THEN COUNT(*) END AS dd_count,  CASE WHEN tran_mode='ONLINE' THEN COUNT(*) END AS online_count, CASE WHEN tran_mode='CARD' THEN COUNT(*) END AS card_count,
                    CASE WHEN tran_mode='CASH' THEN SUM(payable_amt) END AS cash, CASE WHEN tran_mode='CHEQUE' THEN SUM(payable_amt) END AS cheque, CASE WHEN tran_mode='DD' THEN SUM(payable_amt) END AS dd, CASE WHEN tran_mode='ONLINE' THEN SUM(payable_amt) END AS online, CASE WHEN tran_mode='CARD' THEN SUM(payable_amt) END AS card
                FROM tbl_govt_saf_transaction WHERE tran_date BETWEEN '".$from_date."' AND '".$upto_date."' AND status IN (1, 2) GROUP BY tran_mode
                ) AS tbl)
                UNION ALL 
                (SELECT 'Water' AS mode_type,
                    COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count, 
                    COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                FROM (
                    SELECT 
                    tbl_water_tran.cash_count, tbl_water_tran.cheque_count, tbl_water_tran.dd_count, tbl_water_tran.online_count, tbl_water_tran.card_count,
                    tbl_water_tran.cash, tbl_water_tran.cheque, tbl_water_tran.dd, tbl_water_tran.online, tbl_water_tran.card
                FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("water")."'::text, 'SELECT 
                    CASE WHEN payment_mode=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN payment_mode=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN payment_mode=''DD'' THEN COUNT(*) END AS dd_count, CASE WHEN payment_mode=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN payment_mode=''CARD'' THEN COUNT(*) END AS card_count,
                    CASE WHEN payment_mode=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN payment_mode=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN payment_mode=''DD'' THEN SUM(paid_amount) END AS dd, CASE WHEN payment_mode=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN payment_mode=''CARD'' THEN SUM(paid_amount) END AS card
                    FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY payment_mode'::text) tbl_water_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, 
                    cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC)
                ) AS tbl)
                UNION ALL 
                (SELECT 'Trade' AS mode_type, 
                    COALESCE(SUM(cash_count), 0) AS cash_count, COALESCE(SUM(cheque_count), 0) AS cheque_count, COALESCE(SUM(dd_count), 0) AS dd_count, COALESCE(SUM(online_count), 0) AS online_count, COALESCE(SUM(card_count), 0) AS card_count,
                    COALESCE(SUM(cash), 0) AS cash, COALESCE(SUM(cheque), 0) AS cheque, COALESCE(SUM(dd), 0) AS dd, COALESCE(SUM(online), 0) AS online, COALESCE(SUM(card), 0) AS card
                FROM (
                    SELECT tbl_trade_tran.cash_count, tbl_trade_tran.cheque_count, tbl_trade_tran.dd_count, tbl_trade_tran.online_count, tbl_trade_tran.card_count,
                    tbl_trade_tran.cash, tbl_trade_tran.cheque, tbl_trade_tran.dd, tbl_trade_tran.online, tbl_trade_tran.card
                FROM dblink('host=".getenv('db.pgsql.hname')." port=".getenv('db.pgsql.port')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=".dbConfig("trade")."'::text, 'SELECT 
                    CASE WHEN UPPER(payment_mode)=''CASH'' THEN COUNT(*) END AS cash_count, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN COUNT(*) END AS cheque_count, CASE WHEN UPPER(payment_mode)=''DD'' THEN COUNT(*) END AS dd_count, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN COUNT(*) END AS online_count, CASE WHEN UPPER(payment_mode)=''CARD'' THEN COUNT(*) END AS card_count,
                    CASE WHEN UPPER(payment_mode)=''CASH'' THEN SUM(paid_amount) END AS cash, CASE WHEN UPPER(payment_mode)=''CHEQUE'' THEN SUM(paid_amount) END AS cheque, CASE WHEN UPPER(payment_mode)=''DD'' THEN SUM(paid_amount) END AS dd, CASE WHEN UPPER(payment_mode)=''ONLINE'' THEN SUM(paid_amount) END AS online, CASE WHEN UPPER(payment_mode)=''CARD'' THEN SUM(paid_amount) END AS card
                    FROM tbl_transaction WHERE transaction_date BETWEEN ''".$from_date."'' AND ''".$upto_date."'' AND status IN (1, 2) GROUP BY UPPER(payment_mode)'::text) tbl_trade_tran(cash_count NUMERIC, cheque_count NUMERIC, dd_count NUMERIC, online_count NUMERIC, card_count NUMERIC, cash NUMERIC, cheque NUMERIC, dd NUMERIC, online NUMERIC, card NUMERIC)
                ) AS tbl)"; 
        */
        $sql = "WITH trade AS (
                        SELECT COALESCE(sum(tbl_transaction.paid_amount), 0::numeric) AS trade_amount,            
                        tbl_transaction.transaction_date
                        FROM tbl_transaction 
                        WHERE tbl_transaction.status IN(1,2)  $ttran_mode
                        AND transaction_date BETWEEN '$from_date' AND '$upto_date'
                        GROUP BY tbl_transaction.transaction_date
                    ), 
                property AS (
                        SELECT COALESCE(sum(view_property_transaction.payable_amt), 0::numeric) AS property_amount,           
                        view_property_transaction.tran_date
                        FROM (
                            SELECT tbl_transaction.id,
                                tbl_transaction.prop_dtl_id,
                                tbl_transaction.tran_date,
                                tbl_transaction.tran_no,
                                tbl_transaction.tran_mode_mstr_id,
                                tbl_transaction.penalty_amt,
                                tbl_transaction.discount_amt,
                                tbl_transaction.payable_amt,
                                tbl_transaction.from_fy_mstr_id,
                                tbl_transaction.from_qtr,
                                tbl_transaction.upto_fy_mstr_id,
                                tbl_transaction.upto_qtr,
                                tbl_transaction.remarks,
                                tbl_transaction.pay_gateway_tran_id,
                                tbl_transaction.tran_type,
                                tbl_transaction.tran_verification_status,
                                tbl_transaction.tran_by_emp_details_id,
                                tbl_transaction.created_on,
                                tbl_transaction.status,
                                tbl_transaction.deactive_status,
                                tbl_transaction.verify_status,
                                tbl_transaction.verified_by,
                                tbl_transaction.verify_date,
                                tbl_transaction.ward_mstr_id,
                                tbl_transaction.round_off,
                                tbl_transaction.notification_id
                                FROM dblink('host=".getenv("db.pgsql.hname")." port=".getenv("db.pgsql.port")." user=".getenv("db.pgsql.uname")."  password=".getenv("db.pgsql.pass")." dbname=db_rmc_property'::text,
                                            format('SELECT id, prop_dtl_id, tran_date, tran_no, tran_mode_mstr_id, penalty_amt, discount_amt,   
                                                payable_amt, from_fy_mstr_id, from_qtr, upto_fy_mstr_id, upto_qtr, remarks, 
                                                pay_gateway_tran_id, tran_type, tran_verification_status, tran_by_emp_details_id, created_on,
                                                status, deactive_status, verify_status, verified_by, verify_date, ward_mstr_id, round_off, 
                                                notification_id 
                                            FROM tbl_transaction  
                                            WHERE tbl_transaction.status IN(1, 2) $ptran_mode
                                            AND tran_date BETWEEN %L and %L'::text,'$from_date', '$upto_date')) 
                                            tbl_transaction(id bigint, prop_dtl_id bigint, tran_date date, tran_no text, 
                                                            tran_mode_mstr_id bigint, penalty_amt numeric(18,2), 
                                                            discount_amt numeric(18,2), payable_amt numeric(18,2), from_fy_mstr_id bigint,
                                                            from_qtr integer, upto_fy_mstr_id bigint, upto_qtr integer, remarks text,
                                                            pay_gateway_tran_id text, tran_type text, tran_verification_status integer, 
                                                            tran_by_emp_details_id bigint, created_on timestamp without time zone, 
                                                            status integer, deactive_status integer, verify_status integer,
                                                            verified_by bigint, verify_date date, ward_mstr_id bigint, 
                                                            round_off numeric(18,2), notification_id bigint)
                        ) view_property_transaction
                        GROUP BY tran_date
                    ),
                water AS (
                        SELECT COALESCE(sum(view_water_transaction.paid_amount), 0::numeric) AS water_amount,
                        view_water_transaction.transaction_date
                        FROM (
                        SELECT tbl_transaction.id,
                            tbl_transaction.ward_mstr_id,
                            tbl_transaction.transaction_no,
                            tbl_transaction.transaction_type,
                            tbl_transaction.transaction_date,
                            tbl_transaction.related_id,
                            tbl_transaction.payment_mode,
                            tbl_transaction.penalty,
                            tbl_transaction.rebate,
                            tbl_transaction.paid_amount,
                            tbl_transaction.verify_status,
                            tbl_transaction.verified_by,
                            tbl_transaction.verified_on,
                            tbl_transaction.emp_details_id,
                            tbl_transaction.status,
                            tbl_transaction.total_amount,
                            tbl_transaction.from_month,
                            tbl_transaction.upto_month,
                            tbl_transaction.ip_address,
                            tbl_transaction.notification_id,
                            tbl_transaction.payment_from,
                            tbl_transaction.challan_id,
                            tbl_transaction.sspl_id,
                            tbl_transaction.due_amount,
                            tbl_transaction.amount_adjusted,
                            tbl_transaction.created_on
                            FROM dblink('host=".getenv("db.pgsql.hname")." port=".getenv("db.pgsql.port")." user=".getenv("db.pgsql.uname")."  password=".getenv("db.pgsql.pass")." dbname=db_rmc_water'::text, 
                                        format('SELECT id, ward_mstr_id, transaction_no, transaction_type, transaction_date, related_id, 
                                            payment_mode, penalty,rebate,paid_amount,verify_status, verified_by,    
                                            verified_on, emp_details_id, status, total_amount,from_month, upto_month, ip_address, 
                                            notification_id, payment_from, challan_id, sspl_id, due_amount, amount_adjusted, 
                                            created_on 
                                        FROM tbl_transaction
                                        WHERE status IN(1, 2) $wtran_mode
                                                AND transaction_date BETWEEN %L and %L'::text,'$from_date', '$upto_date'))
                                        tbl_transaction(id bigint, ward_mstr_id bigint, transaction_no text, 
                                                        transaction_type text, transaction_date date, related_id bigint, 
                                                        payment_mode text, penalty numeric(18,2), rebate numeric(18,2), 
                                                        paid_amount numeric(18,2), verify_status integer, 
                                                        verified_by bigint, verified_on date, emp_details_id bigint, 
                                                        status integer, total_amount numeric(18,2), from_month date, 
                                                        upto_month date, ip_address text, notification_id bigint, 
                                                        payment_from text, challan_id bigint, sspl_id bigint, 
                                                        due_amount numeric(18,2), amount_adjusted numeric(18,2),
                                                        created_on timestamp without time zone)
                        )view_water_transaction 
                        GROUP BY view_water_transaction.transaction_date
                    )
                SELECT COALESCE(property.tran_date,water.transaction_date,trade.transaction_date) as m_date,
                    COALESCE(property.property_amount,0) as m_property_amount,    
                    COALESCE(water.water_amount,0) as m_water_amount,   
                    COALESCE(trade.trade_amount,0) as m_trade_amount,    
                    (COALESCE(property.property_amount,0) + COALESCE(water.water_amount,0) + COALESCE(trade.trade_amount,0)) AS m_total_amount
                FROM property
                full JOIN trade ON property.tran_date = trade.transaction_date
                full JOIN water ON property.tran_date = water.transaction_date
                ORDER BY COALESCE(property.tran_date,water.transaction_date,trade.transaction_date)
                ";
        //print_var($sql);//die;
        $data['allmodulecollection'] = $this->TradeTransactionModel->row_query($sql);
        return view('report/all_module_collection',$data);
    }
}
?>

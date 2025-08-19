<?php
    session_start();
    ob_start();
    include '../common/inc/config.inc.php';
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $querydemsummry=pg_query($_SESSION["db_water"],"select * from get_demand_summary(".$appmas_id.") as (cons_connection_type integer,current_amount numeric, curr_demand_from bigint,curr_demand_upto bigint,curr_months bigint,last_meter_reading numeric,amount_adjusted numeric,
        adjustment_month bigint,arrear_amount numeric,arr_demand_from bigint, arr_demand_upto bigint, arr_months bigint,
        ulbarr_amount numeric,ulbarrear_left numeric,penalty numeric,adjustment_amount numeric,advance_amount numeric,amount_payable numeric)") or die("An error occurred.\n");
        $fetchdemsumm=pg_fetch_array($querydemsummry);
        $amount_payable=$fetchdemsumm['amount_payable']; 


    if($_GET['razorpay_payment_id'] and $_GET['razorpay_order_id'] and $_GET['razorpay_signature'] and $_GET['type'] and $_GET['type']==200)
    {   
        
        $merchant_id = 'GZ1RB9lnheOVK6';
        $razorpay_payment_id=$_GET['razorpay_payment_id'];
        $razorpay_order_id=$_GET['razorpay_order_id'];
        
        $sql="insert into tbl_razor_pay_request(pg_master_id,merchant_id,customer_id,txn_amount,stamp_date_time)values($pg_mas_id,'$merchant_id','$razorpay_payment_id',$amount_payable,'".date('Y-m-d h:m:s')."')";
        $sqlsc1=pg_query($_SESSION["water_conn"],$sql);

        $sql2="insert into tbl_razor_pay_response(pg_master_id,merchant_id,customer_id,txn_ref_no,txn_amount,txn_date,ip_address,
                date_time,auth_status)
                values($pg_mas_id,'$merchant_id','$razorpay_payment_id','$razorpay_order_id',$amount_payable,'".date('Y-m-d')."','$ip_address','".date('Y-m-d h:m:s')."','0300')";
        $run=pg_query($_SESSION["water_conn"],$sql2);

        $core_pg->resp_water_demand_sucess_new($pg_mas_id,$razorpay_order_id);
        
    }
    else
    {   
        $merchant_id = 'GZ1RB9lnheOVK6';
        $razorpay_payment_id=$_GET['razorpay_payment_id'];
        $razorpay_order_id=$_GET['razorpay_order_id'];
        
        $error_code=$_GET['code'];
        $error_description=$_GET['description'];
        $error_source=$_GET['source'];
        $error_step=$_GET['step'];
        $error_reason=$_GET['reason'];
                
        $sql="insert into tbl_razor_pay_request(pg_master_id,merchant_id,customer_id,txn_amount,stamp_date_time)values($pg_mas_id,'$merchant_id','$razorpay_payment_id',$amount_payable,'".date('Y-m-d h:m:s')."')";
        $sqlsc1=pg_query($_SESSION["water_conn"],$sql);

       

        $sql2="insert into tbl_razor_pay_response(pg_master_id,merchant_id,customer_id,txn_ref_no,txn_amount,txn_date,ip_address,
                date_time,auth_status,error_code,error_desc,error_source,error_step,error_reason)
                values($pg_mas_id,'$merchant_id','$razorpay_payment_id','$razorpay_order_id',$amount_payable,'".date('Y-m-d')."','$ip_address','".date('Y-m-d h:m:s')."','0399','$error_code','$error_description','$error_source','$error_step','$error_reason')";
        $run=pg_query($_SESSION["water_conn"],$sql2);


        $core_pg->resp_water_newconn_failure_new($customer_transaction_no,$razorpay_order_id);

    }
        
?>
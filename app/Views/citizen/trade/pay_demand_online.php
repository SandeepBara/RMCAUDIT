<html>
    <head>
        <title>Suda-JH | Online Trade Payment</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="http://modernulb.com/RMCDMC/public/assets/img/favicon.ico">
    </head>
<body>
<?php
// $this->include('razor_php/razor/vendor/autoload');
// use Razorpay\Api\Api;
// $api_key_id = "rzp_test_trroh9p49WXNDv";
// $api_secret = "PqVzNrZZQcyMPKYK1EhN1aMo";
$api_key_id = getenv("razorpay.api_key_id");
$api_secret = getenv("razorpay.api_secret_key");

if(isset($amount))
{		
    //$api = new Api($api_key_id, $api_secret);
    // $amount=$amount*100;// Amount is in currency subunits. Hence, 50000 refers to 50000 paise
    // $order_id = $api->order->create(
    //                         array(
    //                                 'receipt' => '123',
    //                                 'amount' => $amount,
    //                                 'currency' => 'INR'
    //                             )
    //                     );

	?>
    <div style="display: none;">
        <button id="rzp-button1">Pay</button>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    //setTimeout(document.getElementById('btnStartVisit').dispatchEvent(new MouseEvent("click"));, 1000);
    var options = {
        "key": "<?=$api_key_id;?>", // Enter the Key ID generated from the Dashboard
        "amount": "<?=$amount;?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "Ranchi Muncipal Corporation",
        "description": "Trade License Fee",
        "image": "https://cdn.razorpay.com/logos/FF5xcplf8lBIoi_medium.png",
        "order_id": "<?=$order_id;?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "handler": function (response)
        {
            //console.log(response);
            var razorpay_payment_id=response.razorpay_payment_id;
            var razorpay_order_id=response.razorpay_order_id;
			var razorpay_signature=response.razorpay_signature;
            window.location.href="<?=base_url("TradePaymentCitizen/proceed_payment/$pg_mas_id");?>/"+razorpay_payment_id+'/'+razorpay_order_id+'/'+razorpay_signature;
        },
        "prefill": {
            "name": "<?=$owner_name?>",
            "email": "<?=$email_id?>",
            "contact": "<?=$mobile_no?>"
        },
        "notes": {
            "address": "Razorpay Corporate Office"
        },
        "theme": {
            "color": "#17ca07"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.on('payment.failed', function (response){
            console.log(JSON.stringify(response));
            /*
            alert(response.error.description);
            alert(response.error.source);
            alert(response.error.step);
            alert(response.error.reason);
            alert(response.error.metadata.order_id);
            alert(response.error.metadata.payment_id);
            */
            var razorpay_payment_id=response.error.metadata.payment_id;
            var razorpay_order_id=response.error.metadata.order_id;
            var error_code = response.error.code;
            var error_desc = response.error.description;
            var error_source = response.error.source;
            var error_step = response.error.step;
            var error_reason = response.error.reason;
            window.location.href = "<?php echo base_url("TradePaymentCitizen/paymentFailed/$pg_mas_id")?>/"+razorpay_payment_id+'/'+razorpay_order_id+'/'+error_code+'/'+error_desc+'/'+error_source+'/'+error_step+'/'+error_reason;
    });
    document.getElementById('rzp-button1').onclick = function(e)
    {
        rzp1.open();
        e.preventDefault();
    }
    document.getElementById('rzp-button1').onclick();
    </script>
    <?php	 
}
?>
</body>
</html>
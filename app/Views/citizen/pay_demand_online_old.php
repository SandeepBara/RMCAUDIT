
<?= $this->include('layout_home/header');?>
	
    <div style="display: none;" ><button id="rzp-button1">Pay</button></div>
    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>

    //setTimeout(document.getElementById('btnStartVisit').dispatchEvent(new MouseEvent("click"));, 1000);
    var options = {
        "key": "<?=$api_key_id;?>", // Enter the Key ID generated from the Dashboard
        "amount": "<?=$DuesDetails["PayableAmount"];?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "Ranchi Muncipal Corporation",
        "description": "Holding Tax Payment",
        "image": "https://cdn.razorpay.com/logos/FF5xcplf8lBIoi_medium.png",
        "order_id": "<?=$order_id["id"];?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "callback_url": "",
        "handler": function (response)
        {
            console.log(response);
          	var razorpay_payment_id=response.razorpay_payment_id;
			var razorpay_order_id=response.razorpay_order_id;
			var razorpay_signature=response.razorpay_signature;

            window.location.href = "<?=base_url("onlinePay/paymentSuccess/$prop_dtl_id/$pg_mas_id/");?>/"+razorpay_payment_id+"/"+razorpay_order_id+"/"+razorpay_signature;
        },
        "prefill": {
            "name": "<?=$owner_name;?>",
            "email": "",
            "contact": "<?=$mobile_no;?>"
        },
        "notes": {
            "address": "Razorpay Corporate Office"
        },
        "theme": {
            "color": "#17ca07"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.on('payment.failed', function (response)
    {
        var code = (response.error.code);
        var description = (response.error.description);
        var source = (response.error.source);
        var step = (response.error.step);
        var reason = (response.error.reason);
        var order_id = (response.error.metadata.order_id);
        var payment_id = (response.error.metadata.payment_id);
        window.location.href = "<?=base_url("onlinePay/paymentFailed/$prop_dtl_id/$pg_mas_id/");?>/"+code+"/"+description+"/"+source+"/"+step+"/"+reason+"/"+order_id+"/"+payment_id;
    });
    document.getElementById('rzp-button1').onclick = function(e)
    {
        rzp1.open();
        e.preventDefault();
    }

    document.getElementById('rzp-button1').onclick();
 
    </script>
	

<?=$this->include('layout_home/footer');?>
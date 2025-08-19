<?= $this->include('layout_vertical/header'); ?>
<link href="<?= base_url(); ?>/public/assets/plugins/spinkit/css/spinkit.min.css" rel="stylesheet">

<style>
    .main_panel_container {
        display: flex;
        /* justify-content: space-between; */
        height: auto;
    }

    .left_box_main_container {
        width: 280px;
    }

    .left_box_main_container {
        display: flex;
        flex-direction: column;
        padding: 10px;
    }

    .left_box_container_1 {
        /* border: 2px solid black; */
        height: 120px;
        text-align: center;
        background: #5b9bd5;
        color: #fff;
        border-radius: 5px;
        padding: 10px;
    }

    .left_box_container_2 {
        margin-top: 15px;
        min-height: 250px;
        padding: 10px;
        background-color: #955d43;
        text-align: center;
        color: #fff;
    }

    .left_box_container_2 p {
        font-size: 17px;
        font-weight: 500;
        color: #000;
    }

    .s_box {
        width: 100%;
        height: 100px;
        background: #ffffff !important;
        border-radius: 10px;
        color: #000;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .left_box_container_3 h5 {
        text-align: center;
        padding: 10px;
        line-height: 1;
        border-bottom: 1px solid #000;
    }

    .left_box_container_3_scroll_container {
        display: flex;
        padding: 10px;
        flex-direction: column;
        overflow-y: auto;
        height: 300px;
    }

    .left_box_container_3_scroll_container button {
        border: 1px solid gray;
        color: #f6966c;
        /* margin-top: 5px; */
        border-radius: 13px;
    }



    input[type="checkbox"]+label {
        cursor: pointer;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 50px;
        margin: 5px;
        border: 1px solid transparent;
        width: 100px;
    }

    input[type="checkbox"]:checked+label {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    /* RIGHT MAIN CONTAINER CSS START HERE */

    .right_box_main_container {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 10px;
    }

    .right_box_main_container_1 {
        background: #e9ecf0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding-top: 15px;
        padding: 15px;
    }

    .right_box_main_container_1 h5 {
        border-bottom: 1px solid #000;
        padding-bottom: 20px;
    }

    .right_box_main_container_1_bottom_container button {
        border-radius: 13px;
        border: 1px solid gray;
        color: #f6966c;
        min-width: 40px;
        padding: 10px;
    }

    .right_box_main_container_1_bottom_container button:active {
        background: black;
        color: white;
    }

    .right_box_main_container_2 {
        padding: 20px;
        margin-top: 30px;
    }

    .right_box_main_container_2_grid_container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;

    }

    .right_box_main_container_2_grid_container .card {
        background-color: #f8f9fa;
        border-radius: 8px;
        /* overflow: hidden; */
        color: #ffffff;
        font-size: 1.2em;
        text-align: center;
    }

    .right_box_main_container_2_grid_container .card-header {
        background-color: #094780;
        padding: 10px;
        font-size: 1.1em;
        color: #ffffff;
        line-height: 1.5;

    }

    .right_box_main_container_2_grid_container .card-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 130px;
        background-color: inherit;

    }

    .card-1 .card-content {
        background-color: #f6966c;
        color: #000;
    }

    .card-2 .card-content {
        background-color: #ebd675;
        color: #cf995f !important;
    }

    .card-3 .card-content {
        background-color: #ebd675;
        color: #cf995f;
    }

    .card-4 .card-content {
        background-color: #702254;
        color: #fff;
    }

    .card-5 .card-content {
        background-color: #ad5129;
        color: #ffffff;
    }
    .sub-div{
        border: 3px dotted red;
        border-radius: 10px;
        padding: 3px;
        font-weight:bolder ;
        color: white !important;
        width: 80%;
        font-size: small !important;
    }
    .sub-div h2{
        color: white !important;
        font-size: x-small !important;
    }
    .meter{
        background: #1ced54;
    }
    .fixed {
        background: #e06628;
    }

    .card-percentage {
        margin-top: 5px;

    }

    .card_5_content {
        display: flex;
        flex-direction: column;

    }

    .card-percentage small {
        font-size: 1.2em;

    }

    .right_box_main_container_3 {
        display: flex;
        justify-content: space-between;

    }

    .right_box_main_container_3>div {
        height: 300px;
        width: 330px;
        background: yellow;
        margin: 10px;
    }


    /* RIGHT MAIN CONTAINER CSS END HERE */
</style>

<div id="content-container">
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h1 class="panel-title">RMC TC Visit Report</h1>
            </div>

            <!-- TABLE BODY START HERE -->
            <div class="panel-body">
                <div class="main_panel_container">
                    <!-- LEFT BOX CONTAINER START HERER -->
                    <div class="left_box_main_container">
                        <div class="left_box_container_1 shadow-lg">
                            <p>RMC PT - Visit Report</p>
                        </div>
                        <div class="left_box_container_2">
                            <p>TC Active Status</p>
                            <hr>
                            <div class="s_box">
                                All
                            </div>
                        </div>
                        <div class="left_box_container_3">
                            <h5>Name Of TC</h5>
                            <div class="left_box_container_3_scroll_container">
                                <label class="btn btn-outline-warning rounded-pill">
                                    <input type="checkbox" id="select-all">
                                    SELECT ALL
                                </label>
                                <?php
                                foreach ($tcList as $tc) {
                                    echo '<label class="btn btn-outline-info rounded-pill">
                                                <input type="checkbox" class="tc-checkbox" data-id="' . htmlspecialchars($tc['id']) . '" value="' . htmlspecialchars($tc['id']) . '" name="emp_id[]" onclick="loadChart()">
                                                ' . htmlspecialchars($tc['name']) . '
                                          </label>';
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                    <!-- LEFT BOX CONTAINER END HERER -->

                    <!-- RIGHT BOX CONTAINER START HERE -->
                    <div class="right_box_main_container">
                        <!-- CONTAINER 1 START HERE -->
                        <div class="right_box_main_container_1">
                            <h5>Month of Visit 
                                <span style="float: right;">
                                    <button id="module_1" data-role="module_1" onclick="setUrl(1,true)" class="btn btn-sm btn-primary" >Property</button>
                                    <button id="module_3" data-role="module_3" onclick="setUrl(3,true)" class="btn btn-sm btn-primary" >Water</button>
                                    <button id="module_4" data-role="module_4" onclick="setUrl(4)" class="btn btn-sm btn-primary" style="background: #007bff;">Trade</button>
                                </span> 
                            </h5>
                            <div class="right_box_main_container_1_bottom_container">                                
                                <?php
                                    foreach($fyear_month as $val){
                                        ?>
                                            <button  id=<?=$val;?> class="btn btn-outline-warning rounded-pill" > <?=$val;?> <input type="radio" name="fromDate" value="<?=$val?>" checked onclick="loadChart()"></button>
                                        <?php
                                    }
                                ?>

                            </div>
                        </div>
                        <!-- CONTAINER 1 END HERE -->

                        <!-- CONTAINER 2 START HERE -->
                        <!-- CONTAINER 2 START HERE -->
                        <div class="right_box_main_container_2">
                            <div class="right_box_main_container_2_grid_container">
                                <div class="card card-1">
                                    <div class="card-header">No of Ward Alloted</div>
                                    <div class="card-content">
                                        <h2 id="no_of_ward_alloted">53</h2>
                                    </div>
                                </div>
                                <div class="card card-2">
                                    <div class="card-header">Total TL To be Renewed</div>
                                    <div class="card-content">
                                        <div class="sub-div meter" >
                                            <p>Total to be renewed</p>
                                            <h2 id="total_renewed_license">0</h2>
                                        </div>
                                        <div class="sub-div fixed">
                                            <p>Already Expired</p>
                                            <h2 id="total_expired_license">0</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-3">
                                    <div class="card-header">Visited by TC</div>
                                    <div class="card-content">
                                        <div class="sub-div meter" >
                                            <p>On Renewal</p>
                                            <h2 id="total_renewed">0</h2>
                                        </div>
                                        <div class="sub-div fixed">
                                            <p>On already Expired</p>
                                            <h2 id="total_expired">0</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-4">
                                    <div class="card-header">Payment Received</div>
                                    <div class="card-content">
                                        <div class="sub-div meter" >
                                            <p>From new</p>
                                            <h2 id="total_new_payment">0</h2>
                                        </div>
                                        <div class="sub-div fixed">
                                            <p>From Renewal</p>
                                            <h2 id="total_renewed_payment">0</h2>
                                        </div>
                                        <div class="sub-div meter" >
                                            <p>From Expired</p>
                                            <h2 id="total_expired_payment">0</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-5 ">
                                    <div class="card-header">
                                        Conversion Ratio
                                        <br />
                                        (TL Renewed / Total TL to be renewed)

                                    </div>
                                    <div class="card-content card_5_content">
                                        <h2 id="conversion_ratio" >0</h2>
                                        <div class="card-percentage">
                                            <small id="conversion_ratio_per">0%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONTAINER 2 END HERE -->

                        <!-- CONTAINER 3 START HERE -->
                        <div class="right_box_main_container_3">
                            <div class="right_box_main_container_3_box_1" id="piechart">

                            </div>
                            <div class="right_box_main_container_3_box_2" id="barchart">

                            </div>

                            <div class="right_box_main_container_3_box_3">

                            </div>
                        </div>
                        <!-- CONTAINER 3 END HERE -->
                    </div>
                    <!-- RIGHT BOX CONTAINER END HERE -->
                </div>
            </div>
            <!-- TABLE BODY END HERE -->
        </div>
    </div>
</div>

<?= $this->include('layout_vertical/footer'); ?>
<script src="<?=base_url();?>/public/assets/js/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    // google.charts.setOnLoadCallback(loadChart);

    var checkedValues = [];
    var fromDate ="";
    let cardUrl = "<?=base_url("NewDashboard/ajaxTcVisitingTradeDashboardHH");?>";
    let chartUrl = "<?=base_url("NewDashboard/ajaxTcVisitingTradeDashboard");?>";
    function setUrl(moduleId,redirectUrl=""){
        document.querySelectorAll('[data-role]').forEach(function(element){
            if("module_"+moduleId == element.getAttribute("data-role")){
                element.style="background: #007bff;";
            }
            else{
                element.style="";
            }
        });
        if(redirectUrl){
            window.location.href = ('<?=base_url("NewDashboard/tcVisitingDashboard")?>/'+moduleId);
            $("#loadingDiv").show();
        }
    }
    function loadChart(){
        checkedValues = [];
        fromDate = document.querySelector('input[name="fromDate"]:checked').value;
        var emp_id = document.getElementsByName('emp_id[]');
        for (var i = 0; i < emp_id.length; i++) {
            if (emp_id[i].checked) {
                checkedValues.push(emp_id[i].value);
            }
        }
        data = {
                emp_id: checkedValues,
                fromDate:fromDate+"-01",
            };
        callAjaxForDrawCards(data);
        callAjaxForDrawBarChart(data);

    }

    function callAjaxForDrawCards(data){
        $.ajax({
            url: cardUrl,
            type: "post", 
            dataType: 'json',
            data:data ,
            beforeSend: function() {
				// do nothing
                $("#no_of_ward_alloted").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_renewed_license").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_expired_license").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_renewed").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_expired").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_new_payment").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_renewed_payment").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_expired_payment").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#conversion_ratio").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#conversion_ratio_per").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#piechart").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
            },
            success: function(result) {
                var out = '';
                console.log("card : ",result);
                if (result.status) {
                    var data = result.data;
                    setCards(result?.result[0]);
                    drawPieChart(result?.result[0]);
                    console.log("card : ",result?.result);
                    
                } else {
                    
                }
            },
            error:function(error){
                alert(error);
            }
        });
    }

    function setCards(result){
        $("#no_of_ward_alloted").html(result?.ward_alloted??0);
        $("#total_renewed_license").html(result?.total_renewed_license??0);
        $("#total_expired_license").html(result?.total_expired_license??0);
        $("#total_renewed").html(result?.total_renewed??0);
        $("#total_expired").html((result?.total_expired??0));
        $("#total_new_payment").html(result?.total_new_payment??0);
        $("#total_renewed_payment").html((result?.total_renewed_payment??0));
        $("#total_expired_payment").html((result?.total_expired_payment??0));
        $("#conversion_ratio_per").html(parseFloat(((result?.total_renewed??0) + (result?.total_expired??0) ) / (((result?.total_renewed_license??0) + (result?.total_expired_license??0)) > 0 ? ((result?.total_renewed_license??0) + (result?.total_expired_license??0)) : 1)).toFixed(2) +" %");
        $("#conversion_ratio").html((result?.total_renewed??0) + (result?.total_expired??0));
    }

    function drawPieChart(result){
        var current_only = parseFloat (result?.current_payment??0);
        var arrear_only = parseFloat (result?.arrear_payment??0);
        var full_payment = parseFloat (result?.full_payment??0);
        var current_arrear_payment = parseFloat (result?.current_arrear_payment??0);

        var data = google.visualization.arrayToDataTable([
            ['Status', 'Count'],            
            ['Only Current Due', current_only],
            ['Only Arrear Due', arrear_only],
            ['Fully Paid HH', full_payment],
            ['Arrear Plus Current Due', current_arrear_payment]
            
        ]);

        var options = {
            title: 'No of Visit Done During the Month by Demand Status',
            is3D: true,
            width: '100%',
            height: '100%'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
    
    function callAjaxForDrawBarChart(data){
        $.ajax({
            url: chartUrl,
            type: "post",
            dataType: 'json',
            data:data ,
            beforeSend:function(){
                $("#barchart").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
            },
            success: function(result) {
                var out = '';
                console.log(result)
                if (result.status) {
                    var data = result.data;
                    drawBarChart(result?.result);
                    
                } else {
                    
                }
            },
            error:function(error){
                alert(error);
            }
        });
        
    }

    function drawBarChart(result) {
        
        var arr = [['Visit Status', 'Count']];
        Array.from(result).forEach(function(val){
            arr.push([val.remarks,parseInt (val.count,10)]);
        });
        console.log(arr);
        var data = google.visualization.arrayToDataTable(arr);

        var options = {
            title: 'No of Visit Done During the Month by Visit Status',
            width: '100%',
            height: '100%',
            hAxis: {title: 'Count'},
            vAxis: {title: 'Visit Status'}
        };

        var chart = new google.visualization.BarChart(document.getElementById('barchart'));
        chart.draw(data, options);
    }

    $("document").ready(function(){
        loadChart();
    });
</script>
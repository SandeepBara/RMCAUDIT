<?= $this->include('layout_vertical/header'); ?>
<link href="<?= base_url(); ?>/public/assets/plugins/spinkit/css/spinkit.min.css" rel="stylesheet">

<style>
    .main_panel_container {
        display: flex;
        /* justify-content: space-between; */
        height: auto;
        width: 79%;
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
        overflow: hidden;
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
    /* Content inside the container */
    .content-item {
        background-color: #4285F4;
        color: white;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        word-wrap: break-word; /* Break long words if necessary */
    }
    /* Container that will auto adjust its height */
    .content-area {
        color: #000;
        border: 2px solid #ccc;
        padding: 10px;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        overflow: auto; /* Automatically adds a scrollbar if content exceeds the height */
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
                            <div class="content-area" id="s_box" >
                                <span id="all">All</span>
                            </div>
                        </div>
                        <div class="left_box_container_3 text-info bg-success">
                            <h5 >Name Of Active TC</h5>
                            <div class="left_box_container_3_scroll_container">
                                
                                <?php
                                foreach ($tcList as $tc) {
                                    echo '<label class="btn btn-outline-info rounded-pill">
                                                <input type="checkbox" class="tc-checkbox" data-id="' . htmlspecialchars($tc['id']) . '" value="' . htmlspecialchars($tc['id']) . '" name="emp_id[]" onclick="loadChart();addElement('.$tc['id'].')">
                                                <span id="'.$tc['id'].'">' . htmlspecialchars($tc['name']) . '</span>
                                            </label>';
                                }
                                ?>
                            </div>

                        </div>
                        <div class="left_box_container_3 text-danger" style="background-color:darkviolet ; margin-top:13px">
                        <h5 class="text-light">Name Of De-Active TC</h5>
                            <div class="left_box_container_3_scroll_container">
                                
                                <?php
                                foreach ($tcList2 as $tc) {
                                    echo '<label class="btn btn-outline-info rounded-pill">
                                                <input type="checkbox" class="tc-checkbox"  data-id="' . htmlspecialchars($tc['id']) . '" value="' . htmlspecialchars($tc['id']) . '" name="emp_id_deactivate[]" onclick="loadChart();addElement('.$tc['id'].')">
                                                <span id="'.$tc['id'].'">' . htmlspecialchars($tc['name']) . '</span>
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
                                    <button id="module_1" data-role="module_1" onclick="setUrl(1)" class="btn btn-sm btn-primary" style="background: #007bff;">Property</button>
                                    <button id="module_3" data-role="module_3" onclick="setUrl(3,true)" class="btn btn-sm btn-primary">Water</button>
                                    <!-- <button id="module_4" data-role="module_4" onclick="setUrl(4,true)" class="btn btn-sm btn-primary">Trade</button> -->
                                </span> 
                            </h5>
                            <div class="right_box_main_container_1_bottom_container"> 
                                <label for="fromDate">From Date</label> 
                                <input type="date" name="fromDate" id="fromDate" value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>"> 
                                <label for="uptoDate">Upto Date</label> 
                                <input type="date" name="uptoDate" id="uptoDate" value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>">  
                                
                                <button class="btn btn-outline-warning rounded-pill bg-primary" type="button" onclick="loadChart()" > Refresh </button> 
                                
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
                                    <div class="card-header">Total HH</div>
                                    <div class="card-content">
                                        <h2 id="total_hh">311897</h2>
                                    </div>
                                </div>
                                <div class="card card-3">
                                    <div class="card-header">Fully Paid HH</div>
                                    <div class="card-content">
                                        <h2 id="fully_paid_hh">21177</h2>
                                    </div>
                                </div>
                                <div class="card card-4">
                                    <div class="card-header">HH to be Visited
                                        <br />
                                        (Fully Paid HH)
                                    </div>
                                    <div class="card-content">
                                        <h2 id="hh_to_be_visited">10720</h2>
                                    </div>
                                </div>
                                <div class="card card-5 ">
                                    <div class="card-header">HH Visited</div>
                                    <div class="card-content card_5_content">
                                        <h2 id="hh_visited" >19867</h2>
                                        <div class="card-percentage">
                                            <small id="visiting_pec">53%</small>
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

                            <div class="right_box_main_container_3_box_3 text-center card content-area">
                                <h4 class="card-header">No of Ward Alloted</h4></hr>
                                <div class="card-content">
                                    <span id="no_of_ward_alloted_no"></span>
                                </div>
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
    let cardUrl = "<?=base_url("NewDashboard/ajaxTcVisitingDashboardHH");?>";
    let chartUrl = "<?=base_url("NewDashboard/ajaxTcVisitingDashboard");?>";
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
        // if(moduleId==1){
        //     cardUrl = "<?=base_url("NewDashboard/ajaxTcVisitingDashboardHH");?>";
        //     chartUrl = "<?=base_url("NewDashboard/ajaxTcVisitingDashboard");?>";

        // }
        // if(moduleId==2){
        //     cardUrl = "<?=base_url("NewDashboard/ajaxTcVisitingWaterDashboardHH");?>";
        //     chartUrl = "<?=base_url("NewDashboard/ajaxTcVisitingWaterDashboard");?>";

        // }
        // if(moduleId==3){
        //     cardUrl = "<?=base_url("NewDashboard/ajaxTcVisitingTradeDashboardHH");?>";
        //     chartUrl = "<?=base_url("NewDashboard/ajaxTcVisitingTradeDashboard");?>";

        // }
        // loadChart();
    }
    function loadChart(){
        checkedValues = [];
        // fromDate = document.querySelector('input[name="fromDate"]:checked').value;
        fromDate = $("#fromDate").val();
        uptoDate = $("#uptoDate").val();
        var emp_id = document.getElementsByName('emp_id[]');
        var emp_id_deactivate = document.getElementsByName('emp_id_deactivate[]');
        for (var i = 0; i < emp_id.length; i++) {
            if (emp_id[i].checked) {
                checkedValues.push(emp_id[i].value);
            }
        }
        for (var i = 0; i < emp_id_deactivate.length; i++) {
            if (emp_id_deactivate[i].checked) {
                checkedValues.push(emp_id_deactivate[i].value);
            }
        }
        data = {
                emp_id: checkedValues,
                fromDate:fromDate,
                uptoDate:uptoDate,
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
                $("#no_of_ward_alloted_no").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#total_hh").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#fully_paid_hh").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#hh_to_be_visited").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#hh_visited").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $("#piechart").html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
            },
            success: function(result) {
                var out = '';
                // console.log("card : ",result);
                if (result.status) {
                    var data = result.data;
                    setCards(result?.result[0]);
                    drawPieChart(result?.result[0]);
                    console.log("card : ",result?.result);
                    
                } else {
                    
                }
            },
            error:function(error){
                // alert(error);
            }
        });
    }

    function setCards(result){
        $("#no_of_ward_alloted").html(result?.ward_alloted??0);
        $("#no_of_ward_alloted_no").html(result?.ward_no_alloted??"");
        $("#total_hh").html(result?.total_hh??0);
        $("#fully_paid_hh").html(result?.total_full_payid_hh??0);
        $("#hh_to_be_visited").html((result?.total_hh??0) - (result?.total_full_payid_hh??0));
        $("#hh_visited").html(result?.visit_hh??0);
        $("#visiting_pec").html(parseFloat((result?.visit_hh??0)/(((result?.total_hh??0) - (result?.total_full_payid_hh??0))!=0 ? ((result?.total_hh??0) - (result?.total_full_payid_hh??0)):1)).toFixed(2) +" %");
    }

    function drawPieChart(result){
        console.log("resultttt",result?.current_payment);
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
                // alert(error);
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

    function updateSelection(){
        var emp_id = document.getElementsByName('emp_id[]');
        var emp_id_deactivate = document.getElementsByName('emp_id_deactivate[]');
        for (var i = 0; i < emp_id.length; i++) {
            if (emp_id[i].checked) {
                addElement(emp_id[i].value);
            }
        }
        for (var i = 0; i < emp_id_deactivate.length; i++) {
            if (emp_id_deactivate[i].checked) {
                addElement(emp_id[i].value);
            }
        }
    }
    function addElement(ids){
        var className = "";
        var tc_name = $("#"+ids).html();
        var emp_id = document.getElementsByName('emp_id[]');
        var emp_id_deactivate = document.getElementsByName('emp_id_deactivate[]');
        for (var i = 0; i < emp_id.length; i++) {
            if (emp_id[i].value==ids) {
                if(emp_id[i].checked==false){
                    removeElement("selected_"+ids);
                    return;
                }
                className=" text-success";
            }
        }
        for (var i = 0; i < emp_id_deactivate.length; i++) {
            if (emp_id_deactivate[i].value==ids) {
                if(emp_id_deactivate[i].checked==false){
                    removeElement("selected_"+ids);
                    return;
                }
                className=" text-danger";
            }
        }
        
        $("#all").hide();
        document.getElementById('s_box').innerHTML += (`<div id="selected_`+ids+`" class="tag `+className+`" onclick="removeElement('selected_`+ids+`')"><span>`+tc_name+`</span><span class="remove-btn">&times;</span></div>`);
    }
    function removeElement(id){
        $("#"+id).remove();
        const myArray = id.split("selected_");
        ids = myArray[1]??0; 
        var emp_id = document.getElementsByName('emp_id[]');
        var emp_id_deactivate = document.getElementsByName('emp_id_deactivate[]');
        for (var i = 0; i < emp_id.length; i++) {
            if (emp_id[i].value==ids) {
                emp_id[i].checked=false;
                break;
            }
        }
        for (var i = 0; i < emp_id_deactivate.length; i++) {
            if (emp_id_deactivate[i].value==ids) {
                emp_id_deactivate[i].checked=false;
                break;
            }
        }

    }
</script>
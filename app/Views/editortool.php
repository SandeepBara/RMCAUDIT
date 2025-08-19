<?=$this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->

        <div id="page-content">
            <div class="panel panel-bordered ">
                    <div class="panel-body">
                        
                        <div class="col-sm-2">
                            <div style="flex: 3;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="flex-wrap align-items-center justify-content-center" style="background-color:#25476a; padding:10px 5px; color:#fff;">Database</h5>
                                        <form method="post" >
                                        <select name="dbname" class="form-control" onchange="this.form.submit();">
                                            <option value="db_rmc_property" <?php if(isset($dbname) && $dbname=='db_rmc_property'){ echo "selected"; }?>>db_rmc_property</option>
                                            <option value="db_rmc_water" <?php if(isset($dbname) && $dbname=='db_rmc_water'){ echo "selected"; }?>>db_rmc_water</option>
                                            <option value="db_rmc_trade" <?php if(isset($dbname) && $dbname=='db_rmc_trade'){ echo "selected"; }?>>db_rmc_trade</option>
                                            <option value="db_system" <?php if(isset($dbname) && $dbname=='db_system'){ echo "selected"; }?>>db_system</option>
                                        </select>
                                        </form>
                                        <h5 class="flex-wrap align-items-center justify-content-center" style="background-color:#25476a; padding:10px 5px; color:#fff;">Tables</h5>
                                        <ul class="list-unstyled" style="height:545px; overflow:auto; ">
                                        <?php
                                            foreach($getTable as $table)
                                            {
                                                echo "<li style='font-weight:bold; line-height:1.4em;'><a href='".$table['table']."'>".$table['table']."</a></li>";
                                            }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div style="flex: 3;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="flex-wrap align-items-center justify-content-center" style="background-color:#25476a; padding:10px 5px; color:#fff;">Editor</h5>
                                        <textarea id="editor" class="form-control" rows="10"></textarea>
                                        <br/>
                                        <button type="button" class="btn btn-primary" id="queryExcute">Excute</button>
                                        <button type='button' onclick="exportToExcel()" name='excel_to_export' id='excel_to_export' class="btn btn-primary">Excel Export</button>
                                    </div>
                                </div>
                                <br/><br/>            
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="flex-wrap align-items-center justify-content-center" style="background-color:#25476a; padding:10px 5px; color:#fff;">Data Output</h5>
                                        <div id="result" style="font-size:12px; height:300px; width: 100%; overflow:auto;">No Data Found</div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
            </div>
        </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?=$this->include('layout_home/footer');?>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script>

$(document).ready(function(){
    $("#queryExcute").click(function()
    {
        var selected = ShowSelection();
        var query = $("#editor").val().trim();
        if(selected!="")
        {
            query = selected;
        }
		
		$('#result').html('Proccessing....');
        $.ajax({
			type: "POST",
			url: "<?=base_url('/tools/Ajax_getDataFromEditor');?>",
			dataType:"json",
			data:
			{
				query: query
			},
			beforeSend: function() {
				$("#queryExcute").html('<i class="fa fa-refresh fa-spin"></i>').prop('disabled', true);
			},
			success: function(data)
			{
				$("#queryExcute").html('Excute').prop('disabled', false);
				if(data.status==true)
				{
					data = data.data;
					$('#result').html(data);
				}
				else
				{
					$('#result').html(data.data);
					return false;
				}
			}
		});
    });


   
});

function ShowSelection()
{
  var textComponent = document.getElementById('editor');
  var selectedText;

  if (textComponent.selectionStart !== undefined)
  { // Standards-compliant version
    var startPos = textComponent.selectionStart;
    var endPos = textComponent.selectionEnd;
    selectedText = textComponent.value.substring(startPos, endPos);
  }
  else if (document.selection !== undefined)
  { // Internet Explorer version
    textComponent.focus();
    var sel = document.selection.createRange();
    selectedText = sel.text;
  }

  return selectedText;
}

function exportToExcel() {
	var location = 'data:application/vnd.ms-excel;base64,';
	var excelTemplate = '<html> ' +
		'<head> ' +
		'<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
		'</head> ' +
		'<body> ' +
		document.getElementById("result").innerHTML +
		'</body> ' +
		'</html>'
	window.location.href = location + window.btoa(excelTemplate);
}

function downloadExcel(){
    var selected = ShowSelection();
    var query = $("#editor").val().trim();
    if(selected!="")
    {
        query = selected;
    }
    var form = $('<form>', {
        method: 'POST',
        action: '<?= base_url("/tools/exportDataExcel") ?>',
        target: '_blank' // Open in new tab
    }).css('display', 'none');

    // Add query input
    form.append($('<input>', {
        type: 'hidden',
        name: 'query',
        value: query
    }));
    $('body').append(form);
    form.submit();
    form.remove();

}

</script>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="junjie" name="author" />
    <title>DBMS</title>
    <!-- Bootstrap Styles-->
    <link href="vendors/css-lib/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="vendors/css-lib/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="resources/css/custom-styles.css" rel="stylesheet" />
    <!-- TABLE STYLES-->
    <link href="vendors/plugin/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- load the theme CSS file -->
    <link rel="stylesheet" href="vendors/plugin/jsTree/themes/default/style.min.css" />

    <script src="vendors/jquery/jquery-1.10.2.js"></script>
    <!-- include the minified jstree source -->
    <script src="vendors/plugin/jsTree/jstree.js"></script>
    <script src="vendors/jquery/jquery.json.min.js"></script>
    
    <script src="vendors/plugin/dataTables/jquery.dataTables.js"></script>
    <script src="vendors/plugin/dataTables/dataTables.bootstrap.js"></script>

</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><strong><i class="icon fa fa-group"></i> Gfeller Center</strong></a>
				
				<div id="sideNav" href="">
					<i class="fa fa-bars icon"></i> 
				</div>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">

                    <li>
                        <a href="upload_data.php"><i class="fa fa-cloud-upload"></i>Upload Data</a>
                    </li>
                    <li>
                        <a class="active-menu"  href="download_data.php"><i class="fa fa-cloud-download"></i>Download Data</a>
                    </li>
                    <li>
                        <a href="file-manager.php"><i class="fa fa-folder"></i>File Manager</a>
                    </li>
                    <li>
                        <a href="variables-manager.php"><i class="fa fa-dot-circle-o"></i>Variable Manager</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
      
		<div id="page-wrapper">
		  <div class="header"> 
                        <h1 class="page-header">
                            Download Data
                        </h1>
									
		  </div>
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-4">
                        <div class="panel panel-default">

                            <div class="panel-heading">
                                Variables Tree 
                            </div>

                            <div class="panel-body" id="jstree">
                            </div>

                        </div>

                    </div>

                    <div class="col-md-8 col-sm-12 col-xs-8">
                        <div class="panel panel-default">

                            <div class="panel-heading">
                                Data Panel
                            </div>

                            <div class="panel-body" id="data-panel">
                                <!-- <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">

                                </table></div> -->
                            </div>

                            <div class="panel-body" id="submit-button">
                                <label class="control-label">Sheet Name:&nbsp</label><input class="sheet-bar" id="sheetName"><br>
                                <label class="control-label">Taget Directory:&nbsp</label>
<?php
    $filefolder = "./resources/data/download/";
    $downloadfolder = "./download/";
    function listdir($dir, $level_count = 0) {
        global $content;
        if (!@($thisdir = opendir($dir))) { return; }
        while ($item = readdir($thisdir) ) {
          if (is_dir("$dir/$item") && (substr("$item", 0, 1) != '.')) {
            listdir("$dir/$item", $level_count + 1);
          }
        }
        if ($level_count > 0) {
          $dir = ereg_replace("[/][/]", "/", $dir);
          $content .= "<option value=\"".$dir."/\">".$dir."/</option>";
        }
    }
    echo 
    "<select class=\"btn-warning\" name=\"target_dir\" size=1>\n"
    ."<option value=\"".$filefolder."\">".$downloadfolder."</option>";
    listdir($filefolder);
    echo $content
    ."</select>";
?>
                                <br>
                                <button class="btn btn-info" onclick='download_data()'>Download</button>
                            </div>

                        </div>

                    </div>
                   
                </div>
                <!-- /. ROW  -->
			
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->



    <!-- JS Scripts-->
    <script>
function contains(array, obj) {
    for (var i = 0; i < array.length; i++) {
        if (array[i] === obj) {
            return true;
        }
    }
    return false;
}
function download_data(){
    var targetDir = document.getElementsByName('target_dir')[0].value;
    var sheetName = document.getElementById('sheetName').value;
    var ref = $.jstree.reference('#jstree');
    var m = ref._model.data;

    var ids = [], i;
    for(i in m) {
      if(m.hasOwnProperty(i) && m[i].id !== "#" /*&& m[i].children.length != 0*/) {
        ids.push(m[i].id);
      }
    }

    var selectedNodes = ref.get_selected();
    var groupNode = [];
    if(ref.is_parent(selectedNodes[0]) || ref.get_parent(selectedNodes[0]) == "#"){
        groupNode.push(selectedNodes[0]);
    }
    else{
        groupNode.push(ref.get_parent(selectedNodes[0]));
    }
    var group = ref.get_node(groupNode);
    for(var i = 0; i < group.children.length; i++){
        groupNode.push(group.children[i]);
    }
    var unselectedNodes = [];
    for(var i = 0; i < ids.length; i++){
        if(!contains(groupNode, ids[i])){
            unselectedNodes.push(ids[i]);
        }
    }
    ref.disable_node(unselectedNodes);


    //Present table in data panel.
     var studyArray = group.data;
     var variableArray = [];
    for(i = 0; i < selectedNodes.length; i++){
        if(ref.get_node(selectedNodes[i]).children.length == 0){
            variableArray.push(ref.get_node(selectedNodes[i]).text);
        }
    }
     
    $.ajax({
        method: "POST",
        url : "ajax_download.php",
        data : {
            study : studyArray,
            variables : variableArray,
            targetDir : targetDir,
            sheetName : sheetName
        },
        dataType: "html",
        success: function(data){
            //alert(data);
        }
    });
}
$(function () {
//create an instance when the DOM is ready
    $('#jstree').jstree({
        'core' : {
          'check_callback' : true,
          'data' : {
            'url' : function (node) {
              /*return node.id === '#' ?
                'ajax_roots.json' :
                'ajax_children.json';*/
                return 'resources/data/tree.json';
            },
            'data' : function (node) {
              return { 'id' : node.id };
            }
          }
        },

        'plugins':["checkbox", "search", " state", "types"]
    })
    .on("loaded.jstree", function(){
        $.jstree.reference('#jstree').deselect_all();
        document.getElementById('submit-button').style.display = 'none';
    })
    .on("select_node.jstree", function (e, data) {
        var ref = $.jstree.reference('#jstree');
        var m = ref._model.data;
        //console.log(m);

        var ids = [], i;
        for(i in m) {
          if(m.hasOwnProperty(i) && m[i].id !== "#" /*&& m[i].children.length != 0*/) {
            ids.push(m[i].id);
          }
        }
        //console.log(ids);

        var selectedNodes = ref.get_selected();
        //console.log(selectedNodes);

        var groupNode = [];
        if(ref.is_parent(selectedNodes[0]) || ref.get_parent(selectedNodes[0]) == "#"){
            groupNode.push(selectedNodes[0]);
        }
        else{
            groupNode.push(ref.get_parent(selectedNodes[0]));
        }
        //console.log(groupNode);
        var group = ref.get_node(groupNode);
        //console.log(group.children);
        for(var i = 0; i < group.children.length; i++){
            groupNode.push(group.children[i]);
        }
        //console.log(groupNode);

        var unselectedNodes = [];
        for(var i = 0; i < ids.length; i++){
            if(!contains(groupNode, ids[i])){
                unselectedNodes.push(ids[i]);
            }
        }
        //console.log(unselectedNodes);
        //$.jstree.reference('#jstree').disable_checkbox(ids);
        ref.disable_node(unselectedNodes);


        //Present table in data panel.
        var studyArray = [];
        for(var s in group.data){
            studyArray.push(group.data[s].toLowerCase());
        }
        console.log(studyArray);
        var variableArray = [];
        /*if(selectedNodes.length == groupNode.length){
            for(i = 0; i < selectedNodes.length; i++){
                if(ref.get_node(selectedNodes[i]).children.length == 0){
                    console.log(ref.get_node(selectedNodes[i]));
                    variableArray.push(ref.get_node(selectedNodes[i]).text);
                }
            }
         }
         else
            var variableArray = selectedNodes;*/
        for(i = 0; i < selectedNodes.length; i++){
            if(ref.get_node(selectedNodes[i]).children.length == 0){
                //console.log(ref.get_node(selectedNodes[i]));
                variableArray.push(ref.get_node(selectedNodes[i]).text);
            }
        }
         
        $.ajax({
            method: "POST",
            url : "get_data.php",
            data : {
                study : studyArray,
                variables : variableArray
            },
            dataType: "html",
            success: function(data){
                //console.log(data);
                $("#data-panel").html(data);
                $('#dataTable').dataTable();
                document.getElementById('submit-button').style.display = 'block';

            }
        });

    })
    .on("deselect_node.jstree", function (e, data) {
        var ref = $.jstree.reference('#jstree');
        var selectedNodes = ref.get_selected();
        if(selectedNodes.length == 0){
            var allNodes = ref.get_node("#").children_d;
            //console.log(allNodes);
            ref.enable_node(allNodes);
            $("#data-panel").empty();
            document.getElementById('submit-button').style.display = 'none';
        }


        var ref = $.jstree.reference('#jstree');
        var m = ref._model.data;
        //console.log(m);

        var ids = [], i;
        for(i in m) {
          if(m.hasOwnProperty(i) && m[i].id !== "#" /*&& m[i].children.length != 0*/) {
            ids.push(m[i].id);
          }
        }
        //console.log(ids);

        var selectedNodes = ref.get_selected();
        //console.log(selectedNodes);
        if(selectedNodes.length != 0){
            var groupNode = [];
            if(ref.is_parent(selectedNodes[0]) || ref.get_parent(selectedNodes[0]) == "#"){
                groupNode.push(selectedNodes[0]);
            }
            else{
                groupNode.push(ref.get_parent(selectedNodes[0]));
            }
            //console.log(groupNode);
            var group = ref.get_node(groupNode);
            for(var i = 0; i < group.children.length; i++){
                groupNode.push(group.children[i]);
            }
            //console.log(groupNode);

            var unselectedNodes = [];
            for(var i = 0; i < ids.length; i++){
                if(!contains(groupNode, ids[i])){
                    unselectedNodes.push(ids[i]);
                }
            }
            //console.log(unselectedNodes);
            //$.jstree.reference('#jstree').disable_checkbox(ids);
            ref.disable_node(unselectedNodes);


            //Present table in data panel.
             var studyArray = group.data;
             var variableArray = [];
             /*if(selectedNodes.length == groupNode.length){
                for(i = 0; i < selectedNodes.length; i++){
                    if(ref.get_node(selectedNodes[i]).children.length == 0){
                        console.log(ref.get_node(selectedNodes[i]));
                        variableArray.push(ref.get_node(selectedNodes[i]).text);
                    }
                }
             }
             else
                var variableArray = selectedNodes;*/
            for(i = 0; i < selectedNodes.length; i++){
                if(ref.get_node(selectedNodes[i]).children.length == 0){
                    console.log(ref.get_node(selectedNodes[i]));
                    variableArray.push(ref.get_node(selectedNodes[i]).text);
                }
            }
             
            $.ajax({
                method: "POST",
                url : "get_data.php",
                data : {
                    study : studyArray,
                    variables : variableArray
                },
                dataType: "html",
                success: function(data){
                    console.log(data);
                    $("#data-panel").html(data);
                    $('#dataTable').dataTable();                }
            });
        }
        })
            
});

    </script>
	
     <!-- DATA TABLE SCRIPTS -->
    

</body>

</html>
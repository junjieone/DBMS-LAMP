<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <title>DBMS</title>
    <!-- Bootstrap Styles-->
    <link href="vendors/css-lib/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="vendors/css-lib/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="resources/css/custom-styles.css" rel="stylesheet" />
    <link href="vendors/css-lib/checkbox3.min.css" rel="stylesheet" >

    <!-- Tree -->
    <!-- include the jQuery library -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- include the minified jstree source -->
    <script src="vendors/plugin/jsTree/jstree.js"></script>
    <script src="vendors/jquery/jquery.json.min.js"></script>
    <!-- load the theme CSS file -->
    <link rel="stylesheet" href="vendors/plugin/jsTree/themes/default/style.min.css" />
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
                        <a href="download_data.php"><i class="fa fa-cloud-download"></i>Download Data</a>
                    </li>
                    <li>
                        <a href="file-manager.php"><i class="fa fa-folder"></i>File Manager</a>
                    </li>
                    <li>
                        <a class="active-menu" href="variables-manager.php"><i class="fa fa-dot-circle-o"></i>Variable Manager</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
      
		<div id="page-wrapper">
		  <div class="header"> 
                        <h1 class="page-header">
                            Variables Manager
                        </h1>
									
		  </div>
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Operations 
                            </div>

                            <div class="panel-body">
                                <div class="sub-col-9">
                                <button type="button" class="btn btn-success btn-sm" onclick="create_group();">Create Group</button>
                                <!-- 
                                <button type="button" class="btn btn-warning btn-sm" onclick="demo_rename();"><i class="glyphicon glyphicon-pencil"></i> Rename</button>
                                 -->
                                </div>
                                <div class="sub-col-3">
                                <input class="searchbar" type="text" value="" placeholder="Search"/>
                                </div>
                            
                            </div>

                            <div id="heading"></div>
                            <div id="group_setting"></div>

                            <div id="var_heading"></div>
                            <div id="var_setting"></div>

                            <div class="panel-body" id="buttons"><button type="button" class="btn btn-sm" onclick="set_study();">Create</button><button type="button" class="btn btn-sm" onclick="cancel();">Cancel</button></div>

                            <div class="panel-heading">
                                Variables Tree 
                            </div>
                            <div class="panel-body" id="related-study"></div>

                            <div class="panel-body" id="jstree">
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

function create_group(){

    $.ajax({
        method: "GET",
        url : "get_studies.php",
        dataType: "html",
        success: function(data){
            $("#heading").attr("class", "panel-heading");
            $("#heading").text("Related Study");
            $("#group_setting").html(data);
            document.getElementById('buttons').style.display = 'block';
            //console.log(data);
        }
    });

}
function check_function(){
    $("#var_heading").attr("class", "panel-heading");
    $("#var_heading").text("Common Variables");

    var studyRef = document.getElementsByName("study");
    var study_array = [];

    for(var i=0; i<studyRef.length; i++)
        if(studyRef[i].checked)
            study_array.push(studyRef[i].value);


    $.ajax({
        method: "POST",
        url : "get_variables.php",
        data : {checkedStudies : study_array},
        dataType: "html",
        success: function(data){
            $("#var_setting").attr("class", "panel-body");
            $("#var_setting").html(data);
        }
    });
}

function check_all(){
    var studyRef = document.getElementsByName("variable");
    if(studyRef[0].checked == true){
        for(var i=1; i<studyRef.length; i++){
            studyRef[i].checked = true;
        }
    }
    else{
        for(var i=1; i<studyRef.length; i++){
            studyRef[i].checked = false;
        }
    }
}

function set_study(){

    var tree = $('#jstree').jstree(true);
    tree.refresh(false, false, 'create_group');

}

function cancel(){
    $("#heading").attr("class", "");
    $("#heading").empty();
    $("#group_setting").empty();
    $("#var_heading").empty();
    $("#var_setting").empty();
    $("#var_heading").attr("class", "");
    $("#var_setting").attr("class", "");

    document.getElementById('buttons').style.display = 'none';
}


$(function () {
// 6 create an instance when the DOM is ready
    $('#jstree').jstree({

        "types" : {
            "#" : { /*"max_children" : 1, */"max_depth" : 2, "valid_children" : ["root"] },
            "root" : { "icon" : "glyphicon glyphicon-tree-conifer", "valid_children" : ["default"] },
            //doesnt work
            //"child" : { "icon" : "glyphicon glyphicon-file", "valid_children" : [] },
            
            "default" : { "icon" : "glyphicon glyphicon-tag", "valid_children" : [] }
        },

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

        'plugins':['contextmenu', "search", " state", "types","wholerow"]
    })
    .on("select_node.jstree", function (e, data) {
        var ref = $.jstree.reference('#jstree');
        var m = ref._model.data;

        var selectedNodes = ref.get_selected();

        var groupNode = [];
        if(ref.is_parent(selectedNodes[0]) || ref.get_parent(selectedNodes[0]) == "#"){
            groupNode.push(selectedNodes[0]);
        }
        else{
            groupNode.push(ref.get_parent(selectedNodes[0]));
        }
        document.getElementById('related-study').style.display = 'block';
        var study_array = ref.get_node(groupNode).data;

        var length = 0;
        for (var item in study_array) {
            length++;
        }

        var study_tags = "<strong>Related Study:</strong>&nbsp&nbsp";
        for(var i = 0; i < length; i++){
            study_tags += "<span class='badge'>" + study_array[i] + "</span>&nbsp";
        }
        $("#related-study").html(study_tags);
        //console.log(study_array);
    })
    .on("copy_node.jstree", function (e, data) {
        var wholeTree = $.jstree.reference('#jstree').get_json(0); //get the whole tree
        var t = $.toJSON(wholeTree);
        $.ajax({
            method: "POST",
            url : "write_json.php",
            data: {data: t},
            dataType: "text",
            success: function(msg){
                console.log(msg);
            }
        });
    })
    .on("loaded.jstree", function(){
        document.getElementById('related-study').style.display = 'none';
        document.getElementById('buttons').style.display = 'none';
    })
    .on("move_node.jstree", function (e, data) {
        var wholeTree = $.jstree.reference('#jstree').get_json(0); //get the whole tree
        var t = $.toJSON(wholeTree);
        $.ajax({
            method: "POST",
            url : "write_json.php",
            data: {data: t},
            dataType: "text",
            success: function(msg){
                console.log(msg);
            }
        });
    })
    .on("rename_node.jstree", function (e, data) {
        var wholeTree = $.jstree.reference('#jstree').get_json(0); //get the whole tree
        var t = $.toJSON(wholeTree);
        $.ajax({
            method: "POST",
            url : "write_json.php",
            data: {data: t},
            dataType: "text",
            success: function(msg){
                console.log(msg);
            }
        });
    })
    .on("refresh.jstree", function (e, data) {
        var originTree_instance = data.instance;
        var selectedNode = originTree_instance.get_selected();
        var msg = data.msg;

        if(msg == 'delete'){
            console.log("delete");
            originTree_instance.delete_node(selectedNode);
            var wholeTree = $.jstree.reference('#jstree').get_json(0); //get the whole tree
            var t = $.toJSON(wholeTree);
            //console.log(t);
            $.ajax({
                method: "POST",
                url : "write_json.php",
                data: {data: t},
                dataType: "text",
                success: function(msg){
                    console.log(msg);
                }
            });
        }
        if(msg == 'rename'){
            originTree_instance.edit(selectedNode);
        }
        if(msg == 'create'){
            originTree_instance.create_node(selectedNode, {}, "last", function (new_node) {
                            setTimeout(function () { originTree_instance.edit(new_node); },0);
                        });
        }
        if(msg == 'create_group'){
            //Create parent node
            var studyRef = document.getElementsByName("study");
            var study_array = [];
            for(var i=0; i<studyRef.length; i++)
                if(studyRef[i].checked)
                    study_array.push(studyRef[i].value);
            var sel = originTree_instance.create_node("#", {/*"text":"a", */"type":"root"});

            //Create children nodes
            var varRef = document.getElementsByName("variable");
            var var_array = [];
            for(var i=0; i<varRef.length; i++)
                if(varRef[i].checked)
                    var_array.push(varRef[i].value);

            for(var i=0; i<var_array.length; i++){
                originTree_instance.create_node(sel, var_array[i]);
            }

            //Name the parent node
            originTree_instance.edit(sel);

            var node = $.jstree.reference('#jstree').get_node(sel);
            node['data'] = study_array;


            $("#heading").attr("class", "");
            $("#heading").empty();
            $("#group_setting").empty();
        }
        if(msg == 'cut'){
            originTree_instance.cut(selectedNode);
        }
        if(msg == 'copy'){
            originTree_instance.copy(selectedNode);
        }
        if (msg == 'paste'){
            originTree_instance.paste(originTree_instance.is_parent(selectedNode) ? selectedNode : originTree_instance.get_parent(selectedNode));
        }
    })
        
});


    </script>
	

</body>

</html>
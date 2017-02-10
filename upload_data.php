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
    
    <script src="vendors/jquery/jquery-1.10.2.js"></script>
</head>

<?php
    require "/dbconnect.php";
    require_once('/vendors/func-lib/phpExcel/PHPExcel.php');

    $filefolder = "./resources/data/upload/";

    //Add category
    if(isset($_POST["category_name"])){
        if($_POST["category_name"] != ""){
            $insertCategory = "insert into ".$_POST["radio_group"]." (name) values('".$_POST["category_name"]."')";
            mysqli_query($db, $insertCategory);

            //Study need to create a new directory under ./data/
            if($_POST["radio_group"] == "studies"){
                mkdir ($filefolder.$_POST["category_name"]);
            }
        }
    }

    //Delete category
    if(isset($_POST["d_study"])){
        if($_POST["d_study"] != ""){
            $deleteCategory = "delete from studies where name='".$_POST["d_study"]."'";
            mysqli_query($db, $deleteCategory);
        }
    }
    if(isset($_POST["d_age_level"])){
        if($_POST["d_age_level"] != ""){
            $deleteCategory = "delete from agelevels where name='".$_POST["d_age_level"]."'";
            mysqli_query($db, $deleteCategory);
        }
    }
    if(isset($_POST["d_sport"])){
        if($_POST["d_sport"] != ""){
            $deleteCategory = "delete from sports where name='".$_POST["d_sport"]."'";
            mysqli_query($db, $deleteCategory);
        }
    }
    if(isset($_POST["d_investigator"])){
        if($_POST["d_investigator"] != ""){
            $deleteCategory = "delete from investigators where name='".$_POST["d_investigator"]."'";
            mysqli_query($db, $deleteCategory);
        }
    }
    if(isset($_POST["d_fundingsource"])){
        if($_POST["d_fundingsource"] != ""){
            $deleteCategory = "delete from fundingsources where name='".$_POST["d_fundingsource"]."'";
            mysqli_query($db, $deleteCategory);
        }
    }

    //Upload file
    $uploaded = false;

    if(!isset($_FILES["fileToUpload"]) && !isset($_POST["target_dir"])){
?>
        <script>
            $(document).ready(function(){
                $("#upload_info").attr("class", "alert alert-info");
                $("#upload_info").html("<strong>Waiting for uploading.</strong> Please set category first.");

            });
        </script>

<?php   
    }
    else if($_FILES["fileToUpload"]["name"] == "" && isset($_POST["target_dir"]) && $_POST["target_dir"] != ""){
?>

        <script>
            $(document).ready(function(){
                $("#upload_info").attr("class", "alert alert-danger");
                $("#upload_info").html("<strong>Failed.</strong>   Please choose file to upload.");

            });
        </script>
<?php
    }
    else if($_FILES["fileToUpload"]["name"] != "" && isset($_POST["target_dir"]) && $_POST["target_dir"] == ''){
?>

        <script>
            $(document).ready(function(){
                $("#upload_info").attr("class", "alert alert-danger");
                $("#upload_info").html("<strong>Failed.</strong>   Please choose target directory.");

            });
        </script>
<?php
    }
    else if($_FILES["fileToUpload"]["name"] == "" && isset($_POST["target_dir"]) && $_POST["target_dir"] == ''){
?>

        <script>
            $(document).ready(function(){
                $("#upload_info").attr("class", "alert alert-danger");
                $("#upload_info").html("<strong>Failed.</strong>  Please choose file and target directory.");

            });
        </script>
<?php
    }
    
    else{
        if(!isset($_POST["study"]) || !isset($_POST["age_level"]) || !isset($_POST["sport"]) || !isset($_POST["investigator"]) || !isset($_POST["fundingsource"])){
?>

            <script>
                $(document).ready(function(){
                    $("#upload_info").attr("class", "alert alert-danger");
                    $("#upload_info").html("<strong>Failed.</strong> Please set categories.");

                });
            </script>

<?php
        }
        else{
            $target_dir = $_POST["target_dir"];
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
?>

            <script>
                $(document).ready(function(){
                    $("#upload_info").attr("class", "alert alert-success");
                    $("#upload_info").html("<strong>Well done!</strong> You successfully upload this file.");

                });
            </script>

<?php
                $uploaded = true;
            }
            else {
?>

            <script>
                $(document).ready(function(){
                    $("#upload_info").attr("class", "alert alert-danger");
                    $("#upload_info").html("<strong>Sorry.</strong> There was an error of uploading.");

                });
            </script>

<?php
            }
        }
    }

    //Import data
    if($uploaded == true){
        //Whether the table exisits or not
        $query = "show tables like '" . $_POST['study'] . "'";
        if(mysqli_num_rows(mysqli_query($db, $query)) != 1){
            //Create new table with four basic variables.
            $createTable = "create table " . $_POST['study']. "(id int primary key, agelevel varchar(20), sport varchar(20), investigator varchar(30), fundingsource varchar(30))";
            mysqli_query($db, $createTable);
        }
        
        //Initiation
        $filename = $target_file;
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $i = 0;
        $totalColumn = 0; //To control there won't be extra empty element in the array if the Excel file include some empty columns in the end.
        $variables_array = array();
        $hasUnexistVar = false;

        foreach($objWorksheet->getRowIterator() as $row){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            /*
            * Process vaariables.
            * Check whether the variables already in the table
            */
            //This takes 10s data with 90 columns.
            if($i == 0){
                
                //1. Get the first line of the sheet.
                $cellIterator->setIterateOnlyExistingCells(true);
                foreach($cellIterator as $cell){
                    array_push($variables_array, $cell->getValue());
                    $totalColumn++;
                }

                //2. Get variables already existing in the table, and store them in an array.
                $query = "show columns from " . $_POST['study'];
                $result = mysqli_query($db, $query);
                $existVariables = array();
                while($row = mysqli_fetch_assoc($result)){
                    array_push($existVariables, $row['Field']);
                }

                //3. Check each variable in "the first line" exist or not. If not add the var into the table.
                //Assume the first is id.
                $addColumn = "alter table " . $_POST['study'] . " add(";
                for ($r = 1; $r < count($variables_array); $r++){
                    if(!in_array($variables_array[$r], $existVariables)){
                        $hasUnexistVar = true;
                        $addColumn .= $variables_array[$r] . " varchar(30),";
                    }
                }
                if($hasUnexistVar){
                    $addColumn = substr($addColumn, 0, -1);
                    $addColumn .= ")";
                    mysqli_query($db, $addColumn);
                }
                
            }
            //Insert values into table
            else{
                $j = 0;
                $fields = array();
                foreach($cellIterator as $cell){
                    array_push($fields, $cell->getValue());
                    $j++;
                    if($j == $totalColumn) break;
                }

                //This is fast enough!!!
                //If the id isn't in the DB, use insert since it is so effecient.
                $query = "select id from " . $_POST['study'] . " where id=" . $fields[0];
                if(mysqli_num_rows(mysqli_query($db, $query)) == 0){
                    $createNewRow = "insert into " . $_POST['study'] . "(id,agelevel,sport,investigator,fundingsource";
                    for($k = 1; $k < count($fields); $k++){
                        $createNewRow .= "," . $variables_array[$k];
                    }
                    $createNewRow .= ") values(" . $fields[0] . ",'" . $_POST['age_level'] . "','" . $_POST['sport'] . "','" . $_POST['investigator'] . "','" . $_POST['fundingsource'] . "'";
                    for($k = 1; $k < count($fields); $k++){
                        $createNewRow .= ",'" . $fields[$k] . "'";
                    }
                    $createNewRow .= ")";
                    mysqli_query($db, $createNewRow);
                }
                //If the id is already in DB, then update.
                else{
                    $updateRow = "insert into " . $_POST['study'] . "(id) values(" . $fields[0].") on duplicate key update ";
                    $updateRow .= "agelevel='".$_POST['age_level']."',sport='".$_POST['sport']."',investigator='".$_POST['investigator']."',fundingsource='".$_POST['fundingsource']."'";
                    for($k = 1; $k < count($fields)-1; $k++){
                        $updateRow .= "," . $variables_array[$k] . "='" . $fields[$k] . "'";
                    }
                    mysqli_query($db, $updateRow);
                }
                

                //This way is too slow.
                /*for($k = 0; $k < count($fields); $k++){
                    if($k == 0){
                        //Check if the id already exists. If no, create a new row.
                        $query = "select id from " . $_POST['study'] . " where id=" . $fields[$k];
                        if(mysqli_num_rows(mysqli_query($db, $query)) == 0){
                            $createNewRow = "insert into " . $_POST['study'] . "(id,agelevel,sport,investigator,fundingsource) values (" . $fields[0] . ",'" . $_POST['age_level'] . "','" . $_POST['sport'] . "','" . $_POST['investigator'] . "','" . $_POST['fundingsource'] . "')";
                            mysqli_query($db, $createNewRow);
                        }
                    }
                    else{
                        $updateValue = "update " . $_POST['study'] . " set " . $variables_array[$k] . " = '" . $fields[$k] . "' where id=" . $fields[0];
                        mysqli_query($db, $updateValue);
                    }
                }*/
            }
            $i++;
        }
    }
?>

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
                <a class="navbar-brand" href="upload_data.php"><strong><i class="icon fa fa-group"></i>  Gfeller Center</strong></a>
				
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
                        <a class="active-menu" href="upload_data.php"><i class="fa fa-cloud-upload"></i>Upload Data</a>
                    </li>
                    <li>
                        <a href="download_data.php"><i class="fa fa-cloud-download"></i>Download Data</a>
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
                            Upload Data
                        </h1>					
		  </div>
            <div id="page-inner">
            <form action="upload_data.php" method="post" enctype="multipart/form-data">
            	<div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Set Category</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                	<div class="category-item">
                                        <i class="fa fa-fw fa-book"></i><strong>&nbspStudy:&nbsp&nbsp</strong>
                                        <select name="study">
<?php
    $query = "select * from studies";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select>           
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-bar-chart-o"></i><strong>&nbsp&nbspAge Level:&nbsp&nbsp</strong>
                                        <select name="age_level">
<?php
    $query = "select * from agelevels";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select>   
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-trophy"></i><strong>&nbsp&nbspSports:&nbsp&nbsp</strong>
                                        <select name="sport">
<?php
    $query = "select * from sports";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select> 
                                  	</div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-user"></i><strong>&nbsp&nbspPrimary Investigator:&nbsp&nbsp</strong>
                                        <select name="investigator">
<?php
    $query = "select * from investigators";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select> 
                                  	</div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-suitcase"></i><strong>&nbsp&nbspFunding Source:&nbsp&nbsp</strong>
                                        <select name="fundingsource">
<?php
    $query = "select * from fundingsources";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select> 
                                  	</div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Upload File</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                <form class="form-horizontal">
                                    	<div>
                                        <input type="file" name="fileToUpload" id="fileToUpload" class="file-button"></div>
                                        <div class="form-group">
                                        <label class="control-label">Directory:&nbsp</label>

 <?php
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
          $content .= "<option value=\"".$dir."/\">".substr($dir, 16)."/</option>";
        }
    }
    echo 
    "<select name=\"target_dir\" size=1>\n"
    .'<option value=""></option>'
    ."<option value=\"".$filefolder."\">".substr($filefolder, 16)."</option>";
    listdir($filefolder);
    echo $content
    ."</select>";
?>

                                        </div>
                                        <input type="submit" class="btn btn-info upload-button" value="Upload">
                                        <div id="upload_info"></div>

                                 </form>
                                 </div> 
                                 
                            </div>
                        </div>
                        
                </div>
                </form>
                <!-- /. ROW  -->

                <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Add Category</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                
                                <form class="form-horizontal" action="upload_data.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <br>
                                        <br>
                                        <label class="category-name control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="category_name">
                                            <br>
                                        </div>

                                    </div>
                   
                                    <div>
                                      <div class="radio3 radio-check radio-success radio-inline">
                                        <input type="radio" id="radio1" name="radio_group"  value="studies" checked="">
                                        <label for="radio1"> 
                                          Study
                                        </label>
                                      </div>
                                      <div class="radio3 radio-check radio-success radio-inline">
                                        <input type="radio" id="radio2" name="radio_group"  value="agelevels">
                                        <label for="radio2">
                                          Age Level
                                        </label>
                                      </div>
                                      <div class="radio3 radio-check radio-success radio-inline">
                                        <input type="radio" id="radio3" name="radio_group"  value="sports">
                                        <label for="radio3">
                                          Sport
                                        </label>
                                      </div>
                                      <div class="radio3 radio-check radio-success radio-inline">
                                        <input type="radio" id="radio4" name="radio_group"  value="investigators">
                                        <label for="radio4">
                                          Primary Investigator
                                        </label>
                                      </div>
                                      <div class="radio3 radio-check radio-success radio-inline">
                                        <input type="radio" id="radio5" name="radio_group"  value="fundingsources">
                                        <label for="radio5">
                                          Funding Source
                                        </label>
                                      </div>
                                    </div>
                                 	
                                    <div>
                                    <br>
                                    <br>
                                    <input type="submit" class="btn btn-success add-button" value="Add">
                                    </div>
                                 </form>
                                 </div> 
                                 
                                    
                                </div>
                            </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Delete Catagory</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                <form class="form-horizontal" action="upload_data.php" method="post" enctype="multipart/form-data">
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-book"></i><strong>&nbspStudy:&nbsp&nbsp</strong>
                                        <select name="d_study">
<?php
    $query = "select * from studies";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select>           
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-bar-chart-o"></i><strong>&nbsp&nbspAge Level:&nbsp&nbsp</strong>
                                        <select name="d_age_level">
<?php
    $query = "select * from agelevels";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select>   
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-trophy"></i><strong>&nbsp&nbspSports:&nbsp&nbsp</strong>
                                        <select name="d_sport">
<?php
    $query = "select * from sports";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select> 
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-user"></i><strong>&nbsp&nbspPrimary Investigator:&nbsp&nbsp</strong>
                                        <select name="d_investigator">
<?php
    $query = "select * from investigators";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select> 
                                    </div>
                                    
                                    <div class="category-item">
                                        <i class="fa fa-fw fa-suitcase"></i><strong>&nbsp&nbspFunding Source:&nbsp&nbsp</strong>
                                        <select name="d_fundingsource">
<?php
    $query = "select * from fundingsources";
    echo '<option value=""></option>';
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row["name"].'">'.$row["name"].'</option>';
            }
    }
?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="deleteCmd" value="delete">
                                    <input type="submit" class="btn btn-danger delete-button" value="Delete">
                                </form>
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
    
</body>

</html>
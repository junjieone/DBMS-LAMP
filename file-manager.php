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
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html"><strong><i class="icon fa fa-group"></i> Gfeller Center</strong></a>
				
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
                        <a class="active-menu" href="file-manager.php"><i class="fa fa-folder"></i>File Manager</a>
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
                            File Manager
                        </h1>
									
		  </div>
           <div id="page-inner">
<?php

	$filefolder = "./resources/data/upload/";
	$adminfile = "file-manager.php";

	if (isset($_REQUEST['folder'])) {
			$folder = $_REQUEST['folder'];
			$folders = $_REQUEST['folder'] . "/";
		}
	else if (!isset($_REQUEST['folder'])) {
			$folder = "./data";
			$folders = $filefolder;
	}


	if (isset($_REQUEST['op'])){
		$op = $_REQUEST['op'];
	}
	else 
		$op = "";

	switch($op) {

	    case "ren":
		ren($_REQUEST['file']);
		break;

		case "rename":
		renam($_REQUEST['rename'], $_REQUEST['nrename'], $folders);
		break;

		case "del":
		del($_REQUEST['dename']);
		break;

		case "delete":
		delete($_REQUEST['dename']);
		break; 
	
	    case "mov":
		mov($_REQUEST['file']);
		break;

	    case "move":
		move($_REQUEST['file'], $_REQUEST['ndir']);
		break;

        case "viewframe":
        viewframe($_REQUEST['file']);
        break;
	}
?>
<?php
/* Rename block */
function ren($file) {
	global $adminfile, $folder, $folders, $filefolder;
	if (!$file == "") {
?>
			
				<div class="row">
                    <div class="col-sm-12 col-xs-12">
                    	<div class="panel panel-default">
                            <div class="panel-heading">
                                Rename
                            </div>
                            <div class="panel-body">
 <?php
		echo "<form action=\"".$adminfile."?op=rename&folder=".$folder."\" method=\"post\">\n"
			."<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
			."Renaming ".$folders.$file;
		
		echo "</table><br>\n"
			."<input type=\"hidden\" name=\"rename\" value=\"".$file."\">\n"
			."<input type=\"hidden\" name=\"folder\" value=\"".$folder."\">\n"
			."New Name:<br><input class=\"text\" type=\"text\" size=\"20\" name=\"nrename\">\n"
			."<input class=\"btn-warning\" type=\"Submit\" value=\"Rename\">\n";
?>
							</div>
						</div>
                    </div>
                  
                </div>
            
<?php
	}
}
/* /Rename block */

function renam($rename, $nrename, $folders) {
	global $adminfile, $folder, $folders, $filefolder;
    if (!$rename == "") {
        $loc1 = $folders.$rename; 
        $loc2 = $folders.$nrename;
    }

    if(!$nrename == ""){
        if(file_exists($loc1)){
            if(rename($loc1,$loc2)) {
                echo "<div class=\"alert alert-info\">The file <strong>".$folders.$rename."</strong> has been renamed to <a href=\"".$adminfile."?op=viewframe&file=".$nrename."&folder=$folder\"><strong>".$folders.$nrename."</strong></a></div>\n";
            } else {
                echo "<div class=\"alert alert-warning\"><strong>Problem.</strong> There was a problem renaming this file.</div>\n";
            }
        }
        else{
            echo "<div class=\"alert alert-warning\"><strong>Problem: ".$loc1."</strong> has already been renamed.</div>\n";
        } 
    }
    else{
        echo "<div class=\"alert alert-warning\"><strong>Problem: </strong>please input the new name.</div>\n";
    }
     
}

?>

<?php
/* Delete block */
function del($dename) {
	global $adminfile, $folder, $folders, $filefolder;
	if (!$dename == "") {
?>
			
				<div class="row">
                    <div class="col-sm-12 col-xs-12">
                    	<div class="panel panel-default">
                            <div class="panel-heading">
                                Delete
                            </div>
                            <div class="panel-body">

<?php
	echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
        ."<div class=\"alert alert-danger\"><strong>WARNING.</strong> This will permanatly delete <strong>".$folders.$dename.".</strong> This action is irreversable.</div>\n"
        ."Are you sure you want to delete <strong>".$folders.$dename."?</strong><br><br>\n"
        ."<a href=\"".$adminfile."?op=delete&dename=".$dename."&folder=$folder\">Yes</a> | \n"
        ."<a href=\"".$adminfile."\"> No </a>\n"
        ."</table>\n";
?>
							</div>
						</div>
                    </div>
                  
                </div>
            
<?php
	}
}
/* /Delete block */

function delete($dename) {
  global $folders;
  if (!$dename == "") {
    if (is_dir($folders.$dename)) {
      if(rmdir($folders.$dename)) {
        echo $dename." has been deleted.";
      } else {
        echo "There was a problem deleting this directory. ";
      }
    }
    else {
    	if(file_exists($folders.$dename)){
    		if(unlink($folders.$dename)) {
        		echo "<div class=\"alert alert-info\"><strong>". $dename ."</strong> has been deleted</div>\n";
      		} else {
        		echo "<div class=\"alert alert-warning\"><strong>Problem.</strong> There was a problem deleting this file.</div>\n";
      		}
    	}
      	else{
      		echo "<div class=\"alert alert-warning\"><strong>Problem.</strong> The file doesn't exist.</div>\n";
      	}
    }
  }
}

?>

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

/* Rename block */
function mov($file) {
	global $adminfile, $folder, $folders, $filefolder, $content;
	if (!$file == "") {
?>
			
				<div class="row">
                    <div class="col-sm-12 col-xs-12">
                    	<div class="panel panel-default">
                            <div class="panel-heading">
                                Move
                            </div>
                            <div class="panel-body">
 <?php
		echo "<form action=\"".$adminfile."?op=move\" method=\"post\">\n"
        ."<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
        ."Move&nbsp&nbsp<strong>".$folders.$file."&nbsp&nbsp</strong>to:&nbsp&nbsp\n"
        ."<select class=\"btn-warning\" name=ndir size=1>\n"
        ."<option value=\"".$filefolder."\">".substr($filefolder, 16)."</option>";
    	listdir($filefolder);
    	echo $content
        ."</select>"
        ."</table><br><input type=\"hidden\" name=\"file\" value=\"".$file."\">\n"
        ."<input type=\"hidden\" name=\"folder\" value=\"".$folder."\">\n" 
        ."<input type=\"Submit\" value=\"Move\" class=\"btn btn-warning\">\n";
?>
							</div>
						</div>
                    </div>
                  
                </div>

<?php
	}
}
/* /Rename block */

function move($file, $ndir) {
  global $folders;
  if (!$file == "") {
  	if(file_exists($folders.$file)){
  		if (rename($folders.$file, $ndir.$file)) {
    		echo "<div class=\"alert alert-info\"><strong>". $folders.$file ."</strong> has been succesfully moved to <strong>".$ndir."</strong></div>\n";
    	} else {
    		echo "<div class=\"alert alert-warning\"><strong>Problem:</strong> There was an error moving <strong>".$folders.$file."</strong></div>\n";
    	}
  	}
    else{
    	echo "<div class=\"alert alert-warning\"><strong>Problem: ".$folders.$file."</strong> has already been moved.</div>\n";
    }
  }
}

?>


                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                    	<div class="panel panel-default">
                            <div class="panel-heading">
                                Files
                            </div>
                                <div class="panel-body">
<?php
function viewframe($file) {
  global $adminfile, $folder, $folders, $filefolder;  
  
}
?>

<?php
/* Display file structure */
	$IMG_RENAME = "resources/img/font.png";
	$IMG_DELETE = "resources/img/delete.png";
	$IMG_MOVE = "resources/img/drive_go.png";
	$IMG_FOLDER_MOVE = "resources/img/folder_go.png";
	$IMG_VIEW = "resources/img/view.png";
	$IMG_FOLDER = "resources/img/folder.png";
	$IMG_FILE = "resources/img/file.png";
	
	
	$content1 = "";
	$content2 = "";
	
	$count = "0";
	$style = opendir($folders);
	$a=1;
	$b=1;
	
	
	
	
	while($stylesheet = readdir($style)) {
	if (strlen($stylesheet)>40) { 
	  $sstylesheet = substr($stylesheet,0,40)."...";
	} else {
	  $sstylesheet = $stylesheet;
	}
	if ($stylesheet[0] != "." && $stylesheet[0] != ".." ) {
	  if (is_dir($folders.$stylesheet) && is_readable($folders.$stylesheet)) { 
		$content1[$a] ="<tr>\n<td><a href=\"".$adminfile."?folder=".$folders.$sstylesheet."\"><img src=$IMG_FOLDER width=15 height=15> ".$sstylesheet."</a></td>\n"
				 ."<td>"
				 ."<td>"
				 ."<td>"
				 ."<td align=\"center\"><a href=\"".$adminfile."?op=mov&file=".$stylesheet."&folder=$folder\"><img src=$IMG_FOLDER_MOVE width=15 height=15></a>\n"
				 ."</tr>\n";
		$a++;
	  } elseif (!is_dir($folders.$stylesheet) && is_readable($folders.$stylesheet)) { 
		$content2[$b] ="<tr>\n<td><a href=\"".$folders.$stylesheet."\"><img src=$IMG_FILE width=15 height=15> ".$sstylesheet."</a></td>\n"
				 ."<td align=\"left\">".filesize($folders.$stylesheet)
				 ."<td align=\"center\"><a href=\"".$adminfile."?op=ren&file=".$stylesheet."&folder=$folder\"><img src=$IMG_RENAME width=15 height=15></a>\n"
				 ."<td align=\"center\"><a href=\"".$adminfile."?op=del&dename=".$stylesheet."&folder=$folder\"><img src=$IMG_DELETE width=15 height=15></a>\n"
				 ."<td align=\"center\"><a href=\"".$adminfile."?op=mov&file=".$stylesheet."&folder=$folder\"><img src=$IMG_MOVE width=15 height=15></a>\n"
				 ."</tr>\n";
		$b++;
	  } else {
		echo "Directory is unreadable\n";
	  }
	$count++;
	} 
	}
	closedir($style);
	
	echo "<strong>Browsing:</strong> $folders\n"
	   ."<br><strong>Number of Files:</strong> " . $count . "<br><br>";
	
	echo "<font face=\"\" size=\"2\"><b>\n"
	  ."<table class=\"table table-striped table-hover\">\n";	
	
	echo "<thead class = \"table-head\">\n<tr width=100%>\n"
	  ."<td width=300>Filename\n"
	  ."<td width=65>Size\n"
	  ."<td align=\"center\" width=58>Rename\n"
	  ."<td align=\"center\" width=57>Delete\n"
	  ."<td align=\"center\" width=40>Move\n"
	  ."</tr>\n</thead>\n";
	
	//Get parent folder string.
	if($folders != $filefolder){
		$parent = substr($folder, 0, strrpos($folder, '/'));
		
		$parent_content = "<tr>\n<td><a href=\"".$adminfile."?folder=".$parent."\"><img src=$IMG_FOLDER width=15 height=15> ..</a></td>\n"
				 ."<td>"
				 ."<td>"
				 ."<td>"
				 ."<td>"
				 ."</tr>\n";
		echo $parent_content;
	}
	
	
	  
	if(!empty($content1)){
		for ($a=1; $a<count($content1)+1;$a++) {
			echo $content1[$a];
		}
	}
	
	if(!empty($content2)){
		for ($b=1; $b<count($content2)+1;$b++) {
			echo $content2[$b];
		}
	}
	
	echo"</table>";					
/* /Display file structure */		
						
?>
                        
                    		</div>
                    	</div>
                    </div>
                  
                </div>
                
                                



	
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>

</body>

</html>
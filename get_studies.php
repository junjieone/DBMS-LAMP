<?php
	require "/dbconnect.php";

    $data;

	$panel_body = '<div class="panel-body">';
	$data = $panel_body;

    $checkbox="";
	$query = "select * from studies";
    if($result = mysqli_query($db, $query)){
        while ($row = mysqli_fetch_assoc($result)){
            $checkbox .= '<label><input type="checkbox" name="study" onclick="check_function();" value="'.$row["name"].'">'.$row["name"].'</label>';
            }
    }
    $data .= $checkbox;

    /*$submitbtn = '<div><button type="button" class="btn btn-sm" onclick="set_study();">Submit</button>';
    $cancelbtn = '<button type="button" class="btn btn-sm" onclick="cancel();">Cancel</button></div>';
    $data .= $submitbtn . $cancelbtn;*/
    $data .= "</div>";
    echo $data;
?>
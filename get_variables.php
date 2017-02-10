<?php
	require "/dbconnect.php";
    function contains($array, $obj) {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] == $obj) {
                return true;
            }
        }
        return false;
    }
    $data;

	$panel_body = '<div class="panel-body">';
	$data = $panel_body;

    $checkbox="";
    $commonVariables = array();
    if(isset($_POST['checkedStudies'])){
        $checkbox .= '<label><input type="checkbox" name="variable" onclick="check_all();">Select All&nbsp</label>';

        $query = "show columns from " . $_POST['checkedStudies'][0];
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_assoc($result)){
                array_push($commonVariables, $row['Field']);
            }

        for($i = 1; $i < count($_POST['checkedStudies']); $i++){
            $temp_array = array();
            $query = "show columns from " . $_POST['checkedStudies'][$i];
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_assoc($result)){
                if(contains($commonVariables, $row['Field']))
                    array_push($temp_array, $row['Field']);
            }
            $commonVariables = $temp_array;
        }

        for($r = 1; $r < count($commonVariables); $r++){
           $checkbox .= '<label><input type="checkbox" name="variable" value="'.$commonVariables[$r].'">'.$commonVariables[$r].'&nbsp</label>';
        }
    }
    $data .= $checkbox;

    /*$submitbtn = '<div><button type="button" class="btn btn-sm" onclick="set_study();">Submit</button>';
    $cancelbtn = '<button type="button" class="btn btn-sm" onclick="cancel();">Cancel</button></div>';
    $data .= $submitbtn . $cancelbtn;*/
    $data .= "</div>";
    echo $data;
?>
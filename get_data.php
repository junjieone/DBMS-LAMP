<?php
	require "/dbconnect.php";

	$studyArray = $_POST['study'];
	$variableArray = $_POST['variables'];

	$table = '';
	$table_container = '<div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="dataTable">';

	$table_head = '<thead><tr>';
	for($i = 0; $i < count($variableArray); $i++)
		$table_head .= '<th>' . $variableArray[$i] . '</th>';
    
    $table_head .= '</tr></thead>';

    $table .= $table_container;
    $table .= $table_head;

    //If it's just one study.
    if(count($studyArray) == 1){
    	$query = "select " . $variableArray[0];
    	for($i = 1; $i < count($variableArray); $i++){
    		$query .= "," . $variableArray[$i];
    	}
    	$query .= " from " . $studyArray[0];
    	if($result = mysqli_query($db, $query)){
    		$table_row = "";
        	while ($row = mysqli_fetch_assoc($result)){
            	$table_row = "<tr>";
            	for($j = 0; $j < count($variableArray); $j++){
            		$table_row .= "<td>" . $row[$variableArray[$j]] . "</td>";
            	}
            	$table_row .= "</tr>";
            	$table .= $table_row;
           	}
    	}
    }
    //If there are several studies
    else{
        $initial_query = "select " . $variableArray[0];
        for($i = 1; $i < count($variableArray); $i++){
            $initial_query .= "," . $variableArray[$i];
        }
        for($i = 0; $i < count($studyArray); $i++){
            $query = $initial_query . " from " . $studyArray[$i];
            if($result = mysqli_query($db, $query)){
                $table_row = "";
                while ($row = mysqli_fetch_assoc($result)){
                    $table_row = "<tr>";
                    for($j = 0; $j < count($variableArray); $j++){
                        $table_row .= "<td>" . $row[$variableArray[$j]] . "</td>";
                    }
                    $table_row .= "</tr>";
                    $table .= $table_row;
                }
            }
        }
        
    }


    
    $table .= '</table></div>';

    echo $table;
?>
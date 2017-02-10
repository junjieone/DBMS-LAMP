<?php
	require "/dbconnect.php";
    require_once('/lib/phpExcel/PHPExcel.php');
    require_once('/lib/phpExcel/PHPExcel/Writer/Excel2007.php'); 

	$studyArray = $_POST['study'];
	$variableArray = $_POST['variables'];
    $targetDir = $_POST['targetDir'];
    $sheetName = $_POST['sheetName'];
    
    $filePath = $targetDir.$sheetName.".xlsx";

    if(!file_exists($filePath)){
        $fh = fopen($filePath,"w");
        fclose($fh);
    }

    $objPHPExcel = new PHPExcel(); 
    /*
    $objReader = PHPExcel_IOFactory::createReaderForFile($filePath);
    $objPHPExcel = $objReader->load($filePath);*/
    //$objPHPExcel->getActiveSheet()->getCell('A1')->setValue(11);
    $sheet = $objPHPExcel->getActiveSheet();

    $row =  0;
    //$StartCol = PHPExcel_Cell::stringFromColumnIndex(1).$row;

    for($i = 0; $i < count($variableArray); $i++){
        $sheet->getCellByColumnAndRow($i, 1)->setValue($variableArray[$i]);
    }

    //If it's just one study.
    $r = 2;
    if(count($studyArray) == 1){
        $query = "select " . $variableArray[0];
        for($i = 1; $i < count($variableArray); $i++){
            $query .= "," . $variableArray[$i];
        }
        $query .= " from " . $studyArray[0];
        if($result = mysqli_query($db, $query)){
            while ($row = mysqli_fetch_assoc($result)){
                for($j = 0; $j < count($variableArray); $j++){
                    $sheet->getCellByColumnAndRow($j, $r)->setValue($row[$variableArray[$j]]);
                }
                $r++;
            }
        }
    }
    else{
        $initial_query = "select " . $variableArray[0];
        for($i = 1; $i < count($variableArray); $i++){
            $initial_query .= "," . $variableArray[$i];
        }
        for($i = 0; $i < count($studyArray); $i++){
            $query = $initial_query . " from " . $studyArray[$i];
            if($result = mysqli_query($db, $query)){
                while ($row = mysqli_fetch_assoc($result)){
                    for($j = 0; $j < count($variableArray); $j++){
                        $sheet->getCellByColumnAndRow($j, $r)->setValue($row[$variableArray[$j]]);
                    }
                    $r++;
                }
            }
        }
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);  
    $objWriter->save($filePath/*str_replace('.php', '.xlsx', $filePath)*/);  


/*
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

    
    $table .= '</table></div>';
*/
    echo $sheetName."ssss";
?>
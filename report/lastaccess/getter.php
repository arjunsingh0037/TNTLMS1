<?php 
require_once("../../config.php");
global $DB;

// Get the parameter
$courseid = optional_param('courseid',  0,  PARAM_ALPHANUMEXT);

// If departmentid exists
if($courseid) {

    // Do your query 
    $sql2 = "SELECT *
        FROM {quiz}
        WHERE course = $courseid";

    $tests_arr = $DB->get_records_sql_menu($sql2);
        
    // echo your results, loop the array of objects and echo each one
    echo "<option value='0'>All Students</option>";
    foreach ($tests_arr as $student) {
        echo "<option value=".$student->id.">" . $student->name . "</option>";  
    }
}
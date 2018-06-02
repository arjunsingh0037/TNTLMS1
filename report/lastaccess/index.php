<?php

/* index.php */
require_once('../../config.php');
require($CFG->dirroot . '/report/lastaccess/index_form.php');

// Get the system context.
$systemcontext = context_system::instance();
$url = new moodle_url('/report/lastaccess/index.php');
// Check basic permission.
require_capability('report/lastaccess:view', $systemcontext);
// Get the language strings from language file.

$strgrade = get_string('grade', 'report_lastaccess');
$strcourse = get_string('course', 'report_lastaccess');
$strlastaccess = get_string('lastaccess', 'report_lastaccess');
$strname = get_string('name', 'report_lastaccess');
$strtitle = get_string('title', 'report_lastaccess');
// Set up page object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($strtitle);

$PAGE->requires->js(new moodle_url('/report/lastaccess/javascript/myscript.js'));
$mform = new lastaccess_form('');

echo $OUTPUT->header();
echo $OUTPUT->heading($strtitle);
$mform->get_data_custom();
$mform->display();

$courseid = $_POST['courseid'];
$testid = $_POST['testid'];

if ($courseid != NULL and $testid != NULL) {
    
    $check=$DB->get_record_sql('SELECT course
        FROM mdl_quiz
        WHERE id=?', array($testid));
    
    foreach($check as $key=>$value) {
        if($value==$courseid){
            
            $au=$DB->get_records_sql('SELECT qa.userid
            FROM mdl_quiz_attempts qa
            INNER JOIN mdl_quiz q ON q.id=qa.quiz
            WHERE quiz=? AND q.course=? ', array($testid, $courseid)); 

            $eu=$DB->get_records_sql('SELECT u.id
            FROM mdl_user u
            INNER JOIN mdl_user_enrolments ue ON ue.userid = u.id
            INNER JOIN mdl_enrol e ON e.id = ue.enrolid
            INNER JOIN mdl_course c ON e.courseid = c.id
            WHERE c.id=?', array($courseid));

            echo "The id-list of users that attempted test:<br>";
            $l1=array();
            foreach($au as $key2=>$value2) {
                print_r($key2);
                print_r('<br>');
                array_push($l1, $key2);
            }
            echo "The id-list of enrolled users:<br>";
            $l2=array();

            foreach($eu as $key3=>$value3) {
                print_r($key3);
                print_r('<br>');
                array_push($l2, $key3);
            }

            echo "The email-list of enrolled users who did not attempted test:<br>";

            foreach($l2 as $value) {
                if (!(in_array($value, $l1))) {
                    $u=$DB->get_records_sql('SELECT email FROM mdl_user WHERE id=?', array($value));
                    foreach($u as $uu) {
                        foreach($uu as $key=>$value) {
                            print_r($value);
                            print_r('<br>');
                        }                
                    }
                }
            }
            
        }
        
        else{
            print_r('Selected test not available in selected course.');
        }
    }    
}
echo $OUTPUT->footer();
 




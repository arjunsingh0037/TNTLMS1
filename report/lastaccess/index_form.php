<?php

require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");

// Get data dynamically based on the selection from the dropdown
$PAGE->requires->js(new moodle_url('/report/lastaccess/javascript/myscript.js'));

class lastaccess_form extends moodleform {

    public function definition() {
        global $DB;
        global $CFG;        
        
        $mform = & $this->_form;
        
        $sql = "SELECT id, fullname
        FROM {course}
        WHERE visible = :visible
        AND id != :siteid
        ORDER BY fullname";

        $course_arr = $DB->get_records_sql_menu($sql, array('visible' => 1, 'siteid' => SITEID));

        $tests_arr = array("All Students");
        
        $mform->addElement('select', 'courseid', "Select course", $course_arr);
        $mform->setType('courseid', PARAM_ALPHANUMEXT);

        $mform->addElement('select', 'testid', "Select test", $tests_arr);   
        $mform->setType('testid', PARAM_ALPHANUMEXT);
        
        $mform->addElement('submit', 'save', get_string('display', 'report_lastaccess'), 'align="right"');
    }

    public function get_data_custom(){
        global $DB;

        $data = parent::get_data();

        if (!empty($data)) {
            $mform =& $this->_form;

            // Add the studentid properly to the $data object.
            if(!empty($mform->_submitValues['testid'])) {
                $data->testid = $mform->_submitValues['testid'];
            }
        }

    return $data;
}
    
}

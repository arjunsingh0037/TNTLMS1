<?php
/**
 * Local Library file
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Return sorted list of user courses
 *
 * @return array                courses
 */
function block_my_course_progress_get_sorted_courses() {
    global $USER, $SITE;
	$site=$SITE;

    $courses = enrol_get_my_courses();

    if (array_key_exists($site->id,$courses)) {
        unset($courses[$site->id]);
    }

    foreach ($courses as $c) {
        if (isset($USER->lastcourseaccess[$c->id])) {
            $courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
        } else {
            $courses[$c->id]->lastaccess = 0;
        }
    }

    return $courses;
}

/**
 * Return the completion stats for the given course
 * @param  int      $courseid       the course id
 * @return float                    completion percentage
 */
function block_my_course_progress_get_completion_percentage($courseid) {
    global $USER, $DB;

    // GET THE TOTAL NUMBER OF ACTIVITIES TO COMPLETE IN THE COURSE
    $courseTotal = $DB->get_record_sql(
        "SELECT COUNT(completion) as total
        FROM {course_modules} cm
        WHERE (cm.completion = 1 or cm.completion = 2)
        AND cm.course = ?",
        array($courseid)
    );


    // GET NUMBER OF ACTIVITIES COMPLETED BY THE USER
    $userProgress = $DB->get_record_sql(
        "SELECT COUNT(completionstate) as total
        FROM {course_modules_completion} cmc, {course_modules} cm
        WHERE cmc.coursemoduleid = cm.id
        AND cmc.completionstate = 1
        AND cm.course = ? 
        AND cmc.userid = ?",
        array($courseid, (int) $USER->id)
    );


    // RETURN THE PERCENTAGE OF COMPLETED ACTIVITIES
    if( !!$courseTotal->total && !!$userProgress->total ) {     
        return (float)( (int)$userProgress->total / (int)$courseTotal->total) * 100; 
    }

    return 0;
}


/**
 * Returns the url of the first image contained in the course summary file area
 * @param  int $id the course id
 * @return string     the url to the image
 */
function block_my_course_progress_get_course_image_url($id) {
    global $CFG;
    require_once $CFG->libdir . "/filelib.php";
    $course = get_course($id);

    if ($course instanceof stdClass) {
        require_once $CFG->libdir . '/coursecatlib.php';
        $course = new course_in_list($course);
    }

    foreach ($course->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();

        if ($isimage) {
            return file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
        }
    }

    return false;
}
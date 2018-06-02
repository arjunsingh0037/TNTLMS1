<?php
/**
 * My Course Progress Block
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/blocks/my_course_progress/locallib.php');

class block_my_course_progress extends block_base {

    /**
     * Block initialization
     */
    public function init() {
        //$this->title   = get_string('block_title', 'block_my_course_progress');
        $this->title   = '';
    }

    /**
     * Return contents of my_course_progress block
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        global $CFG, $DB;
        require_once($CFG->dirroot."/calendar/lib.php");
        if($this->content !== NULL) {
            return $this->content;
        }

        $config = get_config('block_my_course_progress');

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $content = array();
        $sortedcourses = block_my_course_progress_get_sorted_courses();

        $renderer = $this->page->get_renderer('block_my_course_progress');
        /* added by arjun for tabs in my_course_progress_block in dashboard --  starts*/
        $this->content->text .= '<ul class="nav nav-tabs" role="tablist">
                                      <li class="nav-item active">
                                        <a class="nav-link active" data-toggle="tab" href="#selflearning" role="tab">Self Learning</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#liveevents" role="tab">Live Events</a>
                                      </li>
                                </ul>
                                <div class="tab-content">
                                  <div class="tab-pane active" id="selflearning" role="tabpanel">';
                                    if (empty($sortedcourses)) {
                                        $this->content->text .= get_string('nocourses','my');
                                    } else {

                                        $this->content->text .= $renderer->course_grid($sortedcourses);
                                    } 
        $this->content->text .=  '</div>';

        /* showing calender events here */
        $filtercourse    = array();
        if (empty($this->instance)) { // Overrides: use no course at all.
            $courseshown = false;
            $this->content->footer = '';

        } else {
            $courseshown = $this->page->course->id;
            if ($courseshown == SITEID) {
                $filtercourse = calendar_get_default_courses();
            } else {
                $filtercourse = array($courseshown => $this->page->course);
            }
        }                         
        list($courses, $group, $user) = calendar_set_filters($filtercourse);
        $defaultlookahead = CALENDAR_DEFAULT_UPCOMING_LOOKAHEAD;
        if (isset($CFG->calendar_lookahead)) {
            $defaultlookahead = intval($CFG->calendar_lookahead);
        }
        $lookahead = get_user_preferences('calendar_lookahead', $defaultlookahead);
        $defaultmaxevents = CALENDAR_DEFAULT_UPCOMING_MAXEVENTS;
        if (isset($CFG->calendar_maxevents)) {
            $defaultmaxevents = intval($CFG->calendar_maxevents);
        }
        $maxevents = get_user_preferences('calendar_maxevents', $defaultmaxevents);
        $events = calendar_get_upcoming($courses, $group, $user, $lookahead, $maxevents);

        if (!empty($this->instance)) {
            $link = 'view.php?view=day&amp;course='.$courseshown.'&amp;';
            $showcourselink = ($this->page->course->id == SITEID);
            $eventcontents = calendar_get_block_upcoming($events, $link, $showcourselink);
        }

        if (empty($this->content->text)) {
            $eventcontents .= '<div class="post">'. get_string('noupcomingevents', 'calendar').'</div>';
        }
        
        $this->content->text .= html_writer::div($eventcontents, 'tab-pane', ['id' => 'liveevents','role' => 'tabpanel']);
        $this->content->text .=   '</div>';
        
        /* added by arjun for tabs in my_course_progress_block in dashboard -- ends*/
        return $this->content;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Sets block header to be hidden or visible
     *
     * @return bool if true then header will be visible.
     */
    public function hide_header() {
        $config = get_config('block_my_course_progress');
        return !!$config->hideblockheader;
    }

    /**
     * Sets block default location to center of page
     *
     * @return bool if true then header will be visible.
     */
    public function specialization() {
        global $DB;
        $this->instance->defaultregion = 'content';
        $this->instance->defaultweight = 0;
        $DB->update_record('block_instances', $this->instance);
    }
}
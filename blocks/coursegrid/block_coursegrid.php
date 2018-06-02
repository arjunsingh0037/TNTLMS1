<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course summary block
 *
 * @package    block_coursegrid
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_coursegrid extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_coursegrid');
    }
    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return false;
    }

    public function instance_allow_config() {
        return true;
    }
    
    public function applicable_formats() {
        return array(
                'admin' => true,
                'site-index' => true,
                'course-view' => true,
                'mod' => false,
                'user-profile' => true,
                'my' => true
        );
    }
    
    
    public function specialization() {
        if($this->page->pagetype == PAGE_COURSE_VIEW && $this->page->course->id != SITEID) {
            $this->title = get_string('coursesummary', 'block_coursegrid');
        }
    }

    function get_content() {
        global $CFG, $DB,$OUTPUT, $PAGE,$USER;
    
 /*     $PAGE->requires->css('/blocks/featuredcourses/css/ihover.min.css');
            $PAGE->requires->css('/blocks/featuredcourses/styles.css');
*/
        if ($this->content !== null) {
            return $this->content;
        }
/*
        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
*/
        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        $this->content->text = '';
      

      $coursestmp = enrol_get_my_courses();
       require_once($CFG->dirroot. '/completion/completion_completion.php');
       
       $courses=[];
       foreach ($coursestmp as $key => $course) {
           $cc = new completion_completion(['userid' => $USER->id, 'course' => $course->id]);
           if(!$cc->is_complete()) {                  
                $courses[] = $course;
           }
       }
      
      
      $this->content->text .= '';
      
      /**
      *get_my_enrolled course return all the enrolled courses,

      */

            require_once($CFG->dirroot. '/course/renderer.php');
            //require_once($CFG->dirroot. '/my/customlib.php');
            //$crs = users_list_course($course->id);
            $crs = ' ';
    // print_object($CFG);
            $chelper = new coursecat_helper();

            foreach ($courses as $course) {                
                
                $course = new course_in_list($course);
                
                $contentteacher = $contentimages = $contentfiles = $imagelink = '';

                 // display course overview files (course image)

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    
                    $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                            '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if ($isimage) {
                        $contentimages .= html_writer::empty_tag('img', array('src' => $url, 'class' =>'attachment-medium wp-post-image', 'alt' => '02', 'itemprop' => 'image', 'style' => ''));
                        $imagelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),$contentimages);
                    } else {
                                                    
                              $url1 = "$CFG->wwwroot/theme/fmubeta/pix/courses-icon.png";
                              $contentimages .= html_writer::empty_tag('img', array('src' => $url, 'class' =>'attachment-medium wp-post-image', 'alt' => '02', 'itemprop' => 'image', 'style' => ''));
                              
                    }
                }
                
                        // course name
                        $coursename = $chelper->get_course_formatted_name($course);
                        $coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                        $coursename, array('class' => $course->visible ? '' : 'dimmed'));

                   //$content = '<div class="col-md-12">
                   $content = '<div class="col-md-12">
                    <div class="ct-productBox ct-productBox--inline ct-u-displayTable ct-u-marginBottom30">
                    <div class="ct-u-displayTableCell">
                        <div class="ct-productImage">
                         
                                '.$imagelink.'
                 
                        </div>
                    </div>
                    <div class="ct-u-displayTableCell">
                        <div class="ct-productDescription">
                            <a href="course-single.html"><h5 class="ct-fw-600 ct-u-marginBottom10">'.$coursenamelink.'</h5></a>
                               <span>';
                                              if ($course->has_summary()) {
												$tempcnt = $chelper->get_course_formatted_summary($course,
                                                array('overflowdiv' => true, 'noclean' => true, 'para' => false));
												$finalcnt = substr($tempcnt,0,80);
                                                //$content .= $finalcnt;
                                                $content .= $tempcnt;
                                                }
                   $content .= '</span>
                        </div>
                        <div class="ct-productMeta">
                            <div class="ct-u-displayTableVertical">
                                <div class="ct-u-displayTableCell">
                                     <span>'; 
                                        include_once($CFG->dirroot. '/my/classes/site.php');

                                        $site = new \my\site($DB,$CFG,$course->id);
                                        //echo $site->display_rating();
                          $content .= $site->display_rating().'</span>
                                </div>
                                  <div class="ct-u-displayTableCell">
                                  
                                  <a href="';
                          $content .= $CFG->wwwroot.'/my/coursememberlist.php?id='.$course->id.'"<i class="fa fa-user"> </i>' .$crs. '</a>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>';



                // for teacher, student count
                $coursehascontacts = $course->has_course_contacts();
                if ($coursehascontacts) {
                  //echo '<pre>',print_r($course->get_course_contacts());
                    foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                         $contentteacher = $coursecontact['rolename'].' : '.
                                html_writer::link(new moodle_url('/user/view.php',
                                        array('id' => $userid, 'course' => SITEID)),
                                    $coursecontact['username']);
                            
                        $urlimg = $CFG->wwwroot.'/user/pix.php?file=/'.$userid.'/f1.jpg';
                        $contentteacherimg = html_writer::empty_tag('img', array('src' => $urlimg,'class' => 'avatar user-12-avatar avatar-32 photo','width'=>'32','height'=>'32'));
                      $contentimage = '<div class="course-instructor" itemprop="creator">
                                          <span class="avatar"></span>
                                          <span></span>
                                      </div>'; 
                $content.= $contentimage;
                    }
                }else{
                  $urlimg = $CFG->wwwroot.'/user/pix/noteacher.png';
                  $contentteacherimg = html_writer::empty_tag('img', array('src' => $urlimg,'class' => 'avatar user-12-avatar avatar-32 photo','width'=>'32','height'=>'32'));
                  $contentimage = '<div class="course-instructor" itemprop="creator">
                                        <span class="avatar"></span>
                                        <span></span>
                                        </div>';
                  
                        $content.= $contentimage;
                }
                // $content.='</div>';

              //  $content.='</div>';
     

                $this->content->text .= $content. '';
                }
      
				$this->content->text .= ''; // for container fluid on top
                return $this->content;
           
    }
    

   /*  function preferred_width() {
        return 210;
    } */
    
}


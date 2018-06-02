<?php
/**
 * Block Renderer
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

class block_my_course_progress_renderer extends plugin_renderer_base {

	/**
	 * Output the HTML grid of courses
	 * @param  array 	$courses 	the list of enrolled courses
	 * @return string          		HTML fragment
	 */
	function course_grid($courses) {
		global $CFG;
		require_once($CFG->dirroot.'/blocks/my_course_progress/locallib.php');

		$html = sprintf('<div class="grid__container">');

		// LOOP THROUGH EACH COURSE AND GET THE OUTPUT
		foreach ($courses as $c) {

			$course = get_course($c->id);
			
			// OPEN GRID ITEM
			$html .= sprintf('<div class="grid__item">');

				// RENDER THE COURSE DETAILS
				$html .= $this->course($course);

				// IF COURSE HAS COMPLETION STATS, DO
				if ( !!$course->enablecompletion ) {

					// TODO: GET COMPLETION PERCENTAGE
					$percentage = block_my_course_progress_get_completion_percentage((int)$course->id);

					// RENDER THE COURSE PROGRESS
					$html .= $this->progress($percentage);
				}

				$starttext = 'CONTINUE';
				// OUTPUT COURSE TITLE as BUTTON MIHIR
				$html .= sprintf('<center id="cbutton"><h3 class="button_title"><a class="cprbuttonlink" href="%s">%s</a></h3> </center>',
					new moodle_url('/course/view.php', array('id' => $c->id)),
					$starttext
				);

			// CLOSE GRID ITEM
			$html .= sprintf('</div>');

		}
		$html .= sprintf('</div>');
		return $html;
	}


	/**
	 * Output the course HTML string
	 * @param  course 	$c		Moodle course object
	 * @return string     		HTML fragment
	 */
	function course($c) {
		global $CFG;
		require_once($CFG->dirroot.'/blocks/my_course_progress/locallib.php');

		// OPEN COURSE WRAPPER
		$html = sprintf('<div class="course">');
			
			// OUTPUT COURSE IMAGE
			$html .= sprintf('<img src="%s" class="course__image" alt="%s">',
				block_my_course_progress_get_course_image_url($c->id),
				$c->shortname
			);

			// OUTPUT COURSE TITLE
			if(strlen($c->fullname) > 44){
				$html .= sprintf('<h4 class="course__title"><a class="course__link" href="%s">%s..</a></h4>',
					new moodle_url('/course/view.php', array('id' => $c->id)),substr($c->fullname,0,44));
			}else{
				$html .= sprintf('<h4 class="course__title"><a class="course__link" href="%s">%s</a></h4>',
					new moodle_url('/course/view.php', array('id' => $c->id)),$c->fullname);
			}

			
		// CLOSE COURSE WRAPPER
		$html .= sprintf('</div>');

		return $html;
	}


	/**
	 * Output the progress HTML string
	 * @param  float  	$percentage 	the completion percentage
	 * @return string             		HTML fragment
	 */
	function progress($percentage) {

		// OPEN PROGRESS WRAPPER
		$html = sprintf('<div class="progress-wrapper">');

			// OUTPUT COMPLETION STATUS
			if( $percentage < 100) {
				// OUTPUT PERCENTAGE LABEL
				$html .= sprintf('<div class="progress__completion">%s <span class="progress__percent">%.0f%%</span></div>',
					get_string('completion_label', 'block_my_course_progress'),
					$percentage
				);

				// OUTPUT PROGRESS BAR
				$html .= sprintf('<div class="progress__bar"><div class="progress__bar__fill" style="width: %.0f%%;"></div></div>',
					$percentage
				);

			} else {
				// OUTPUT COMPLETED LABEL
				$html .= sprintf('<div class="progress__completion status--completed">%s</div>',
					get_string('completion_finished_label', 'block_my_course_progress')
				);
			}

		// CLOSE PROGRESS WRAPPER
		$html .= sprintf('</div>');

		return $html;
	}

}
<?php
/**
 * Settings file
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $name = 'block_my_course_progress/hideblockheader';
    $title = get_string('hideblockheader', 'block_my_course_progress');
    $description = get_string('hideblockheader_desc', 'block_my_course_progress');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

}

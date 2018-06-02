<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
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
 * Private usermanagement functions
 * @author     Siddharth Behera <siddharth@elearn10.com>
 * @package    local_usermanagement
 * @copyright  2016 lms of india
 * @license    http://www.lmsofindia.com GNU GPL v3 or later
 */
/*
 * Site level 
 */
$string['pluginname'] = 'User Managemment';
$string['userlevel'] = 'User Level Managemment';
$string['cohortlevel'] = 'Cohort Level Managemment';
$string['pagetitle'] = 'User Managemment';
$string['plugin'] = 'User Managemment';
$string['pageheader'] = 'User Managemment';
$string['city'] = 'City/town';
$string['cityh'] = 'City/town';
$string['cityh_help'] = 'Put City/town';
$string['chk1'] = 'Private';
$string['chk2'] = 'Public';

$string['webpage'] = 'Web page';
$string['webpageh'] = 'Web page';
$string['webpageh_help'] = 'Put web address of your page';

$string['icq'] = 'ICQ number';
$string['icqh'] = 'ICQ number';
$string['icqh_help'] = 'Put ICQ number';

$string['skype'] = 'Skype ID';
$string['skypeh'] = 'Skype ID';
$string['skypeh_help'] = 'Put Skype ID';

$string['aim'] = 'AIM ID';
$string['aimh'] = 'AIM ID';
$string['aimh_help'] = 'Put AIM ID';

$string['yahoo'] = 'Yahoo ID';
$string['yahooh'] = 'Yahoo ID';
$string['yahooh_help'] = 'Put Yahoo ID';

$string['msn'] = 'MSN ID';
$string['msnh'] = 'MSN ID';
$string['msnh_help'] = 'Put MSN ID';

$string['id'] = 'ID number';
$string['idh'] = 'ID number';
$string['idh_help'] = 'Put ID number';

$string['inst'] = 'Institution';
$string['insth'] = 'Institution';
$string['insth_help'] = 'Put institution name';

$string['depart'] = 'Department';
$string['departh'] = 'Department';
$string['departh_help'] = 'Put department name';

$string['phone'] = 'Phone';
$string['phoneh'] = 'Phone';
$string['phoneh_help'] = 'Put phone number';

$string['mob'] = 'Mobile phone';
$string['mobh'] = 'Mobile phone';
$string['mobh_help'] = 'Put mobile number';

$string['address'] = 'Address';
$string['addressh'] = 'Address';
$string['addressh_help'] = 'Put address';
$string['description'] = 'desc';
$string['descriptionofexercise'] = 'desc12';
$string['autogeneratedpassword'] = 'Auto generate password';
$string['setpassword'] = 'Set password';
$string['suspendusercriteria'] = 'Suspend user based on criteria';
$string['withselectedcohorts'] = 'With selected cohorts';
$string['selectcohortheader'] = 'All Cohorts';
$string['allowchangepassword'] = 'Allow Change Password';
$string['preventchangepassword'] = 'Prevent Change Password';
$string['changepasswordcapability'] = 'Change password capability at site level';
$string['cohort'] = 'Cohorts';
$string['allcohort'] = 'All cohorts';
$string['allcohort_help'] = 'List out all the cohorts';
$string['enddate'] = 'End Date';
$string['afterdays1'] = 'Suspend after number of days(from current date)';
$string['afterdays2'] = 'Suspend after number of days(from usercreation date)';
/*nihar to check */ 
$string['autogeneratepasswordconfirm'] = 'Auto generate password for {$a}';
$string['setcommonpassword'] = 'Set common password for {$a}';
$string['suspenduser'] = 'Suspend user {$a}';
$string['unsuspenduser'] = 'Unsuspend user {$a}';

$string['email:user:suspend:manual:body'] = '<p>Dear {$a->name}</p>
<p>Your account has been suspended.</p>
<p>If you feel this is unintended or want to have your account activated again,
please contact {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';

$string['email:adminuser:suspend:manual:body'] = '<p>Dear Admin</p>
<p>Below user account has been suspended.</p>
<p>{$a->userlist}</p>';

$string['email:user:unsuspend:manual:body'] = '<p>Dear {$a->name}</p>
<p>Your account has been unsuspended.</p>
<p>If you feel this is unintended or do not want to have your account activated again,
please contact {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';

$string['email:adminuser:unsuspend:manual:body'] = '<p>Dear Admin</p>
<p>Below user account has been unsuspended.</p>
<p>{$a->userlist}</p>';

$string['email:user:suspend:subject'] = 'Your account has been suspended';
$string['email:user:unsuspend:subject'] = 'Your account has been unsuspended';
$string['email:admin:suspend:subject'] = 'Suspended user account';
$string['email:admin:unsuspend:subject'] = 'Unsuspended user account';


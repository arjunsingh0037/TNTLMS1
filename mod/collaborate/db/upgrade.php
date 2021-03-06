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
 * This file keeps track of upgrades to the collaborate module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package   mod_collaborate
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('upgradelib.php');

/**
 * Execute collaborate upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_collaborate_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    $upgradelib = new collaborate_update_manager();

    if ($oldversion < 2015072400) {

        // Define field completionlaunch to be added to collaborate.
        $table = new xmldb_table('collaborate');
        $field = new xmldb_field('completionlaunch', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'grade');

        // Conditionally add field completionlaunch.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2015072400, 'collaborate');
    }

    if ($oldversion < 2015101600) {

        // Changing nullability of field intro on table collaborate to null.
        $table = new xmldb_table('collaborate');
        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, null, 'name');

        // Launch change of nullability for field intro.
        $dbman->change_field_notnull($table, $field);

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2015101600, 'collaborate');
    }

    if ($oldversion < 2016041500) {

        // Define field guestaccessenabled to be added to collaborate.
        $table = new xmldb_table('collaborate');
        $field = new xmldb_field('guestaccessenabled',
                XMLDB_TYPE_INTEGER,
                '1',
                null,
                XMLDB_NOTNULL,
                null,
                '0',
                'completionlaunch'
        );

        // Conditionally add guestaccessenabled field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field guestrole to be added to collaborate.
        $field = new xmldb_field(
                'guestrole',
                XMLDB_TYPE_CHAR,
                '2',
                null,
                XMLDB_NOTNULL,
                null,
                'pr',
                'guestaccessenabled'
        );

        // Conditionally add guestrole field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field guesturl to be added to collaborate.
        $field = new xmldb_field(
            'guesturl',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            null,
            null,
            null,
            'guestrole'
        );

        // Conditionally add guestrole field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016041500, 'collaborate');
    }

    if ($oldversion < 2016041501) {

        // Define table collaborate_recording_info to be created.
        $table = new xmldb_table('collaborate_recording_info');

        // Adding fields to table collaborate_recording_info.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('instanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('recordingid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('action', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table collaborate_recording_info.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table collaborate_recording_info.
        $table->add_index('instanceid-recordingid-action', XMLDB_INDEX_NOTUNIQUE, ['instanceid', 'recordingid', 'action']);

        // Conditionally launch create table for collaborate_recording_info.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016041501, 'collaborate');
    }

    if ($oldversion < 2016121304) {

        // Define table collaborate_sessionlink to be created.
        $table = new xmldb_table('collaborate_sessionlink');

        // Adding fields to table collaborate_sessionlink.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('collaborateid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('sessionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table collaborate_sessionlink.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('collaborateid', XMLDB_KEY_FOREIGN, array('collaborateid'), 'collaborate', array('id'));
        $table->add_key('groupid', XMLDB_KEY_FOREIGN, array('groupid'), 'groups', array('id'));

        // Adding indexes to table collaborate_sessionlink.
        $table->add_index('sessionid', XMLDB_INDEX_UNIQUE, array('sessionid'));

        // Conditionally launch create table for collaborate_sessionlink.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Add new sessionlinkid field and foreign key to recording info table.
        // Define field sessionlinkid to be added to collaborate_recording_info.
        $table = new xmldb_table('collaborate_recording_info');
        $field = new xmldb_field('sessionlinkid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, 'id');
        // Conditionally launch add field sessionlinkid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define key sessionlinkid (foreign) to be added to collaborate_recording_info.
        $key = new xmldb_key('sessionlinkid', XMLDB_KEY_FOREIGN, array('sessionlinkid'), 'collaborate_sessionlink', array('id'));
        // Launch add key sessionlinkid.
        try {
            $dbman->add_key($table, $key);
            unset($key);
        } catch (Exception $e) {
            // Let's assume key already exists - /MDL-57761.
            unset($key);
        }

        // Add new composite key.
        $index = new xmldb_index('sessionlinkid-recordingid-action', XMLDB_INDEX_NOTUNIQUE,
                ['sessionlinkid', 'recordingid', 'action']
        );

        // Conditionally launch add index sessionlinkid-recordingid-action.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016121304, 'collaborate');
    }

    if ($oldversion < 2016121305) {
        $upgradelib->migrate_recording_info_instanceid_to_sessionlink();

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016121305, 'collaborate');
    }

    if ($oldversion < 2016121306) {

        // Define index instanceid-recordingid-action (not unique) to be dropped form collaborate_recording_info.
        $table = new xmldb_table('collaborate_recording_info');
        $index = new xmldb_index('instanceid-recordingid-action', XMLDB_INDEX_NOTUNIQUE,
                ['instanceid', 'recordingid', 'action']);

        // Conditionally launch drop index instanceid-recordingid-action.
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }

        // Note, we have to keep the existing fields 0
        // so that the code in upgradelib.php can be unit tested.

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016121306, 'collaborate');
    }

    if ($oldversion < 2016121307) {

        // Define field deletionattempted to be added to collaborate_sessionlink.
        $table = new xmldb_table('collaborate_sessionlink');
        $field = new xmldb_field('deletionattempted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'sessionid');

        // Conditionally launch add field deletionattempted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Collaborate savepoint reached.
        upgrade_mod_savepoint(true, 2016121307, 'collaborate');
    }

    return true;
}

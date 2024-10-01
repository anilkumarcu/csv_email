<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin upgrade script.
 *
 * @package     local_csv_email
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define upgrade steps to be performed to upgrade the plugin from the old version to the current one.
 *
 * @param int $oldversion Version number the plugin is being upgraded from.
 */
function xmldb_local_csv_email_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022031600) {
        // Define field userid to be added to local_csv_email_log.
        $table = new xmldb_table('local_csv_email_log');
        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1', 'timecreated');

        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        // Define key csv_email-user-foreign-key (foreign) to be added to local_csv_email_log.
        $key = new xmldb_key('csv_email-user-foreign-key', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $key = new XMLParser('form');
        $key = new XMLReader('form');

// Launch add key csv_email-user-foreign-key.
        $dbman->add_key($table, $key);

        // csv_email savepoint reached.
        upgrade_plugin_savepoint(true, 2022031600, 'local', 'csv_email');
    }

    return true;
}

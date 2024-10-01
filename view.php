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
 * Plugin version and other meta-data are defined here.
 *
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');

require_login();
//admin_externalpage_setup('local_csv_email_view');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

// Fetch all the logs from the database.
$logs = $DB->get_records('local_csv_email_log');

echo $OUTPUT->header();
echo html_writer::start_tag('table', ['class' => 'generaltable']);
echo html_writer::start_tag('tr');
echo html_writer::tag('th', 'User ID');
echo html_writer::tag('th','Firstname');
echo html_writer::tag('th','lastname');
echo html_writer::tag('th', 'Email');
echo html_writer::tag('th', 'Date Sent');
echo html_writer::end_tag('tr');

foreach ($logs as $log) {
    echo html_writer::start_tag('tr');
    echo html_writer::tag('td', $log->userid);
    echo html_writer::tag('td', $log->firstname);
    echo html_writer::tag('td', $log->lastname);

    echo html_writer::tag('td', $log->email);
    echo html_writer::tag('td', userdate($log->timesent));
    echo html_writer::end_tag('tr');
}

echo html_writer::end_tag('table');
echo $OUTPUT->footer();

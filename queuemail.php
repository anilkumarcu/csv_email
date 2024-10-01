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
//admin_externalpage_setup('local_csv_email_queuemail');

$context = context_system::instance();
require_capability('moodle/site:config', $context);
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/csv_email/index.php'));

//$PAGE->set_title('Upload_CSV');
$PAGE->set_title(get_string('pluginname', 'local_csv_email'));
$PAGE->set_pagelayout('standard');

// Fetch all users from the database.
$users = $DB->get_records('user');

// Select a random user.
$randomuser = $users[array_rand($users)];

// Queue the email using a scheduled task.
$task = new \local_csv_email\task\send_email_task();

$task->set_custom_data(['userid' => $randomuser->id, 'email' => $randomuser->email,'firstname'=> $randomuser->firstname,'lastname'=> $randomuser->lastname]);
\core\task\manager::queue_adhoc_task($task,true);
 
// Log the email.
$log = new stdClass();
$log->userid = $randomuser->id;
$log->firstname = $randomuser->firstname;
$log->lastname = $randomuser->lastname;
$log->email = $randomuser->email;
$log->timesent = time();
$DB->insert_record('local_csv_email_log', $log);

echo $OUTPUT->header();
echo 'Random email queued for user: ' . $randomuser->email;
echo '<br>random firstname : '. $randomuser->firstname;
echo '<br>random lastname  : '. $randomuser->lastname;
echo $OUTPUT->footer();

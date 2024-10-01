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
//admin_externalpage_setup('local_csv_email_sendemails');

$context = context_system::instance();
require_capability('moodle/site:config', $context);
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/csv_email/index.php'));

//$PAGE->set_title('Upload_CSV');
$PAGE->set_title(get_string('pluginname', 'local_csv_email'));
$PAGE->set_pagelayout('standard');

// Fetch the CSV data that was uploaded.
$csvdata = optional_param('csvdata', '', PARAM_RAW);

if (!$csvdata) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification('No CSV file uploaded or processed. Please go back and upload a CSV.', 'error');
    echo $OUTPUT->footer();
    exit;
}

// Process the CSV data.
$csvdata = array_map('str_getcsv', explode("\n", $csvdata));

// Define column indices (firstname, lastname, email).
$firstname_index = 0;
$lastname_index = 1;
$email_index = 2;

$email_count = 0;
$error_count = 0;
$errors = [];

// Loop through each row and queue an email.
foreach ($csvdata as $row) {
    if (count($row) < 3) {
        // Skip rows with missing data.
        continue;
    }

    // Extract user information.
    $firstname = trim($row[$firstname_index]);
    $lastname = trim($row[$lastname_index]);
    $email = trim($row[$email_index]);

    if (!validate_email($email)) {
        // Log invalid email and continue to the next record.
        $errors[] = "Invalid email format for $firstname $lastname ($email)";
        $error_count++;
        continue;
    }

    // Prepare the adhoc task.
    $task = new \local_csv_email\task\send_queued_email_task();

    // Set custom data to pass the email details to the task.
    $task->set_custom_data([
        
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
    ]);
    
    // Queue the task for asynchronous execution.
    \core\task\manager::queue_adhoc_task($task);

    // Increment the email count.
    $email_count++;
}

// Display a summary of the process.
echo $OUTPUT->header();
echo $OUTPUT->heading("Email Queue Summary");

if ($email_count > 0) {
    echo $OUTPUT->notification("$email_count emails were queued for sending.", 'success');
}

if ($error_count > 0) {
    echo $OUTPUT->notification("$error_count emails had errors and were not queued.", 'error');
    
    if (!empty($errors)) {
        echo html_writer::start_tag('ul');
        foreach ($errors as $error) {
            echo html_writer::tag('li', s($error));
        }
        echo html_writer::end_tag('ul');
    }
}

echo $OUTPUT->footer();
    
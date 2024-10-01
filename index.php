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
 * @package     local_moodle_csv_email
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot.'/local/csv_email/classes/form/upload_form.php');

require_login();
//admin_externalpage_setup('local_csv_email');

$context = context_system::instance();
//require_capability('moodle/site:config', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/csv_email/index.php'));

//$PAGE->set_title('Upload_CSV');
$PAGE->set_title(get_string('pluginname', 'local_csv_email'));
$PAGE->set_heading('Upload CSV and Send Emails');
$PAGE->set_pagelayout('standard');



$mform = new \local_csv_email\form\upload_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/csv_email/index.php'));
} else if ($data = $mform->get_data()) {
    // Handle file upload.
    $csvfile = $mform->get_file_content('csvfile');
    $csvdata = array_map('str_getcsv', explode("\n", $csvfile));

    // Display the CSV data.
    echo $OUTPUT->header();
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    foreach ($csvdata as $row) {
        echo html_writer::start_tag('tr');
        foreach ($row as $cell) {
            echo html_writer::tag('td', ($cell));
        }
        echo html_writer::end_tag('tr');
    }
    echo html_writer::end_tag('table');
    echo $OUTPUT->footer();
} else {
    
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}



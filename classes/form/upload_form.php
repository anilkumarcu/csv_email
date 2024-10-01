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

namespace local_csv_email\form;
use moodleform;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class upload_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add a file picker element for CSV file.
        $mform->addElement('filepicker', 'csvfile', get_string('uploadcsv', 'local_csv_email'), null, array('accepted_types' => '*.csv'));
        //$mform->addRule('csvfile', null, 'required', null, 'client'); 

        $this->add_action_buttons(true, get_string('uploadcsv', 'local_csv_email'));
        $mform->addElement('html', '<br><br><hr style="border: 1px solid #222cc; margin: 10px 0;"><br>');


 $mform->addElement('html','<hr>');

    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Perform CSV validation if necessary.
        
    if (empty($data['email'])) {
        //echo $errors['email'];
    
    }
        return $errors;
    }
}

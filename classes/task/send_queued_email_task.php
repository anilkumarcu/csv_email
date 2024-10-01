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
namespace local_csv_email\task;
use Exception;

class send_queued_email_task extends \core\task\adhoc_task {
    /**
     * Execute the task (send the email).
     */ 
    public function execute() {
        global $DB;

        $data = $this->get_custom_data();
        $email = $data->email;
        $firstname = $data->firstname;
        $lastname = $data->lastname;

        $subject = "Sample Email to {$firstname} {$lastname}";
        $message = "Dear {$firstname} {$lastname},\n\nThis is a sample email sent by Moodle.\n\nBest regards,\nMoodle Team";
        
        // Set up the user object for the recipient.
        $user = (object) [
           
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'mailformat' => 1, 
        ];
        // Get the admin user (for "from" address).
        $adminuser = get_admin();

        // Send the email.
        if (email_to_user($user, $adminuser, $subject, $message)) {
            // If email was successfully sent, log the action.
            $log = new \stdClass();
            $log->userid = 0;
            $log->email = $email;
            $log->timesent = time();
            $DB->insert_record('local_csv_email_log', $log);
            
        } else {
            throw new \moodle_exception("Failed to send email to $email");
            Exception::invalid_param("this is invalid", $email);
        }
    }

}

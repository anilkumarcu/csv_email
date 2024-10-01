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

class send_email_task extends \core\task\adhoc_task {
    public function execute() {
        mtrace("My task started");
        $data = $this->get_custom_data();
        $userid = $data->userid;
        $email = $data->email;

        // Use Moodle email API to send the email.
        email_to_user(core_user::get_user($userid), get_admin(), 'Random Email', 'This is a random email.');

        mtrace("My task finished");
   


    }
}



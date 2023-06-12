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
 * Block activity_list is defined here.
 *
 * @package     block_activity_list
 * @copyright   2023 Avinash Pastor <avinash.pastor@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  *  This function return module completion status of logged in user.
  *
  * @param int $cmid course moduleid
  * @param int $userid  loggedin userid
  * @return string status completed
  */
function modules_completion_status($cmid, $userid) {
    global $DB;
    $status = $DB->get_record("course_modules_completion", array('coursemoduleid' => $cmid, 'userid' => $userid,
    'completionstate' => 1), 'id');
    if (!empty($status->id)) {
        return ' - completed';
    }
    return;
}

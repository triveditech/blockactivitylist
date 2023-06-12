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
class block_activity_list extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_activity_list');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $DB, $COURSE, $USER, $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        require_once($CFG->dirroot . '/blocks/activity_list/lib.php');
        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        $text = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            if (!has_capability('block/activity_list:view', $this->page->context) && isloggedin()) {
                return $this->content;
            }
            $records = $DB->get_records("course_modules", array('course' => $COURSE->id), $sort = '', $fields = 'id,
            module, instance, added', $limitfrom = 0, $limitnum = 0 );
            $text = '<ul class="list">';
            foreach ($records as $record) {
                $modname = $DB->get_record("modules", array('id' => $record->module), 'name');
                $activityname = $DB->get_record($modname->name, array('id' => $record->instance), 'id, name');
                $linkurl = new moodle_url("/mod/$modname->name/view.php", array('id' => $record->id));
                $status = modules_completion_status($record->id, $USER->id);
                $linktext = $record->id.' - '.$activityname->name.' - '. date('d-M-Y', $record->added).$status;
                $linkatag = html_writer::link($linkurl, $linktext);
                $text .= '<li>'.$linkatag .'</li>';
            }
            $text .= '</ul>';

            $this->content->text = $text;
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_activity_list');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array('course-view' => true);
    }
}

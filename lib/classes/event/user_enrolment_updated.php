<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User enrolment updated event.
 *
 * @package    core
 * @copyright  2013 Rajesh Taneja <rajesh@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace core\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event when user enrolment is updated.
 *
 * @package    core
 * @since      Moodle 2.6
 * @copyright  2013 Rajesh Taneja <rajesh@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_enrolment_updated extends base {

    /**
     * Initialise required event data properties.
     */
    protected function init() {
        $this->data['objecttable'] = 'user_enrolments';
        $this->data['crud'] = 'u';
        $this->data['level'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventuserenrolmentupdated', 'core_enrol');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        return 'User '.$this->relateduserid. ' has updated enrolment for user '.$this->userid.' in course '.$this->courseid;
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/enrol/users.php', array('id' => $this->courseid));
    }

    /**
     * Return name of the legacy event, which is replaced by this event.
     *
     * @return string legacy event name
     */
    public static function get_legacy_eventname() {
        return 'user_enrol_modified';
    }

    /**
     * Return user_enrol_modified legacy event data.
     *
     * @return \stdClass
     */
    protected function get_legacy_eventdata() {
        $legacyeventdata = $this->get_record_snapshot('user_enrolments', $this->objectid);
        $legacyeventdata->enrol = $this->other['enrol'];
        $legacyeventdata->courseid = $this->courseid;
        return $legacyeventdata;
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();
        if (!isset($this->other['enrol'])) {
            throw new \coding_exception('Enrolment plugin name must be set in $other.');
        }
        if (!isset($this->relateduserid)) {
            throw new \coding_exception('Related user id must be set.');
        }
    }
}

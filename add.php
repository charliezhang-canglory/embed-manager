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
 * add function are defined here.
 *
 * @package     local_embedmanager
 * @copyright   2024 CharlieZhang <charlie.zhang@cangloryeducation.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../config.php';
require_once $CFG->libdir . '/formslib.php';

require_login();
if (!has_capability('local/embedmanager:manage', context_system::instance())) {
    throw new \moodle_exception('nopermissions', 'error', '', get_string('view', 'local_embedmanager'));
}

class embed_form extends moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $mform->addElement('text', 'name', get_string('name', 'local_embedmanager'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'url', get_string('url', 'local_embedmanager'));
        $mform->setType('url', PARAM_URL);
        $this->add_action_buttons(true, get_string('addembed', 'local_embedmanager'));
    }
}

$mform = new embed_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/embedmanager/index.php'));
} else if ($data = $mform->get_data()) {
    $data->userid = $USER->id;
    $data->timecreated = time();
    $DB->insert_record('embed', $data);
    redirect(new moodle_url('/local/embedmanager/index.php'));
}

$PAGE->set_url(new moodle_url('/local/embedmanager/add.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('addembed', 'local_embedmanager'));
$PAGE->set_heading(get_string('addembed', 'local_embedmanager'));
$PAGE->set_pagelayout('standard');

// 添加面包屑导航
$PAGE->navbar->add(get_string('embedmanager', 'local_embedmanager'), new moodle_url('/local/embedmanager/index.php'));
$PAGE->navbar->add(get_string('addembed', 'local_embedmanager'));

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();

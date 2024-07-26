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
 * index list is defined here.
 *
 * @package     local_embedmanager
 * @copyright   2024 CharlieZhang <charlie.zhang@cangloryeducation.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once '../../config.php';

global $DB, $PAGE;

require_login();
if (!has_capability('local/embedmanager:view', context_system::instance())) {
    throw new \moodle_exception('nopermissions', 'error', '', get_string('view', 'local_embedmanager'));
}

// Your code to display the embed manager list
$PAGE->set_url(new moodle_url('/local/embedmanager/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('embedmanager', 'local_embedmanager'));
$PAGE->set_heading(get_string('embedmanager', 'local_embedmanager'));
$PAGE->navbar->add(get_string('embedmanager', 'local_embedmanager'), $PAGE->url);

echo $OUTPUT->header();
if (has_capability('local/embedmanager:manage', context_system::instance())) {
    $url = new moodle_url('/local/embedmanager/add.php', array('id' => -1));
    echo $OUTPUT->single_button($url, get_string('addembed', 'local_embedmanager'), 'get', ['style' => 'margin-bottom:1rem;']);
}

$embeds = $DB->get_records('embed');
echo html_writer::start_tag('table', ['class' => 'generaltable']);
echo html_writer::start_tag('tr');
echo html_writer::tag('th', 'ID');
echo html_writer::tag('th', get_string('name', 'local_embedmanager'));
// echo html_writer::tag('th', get_string('userid', 'local_embedmanager'));
echo html_writer::tag('th', get_string('timecreated', 'local_embedmanager'));
echo html_writer::tag('th', get_string('action', 'local_embedmanager'));
echo html_writer::end_tag('tr');

foreach ($embeds as $embed) {
    $button = '';
    if (has_capability('local/embedmanager:view', context_system::instance())) {
        $url = new moodle_url('/user/editadvanced.php', array('id' => $user->id, 'course' => $site->id));
        $button = html_writer::link(new moodle_url('/local/embedmanager/view.php', ['id' => $embed->id]), $OUTPUT->pix_icon('t/hide', get_string('view')));
    }

    if (has_capability('local/embedmanager:manage', context_system::instance())) {
        $url = new moodle_url('/user/editadvanced.php', array('id' => $user->id, 'course' => $site->id));
        $button .= html_writer::link(new moodle_url('/local/embedmanager/edit.php', ['id' => $embed->id]), $OUTPUT->pix_icon('t/edit', get_string('edit')));
        $button .= html_writer::link(new moodle_url('/local/embedmanager/delete.php', ['id' => $embed->id]), $OUTPUT->pix_icon('t/delete', get_string('delete')));
    }
    echo html_writer::start_tag('tr');
    echo html_writer::tag('td', $embed->id);
    echo html_writer::tag('td', $embed->name);
    // echo html_writer::tag('td', $embed->userid);
    echo html_writer::tag('td', userdate($embed->timecreated));
    echo html_writer::tag('td', $button);
    echo html_writer::end_tag('tr');
}

echo html_writer::end_tag('table');
echo $OUTPUT->footer();

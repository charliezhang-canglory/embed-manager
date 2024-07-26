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
 * delete function are defined here.
 *
 * @package     local_embedmanager
 * @copyright   2024 CharlieZhang <charlie.zhang@cangloryeducation.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../config.php';

require_login();
if (!has_capability('local/embedmanager:manage', context_system::instance())) {
    throw new \moodle_exception('nopermissions', 'error', '', get_string('view', 'local_embedmanager'));
}

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($confirm) {
    $DB->delete_records('embed', ['id' => $id]);
    redirect(new moodle_url('/local/embedmanager/index.php'));
}

$PAGE->set_url(new moodle_url('/local/embedmanager/delete.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('deleteembed', 'local_embedmanager'));
$PAGE->set_heading(get_string('deleteembed', 'local_embedmanager'));
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
echo $OUTPUT->confirm(get_string('confirmdelete', 'local_embedmanager'),
    new moodle_url('/local/embedmanager/delete.php', ['id' => $id, 'confirm' => 1]),
    new moodle_url('/local/embedmanager/index.php'));
echo $OUTPUT->footer();

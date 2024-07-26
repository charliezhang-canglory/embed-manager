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
 * view function is defined here.
 *
 * @package     local_embedmanager
 * @copyright   2024 CharlieZhang <charlie.zhang@cangloryeducation.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../config.php';

require_login();
if (!has_capability('local/embedmanager:view', context_system::instance())) {
    throw new \moodle_exception('nopermissions', 'error', '', get_string('view', 'local_embedmanager'));
}

$id = required_param('id', PARAM_INT);

$data = $DB->get_record('embed', ['id' => $id]);
if (!$data) {
    throw new \moodle_exception('notfound', 'error', '', get_string('view', 'local_embedmanager'));
}
$PAGE->set_url(new moodle_url('/local/embedmanager/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($data->name);
$PAGE->navbar->add($data->name, $PAGE->url);

echo $OUTPUT->header();
echo "<style>@media (min-width: 576px) {
    #page.drawers .main-inner {
        margin-top: 0;
    }
    @media (min-width: 768px) {
        #page.drawers .main-inner {
            padding: 0 0;
        }
    }
}</style>";
echo '<div style="width: 100%;height: 100vh;display: flex;"><iframe style="width:100%;height:100%;border:none;" src="' . $data->url . '"></iframe></div>';
echo $OUTPUT->footer();

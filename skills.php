<?php
require 'crud_bootstrap.php';

$pid  = profile_id($conn);
$rows = [];

$stmt = mysqli_prepare($conn, 'SELECT id, skill_name, skill_level FROM skill WHERE profile_id = ? ORDER BY id DESC');
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $pid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $level);
    while (mysqli_stmt_fetch($stmt)) {
        $rows[] = ['id' => $id, 'skill_name' => $name, 'skill_level' => $level];
    }
    mysqli_stmt_close($stmt);
}

$messages = [
    'added'   => 'เพิ่มทักษะเรียบร้อยแล้ว',
    'updated' => 'แก้ไขทักษะเรียบร้อยแล้ว',
    'deleted' => 'ลบทักษะเรียบร้อยแล้ว',
];

page_start('Manage Skills | CRUD');
$key = $_GET['success'] ?? '';
if (isset($messages[$key])) echo '<p class="ok">' . h($messages[$key]) . '</p>';

echo '<p class="muted">รายการทักษะในเรซูเม่ (ผูกกับโปรไฟล์แรกในตาราง profile)</p>';
echo '<table><tr><th>ID</th><th>Skill</th><th>Level</th><th>Actions</th></tr>';
foreach ($rows as $r) {
    $idq = urlencode((string)$r['id']);
    echo '<tr><td>' . h($r['id']) . '</td><td>' . h($r['skill_name']) . '</td><td>' . h($r['skill_level']) . '</td>'
       . '<td><a href="skill_edit.php?id=' . $idq . '">Edit</a> | <a href="skill_delete.php?id=' . $idq . '">Delete</a></td></tr>';
}
if (!$rows) echo '<tr><td colspan="4" class="muted">ยังไม่มีข้อมูลทักษะ</td></tr>';
echo '</table>';
page_end();

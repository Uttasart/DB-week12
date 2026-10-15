<?php
require 'crud_bootstrap.php';

$id     = valid_id($_GET['id'] ?? $_POST['id'] ?? null);
$pid    = profile_id($conn);
$error  = '';
$row    = null;
$levels = ['Beginner', 'Intermediate', 'Advanced'];

if (!$id) { http_response_code(400); exit('Invalid record ID'); }

// ดึงข้อมูลเดิมของ record เดียวที่เป็นของโปรไฟล์นี้
$sql  = "SELECT skill_name, skill_level FROM skill WHERE id = ? AND profile_id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ii', $id, $pid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $oldName, $oldLevel);
    if (mysqli_stmt_fetch($stmt)) $row = ['skill_name' => $oldName, 'skill_level' => $oldLevel];
    mysqli_stmt_close($stmt);
}
if (!$row) { http_response_code(404); exit('Record not found'); }

$name  = $row['skill_name'];
$level = $row['skill_level'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name  = trim((string)($_POST['skill_name'] ?? ''));
    $level = trim((string)($_POST['skill_level'] ?? ''));

    if ($name === '' || mb_strlen($name) > 120 || !in_array($level, $levels, true)) {
        $error = 'กรุณากรอกข้อมูลให้ถูกต้อง';
    } else {
        $sql  = "UPDATE skill SET skill_name = ?, skill_level = ? WHERE id = ? AND profile_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $error = 'เตรียมคำสั่ง SQL ไม่สำเร็จ';
        } else {
            mysqli_stmt_bind_param($stmt, 'ssii', $name, $level, $id, $pid);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: skills.php?success=updated');
                exit;
            }
            $error = 'แก้ไขข้อมูลไม่สำเร็จ';
            mysqli_stmt_close($stmt);
        }
    }
}

page_start('UPDATE | Edit Skill');
if ($error) echo '<p class="err">' . h($error) . '</p>';
?>
<form method="post">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= h($id) ?>">
    <label>Skill name</label>
    <input name="skill_name" required maxlength="120" value="<?= h($name) ?>">
    <label>Level</label>
    <select name="skill_level">
        <?php foreach ($levels as $choice): ?>
            <option value="<?= h($choice) ?>" <?= $choice === $level ? 'selected' : '' ?>><?= h($choice) ?></option>
        <?php endforeach; ?>
    </select>
    <p><button type="submit">Update skill</button> <a href="skills.php">Cancel</a></p>
</form>
<?php page_end(); ?>

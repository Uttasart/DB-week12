<?php
require 'crud_bootstrap.php';

$error = '';
$name  = '';
$level = 'Beginner';
$levels = ['Beginner', 'Intermediate', 'Advanced'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name  = trim((string)($_POST['skill_name'] ?? ''));
    $level = trim((string)($_POST['skill_level'] ?? ''));
    $pid   = profile_id($conn);

    if ($pid === 0 || $name === '' || mb_strlen($name) > 120 || !in_array($level, $levels, true)) {
        $error = 'ข้อมูลไม่ถูกต้อง';
    } else {
        $sql  = "INSERT INTO skill (profile_id, skill_name, skill_level) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $error = 'เตรียมคำสั่ง SQL ไม่สำเร็จ';
        } else {
            mysqli_stmt_bind_param($stmt, 'iss', $pid, $name, $level);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: skills.php?success=added');
                exit;
            }
            $error = 'บันทึกข้อมูลไม่สำเร็จ';
            mysqli_stmt_close($stmt);
        }
    }
}

page_start('CREATE | Add Skill');
if ($error) echo '<p class="err">' . h($error) . '</p>';
?>

<form method="post">
    <?php csrf_field(); ?>
    <label>Skill Name</label>
    <input name="skill_name" required maxlength="120" value="<?= h($name) ?>">
    <label>Skill Level</label>
    <select name="skill_level">
        <?php foreach ($levels as $choice): ?>
            <option value="<?= h($choice) ?>" <?= $choice === $level ? 'selected' : '' ?>><?= h($choice) ?></option>
        <?php endforeach; ?>
    </select>
    <p><button type="submit">Save Skill</button></p>
</form>

<?php page_end(); ?>

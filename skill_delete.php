<?php
require 'crud_bootstrap.php';

$id    = valid_id($_GET['id'] ?? $_POST['id'] ?? null);
$pid   = profile_id($conn);
$error = '';

if (!$id) { http_response_code(400); exit('Invalid record ID'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $sql  = "DELETE FROM skill WHERE id = ? AND profile_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $error = 'เตรียมคำสั่ง SQL ไม่สำเร็จ';
    } else {
        mysqli_stmt_bind_param($stmt, 'ii', $id, $pid);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: skills.php?success=deleted');
            exit;
        }
        $error = 'ลบข้อมูลไม่สำเร็จ';
        mysqli_stmt_close($stmt);
    }
}

page_start('DELETE | Confirm Skill Removal');
if ($error) echo '<p class="err">' . h($error) . '</p>';
?>
<div class="alert">การลบไม่สามารถย้อนกลับได้</div>
<p>ต้องการลบทักษะ record #<?= h($id) ?> ใช่หรือไม่?</p>
<form method="post">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= h($id) ?>">
    <button type="submit" class="danger">Yes, delete this skill</button>
    <a href="skills.php">Cancel</a>
</form>
<?php page_end(); ?>

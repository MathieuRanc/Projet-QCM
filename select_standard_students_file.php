<?php
	require_once('Quiz.class.php');
	require_once('quiz_common.php');

	$quiz = Quiz::getQuizById($_GET['quiz-id']);
?><?php $page_title='Sélection d\'un fichier d\'information sur les étudiants'; include 'header.php' ?>
<?php
	if (isset($_POST['action']) && ($_POST['action'] == "update-students-file")) {
	  $src_file = $students_dir . $_POST['students-file'];
	  $dst_file = $quiz->getCorrectionDir() . "students";
	  if (copy($src_file, $dst_file)) {
	    echo "Opération réussie.<br>";
	  } else {
	    echo "<br>Erreur: impossible de copier le fichier.<br>";
	  }
	} else {
?>
Si le fichier d'information sur les étudiants que vous recherchez ne se trouve pas dans la liste, demandez à <?php echo $admin_name; ?> de l'ajouter.
<br><br>
Fichier d'informations sur les étudiants :
<form method="POST" class="form-horizontal" action="select_standard_students_file.php?quiz-id=<?php echo $_GET['quiz-id'] ?>">
<input type="hidden" name="action" value="update-students-file">
<select name="students-file" class="form_elem">
<?php
$d = dir($students_dir);
$files = array();
$i = 0;
while (false !== ($entry = $d->read())) {
  if ($entry[0] != '.') {
    $files[$i++] = $entry; 
  }
}
sort($files);
foreach ($files as $nb => $file) {
    echo "<option value=\"".$file."\">".$file."</option>";
}
?>
</select>
<button type="submit" class="btn btn-primary">Sélectionner</button>
</form>
  <?php } ?>
<br>
<?php  doMainMenu() ?>
<?php include 'footer.html' ?>
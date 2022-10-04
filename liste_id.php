<?php
	require_once('Quiz.class.php');
	require_once('quiz_common.php');

	$quiz = Quiz::getQuizById($_GET['quiz-id']);
?><?php $page_title='Déposer la liste des étudiants'; include 'header.php' ?>
<form action="" method="post" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="20000">
  <!-- Le champs 'hidden' doit être défini avant le champs 'file'  -->   
  <label>Déposez la liste des étudiants pour le quiz <?php echo $quiz->getName(); ?></label> :
  <input type="file" name="mon_fichier"><br>
  <input type="submit"class="btn btn-primary"value="Envoyer"> 
</form>


<?php
$nom = $_FILES['mon_fichier']['tmp_name'];
$nomdestination = './liste_id.csv';
move_uploaded_file($nom, $nomdestination);
if ($nom) {
  echo "Fichier envoyé !";
}
?>



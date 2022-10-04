<?php
	require_once('Quiz.class.php');
	require_once('quiz_common.php');

	$quiz = Quiz::getQuizById($_GET['quiz-id']);
 # exec('python transfert.py');
?><?php $page_title='Déposer les copies des étudiants'; include 'header.php' ?>

<form method='post' action='' enctype='multipart/form-data'>
 <input type="file" name="file[]" id="file" multiple>

 <input type='submit' class="btn btn-primary" name='submit' value='envoyer'>
</form>

<?php
	if(isset($_POST['submit'])){
		$countfiles = count($_FILES['file']['name']);
		echo "Fichiers envoyés !";
		$nomdestination = '/var/www/html/qcm/ftp/';
		mkdir('/var/www/html/qcm/ftp/'.$quiz->getName().'/');
		for ($i=0; $i < $countfiles; $i++) {
			$filename = $_FILES['file']['name'][$i];
		 
			// Upload file
			move_uploaded_file($_FILES['file']['tmp_name'][$i],'/var/www/html/qcm/ftp/'.$quiz->getName().'/'.$i.'.jpg');	
		}
	}
?>

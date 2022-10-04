<?php
	require_once('Quiz.class.php');
	require_once('quiz_common.php');

	$quiz = Quiz::getQuizById($_GET['quiz-id']);
?><?php $page_title='Récupérer les copies vierges des étudiants'; include 'header.php' ?>

<?php
exec('python autocomplétion.py');
?>
<li>
<a class="btn" href="sortie.pdf?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-download"></i> Récupérer les copies vierges</a>
</li>
<?php ?>
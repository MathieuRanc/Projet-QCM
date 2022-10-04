<?php
        require_once('Quiz.class.php');
        require_once('quiz_common.php');
                                                                                                                                                                                        
        $quiz = Quiz::getQuizById($_GET['quiz-id']);
?>
<?php $page_title='Déblocage de la reconnaissance des marques'; include 'header.php' ?>
<?php
	system('kill -9 `ps aux | grep omr2 | grep -v grep | tr -s " " | cut -d " " -f 2` &');
?>
Processus OMR2 tué.
<?php doMainMenu() ?>
<?php include 'footer.html' ?>

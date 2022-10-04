<?php
	require_once('Quiz.class.php');
	require_once('quiz_common.php');

	$quiz = Quiz::getQuizById($_GET['quiz-id']);
?><?php $active_page='workflow'; $page_title='Quiz workflow'; include 'header.php' ?>

<p>Que voulez-vous faire sur le quiz 
<?php
	if ($quiz) echo $quiz->getName(); else echo 'unkown';
?> ?</p>

<div class="row-fluid">
<div class="span6">
<h3 class="page-header"><span class="badge">1</span> Quiz</h3>
<ul class="nav">
<li>
<a class="btn" href="configure_quiz.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-cog"></i> Configurer le quiz</a>
</li>
<li>
<a class="btn" href="rename_quiz.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-pencil"></i> Renommer le quiz</a>
</li>
<li>
<a class="btn" href="remove_quiz.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-trash"></i> Supprimer le quiz</a>
</li>
</ul>
</div>

<div class="span6">
<h3 class="page-header"><span class="badge">2</span> Reconnaissance des marques</h3>
<ul>
<li>
Récupérer les images scannées
  <div class="nav nav-pills">
  <!--<a class="btn" title="sur le mail qcm.isen@gmail.com" href="fetch_mails.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-envelope"></i>&nbsp;</a>-->
  <a class="btn btn-primary" title="sur le FTP de dev.isen.fr" href="get_ftp_files.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-cloud-download"></i>&nbsp;</a>
  </div>
</li>
<li>
Traitements sur les images scannées
<div class="nav nav-pills">
	<a class="btn" title="Corriger la rotation des images" href="correct_images_rotation.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-facetime-video"></i>&nbsp;</a>
	<a class="btn" title="Lancer la reconnaissance des marques" href="omr.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-barcode"></i>&nbsp;</a>
	<a class="btn btn-primary" title="Corriger la rotation et reconnaître les marques en une seule étape" href="rotate_and_omr.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-facetime-video"></i> <i class="icon-barcode"></i></a>
</div>
</li>
<li>
<a href="correct_omr_errors.php?quiz-id=<?php echo $_GET['quiz-id'] ?>">Corriger les erreurs de reconnaissance</a>
</li>
</ul>
</div>
</div>

<div class="row-fluid">
<div class="span8 offset2">
<h3 class="page-header"><span class="badge">3</span> Correction</h3>
<ul class="icons">
<li>
<a href="prepare_correction.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-tasks"></i> Préparer la correction</a>
</li>
<!-- <li>
 <a href="">Corriger les erreurs de préparation de la correction</a>
</li> -->
<li>
<a href="upload_marking.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-upload"></i> Déposer un corrigé</a> 
</li>
<!--  <li>
<a href="modify_marking.php?quiz-id=<?php echo $_GET['quiz-id'] ?>">Préparer ou modifier un corrigé en ligne</a> 
</li> -->
<li><i class="icon-group"></i> Fichier d'informations sur les étudiants
<ul class="icons">
<li>
<a href="create_students_file_from_scans_infos.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-camera"></i> Générer le fichier d'informations sur les étudiants à partir des pages scannées</a>
</li>
<li>
<a href="select_standard_students_file.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-list"></i>Choisir un fichier d'informations sur les étudiants standard</a>
</li>
</ul>
</li>
<li>
<a href="correct_quiz.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"><i class="icon-cogs"></i> Corriger l'épreuve</a>
</li>
<!-- <li>
<a href="">Associer un fichier FreeMind à une matière</a>
</li> -->
</ul>
</div>
</div>

<div class="row-fluid">
<div class="span6">
<h3 class="page-header"><span class="badge">4</span> Processus</h3>
<ul class=" nav">
<li>
<a class="btn" href="view_process_progress.php?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-dashboard"></i> Voir la progression du processus courant</a>
</li>
<li>
<a class="btn" href="view_processes.php?quiz-id=<?php echo $quiz->getId() ?>">Voir les processus terminés</a>
</li>
<li>
<a class='btn' href="liste_id.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"> Déposer la liste des étudiants</a> 
</li>
<li>
<a class='btn' href="transfert.php?quiz-id=<?php echo $_GET['quiz-id'] ?>"> Déposer les copies des étudiants</a> 
</li>
</ul>
</div>


<div class="span6">
<h3 class="page-header"><span class="badge">5</span> Fichiers</h3>
<ul class="nav">
<li>
<a class="btn" href="sheets.php?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-download"></i> Récupérer les copies vierges</a>
</li>
<li>
<a class="btn" href="view_omr_files.php?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-download"></i> Reconnaissance des marques</a>
</li>
<li>
<a class="btn" href="view_correction_files.php?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-download"></i> Correction</a>
</li>
<li>
<a class="btn" href="get_corrections.php?quiz-id=<?php echo $quiz->getId() ?>"><i class="icon-download"></i> OpenOffice avec les résultats</a>
</li>
</ul>
</div>
</div>
<?php include 'footer.html' ?>

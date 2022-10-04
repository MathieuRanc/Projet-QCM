<?php
    require_once('Quiz.class.php');
    require_once('quiz_common.php');
    require_once('view_files_common.php');
	
	$quiz = Quiz::getQuizById($_GET['quiz-id']);
        doFileOperation();
        
   function displaydir($dir)
   {
   	$tab_files = getDirFiles($dir);
			if(sizeof($tab_files)>0)
			{
			echo "<ul>";
			$nb_group = sizeof($tab_files);
			$count_group = 0;
			foreach($tab_files as $group){
				echo "<li>";
				$count = 0;
				$taille = sizeof($group);
				if(sizeof($group)>1)
				{
					echo "<button class=\"selection btn\" type=\"button\" onClick=\"doSimpleinSection(this.parentNode,true);\" value=\"tous\">tous</button>";
					echo "<button class=\"selection btn\" type=\"button\" onClick=\"doSimpleinSection(this.parentNode,false);\" value=\"aucun\">aucun</button>";
					$count_group++;
					if($count_group==$nb_group){
						echo "<ul class=\"last\">";
					} else {
						echo "<ul>";
					}

					foreach($group as $tab_file){
						$count++;
						echo "<li>";
						if($count == $taille){
							echo "<img src=\"img/joinbottom.gif\"/>";
						} else {
							echo "<img src=\"img/join.gif\"/>";
						}
						if($tab_file != null && $tab_file["file"] != null){
							showFileFromDescription($tab_file);
						}
						echo "</li>";
					}
					echo "</ul>";
				}
				else
				{
					foreach($group as $tab_file){
						$count++;
						if($count == $taille){
							echo "<img src=\"img/joinbottom.gif\"/>";
						} else {
							echo "<img src=\"img/join.gif\"/>";
						}
						if($tab_file != null && $tab_file["file"] != null){
							showFileFromDescription($tab_file);
						}
					}
				}
				echo "</li>";
			}
			echo "</ul>";
		}
   }
?><?php $page_title='Visualisation des fichiers liés à la reconnaissance des marques'; include 'header.php' ?>
<script type="text/javascript" src="./js/fichier.js"></script>
<?php doMainMenu(); ?>
<hr>
<form method="POST" action="view_omr_files.php?quiz-id=<?php echo $quiz->getId(); ?>" name="file_operations_form">

<div id="arborescence">
<h1>Fichiers du répertoire d'entrée</h1>
<?php
include("files_control.php.inc");
?>
<div class="liste_fichier">
<?php
displaydir($quiz->getOmrInputDir());
?>
</div>
</div>

<div id="arborescence">
<h1>Fichiers du répertoire de sortie</h1>
<?php
include("files_control.php.inc");
?>
<div class="liste_fichier">
<?php
displaydir($quiz->getOmrOutputDir());
?>
</div>
</div>


<div id="arborescence">
<h1>Fichiers du répertoire d'erreur</h1>
<?php
include("files_control.php.inc");
?>
<div class="liste_fichier">
<?php
displaydir($quiz->getOmrErrorDir());
?>
</div>
</div>

<div id="arborescence">
<h1>Autres fichiers</h1>
<div class="liste_fichier">
<ul>
<?php showFile("./", "omr.log"); ?>
<?php showFile("./", "omr_rotate.log"); ?>
</ul>
</div>
</div>

<br><br>

<?php doFilesMenu(); ?>
</form>
<br><br>
<br>
<hr>
<?php  doMainMenu() ?>
<?php include 'footer.html' ?>

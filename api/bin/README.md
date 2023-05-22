# Install

Ce programme nécessite un certain nombre de bibliothèques tierces :

- La bibliothèque Magick++ (popur la reconnaissance des marques sur les images).
  Sous Fedora, le paquet s'appelle ImageMagick-c++.
- Les biblothèques SDL et SDL_image.
- Le programme ldapsearch (pour accéder aux informations sur les étudiants
  dans l'annuaire LDAP).
  Sous Fedora, le paquet s'appelle openldap-utils.
- Un environnement Sun Java 1.6 correctement configuré (pour la partie
  de récupération de fichiers scannés depuis la boîte mail).
- Le programme Xvfb.
- OpenOffice Calc.

Si vous avez récupéré Quiz à partir du serveur Subversion
ou si vous voulez recompiler les programmes de reconnaissance de marques,
il vous faudra également les fichiers de développement pour Magick++.
Sous Fedora, le paquet s'appelle ImageMagick-c++-devel.
Pour compiler les programmes en C et en Java, tapez make dans le
répertoire src/

## Processus

Ce fichier détaille la marche à suivre pour créer et corriger un quiz.
Il y a différentes étapes à suivre :

- création et paramétrage d'un quiz,
- préparation du ou des barêmes associés au quiz,
- scan des feuilles de réponse et envoi par mail,
- récupération des mails,
- reconnaissance des réponses sur les scans,
- traitement éventuel des erreurs de reconnaissance,
- transformation des données brutes en vue de la correction,
- renseignement de la liste des étudiants,
- correction,
- analyse des résultats.

Chaque étape est gérée par un script différent dans le répertoire bin/.
Chaque script prend en paramètre le nom du quiz qu'il doit traiter.

Voici la description des étapes successives à suivre :

- Créer le quiz avec la commande :
  ./bin/create_quiz.sh nom_du_quiz
  Merci de respecter la convention de nommage suivante :
  AA_MM_JJ-GROUPE-INFO_QUIZ
  avec : - AA l'année sur 2 chiffres - MM le mois sur deux chiffres - JJ le jour sur deux chiffres - GROUPE le nom de la promo ou du groupe d'étudiants concerné - INFO_QUIZ le nom de la matière ou du sujet concerné

- Editer le fichier quiz_nom_du_quiz/quiz.conf en fonction du nombre de questions
  attribué à chaque matière dans le quiz. Le format à suivre est indiqué dans
  l'en-tête du fichier.

- Préparer le ou les fichiers de barème dans le répertoire quiz/nom_du_quiz/.
  Il y a un fichier de barème par matière. Il faut les nommer nom_matiere.marking
  où nom_matiere correspond aux noms de matière indiqués dans le fichier quiz.conf.
  Le fichier sample.marking indique le format à suivre.

- Scanner les pages et les envoyer à l'adresse mail qcm.isen@gmail.com.
  Utiliser l'imprimante du niveau 4 avec les paramètres suivants : - niveaux de gris - résolution : 200 dpi - format de fichier : jpeg
  Ne pas scanner plus de 25 pages à la fois pour ne pas dépasser la limite de taille
  des mails.
  Les pages peuvent être scannées dans n'importe quel sens de rotation.
  Il est possible de scanner une page de référence contenant les réponses attendues.
  Pour la différencier des autres, on utilise 00000 comme identifiant étudiant.

- Récupérer les scans avec la commande :
  ./bin/fetch_scans_from_mail.sh nom_du_quiz

- Lancer la reconnaissance des réponses sur les scans avec la commande :
  ./bin/omr.sh nom_du_quiz

- Vérifier le contenu du répertoire quiz/nom_du_quiz/omr_errors/.
  S'il est vide, vous pouvez passer à l'étape suivante.
  S'il contient des fichiers, il faut vérifier manuellement la détection des marques.
  Les erreurs détectées par le module de reconnaissance des réponses sont explicitées
  dans le fichier omr_errors.txt
  Le nom de l'image scannée est sous la forme nombre.jpg.
  On utilise deux programmes de reconnaissance de marques différents. Ils génèrent
  des fichiers de données nommés nom_image.omr1_data et nom_image.omr2_data.
  Ils génèrent également des images où apparaissent les marques reconnues. Leurs
  noms sont nom_image.omr1.jpg et nom_image.omr2.jpg.
  Pour effectuer les corrections, créer un fichier du même nom que l'image originale
  suffixé par .mmr_data (pour manual mark recognition).
  Ce fichier doit avoir le même format que les fichiers .omr1_data et .omr2_data.

- Valider les éventuelles corrections d'erreur avec la commande :
  ./bin/omr_errors_resolved.sh nom_du_quiz
  Le script vérifie que vous avez bien corrigé les erreurs et si c'est le cas, il
  déplace les fichiers correspondants dans le répertoire omr_output de votre quiz.

- Transformer les données brutes en données corrigeables :
  ./bin/prepare_correction.sh nom_du_quiz
  Si une page de référence a été scannée, un fichier nom_matiere.correction_data
  est généré dans le répertoire correction.

- Renseigner la liste des étudiants :
  Il faut créer un fichier nommé students et le placer dans le répertoire
  correction du quiz. Il contient sur chaque ligne les informations pour un étudiant
  sous la forme :
  nom prénom pABCDE
  Il est possible de créer ce fichier automatiquement à partir des informations récoltées
  sur les feuilles scannées avec la commande suivante :
  ./bin/create_students_file_from_scans_infos.sh nom_du_quiz
  Attention, cette manière de procéder ne permet pas de gérer les absents.

- Lancer la correction avec la commande :
  ./bin/correct_quiz.sh nom_du_quiz
  Ce script génère un fichier nom_matiere.students_marks dans le répertoire correction
  pour chacune des matières du quiz.

Informations supplémentaires :

- Si on dispose d'un fichier nom_matiere.correction_answers généré dans le répertoire
  correction suite au scan d'une page de référence, on peut l'utiliser pour générer
  un squelette de bareme pour toutes les matières avec la commande :
  ./bin/create_marking_templates.sh nom_du_quiz

- Le répertoire answer_sheet/ contient la fiche de réponses à fournir aux étudiants.

## TODO

- OMR :

  - Prendre en compte la marque de rotation pour la rotation de l'image
  - Vérifier le cas où il y a une ligne verticale dans le calcul de l'intersection des deux droites

- Scripts :

  - Ecrire create_marking_templates.sh
  - Détecter l'utilisation multiple d'un login
  - Détecter les coches dans des mauvaises lignes

- Interface Web :
  - Régler problème de synchronisation sur le script de création d'un processus
  - Création des corrigés en ligne

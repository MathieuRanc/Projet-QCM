#!/bin/bash

# Récupère le répertoire courant du script en cours d'exécution
SCRIPT_DIR=$(dirname $0)

# Inclut le script quiz_common.sh à partir du répertoire courant
# Ce script contient des variables et fonctions communes à plusieurs scripts du quiz
source ${SCRIPT_DIR}/quiz_common.sh

# Définit le chemin d'accès au fichier students dans le répertoire de correction du quiz
STUDENTS_FILE=${QUIZ_DIR}/correction/students

# Réinitialise le contenu du fichier students
echo -n "" >${STUDENTS_FILE}

# Récupère le premier élément de la variable QUIZ_PARTS et le stocke dans FIRST_QUIZ_PART
FIRST_QUIZ_PART=$(echo "$QUIZ_PARTS" | head -n 1)

# Extrait tous les noms d'utilisateurs à partir du fichier students_answers du quiz
# et les stocke dans la variable LOGINS
LOGINS=$(awk 'BEGIN {FS=";" } { print $1}' ${QUIZ_DIR}/correction/${FIRST_QUIZ_PART}.students_answers)

# Pour chaque nom d'utilisateur, exécute un script Python pour récupérer le nom complet de l'étudiant correspondant
for LOGIN in ${LOGINS}; do
    # Exécute le script Python bdd.py avec le nom d'utilisateur LOGIN et stocke le résultat dans la variable NAME
    NAME=$(python3 ${SCRIPT_DIR}/bdd.py ${LOGIN})

    # Si le script Python n'a pas renvoyé de nom, attribue les valeurs "UNKNOWN_NAME;UNKNOWN_FIRST_NAME" à NAME
    if [ "${NAME}" = "" ]; then
        NAME="UNKNOWN_NAME;UNKNOWN_FIRST_NAME"
    fi

    # Affiche le résultat sous la forme "LOGIN -> NAME"
    echo "${LOGIN} -> ${NAME}"

    # Ajoute une ligne contenant le nom et le login dans le fichier students
    echo "${NAME};${LOGIN}" >>${STUDENTS_FILE}
done

# Affiche "Done." pour indiquer la fin du traitement
echo "Done."

import numpy as np
import sys
from pathlib import Path
import os

# Get the script directory
script_dir = Path(__file__).parent

# Change the current working directory
os.chdir(script_dir)

# Ouvre le fichier CSV contenant les données des étudiants
f = open("liste_id.csv", "r")

# Charge les données du fichier CSV en utilisant NumPy et ignore la première ligne (en-têtes)
data = np.loadtxt(f, dtype=str, delimiter=";", skiprows=1)

# Récupère le login cible à partir des arguments de ligne de commande
arg = sys.argv
loginCible = str(arg[1])
loginCible = loginCible[1:]  # Supprime le premier caractère du login cible

# Parcourt les données de chaque étudiant
for Nstudent in range(len(data[:, 0])):
    # Récupère le nom, le prénom et l'identifiant de l'étudiant actuel
    name = data[Nstudent, 1]
    last_name = data[Nstudent, 2]
    identifiant = data[Nstudent, 0]

    # Vérifie si l'identifiant de l'étudiant correspond au login cible
    if (identifiant == loginCible):
        # Affiche le nom et le prénom de l'étudiant
        print(name+';'+last_name)

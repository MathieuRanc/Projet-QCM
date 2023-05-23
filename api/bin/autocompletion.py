import numpy as np
import os
import re
from pathlib import Path
import subprocess
from tqdm import tqdm

# Get the script directory
script_dir = Path(__file__).parent

# Change the current working directory
os.chdir(script_dir)

# Ouverture du fichier CSV
with open("liste_id.csv", "r") as f:
    data = np.loadtxt(f, dtype=str, delimiter=";", skiprows=1)

# Coordinates for each rectangle
coords_1 = [577, 692, 812, 935, 1048, 1524, 1635, 1760, 1880, 1996]
coords_2 = [364, 414, 474, 524, 584]
rect_size = [30, 30]  # width and height

print('Traitement en cours...')

for Nstudent, (identifiant, name, last_name, Date, examen) in enumerate(tqdm(data, ncols=70)):
    if Nstudent >= len(data[:, 0]):
        break
#
    list_of_integers = list(map(int, identifiant))

    # Copy the original.jpg to out.jpg
    subprocess.run(['cp', 'original.jpg', 'out.jpg'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

    for Nid, integer in enumerate(list_of_integers):
        # Generate the command for each rectangle
        x1 = coords_1[integer]
        y1 = coords_2[Nid]
        x2 = x1 + rect_size[0]
        y2 = y1 + rect_size[1]

        # Generate and run the command
        command = ['convert', 'out.jpg', '-fill', 'black', '-draw', f'rectangle {x1},{y1} {x2},{y2}', 'out.jpg']
        subprocess.run(command, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

    # Inscription des noms
    command = ['convert', 'out.jpg', '-font', 'calibri', '-pointsize', '40', '-draw', f'text 577,720 "{name}"', '-draw', f'text 577,775 "{last_name}"', f'{Nstudent}.jpg']
    subprocess.run(command, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

Date = data[0,3]
examen = data[0,4]

# Inscription des dates et des titres
subprocess.run(['mv', 'original.jpg', 'original.png'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
subprocess.run(['mogrify', '-fill', 'black', '-pointsize', '40', '-annotate', '+1524+720', f'Date : {Date}', '*.jpg'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
subprocess.run(['mogrify', '-fill', 'black', '-pointsize', '40', '-annotate', '+1524+775', f'Examen : {examen}', '*.jpg'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
subprocess.run(['mv', 'original.png', 'original.jpg'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

# Conversion en PDF de tous les fichiers jpg dont le nom commence par un chiffre
# subprocess.run(['convert', '*.jpg', 'sortie.pdf'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

# Get a list of all .jpg files in the current directory that start with a digit
jpg_files = [f for f in os.listdir() if re.match(r'\d.*\.jpg$', f)]

# If there are any matching files, convert them
if jpg_files:
    subprocess.run(['convert'] + jpg_files + ['sortie.jpg'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

# supression des fichiers temporaires
# os.system('rm out.jpg')
# os.system('rm [0-9]*.jpg')

print('\nLe traitement est terminé !')

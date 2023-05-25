from PIL import Image, ImageDraw, ImageFont
import numpy as np
import os
import re
from pathlib import Path
from tqdm import tqdm

# Get the script directory
script_dir = Path(__file__).parent

# Change the current working directory
os.chdir(script_dir)

# Open the CSV file
with open("liste_id.csv", "r") as f:
    data = np.loadtxt(f, dtype=str, delimiter=";", skiprows=1)

# Coordinates for each rectangle
coords_1 = [577, 692, 812, 935, 1048, 1524, 1635, 1760, 1880, 1996]
coords_2 = [364, 414, 474, 524, 584]
rect_size = [30, 30]  # width and height

print('Traitement en cours...')

font = ImageFont.truetype('calibri.ttf', 40)

for Nstudent, (identifiant, name, last_name, Date, examen) in enumerate(tqdm(data, ncols=70)):
    if Nstudent >= len(data[:, 0]):
        break
#
    list_of_integers = list(map(int, identifiant))

    # Copy the original.jpg to out.jpg
    img = Image.open('original.jpg')
    draw = ImageDraw.Draw(img)

    for Nid, integer in enumerate(list_of_integers):
        # Generate the command for each rectangle
        x1 = coords_1[integer]
        y1 = coords_2[Nid]
        x2 = x1 + rect_size[0]
        y2 = y1 + rect_size[1]

        # Draw the rectangle
        draw.rectangle([x1, y1, x2, y2], fill="black")

    # Inscription des noms
    draw.text((500, 690), name, fill='black', font=font)
    draw.text((500, 745), last_name, fill='black', font=font)

    img.save(f'{Nstudent}.jpg')

Date = data[0, 3]
examen = data[0, 4]

jpg_files = [f for f in os.listdir() if re.match(r'\d.*\.jpg$', f)]

# Inscription des dates et des titres
for filename in jpg_files:
    img = Image.open(filename)
    draw = ImageDraw.Draw(img)
    draw.text((1650, 690), f'{Date}', fill='black', font=font)
    draw.text((1720, 745), f'{examen}', fill='black', font=font)
    img.save(filename)

# Conversion en PDF de tous les fichiers jpg dont le nom commence par un chiffre
# Get a list of all .jpg files in the current directory that start with a digit

# If there are any matching files, convert them
print(jpg_files)
if jpg_files:
    img = Image.open(jpg_files[0])
    img.save("sortie.pdf", "PDF", resolution=100.0, save_all=True,
             append_images=[Image.open(i) for i in jpg_files[1:]])
    
for filename in jpg_files:
    os.remove(filename)

# suppression des fichiers temporaires

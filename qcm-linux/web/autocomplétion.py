from datetime import date
import numpy as np
import os
from Nfinish import *

#Ouverture du fichier CSV
f = open("liste_id.csv")
data = np.loadtxt(f, dtype=str,delimiter=";",skiprows=1)
print(data)

#Traitement
for Nstudent in range(len(data[:,0])):
    name = data[Nstudent,1]
    last_name = data[Nstudent,2]
    identifiant = data[Nstudent,0]
    Date = data[0,3]
    examen = data[0,4]

    map_object = map(int, identifiant)
    list_of_integers = list(map_object)
    print(list_of_integers)

    for Nid in range(len(list_of_integers)):
        if(Nid == 0):
            if(list_of_integers[Nid] == 0):
                os.system(tr)
            elif(list_of_integers[Nid] == 1):
                os.system(tr11)
            elif(list_of_integers[Nid] == 2):
                os.system(tr12)
            elif(list_of_integers[Nid] == 3):
                os.system(tr13)
            elif(list_of_integers[Nid] == 4):
                os.system(tr14)
            elif(list_of_integers[Nid] == 5):
                os.system(tr15)
            elif(list_of_integers[Nid] == 6):
                os.system(tr16)
            elif(list_of_integers[Nid] == 7):
                os.system(tr17)
            elif(list_of_integers[Nid] == 8):
                os.system(tr18)
            elif(list_of_integers[Nid] == 9):
                os.system(tr19)
        elif(Nid == 1):
            if(list_of_integers[Nid] == 0):
                os.system(tr2)
            elif(list_of_integers[Nid] == 1):
                os.system(tr21)
            elif(list_of_integers[Nid] == 2):
                os.system(tr22)
            elif(list_of_integers[Nid] == 3):
                os.system(tr23)
            elif(list_of_integers[Nid] == 4):
                os.system(tr24)
            elif(list_of_integers[Nid] == 5):
                os.system(tr25)
            elif(list_of_integers[Nid] == 6):
                os.system(tr26)
            elif(list_of_integers[Nid] == 7):
                os.system(tr27)
            elif(list_of_integers[Nid] == 8):
                os.system(tr28)
            elif(list_of_integers[Nid] == 9):
                os.system(tr29)
        elif(Nid == 2):
            if(list_of_integers[Nid] == 0):
                os.system(tr3)
            elif(list_of_integers[Nid] == 1):
                os.system(tr31)
            elif(list_of_integers[Nid] == 2):
                os.system(tr32)
            elif(list_of_integers[Nid] == 3):
                os.system(tr33)
            elif(list_of_integers[Nid] == 4):
                os.system(tr34)
            elif(list_of_integers[Nid] == 5):
                os.system(tr35)
            elif(list_of_integers[Nid] == 6):
                os.system(tr36)
            elif(list_of_integers[Nid] == 7):
                os.system(tr37)
            elif(list_of_integers[Nid] == 8):
                os.system(tr38)
            elif(list_of_integers[Nid] == 9):
                os.system(tr39)
        elif(Nid == 3):
            if(list_of_integers[Nid] == 0):
                os.system(tr4)
            elif(list_of_integers[Nid] == 1):
                os.system(tr41)
            elif(list_of_integers[Nid] == 2):
                os.system(tr42)
            elif(list_of_integers[Nid] == 3):
                os.system(tr43)
            elif(list_of_integers[Nid] == 4):
                os.system(tr44)
            elif(list_of_integers[Nid] == 5):
                os.system(tr45)
            elif(list_of_integers[Nid] == 6):
                os.system(tr46)
            elif(list_of_integers[Nid] == 7):
                os.system(tr47)
            elif(list_of_integers[Nid] == 8):
                os.system(tr48)
            elif(list_of_integers[Nid] == 9):
                os.system(tr49)
        elif(Nid == 4):
            if(list_of_integers[Nid] == 0):
                os.system(tr5)
            elif(list_of_integers[Nid] == 1):
                os.system(tr51)
            elif(list_of_integers[Nid] == 2):
                os.system(tr52)
            elif(list_of_integers[Nid] == 3):
                os.system(tr53)
            elif(list_of_integers[Nid] == 4):
                os.system(tr54)
            elif(list_of_integers[Nid] == 5):
                os.system(tr55)
            elif(list_of_integers[Nid] == 6):
                os.system(tr56)
            elif(list_of_integers[Nid] == 7):
                os.system(tr57)
            elif(list_of_integers[Nid] == 8):
                os.system(tr58)
            elif(list_of_integers[Nid] == 9):
                os.system(tr59)
    
    os.system('convert out.jpg -font calibri -pointsize 40 -draw "text 577,720 ' + name + '" out.jpg')
    os.system('convert out.jpg -font calibri -pointsize 40 -draw "text 577,775 ' + last_name +'" '+ str(Nstudent) +'.jpg')

# Inscription des dates et des titres
os.remove('out.jpg')
os.system('convert original.jpg original.png')
os.system('mogrify -fill black -pointsize 40 -annotate +1524+720 "Date : '+ Date +'" *.jpg')
os.system('mogrify -fill black -pointsize 40 -annotate +1524+775 "Examen : '+ examen +'" *.jpg')
os.system('convert original.png original.jpg')

#Conversion en PDF
os.system('convert *.jpg sortie.pdf')

#supression des fichiers temporaires
os.remove('original.png')
for i in range(len(data[:,0])):
    os.remove(str(i) + '.jpg')
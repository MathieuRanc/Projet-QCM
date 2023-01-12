import numpy as np
import os
import sys

# Ouverture du fichier CSV
f = open("liste_id.csv")
data = np.loadtxt(f, dtype=str,delimiter=";",skiprows=1)
#print(data)

arg = sys.argv
loginCible = str(arg[1])
loginCible = loginCible[1:]
for Nstudent in range(len(data[:,0])):
    name = data[Nstudent,1]
    last_name = data[Nstudent,2]
    identifiant = data[Nstudent,0]
    if(identifiant == loginCible):
        print(name+';'+last_name)

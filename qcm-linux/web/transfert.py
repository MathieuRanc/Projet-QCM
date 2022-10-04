import tkinter
import Tkinter as tk
from Tkinter import filedialog
import os
import shutil

root = Tkinter.Tk()
root.withdraw()

file_path = Tkinder.filedialog.askopenfilenames(parent=root,title='choose file')
lst = list(file_path)
nb = 0
for i in lst:
    shutil.move(i, '/var/www/html/qcm/ftp/' + str(nb) + '.jpg')

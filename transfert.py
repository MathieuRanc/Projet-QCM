import tkinter as tk
from tkinter import filedialog
import os
import shutil

root = tk.Tk()
root.withdraw()

file_path = filedialog.askopenfilenames(parent=root,title='choose file')
lst = list(file_path)
nb = 0
for i in lst:
    shutil.move(i, '".\home\qcm"' + str(nb) + '.jpg')

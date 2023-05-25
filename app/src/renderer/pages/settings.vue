<template>
  <main>
    <button @click="clearDB">Supprimer les données</button>
    <button @click="exportData">Export les données</button>
    <!-- import data -->
    <label for="restore">
      Importer des données
      <input type="file" @change="importData" />
    </label>
    <label for="restore">
      Restaurer les données
      <input type="file" @change="restoreData" />
    </label>
  </main>
</template>

<script>
import db from 'electron-db';
export default {
  methods: {
    clearDB() {
      db.clearTable('quiz', (succ, msg) => {
        console.log(succ, msg);
      });
    },
    exportData() {
      db.getAll('quiz', (succ, data) => {
        // download quiz.json file with data
        const blob = new Blob([JSON.stringify(data)], {
          type: 'application/json',
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'quiz.json';
        link.click();
      });
    },
    importData(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        const data = JSON.parse(e.target.result);
        console.log(data.quiz);
        data.quiz.forEach((quiz) => {
          db.insertTableContent('quiz', quiz, (succ, msg) => {
            console.log(succ, msg);
          });
        });
      };
      reader.readAsText(file);
    },
    restoreData(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        const data = JSON.parse(e.target.result);
        db.clearTable('quiz', (succ, msg) => {
          console.log(succ, msg);
          data.quiz.forEach((quiz) => {
            db.insertTableContent('quiz', quiz, (succ, msg) => {
              console.log(succ, msg);
            });
          });
        });
      };
      reader.readAsText(file);
    },
  },
};
</script>

<style lang="scss" scoped></style>

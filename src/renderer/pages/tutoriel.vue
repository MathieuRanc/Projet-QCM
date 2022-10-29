<template>
  <main>
    <h1>Tutoriel</h1>
    <div>
      <button @click="clicked">Click me</button>
    </div>
    <div v-for="(element, i) in elements" :key="i">
      {{ element }}
    </div>
  </main>
</template>

<script>
const db = require('electron-db');
const path = require('path');

// This will save the database in the same directory as the application.
const location = path.join(__dirname, '');

db.createTable('customers', (succ, msg) => {
  // succ - boolean, tells if the call is successful
  if (succ) {
    console.log(msg);
  } else {
    console.log('An error has occured. ' + msg);
  }
});

export default {
  name: 'TutorielPage',
  data() {
    return {
      elements: [],
    };
  },
  methods: {
    clicked() {
      if (db.valid('customers')) {
        const date = new Date().toISOString();
        db.insertTableContent('customers', { date }, (succ, msg) => {
          // succ - boolean, tells if the call is successful
          console.log('Success: ' + succ);
          console.log('Message: ' + msg);
          // get last element of the table
        });
        db.getRows('customers', { date }, (succ, result) => {
          if (succ) {
            // push in first position
            this.elements.unshift(result[0]);
          }
        });
      }
    },
  },
  mounted() {
    const em = this;
    db.getAll('customers', (succ, data) => {
      // succ - boolean, tells if the call is successful
      // put data in elements order by date
      em.elements = data.sort((a, b) => {
        return new Date(a.date) - new Date(b.date);
      });
    });
  },
};
</script>

<style lang="scss" scoped></style>

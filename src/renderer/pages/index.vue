<template>
  <main>
    <NuxtLink to="/doc/introduction" class="hero" tag="div">
      <h2>Apprendre à générer un QCM</h2>
      <h3>Parcourez la documentation pour apprendre à utiliser l’application</h3>
    </NuxtLink>
    <div class="grid">
      <h1>Récents</h1>
      <div>
        <Card
          v-for="(quiz, i) in mostRecentsQuizzes"
          :key="i"
          :id="quiz.id"
          :title="quiz.promo + ' - ' + quiz.subject"
          :type="quiz.type"
          :step="quiz.step || 0"
          :date="new Date(quiz.date).toLocaleDateString('fr-FR')"
        />
      </div>
    </div>
    <div class="grid">
      <h1>
        Epreuves à venir <span>({{ futureQuiz.length }})</span>
      </h1>
      <div>
        <Card
          v-for="(quiz, i) in futureQuiz"
          :key="i"
          :id="quiz.id"
          :title="quiz.promo + ' - ' + quiz.subject"
          :type="quiz.type"
          :step="quiz.step || 0"
          :date="new Date(quiz.date).toLocaleDateString('fr-FR')"
        />
      </div>
    </div>
    <div class="grid">
      <h1>
        Epreuves passées <span>({{ passedQuiz.length }})</span>
      </h1>
      <div>
        <Card
          v-for="(quiz, i) in passedQuiz"
          :key="i"
          :id="i + 1"
          :title="quiz.promo + ' - ' + quiz.subject"
          :type="quiz.type"
          :step="quiz.step || 0"
          :date="new Date(quiz.date).toLocaleDateString('fr-FR')"
        />
      </div>
    </div>
  </main>
</template>

<script>
import Card from '~/components/Card.vue';
var db = require('electron-db');

export default {
  // meta: {
  //   scrollPos: {
  //     x: 0,
  //     y: 0,
  //   },
  // },
  name: 'IndexPage',
  components: {
    Card,
  },
  computed: {
    quizzes() {
      var quizzes;
      db.getAll('quiz', (succ, data) => {
        if (succ) {
          // sort by date
          quizzes = data.sort((a, b) => {
            // less 1 day to compare date in UTC
            return new Date(b.date) - new Date(a.date);
          });
        }
      });
      return quizzes;
    },
    // compare date in UTC
    passedQuiz() {
      return this.quizzes
        .filter((quiz) => {
          return new Date(quiz.date) < new Date();
        })
        .sort((a, b) => {
          return new Date(b.date) - new Date(a.date);
        });
    },
    // futur from yesterday
    futureQuiz() {
      return this.quizzes
        .filter((quiz) => {
          return new Date(quiz.date) >= new Date();
        })
        .sort((a, b) => {
          return new Date(a.date) - new Date(b.date);
        });
    },
    mostRecentsQuizzes() {
      return this.quizzes
        .sort((a, b) => {
          return new Date(b.modifiedAt) - new Date(a.modifiedAt);
        })
        .slice(0, 3);
    },
  },
};
</script>

<style lang="scss" scoped>
.hero {
  background-image: url('~/assets/images/tutoriel.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 20px;
  cursor: pointer;

  width: 100%;
  height: 400px;

  padding: 40px;

  font-family: 'Fredoka';
  color: white;
  font-weight: 700;
  h2 {
    margin-bottom: 10px;
    font-size: 40px;
  }
  h3 {
    font-size: 20px;
  }
}

.grid {
  display: flex;
  flex-direction: column;
  gap: 20px;
  h1 {
    font-weight: 700;
    font-size: 30px;
    span {
      font-weight: 400;
      font-size: 20px;
    }
  }
  > div {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    grid-gap: 20px;
  }
}
</style>

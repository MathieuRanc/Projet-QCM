<template>
  <main>
    <h1>Création d’un nouveau quiz</h1>
    <div>
      Veuillez remplir le formulaire suivant en respectant les règles suivantes :
      <ul>
        <li>La date est celle où les étudiants font le quiz.</li>
        <li>Ne mettez aucun espace dans les champs. Les remplacer par des _.</li>
        <li>
          Le nom du groupe d'étudiants doit commencer par le nom de la promo suivi éventuellement du sous-groupe
          concerné séparés par un _ (ex. : CIR1, ou M1_Unix).
        </li>
        <li>L'intitulé doit permettre de reconnaître le sujet du quiz (ex. : TP1, DS).</li>
      </ul>
    </div>
    <form @submit.prevent="submit">
      <div>
        <label for="promo">
          <span>Promo</span>
          <input type="text" name="promo" id="promo"
        /></label>
        <label for="subject">
          <span>Epreuve</span>
          <input type="text" name="subject" id="subject"
        /></label>
        <label for="type">
          <span>Type d'épreuve</span>
          <input type="text" name="type" id="type"
        /></label>
        <label for="date">
          <span>Date d'examen</span>
          <input type="date" name="date" id="date" :value="new Date().toISOString().substring(0, 10)"
        /></label>
        <label for="nbQuestion">
          <span>Nombre de questions</span>
          <input type="number" name="nbQuestion" id="nbQuestion" value="20"
        /></label>
      </div>
      <input class="button" type="submit" value="Créer le QCM" />
    </form>
  </main>
</template>

<script lang="js">
const db = require('electron-db');
export default {
  name: 'Create',
  data() {
    return {
      elements: [],
      API_BASE_URL: this.$config.apiBaseUrl || 'http://localhost',
    };
  },
  methods: {
    async submit(event)  {
      const quiz = {
        promo: event.target.promo.value,
        subject: event.target.subject.value,
        type: event.target.type.value,
        date: event.target.date.value,
        nbQuestions: event.target.nbQuestion.value,
        step: 0,
        createdAt: new Date().toISOString(),
        modifiedAt: new Date().toISOString(),
      };
      console.log(quiz);

      // request localhot:8000/quiz/create with param name
      await this.$axios.$post(this.API_BASE_URL + '/quiz', {
        name: quiz.promo + '_' + quiz.subject + '_' + quiz.type + '_' + quiz.date,
        nbQuestion: quiz.nbQuestions,
      });

      db.insertTableContent('quiz', quiz, (succ, msg) => {
        // succ - boolean, tells if the call is successful
        console.log('Success: ' + succ);
        console.log('Message: ' + msg);
        // get last element of the table
      });
      this.$router.push('/');
    },
  },
  mounted() {
    const em = this;
    db.getAll('quiz', (succ, data) => {
      if (succ) {
        em.elements = data;
      }
    });
  },
};
</script>

<style lang="scss" scoped>
ul {
  list-style-type: '-';
  margin-left: 1em;
  li {
    padding-left: 1em;
  }
}
form {
  display: flex;
  flex-direction: column;
  gap: 2em;
  div {
    display: flex;
    justify-content: space-between;
    gap: 1em;
    label {
      width: 100%;
      display: flex;
      flex-direction: column;
      background-color: white;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
      border-radius: 20px;
      padding: 10px 16px;
      gap: 8px;
      span {
        font-weight: 400;
        font-size: 12px;
        color: var(--gray);
      }
      input {
        border: none;
        background: none;
        outline: none;
      }
      border: 2px solid transparent;
      &:focus-within {
        border: 2px solid var(--red);
      }
    }
  }
  input[type='submit'] {
    color: white;
    margin: 0 auto;
    border: none;
  }
}
</style>

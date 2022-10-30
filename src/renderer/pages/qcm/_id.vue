<template>
  <main>
    <!-- return link -->
    <div class="navigation">
      <button @click="$router.push('/')">
        <!-- fontawesome chevron left -->
        <i class="fas fa-chevron-left"></i>
        Retour
      </button>
      <input type="checkbox" name="menu" id="menu" />
      <label for="menu">
        <!-- fontawesome 3 dots -->
        <i class="fas fa-ellipsis-v"></i>
        <div class="menu">
          <button @click="renameQuiz">Renommer</button>
          <button @click="deleteQuiz">Supprimer</button>
        </div>
      </label>
    </div>
    <div>
      <div><span>Promo</span>{{ quiz.promo }}</div>
      <div><span>Matière</span>{{}}</div>
      <div><span>Type d'épreuve</span>{{}}</div>
      <div><span>Date d'examen</span>{{}}</div>
    </div>
    {{ quiz }}
  </main>
</template>

<script>
var db = require('electron-db');
export default {
  computed: {
    quiz() {
      // find quiz by id
      var quiz;
      db.getRows('quiz', { id: parseInt(this.$route.params.id) }, (succ, result) => {
        if (succ) {
          quiz = result;
        }
      });
      return quiz;
    },
  },
  methods: {
    renameQuiz() {},
    deleteQuiz() {},
  },
};
</script>

<style lang="scss" scoped>
.navigation {
  display: flex;
  justify-content: space-between;
  > button,
  > label {
    cursor: pointer;
    background: none;
    border: var(--gray) solid 2px;
    padding: 20px 26px;
    border-radius: 50px;
    font-size: 16px;
    display: flex;
    gap: 16px;
  }
  input[type='checkbox'] {
    position: absolute;
    left: -9999px;
  }
  input[type='checkbox']:not(:checked) + label .menu {
    opacity: 0;
    pointer-events: none;
  }
  > label {
    position: relative;
    .menu {
      transition: opacity 0.2s;

      position: absolute;
      top: 100%;
      right: 0;
      display: flex;
      flex-direction: column;
      background: white;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
      transform: translate(0, 12px);
      border-radius: 20px;
      overflow: hidden;
      &::before {
        content: '';
        background-image: url('../../assets/images/arrow.svg');
        background-position: center;
        background-size: cover;
        height: 14px;
        width: 30px;
        position: absolute;
        transform: translate(0, -90%);
        right: 14px;
      }
      button {
        background: none;
        border: none;
        padding: 30px 110px;
        font-size: 16px;
        gap: 16px;
        cursor: pointer;

        transition: color 0.1s, background-color 0.1s;
        &:hover {
          background-color: var(--red);
          color: white;
        }
        &:not(:last-child) {
          border-bottom: solid 1px var(--gray);
        }
      }
    }
  }
}
</style>

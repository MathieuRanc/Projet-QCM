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
    <div class="infos">
      <div>
        <span>Promo</span>
        <div>{{ quiz.promo }}</div>
      </div>
      <div>
        <span>Matière</span>
        <div>{{ quiz.subject }}</div>
      </div>
      <div>
        <span>Type d'épreuve</span>
        <div>{{ quiz.type }}</div>
      </div>
      <div>
        <span>Date d'examen</span>
        <!-- display date with format dd/mm/yyyy -->
        <div>{{ new Date(quiz.date).toLocaleDateString() }}</div>
      </div>
    </div>
    <hr />
    <section>
      <span>1</span>
      <div>
        <h2>Corrigé</h2>
        <p>
          Déposer un corrigé, un corrigé est une feuille d’examen contenant les réponses correctes aux questions, vous
          pouvez suivre ce lien pour <a href="/" download>télécharger un fichier exemple</a>.
        </p>
        <dropzone id="foo" ref="el" :options="options" :destroyDropzone="true"></dropzone>
        <div class="validation">
          <button class="validate" @click="validateStep">
            <!-- fontawesome check -->
            <i class="fas fa-check"></i>
            Valider
          </button>
        </div>
      </div>
    </section>
    <hr />
    <section>
      <span>2</span>
      <div>
        <h2>Impression des sujets</h2>
        <p>Récupérer une copie vierge pour afin de la photocopier pour l’ensemble des élèves</p>
        <button @click="downloadBlank">
          <!-- fontawesome file download  -->
          <i class="fas fa-file-download"></i>
          Télécharger une copie vierge
        </button>
        <div class="or">OU</div>
        <p>
          Récupérer des copies numérotées, sélectionnez un nombre de copies puis téléchargez les copies via le bouton de
          téléchargement
        </p>
        <label for="copies">
          Nombre d’étudiants
          <input type="number" name="copies" id="copies" v-model="copies" />
          <!-- fontawesome file download -->
          <button @click="downloadCopies">
            <i class="fas fa-file-download"></i>
          </button>
        </label>
        <div class="or">OU</div>
        <p>
          Téléverser la liste des élèves afin d’associer automatiquement chaque copie numérotée à un élève,
          <a href="" download>télécharger un fichier exemple</a>
        </p>
        <dropzone id="foo" ref="el" :options="options" :destroyDropzone="true"></dropzone>
        <!-- télécharger les copies des élèves -->
        <button @click="downloadCopies">
          <!-- fontawesome file download -->
          <i class="fas fa-file-download"></i>
          Télécharger les copies des élèves
        </button>
        <div class="validation">
          <button class="outline" @click="cancelStep">
            <!-- fontawesome return -->
            <i class="fas fa-undo"></i>
            Retour à l'étape précédente
          </button>
          <button class="validate" @click="validateStep">
            <!-- fontawesome check -->
            <i class="fas fa-check"></i>
            Valider
          </button>
        </div>
      </div>
    </section>
    <hr />
    <section>
      <span>3</span>
      <div>
        <h2>Récupération des copies des élèves</h2>
        <p>Téléverser les copies des élèves</p>
        <dropzone id="foo" ref="el" :options="options" :destroyDropzone="true"></dropzone>
        <div class="validation">
          <button class="outline" @click="cancelStep">
            <!-- fontawesome return -->
            <i class="fas fa-undo"></i>
            Retour à l'étape précédente
          </button>
          <button class="validate" @click="validateStep">
            <!-- fontawesome check -->
            <i class="fas fa-check"></i>
            Valider
          </button>
        </div>
      </div>
    </section>
    <hr />
    <section>
      <span>4</span>
      <div>
        <h2>Correction automatique des copies</h2>
        <p>Lancer le traitement de correction des copies</p>
        <button @click="correctCopies">
          <!-- fontawesome magic -->
          <i class="fas fa-magic"></i>
          Lancer la correction
        </button>
        <div class="validation">
          <button class="outline" @click="cancelStep">
            <!-- fontawesome return -->
            <i class="fas fa-undo"></i>
            Retour à l'étape précédente
          </button>
          <button class="validate" @click="validateStep">
            <!-- fontawesome check -->
            <i class="fas fa-check"></i>
            Valider
          </button>
        </div>
      </div>
    </section>
    <hr />
    <section>
      <span>5</span>
      <div>
        <h2>Vérification manuelle des résultats</h2>
        <p>Vérifier le bon fonctionnement de la correction automatique</p>
        <button @click="checkCorrection">
          <!-- fontawesome pen -->
          <i class="fas fa-pen"></i>
          Vérifier
        </button>
        <div class="validation">
          <button class="outline" @click="cancelStep">
            <!-- fontawesome return -->
            <i class="fas fa-undo"></i>
            Retour à l'étape précédente
          </button>
          <button class="validate" @click="validateStep">
            <!-- fontawesome check -->
            <i class="fas fa-check"></i>
            Valider
          </button>
        </div>
      </div>
    </section>
    <hr />
    <section>
      <span>6</span>
      <div>
        <h2>Résultats de l’épreuve</h2>
        <p>Récupérer les résultats de l’épreuve</p>
        <button @click="downloadResults">
          <!-- fontawesome download -->
          <i class="fas fa-download"></i>
          Télécharger
        </button>
        <div class="validation">
          <button class="outline" @click="cancelStep">
            <!-- fontawesome return -->
            <i class="fas fa-undo"></i>
            Retour à l'étape précédente
          </button>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
var db = require('electron-db');

import Dropzone from 'nuxt-dropzone';
import 'nuxt-dropzone/dropzone.css';
export default {
  data() {
    return {
      copies: 0,
      myFiles: ['tutoriel.jpg'],
      options: {
        url: 'http://httpbin.org/anything',
      },
    };
  },
  components: {
    Dropzone,
  },
  computed: {
    quiz() {
      // find quiz by id
      var quiz;
      db.getRows('quiz', { id: parseInt(this.$route.params.id) }, (succ, result) => {
        if (succ) {
          quiz = result[0];
        }
      });
      return quiz;
    },
  },
  methods: {
    renameQuiz() {},
    deleteQuiz() {},
    uploadCorrection() {},
    downloadBlank() {},
    downloadCopies() {},
    uploadStudents() {},
    uploadStudentsCopies() {},
    correctCopies() {},
    checkCorrection() {},
    downloadResults() {},
    handleFilePondInit() {
      console.log('FilePond has initialized');
    },
    validateStep(event) {
      // add current class to next tag section
      // remove current class from current tag section
      try {
        event.target.parentNode.parentNode.parentNode.classList.remove('current');
        event.target.parentNode.parentNode.parentNode.classList.add('done');
        const next = event.target.parentNode.parentNode.parentNode.nextElementSibling.nextElementSibling;
        if (next.tagName !== 'SECTION') {
          throw new Error('No next section');
        }
        next.classList.add('current');
        db.updateRow(
          'quiz',
          { id: parseInt(this.$route.params.id) },
          {
            step: parseInt(next.firstElementChild.innerText) - 1,
            modifiedAt: new Date(),
          },
          (succ, msg) => {
            if (succ) {
              console.log('update success');
            } else {
              console.log('update failed');
            }
          }
        );
      } catch (error) {
        console.log('no next element');
      }
    },
    cancelStep(event) {
      event.target.parentNode.parentNode.parentNode.classList.remove('current');
      try {
        const prev = event.target.parentNode.parentNode.parentNode.previousElementSibling.previousElementSibling;
        if (prev.tagName !== 'SECTION') {
          throw new Error('No previous section');
        }
        prev.classList.add('current');
        prev.classList.remove('done');
        db.updateRow(
          'quiz',
          { id: parseInt(this.$route.params.id) },
          {
            step: parseInt(prev.firstElementChild.innerText) - 1,
            modifiedAt: new Date(),
          },
          (succ, msg) => {
            if (succ) {
              console.log('update success');
            } else {
              console.log('update failed');
            }
          }
        );
      } catch (error) {
        console.log('no previous element');
      }
    },
  },
  mounted() {
    // Everything is mounted and you can access the dropzone instance
    const instance = this.$refs.el.dropzone;

    if (this.quiz.step) {
      // add .done class to all quiz.step fisrt section elements and add .current class to quiz.step + 1 section element
      const sections = document.querySelectorAll('section');
      for (let i = 0; i < this.quiz.step; i++) {
        sections[i].classList.add('done');
      }
      sections[this.quiz.step].classList.add('current');
    } else {
      document.querySelector('section').classList.add('current');
    }
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
        gap: 10px;
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
.infos {
  display: flex;
  gap: 10px;
  > div {
    font-weight: 700;
    font-size: 30px;
    display: flex;
    flex-direction: column;
    text-align: center;
    flex: 1;

    background: #ffffff;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 20px;

    padding: 20px;
    > span {
      font-weight: 700;
      font-size: 16px;
    }
    > div {
      margin: auto 0;
    }
  }
}
hr {
  width: 100%;
  height: 1px;
  background-color: var(--gray);
  border: none;
}
a {
  color: var(--red);
}
p {
  // normal cursor
  cursor: default;
}
section {
  display: flex;
  flex-direction: row;
  gap: 20px;
  > span {
    font-size: 30px;
    background-color: white;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
  }
  > div {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    > div.or {
      text-align: center;
      font-size: 16px;
      font-weight: 700;
    }
    button {
      background-color: var(--red);
      color: white;
      border: none;
      padding: 12px 20px;
      margin-right: auto;
      border-radius: 50px;
      font-size: 14px;
      display: flex;
      gap: 10px;
      cursor: pointer;
      transition: color 0.1s, background-color 0.1s;
      &:hover {
        background-color: var(--dark-red);
      }
    }
    label {
      cursor: text;
      background: white;
      border: none;
      padding: 12px 20px;
      margin-right: auto;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
      border-radius: 20px;

      font-size: 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      font-size: 10px;
      color: var(--gray);

      position: relative;
      input {
        border: none;
        outline: none;
        font-size: 14px;
      }
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        appearance: none;
        margin: 0;
      }
      button {
        padding: 0;
        border-radius: 0;
        background: none;
        color: var(--red);
        font-size: 20px;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translate(0, -50%);
        &:hover {
          background: none;
          color: var(--dark-red);
        }
      }
    }
  }
}

.validation {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
  button {
    margin: 0;
  }
  button.outline {
    background-color: white;
    color: black;
    border: solid 2px var(--gray);
    transition: border 0.1s;
    &:hover {
      background-color: white;
      border-color: black;
    }
  }
  button.validate {
    background-color: var(--green);
    border: solid 2px var(--green);
    transition: background-color 0.1s, border 0.1s;
    &:hover {
      background-color: var(--dark-green);
      border-color: var(--dark-green);
    }
  }
}

// section {
//   > div {
//     transition: heig 0.2s;
//     height: max-content;
//   }
// }
section:not(.current) {
  > div {
    // justify-content: center;
    // :not(h2) {
    //   display: none;
    // }
    transform: translateY(20px);
    max-height: 40px;
  }
}
i {
  pointer-events: none;
}
section {
  > div {
    overflow: hidden;
  }
}
// section:not(.done),
section.done,
section {
  > div {
    transition: all 0.3s;
    max-height: 40px;
    :not(h2) {
      transition: opacity 0.3s;
      opacity: 0;
    }
  }
}
section.current {
  > div {
    transition: transform 0.2s, max-height 1s;
    max-height: 620px;
    :not(h2) {
      transition: opacity 1s;
      opacity: 1;
    }
  }
}
section.done {
  > span {
    background-color: var(--green);
    color: white;
  }
}
</style>

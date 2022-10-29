<template>
  <main>
    <NuxtLink to="/tutoriel" class="hero" tag="div">
      <h2>Apprendre à générer un QCM</h2>
      <h3>Suivez notre tutoriel pour apprendre à utiliser l’application</h3>
    </NuxtLink>
    <div class="grid">
      <h1>Récents</h1>
      <div>
        <Card />
        <Card />
        <Card />
      </div>
    </div>
    <div class="grid">
      <h1>
        Toutes les épreuves <span>({{ count }})</span>
      </h1>
      <div>
        <Card v-for="n in count" :key="n" :id="n + 1" />
      </div>
    </div>
  </main>
</template>

<script>
import Card from '~/components/Card.vue';
export default {
  name: 'IndexPage',
  components: {
    Card,
  },
  data() {
    return {
      count: 8,
    };
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

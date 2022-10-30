<template>
  <div id="App">
    <Navbar />
    <nuxt />
    <Footer />
  </div>
</template>

<script>
import Navbar from '~/components/Navbar.vue';
import Footer from '~/components/Footer.vue';
export default {
  name: 'DefaultLayout',
  components: {
    Navbar,
    Footer,
  },
};
</script>

<style lang="scss">
@font-face {
  font-family: 'Fredoka';
  src: url('~/assets/fonts/Fredoka.ttf');
}

/* reset style */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  margin: 0 !important;
  font-family: Helvetica, Arial, sans-serif;
}

input,
textarea {
  font-family: Helvetica, Arial, sans-serif;
}

#App {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

:root {
  --red: #db3f3f;
  --green: #5cd8a4;
  --gray: #bdbdbd;
}

main {
  width: 100%;
  max-width: 1000px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 40px;
  margin: 0 auto;
  @media screen and (max-width: 1000px) {
    padding: 0 20px;
  }
}

.button {
  background-color: var(--red);
  padding: 20px 26px;
  border-radius: 50px;
  cursor: pointer;
  font-weight: 700;
}
</style>

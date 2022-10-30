<template>
  <div id="App">
    <Navbar />
    <main class="content">
      <div class="menu">
        <details open>
          <summary>Getting Started</summary>
          <a href="#/doc/introduction">Introduction</a>
          <a href="#/doc/installation">Installation</a>
          <a href="#/doc/configuration">Configuration</a>
          <a href="#/doc/advanced">Advanced</a>
        </details>

        <details open>
          <summary>Examples</summary>
          <a href="#/doc/basic-usage">Basic Usage</a>
        </details>
      </div>
      <nuxt class="page" />
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '~/components/Navbar.vue';
import Footer from '~/components/Footer.vue';
export default {
  name: 'doc',
  components: {
    Navbar,
    Footer,
  },
};
</script>

<style lang="scss">
.content {
  margin-top: 20px;
  width: 100%;
  max-width: 1000px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  @media screen and (max-width: 1000px) {
    padding: 0 20px;
  }
  .menu {
    details {
      cursor: pointer;
      -webkit-appearance: none;
      appearance: none;
      display: flex;
      flex-direction: column;
      margin-bottom: 50px;
      a {
        margin: 10px 0;
        text-decoration: none;
        color: black;
      }
    }
  }
  .page {
    flex: 1;
    width: 100%;
    p {
      margin: 20px 0;
    }
  }
}
</style>

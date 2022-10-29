<template>
  <div class="nav__container">
    <nav>
      <ul>
        <li>
          <nuxt-link to="/"><img src="~/assets/images/qcmapp.svg" alt="" /></nuxt-link>
        </li>
        <li>
          <nuxt-link to="/create" class="button">
            <i class="fas fa-plus"></i>
            Créer un nouveau qcm
          </nuxt-link>
        </li>
      </ul>
      <img src="~/assets/images/navbar.svg" alt="" />
    </nav>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.nav__container {
  height: 176px;
}
a {
  text-decoration: none;
  color: #fff;
}
ul {
  background-color: white;
  box-shadow: 0px 8px 40px rgba(0, 0, 0, 0.25);

  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 40px;
  list-style: none;
}

nav {
  z-index: 100;
  height: 143px;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  flex-direction: column;
  > img {
    width: 100%;
    pointer-events: none;
  }
}
</style>

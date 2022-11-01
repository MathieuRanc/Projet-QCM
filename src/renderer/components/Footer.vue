<template>
  <footer>
    <!-- <img src="~/assets/images/footer.svg" alt="" /> -->
    <!-- <svg width="1440" height="35" viewBox="0 0 1440 35" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 0V35H1440V0C1258.25 20.9254 1002.85 33.9461 720 33.9461C437.148 33.9461 181.752 20.9254 0 0Z" />
    </svg> -->
    <img src="../assets/images/footer.svg" alt="" />
    <NuxtLink to="/settings">
      <!-- fontawesome settings icon -->
      <i class="fas fa-cog"></i>
      Réglages
    </NuxtLink>
    <div>&copy; {{ new Date().getFullYear() }} - JUNIA</div>
  </footer>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
footer {
  margin-top: auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: var(--red);
  img {
    width: 100vw;
    object-fit: fill;
    transform: translate(0, -100%);
  }
  a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    margin: 20px 0;
    i {
      margin-right: 10px;
    }
  }
  div {
    color: #fff;
    text-align: center;
    padding: 50px;
    font-weight: 700;
    font-size: 16px;
  }
}
</style>

<style lang="scss">
footer {
  svg {
    fill: vat(--red);
  }
}
</style>

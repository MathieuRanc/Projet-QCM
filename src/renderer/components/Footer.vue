<template>
  <footer>
    <img src="~/assets/images/footer.svg" alt="" />
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
  img {
    width: 100%;
  }
  div {
    color: #fff;
    text-align: center;
    padding: 50px;
    background-color: var(--red);
    font-weight: 700;
    font-size: 16px;
  }
}
</style>

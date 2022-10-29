<template>
  <NuxtLink class="card" tag="div" :to="'/qcm/' + id">
    <h4>{{ title }}</h4>
    <Stepper :step="step" />
    <img src="~/assets/images/round_shape.svg" alt="" />
    <div class="red__part">
      <p>{{ type }}</p>
      <p>{{ date }}</p>
    </div>
  </NuxtLink>
</template>

<script>
import Stepper from '~/components/Stepper.vue';
export default {
  props: {
    id: {
      type: Number,
      default: 1,
    },
    title: {
      type: String,
      default: 'empty title',
    },
    step: {
      type: Number,
      default: 2,
    },
    type: {
      type: String,
      default: 'empty type',
    },
    date: {
      type: String,
      default: 'empty date',
    },
  },
  components: {
    Stepper,
  },
};
</script>

<style lang="scss" scoped>
.card {
  cursor: pointer;
  box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
  border-radius: 20px;
  background-color: white;

  display: flex;
  flex-direction: column;
  img {
    width: 100%;
  }
  h4 {
    text-align: center;
    padding: 20px;
  }
  .red__part {
    background-color: var(--red);
    border-radius: 0 0 20px 20px;
    padding: 20px;
    color: white;
    text-align: center;
  }
}
</style>

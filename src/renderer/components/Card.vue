<template>
  <NuxtLink class="card" tag="div" :to="'/qcm/' + id">
    <h4>{{ title }}</h4>
    <Stepper :step="step" />
    <svg width="400" height="14" viewBox="0 0 400 14" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 0C0 0 0 14 200 14C400 14 400 0 400 0V14H0V0Z" fill="#DB3F3F" />
    </svg>

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
      default: 0,
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
  svg {
    width: 100%;
    transform: translate(0, 2px);
    object-fit: cover;
    path {
      fill: var(--red);
    }
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

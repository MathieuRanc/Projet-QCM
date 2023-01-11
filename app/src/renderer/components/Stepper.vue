<template>
  <div>
    <ul>
      <li v-for="n in maxNumber" :key="n" :class="n <= step ? 'green' : ''">
        <span>{{ n }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      maxNumber: 6,
    };
  },
  props: {
    step: {
      type: Number,
      default: 0,
    },
  },
};
</script>

<style lang="scss" scoped>
div {
  display: flex;
}
ul {
  list-style: none;
  display: flex;
  margin: 0 auto;
  gap: 10px;
  li {
    position: relative;
    span {
      display: block;
      z-index: 10;
      background-color: var(--gray);
      height: 16px;
      width: 16px;
      font-size: 10px;
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    &:not(:first-child)::before {
      content: '';
      width: 10px;
      height: 2px;
      background-color: var(--gray);
      display: block;
      position: absolute;
      left: 0;
      top: 50%;
      transform: translate(-100%, -50%);
    }
    &.green {
      span {
        background-color: var(--green);
      }
      &:not(:first-child)::before {
        background-color: var(--green);
      }
    }
  }
}
</style>

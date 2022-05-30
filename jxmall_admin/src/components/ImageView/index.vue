<template>
  <div style="background-color: #f7f4f4;">
    <el-image :src="src" :style="{ width: width + 'px', height: height + 'px' }" fit="fill"></el-image>
  </div>
</template>

<script>
export default {
  name: 'ImageView',
  props: {
    src: {
      type: String,
      default: '',
    },
    width: {
      type: Number,
      default: 100,
    },
    height: {
      type: Number,
      default: 100,
    }

  },
  data: () => ({}),
  computed: {},
  methods: {},
  watch: {},
}

</script>

<style scoped>
</style>
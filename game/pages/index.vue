<template>
  <div class="container">
    <div class="input-box">
      <input type="text" v-model="name" @keydown.enter="onPlay">
      <button class="btn btn-play" @click="onPlay" :disabled="!canPlay">
        JOIN GAME
      </button>
    </div>
  </div>
</template>

<script>
export default {
  auth: false,
  data() {
    return {
      name: null,
    }
  },
  computed: {
    canPlay() {
      return this.name
    },
  },
  methods: {
    async onPlay() {
      if(this.canPlay){
        try {
          let response = await this.$auth.loginWith('local', { data: {
            name: this.name
          }})
          this.$router.push({
            name: 'screen'
          })
        } catch (err){

        }
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.container {
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #2193b0; /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2193b0, #6dd5ed); /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2193b0, #6dd5ed); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
.input-box {
  display: grid;
  width: 30%;
  * {
    margin-bottom: 10px;
  }
  input[type="text"] {
    border: navajowhite;
    border-radius: 4px;
    outline: none;
    padding: 10px;
    font-size: 20px;
  }
}
@media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
  .input-box {
    width: 80%;
  }
}
</style>

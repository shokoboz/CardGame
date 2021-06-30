<template>
  <div class="screen-page">
    <div class="screen-container">
      <div class="screen-menu">
        <div class="d-flex flex-column align-items-center">
          <span>CLICK :</span>
          <span>{{ displayClick }}</span>
        </div>
        <div class="d-flex flex-column align-items-center">
          <span>My Best :</span>
          <span>{{ UserBestScore }}</span>
        </div>
        <div class="d-flex flex-column align-items-center">
          <span>Global Best</span>
          <span>{{ GlobalScore }}</span>
        </div>
        <div class="game-control">
          <button class="btn btn-newgame w-100" @click="newGame">
            NEW GAME
          </button>
        </div>
      </div>
      <div class="screen-play">
        <div class="card-screen">
          <template v-for="(item, index) in CardData">
            <div
              class="card"
              :class="{ 'card-open': item.card_open }"
              :key="index"
              @click="openCard(item)"
            >
              <div class="card-inner">
                <span v-if="item.card_open">{{ item.value }}</span>
                <span v-else>?</span>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div v-if="GameOver" class="gameover-overlay">
        <div class="gameover-text">
          <span>YOUR SCORE : {{ clickCouter }}</span>
        </div>
        <div style="line-height: 0">
          <button @click="newGame" class="btn-play-again">PLAY AGAIN</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import io from 'socket.io-client'
export default {
  middleware: 'auth',
  name: 'ScreenPlay',
  data() {
    return {
      user: {
        name: null,
        best_score: 0,
      },
      global_score: 0,
      game: {
        cards: [],
        card_opened: [],
        card_select: [],
        click_counter: 0,
        can_open: true,
        game_over: false,
      },
    }
  },
  computed: {
    displayClick() {
      return this.clickCouter
    },
    canOpen: {
      get() {
        return this.game.can_open
      },
      set(state) {
        this.game.can_open = state
      },
    },
    UserBestScore: {
      get() {
        return this.user.best_score
      },
      set(score) {
        this.user.best_score = score
      },
    },
    GlobalScore: {
      get() {
        return this.global_score
      },
      set(score) {
        this.global_score = score
      },
    },
    clickCouter: {
      get() {
        return this.game.click_counter
      },
      set(value) {
        this.game.click_counter = value
      },
    },
    CardData: {
      get() {
        return this.game.cards
      },
      set(data) {
        this.game.cards = data
      },
    },
    CardSelect: {
      get() {
        return this.game.card_select
      },
      set(data) {
        this.game.card_select = data
      },
    },
    GameOver: {
      get() {
        return this.game.game_over
      },
      set(state) {
        this.game.game_over = state
      },
    },
  },
  watch: {
    CardSelect: {
      handler(val, oldVal) {
        if (val.length === 2) {
          this.canOpen = false
          const cardNumber = val.map((o) => o.value)
          if (this.matchCard(cardNumber)) {
            this.canOpen = true
            this.CardSelect.length = 0
            const CountOpenCard = this.CardData.filter((o) => o.card_open)
            if (this.CardData.length === CountOpenCard.length) {
              this.GameOver = true
              this.saveScore()
            }
          } else {
            setTimeout(() => {
              val.forEach((card) => {
                delete card.card_open
              })
              this.canOpen = true
              this.CardSelect.length = 0
              this.$forceUpdate()
            }, 1000)
          }
        }
      },
      deep: true,
    },
  },
  created() {
    this.newGame()
  },
  beforeMount() {
    if (this.$store.state.auth.user) {
      document.title = `${this.$store.state.auth.user.name} - CARD GAME`
      this.user.best_score = this.$store.state.auth.user.best_score || 0
      this.$axios.$get('/ws/init').then(() => {
        this.socket = io()
        this.socket.on('connected-callback', (data) => {})
        this.socket.on('global-score', () => {
          this.loadGlobal()
        })
      })
      this.$nextTick(() => {
        this.loadGlobal()
      })
    } else {
      this.$router.push({
        name: 'index',
      })
    }
  },
  mounted() {},
  methods: {
    newGame() {
      this.clickCouter = 0
      this.CardData = []
      for (let x = 0; x < 2; x++) {
        for (let y = 0; y < 6; y++) {
          this.CardData.push({
            key: `${x + 1}_${y + 1}`,
            value: y + 1,
          })
        }
      }
      this.GameOver = false
      this.CardData = this.shuffleCard(this.CardData)
    },
    async loadGlobal() {
      var results = await this.$axios.$get('/api/index.php?high-score')
      if (results) {
        this.GlobalScore = results.global_score || 0
      } else {
        this.GlobalScore = 0
      }
    },
    async saveScore() {
      var score = this.clickCouter
      var response = await this.$axios.post('/api/index.php?save-game', {
        score: score,
      })
      if (response && response.status) {
        var results = response.data
        if (results && results.status) {
          this.UserBestScore = score
          if (this.socket) {
            this.socket.emit('update-global')
          }
        }
      }
    },
    openCard(item) {
      if (this.canOpen && !item.card_open) {
        item.card_open = true
        this.game.card_select.push(item)
        this.clickCouter++
        this.$forceUpdate()
      }
    },
    clearCard() {},
  },
}
</script>

<style lang="scss" scoped>
.screen-page {
  width: 100vw;
  height: 100vh;
  background: #2193b0; /* fallback for old browsers */
  background: -webkit-linear-gradient(
    to right,
    #2193b0,
    #6dd5ed
  ); /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(
    to right,
    #2193b0,
    #6dd5ed
  ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
.screen-container {
  display: grid;
  grid-template-rows: max-content auto;
  height: 100%;
  .screen-play {
    position: relative;
    border: 1px solid #ddd;
  }
}
.screen-menu {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  padding: 0.5rem;
  background: #fff;
}
.card-screen {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  height: 100%;
}
.card {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 1rem;
  padding: 0.25rem;
  background: #fff;
  border-radius: 10px;
  font-size: 40px;
  box-shadow: 0 0 10px #aaaaaa;
  cursor: pointer;
  .card-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    background: yellow;
    border-radius: 5px;
    overflow: hidden;
    width: 100%;
    height: 100%;
    font-size: 40px;
    pointer-events: none;
  }
  &.card-open {
    .card-inner {
      color: #fff;
      background: black;
    }
  }
}

.gameover-overlay {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: #fff;
  background: rgb(0, 0, 0, 0.7);
  .gameover-text {
    font-size: 6rem;
  }
}
@media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
  .screen-menu {
    grid-template-columns: repeat(3, 1fr) !important;
  }
  .card {
    margin: 0.25rem !important;
  }
  .gameover-overlay {
    .gameover-text {
      font-size: 3rem !important;
    }
  }
  .game-control {
    grid-column: 1 / span 3;
    margin-top: 10px;
  }
}
</style>

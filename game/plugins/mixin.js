import Vue from 'vue'

// Make sure to pick a unique name for the flag
// so it won't conflict with any other mixin.
if (!Vue.__my_mixin__) {
  Vue.__my_mixin__ = true
  Vue.mixin({
    methods: {
      shuffleCard(array) {
        let currentIndex = array.length
        let temporaryValue, randomIndex

        while (currentIndex !== 0) {
          randomIndex = Math.floor(Math.random() * currentIndex)
          currentIndex -= 1
          temporaryValue = array[currentIndex]
          array[currentIndex] = array[randomIndex]
          array[randomIndex] = temporaryValue
        }
        return array
      },
      matchCard(array) {
        return array.every((val, i, arr) => val === arr[0])
      },
    },
  }) // Set up your mixin then
}

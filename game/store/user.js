export const state = () => ({
  name: null,
  tokne: null
})

export const mutations = {
  add(state, data) {
    state.name = data.name
    state.token = data.token
    localStorage.setItem("name", data.name)
    localStorage.setItem("token", data.token)
  },
  setName(state, name) {
    state.name = name
  },
  setToken(state, token){
    state.token = token
  }
}
export const actions = {
  
}


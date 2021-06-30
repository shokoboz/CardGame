export default function ({ $axios, redirect }) {
  $axios.onRequest((config) => {
    // eslint-disable-next-line no-console
    if(process.env.NODE_ENV == 'development'){
      console.log('REQUEST URL TO ' + config.url)
    }
  })

  $axios.onError((error) => {
    const code = parseInt(error.response && error.response.status)
    if (code === 400) {
      redirect('/400')
    }
  })
}

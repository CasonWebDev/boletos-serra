import "./bootstrap"
import Vue from "vue"

import Route from './routes/routes.js'

import App from './views/App'

const app = new Vue({
    el: "#app",
    components: {
        App
    },
    router: Route,
    render: h => h(App)
});

export default app;

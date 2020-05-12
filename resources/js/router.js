import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./views/Home";
import Register from "./views/Register";
import Overview from "./views/Overview";
import VerifyEmail from "./views/VerifyEmail";
import PasswordReset from "./views/PasswordReset";
import auth from "./auth";

auth.init();
Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/register",
            name: "register",
            component: Register
        },
        {
            path: "/verify/email/:uid/:hash",
            name: "verifyEmail",
            component: VerifyEmail
        },
        {
            path: "/password/reset/:uid/:hash",
            name: "passwordReset",
            component: PasswordReset
        },
        {
            path: "/overview",
            name: "overview",
            component: Overview,
            meta: {
                requiresAuth: true
            }
        }
    ]
});

router.beforeEach((to, from, next) => {
    // if logged in redirect to overwiew
    if (to.path === "/" && auth.loggedIn()) {
        next({ name: "overview" });
    } else if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!auth.loggedIn()) {
            next({ name: "home" });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;

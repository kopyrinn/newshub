import { createRouter, createWebHistory, createMemoryHistory } from "vue-router"

const routes = [
    {
        path: "/:locale(en|kk)?",
        name: "locale",
        children: [
            {
                path: "",
                name: "index",
                component: () => import("@/views/Index.vue"),
                meta: {
                    title: 'NewsHub.kz - Информационный хаб',
                    toolbar: false,
                    animate: true,
                },
            },
            {
                path: "feed",
                name: "feed",
                component: () => import("@/views/Posts/Feed.vue"),
                meta: {
                    noSsr: true,
                    title: 'My Feed',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "search",
                name: "search",
                component: () => import("@/views/Posts/Search.vue"),
                meta: {
                    title: 'Search',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "archive",
                name: "archive",
                component: () => import("@/views/Posts/Archive.vue"),
                meta: {
                    title: 'Archive',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "login",
                name: "login",
                component: () => import("@/views/Auth/Auth.vue"),
                meta: {
                    title: 'Login',
                    toolbar: false,
                    animate: true,
                },
            },
            {
                path: "password/reset/:token",
                name: "reset",
                component: () => import("@/views/Auth/Reset.vue"),
                meta: {
                    title: 'Reset',
                    toolbar: false,
                    animate: true,
                },
            },
            {
                path: "map",
                name: "map",
                component: () => import("@/views/Map.vue"),
                meta: {
                    noSsr: true,
                    title: 'Media Map',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "packages/:slug?",
                name: "packages",
                component: () => import("@/views/Packages.vue"),
                meta: {
                    title: 'Packages',
                    toolbar: true,
                    animate: false,
                },
            },
            {
                path: "polls",
                name: "polls",
                component: () => import("@/views/Polls/Polls.vue"),
                meta: {
                    title: 'Polls',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "polls/:slug",
                name: "poll",
                component: () => import("@/views/Polls/Poll.vue"),
                meta: {
                    title: 'Polls',
                    toolbar: false,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'polls',
                },
            },
            {
                path: "users",
                name: "users",
                component: () => import("@/views/Users/Users.vue"),
                meta: {
                    title: 'Press Center',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "users/:slug",
                name: "user-category",
                component: () => import("@/views/Users/Category.vue"),
                meta: {
                    toolbar: true,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'users',
                },
            },
            {
                path: "journalists",
                name: "journalists",
                component: () => import("@/views/Users/Journalists.vue"),
                meta: {
                    title: 'Journalists',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "user/:slug",
                component: () => import("@/views/Users/User.vue"),
                meta: {
                    toolbar: false,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'users',
                },
                children: [
                    {
                        path: "",
                        name: "user",
                        component: () => import("@/views/Users/Account/Overview.vue"),
                        meta: {
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "settings",
                        name: "user-settings",
                        component: () => import("@/views/Users/Account/Settings.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "favorite",
                        name: "user-favorite",
                        component: () => import("@/views/Users/Account/Favorite.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "workspace",
                        name: "user-workspace",
                        component: () => import("@/views/Users/Account/Workspace.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "actions",
                        name: "user-actions",
                        component: () => import("@/views/Users/Account/Actions.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "notifications",
                        name: "user-notifications",
                        component: () => import("@/views/Users/Account/Notifications.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "package",
                        name: "user-package",
                        component: () => import("@/views/Users/Account/Package.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "followers",
                        name: "user-followers",
                        component: () => import("@/views/Users/Account/Followers.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                    {
                        path: "subscriptions",
                        name: "user-subscriptions",
                        component: () => import("@/views/Users/Account/Subscriptions.vue"),
                        meta: {
                            noSsr: true,
                            toolbar: false,
                            animate: false,
                        },
                    },
                ],
            },
            {
                path: "vacancies",
                name: "vacancies",
                component: () => import("@/views/Vacancies/Vacancies.vue"),
                meta: {
                    title: 'Vacancies',
                    toolbar: true,
                    animate: true,
                },
            },
            {
                path: "vacancies/:slug",
                name: "vacancy",
                component: () => import("@/views/Vacancies/Vacancy.vue"),
                meta: {
                    toolbar: false,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'vacancies',
                },
            },
            {
                path: "category/:slug/:rubric?",
                name: "category",
                component: () => import("@/views/Posts/Category.vue"),
                meta: {
                    toolbar: true,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'index',
                },
            },
            {
                path: "post/:slug/:action(resolve|scroll)?",
                name: "post",
                component: () => import("@/views/Posts/Post.vue"),
                meta: {
                    toolbar: false,
                    animate: false,
                    mobileBack: true,
                    mobileBackFallback: 'index',
                },
            },
            {
                path: "verify/:slug",
                name: "verify",
                component: () => import("@/views/Auth/Verify.vue"),
                meta: {
                    toolbar: false,
                    animate: true,
                },
            },
            {
                path: "unsubscribe/:slug",
                name: "unsubscribe",
                component: () => import("@/views/Auth/Unsubscribe.vue"),
                meta: {
                    toolbar: false,
                    animate: true,
                },
            },
            {
                path: "page/:slug",
                name: "page",
                component: () => import("@/views/Page.vue"),
                meta: {
                    toolbar: true,
                    animate: true,
                    mobileBack: true,
                    mobileBackFallback: 'index',
                },
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        component: () => import("@/views/404.vue"),
    },
];

const history = import.meta.env.SSR ? createMemoryHistory() : createWebHistory()

const router = createRouter({
    history,
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (to.params.action == 'scroll') {
            return false
        }

        if (from.name && savedPosition) {
            return savedPosition
        } else {
            return { top: 0 }
        }
    },
})

export default router

import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    //mode: 'history',
    routes: [{
            path: '/',
            name: 'Another-landing-page',
            component: require('../components/AnotherLandingPage').default,
        },
        {
            path: '/weekly',
            name: 'landing-page',
            component: require('../components/WeeklyTimesheet').default,
        },
        {
            path: '/weekly/edit/:week_start_date',
            name: 'edit-weekly-page',
            component: require('../components/EditWeeklyTimesheet').default,
            props: true
        },
        /* ,
        {
            path: '/register',
            name: 'register-page',
            component: require('../components/RegisterPage').default,
        },
        {
            path: '/verify-email/:email/:code?',
            name: 'verify-email-page',
            component: require('../components/VerifyEmailPage').default,
            props: true
        },
        {
            path: '/forgot-password',
            name: 'forgot-password-page',
            component: require('../components/ForgotPasswordPage').default,
        },
        {
            path: '/reset-password/:email/:code?',
            name: 'reset-password-page',
            component: require('../components/resetPasswordPage').default,
            props: true
        },
        {
            path: '/user/dashboard/:userId?',
            name: 'user-dashboard-page',
            component: require('../components/users/dashboardPage').default,
            props: true
        },
        {
            path: '/user/bots/:userId?',
            name: 'bots-page',
            component: require('../components/users/botsPage').default,
            props: true
        },
        {
            path: '/user/view-bot/:botId/:userId?',
            name: 'view-bot-page',
            component: require('../components/users/viewBotPage').default,
            props: true
        },
        {
            path: '/user/create-bot/:userId',
            name: 'create-bot-page',
            component: require('../components/users/createBotPage').default,
            props: true
        },
        {
            path: '/user/edit-bot/:botId',
            name: 'edit-bot-page',
            component: require('../components/users/editBotPage').default,
            props: true
        },

        {
            path: '/user/pairs/:userId?',
            name: 'user-pairs-page',
            component: require('../components/users/pairsPage').default
        },

        {
            path: '/user/stats/:userId?',
            name: 'user-stats-page',
            component: require('../components/users/statsPage').default,
            props: true
        },
        {
            path: '/user/coins-stats/:userId?',
            name: 'user-coins-stats-page',
            component: require('../components/users/CoinsStatsPage').default,
            props: true
        },
        {
            path: '/user/trades-graph/:userId?',
            name: 'user-trades-graph-page',
            component: require('../components/users/tradesGraphPage').default,
            props: true
        },
        {
            path: '/user/recommendation/:userId?',
            name: 'user-recommendation-page',
            component: require('../components/users/recommendationPage').default,
            props: true
        }, */

        /* {
            path: '*',
            redirect: '/'
        } */
    ]
})
<template>
  <div>
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main dashboard">
      <div class="pagetitle">
        <h1><i class="bi bi-grid"></i>&nbsp;Dashboard</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link class="nav-link" :to="'/user/dashboard' + (userId ? '/' + userId : '')">Home</router-link>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <div class="row">
        <!-- Bots Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <span>
                <h5 class="card-title">Users <span>| Total</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ dashboardStats.totalUsers }}</h6>
                    <span class="text-success small pt-1 fw-bold"></span>
                    <span class="text-muted small pt-2 ps-1">active</span>

                  </div>
                </div>
              </span>
            </div>

          </div>
        </div><!-- End Bots Card -->

        <!-- Profit:Total Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">



            <div class="card-body">
              <h5 class="card-title">Employees <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people-fill"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ dashboardStats.totalEmployees }}</h6>
                    <span class="text-success small pt-1 fw-bold"></span>
                    <span class="text-muted small pt-2 ps-1">active</span>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Profit:Total Card -->

        <!-- Profit:This Month Card -->
        <div class="col-xxl-4 col-xl-12">

          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Weekly Timesheets <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-table"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ dashboardStats.totalWeeklyTimesheets }}</h6>
                    <span class="text-success small pt-1 fw-bold"></span>
                    <span class="text-muted small pt-2 ps-1"></span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End: Profit:This Month Card -->
        <!-- Investment Card -->
        <div class="col-xxl-4 col-xl-12 d-none">

          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Investment <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-bank"></i>
                </div>
                <div class="ps-3">
                  <h6>${{ (dashboardStats.TotalInvested >= 0 ? +dashboardStats.TotalInvested : 0).toFixed(0) }}
                    <span style="font-size:12px;">
                      <span class="text-success pt-1 fw-bold">${{ (dashboardStats.TotalUsedUsd >= 0 ?
                        +dashboardStats.TotalUsedUsd : 0).toFixed(0) }}</span> Used</span>
                  </h6>

                  <span class="text-danger small pt-1 fw-bold">
                    ${{ (dashboardStats.TotalInvested >= 0 && dashboardStats.TotalUsedUsd >= 0
                      ? +dashboardStats.TotalInvested - +dashboardStats.TotalUsedUsd : 0).toFixed(0) }}
                  </span>
                  <span class="text-muted small pt-2 ps-1">to be used</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End Investment Card -->
        <!-- Trades Card -->
        <div class="col-xxl-4 col-xl-12 d-none">

          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Trades <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ tradeStats.TotalTrades }}</h6>
                  <span class="text-muted small pt-2 ps-1">Next&nbsp;</span>
                  <span class="text-danger small pt-1 fw-bold">{{ tradeStats.PendingBuyTrades }}</span>
                  <span class="text-muted small pt-2 ps-1">Buys</span>
                  <span class="text-success small pt-1 fw-bold">{{ tradeStats.PendingSellTrades }}</span>
                  <span class="text-muted small pt-2 ps-1">Sells at profit</span>
                  <span class="text-success small pt-1 fw-bold">${{ tradeStats.PendingProfit ?
                    tradeStats.PendingProfit.toFixed(0) : ''
                  }}</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End Trades Card -->
        <!-- Fees Card -->
        <div class="col-xxl-4 col-xl-12 d-none">

          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Fees <span>| Paid</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cash-coin"></i>
                </div>
                <div class="ps-3">
                  <h6>${{ (+totalPaid).toFixed(0) }}</h6>
                  <span class="text-success small pt-1 fw-bold">${{ (onProfit).toFixed(0) }}</span>
                  <span class="text-muted small pt-2 ps-1">on Profit</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End Fees Card -->

      </div>
      <div class="">
        <div class="card">
          <!-- <div class="card-header">

          </div> -->
          <!-- <div class="card-header">
            <h3><i class="bi bi-grid"></i>&nbsp;Dasbhoard</h3>
          </div> -->
          <div class="card-header d-none">
            <div style="text-align: center; font-size: 8px;">
              Tip: Do not use public internet for your private things.
            </div>
          </div>
        </div>
      </div>
    </main>

    <!--/.Navbar -->
    <div v-if="loading" class="processing" style="">
      <span style="">Processing...</span>
    </div>
    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
import CommonHeader from '../CommonHeader.vue'
import CommonSidebar from '../CommonSidebar.vue'

export default {
  name: 'dashboard-page',
  components: {
    CommonHeader: CommonHeader,
    CommonSidebar: CommonSidebar,
  },
  props: ['userId'],
  data() {
    return {
      bots: [],
      balances: [],
      pairs: [],
      openOrders: [],

      dashboardStats: {},
      tradeStats: {},
      lastPaid: {},
      totalPaid: 0,
      onProfit: 0,
      graphDays: 0,

      isExchangeAccountSettings: false,
      exchangeAccountSettings: {
        exchangeName: 'kraken',
        apiKey: '',
        apiSecret: '',
      },

      showQuickBot: false,
      showAddProfitUsage: false,

      exchangePairs: {
        "binance": [],
      },
      exchangeName: 'binance',
      selectedPair: '',
      selectedPrice: 0,
      newApiKey: true,
      isTesting: false,

      loading: false,
    }
  },
  watch: {
  },
  computed: {
  },
  methods: {
    getDashboardStats() {
      let that = this
      that.loading = true
      that.$local
      .getRequest('/dashboard')
        .then(function(response) {
          that.dashboardStats = response.data
          that.loading = false
        })
        .catch(function(error) {
          that.loading = false
          console.log(error)
        })
    },
  }, //
  created() {
    let that = this
    that.getDashboardStats()
  },
}
</script>

<style scoped>
.box-shadow {
  box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
  padding: 20px;
  margin-bottom: 20px;
}

.box-shadow:hover {
  box-shadow: 0px 10px 20px 2px rgba(0, 0, 0, 0.25);

}

/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .3s ease;
}

.slide-fade-leave-active {
  transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter,
.slide-fade-leave-to

/* .slide-fade-leave-active below version 2.1.8 */
  {
  transform: translateX(10px);
  opacity: 0;
}
</style>

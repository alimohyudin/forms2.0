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
        <transition name="slide-fade" mode="in-out">
          <div class="row" v-if="showQuickBot">

            <div class="col-sm-3"></div>
            <div class="col-sm-6 box-shadow">
              <form ref="form" id="createBot_form" @submit.prevent="createBot">
                <h1 style="text-align: center; font-weight: 800">Your First Bot</h1>
                <input type="text" class="form-control" name="userId" :value="userId" style="display: none" />
                <div class="form-group">
                  <!-- <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                  <label for="totalUsdAmount" v-b-popover.hover="'Investment is the money in USDT that you want to start with e.g. Start my bot with 1000$ investment so you write 1000 here.'
                    " title="What is Total Amount?">Investment USDT (&#8505;):</label>
                  <input type="number" class="form-control" id="totalUsdAmount" name="totalUsdAmount"
                    aria-describedby="totalUsdAmountHelp" required="required" min="100" />
                </div>
                <div class="form-group">
                  <label for="marketPair" v-b-popover.hover="'MATIC is the crypto currency that is proven to be highly profitable. '
                    " title="Cryptocurrency">Cryptocurrency (&#8505;):</label>
                  <select class="form-control" id="marketPair" name="marketPair" v-model="selectedPair"
                    aria-describedby="marketPairHelp">
                    <option v-for="pair in exchangePairs[exchangeName == 'binance-us' ? 'binance' : exchangeName]"
                      v-bind:key="pair" :value="pair">
                      {{ pair.replace('USDT', '') }}
                    </option>
                  </select>
                </div>
                <template v-if="newApiKey">
                  <div class="row" v-if="howToCreateApi.show">
                    <div class="col-xs-12 box-shadow">
                      <h3 style="text-align: center; font-weight: 800">How to create API</h3>
                      <span v-html="howToCreateApi.detail"></span><br />
                      <button class="btn btn-secondary" @click="howToCreateApi.show = !howToCreateApi.show">Close</button>
                    </div>
                  </div>
                  <div class="form-group" v-if="!isTesting">
                    <label for="apiKey">API Key:
                      <span style="color: blue; font-size: 12px;">
                        <a @click="howToCreateApi.show = !howToCreateApi.show">
                          <span>how to create API?</span>
                        </a>
                      </span>
                    </label>
                    <input type="text" class="form-control" id="apiKey" name="apiKey" aria-describedby="apiKeyHelp" />
                  </div>
                  <div class="form-group" v-if="!isTesting">
                    <label for="apiSecrete" v-b-popover.hover="'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      " title="What is API Secret?">API Secret (&#8505;):</label>
                    <input type="text" class="form-control" id="apiSecret" name="apiSecrete"
                      aria-describedby="apiSecreteHelp" />
                  </div>
                </template>
                <div class="row" style="padding: 20px;" >
                  <div class="col-sm-6 form-check" v-if="!newApiKey && !isTesting">
                    <input type="checkbox" class="form-check-input" id="newApiKey" v-model="newApiKey">
                    <label class="form-check-label" for="newApiKey">New API Key</label>
                  </div>
                  <div class="col-sm-6 form-check" v-if="!isTesting">
                    <input type="checkbox" class="form-check-input" id="twobotStrategy" name="twobotStrategy" checked>
                    <label class="form-check-label" for="twobotStrategy"
                      v-b-popover.hover="'It creates 2 Bots (Normal & Lower), Lower Bot increases lower range of normal bot to generate profit in worst times when crypto falls a lot.'">Two-Bot
                      Strategy (&#8505;)</label>
                  </div>
                  <div class="col-sm-6 form-check">
                    <input type="text" :value="isTesting? '1' : '0'" style="display: none" name="isTestingLive">
                    <input type="checkbox" class="form-check-input" id="isTestingInput" v-model="isTesting">
                    <label class="form-check-label" for="isTestingInput"
                      v-b-popover.hover="'Live Simulation is used for testing bot using Live prices but simulating trades without binance api.'">Live Simulation (&#8505;)</label>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary">Start Bot</button>
                <button type="button" class="btn btn-secondary" v-on:click="showQuickBot = false">Cancel</button>

              </form>
            </div>
            <div class="col-sm-3"></div>
          </div>
        </transition>
        <transition name="slide-fade" mode="in-out">
          <div class="row" v-if="showAddProfitUsage">

            <div class="col-sm-3"></div>
            <div class="col-sm-6 box-shadow">
              <form ref="form" id="addProfitUsage_form" @submit.prevent="addProfitUsage">
                <h1 style="text-align: center; font-weight: 800">Add Profit Usage</h1>
                <input type="text" class="form-control" name="userId" :value="userId" style="display: none" />
                <div class="form-group">
                  <label for="title" v-b-popover.hover="'What is the reason you used the money?'
                    " title="What is the title?">Title (&#8505;):</label>
                  <input type="text" class="form-control" id="title" name="title" aria-describedby="titleHelp"
                    required="required" />
                </div>
                <div class="form-group">
                  <label for="amount" v-b-popover.hover="'How much USDT you used out of profit?'
                    " title="What is Used USDT?">Used USDT (&#8505;):</label>
                  <input type="number" class="form-control" id="amount" name="amount" aria-describedby="amountHelp"
                    required="required" />
                </div>
                <br />


                <button type="submit" class="btn btn-primary">Add Usage</button>
                <button type="button" class="btn btn-secondary" v-on:click="showAddProfitUsage = false">Cancel</button>

              </form>
            </div>
            <div class="col-sm-3"></div>
          </div>
        </transition>
      </div>

      <div class="row">
        <!-- Bots Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <router-link :to="'/user/bots' + (userId ? '/' + userId : '')" class="nav-link">
                <h5 class="card-title">Bots <span>| Total</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-robot"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ botStats.TotalActiveBots }}</h6>
                    <span class="text-success small pt-1 fw-bold">{{ botStats.TotalStartedBots }}</span>
                    <span class="text-muted small pt-2 ps-1">active</span>

                  </div>
                </div>
              </router-link>
            </div>

          </div>
        </div><!-- End Bots Card -->

        <!-- Profit:Total Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">



            <div class="card-body">
              <h5 class="card-title">Profit <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="ps-3">
                  <h6>${{ (botStats.TotalProfit >= 0 ? +botStats.TotalProfit : 0).toFixed(0) }}</h6>
                  <span style="cursor: pointer" @click="showAddProfitUsage = !showAddProfitUsage">
                    <span class="text-success small pt-1 fw-bold">
                      ${{ (botStats.TotalUsedProfit >= 0 ? +botStats.TotalUsedProfit + totalPaid : totalPaid).toFixed(0) }}
                      <!-- add plus icon green -->
                      <i class="bi bi-plus-circle-fill" style="color: green; font-size: small;"></i>
                    </span>
                    <span class="text-muted small pt-2 ps-1">Profit Used</span>
                  </span>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Profit:Total Card -->

        <!-- Profit:This Month Card -->
        <div class="col-xxl-4 col-xl-12">

          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Profit <span>| This Month</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-coin"></i>
                </div>
                <div class="ps-3">
                  <h6>${{ (botStats.profitThisMonth >= 0 ? +botStats.profitThisMonth : 0).toFixed(0) }}
                    <span style="font-size:12px;">
                      <span class="text-success pt-1 fw-bold">${{ (botStats.profitToday >= 0 ?
                        +botStats.profitToday : 0).toFixed(0) }}</span> Today</span>
                  </h6>
                  <span class="text-danger small pt-1 fw-bold">${{ (botStats.profitPreviousMonth >= 0 ?
                    +botStats.profitPreviousMonth : 0).toFixed(0) }}</span> <span
                    class="text-muted small pt-2 ps-1">previous month</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End: Profit:This Month Card -->
        <!-- Investment Card -->
        <div class="col-xxl-4 col-xl-12">

          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Investment <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-bank"></i>
                </div>
                <div class="ps-3">
                  <h6>${{ (botStats.TotalInvested >= 0 ? +botStats.TotalInvested : 0).toFixed(0) }}
                    <span style="font-size:12px;">
                      <span class="text-success pt-1 fw-bold">${{ (botStats.TotalUsedUsd >= 0 ?
                        +botStats.TotalUsedUsd : 0).toFixed(0) }}</span> Used</span>
                  </h6>

                  <span class="text-danger small pt-1 fw-bold">
                    ${{ (botStats.TotalInvested >= 0 && botStats.TotalUsedUsd >= 0
                      ? +botStats.TotalInvested - +botStats.TotalUsedUsd : 0).toFixed(0) }}
                  </span>
                  <span class="text-muted small pt-2 ps-1">to be used</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End Investment Card -->
        <!-- Trades Card -->
        <div class="col-xxl-4 col-xl-12">

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
        <div class="col-xxl-4 col-xl-12">

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

      botStats: {},
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


      series: [
      ],
      chartOptions: {
        chart: {
          height: 350,
          type: 'bar',
        },
        dataLabels: {
          enabled: false,
        },
        series: [],
        title: {
          text: '30 Days Profit',
        },
        noData: {
          text: 'No data available.',
        },
      },
      howToCreateApi: {
        title: "Create API Key & Secret on Binance",
        detail: "1- Click <b>API Management</b> or visit this link: \
        <a href=\"https://www.binance.com/en/my/settings/api-management\" target='_blank'>Binance API</a><br/>\
        2- Click <b>Create Api</b> button<br/>\
        3- Choose <b>System Generated</b> and click <b>Next</b><br/>\
        4- Enter name <b>\"MCQuare API\"</b> and click <b>Next</b><br/>\
        5- Copy <b>API Key</b> and <b>Secret</b> and paste in the fields below.<br/>\
        6- Select <b>Restrict access to trusted IPs only</b> and enter <b>31.42.188.108</b><br/>\
        7- Select <b>Enable Spot & Margin Trading</b> and click <b>Save</b><br/>\
        ",
        image: "https://mcquare.com/assets/img/getstarted-binance-create-api.webp",
        link: "https://www.binance.com/en/my/settings/api-management",
        show: false
      },
      loading: false,
    }
  },
  watch: {
  },
  computed: {
  },
  methods: {
  }, //
  created() {
    let that = this

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

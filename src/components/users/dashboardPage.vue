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
          <div class="card-body">
            <!-- Get Exchange Account settings info from user -->
            <form v-if="false && !isExchangeAccountSettings" ref="form" id="updateExchangeAccountSettings_form"
              v-on:submit.prevent="updateExchangeAccountSettings">
              <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                  <p>
                    Please enter details below to get your Exchange's Account
                    Balances and stats of bots.
                  </p>
                  <div class="form-group">
                    <label for="exchangeName"></label>
                    <select class="form-control" id="exchangeName" name="exchangeName"
                      :value="exchangeAccountSettings.exchangeName" aria-describedby="exchangeNameHelp">
                      <option value="kraken">Kraken</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="apiKey" v-b-popover.hover="'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      " title="What is API Key?">API Key (?):</label>
                    <input type="text" class="form-control" id="apiKey" name="apiKey"
                      :value="exchangeAccountSettings.apiKey" aria-describedby="apiKeyHelp" />
                  </div>
                  <div class="form-group">
                    <label for="apiSecret" v-b-popover.hover="'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      " title="What is API Secrete?">API Secrete (?):</label>
                    <input type="text" class="form-control" id="apiSecret" name="apiSecret"
                      :value="exchangeAccountSettings.apiSecret" aria-describedby="apiSecretHelp" />
                  </div>
                  <div class="form-group" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">
                      Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="isExchangeAccountSettings = true">
                      Cancel
                    </button>
                  </div>
                </div>
                <div class="col-sm-3"></div>
              </div>
            </form>
            <!-- End -->

            <!-- Show Dashboard -->
            <div>
              <div style="padding: 20px; ">
                <div class="row" style="padding: 0px 15px; margin: 5px 0px; text-align: center">
                  <div class="col-md-3 col-sm-3"
                    style="border: 2px solid; border-radius: 5px; border-color: red; margin: 0px 5px 5px 0px; "
                    v-if="lastPaid.status == 'requested'">
                    <div style="border-bottom: 10px solid; border-color: red;">
                      <h1>Pay Fee</h1>
                    </div>
                    <div style="margin: 25px;">
                      <span><b>Fee: </b>
                        <h3>${{ (+lastPaid.total).toFixed(0) }}</h3>
                      </span><br />
                      <span><b>Due Date: </b>{{ new Date(lastPaid.lastDate).toDateString() }}</span><br />
                      <span><b>On Profit: </b>${{
                        (+lastPaid.atProfit).toFixed(0)
                      }}</span><br />
                      <span><b>Percentage: </b>{{ lastPaid.percentage }}% of
                        Profit</span><br />



                      <!-- <span
                        ><button class="btn btn-danger">Pay Now: <b>{{ ((botStats.TotalProfit*20/100).toFixed(0)) }}$</b></button></span
                      ><br /><br/> -->
                    </div>
                    <!-- display image -->
                    <div v-if="lastPaid.qrImageUrl">
                      <img :src="lastPaid.qrImageUrl" alt="Pay with Binance QR Code"
                        style="width: 200px; height: 100%;" />
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="openOrders.length > 0">
                <div class="card-header">
                  <b>Open Trades on Account</b>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead style="font-weight: 700">
                      <td>#</td>
                      <td>Order</td>
                      <td>Time</td>
                      <td>Cost</td>
                      <td>Op</td>
                    </thead>
                    <tbody>
                      <tr v-for="(order, index) in openOrders" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ order.order }}</td>
                        <td>
                          {{ new Date(order.time).toLocaleString('en-IN') }}
                        </td>
                        <td>{{ (order.atPrice * order.vol).toFixed(1) }}$</td>
                        <td>
                          <!-- <button class="btn btn-danger" v-on:click="deleteOrder(order.orderExchangeId)">
                            <span
                              
                              >Delete</span
                            >
                          </button> -->
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div>
              <div id="chart">
                <apexchart ref="apexchartLine" type="line" height="350" :options="chartOptions" :series="series">
                </apexchart>
              </div>
            </div>
            <!-- End -->
          </div>
          <div class="card-header">
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
      loading: true,
    }
  },
  watch: {
    isExchangeAccountSettings(changed) {
      if (changed == true) {
        //console.log('Key updated: true')
      }
    },
  },
  computed: {
    TotalProfit: function () {
      if (this.bots.length > 0) {
        let total = 0
        for (let index = 0; index < this.bots.length; index++) {
          const bot = this.bots[index]
          total += bot.totalProfit
        }
        return total
      }
    },
  },
  methods: {
    updateExchangeAccountSettings() {
      let that = this

      let formData = $('#updateExchangeAccountSettings_form').serialize()

      this.$local
        .postRequest('/auth/users/update-account-settings', formData)
        .then(function (data) {
          //console.log(data)
          global.vm.$local.userData.exchangeAccountSettings = {
            exchangeName: that.exchangeAccountSettings.exchangeName,
            apiKey: that.exchangeAccountSettings.apiKey,
            apiSecret: that.exchangeAccountSettings.apiSecret,
          }
          that.isExchangeAccountSettings = true
          localStorage.setItem(
            'isExchange',
            data.data.isExchangeAccountSettings
          )
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error(msg)
          return
        })
    },
    getBalances() {
      let that = this

      this.$local
        .postRequest('/auth/exchange-account/get-balances', {})
        .then(function (data) {
          ////console.log(data)
          that.balances = data.result
          that.maticPrice = data.maticPrice
          that.algoPrice = data.algoPrice
          that.enjPrice = data.enjPrice
          //console.log(that.balances)
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error('Allow Balance access on API')
          return
        })
    },
    getPairs() {
      let that = this
      this.loading = true;
      let formData = new FormData()
      formData.append('exchange', 'binance')
      this.$local
        .postRequest('/auth/pairs/get-all', formData)
        .then(function (data) {
          ////console.log(data);
          that.allPairs = data.pairs;
          data.pairs.forEach(function (val, index) {
            that.exchangePairs['binance'].push(val.pair);
          })
          that.selectedPair = 'MATICUSDT'
          that.loading = false;
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error("Something went wrong. Error: " + msg);
          return
        })
    },
    pairPrice(pair) {
      for (let index = 0; index < this.pairs.length; index++) {
        const element = this.pairs[index]
        if (element.pair == pair) {
          return element.price
        }
      }
    },
    getOpenOrders() {
      let that = this

      this.$local
        .postRequest('/auth/exchange-account/get-open-orders', {})
        .then(function (data) {
          //console.log('Open Orders: ')
          let openOrders = data.result.open
          //console.log(openOrders)
          that.openOrders = []

          for (let index = 0; index < Object.keys(openOrders).length; index++) {
            let key = Object.keys(openOrders)[index]
            let singleOrder = openOrders[key]
            /* //console.log(singleOrder)
            //console.log('Time: ' + new Date(singleOrder.opentm * 1000))
            //console.log(singleOrder.descr.order)
            //console.log(singleOrder.vol)
            //console.log(singleOrder.descr.price) */

            that.openOrders.push({
              orderExchangeId: key,
              order: singleOrder.descr.order,
              time: new Date(singleOrder.opentm * 1000),
              vol: 1 * singleOrder.vol,
              atPrice: 1 * singleOrder.descr.price,
            })
          }
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error(msg)
          return
        })
    },
    getStats() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)

      this.$local
        .postRequest('/auth/users/account-stats', formData)
        .then(function (data) {
          //console.log('Account STats: ')
          //console.log(data)
          that.botStats = data
          if (data.TotalActiveBots == 0) {
            that.getPairs()
            that.showQuickBot = true
          }
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    getTradeStats() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)

      this.$local
        .postRequest('/auth/users/trade-stats', formData)
        .then(function (data) {
          //console.log('Trade STats: ')
          //console.log(data)
          that.tradeStats = data
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    getLastBill() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)

      this.$local
        .postRequest('/auth/billing/get-last-request', formData)
        .then(function (data) {
          //console.log('Last Bill request: ')
          //console.log(data)
          if (data[0]) that.lastPaid = data[0]

          //that.botStats = data
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    getTotalPaid() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)

      this.$local
        .postRequest('/auth/billing/get-all', formData)
        .then(function (data) {
          //console.log('All Paid Bills: ')
          //console.log(data)
          /* if (data[0].total) {
            that.totalPaid = data[0].total
          } */

          that.totalPaid = 0;
          for (let index = 0; index < data.bills.length; index++) {
            const element = data.bills[index]
            if (element.status == 'paid') {
              that.totalPaid += element.total
              that.onProfit += element.atProfit
            }
          }

          //that.botStats = data
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    fillMissingDates(data) {
      // Extract all existing dates
      const existingDates = data.map(item => item.x);
      ////console.log("existingDates", existingDates)

      // Find the minimum and maximum dates
      const minDate = new Date(Math.min(...existingDates.map(date => new Date(date))));
      const maxDate = new Date(Math.max(...existingDates.map(date => new Date(date))));

      // Generate an array of all dates between min and max
      const allDates = [];
      let currentDate = new Date(minDate);

      while (currentDate <= maxDate) {
        allDates.push(new Date(currentDate));
        currentDate.setDate(currentDate.getDate() + 1);
      }

      // Create a map for quick lookup of existing data
      const existingDataMap = new Map(data.map(item => [item.x, item]));
      ////console.log("existingDataMap", existingDataMap)
      // Fill in missing dates with zero values
      const result = allDates.map(date => {
        ////console.log(date.toISOString())
        return ({
          y: existingDataMap.has(date.toISOString().split('T')[0]) ? existingDataMap.get(date.toISOString().split('T')[0]).y : 0,
          x: date.toLocaleDateString()//date.toISOString().split('T')[0], // Extract the date part only
        })
      });

      return result;
    },
    getProfitByDates() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)

      this.$local
        .postRequest('/auth/trades/get-profit-by-dates', formData)
        .then(function (data) {
          //console.log("graph", data)
          that.graphDays = data?.v_extras?.days;
          that.$refs.apexchartLine.updateOptions({
            title: {
              text: data?.v_extras?.days + ' Days Profit',
            },
          });
          let newData = data.map(function (item) {
            return {
              y: Math.floor(item.profit),
              x: item._id,
            }
          })

          let completeData = that.fillMissingDates(newData)
          //console.log("completeData", completeData)

          that.series = [
            {
              name: 'Profit',
              data: completeData
            },
          ]
          that.loading = false;
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          that.loading = false;
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    createBot() {
      let formData = $('#createBot_form').serialize()
      let that = this
      this.loading = true;

      this.$local
        .postRequest('/auth/bots/create-quick', formData)
        .then(function (data) {
          //that.$router.push({name: 'ManageTimesheets'})
          that.$toaster.success('Bot Created!')
          //that.getBots();
          that.$router.push({ name: 'bots-page' })
          that.loading = false;
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error(msg)
          that.loading = false;
          return
        })
    },
    addProfitUsage() {
      let formData = $('#addProfitUsage_form').serialize()
      let that = this
      this.loading = true;

      this.$local
        .postRequest('/auth/profit-usage/create', formData)
        .then(function (data) {
          //that.$router.push({name: 'ManageTimesheets'})
          console.log(data)
          that.totalPaid += Array.isArray(data) ?  data[0].amount : data.amount;
          that.$toaster.success('Profit Usage Added!')
          that.showAddProfitUsage = false;
          //that.getBots();
          //that.$router.push({ name: 'bots-page' })
          that.loading = false;
          return
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error(msg)
          that.loading = false;
          return
        })
    },
  }, //
  created() {
    let that = this

    that.getStats()
    that.getTradeStats()
    that.getTotalPaid()
    that.getLastBill()
    that.getProfitByDates()//this will turn off loading

    // //console.log('something on socket')
    // //console.log(global.vm.$socket)
    // //console.log('Something from user settings on vue')
    // //console.log(global.vm.$local.token)
    // //console.log(global.vm.$local.isExchange)
    // this.isExchangeAccountSettings =
    //   global.vm.$local.isExchange == 'true' ? true : false
    // ////console.log(this.isExchangeAccountSettings)

    // //this.getOpenOrders()
    // if (this.isExchangeAccountSettings) {
    //   setTimeout(function() {
    //     //that.getBalances()
    //     //that.getPairs()

    //     that.getStats()
    //     that.getLastBill()
    //   }, 500);
    //   setTimeout(function() {
    //     //that.getOpenOrders()
    //   }, 3000);
    // }
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

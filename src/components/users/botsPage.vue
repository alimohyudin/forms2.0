<template>
  <div>
    <!-- <CommonHeader></CommonHeader> -->
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main">
      <div class="container">
        <div class="card">
          <div class="card-header">

          </div>
          <div class="card-header">
            <h3> <i class="bi bi-robot"></i> Crypto Bots</h3>
          </div>
          <div class="card-body">
            <!-- <CommonSubNav :userId="userId"></CommonSubNav> -->
          </div>
          <!-- <div class="card-header">>Bots Page</div> -->
          <div class="card-body">
            <div style="margin-bottom: 5px">
              <div class="row">



                <div class="col-sm-6">
                  <button class="btn btn-secondary" @click="showQuickBot = !showQuickBot">
                    Quick Start
                  </button>

                  <router-link :to="'/user/create-bot/' + computedUserId" v-if="true || accountType == 'admin'">
                    <button class="btn btn-primary">
                      Create Bot
                    </button>
                  </router-link>
                </div>

                <div class="col-sm-6" style="text-align: right;">

                  <!-- Switch Grid View and Table view -->

                  <button class="btn btn-secondary" v-on:click="viewType = viewType == 'table' ? 'grid' : 'table'"
                    style="margin-bottom: 10px;" data-toggle="tooltip" data-placement="top"
                    title="Switch Grid View and Table View">
                    <span v-if="viewType == 'table'">
                      <i class="bi bi-grid"></i>
                    </span>
                    <span v-else>
                      <i class="bi bi-list"></i>
                    </span>
                  </button>

                  <a class="nav-link " href="#" data-bs-toggle="dropdown">
                    <span class="d-md-block dropdown-toggle ps-2">
                      <i class="bi bi-funnel-fill"></i> {{ currentFilter }}
                    </span>
                  </a><!-- End Profile Iamge Icon -->

                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li v-for="(filter, index) in filters" :key="index">
                      <a class="dropdown-item" :id="'filter_' + index" v-on:click="setFilter(index)">{{ filter }}</a>
                    </li>
                  </ul><!-- End Profile Dropdown Items -->
                  <!-- <button class="btn btn-secondary" v-on:click="getBots()">
                    Refresh
                  </button> -->
                </div>

                <transition name="slide-fade" mode="in-out">
                  <div class="row" v-if="showQuickBot">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 box-shadow">
                      <form ref="form" id="createBot_form" @submit.prevent="createBot">
                        <h1 style="text-align: center; font-weight: 800">Create Bot</h1>
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
              </div>
            </div>
            <br />
            <div>
              <div v-if="viewType == 'table'" class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <td></td>
                    <td>Name</td>
                    <td>Invested</td>
                    <td>Current</td>
                    <td>Start</td>
                    <td>Range</td>
                    <td>Pair</td>
                    <td>Profit</td>
                    <td>Recently</td>
                    <td>Ago</td>
                    <td>Op</td>
                  </thead>
                  <tbody>
                    <tr v-for="(bot, index) in filteredBots" :key="bot._id" :style="bot.outOfOrder
                      ? 'background-color: rgb(255 234 230);'
                      : ''
                      ">
                      <td>{{ index + 1 }}</td>
                      <td>
                        <router-link :to="'/user/view-bot/' + bot._id" class="viewBotLink">{{ bot.name }}</router-link>
                        <span v-if="bot.lowerBotData">[two-bot]</span>
                        <span style="font-weight: bold;">{{ bot.outOfOrder ? '[Out of Range]' : '' }}
                          {{ bot.startTrading ? '' : '[Stopped]' }}
                        </span>
                      </td>
                      <td>
                        {{ bot.totalUsdAmount + (bot.lowerBotData ? bot.lowerBotData.totalUsdAmount / 2 : 0) }}
                      </td>
                      <td>
                        {{ +bot.cryptoCurrentPrice.toFixed(4) }}
                      </td>
                      <td>
                        {{ +bot.cryptoInitialPrice.toFixed(4) }}
                      </td>
                      <td>
                        <span v-if="bot.botType == 'upper'">{{
                          bot.cryptoInitialPrice
                        }}</span>
                        <span v-else>
                          {{ bot.lowerBotData ? bot.lowerBotData.range[0] : bot.range[0] }}
                        </span>
                        -
                        {{ bot.range[1] }}
                      </td>
                      <td>{{ bot.marketPair }}</td>
                      <td>{{ (bot.totalProfit + (bot.lowerBotData ? bot.lowerBotData.totalProfit : 0)).toFixed(1) }}$</td>
                      <td>
                        <span v-if="bot.lastTrade">
                          <span style="font-weight: bolder;">
                            <span v-if="bot.lastTrade.status == 'completed'" style="color: green;">
                              &#10003;
                            </span>
                            <span v-else style="color: red;">&#9747;</span>
                          </span>
                          <span v-if="bot.lastTrade.orderType">
                            {{ bot.lastTrade.orderType }}
                            at
                          </span>
                          <span>{{ bot.lastTrade.atPrice }}</span>
                        </span>
                      </td>
                      <td>
                        {{
                          (
                            (+new Date() - +new Date(bot.createdAt)) /
                            1000 /
                            60 /
                            60 /
                            24
                          ).toFixed(0)
                        }}
                        days
                      </td>
                      <td>
                        <router-link :to="'/user/view-bot/' + bot._id" class="viewBotLink">View</router-link><!-- &nbsp;|&nbsp;
                        <router-link :to="'/user/edit-bot/' + bot._id"
                          >Edit</router-link
                        > -->
                        <span>&nbsp;|&nbsp;
                          <a v-if="true" href="javascript:;" v-on:click="deleteBot(bot._id, index)"
                            class="deleteBotLink">Delete</a></span>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td colspan="" style="text-align: center; font-size: 24px;">
                        <b>Total: </b>
                      </td>
                      <td>
                        <b v-if="TotalInvestment > 0">{{ TotalInvestment.toFixed(0) }}</b>
                      </td>
                      <td colspan="4"></td>
                      <td colspan="5" style="text-align: left; font-size: 24px;">
                        <b v-if="TotalProfit > 0">{{ TotalProfit.toFixed(2) }}$</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else>
                <!--  -->
                <!-- GridView for Bots -->
                <!--  -->
                <div class="container">
                  <div class="row">

                    <div class="col-xl-3 mb-3 mb-lg-3" v-for="(bot, index) in filteredBots" :key="bot._id"
                      v-if="bot.totalUsdAmount > 49">
                      <div class="card" style="border: 1px solid #dee2e6;">
                        <div class="card-body">
                          <div style="position: relative">
                            <span style="position: absolute; left: 0px; margin-top: 5px; font-size: 22px;">
                              <router-link :to="'/user/view-bot/' + bot._id" class="viewBotLink"><i
                                  class="bi bi-eye"></i></router-link>
                            </span>
                            <span style="position: absolute; right: 0px; margin-top: 5px; font-size: 22px;">
                              <a href="javascript:;" v-on:click="deleteBot(bot._id, index)"><i
                                  class="bi bi-trash"></i></a>
                            </span>
                          </div>
                          <div class="d-flex flex-column text-center align-items-center">

                            <div class="flex-grow-1 pt-4">
                              <span class="d-block text-muted" style="font-size: x-small;">
                                {{ ((+new Date() - +new Date(bot.createdAt)) / 1000 / 60 / 60 / 24).toFixed(0) }}
                                days old
                              </span>
                              <h5>{{ bot.name }}</h5>
                              <div class="mb-4">
                                <span v-if="bot.outOfOrder" class="badge rounded-pill bg-secondary">
                                  Out of Range
                                </span>
                                <span v-if="!bot.startTrading || (bot.lowerBotData  && !bot.lowerBotData.startTrading)" class="badge rounded-pill bg-danger">
                                  Stopped
                                </span>
                                <span v-else class="badge rounded-pill bg-success">
                                  Running
                                </span>

                                <span v-if="bot.lowerBotData" class="badge rounded-pill bg-warning">
                                  Two-bot
                                </span>

                              </div>

                            </div>
                          </div>

                          <div class="bot-img">
                            <img class="img"
                              :src="'https://s2.coinmarketcap.com/static/img/coins/64x64/' + coinImages[bot.marketPair.replace('USDT', '')] + '.png'"
                              alt="">
                            <h5 style="margin-top: 5px; text-shadow: rgba(0,0,0,0.5) 2px 2px 15px;">
                              {{ bot.marketPair.replace('USDT', '') }}</h5>
                          </div>

                          <!-- Investment Used Bar -->
                          <div class="range-progress-bar-container" style="margin-bottom: 5px;">
                            <div class="progress mt-1 mb-0"
                              style="height: 15px; position: relative; text-align: center; overflow: unset;">
                              <span class="progress-label">Investment
                                Used</span>
                              <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                role="progressbar" :style="'width: ' + calcInvestmentUsedPercentage(bot) + '%'"
                                :aria-valuenow="calcInvestmentUsedPercentage(bot)" aria-valuemin="0" aria-valuemax="100">
                              </div>
                              <div class="price-range-tooltip tooltip-bottom start">0.00$</div>
                              <div class="price-range-tooltip tooltip-bottom middle bg-primary"
                                :style="'left:' + calcInvestmentUsedPercentage(bot) + '%;'">
                                {{ (bot.usedUsd * 1 + (bot.lowerBotData ? bot.lowerBotData.usedUsd : 0)).toFixed(0) }}$
                              </div>
                              <div class="price-range-tooltip tooltip-bottom end">{{ (bot.totalUsdAmount * 1 +
                                (bot.lowerBotData ? bot.lowerBotData.totalUsdAmount / 2 : 0)).toFixed(0) }}$</div>
                            </div>
                          </div>

                          <!-- Price Range Bar -->
                          <div class="range-progress-bar-container" style="margin-bottom: 5px;">
                            <div class="progress mt-1 mb-0"
                              style="height: 15px; position: relative; text-align: center; overflow: unset;">
                              <span class="progress-label">Range</span>
                              <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                role="progressbar" :style="'width: ' + calcRangePercentage(bot) + '%'"
                                :aria-valuenow="calcRangePercentage(bot)" aria-valuemin="0" aria-valuemax="100">
                              </div>
                              <div class="price-range-tooltip tooltip-bottom start">{{ bot.lowerBotData ?
                                bot.lowerBotData.range[0] : bot.range[0] }}</div>
                              <div class="price-range-tooltip tooltip-bottom middle bg-primary"
                                :style="'left:' + calcRangePercentage(bot) + '%;'">{{ +bot.cryptoCurrentPrice.toFixed(4)
                                }}
                              </div>
                              <div class="price-range-tooltip tooltip-bottom end">{{ bot.range[1] }}</div>
                            </div>
                          </div>

                        </div>

                        <!--Footer states-->
                        <div class="card-footer py-0 border-top"
                          style="color: black; background-color: rgba(0,0,0, 0.03); border-top: 1px solid lightgray !important;">
                          <div class="row">
                            <div class="col text-center p-3">
                              <h4 class="fs-5 mb-1">
                                ${{ bot.totalUsdAmount + (bot.lowerBotData ? bot.lowerBotData.totalUsdAmount / 2 : 0) }}
                              </h4>
                              <span class="d-block font-size-sm">Invested</span>
                            </div>

                            <div class="col p-3 text-center border-start">
                              <h4 class="fs-5 mb-1">${{ (bot.totalProfit + (bot.lowerBotData ?
                                bot.lowerBotData.totalProfit : 0)).toFixed(1) }}</h4>
                              <span class="d-block font-size-sm">Profit</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-header"></div>
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
import CommonFooter from '../CommonFooter.vue'
import CommonSidebar from '../CommonSidebar.vue'

export default {
  name: 'bots-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar: CommonSidebar,
  },
  props: ['userId'],
  data() {
    return {
      viewType: 'grid',
      accountType: 'user',
      bots: [],
      combinedBots: [],
      filteredBots: [],
      currentFilter: 'Running Bots',
      filters: ['Running Bots', 'Stopped', 'Out of Range', 'All Bots'],
      showFilters: false,
      loading: true,
      showQuickBot: false,

      exchangePairs: {
        "binance": [],
      },
      exchangeName: 'binance',
      selectedPair: '',
      selectedPrice: 0,
      newApiKey: false,      
      isTesting: false,

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
      coinImages: {
        "BTC": 1,
        "ETH": 1027,
        "SOL": 5426,
        "LTC": 2,
        "RUNE": 4157,
        "APE": 18876,
        "THETA": 2416,
        "MATIC": 3890,
        "KNC": 9444,
        "ADA": 2010,
        "ENJ": 2130,
        "BAT": 1697,
        "ALGO": 4030,
        "ACA": 6756,
        "DOGE": 74
      }
    }
  },
  watch: {},
  computed: {
    computedUserId: function () {
      if (this.userId) {
        return this.userId
      } else {
        return global.vm.$local.userData._id
      }
    },
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
    TotalInvestment: function () {
      if (this.bots.length > 0) {
        let total = 0
        for (let index = 0; index < this.bots.length; index++) {
          const bot = this.bots[index]

          if (bot.botType == 'lower') {
            total += bot.totalUsdAmount / 2
          } else {
            total += bot.totalUsdAmount
          }
        }
        return total
      }
    }
  },
  methods: {
    calcInvestmentUsedPercentage(bot) {
      let val = ((bot.usedUsd * 1 + (bot.lowerBotData ? bot.lowerBotData.usedUsd : 0)) / (bot.totalUsdAmount + (bot.lowerBotData ? bot.lowerBotData.totalUsdAmount / 2 : 0)) * 100)
      val = val < 0 ? 0 : val
      val = val > 100 ? 100 : val
      return val
    },
    calcRangePercentage(bot) {
      let val = ((bot.cryptoCurrentPrice.toFixed(4) - (bot.lowerBotData ? bot.lowerBotData.range[0] : bot.range[0])) / (bot.range[1] - (bot.lowerBotData ? bot.lowerBotData.range[0] : bot.range[0])) * 100)
      val = val < 0 ? 0 : val
      val = val > 100 ? 100 : val
      return val
    },
    getBots(filter) {
      let that = this
      let formData = new FormData()

      if (this.userId) {
        formData.append('userId', this.userId)
      }

      if (filter >= 0) {
        this.currentFilter = this.filters[filter]
        //formData.append('filter', this.currentFilter)
      }

      let combinedBots = this.$store.state.Bots.bots;
      let oldUserId = this.$store.state.Bots.userId;
      console.log(combinedBots)
      console.log(oldUserId)
      if (false && combinedBots.length > 0 && this.userId == oldUserId) {
        this.combinedBots = combinedBots;
        if (combinedBots.length > 10)
          this.setFilter(0);
        else
          this.setFilter(3);

        this.loading = false;
        if (combinedBots && combinedBots.length > 0)
          this.newApiKey = false;
        else
          this.newApiKey = true;
      } else {


        this.$local
          .postRequest('/auth/bots/get-all', formData)
          .then(function (data) {
            ////console.log(data);
            //that.schedules = data[0].roosters;
            that.bots = data.bots;
            that.combineNormalLowerBots();

            if (data.bots.length > 10)
              that.setFilter(0);
            else
              that.setFilter(3);

            that.loading = false;
            if (that.bots && that.bots.length > 0)
              that.newApiKey = false;
            else
              that.newApiKey = true;



            return;
          })
          .catch(function (msg) {
            //console.log(msg)
            that.setFilter(0);
            that.loading = false;
            that.$toaster.error("Something went wrong. Error: " + msg);
            return
          })
      }
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
          that.getBots();
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
    getPairs() {
      let that = this
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
        })
        .catch(function (msg) {
          //console.log(msg)
          that.$toaster.error("Something went wrong. Error: " + msg);
          return
        })
    },
    combineNormalLowerBots() {
      // Separate normal bots and lower bots
      const normalBots = this.bots.filter(bot => bot.botType === 'normal');
      const lowerBots = this.bots.filter(bot => bot.botType === 'lower');
      const combinedData = [];

      for (let i = 0; i < normalBots.length; i++) {
        const normalBot = normalBots[i];
        if (normalBot.name.toString().toLowerCase().includes('api') && normalBot.name.toString().toLowerCase().includes('only')) {
        } else {
          const lowerBotData = lowerBots.find(lowerBot => lowerBot.oldBot === normalBot._id);
          //console.log(lowerBotData)
          if (lowerBotData) {
            normalBot.lowerBotData = lowerBotData;
          }
          combinedData.push(normalBot);

        }
      }
      //this.combinedBots = combinedData
      this.$store.commit('setUserId', this.userId)
      this.$store.commit('setAllBotsData', combinedData);
    },
    setFilter(filter) {
      this.showFilters = false;
      if (filter >= 0) {
        this.currentFilter = this.filters[filter]
        //formData.append('filter', this.currentFilter)
      }
      this.filteredBots = [];
      let combinedBots = this.$store.state.Bots.bots
      for (let index = 0; index < combinedBots.length; index++) {
        const bot = combinedBots[index]
        let lowerRange = bot.lowerBotData ? bot.lowerBotData.range[0] : bot.range[0]
        if (+bot.cryptoCurrentPrice > +bot.range[1] || +bot.cryptoCurrentPrice < lowerRange) {
          bot.outOfOrder = true
        }

        if (bot.botType == 'lower' && +bot.cryptoCurrentPrice > +bot.cryptoInitialPrice) {
          bot.outOfOrder = true
        }

        if (this.currentFilter == 'Out of Range') {
          if (bot.outOfOrder) {
          } else {
            continue
          }
        }

        if (this.currentFilter == 'Running Bots') {
          if (bot.startTrading && !bot.outOfOrder) {
          } else {
            continue
          }
        }

        if (this.currentFilter == 'Stopped') {
          if (!bot.startTrading) {
          } else {
            continue
          }
        }
        this.filteredBots.push(bot);
      }
    },
    deleteBot(botId, index) {
      let that = this
      if (confirm('Do you really want to delete?')) {
        let formData = new FormData()
        formData.append('botId', botId)
        this.$local
          .postRequest('/auth/bots/delete', formData)
          .then(function (data) {
            ////console.log(data);
            //that.schedules = data[0].roosters;
            that.filteredBots.splice(index, 1)
            that.$toaster.error('Bot Deleted.')
            return
          })
          .catch(function (msg) {
            //console.log(msg)
            that.$toaster.error('Something went wrong.')
            return
          })
      }
    }
  }, //
  created() {
  },
  mounted() {
    let that = this;
    this.getBots()
    this.getPairs()
    //if(this.viewType == 'grid')


    //console.log('something on socket')
    //console.log(global.vm.$socket)
    this.accountType = global.vm.$local.userData.accountType;


    /* let body = document.getElementsByTagName('main')[0]
    body?.addEventListener('keydown', function (e) {
      //console.log(e)
      if (e.key == 'Escape') {

        that.showFilters = false;
      }
    }) */
  }
}
</script>

<style scoped>
label {
  font-weight: 700;
}

.box-shadow {
  box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
  padding: 20px;
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


.bg-gray {
  --bs-bg-opacity: 1;
  background-color: #FF7F50 !important;
}

.card {
  box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}


.bot-img {
  text-align: center;
  margin-bottom: 20px;
}

.bot-img>img {
  width: 64px;
  height: 64px;
  opacity: 0.7;
  -webkit-filter: drop-shadow(0px 0px 2px #222);
  filter: drop-shadow(0px 0px 2px #222);
}

.progress-bar {
  border-radius: 100px;
}

.progress-label {
  position: absolute;
  width: 100%;
  top: -2px;
  color: white;
  text-shadow: 1px 1px 5px black;
}


.price-range-tooltip {
  position: absolute;
  width: 100%;
  text-align: center;
  background-color: #333;
  width: min-content;
  transition: all 0.3s ease-in-out;
  z-index: 1;
  color: #fff;
  font-size: 12px;
  font-weight: bold;
  padding: 5px;
  border-radius: 5px;
  opacity: 0;
  /* Ensure tooltips are above other elements */
}

.tooltip-top {
  top: -30px;
}

.tooltip-bottom {
  bottom: -35px;
  transform: translateX(-50%);
  opacity: 0
}

.tooltip-bottom.middle {
  bottom: -35px;
  transform: translateX(-50%);
}

.tooltip-bottom.end {
  right: 0px;
  transform: translateX(50%)
}

.tooltip-bottom::before {
  content: '';
  position: absolute;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  border-style: solid;
  border-width: 0 10px 10px 10px;
  border-color: transparent transparent #333 transparent;
}

.tooltip-bottom.middle::before {
  border-color: transparent transparent #0d6efd transparent;
}

.range-progress-bar-container:hover .tooltip-bottom {
  opacity: 0.8
}

.tooltip-bottom:hover {
  z-index: 999;
}
</style>

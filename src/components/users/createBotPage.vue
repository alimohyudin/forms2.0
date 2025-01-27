<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main">
      <div class="container">
        <div class="card">
          <div class="card-header">
            <!-- <CommonSubNav :userId="userId"></CommonSubNav> -->
          </div>
          <div class="card-header"><h3>Create Bot</h3></div>
          <div class="card-body">
            <form ref="form" id="createBot_form">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label
                      for="name"
                      v-b-popover.hover="
                        '1- If you need old values then use this field. 2- If your old bot is out of range then use this field'
                      "
                      title="Why to use old bot?"
                      ><b>Use Old Bot (?):</b></label
                    >
                    <!-- hidden text field -->
                    <input
                      type="text"
                      class="form-control"
                      name="userId"
                      :value="userId"
                      v-bind:style="{ display: 'none' }" 
                      />
                    <select
                      class="form-control"
                      id="oldBot"
                      name="oldBot"
                      v-model="useOldBotId"
                      aria-describedby="Old Bot Id"
                    >
                      <option value="">None</option>
                      <option
                        v-for="singleBot in bots"
                        v-bind:key="singleBot._id"
                        :value="singleBot._id"
                      >
                        {{ singleBot.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3" v-if="useOldBotId != ''">
                  <div class="form-group">
                    <label
                      for="name"
                      v-b-popover.hover="
                        '1- if current price is higher than your range, use Upper. 2- if current price is lower than your range, use Lower.'
                      "
                      title="What to choose?"
                      ><b>New Bot Type:</b></label
                    >
                    <select
                      class="form-control"
                      id="botType"
                      name="botType"
                      v-model="newBotType"
                      aria-describedby="Old Bot Id"
                    >
                      <option value="normal">Normal</option>
                      <option value="lower">Lower of old bot</option>
                      <option value="upper">Upper of old bot</option>                      
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- <input type="hidden" name="botId" :value="this.bot._id" /> -->

                  <div class="form-group">
                    <label for="name">Bot Name:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="name"
                      name="name"
                      v-model="newBotName"
                      aria-describedby="nameHelp"
                    />
                  </div>
                  <div class="form-group" v-if="false">
                    <label for="botStrategy">Bot Strategy:</label>
                    <select
                      class="form-control"
                      id="botStrategy"
                      name="botStrategy"
                      v-model="bot.botStrategy"
                      aria-describedby="botStrategyHelp"
                    >
                      <option value="blshv1">BLSHv1 (50% Risk)</option>
                      <option value="blshv2">BLSHv2 (10% Risk)</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exchangeName">Exchange Name:</label>
                    <select
                      class="form-control"
                      id="exchangeName"
                      name="exchangeName"
                      v-model="bot.exchangeName"
                      aria-describedby="exchangeNameHelp"
                    >
                      <option value="binance">Binance</option>
                      <option value="binance-us">Binance US</option>
                      <option value="kraken">Kraken (deprecated)</option>
                      
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="marketPair">Crypto:</label>
                    <select
                      class="form-control"
                      id="marketPair"
                      name="marketPair"
                      :value="bot.marketPair"
                      v-model="selectedPair"
                      aria-describedby="marketPairHelp"
                    >
                      <option
                        v-for="pair in exchangePairs[bot.exchangeName == 'binance-us' ? 'binance' : bot.exchangeName]"
                        v-bind:key="pair"
                        :value="pair"
                      >
                        {{ pair.replace('USDT', '') }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label
                      for="totalUsdAmount"
                      v-b-popover.hover="
                        'Investment is the money in USDT that you want to start with e.g. Start my bot with 1000$ investment so you write 1000 here.'
                      "
                      title="What is Investment USDT?"
                      >Investment USDT (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="totalUsdAmount"
                      name="totalUsdAmount"
                      v-model="totalUsdAmount"
                      aria-describedby="totalUsdAmountHelp"
                      required="required"
                    />
                  </div>
                  <div class="form-group" v-if="newBotType == 'normal'">
                    <label
                      for="cryptoInitialPrice"
                      v-b-popover.hover="
                        'Starting price is when your bot makes its first trade eg. Start bot with First Buy of ADA at 1.5$ so you put 1.5 here.'
                      "
                      title="What is starting price?"
                      >Starting Price (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="cryptoInitialPrice"
                      name="cryptoInitialPrice"
                      :value="selectedPrice"
                      aria-describedby="cryptoInitialPriceHelp"
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <label
                      for="cryptoCheckpoints"
                      v-b-popover.hover="
                        'Trading speed is how fast your bot makes trades. More Speed means More Trades but less profit per trade'
                      "
                      title="What is trading speed?"
                      >Trading Speed (?):
                    </label>
                    <!-- /**
 * Very Slow: checkpoints = 5
 * Slow: checkpoints = 9
 * Medium: checkpoints = 11
 * Fast: checkpoints = 13
 * Very Fast: checkpoints = 19
 * Extreme: checkpoitns = 23
 */ -->
                    <select
                      class="form-control"
                      id="cryptoCheckpoints"
                      name="cryptoCheckpoints"
                      v-model="bot.cryptoCheckpoints"
                      aria-describedby="cryptoCheckpointsHelp"
                      required="required"
                    >
                      <option value="5">Very Slow</option>
                      <option value="9">Slow</option>
                      <option value="11">Medium</option>
                      <option value="13">Fast</option>
                      <option value="19">Very Fast</option>
                      <option value="23">Extreme</option>
                      <option value="33">Ultra Fast</option>
                    </select>
                  </div>

                </div>
                <div class="col-sm-6" v-if="useOldBotId == ''">
                  <div class="form-group">
                    <label
                      for="apiKey"
                      v-b-popover.hover="
                        'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      "
                      title="What is API Key?"
                      >API Key (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="apiKey"
                      name="apiKey"
                      v-model="bot.apiKey"
                      aria-describedby="apiKeyHelp"
                    />
                  </div>
                  <div class="form-group">
                    <label
                      for="apiSecrete"
                      v-b-popover.hover="
                        'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      "
                      title="What is API Secret?"
                      >API Secret (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="apiSecret"
                      name="apiSecrete"
                      v-model="bot.apiSecrete"
                      aria-describedby="apiSecreteHelp"
                    />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  
                  <button
                    type="button"
                    class="btn btn-secondary"
                    id="createAndStartBotBtn"
                    v-on:click="createBot(true)"
                  >
                    Save & Start Bot
                  </button>
                  <span>&nbsp;</span>
                  <button
                    type="button"
                    class="btn btn-primary"
                    id="createBotBtn"
                    v-on:click="createBot(false)"
                  >
                    Save Only
                  </button>
                </div>
              </div>
            </form>
            <!-- <router-link style="color:#555;  font-size: 20px;" to="/register-page">Or, create a free account...</router-link> -->
          </div>
          <div class="card-header"></div>
        </div>
      </div>
    </main>

    <!--/.Navbar -->

    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
import CommonHeader from '../CommonHeader.vue'
import CommonFooter from '../CommonFooter.vue'

import CommonSidebar from '../CommonSidebar.vue'
/* import Checkpoints from '../../tiny-components/checkpoints.vue'
import BotInsights from '../../tiny-components/botInsights.vue' */

export default {
  name: 'edit-bot-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar,
    // Checkpoints,
    // BotInsights,
  },
  props: ['userId'],
  data() {
    return {
      bots: [],
      newBotName: 'My First Bot',
      useOldBotId: '',
      newBotType: 'normal',
      totalUsdAmount: 1000,
      allPairs: [],

      selectedPair: '',
      selectedPrice: 0,
      
      bot: {
        botStrategy: 'blshv1',
        name: 'My Bot',
        exchangeName: 'binance',
        marketPair: 'MATICUSDT',
        cryptoCheckpoints: 19,
        cryptoPriceIntervals: 0,
        
      },
      tradingSpeed: {
        5: 'Very Slow',
        9: 'Slow',
        11: 'Medium',
        13: 'Fast',
        19: 'Very Fast',
        23: 'Extreme',
      },
      exchangePairs: {
        "kraken":  [
        '1INCHUSD',
        'AAVEUSD',
        'ADAUSD',
        'ALGOUSD',
        'ANKRUSD',
        'ANTUSD',
        'ATOMUSD',
        'AUDUSD',
        'AXSUSD',
        'BADGERUSD',
        'BALUSD',
        'BANDUSD',
        'BATUSD',
        'BCHUSD',
        'BNTUSD',
        'CHZUSD',
        'COMPUSD',
        'CQTUSD',
        'CRVUSD',
        'CTSIUSD',
        'DAIUSD',
        'DASHUSD',
        'DOTUSD',
        'DYDXUSD',
        'ENJUSD',
        'EOSUSD',
        'EWTUSD',
        'FILUSD',
        'FLOWUSD',
        'GHSTUSD',
        'GNOUSD',
        'GRTUSD',
        'ICXUSD',
        'INJUSD',
        'KARUSD',
        'KAVAUSD',
        'KEEPUSD',
        'KNCUSD',
        'KSMUSD',
        'LINKUSD',
        'LPTUSD',
        'LRCUSD',
        'LSKUSD',
        'MANAUSD',
        'MATICUSD',
        'MINAUSD',
        'MIRUSD',
        'MKRUSD',
        'MOVRUSD',
        'NANOUSD',
        'OCEANUSD',
        'OGNUSD',
        'OMGUSD',
        'OXTUSD',
        'PAXGUSD',
        'PERPUSD',
        'QTUMUSD',
        'RARIUSD',
        'RENUSD',
        'REPV2USD',
        'SANDUSD',
        'SCUSD',
        'SDNUSD',
        'SNXUSD',
        'SOLUSD',
        'SRMUSD',
        'STORJUSD',
        'SUSHIUSD',
        'TBTCUSD',
        'TRXUSD',
        'UNIUSD',
        'USDCUSD',
        'USDTZUSD',
        'WAVESUSD',
        'WBTCUSD',
        'XDGUSD',
        'XETCZUSD',
        'XETHZUSD',
        'XLTCZUSD',
        'XMLNZUSD',
        'XREPZUSD',
        'XTZUSD',
        'XXBTZUSD',
        'XXLMZUSD',
        'XXMRZUSD',
        'XXRPZUSD',
        'XZECZUSD',
        'YFIUSD',
        'ZEURZUSD',
        'ZGBPZUSD',
        'ZRXUSD',
      ],
        "binance": [],
      },
      cryptoUsdArrays: {},
      isShowingInsights: false,
    }
  },
  watch: {
    useOldBotId(changed) {
      let that = this
      if (changed != '') {
        this.bots.forEach(function(val, index) {
          if (val._id == changed) {
            console.log(changed)
            that.bot = val
            that.newBotName = that.bot.name
            that.newBotType = 'normal'
            that.totalUsdAmount = that.bot.totalUsdAmount
            return
          }
        })
      }
    },
    newBotType(changed) {
      if (changed == 'upper') {
        this.newBotName = 'Upper: ' + this.bot.name
        this.bot.cryptoInitialPrice = this.bot.cryptoCheckpointsArray[
          this.bot.cryptoCheckpointsArray.length - 1
        ]
        
        this.totalUsdAmount = this.bot.totalUsdAmount / 2
      } else if (changed == 'lower') {
        this.newBotName = 'Lower: ' + this.bot.name
        this.bot.cryptoInitialPrice = this.bot.cryptoCheckpointsArray[0]
        
        this.totalUsdAmount = this.bot.totalUsdAmount
      } else {
        this.totalUsdAmount = this.bot.totalUsdAmount
      }
    },
    cryptoUsdArrays(changed) {
      this.bot.minPrice = changed.cryptoCheckpointsArray[0]
      this.bot.maxPrice =
        changed.cryptoCheckpointsArray[
          changed.cryptoCheckpointsArray.length - 1
        ]
      this.bot.usdUsagePerPoint = changed.buyWithUsdAmountArray[0]
    },
    selectedPair(changed){
      console.log(changed)
      let that= this;
      this.allPairs.forEach(function(val, index){
        if(val.pair == changed){
          console.log("found", val)
          that.selectedPrice = val.price;
          return
        }
      })
    }
  },
  methods: {
    createBot(saveAndStart = false) {
      let formData = $('#createBot_form').serialize()
      formData += '&isTesting=false' //make it false for live-
      formData += '&startTrading=' + saveAndStart

      /* console.log(formData)
      console.log("End")
      return; */

      let that = this
      this.$local
        .postRequest('/auth/bots/create', formData)
        .then(function(data) {
          console.log(data)
          //that.timesheets.push(data[0]);
          //that.$router.push({name: 'ManageTimesheets'})
          that.$toaster.success('Bot Created!')
          that.$router.push({ name: 'bots-page' })
          return
        })
        .catch(function(msg) {
          console.log(msg)
          that.$toaster.error(msg)
          return
        })
    },
    getBots() {
      let that = this
      let formData = new FormData()
      formData.append('userId', this.userId)
      this.$local
        .postRequest('/auth/bots/get-all', formData)
        .then(function(data) {
          //console.log(data);
          //that.schedules = data[0].roosters;
          that.bots = data.bots
        })
        .catch(function(msg) {
          console.log(msg)
          //that.$toaster.error("Something went wrong.");
          return
        })
    },
    calculateInterval() {
      this.bot.maxPrice = (4 / 3) * this.bot.cryptoInitialPrice
      this.bot.cryptoPriceIntervals = (
        (this.bot.maxPrice - this.bot.maxPrice / 2) /
        (this.bot.cryptoCheckpoints - 1)
      ).toFixed(3)
    },
    calculateInsights() {
      console.log('CALCULATING INSIGHTS:')
      /* this.bot.maxPrice = (4 / 3) * this.bot.cryptoInitialPrice;
      this.bot.minPrice =
        this.bot.maxPrice -
        this.bot.cryptoPriceIntervals * (this.bot.cryptoCheckpoints - 1);

      this.bot.profit =
        (this.cryptoUsdArrays.buyWithUsdAmountArray[0] / this.bot.cryptoInitialPrice) *
          (this.bot.cryptoInitialPrice + 1 * this.bot.cryptoPriceIntervals) -
        this.cryptoUsdArrays.buyWithUsdAmountArray[0];

      this.bot.profit = (this.bot.profit).toFixed(3); */

      this.isShowingInsights = true
    },
    getPairs(){
      let that = this
      let formData = new FormData()
      formData.append('exchange', 'binance')
      this.$local
        .postRequest('/auth/pairs/get-all', formData)
        .then(function(data) {
          console.log(data);
          that.allPairs = data.pairs;
          data.pairs.forEach(function(val, index) {
            //that.pairs.push(val.pair)
            that.exchangePairs['binance'].push(val.pair);
          })
          that.selectedPair = 'MATICUSDT'
          //that.schedules = data[0].roosters;
          //that.pairs = data.pairs
        })
        .catch(function(msg) {
          console.log(msg)
          //that.$toaster.error("Something went wrong.");
          return
        })
    }
  },
  created: function() {
    this.getBots()
    this.getPairs()
  },
}
</script>

<style></style>

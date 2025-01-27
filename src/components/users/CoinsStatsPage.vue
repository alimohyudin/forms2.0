<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main">
      <div class="container">
        <div class="card">
          <div class="card-header">
            <!-- <CommonSubNav></CommonSubNav> -->
          </div>
          <div class="card-header">
            <h3><i class="bi bi-pie-chart"></i> Coins Stats</h3>
          </div>
          <div class="card-body">
            <div style="margin-bottom: 5px">
              <div class="row">
                <div class="col-sm-6">
                  <router-link to="/user/create-bot">
                    <button class="btn btn-primary">
                      Create Bot
                    </button>
                  </router-link>
                </div>

                <div class="col-sm-6" style="text-align: right;">

                  <button class="btn btn-secondary" v-on:click="getBots()">
                    Refresh
                  </button>
                </div>
              </div>
            </div>
            <br />
            <div class="container">
              <div class="row">

                <div class="col-xl-3 mb-3 mb-lg-3" v-for="(coin, index) in coins" :key="index">
                  <div class="card" style="border: 1px solid #dee2e6;">
                    <div class="card-body">
                      <div style="position: relative">
                        
                      </div>
                      <div class="d-flex flex-column text-center align-items-center">

                        <div class="flex-grow-1 pt-4">
                          <span class="d-block text-muted" style="font-size: x-small;">
                            
                          </span>
                          <h5></h5>

                        </div>
                      </div>

                      <div class="bot-img">
                        <img class="img"
                          :src="'https://s2.coinmarketcap.com/static/img/coins/64x64/' + coinImages[coin.marketPair.replace('USDT', '')] + '.png'"
                          alt="">
                        <h5 style="margin-top: 5px; text-shadow: rgba(0,0,0,0.5) 2px 2px 15px;">
                          {{ coin.marketPair.replace('USDT', '') }}</h5>
                      </div>


                    </div>

                    <!--Footer states-->
                    <div class="card-footer py-0 border-top"
                      style="color: black; background-color: rgba(0,0,0, 0.03); border-top: 1px solid lightgray !important;">
                      <div class="row">
                        <div class="col text-center p-3">
                          <h4 class="fs-5 mb-1">
                            
                          </h4>
                          <span class="d-block font-size-sm">Coins</span>
                        </div>

                        <div class="col p-3 text-center border-start">
                          <h4 class="fs-5 mb-1">{{ (coin.remainingCrypto ? coin.remainingCrypto : 0).toFixed(coin.lot_decimals) }}</h4>
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

    <!-- <div v-if="loading" class="processing" style="">
      <span style="">Processing...</span>
    </div> -->
    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
import CommonHeader from '../CommonHeader.vue'
import CommonFooter from '../CommonFooter.vue'
import CommonSubNav from '../CommonSubNav.vue'
import CommonSidebar from '../CommonSidebar.vue'

export default {
  name: 'user-stats-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar: CommonSidebar,
  },
  props: ['userId'],
  data() {
    return {
      spentUSDTAmount: 0,
      spentUSDTValueNow: 0,
      totalValueWithBot: 0,
      totalValueWithoutBot: 0,
      bots: [],
      coins: [],
      pairs: [],
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
      },
      loading: true,
    }
  },
  watch: {},
  computed: {
    TotalProfit: function () {
      if (this.bots.length > 0) {
        let total = 0
        this.spentUSDTValueNow = 0;
        this.spentUSDTAmount = 0;
        for (let index = 0; index < this.bots.length; index++) {
          const bot = this.bots[index]
          total += bot.totalProfit

          //calculate remaining money availbale in account.

          this.spentUSDTAmount += bot.totalBuyUSDT;
          this.spentUSDTValueNow += bot.totalCrypto * bot.cryptoCurrentPrice
          this.totalValueWithBot += bot.totalValueWithBot;
          this.totalValueWithoutBot += bot.totalValueWithoutBot;

          this.loading = false;

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
    },
  },
  methods: {
    getBots(filter) {
      this.loading = true;
      let that = this
      let formData = new FormData()

      if (this.userId) {
        formData.append('userId', this.userId)
      } else {
        formData.append('userId', global.vm.$local.userData._id)
      }
      //formData.append('stats', true)


      if (filter >= 0) {
        this.currentFilter = this.filters[filter]
        //formData.append('filter', this.currentFilter)
      }
      this.$local
        .postRequest('/auth/bots/get-all', formData)
        .then(function (data) {
          console.log(data);
          if (data.bots.length == 0)
            that.loading = false;

          that.bots = []
          that.coins = []
          for (let index = 0; index < data.bots.length; index++) {
            let bot = data.bots[index]
            let foundCoin = that.coins.find(coin => coin.marketPair === bot.marketPair)
            if (foundCoin) {
              foundCoin.remainingCrypto += bot.remainingCrypto
            } else {
              let findPair = that.pairs.find(pair => pair.pair === bot.marketPair)
              let coin = {
                marketPair: bot.marketPair,
                remainingCrypto: bot.remainingCrypto,
                lot_decimals: findPair ? findPair.lot_decimals : 2
              }
              that.coins.push(coin)
            }
            //console.log(that.coins)
          }
          /* for (let index = 0; index < data.bots.length; index++) {
            let bot = data.bots[index]
            //console.log("LAST TRADE OF: " + bot._id);
            if (bot.buyCheckpoints) {//buyCheckpoints is when price is below starting price.//it is common for normal and lower bot.
              if (bot.botType == 'normal')
                bot._totalCrypto = bot.totalUsdAmount / 2 / bot.cryptoInitialPrice
              else
                bot._totalCrypto = 0;

              for (let index = 0; index < bot.buyCheckpoints.length; index++) {
                const checkpoint = bot.buyCheckpoints[index];

                if (index != bot.buyCheckpoints.length - 1) {
                  bot._totalCrypto += bot.buyWithUsdAmountArray[0] / checkpoint
                } else {
                  if (bot.lastTrade && bot.lastTrade.atPrice == checkpoint && bot.lastTrade.orderType == 'buy') {
                    bot._totalCrypto += bot.buyWithUsdAmountArray[0] / checkpoint
                  }
                }
              }
            } else if (bot.sellCheckpoints) {//sellCheckpoints is when price is above starting price.//it is only for normal bot.
              bot._totalCrypto = bot.totalUsdAmount / 2 / bot.cryptoInitialPrice
              let startingCryptoAmount = bot._totalCrypto
              for (let index = 0; index < bot.sellCheckpoints.length; index++) {
                const checkpoint = bot.sellCheckpoints[index];
                //remove what was bought at starting price
                bot._totalCrypto -= startingCryptoAmount / ((bot.cryptoCheckpointsArray.length-1)/2)
                //console.log("total crypto: " + bot._totalCrypto)
                if (index == bot.sellCheckpoints.length - 1 && bot.lastTrade) {
                  console.log(bot.marketPair +" last trade")
                  if(bot.lastTrade.atPrice == checkpoint && bot.lastTrade.status == 'completed'){
                    console.log("1")
                  }
                  
                  if(bot.lastTrade.atPrice > checkpoint && bot.lastTrade.orderType == 'sell'){
                    console.log("2")
                    bot._totalCrypto -= startingCryptoAmount / ((bot.cryptoCheckpointsArray.length-1)/2)
                  }
                  if(bot.lastTrade.atPrice == checkpoint && bot.lastTrade.orderType == 'buy' && bot.lastTrade.status == 'not-completed'){
                    console.log("3")
                    bot._totalCrypto -= startingCryptoAmount / ((bot.cryptoCheckpointsArray.length-1)/2)
                  }
                }
              }

            } else{
              if(bot.botType == 'normal'){
                bot._totalCrypto = bot.totalUsdAmount / 2 / bot.cryptoInitialPrice
              }
            }
            that.bots.push(bot)
          } */
        })
        .catch(function (msg) {
          console.log(msg)
          //that.$toaster.error("Something went wrong.");
          return
        })
    },
    getPairs() {
      let that = this;

      this.$local
        .postRequest("/auth/pairs/get-all", {})
        .then(function (data) {
          //console.log('Get pairs: ')
          console.log(data)
          that.pairs = data.pairs;
          that.getBots()
          return;
        })
        .catch(function (msg) {
          console.log(msg);
          //that.$toaster.error("Allow Balance access on API")
          return;
        });
    },
  }, //
  mounted() {
    console.log("mounted")
  },
  created() {
    let that = this
    console.log("started")
    //this.$local.userData.
    this.getPairs()//inside it getBots
    
    // setInterval(function() {
    //   that.getBots()
    // }, 120000)

    console.log('something on socket')
    console.log(global.vm.$socket)
  },
}
</script>

<style>
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
</style>

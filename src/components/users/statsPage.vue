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
            <h3><i class="bi bi-pie-chart"></i> Stats & Comparison</h3>
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
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <td>Name</td>
                  <td>Invested</td>
                  <td>Current</td>
                  <td>Start</td>
                  <td>Range</td>
                  <td>Pair</td>
                  <td>Profit</td>
                  <!-- <td>Recently</td> -->
                  <td>Ago</td>
                  <td>Total Buys</td>
                </thead>
                <tbody>
                  <tr v-for="(bot, index) in bots" :key="bot._id" :style="bot.outOfOrder
                    ? 'background-color: rgb(255 234 230);'
                    : ''
                    ">
                    <td>
                      [{{ index + 1 }}] {{ bot.name }}
                      <span style="font-weight: bold;">{{ bot.outOfOrder ? '[Out of Range]' : '' }}
                        {{ bot.startTrading ? '' : '[Stopped]' }}
                      </span>
                    </td>
                    <td>
                      {{
                        bot.botType == 'lower'
                        ? bot.totalUsdAmount / 2
                        : bot.totalUsdAmount
                      }}
                    </td>
                    <td>
                      {{ +bot.cryptoCurrentPrice.toFixed(4) }}
                    </td>
                    <td>
                      {{ +bot.cryptoInitialPrice.toFixed(4) }}
                    </td>
                    <td>
                      <!-- <span v-if="bot.botType == 'upper'">{{
                        bot.cryptoInitialPrice
                      }}</span> -->
                      <span>
                        {{ bot.range[0] + " - " + (bot.botType == 'lower' ? bot.cryptoInitialPrice : bot.range[1]) }}
                      </span>
                    </td>
                    <td>{{ bot.marketPair }}</td>
                    <td>{{ bot.totalProfit.toFixed(2) }}$</td>
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
                      Spent: {{ bot.totalBuyUSDT }} $<br />
                      Remaining: {{ bot.remainingUSDT }} $<br />
                      Crypto Bought: {{ bot.totalCrypto }} <br />
                      Value now:
                      {{ ((bot.totalCrypto) * bot.cryptoCurrentPrice).toFixed(0) }}
                      $<br />
                      Profit: {{ (bot.totalProfit).toFixed(0) }} $<br />



                      <hr>
                      Total (Val + Rem + Profit): {{ bot.totalValueWithBot.toFixed(0) }} $

                      <hr>
                      Value Without Bot:
                      <!-- bot with current price being below starting price -->
                      <span>
                        {{ bot.totalValueWithoutBot.toFixed(0) }}
                      </span>

                    </td>
                  </tr>
                  <tr>
                    <td colspan="" style="text-align: center; font-size: 24px;">
                      <b>Total: </b>
                    </td>
                    <td>
                      <b v-if="TotalInvestment > 0">{{ TotalInvestment }}</b>
                    </td>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: left; font-size: 24px;">
                      <b v-if="TotalProfit > 0">{{ TotalProfit.toFixed(0) }}$</b>
                    </td>
                    <td>
                      <b>Total Value With Bot: </b>{{ totalValueWithBot.toFixed(0) }}$<br />
                      <b>Total Value Without Bot: </b>{{ totalValueWithoutBot.toFixed(0) }}$
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div style="margin-top: 100px;">
              <!-- <button class="btn btn-danger">Logout</button> -->
              <b>"Spent": </b> It is the USDT amount that is used so far by the bot.<br />
              <b>"Remaing":</b> It is the USDT amount that is left to be used later if price drops.<br />
              <b>"Crypto Bought": </b> It is the amount of crypto coins that are bought using Spent amount.<br />
              <b>"Profit":</b> It is the USDT amount that is generated as profit by the bot.<br />

              <b>"Value Now":</b> It is the total value of money spent using the bot at current price. It is without
              profit.<br />


              <b>"Value Without Bot":</b> It is the total value of money spent at the price the bot was started. For
              example, if a bot
              was started at 1.5$ of MATICUSDT with 1000$, and if current price is 1$ then current value without using bot
              will be: 1000/1.5$ x 1$ = 666$
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
      formData.append('stats', true)


      if (filter >= 0) {
        this.currentFilter = this.filters[filter]
        //formData.append('filter', this.currentFilter)
      }
      this.$local
        .postRequest('/auth/bots/get-all', formData)
        .then(function (data) {
          //console.log(data);
          //that.schedules = data[0].roosters;
          //that.bots = data.bots;
          if (data.bots.length == 0)
            that.loading = false;

          that.bots = []
          for (let index = 0; index < data.bots.length; index++) {
            const bot = data.bots[index]
            console.log("LAST TRADE OF: " + bot._id);
            //console.log(bot)

            /**
             * Total Buy USDT
             */

            //console.log('Calculate Buy information: ')
            let totalBuyUSDT = bot.totalUsdAmount / 2
            let totalCrypto = bot.calcRemainingCrypto
            let totalUSDTNow = 0

            if (bot.marketPair == 'KNCUSDT') {
              bot.cryptoCurrentPrice = 0.784;
            }

            if (bot.cryptoCurrentPrice < bot.cryptoInitialPrice) {
              if (bot.botType == 'lower') {
                totalBuyUSDT = 0
              }

              let filteredTrades = bot.uniqueTrades.sort((a,b)=>a.atPrice - b.atPrice)
              filteredTrades = filteredTrades.filter((a)=>a.atPrice < bot.cryptoInitialPrice && a.atPrice > bot.cryptoCurrentPrice)
              console.log(filteredTrades)

              for (let j = 0; j < filteredTrades.length-1; j++) {
                const trade = filteredTrades[j];
                console.log(trade)
                if(trade.orderType == 'buy'){
                  console.log(trade.usdAmount)
                  totalBuyUSDT += trade.usdAmount;//trade.cryptoAmount*trade.atPrice
                }
              }
            } else {
              if (bot.botType == 'lower') {
                totalBuyUSDT = 0
              } else {
                // totalCrypto = totalBuyUSDT / bot.cryptoInitialPrice

                // let i = bot.cryptoInitialPrice
                // while (
                //   totalCrypto > bot.remainingCrypto
                //   /* i > bot.cryptoCurrentPrice &&
                //   i >= bot.cryptoCheckpointsArray[0] - bot.cryptoPadding */
                // ) {
                //   if (i == bot.cryptoInitialPrice) {
                //   } else {
                //     totalBuyUSDT -= bot.buyWithUsdAmountArray[0]
                //     totalCrypto -= bot.buyWithUsdAmountArray[0] / i
                //     //totalUSDTNow += (bot.buyWithUsdAmountArray[0] / i) * bot.cryptoCurrentPrice
                //   }

                //   i += bot.cryptoPriceIntervals
                // }
              }
            }
            //console.log('Total Buy USDT: ' + Math.round(totalBuyUSDT))
            bot.totalBuyUSDT = totalBuyUSDT > 0 ? Math.round(totalBuyUSDT) : 0;
            bot.totalCrypto = totalCrypto > 0 ? totalCrypto.toFixed(2) : 0;
            bot.totalUSDTNow = totalUSDTNow > 0 ? totalUSDTNow.toFixed(2) : 0;
            bot.remainingUSDT = +bot.totalUsdAmount - +bot.totalBuyUSDT;
            if (bot.botType == 'lower')
              bot.remainingUSDT = +bot.totalUsdAmount / 2 - +bot.totalBuyUSDT;



            /* Total Value With/without Bot */
            bot.totalValueWithBot = (bot.totalCrypto * bot.cryptoCurrentPrice + bot.remainingUSDT + bot.totalProfit);
            bot.totalValueWithoutBot = 0;

            if (bot.cryptoCurrentPrice < bot.cryptoInitialPrice) {
              if (bot.botType == 'normal') {
                bot.totalValueWithoutBot = (bot.totalUsdAmount / bot.cryptoInitialPrice * bot.cryptoCurrentPrice);
              } else if (bot.botType == 'lower') {
                bot.totalValueWithoutBot = ((bot.totalUsdAmount / 2) / bot.cryptoInitialPrice * bot.cryptoCurrentPrice)
              }
            } else {
              if (bot.botType == 'lower') {
                bot.totalValueWithoutBot = +bot.remainingUSDT;
              } else {
                bot.totalValueWithoutBot = ((bot.totalUsdAmount / 2) / bot.cryptoInitialPrice * bot.cryptoCurrentPrice);
              }
            }

            if (bot.totalUsdAmount > 10)
              that.bots.push(bot)

          }
        })
        .catch(function (msg) {
          console.log(msg)
          //that.$toaster.error("Something went wrong.");
          return
        })
    },
  }, //
  mounted() {
    console.log("mounted")
  },
  created() {
    let that = this
    console.log("started")
    //this.$local.userData.
    this.getBots()
    // setInterval(function() {
    //   that.getBots()
    // }, 120000)

    console.log('something on socket')
    console.log(global.vm.$socket)
  },
}
</script>

<style></style>

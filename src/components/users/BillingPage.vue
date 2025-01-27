<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->

    <div class="">
      <div class="container">
        <div class="card">
          <div class="card-header">
            <CommonSubNav></CommonSubNav>
          </div>
          <div class="card-header"><h3>Billing</h3></div>
          <div class="card-body">
           
            <!-- Show Dashboard -->
            <div>
              
              <div style="padding: 20px; border: 3px solid ghostwhite">
                <div
                  class="row"
                  style="padding: 0px 15px; margin: 5px 0px; text-align: center"
                >
                  
                  <div
                    class="col-md-3 col-sm-3"
                    style=" border: 2px solid; border-radius: 5px; margin: 0px 5px 5px 0px; "
                  >
                    <div style="border-bottom: 10px solid;">
                      <h1>Fee</h1>
                    </div>
                    <div style="margin: 25px;">
                      <span
                        ><b>Date: </b>{{ (new Date()).toDateString() }}</span
                      ><br />
                      <span
                        ><b>Total Profit: </b>{{ (+botStats.TotalProfit).toFixed(0) }}</span
                      ><br />
                      <span
                        ><b>Fee: </b>20% of Profit</span
                      ><br />
                      <span
                        ><b>Total Fee: </b
                        >{{ (botStats.TotalProfit*20/100).toFixed(0) }}$</span
                      ><br />
                      <!-- <span
                        ><button class="btn btn-danger">Pay Now: <b>{{ ((botStats.TotalProfit*20/100).toFixed(0)) }}$</b></button></span
                      ><br /><br/> -->
                      
                    </div>
                  </div>
                  <div
                    class="col-md-3 col-sm-3"
                    style=" border: 2px solid; border-radius: 5px; margin: 0px 5px 5px 0px; "
                    v-if="lastPaid.atProfit"
                  >
                    <div style="border-bottom: 10px solid;">
                      <h1 v-if="lastPaid.status == 'paid'">Last Paid</h1>
                      <h1 v-if="lastPaid.status == 'requested'">Pay Fee</h1>
                    </div>
                    <div style="margin: 25px;">
                      <span
                        ><b>Date: </b>{{ (new Date(lastPaid.updatedAt)).toDateString() }}</span
                      ><br />
                      <span
                        ><b>On Profit: </b>{{ (+lastPaid.atProfit).toFixed(0) }}</span
                      ><br />
                      <span
                        ><b>Percentage: </b>{{lastPaid.percentage}}% of Profit</span
                      ><br />
                      <span
                        ><b>Total Paid: </b
                        >{{ (+lastPaid.total).toFixed(0) }}$</span
                      ><br />
                      <!-- <span
                        ><button class="btn btn-danger">Pay Now: <b>{{ ((botStats.TotalProfit*20/100).toFixed(0)) }}$</b></button></span
                      ><br /><br/> -->
                      
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
            <!-- End -->
          </div>
          <div class="card-header">
            <div style="text-align: center; font-size: 8px;">
              Tip: Do not use public internet for your private things.
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--/.Navbar -->

    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
import CommonHeader from '../CommonHeader.vue'
import CommonFooter from '../CommonFooter.vue'
import CommonSubNav from '../CommonSubNav.vue'

export default {
  name: 'billing-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSubNav: CommonSubNav,
  },
  data() {
    return {
      bots: [],
      balances: [],
      pairs: [],
      openOrders: [],
      maticPrice: 0,
      algoPrice: 0,
      enjPrice: 0,

      botStats: {},
      lastPaid: {},

      isExchangeAccountSettings: false,
      exchangeAccountSettings: {
        exchangeName: 'kraken',
        apiKey: '',
        apiSecret: '',
      },
    }
  },
  watch: {
    isExchangeAccountSettings(changed) {
      if (changed == true) {
        console.log('Key updated: true')
      }
    },
  },
  computed: {
    TotalProfit: function() {
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
        .then(function(data) {
          console.log(data)
          global.vm.$local.userData.exchangeAccountSettings = {
            exchangeName: that.exchangeAccountSettings.exchangeName,
            apiKey: that.exchangeAccountSettings.apiKey,
            apiSecret: that.exchangeAccountSettings.apiSecret,
          }
          that.isExchangeAccountSettings = true
          localStorage.setItem('isExchange', (data.data.isExchangeAccountSettings));
          return
        })
        .catch(function(msg) {
          console.log(msg)
          that.$toaster.error(msg)
          return
        })
    },
    getBalances() {
      let that = this

      this.$local
        .postRequest('/auth/exchange-account/get-balances', {})
        .then(function(data) {
          //console.log(data)
          that.balances = data.result
          that.maticPrice = data.maticPrice
          that.algoPrice = data.algoPrice
          that.enjPrice = data.enjPrice
          console.log(that.balances)
          return
        })
        .catch(function(msg) {
          console.log(msg)
          that.$toaster.error('Allow Balance access on API')
          return
        })
    },
    getPairs() {
      let that = this

      this.$local
        .postRequest('/auth/pairs/get-all', {})
        .then(function(data) {
          console.log('Get pairs: ')
          console.log(data)
          that.pairs = data.pairs
          return
        })
        .catch(function(msg) {
          console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    pairPrice(pair){
      for (let index = 0; index < this.pairs.length; index++) {
        const element = this.pairs[index];
        if(element.pair == pair){
          return element.price
        }
      }
    },
    getOpenOrders() {
      let that = this

      this.$local
        .postRequest('/auth/exchange-account/get-open-orders', {})
        .then(function(data) {
          console.log('Open Orders: ')
          let openOrders = data.result.open
          console.log(openOrders)
          that.openOrders = []

          for (let index = 0; index < Object.keys(openOrders).length; index++) {
            let key = Object.keys(openOrders)[index]
            let singleOrder = openOrders[key]
            /* console.log(singleOrder)
            console.log('Time: ' + new Date(singleOrder.opentm * 1000))
            console.log(singleOrder.descr.order)
            console.log(singleOrder.vol)
            console.log(singleOrder.descr.price) */

            that.openOrders.push({
              orderExchangeId: key,
              order: singleOrder.descr.order,
              time: new Date(singleOrder.opentm * 1000),
              vol: 1 * singleOrder.vol,
              atPrice: 1 * singleOrder.descr.price,
            })
          }
        })
        .catch(function(msg) {
          console.log(msg)
          that.$toaster.error(msg)
          return
        })
    },
    getStats() {
      let that = this

      this.$local
        .postRequest('/auth/users/account-stats', {})
        .then(function(data) {
          console.log('Account STats: ')
          console.log(data)
          that.botStats = data
          return
        })
        .catch(function(msg) {
          console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    getLastBill() {
      let that = this

      this.$local
        .postRequest('/auth/billing/get-last', {})
        .then(function(data) {
          console.log('Last Bill: ')
          console.log(data)
          that.lastPaid = data
          //that.botStats = data
          return
        })
        .catch(function(msg) {
          console.log(msg)
          //that.$toaster.error("Allow Balance access on API")
          return
        })
    },
    /* deleteOrder(orderExchangeId) {
      let that = this
      console.log('Delete order: ' + orderExchangeId)
      let formData = new FormData()
      formData.append('orderExchangeId', orderExchangeId)
      
      this.$local
        .postRequest('/auth/exchange-account/delete-order', formData)
        .then(function(data) {
          console.log(data)

          //console.log(that.balances)
          return
        })
        .catch(function(msg) {
          console.log(msg)
          that.$toaster.error(msg)
          return
        })
    }, */
  }, //
  created() {
    let that = this

    that.getStats()
    that.getLastBill()
    // console.log('something on socket')
    // console.log(global.vm.$socket)
    // console.log('Something from user settings on vue')
    // console.log(global.vm.$local.token)
    // console.log(global.vm.$local.isExchange)
    // this.isExchangeAccountSettings =
    //   global.vm.$local.isExchange == 'true' ? true : false
    // //console.log(this.isExchangeAccountSettings)

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

<style></style>

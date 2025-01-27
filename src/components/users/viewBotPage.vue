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
                <h3>
                  Bot : <b>{{ bot.name }}</b><br />
                  Pair: <b>{{ bot.marketPair }}</b><br />
                  <span v-if="bot.speed">Speed: <b>{{ tradingSpeed[bot.speed] }}</b></span>
                </h3>

                <span>
                  <button v-if="accountType == 'admin'" v-on:click="canDelete = !canDelete">
                    Can Delete
                  </button>
                </span>
              </div>
              <div style="padding: 20px; border: 3px solid ghostwhite">
                <div class="row" style="padding: 0px 15px; margin: 5px 0px; text-align: center">
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Start Bot</h5>
                    <label class="switch">
                      <input type="checkbox" :checked="bot.startTrading ? 'checked' : ''" v-model="startTrading" />
                      <span id="startTrading" class="slider round"></span>
                    </label>
                  </div>
                  <div v-if="accountType == 'admin'" class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Testing Mode</h5>
                    <label class="switch">
                      <input type="checkbox" :checked="bot.isTesting ? 'checked' : ''" v-model="isTesting" />
                      <span id="startTesting" class="slider round"></span>
                    </label>
                  </div>
                  <div v-if="accountType == 'admin'" class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Show Logs (cli)</h5>
                    <label class="switch">
                      <input type="checkbox" :checked="bot.showLogs ? 'checked' : ''" v-model="showLogs" />
                      <span id="showLogs" class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Starting Price</h5>
                    <span v-if="bot.cryptoInitialPrice >= 0"><b>{{ +(bot.cryptoInitialPrice * 1).toFixed(4) }}$</b></span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Range</h5>
                    <span v-if="bot.range"><b>
                        <span v-if="bot.botType == 'upper'">{{
                          bot.cryptoInitialPrice
                        }}</span>
                        <span v-else>
                          {{ bot.lowerBotData ?
                            bot.lowerBotData.range[0] + ' - ' : '' }}
                          {{ bot.range[0] }}
                        </span>
                        -
                        {{ bot.range[1] }}</b></span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Total Trades</h5>
                    <span><b>
                        {{ totalTrades }}
                      </b></span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Created On</h5>
                    <span><b>
                        {{ new Date(bot.createdAt).toLocaleString() }}
                      </b></span>
                  </div>
                  <!-- <div
                    class="col-md-2 col-sm-3"
                    style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    "
                  >
                    <h5>Protection Mode</h5>
                    <label class="switch">
                      <input
                        type="checkbox"
                        :checked="bot.isProtecting ? 'checked' : ''"
                        v-model="isProtecting"
                      />
                      <span class="slider round"></span>
                    </label>
                  </div> -->
                </div>
                <div class="row" style="padding: 0px 15px; margin: 5px 0px; text-align: center">
                  <div v-if="bot.isTesting" class="col-md-3 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Current Price</h5>

                    <div class="form-group row">
                      <div class="col-md-8">
                        <input type="number" class="form-control" id="cryptoCurrentPrice" name="cryptoCurrentPrice"
                          v-model="bot.cryptoCurrentPrice" aria-describedby="cryptoCurrentPriceHelp"
                          style="display: inline" step=".01" />
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success" id="setCryptoCurrentPriceBtn" v-on:click="
                          cryptoUsdArrays.cryptoCurrentPrice =
                          bot.cryptoCurrentPrice;
                        updateBot(
                          'cryptoCurrentPrice',
                          bot.cryptoCurrentPrice
                        );
                        ">
                          save
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Current Price</h5>
                    <span v-if="bot.cryptoCurrentPrice >= 0">
                      <h2 style="color: forestgreen">
                        {{ +(bot.cryptoCurrentPrice * 1).toFixed(4) }}
                      </h2>
                    </span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Total Profit</h5>
                    <span v-if="bot.totalProfit"><b> {{ (bot.totalProfit + (bot.lowerBotData ?
                      bot.lowerBotData.totalProfit : 0)).toFixed(1) }}$ </b></span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Crypto Bought</h5>
                    <span v-if="bot.remainingCrypto"><b>{{ (bot.remainingCrypto + (bot.lowerBotData ?
                      bot.lowerBotData.remainingCrypto : 0)).toFixed(4) }}
                        {{ bot.marketPair.replace("USDT", "") }}</b></span>
                  </div>
                  <div class="col-md-2 col-sm-3" style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    ">
                    <h5>Total Investment</h5>
                    <span v-if="bot.totalUsdAmount >= 0"><b>{{ bot.totalUsdAmount + (bot.lowerBotData ?
                      bot.lowerBotData.totalUsdAmount / 2 : 0) }}</b></span>
                  </div>
                  <!-- <div
                    class="col-md-2 col-sm-3"
                    style="
                      border: 2px solid;
                      border-radius: 5px;
                      margin: 0px 5px 5px 0px;
                    "
                  >
                    <h5>Total Crypto Profit</h5>
                    <span v-if="bot.profitCrypto >= 0">{{
                      bot.profitCrypto.toFixed(4)
                    }}</span>
                  </div> -->
                </div>
              </div>
            </div>

            <div class="card-header">
              <b>Most Recent Trade</b>
            </div>
            <div class="table-responsive">
              <table id="tradesTable" class="table table-bordered">
                <thead style="font-weight: 700">
                  <td>#</td>
                  <td>Order</td>
                  <td>At</td>
                  <td>USD</td>
                  <td>Crypto</td>
                  <td>Status</td>
                  <td>profit</td>
                  <td>Fee</td>
                  <td>Time</td>
                  <td v-if="bot.isTesting">Op</td>
                </thead>
                <tbody>
                  <tr v-for="(trade, index) in trades" :key="trade._id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ trade.orderType }}</td>
                    <td>{{ trade.atPrice }}</td>
                    <td>
                      {{ +trade.usdAmount.toFixed(2) }}
                    </td>
                    <td>
                      {{ +trade.cryptoAmount.toFixed(2) }}
                    </td>
                    <td>{{ trade.status }}</td>
                    <td>
                      <b v-if="trade.profit">{{ +trade.profit.toFixed(2) }}</b>$
                    </td>
                    <td>
                      <b v-if="trade.realFee">{{ +trade.realFee.toFixed(4) }}</b>$
                    </td>
                    <td>
                      {{ new Date(trade.updatedAt).toLocaleString("en-IN") }}
                    </td>
                    <td v-if="canDelete && accountType == 'admin'">
                      <button class="btn btn-danger">
                        <span v-on:click="deleteTrade(trade._id, index)">Delete</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <br />
            <div class="card-header">
              <b>Recent Logs</b>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead style="font-weight: 700">
                  <td>#</td>
                  <td>Level</td>
                  <td>Class</td>
                  <td>Function</td>
                  <td>Message</td>
                  <td>Time</td>
                  <td>Ago</td>
                </thead>
                <tbody>
                  <tr v-for="(log, index) in logs" :key="log._id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ log.level }}</td>
                    <td>{{ log.message }}</td>
                    <td>{{ log.meta.function }}</td>
                    <td>{{ log.meta.message }}</td>
                    <td>{{ new Date(log.timestamp).toLocaleString() }}</td>
                    <td>
                      {{
                        (
                          (+new Date() - +new Date(log.timestamp)) /
                          1000 /
                          60
                        ).toFixed(0)
                      }}
                      minutes ago
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- <div style="margin-top: 5px; text-align: right">
              <button class="btn btn-danger">Logout</button>
            </div> -->
          </div>
          <div class="card-header"></div>
        </div>
      </div>
    </main>
  </div>

  <!--/.Navbar -->

  <!-- <CommonFooter></CommonFooter> -->
</div></template>

<script>
import CommonHeader from '../CommonHeader.vue'
import CommonFooter from '../CommonFooter.vue'
import CommonSidebar from '../CommonSidebar.vue'

export default {
  name: "bots-page",
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar: CommonSidebar,
  },
  props: ["botId", "userId"],
  data() {
    return {
      accountType: "user",
      bot: { name: "" },
      trades: [],
      logs: [],
      totalTrades: 0,
      totalProfit: 0.0,
      totalCryptoProfit: 0.0,
      startTrading: false,
      isTesting: true,
      showLogs: true,
      isProtecting: false,
      canDelete: false,
      cryptoCurrentPrice: 0,
      cryptoUsdArrays: {},

      tradingSpeed: {
        5: "Very Slow",
        9: "Slow",
        11: "Medium",
        13: "Fast",
        19: "Very Fast",
        23: "Extreme",
        33: "Ultra Fast",
      },
    };
  },
  watch: {
    startTrading(changed) {
      //console.log("something changed in startTrading");
      //console.log(changed);
      this.updateBot("startTrading", changed);
      if (this.bot.lowerBotData) {
        this.updateBot("startTrading", changed, this.bot.lowerBotData._id);
      }
    },
    isTesting(changed) {
      //console.log("something changed in isTesting");
      //console.log(changed);
      this.updateBot("isTesting", changed);
      if (this.bot.lowerBotData) {
        this.updateBot("isTesting", changed, this.bot.lowerBotData._id);
      }
    },
    showLogs(changed) {
      ////console.log("something changed in isTesting");
      ////console.log(changed);
      this.updateBot("showLogs", changed);
      if (this.bot.lowerBotData) {
        this.updateBot("showLogs", changed, this.bot.lowerBotData._id);
      }
    },
  },
  methods: {
    sortByUpdatedAt(arr) {
      return arr.sort((a, b) => {
        return new Date(b.updatedAt) - new Date(a.updatedAt);
      })
    },
    getBot(extraChanges = true) {
      let that = this;

      let combinedBots = this.$store.state.Bots.bots;
      if (combinedBots.length > 0) {
        /* find the bot in combinedBots where _id == this.botId */
        let bot = combinedBots.find((bot) => bot._id == this.botId);
        console.log(bot)
        that.bot = bot;

        that.startTrading = bot.startTrading
        that.isTesting = bot.isTesting
        that.showLogs = bot.showLogs

        if (bot.lowerBotData && bot.lowerBotData._id) {
          that.getLogs(bot.lowerBotData._id)
          that.getTrades(bot.lowerBotData._id)

          that.startTrading = that.startTrading ? bot.lowerBotData.startTrading : false;
          that.isTesting = that.isTesting ? bot.lowerBotData.isTesting : false
          that.showLogs = that.showLogs ? bot.lowerBotData.showLogs : false
        }

      } else {

        let formData = new FormData();
        formData.append("botId", this.botId);
        this.$local
          .postRequest("/auth/bots/get-single", formData)
          .then(function (data) {
            //console.log(data);
            that.bot = data;
            that.startTrading = data.startTrading
            that.isTesting = data.isTesting
            that.showLogs = data.showLogs

            if (that.bot.lowerBotId && that.bot.lowerBotId._id) {
              formData = new FormData();
              formData.append("botId", that.bot.lowerBotId._id);
              that.$local
                .postRequest("/auth/bots/get-single", formData)
                .then(function (data) {
                  //console.log(data);
                  that.bot.lowerBotData = data;
                  that.getTrades(that.bot.lowerBotData._id)
                  that.getLogs(that.bot.lowerBotData._id)

                  if (extraChanges) {
                    that.startTrading = data.startTrading;
                    that.isTesting = data.isTesting;
                    that.showLogs = data.showLogs;
                  }
                  return;
                })
                .catch(function (msg) {
                  //console.log(msg);
                  that.$toaster.error(msg);
                  return;
                });
            }
            return;
          })
          .catch(function (msg) {
            //console.log(msg);
            that.$toaster.error(msg);
            return;
          });
      }
    },
    getTrades(botId) {
      let that = this;
      let formData = new FormData();
      formData.append("botId", botId ? botId : this.botId);
      this.$local
        .postRequest("/auth/trades/get-all", formData)
        .then(function (data) {
          ////console.log(data);
          //that.schedules = data[0].roosters;
          let preLength = that.trades.length;
          that.trades.push(...data.trades);
          if (preLength > 0) {
            that.trades = that.sortByUpdatedAt(that.trades)
          }
          that.totalTrades += data.pagination.total


          that.totalProfit = data.totalProfit;
          that.totalCryptoProfit = data.totalCryptoProfit;


          /* let totalCrypto = 0
          data.trades.forEach((trade) => {
            if (trade.orderType == 'buy') {
              totalCrypto += trade.cryptoAmount
            } else {
              totalCrypto -= trade.cryptoAmount
            }
          })
          //console.log('Total Crypto: ' + totalCrypto) */

          return;
        })
        .catch(function (msg) {
          //console.log(msg);
          that.$toaster.error("Something went wrong.");
          return;
        });
    },
    getLogs(botId) {
      let that = this;
      let formData = new FormData();
      formData.append("botId", botId ? botId : this.botId);
      console.log(botId ? botId : this.botId)
      this.$local
        .postRequest("/auth/bots/get-bot-logs", formData)
        .then(function (data) {
          //console.log(data);
          //that.schedules = data[0].roosters;
          that.logs.push(...data);
          return;
        })
        .catch(function (msg) {
          //console.log(msg);
          that.$toaster.error("Something went wrong.");
          return;
        });
    },
    updateBot(fieldName, fieldValue, botId) {
      let formData = new FormData();
      formData.append("botId", botId ? botId : this.botId);

      if (fieldName == "higherPointsSellingArray") {
        //formData = 'botId='+this.botId+'&higherPointsSellingArray[]='+fieldValue;
        for (let index = 0; index < fieldValue.length; index++) {
          const element = fieldValue[index];
          formData.append("higherPointsSellingArray[]", element);
        }
      } else {
        formData.append(fieldName, fieldValue);
      }

      let that = this;
      this.$local
        .postRequest("/auth/bots/update", formData)
        .then(function (data) {
          that.bot[fieldName] = fieldValue;
          return;
        })
        .catch(function (msg) {
          //console.log(msg);
          //that.$toaster.error(msg)
          return;
        });
    },
    deleteTrade(tradeId, index) {
      let that = this;
      let formData = new FormData();
      formData.append("tradeId", tradeId);
      this.$local
        .postRequest("/auth/trades/delete", formData)
        .then(function (data) {
          ////console.log(data);
          //that.schedules = data[0].roosters;
          that.trades.splice(index, 1);
          that.$toaster.error("Trade Deleted.");
          return;
        })
        .catch(function (msg) {
          //console.log(msg);
          that.$toaster.error("Something went wrong.");
          return;
        });
    },
  }, //
  created() {
    this.getBot();
    this.getTrades();
    this.getLogs();
    //console.log("WHO IS: ");
    //console.log(global.vm.$local.userData.accountType);
    this.accountType = global.vm.$local.userData.accountType;
  },
};
</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked+.slider {
  background-color: #2196f3;
}

input:focus+.slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked+.slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

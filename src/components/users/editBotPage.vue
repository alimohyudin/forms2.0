<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->

    <div class="">
      <div class="container">
        <div class="card">
          <div class="card-header">
            <CommonSubNav></CommonSubNav>
          </div>
          <div class="card-header"><h3>Edit Bot</h3></div>
          <div class="card-body">
            <form
              ref="form"
              id="updateBot_form"
              v-on:submit.prevent="updateBot"
            >
              <div class="row">
                <div class="col-sm-6">
                  <input type="hidden" name="botId" :value="this.bot._id" />
                  <div class="form-group">
                    <label for="name">Bot Name:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="name"
                      name="name"
                      :value="bot.name"
                      aria-describedby="nameHelp"
                    />
                  </div>
                  <div class="form-group">
                    <label for="marketPair">Market Pair:</label>
                    <select
                      class="form-control"
                      id="marketPair"
                      name="marketPair"
                      v-model="bot.marketPair"
                      aria-describedby="marketPairHelp"
                    >
                      <option
                        v-for="pair in allPairs"
                        v-bind:key="pair"
                        :value="pair"
                      >
                        {{ pair }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label
                      for="totalUsdAmount"
                      v-b-popover.hover="
                        'Total Amount is the money in USD that you want to start with e.g. Start my bot with 1000$ investment so you write 1000 here.'
                      "
                      title="What is Total Amount?"
                      >Total Amount (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="totalUsdAmount"
                      name="totalUsdAmount"
                      v-model="bot.totalUsdAmount"
                      aria-describedby="totalUsdAmountHelp"
                      required="required"
                    />
                  </div>
                  <div class="form-group">
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
                      v-model="bot.cryptoInitialPrice"
                      aria-describedby="cryptoInitialPriceHelp"
                      required="required"
                    />
                  </div>
                  <div class="form-group">
                    <label
                      for="cryptoCheckpoints"
                      v-b-popover.hover="
                        'Trading speed is how fast your bot makes trades. More Speed means More Trades but less profit'
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
                  <div class="form-group">
                    <label
                      for="cryptoPriceIntervals"
                      v-b-popover.hover="
                        'Crypto Price Interval means how far apart your trades are. If you don\'t know this then just press Calculate.'
                      "
                      title="What is interval here?"
                      >Crypto Price Interval (?):
                      <button
                        v-on:click="calculateInterval()"
                        class="btn btn-sm btn-outline-danger"
                        type="button"
                      >
                        Calculate
                      </button></label
                    >
                    <input
                      type="number"
                      class="form-control"
                      id="cryptoPriceIntervals"
                      name="cryptoPriceIntervals"
                      v-model="bot.cryptoPriceIntervals"
                      aria-describedby="cryptoPriceIntervalsHelp"
                      step=".001"
                      min="0"
                      required="required"
                    />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exchangeName">Exchange Name:</label>
                    <select
                      class="form-control"
                      id="exchangeName"
                      name="exchangeName"
                      :value="bot.exchangeName"
                      aria-describedby="exchangeNameHelp"
                    >
                      <option value="kraken">Kraken</option>
                    </select>
                  </div>
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
                      :value="bot.apiKey"
                      aria-describedby="apiKeyHelp"
                    />
                  </div>
                  <div class="form-group">
                    <label
                      for="apiSecrete"
                      v-b-popover.hover="
                        'API Key/Secret is the connection between this bot and your exchange. Allow Everything for this api except Deposit/Withdraw Funds. Please visit your Exchange for API key/secret. For testing put xyz here.'
                      "
                      title="What is API Secrete?"
                      >API Secrete (?):</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="apiSecrete"
                      name="apiSecrete"
                      :value="bot.apiSecrete"
                      aria-describedby="apiSecreteHelp"
                    />
                  </div>
                </div>
              </div>

              <Checkpoints
                :numberOfCheckpoints="bot.cryptoCheckpoints"
                :totalUsdAmount="bot.totalUsdAmount"
                :cryptoPriceIntervals="bot.cryptoPriceIntervals"
                :cryptoInitialPrice="bot.cryptoInitialPrice"
                v-model="cryptoUsdArrays"
                :areCheckpointsEdited="areCheckpointsEdited"
              ></Checkpoints>

              <!-- Start Bot Insights -->

              <bot-insights v-if="isShowingInsights" :value="bot"></bot-insights>

              <!-- End: Bot Insights -->

              <div class="row">
                <div class="col-sm-12">
                  <button
                    type="button"
                    v-b-popover.hover.topright="
                      'This will help you know your bot much better.'
                    "
                    title="Insights?"
                    v-on:click="isShowingInsights = !isShowingInsights"
                    class="btn btn-secondary"
                  >
                    <span v-if="!isShowingInsights">Get Insights (?)</span>
                    <span v-else>Close Insights (?)</span>
                  </button>
                  <span>&nbsp;</span>
                  <button v-if="!bot.startTrading || true" type="submit" class="btn btn-primary">
                    Save Changes
                  </button>
                </div>
              </div>
            </form>
            <!-- <router-link style="color:#555;  font-size: 20px;" to="/register-page">Or, create a free account...</router-link> -->
          </div>
          <div class="card-header"></div>
        </div>
      </div>
    </div>

    <!--/.Navbar -->

    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
import CommonHeader from "../CommonHeader.vue";
import CommonFooter from "../CommonFooter.vue";
import CommonSubNav from "../CommonSubNav.vue";
import Checkpoints from "../../tiny-components/checkpoints.vue";
import BotInsights from "../../tiny-components/botInsights.vue";

export default {
  name: "edit-bot-page",
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSubNav,
    Checkpoints,
    BotInsights
  },
  props: ["botId"],
  data() {
    return {
      bot: {},
      tradingSpeed: {
        5: "Very Slow",
        9: "Slow",
        11: "Medium",
        13: "Fast",
        19: "Very Fast",
        23: "Extreme",
      },
      allPairs: [
        "1INCHUSD",
        "AAVEUSD",
        "ADAUSD",
        "ALGOUSD",
        "ANKRUSD",
        "ANTUSD",
        "ATOMUSD",
        "AUDUSD",
        "AXSUSD",
        "BADGERUSD",
        "BALUSD",
        "BANDUSD",
        "BATUSD",
        "BCHUSD",
        "BNTUSD",
        "CHZUSD",
        "COMPUSD",
        "CQTUSD",
        "CRVUSD",
        "CTSIUSD",
        "DAIUSD",
        "DASHUSD",
        "DOTUSD",
        "DYDXUSD",
        "ENJUSD",
        "EOSUSD",
        "EWTUSD",
        "FILUSD",
        "FLOWUSD",
        "GHSTUSD",
        "GNOUSD",
        "GRTUSD",
        "ICXUSD",
        "INJUSD",
        "KARUSD",
        "KAVAUSD",
        "KEEPUSD",
        "KNCUSD",
        "KSMUSD",
        "LINKUSD",
        "LPTUSD",
        "LRCUSD",
        "LSKUSD",
        "MANAUSD",
        "MATICUSD",
        "MINAUSD",
        "MIRUSD",
        "MKRUSD",
        "MOVRUSD",
        "NANOUSD",
        "OCEANUSD",
        "OGNUSD",
        "OMGUSD",
        "OXTUSD",
        "PAXGUSD",
        "PERPUSD",
        "QTUMUSD",
        "RARIUSD",
        "RENUSD",
        "REPV2USD",
        "SANDUSD",
        "SCUSD",
        "SDNUSD",
        "SNXUSD",
        "SOLUSD",
        "SRMUSD",
        "STORJUSD",
        "SUSHIUSD",
        "TBTCUSD",
        "TRXUSD",
        "UNIUSD",
        "USDCUSD",
        "USDTZUSD",
        "WAVESUSD",
        "WBTCUSD",
        "XDGUSD",
        "XETCZUSD",
        "XETHZUSD",
        "XLTCZUSD",
        "XMLNZUSD",
        "XREPZUSD",
        "XTZUSD",
        "XXBTZUSD",
        "XXLMZUSD",
        "XXMRZUSD",
        "XXRPZUSD",
        "XZECZUSD",
        "YFIUSD",
        "ZEURZUSD",
        "ZGBPZUSD",
        "ZRXUSD",
      ],
      cryptoUsdArrays: {},
      areCheckpointsEdited: false,
      isShowingInsights: false,
    };
  },
  watch: {
    cryptoUsdArrays(changed) {
      this.bot.minPrice = changed.cryptoCheckpointsArray[0];
      this.bot.maxPrice = changed.cryptoCheckpointsArray[changed.cryptoCheckpointsArray.length-1];
      this.bot.usdUsagePerPoint = changed.buyWithUsdAmountArray[0];
    },
  },
  methods: {
    getBot() {
      let that = this;

      let formData = new FormData();
      formData.append("botId", this.botId);
      this.$local
        .postRequest("/auth/bots/get-single", formData)
        .then(function (data) {
          console.log(data);
          that.bot = data;
          that.cryptoUsdArrays = {
            cryptoCheckpointsArray: data.cryptoCheckpointsArray,
          };
          return;
        })
        .catch(function (msg) {
          console.log(msg);
          that.$toaster.error(msg);
          return;
        });
    },
    updateBot() {
      let formData = $("#updateBot_form").serialize();
      formData +=
        "&cryptoCheckpointsArray=" +
        this.cryptoUsdArrays.cryptoCheckpointsArray;
      formData +=
        "&buyWithUsdAmountArray=" + this.cryptoUsdArrays.buyWithUsdAmountArray;
      if (this.cryptoUsdArrays.buyWithUsdAmountArray[0]) {
        this.bot.usdUsagePerPoint =
          this.cryptoUsdArrays.buyWithUsdAmountArray[0];
        formData +=
          "&usdUsagePerPoint=" + this.cryptoUsdArrays.buyWithUsdAmountArray[0];
      }
      console.log(formData);
      let that = this;
      this.$local
        .postRequest("/auth/bots/update", formData)
        .then(function (data) {
          console.log(data);
          //that.timesheets.push(data[0]);

          that.$toaster.success("Bot updated!");
          that.$router.push({ name: "bots-page" });
          return;
        })
        .catch(function (msg) {
          console.log(msg);
          that.$toaster.error(msg);
          return;
        });
    },
    calculateInterval() {
      this.bot.maxPrice = (4 / 3) * this.bot.cryptoInitialPrice;
      this.bot.cryptoPriceIntervals = (
        (this.bot.maxPrice - this.bot.maxPrice / 2) /
        (this.bot.cryptoCheckpoints - 1)
      ).toFixed(3);
    },
  },
  created: function () {
    this.getBot();
  },
};
</script>

<style>
ol {
  text-align: left;
}
</style>

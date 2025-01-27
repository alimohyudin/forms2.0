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
            <h3><i class="bi bi-graph-up"></i> Animated Graph</h3>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <label for="mainBot">Bot: </label>
                <select class="form-select" name="mainBot" id="mainBot" v-model="mainBot" v-on:change="botChanged">
                  <template v-for="(bot, index) in bots">
                    <option v-if="bot.botType == 'normal'" :value="bot._id">{{ bot.name }}
                    </option>
                  </template>
                </select>
              </div>
              <div class="col-md-6 col-sm-12">
                <label for="lowerBot">Lower Bot: </label>
                <select class="form-select" name="lowerBot" id="lowerBot" disabled>
                  <template v-for="(bot, index) in bots">
                    <option :value="bot._id" :selected="bot._id == lowerBot">{{ bot.name }}</option>
                  </template>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="card-body" style="margin-top: 10px;">
                <h3>Pair: {{ selectedBotPair }}</h3>
              </div>
            </div>
            <div class="row" style="margin-top: 10px;">
              <TradesAnimationGraph :data="trades" :config="{ totalInvestment: totalInvestment }" :key="trades.length">
              </TradesAnimationGraph>
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
import TradesAnimationGraph from '../../tiny-components/tradesAnimationGraph/index.vue'

export default {
  name: 'user-trades-graph-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar: CommonSidebar,
    TradesAnimationGraph: TradesAnimationGraph
  },
  props: ['userId'],
  data() {
    return {

      bots: [],
      trades: [],
      mainBot: '',
      lowerBot: '',
      loading: true,
      selectedBotPair: '',
      totalInvestment: 0,
    }
  },
  watch: {
    mainBot(e, v) {
      let that = this;
      console.log("mainBot Changed", e, v)
      this.bots.forEach(bot => {
        if (bot._id == this.mainBot) {
          that.selectedBotPair = bot.marketPair;
          return;
        }
      });
    }
  },
  computed: {
  },
  methods: {
    getTrades() {
      this.loading = true;
      let that = this
      let formData = new FormData()

      if (this.userId) {
        formData.append('userId', this.userId)
      } else {
        formData.append('userId', global.vm.$local.userData._id)
      }
      formData.append('botId', this.mainBot)
      if (this.lowerBot != '')
        formData.append('botId2', this.lowerBot)


      this.$local
        .postRequest('/auth/trades/get-all-simplified', formData)
        .then(function (data) {
          console.log(data);
          that.trades = data.trades;
          that.loading = false;

        })
        .catch(function (msg) {
          console.log(msg)
          //that.$toaster.error("Something went wrong.");
          return
        })
    },
    getBots() {
      let that = this
      let formData = new FormData()

      if (this.userId) {
        formData.append('userId', this.userId)
      }

      this.$local
        .postRequest('/auth/bots/get-all', formData)
        .then(function (data) {
          console.log(data.bots)
          //that.bots = data.bots
          let mybots = []
          let mainBot = '';
          let lowerBot = '';
          for (let i = 0; i < data.bots.length; i++) {
            const bot = data.bots[i];
            if (bot.name.toString().toLowerCase().includes('api') && bot.name.toString().toLowerCase().includes('only')) {

            } else {
              console.log(mainBot, lowerBot)
              if (mainBot == '') {
                mainBot = bot._id;
                that.totalInvestment = bot.totalUsdAmount * 1;
                that.selectedBotPair = bot.marketPair;
                console.log(that.tot)
              }
              if (mainBot != '' && bot.oldBot == mainBot && bot.botType == 'lower') {
                lowerBot = bot._id;
                console.log("lowerBot: ", bot)
                that.totalInvestment += bot.realTotalUsdAmount ? bot.realTotalUsdAmount * 1 / 2 : bot.totalUsdAmount * 1 / 2;
              }


              mybots.push(bot)
            }
          }
          console.log("totalinvestment: ", that.totalInvestment)
          that.bots = mybots;
          that.mainBot = mainBot;
          that.lowerBot = lowerBot;
          that.loading = false

          if (mainBot != '') {
            that.getTrades();
          }
        })
        .catch(function (msg) {
          console.log(msg)
          that.$toaster.error("Something went wrong.");
          that.loading = false
          return
        })
    },
    botChanged() {
      let that = this;
      //console.log("value changed", this.mainBot)

      let mainBot = this.mainBot;
      let lowerBot = '';
      that.totalInvestment = 0
      for (let i = 0; i < this.bots.length; i++) {
        const bot = this.bots[i];
        if (bot.name.toString().toLowerCase().includes('api') && bot.name.toString().toLowerCase().includes('only')) {

        } else {
          //console.log(mainBot, lowerBot)
          if(bot._id == mainBot){
            that.totalInvestment = bot.totalUsdAmount * 1;            
          }else if (mainBot != '' && bot.oldBot == mainBot && bot.botType == 'lower') {
            lowerBot = bot._id;
            //console.log("lowerBot: ", bot)
            that.totalInvestment += bot.realTotalUsdAmount ? bot.realTotalUsdAmount * 1 / 2 : bot.totalUsdAmount * 1 / 2;
          }
        }
      }
      //console.log("totalinvestment: ", that.totalInvestment)
      //that.mainBot = mainBot;
      that.lowerBot = lowerBot;
      this.getTrades();
    }
  }, //
  mounted() {
    console.log("mounted")
  },
  created() {
    let that = this
    console.log("started")
    //this.loading = false;
    this.getBots()


    console.log('something on socket')
    console.log(global.vm.$socket)
  },
}
</script>

<style></style>

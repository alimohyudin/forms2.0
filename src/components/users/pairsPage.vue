<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main">
      <div class="container">
        <div class="card">
          <!-- <div class="card-header">
            <CommonSubNav></CommonSubNav>
          </div> -->
          <div class="card-header">
            <h3><i class="bi bi-currency-bitcoin"></i>&nbsp; Available Pairs</h3>
          </div>
          <div class="card-body">
            <div style="margin-bottom: 5px">

              <button style="float: right;" class="btn btn-secondary" v-on:click="getPairs()">
                Refresh
              </button>
            </div>
            <div v-if="pairs.length > 0">
              <div class="card-header">
              </div>
              <br />
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead style="font-weight: 700">
                    <td>#</td>
                    <td>Pair</td>
                    <td>Price</td>
                    <td>Change ({{
                      startInterval > 60
                      ? startInterval / 60 > 60
                        ? (startInterval / 3600).toFixed(0) + "hr"
                        : (startInterval / 60).toFixed(0) + "min"
                      : startInterval + "sec"
                    }})</td>
                    <td>exchange</td>
                    <!-- <td>Price Decimals</td>
                    <td>Vol Decimals</td>
                    <td>op</td> -->
                  </thead>
                  <tbody>
                    <tr v-for="(pair, index) in pairs" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ pair.pair }}</td>
                      <td :style="'background-color:' + pair.price_color">
                        {{ pair.price }}
                      </td>
                      <td :style="'color: ' + (pair.change_price > 0 ? 'green' : 'red')
                        " style="font-weight: 800">
                        {{
                          pair.change_price
                          ? pair.change_price.toFixed(pair.pair_decimals)
                          : 0
                        }}
                      </td>

                      <td>{{ pair.exchange }}</td>
                      <!-- <td>{{ pair.pair_decimals }}</td>
                      <td>{{ pair.lot_decimals }}</td>

                      <td>
                        <button v-on:click="deletePair(pair._id, index)">
                          Delete
                        </button>
                      </td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- <div style="margin-top: 5px; text-align: right">
              <button class="btn btn-danger">Logout</button>
            </div> -->
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
import CommonSubNav from '../CommonSubNav.vue'

import CommonSidebar from '../CommonSidebar.vue'

export default {
  name: 'user-pairs-page',
  components: {
    CommonHeader: CommonHeader,
    CommonFooter: CommonFooter,
    CommonSidebar: CommonSidebar,
  },
  props: ['userId'],
  data() {
    return {
      pairs: [],
      startDate: "",
      startInterval: 0,
      pricingInterval: 0,
    }
  },
  watch: {},
  computed: {

  },
  methods: {
    getPairs() {
      let that = this;

      this.$local
        .postRequest("/auth/pairs/get-all", {})
        .then(function (data) {
          //console.log('Get pairs: ')
          //console.log(data)

          /* if price increases then change color to green and if price decreases then change color to red */
          for (let i = 0; i < data.pairs.length; i++) {
            if (that.pairs.length > 0) {
              if (data.pairs[i].price > that.pairs[i].price) {
                data.pairs[i].price_color = "#80b580"; //green
              } else if (data.pairs[i].price < that.pairs[i].price) {
                data.pairs[i].price_color = "#f25656"; //red
              } else {
                data.pairs[i].price_color = "#bababa"; //black
              }
              data.pairs[i].lowest_price = that.pairs[i].lowest_price;
              data.pairs[i].change_price =
                data.pairs[i].price - that.pairs[i].lowest_price;
            } else {
              /* assign lowest_price values */
              data.pairs[i].lowest_price = data.pairs[i].price;
            }
          }
          if (that.startDate == "" && data.pairs.length > 0) {
            that.startDate = data.pairs[data.pairs.length - 1].updatedAt;
          }
          that.startInterval = (
            (+new Date() - +new Date(that.startDate)) /
            1000
          ).toFixed(0);

          that.pairs = data.pairs;
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
    let that = this
    this.getPairs()
    this.pricingInterval = setInterval(function () {
      that.getPairs();
    }, 2000);

    console.log('something on socket')
    console.log(global.vm.$socket)
  },
  beforeDestroy(){
    console.error("unmounted pairspage")
    clearInterval(this.pricingInterval)
  }
}
</script>

<style></style>

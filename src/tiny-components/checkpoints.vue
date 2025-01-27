<template>
  <div>
    <div v-if="onlyView != 'true'" style="display: none;">
      <div
        v-for="index in cryptoCheckpointsArray.length"
        v-bind:key="index"
        class="box"
      >
        <span
          :class="
            index == (cryptoCheckpointsArray.length + 1) / 2
              ? 'center'
              : 'normal'
          "
        >
          <span
            v-if="index == (cryptoCheckpointsArray.length + 1) / 2"
            style="font-weight: 1000"
          >
            Starting:
          </span>
          <span class="at">
            At:
            <span class="value">{{ cryptoCheckpointsArray[index - 1] }}</span>
          </span>
          <span class="usd">
            USD:
            <span class="value">{{
              buyWithUsdAmountArray[index - 1].toFixed(0)
            }}</span>
          </span>
        </span>
      </div>

      <div v-if="isEditingCheckpoints" style="display: none;">
        <div
          v-for="index in cryptoCheckpointsArray.length"
          v-bind:key="'edit' + index"
          class="box"
        >
          <span
            :class="
              index == (cryptoCheckpointsArray.length + 1) / 2
                ? 'center'
                : 'normal'
            "
          >
            <span
              v-if="index == (cryptoCheckpointsArray.length + 1) / 2"
              style="font-weight: 1000"
            >
              Starting:</span
            >
            <span class="at">
              At:
              <span class="value">
                <input
                  class="form-control"
                  type="number"
                  v-model="cryptoCheckpointsArray[index - 1]"
                  step=".01"
                />
              </span>
            </span>
            <!-- <span class="usd">
            USD:
            <span class="value">{{
              buyWithUsdAmountArray[index - 1].toFixed(0)
            }}</span>
          </span> -->
          </span>
        </div>
      </div>
      <!-- <button
        v-if="!isEditingCheckpoints && numberOfCheckpoints > 0"
        style="float: right"
        class="btn btn-secondary"
        v-on:click="isEditingCheckpoints = true"
        type="button"
      >
        Edit Checkpoints
      </button>
      <button
        v-if="isEditingCheckpoints"
        style="float: right"
        class="btn btn-secondary"
        v-on:click="editCheckpoints"
        type="button"
      >
        Save checkpoints
      </button> -->
    </div>
    <div v-else style="display: none;">
      <div
        v-for="index in cryptoCheckpointsArray.length"
        v-bind:key="index"
        class="box"
      >
        <span :class="
            (cryptoCurrentPrice*1 > (cryptoCheckpointsArray[index - 1]*1 - cryptoPadding*1) &&
            cryptoCurrentPrice*1 <= (cryptoCheckpointsArray[index - 1]*1 + cryptoPadding*1))
              ? 'center'
              : 'normal'
          ">
          <!-- <span
            v-if="index == (cryptoCheckpointsArray.length + 1) / 2"
            style="font-weight: 1000"
          >
            Starting: 
          </span> -->
          <span class="at">
            At:
            <span class="value">{{
              cryptoCheckpointsArray[index - 1]
            }}</span>
          </span>
          <span class="usd">
            USD:
            <span class="value">{{
              buyWithUsdAmountArray[index - 1].toFixed(0)
            }}</span>
          </span>
        </span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: [
    "numberOfCheckpoints",
    "totalUsdAmount",
    "cryptoPriceIntervals",
    "cryptoInitialPrice",
    "cryptoCurrentPrice",
    "onlyView",
    "value",
  ],
  data() {
    return {
      cryptoCheckpointsArray: [],
      buyWithUsdAmountArray: [],
      content: {},
      cryptoPadding: 0,
      isEditingCheckpoints: false,
    };
  },
  watch: {
    value(changed) {
      console.log("CHECKPOINTS_CP: UPDATED");
      //console.log(this.content.cryptoCheckpointsArray);
      this.cryptoCheckpointsArray = changed['cryptoCheckpointsArray'];
      this.buyWithUsdAmountArray = changed['buyWithUsdAmountArray'];

      this.cryptoPadding = ((this.cryptoCheckpointsArray[1] - this.cryptoCheckpointsArray[0])/2).toFixed(2);
    },
    numberOfCheckpoints() {
      this.startGettingCheckpoints();
    },
    totalUsdAmount() {
      this.startGettingCheckpoints();
    },
    cryptoPriceIntervals() {
      this.startGettingCheckpoints();
    },
    cryptoInitialPrice() {
      this.startGettingCheckpoints();
    },
    cryptoCurrentPrice(changed){
      console.log("Current Price Changed")
      console.log(changed)
    }
  },
  components: {},
  methods: {
    editCheckpoints() {
      this.isEditingCheckpoints = false;
      this.$emit("input", {
        cryptoCheckpointsArray: this.cryptoCheckpointsArray,
        buyWithUsdAmountArray: this.buyWithUsdAmountArray,
      });
    },
    startGettingCheckpoints() {
      if (
        this.numberOfCheckpoints > 0 &&
        this.totalUsdAmount > 0 &&
        this.cryptoPriceIntervals > 0 &&
        this.cryptoInitialPrice > 0
      ) {
        console.log("Checkpoints: " + this.numberOfCheckpoints);
        let allCheckPoints = this.getAllCheckPoints();
        this.cryptoCheckpointsArray = allCheckPoints.btcusdCheckPoints;
        this.buyWithUsdAmountArray = allCheckPoints.buyWithUsdAmount;
        this.$emit("input", {
          cryptoCheckpointsArray: this.cryptoCheckpointsArray,
          buyWithUsdAmountArray: this.buyWithUsdAmountArray,
        });
      }
    },

    getAllCheckPoints() {
      let checkPoint = [];
      let buyWithUsdAmount = [];
      let bot = {
        cryptoCheckpoints: (this.numberOfCheckpoints * 1 + 1) / 2,
        cryptoPriceIntervals: this.cryptoPriceIntervals * 1,
        totalUsdAmount: this.totalUsdAmount * 1,
        cryptoInitialPrice: this.cryptoInitialPrice * 1,
      };
      bot.initialUsd = this.totalUsdAmount / 2;
      bot.usdUsagePerPoint =
        this.totalUsdAmount / 2 / (bot.cryptoCheckpoints - 1);

      console.log("starting:: getAllCheckPoints");
      console.log(bot);
      for (let index = bot.cryptoCheckpoints - 1; index >= 0; index--) {
        if (index == bot.cryptoCheckpoints - 1) {
          checkPoint[index] = bot.cryptoInitialPrice;
          buyWithUsdAmount[index] = bot.initialUsd;
        } else {
          checkPoint[index] =
            (checkPoint[index + 1] * 1 - bot.cryptoPriceIntervals).toFixed(4) *
            1;
          let increasingIndex =
            bot.cryptoCheckpoints - 1 + (bot.cryptoCheckpoints - 1 - index);
          checkPoint[increasingIndex] =
            (
              checkPoint[increasingIndex - 1] * 1 +
              bot.cryptoPriceIntervals
            ).toFixed(4) * 1;

          console.log(checkPoint[index + 1]);

          buyWithUsdAmount[index] = bot.usdUsagePerPoint;
          buyWithUsdAmount[increasingIndex] = bot.usdUsagePerPoint;
        }
      }

      console.log(checkPoint);
      console.log(buyWithUsdAmount);
      return {
        btcusdCheckPoints: checkPoint,
        buyWithUsdAmount: buyWithUsdAmount,
      };
    },
  },
};
</script>
<style scoped>
.box {
  border: 2px solid;
  display: inline-block;
  width: 8%;
  text-align: center;
  margin: 3px;
}
.center {
  border: 5px solid green;
  display: block;
}
.at {
  display: block;
}
.usd {
  display: block;
}
.value {
  font-weight: 700;
}
</style>
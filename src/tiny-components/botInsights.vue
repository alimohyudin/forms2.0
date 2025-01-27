<template>
  <div class="row">
    <div class="col-sm-12">
      <div>
        <b-card-group deck>
          <b-card
            border-variant="secondary"
            header="Bot Insights"
            header-bg-variant="secondary"
            header-text-variant="white"
            align="center"
          >
            <b-card-group deck>
              <b-card
                bg-variant="primary"
                text-variant="white"
                header="Me"
                class="text-center"
              >
                <b-card-text
                  >Hi, I'm your {{ bot.marketPair }} Bot,<br />
                  <ol>
                    <li>
                      I will start when price hits
                      <b>{{ bot.cryptoInitialPrice }}$</b>
                    </li>
                    <li>I will never let your money sleep</li>
                  </ol>
                </b-card-text>
              </b-card>

              <b-card
                bg-variant="secondary"
                text-variant="white"
                header="Range"
                class="text-center"
              >
                <b-card-text>
                  <ol>
                    <li>
                      My range is between <b>{{ bot.minPrice }}$ -
                      {{ bot.maxPrice }}$</b>
                    </li>
                    <li>I never go out of this range.</li>
                    <li>
                      If price goes above <b>{{ bot.maxPrice }}$</b>
                      <ol>
                        <li>You can stop me and leave with profit</li>
                        <li>
                          Or Leave me running and start new bot at price above
                          <b>{{ bot.maxPrice }}$</b> so that I can cover you when
                          market crashes later
                        </li>
                      </ol>
                    </li>
                    <li>
                      If price goes below <b>{{ bot.minPrice }}$</b>
                      <ol>
                        <li>You should NOT stop me</li>
                        <li>
                          You can Start new bot at price below
                          <b>{{ bot.minPrice }}$</b>
                        </li>
                      </ol>
                    </li>
                  </ol>
                </b-card-text>
              </b-card>

              <b-card
                bg-variant="success"
                text-variant="white"
                header="Success"
                class="text-center"
              >
                <b-card-text>
                  <ol>
                    <li>I will never lose and make you win all your trades.</li>
                    <li>I generate passive income</li>
                    <li>
                      With your <b>{{ bot.totalUsdAmount }}$</b> and on Medium Speed I
                      can make
                      <ul>
                        <li>
                          Average Month: <b>~{{ (bot.totalUsdAmount * 3) / 10 }}$</b>
                        </li>
                        <li>
                          Great Month: <b>~{{ (bot.totalUsdAmount * 2) / 5 }}$</b>
                        </li>
                        <li>
                          Worst Month: <b>~{{ (bot.totalUsdAmount * 3) / 20 }}$</b>
                        </li>
                      </ul>
                    </li>
                    <li>
                      You chose
                      <b>{{ tradingSpeed[bot.cryptoCheckpoints] }}</b>
                      speed
                      <ul>
                        <li>
                          It will make <b>{{ bot.profit }}$</b> on average per trade
                        </li>
                      </ul>
                    </li>
                  </ol>
                </b-card-text>
              </b-card>
            </b-card-group>
            <div class="mt-3">
              <b-card-group deck>
                <b-card
                  bg-variant="info"
                  text-variant="white"
                  header="Info"
                  class="text-center"
                >
                  <b-card-text>
                    <ol>
                      <li>
                        If you make changes to above fields
                        <ol>
                          <li>
                            Make sure you calculate your <b>'Crypto Price Interval'</b>
                            again
                          </li>
                          <li>
                            Then close Insights from below and open it again
                          </li>
                        </ol>
                      </li>
                      <li>I don't have any Withdraw operation built in me</li>
                      <li>There will be no withdraw request from me ever.</li>
                      <li>
                        My creator has a separte mechanism for payment to be
                        requested.
                      </li>
                    </ol>
                  </b-card-text>
                </b-card>

                <b-card
                  bg-variant="warning"
                  text-variant="white"
                  header="Warning"
                  class="text-center"
                >
                  <b-card-text>
                    I will Cover you till 0$ but follow me:
                    <ul>
                      <li>
                        Save <b>{{ 2 * bot.totalUsdAmount }}$</b> for crash as backup
                        <ol>
                          <li>
                            When first crash occurs and price goes below
                            <b>{{ bot.minPrice }}$</b> then you can create new bot with
                            another <b>{{ bot.totalUsdAmount }}$</b> with same values
                            and new starting price.
                          </li>
                          <li>
                            When second crash occurs after your second bot then
                            you can start a 3rd bot with same values and new
                            starting price and it will cover you to the 0$
                          </li>
                        </ol>
                      </li>
                    </ul>
                  </b-card-text>
                </b-card>

                <b-card
                  bg-variant="danger"
                  text-variant="white"
                  header="Danger"
                  class="text-center"
                >
                  <b-card-text>
                    <ul>
                      <li>
                        Please don't Edit me once I create any trade.
                        <ol>
                          <li>
                            Old trades will stay hanging and keep bothering me
                          </li>
                          <li>
                            Edit ONLY IF there is no trade in the trades list
                          </li>
                          <li>
                            Visit Edit Page only if you need to view my values
                            and go back WITHOUT Saving Changes
                          </li>
                          <li>I'll become more intelligent with time</li>
                        </ol>
                      </li>
                    </ul>
                  </b-card-text>
                </b-card>
              </b-card-group>
            </div>
          </b-card>
        </b-card-group>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["value"],
  data() {
    return {
      bot: this.value,
      tradingSpeed: {
        5: "Very Slow",
        9: "Slow",
        11: "Medium",
        13: "Fast",
        19: "Very Fast",
        23: "Extreme",
      },
    };
  },
  watch: {
    value(changed) {
      console.log("BOT INSIGNTS: value changed");
      this.bot = changed;

      this.calculateInsights();
    },
  },
  methods: {
    calculateInsights() {
      console.log("CALCULATING INSIGHTS22222:");

      this.bot.profit =
        (+this.bot.usdUsagePerPoint / +this.bot.cryptoInitialPrice) *
          (+this.bot.cryptoInitialPrice + 1 * +this.bot.cryptoPriceIntervals) -
        +this.bot.usdUsagePerPoint;

      console.log(this.bot.cryptoInitialPrice);
      console.log(this.bot.usdUsagePerPoint);
      this.bot.profit = this.bot.profit.toFixed(3);

      this.isShowingInsights = true;
    },
  },
  created() {
    console.log("botinsight created");
    this.calculateInsights();
  },
  mounted() {
    console.log("botinsight mounted");
    this.calculateInsights();
  },
};
</script>
<template>
    <div style="position: relative;">
        <!-- <div style="background-color: aliceblue;">
            <input type="text" v-model="chartValue"> <button @click="displayChart">Update</button>
            <select name="chartSelection" id="chartSelection">
                <option value="">{{mydata}}</option>
            </select>
        </div> -->
        <div id='timeline'></div>
    </div>
</template>

<script>
import * as d3 from 'd3'


//import BinanceChart from '../graph/timeline'
import CATradeChart from '../tradesAnimationGraph/timeline'

export default {
    props: {
        data: {
            type: Array
        },
        config: {
            type: Object,
            default: () => { }
        }
    },
    data() {
        return {
            chartValue: ""
        }
    },
    methods: {
        displayChart() {
            let that = this;
            this.chartValue = this.chartValue != '' ? this.chartValue : 'atPrice'
            //d3.json('http://localhost:8080/muzamilBot1.trades.json').then(function (d) {
            //console.log(d)
            let d = this.data;
            let myData = [];
            for (let index = 0; index < d.length; index++) {
                const myd = d[index];

                let value = 0
                if (that.chartValue == 'usdAmount')
                    value = Math.floor(myd[that.chartValue])
                else
                    value = 1 * myd[that.chartValue]
                //console.log(that.chartValue, value)
                //if(myd['executedQty']*1 > 0)
                myData.push({ date: new Date(myd['createdAt']), value: value, price: myd['atPrice'], orderType: myd['orderType'] + '', usdAmount: myd['usdAmount'].toFixed(2), profit: myd['profit'], cryptoAmount: myd['cryptoAmount'] * 1 });
            }

            //console.log(myData)

            d3.select('#timeline')
                .datum(myData)
                .call(CATradeChart({
                    chart: "line",
                    widthResizable: true,
                    viewHeight: 800,
                    margin: {
                        top: 0,
                        bottom: 30,
                        left: 15,
                        right: 15
                    },
                    onEventClick: that.config?.onEventClick,
                    onDataUpdate: that.config?.onDataUpdate,
                    totalInvestment: that.config?.totalInvestment
                }))
            //})
        },
        displayChart2() {
            let that = this;
            this.chartValue = this.chartValue != '' ? this.chartValue : 'price'
            d3.json('http://localhost:8080/muzamil-binance-trades.json').then(function (d) {
                //console.log(d)
                let myData = [];
                for (let index = 0; index < d.length; index++) {
                    const myd = d[index];

                    let value = 0
                    if (that.chartValue == 'usdAmount')
                        value = Math.floor(myd[that.chartValue])
                    else
                        value = 1 * myd[that.chartValue]
                    //console.log(that.chartValue, value)
                    if (myd['executedQty'] * 1 > 0)
                        myData.push({ date: new Date(myd['updateTime'] * 1), value: value, price: myd['price'], orderType: myd['side'], usdAmount: myd['origQty'], profit: 0 });
                    //myData.push({ date: new Date(myd['updateTime']*1), high_price: myd['price']*1, low_price: 0, type: myd['side'] });
                }

                //console.log(myData)

                d3.select('#timeline')
                    .datum(myData)
                    .call(CATradeChart({
                        chart: "line",
                        widthResizable: true,
                        viewHeight: 800,
                        margin: {
                            top: 0,
                            bottom: 30,
                            left: 15,
                            right: 15
                        },
                        onEventClick: that.config?.onEventClick,
                        onDataUpdate: that.config?.onDataUpdate
                    }))
            })
        },
    },
    mounted() {
        let that = this;
        this.displayChart()
    }
}
</script>

<style>
.tooltip {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    pointer-events: none;
}

.totalValue {
    opacity: 1;
    position: absolute;
    top: 0px;
    right: 0px;
    margin: 50px;
}

.stopAnimationBtn {
    opacity: 1;
    position: absolute;
    top: 0px;
    right: 0px;
    margin: 10px;
}

.chartInfo{
    background-color: rgba(220, 220, 220, 0.5);
    border-radius: 10px 10px 0px 0px;
    padding: 20px;
}
.chartInfo-secondary{
    background-color: rgba(220, 220, 220, 0.5);
    padding-left: 20px;
    border-radius: 0px 0px 10px 10px;
    padding-bottom: 10px;
}

.chartInfo, .chartInfo-secondary{
    font-size: 12px;
}
.chartInfo > span{
    font-size: 22px;
}
</style>
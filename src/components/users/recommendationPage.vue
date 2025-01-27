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
                        <h3><i class="bi bi-umbrella"></i>&nbsp; Recommendations</h3>
                    </div>
                    <div class="card-body">

                        <h2>Table Information</h2>
                        <p>The following information shows the profits generated previous month for each coin at a specific
                            speed. The profit is based on 1000$ investment without two-bot strategy (no lower bot created).
                        </p>
                        <p> Symbol <span style="color: yellow;">⚡</span> in the following table means it is recommended for
                            long term.</p>

                        <div id="profitTable" class="table-responsive"></div>

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
            tableHeaders: [],
            tableRows: [],
        }
    },
    watch: {},
    computed: {

    },
    methods: {
        prepareTableData() {
            let that = this;
            const dataArray = [
                { "symbol": "BTCUSDT", "profit": 40.9, "speed": "very slow" },
                { "symbol": "BTCUSDT", "profit": 39.8, "speed": "slow" },
                { "symbol": "BTCUSDT", "profit": 25.3, "speed": "medium" },
                { "symbol": "BTCUSDT", "profit": 30.9, "speed": "fast" },
                { "symbol": "BTCUSDT", "profit": 30.3, "speed": "very fast" },
                { "symbol": "BTCUSDT", "profit": 34.9, "speed": "extreme" },
                { "symbol": "BTCUSDT", "profit": 30.4, "speed": "ultra fast" },
                { "symbol": "ETHUSDT", "profit": 40.9, "speed": "very slow" },
                { "symbol": "ETHUSDT", "profit": 39.8, "speed": "slow" },
                { "symbol": "ETHUSDT", "profit": 37.4, "speed": "medium" },
                { "symbol": "ETHUSDT", "profit": 39.3, "speed": "fast" },
                { "symbol": "ETHUSDT", "profit": 39, "speed": "very fast" },
                { "symbol": "ETHUSDT", "profit": 39.9, "speed": "extreme" },
                { "symbol": "ETHUSDT", "profit": 40.2, "speed": "ultra fast" },
                { "symbol": "SOLUSDT", "profit": 283.7, "speed": "very slow" },
                { "symbol": "SOLUSDT", "profit": 199.9, "speed": "slow" },
                { "symbol": "SOLUSDT", "profit": 178, "speed": "medium" },
                { "symbol": "SOLUSDT", "profit": 166.6, "speed": "fast" },
                { "symbol": "SOLUSDT", "profit": 156.3, "speed": "very fast" },
                { "symbol": "SOLUSDT", "profit": 150.5, "speed": "extreme" },
                { "symbol": "SOLUSDT", "profit": 132.9, "speed": "ultra fast" },
                { "symbol": "LTCUSDT", "profit": 0, "speed": "very slow" },
                { "symbol": "LTCUSDT", "profit": 19.9, "speed": "slow" },
                { "symbol": "LTCUSDT", "profit": 25.6, "speed": "medium" },
                { "symbol": "LTCUSDT", "profit": 21.8, "speed": "fast" },
                { "symbol": "LTCUSDT", "profit": 19, "speed": "very fast" },
                { "symbol": "LTCUSDT", "profit": 24.2, "speed": "extreme" },
                { "symbol": "LTCUSDT", "profit": 18.3, "speed": "ultra fast" },
                { "symbol": "RUNEUSDT", "profit": 48.7, "speed": "very slow" },
                { "symbol": "RUNEUSDT", "profit": 45, "speed": "slow" },
                { "symbol": "RUNEUSDT", "profit": 58.4, "speed": "medium" },
                { "symbol": "RUNEUSDT", "profit": 58, "speed": "fast" },
                { "symbol": "RUNEUSDT", "profit": 50.1, "speed": "very fast" },
                { "symbol": "RUNEUSDT", "profit": 52.1, "speed": "extreme" },
                { "symbol": "RUNEUSDT", "profit": 22.7, "speed": "ultra fast" },
                { "symbol": "APEUSDT", "profit": 123, "speed": "very slow" },
                { "symbol": "APEUSDT", "profit": 79.5, "speed": "slow" },
                { "symbol": "APEUSDT", "profit": 75.8, "speed": "medium" },
                { "symbol": "APEUSDT", "profit": 86.3, "speed": "fast" },
                { "symbol": "APEUSDT", "profit": 64.7, "speed": "very fast" },
                { "symbol": "APEUSDT", "profit": 63.2, "speed": "extreme" },
                { "symbol": "APEUSDT", "profit": 60.8, "speed": "ultra fast" },
                { "symbol": "THETAUSDT", "profit": 240.8, "speed": "very slow" },
                { "symbol": "THETAUSDT", "profit": 263.3, "speed": "slow" },
                { "symbol": "THETAUSDT", "profit": 237.3, "speed": "medium" },
                { "symbol": "THETAUSDT", "profit": 231.4, "speed": "fast" },
                { "symbol": "THETAUSDT", "profit": 183.7, "speed": "very fast" },
                { "symbol": "THETAUSDT", "profit": 187.5, "speed": "extreme" },
                { "symbol": "THETAUSDT", "profit": 161.1, "speed": "ultra fast" },
                { "symbol": "MATICUSDT", "profit": 164.9, "speed": "very slow" },
                { "symbol": "MATICUSDT", "profit": 184.9, "speed": "slow" },
                { "symbol": "MATICUSDT", "profit": 185.3, "speed": "medium" },
                { "symbol": "MATICUSDT", "profit": 165.6, "speed": "fast" },
                { "symbol": "MATICUSDT", "profit": 156.8, "speed": "very fast" },
                { "symbol": "MATICUSDT", "profit": 142.8, "speed": "extreme" },
                { "symbol": "MATICUSDT", "profit": 112.1, "speed": "ultra fast" },
                { "symbol": "KNCUSDT", "profit": 0, "speed": "very slow" },
                { "symbol": "KNCUSDT", "profit": 42.1, "speed": "slow" },
                { "symbol": "KNCUSDT", "profit": 40.2, "speed": "medium" },
                { "symbol": "KNCUSDT", "profit": 46.4, "speed": "fast" },
                { "symbol": "KNCUSDT", "profit": 42, "speed": "very fast" },
                { "symbol": "KNCUSDT", "profit": 43.7, "speed": "extreme" },
                { "symbol": "KNCUSDT", "profit": 32.2, "speed": "ultra fast" },
                { "symbol": "ADAUSDT", "profit": 249.8, "speed": "very slow" },
                { "symbol": "ADAUSDT", "profit": 153.3, "speed": "slow" },
                { "symbol": "ADAUSDT", "profit": 138.4, "speed": "medium" },
                { "symbol": "ADAUSDT", "profit": 129.1, "speed": "fast" },
                { "symbol": "ADAUSDT", "profit": 114.3, "speed": "very fast" },
                { "symbol": "ADAUSDT", "profit": 107, "speed": "extreme" },
                { "symbol": "ADAUSDT", "profit": 82.5, "speed": "ultra fast" },
                { "symbol": "ENJUSDT", "profit": 280.7, "speed": "very slow" },
                { "symbol": "ENJUSDT", "profit": 195.3, "speed": "slow" },
                { "symbol": "ENJUSDT", "profit": 180.2, "speed": "medium" },
                { "symbol": "ENJUSDT", "profit": 164, "speed": "fast" },
                { "symbol": "ENJUSDT", "profit": 146.8, "speed": "very fast" },
                { "symbol": "ENJUSDT", "profit": 136.9, "speed": "extreme" },
                { "symbol": "ENJUSDT", "profit": 136.7, "speed": "ultra fast" },
                { "symbol": "BATUSDT", "profit": 40.6, "speed": "very slow" },
                { "symbol": "BATUSDT", "profit": 41.3, "speed": "slow" },
                { "symbol": "BATUSDT", "profit": 63.7, "speed": "medium" },
                { "symbol": "BATUSDT", "profit": 68.4, "speed": "fast" },
                { "symbol": "BATUSDT", "profit": 65.4, "speed": "very fast" },
                { "symbol": "BATUSDT", "profit": 63.8, "speed": "extreme" },
                { "symbol": "BATUSDT", "profit": 59, "speed": "ultra fast" },
                { "symbol": "ALGOUSDT", "profit": 275.5, "speed": "very slow" },
                { "symbol": "ALGOUSDT", "profit": 160.3, "speed": "slow" },
                { "symbol": "ALGOUSDT", "profit": 144.8, "speed": "medium" },
                { "symbol": "ALGOUSDT", "profit": 124.6, "speed": "fast" },
                { "symbol": "ALGOUSDT", "profit": 121, "speed": "very fast" },
                { "symbol": "ALGOUSDT", "profit": 113.3, "speed": "extreme" },
                { "symbol": "ALGOUSDT", "profit": 115.3, "speed": "ultra fast" },
                { "symbol": "ACAUSDT", "profit": 304, "speed": "very slow" },
                { "symbol": "ACAUSDT", "profit": 229.6, "speed": "slow" },
                { "symbol": "ACAUSDT", "profit": 204.7, "speed": "medium" },
                { "symbol": "ACAUSDT", "profit": 153, "speed": "fast" },
                { "symbol": "ACAUSDT", "profit": 57.3, "speed": "very fast" },
                { "symbol": "ACAUSDT", "profit": 46.8, "speed": "extreme" },
                { "symbol": "ACAUSDT", "profit": 3.9, "speed": "ultra fast" },
                { "symbol": "DOGEUSDT", "profit": 41, "speed": "very slow" },
                { "symbol": "DOGEUSDT", "profit": 97.8, "speed": "slow" },
                { "symbol": "DOGEUSDT", "profit": 54.5, "speed": "medium" },
                { "symbol": "DOGEUSDT", "profit": 74, "speed": "fast" },
                { "symbol": "DOGEUSDT", "profit": 84.4, "speed": "very fast" },
                { "symbol": "DOGEUSDT", "profit": 69, "speed": "extreme" },
                { "symbol": "DOGEUSDT", "profit": 64.1, "speed": "ultra fast" }
            ]

                ;

            let symbolNames = [{
                name: 'Bitcoin', symbol: 'BTCUSDT',
            }, {
                name: 'Ethereum', symbol: 'ETHUSDT',
            }, {
                name: 'Solana', symbol: 'SOLUSDT',
            }, {
                name: 'Litecoin', symbol: 'LTCUSDT',
            }, {
                name: 'Rune', symbol: 'RUNEUSDT',
            }, {
                name: 'Ape', symbol: 'APEUSDT',
            }, {
                name: 'Theta', symbol: 'THETAUSDT',
            }, {
                name: 'Polygon', symbol: 'MATICUSDT',
            }, {
                name: 'Kyber Network', symbol: 'KNCUSDT',
            }, {
                name: 'Cardano', symbol: 'ADAUSDT',
            }, {
                name: 'Enjin', symbol: 'ENJUSDT',
            }, {
                name: 'Basic Attention', symbol: 'BATUSDT',
            }, {
                name: 'Algorand', symbol: 'ALGOUSDT',
            }, {
                name: 'Acala', symbol: 'ACAUSDT',
            }, {
                name: 'Dogecoin', symbol: 'DOGEUSDT',
            }
            ]

            // Group the data by symbol
            const groupedData = {};
            dataArray.forEach(item => {
                const key = item.symbol;
                if (!groupedData[key]) {
                    groupedData[key] = [];
                }
                groupedData[key].push({ speed: item.speed, profit: item.profit });
            });

            // Define the desired order of speeds
            const speedOrder = ["very slow", "slow", "fast", "very fast", "extreme", "ultra fast"];

            // Create an HTML table
            const table = document.createElement('table');
            table.classList.add('table', 'table-bordered', 'table-hover');
            const headerRow = table.insertRow();
            const headerSymbols = ['Symbol'];

            headerRow.classList.add('table-primary')
            // Add header cells for speeds in the desired order
            const th = document.createElement('th');
            th.textContent = 'SPEED';
            headerRow.appendChild(th);

            speedOrder.forEach(speed => {
                const th = document.createElement('th');
                th.innerHTML = speed + (speed == 'very fast' ? '&nbsp;<span style="color: yellow;">⚡</span>' : '');
                headerRow.appendChild(th);
            });

            // Add data rows
            Object.entries(groupedData).forEach(([symbol, speeds]) => {
                const row = table.insertRow();
                const symbolCell = row.insertCell();
                //symbolCell.textContent = symbol;

                const symbolName = symbolNames.find(s => s.symbol === symbol);
                //symbolCell.textContent = symbolName ? symbolName.name + ' (' + symbol + ')' : symbol;
                symbolCell.innerHTML = (symbolName ? symbolName.name + ' (' + symbol + ')' : symbol) + (symbol == 'MATICUSDT' ? '&nbsp;<span style="color: yellow;">⚡</span>' : '');

                // Initialize an object to store speeds for the current symbol
                const speedsMap = {};

                // Populate the speedsMap with the latest profit for each speed
                speeds.forEach(({ speed, profit }) => {
                    speedsMap[speed] = profit;
                });

                // Add cells for each speed in the desired order
                speedOrder.forEach(speed => {
                    const cell = row.insertCell();
                    const speedProfit = speedsMap[speed] || 0; // Default to 0 if speed not found
                    cell.textContent = '$' + speedProfit.toFixed(0);
                    /* if(speedProfit >= 120)
                        cell.classList.add('table-success')
                    else  */
                    if (speedProfit < 50)
                        cell.classList.add('table-danger')

                });
            });

            // Append the table to the HTML element with id "profitTable"
            document.getElementById('profitTable').appendChild(table);
        },

    }, //
    mounted() {
        let that = this
        this.prepareTableData();


        console.log('something on socket')
        console.log(global.vm.$socket)
    },
    beforeDestroy() {
        //console.error("unmounted pairspage")
    }
}
</script>
  
<style>
#profitTable th {
    height: 80px;
    line-height: 80px;
    margin: auto;
    text-align: center;
    text-transform: capitalize;
}

#profitTable td:first-child {
    text-align: center;
    text-transform: capitalize;
    font-size: 18px;
    height: 40px;
    line-height: 40px;
    background-color: #cfe2ff;
}

#profitTable td:not(:first-child) {
    font-size: 28px;
    height: 40px;
    line-height: 40px;
    text-align: center;
}
</style>
  
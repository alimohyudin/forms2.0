import * as d3 from 'd3'

export default config => selection => {
    let {
        data,
        xScale,
        yScale,
        totalInvestment
    } = config

    let stopAnimation = false;
    let animationEnded = false;

    let lineGenerator = d3
        .line()
        //.curve(d3.curveCardinal)
        .x((d) => xScale(d.date))
        .y((d) => yScale(d.value));

    /* var stopAnimationBtn = d3.select("#timeline").append("div")
        .attr("class", "stopAnimationBtn")
        .style("opacity", 1)
        .html(`<button class="btn btn-light">Stop</button>`)
        .on('click', () => {
            stopAnimation = !stopAnimation
            let text = stopAnimation ? 'Start' : 'Stop'
            stopAnimationBtn.html(`<button class="btn btn-light">${text}</button>`)
        }) */

    d3.selectAll(".tooltip").remove()
    var tooltip = d3.select("body").append("div")
        .attr("class", "tooltip")
        .style("opacity", 0);

    d3.selectAll(".totalValue").remove()
    var Totalvalue = d3.select("#timeline")
        .data(selection.data())
        .append("div")
        .attr("class", "totalValue")
        .style("opacity", 1)
    /* .on("mouseover", () => {
        console.log("mouse entered")
        stopAnimation = true;
    }).on("mouseout", () => {
        console.log("mouse entered")
        stopAnimation = false;
    }) */


    selection.selectAll(".cryptoLine").remove()
    selection.selectAll("g.label").remove()

    let events = selection.selectAll('.cryptoLine').data(data)

    let accountBalance = 0;
    let totalUsdAmount = totalInvestment * 1;
    let totalCryptoAmount = 0;
    let startingPrice = 0;
    let totalProfit = 0;    
    let accountBalanceWithoutBot = totalUsdAmount;
    let cryptoWithoutBot = 0;
    let isFirstRecursion = true;
    let delay = 0
    function recursiveDrawing(data, currentIndex, delay) {
        if (currentIndex > 0) {
            const e = data[0][currentIndex];
            const f = data[0][currentIndex - 1];
            //console.log(e)

            basicCalculation(e);

            if (currentIndex == 1){
                animationEnded = true;
                let text = 'Ended'
                //stopAnimationBtn.html(`<button class="btn btn-light" disabled>${text}</button>`)
                basicCalculation(f)
            }

            // console.log("TotalUsdAmount: ", totalUsdAmount)
            // console.log("TotalCryptoAmount: ", totalCryptoAmount)
            // console.log("TotalProfit: ", totalProfit)
            Totalvalue.html(`<div class="chartInfo">
            Investment: <span>${totalInvestment.toFixed(0)}$</span><br/>
            Balance: <span>${accountBalance.toFixed(0)}</span>$<br/>
            </div><div class="chartInfo-secondary">
            USDT: <span>${totalUsdAmount.toFixed(0)}$</span><br/>
            Crypto: <span>${totalCryptoAmount.toFixed(0)}</span><br/>
            Profit: <span>${totalProfit.toFixed(0)}$</span><br/>
            
            </div>`)
            // Do something with e and f, if needed
            events
                .enter()
                .append("path")
                .attr("d", (d) => {
                    //console.log(e, f)
                    let md = [e, f]
                    return lineGenerator(md)
                })
                .style("fill", "none")
                .style("stroke", "steelblue")
                .style("stroke-width", 5)
                .style("transform", "translate(20px, 0px)")
                .style("stroke-dasharray", function () {
                    var length = this.getTotalLength();
                    return length + " " + length;
                })
                .style("stroke-dashoffset", function () {
                    return this.getTotalLength();
                })
                .transition()
                .duration(delay)
                .ease(d3.easeLinear)
                .style("stroke-dashoffset", 0)
                /* .style("fill", "none")
                .style("stroke", "#fb9a99")
                .style("stroke-width", 5)
                .style("stroke-linejoin", "round") */
                .attr('class', 'cryptoLine')


            displayInformation(e)

            if (currentIndex == 1)
                displayInformation(f)

            // Call the function recursively with the updated index
            setTimeout(function () {
                let myInterval = 0;
                if (stopAnimation) {

                    myInterval = setInterval(() => {
                        if (!stopAnimation) {
                            clearInterval(myInterval)
                            recursiveDrawing(data, currentIndex - 1, delay)
                        }
                    }, 500)

                } else {
                    recursiveDrawing(data, currentIndex - 1, delay)
                }

            }, delay)
        }
    }


    recursiveDrawing(data, data[0].length - 1, delay)


    function basicCalculation(e) {
        if (e.orderType == 'buy') {
            if (isFirstRecursion) {
                isFirstRecursion = false;
                startingPrice = e.price;
                cryptoWithoutBot = totalUsdAmount / e.price;
            }

            totalUsdAmount -= e.usdAmount * 1;
            totalCryptoAmount += e.cryptoAmount * 1;

        }
        if (e.orderType == 'sell') {
            totalUsdAmount += e.usdAmount * 1;
            totalCryptoAmount -= e.cryptoAmount * 1;
            totalProfit += e.profit * 1;
        }
        accountBalance = totalUsdAmount + totalCryptoAmount * e.price
        accountBalanceWithoutBot = cryptoWithoutBot * e.price
    }
    function displayInformation(e) {
        let label = events
            .enter()
            .append("g")
            .attr('class', 'label')
            .attr("transform", (d) => {
                //console.log(e)
                return `translate(${xScale(new Date(e.date)) + 20}, ${yScale(e.value)})`
            })

        label.append("circle")
            .attr("r", 4)
            .style("stroke", "white")
            .style("fill", 'green')
            .style("opacity", 0)
            .on("mouseover", function (d) {
                showTooltip(e)
            })
            .on("mouseout", function (d) {
                // Hide the tooltip on mouseout
                tooltip
                    .style("opacity", 0);
            })
            .transition()
            .duration(200)
            .style("opacity", 1)


        let text = label.append("text")
            .text((d) => e.value)
            .attr("dy", 5)
            .attr("dx", 10)
            .style("font-family", "monospace")
            .style("fill", (d) => e.orderType.toLowerCase() == 'buy' ? 'red' : 'green')
            .on("mouseover", function (d) {
                showTooltip(e)
            })
            .on("mouseout", function (d) {
                // Hide the tooltip on mouseout
                tooltip
                    .style("opacity", 0);
            })
            .style("opacity", 0)
            .transition()
            .duration(200)
            .style("opacity", 1);



        text.each(function () {
            var bbox = this.getBBox();
            label.insert("rect", ":first-child")
                .attr("x", bbox.x - 5)
                .attr("y", bbox.y - 2)
                .attr("width", bbox.width + 10)
                .attr("height", bbox.height + 4)
                .style("fill", "lightyellow")
                .style("rx", 5)
                .style("ry", 5);
        });
    }
    function showTooltip(e) {
        // Show the tooltip on mouseover
        tooltip
            .style("opacity", 0.9);

        // Set the tooltip content based on the data
        tooltip.html("Price: " + e.price
            + "<br>Type: " + e.orderType
            + "<br>Usd: " + e.usdAmount
            + "<br>Profit: " + e.profit.toFixed(2)
            + "<br>Date: " + d3.timeFormat('%H %p-%d %b %Y')(e.date)
        )
            .style("left", (d3.event.pageX + 5) + "px")
            .style("top", (d3.event.pageY - 28) + "px");
    }
}
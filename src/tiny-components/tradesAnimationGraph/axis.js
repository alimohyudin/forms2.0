import * as d3 from 'd3'

function format(date) {
    if (d3.timeDay(date) < date) {
        return d3.timeFormat('%I:%M')(date)
    }

    if (d3.timeMonth(date) < date) {
        return d3.timeFormat('%b %d')(date)
    }

    if (d3.timeYear(date) < date) {
        return d3.timeFormat('%B')(date)
    }

    return d3.timeFormat('%Y')(date)
}

export default (config) => selection => {
    let {
        xScale,
        yScale,
        height
    } = config


    selection.selectAll(".axis").remove()

    let xAxisGenerator = d3.axisBottom(xScale).tickFormat(d3.timeFormat('%d %b')),//('%H%p %d %b')),
        yAxisGenerator = d3.axisLeft(yScale).tickFormat(d3.format(".2f"));

    selection.append('g').call(xAxisGenerator).attr("transform", `translate(20, ${height})`).attr('class', 'axis');
    selection.append('g').call(yAxisGenerator).attr("transform", `translate(20, 0)`).attr('class', 'axis');


    /* let axe = selection.selectAll('.axe').data(d => d)

    let ay = d3.axisBottom()
            .scale(timeScale)
            .tickFormat(d => format(d))

    axe.enter()
        .append('g')
        .attr('transform', `translate(0, ${height+5})`)
        .classed('axe', true)
        .call(ay)

    axe.call(ay) */
}
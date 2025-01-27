import * as d3 from 'd3'

import events from './events'
import axis from './axis'
import zoom from './zoom'
import cursor from './cursor'
import layout from './layout'

//import { toRoman } from 'roman-numerals'

export default (config) => {
    function init(selection) {
        console.log('timeline init')
        //console.log(selection.selectAll('svg'))
        selection.selectAll('svg').remove()

        let data = selection.data()

        let events = data[0]
        layout.generate(events)

        //console.table(events)

        let {
            viewWidth = 800,
            viewHeight = 200,
            widthResizable = true,
            margin,
            onEventClick,
            showCursor = true,
            totalInvestment

        } = config

        if (widthResizable) {
            viewWidth = selection.node().clientWidth
        }

        let width = viewWidth - margin.right - margin.left
        let height = viewHeight - margin.top - margin.bottom

        let svg = selection
            .append('svg')
            .data(data)
            .attr('width', width + margin.right + margin.left)
            .attr('height', height + margin.top + margin.bottom)
            .style('transform', 'translate(0px, 0px)')

        var bufferX = 70;

        let xScale = d3
            .scaleTime()
            //.domain([d3.min(events, d=> d.date), d3.max(events, d=> d.date)])
            .domain([d3.min(events, d => d.date), d3.max(events, d => d.date)])
            .range([0, width - bufferX]),
            yScale = d3.scaleLinear().domain([d3.min(events, d => d.value) - 0.1, d3.max(events, d => d.value) + 0.1]).range([height, 0]);

        let graph = svg
            .append('g')
            .classed('graph', true)
            .attr('transform', `translate(${margin.left},${margin.top})`)

        let view = graph.append('g')
            .classed('view', true)

        /* svg.call(zoom({
            data,
            xScale,
            yScale,
            height,
            view,
            draw,
        }))
 */

        view.call(draw(data, xScale, yScale, height, totalInvestment))
    }

    function chart(selection) {
        console.log('timeline constructor')
        chart._init = () => init(selection)
        chart._init()

        if (config.widthResizable) {
            global.addEventListener('resize', chart._init, true)
        }
    }

    function draw(data, xScale, yScale, height, totalInvestment) {
        return selection => {
            selection
                .data(selection.data())
                .call(events({
                    data,
                    xScale,
                    yScale,
                    totalInvestment
                }))
                .call(axis({
                    xScale,
                    yScale,
                    height
                }))
            /* .call(cursor({
                showCursor,
                xScale, 
                yScale,
                height,
            })) */
        }
    }

    return chart

}
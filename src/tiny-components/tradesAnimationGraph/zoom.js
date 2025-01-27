import * as d3 from 'd3'

export default (config) => {
    let {
        data,
        xScale,
        yScale,
        height,
        view,
        draw,
    } = config

    return d3.zoom()
        //.translateExtent([[ -1200, -700 ], [ 800, 800 ]])
        .on('zoom', () => {
            //console.log(d3.event.transform)
            //let { k, x, y } = d3.event.transform

            /*  let scale = d3.zoomIdentity
                             .translate(x,y)
                             .scale(k)
                             .rescaleX(xScale) */
            var new_xScale = d3.event.transform.rescaleX(xScale);
            var new_yScale = d3.event.transform.rescaleY(yScale);

            view.call(draw(data, new_xScale, new_yScale, height))
        })
}
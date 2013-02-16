/*!
* jQuery Sphere 3D: SHORT_DESCRIPTION - v1.0pre - ??/??/2010
* http://benalman.com/projects/jquery-sphere3d-plugin/
* 
* Copyright (c) 2010 "Cowboy" Ben Alman
* Dual licensed under the MIT and GPL licenses.
* http://benalman.com/about/license/
*/

// Script: jQuery Sphere 3D: SHORT_DESCRIPTION
//
// *Version: 1.0pre, Last updated: ??/??/2010*
// 
// Project Home - http://benalman.com/projects/jquery-sphere3d-plugin/
// GitHub       - http://github.com/cowboy/jquery-sphere3d/
// Source       - http://github.com/cowboy/jquery-sphere3d/raw/master/jquery.ba-sphere3d.js
// (Minified)   - http://github.com/cowboy/jquery-sphere3d/raw/master/jquery.ba-sphere3d.min.js (3.5kb)
// 
// About: License
// 
// Copyright (c) 2010 "Cowboy" Ben Alman,
// Dual licensed under the MIT and GPL licenses.
// http://benalman.com/about/license/
// 
// About: Examples
// 
// These working examples, complete with fully commented code, illustrate a few
// ways in which this plugin can be used.
// 
// Static Images - http://benalman.com/code/projects/jquery-sphere3d/examples/flickr/
// 
// About: Support and Testing
// 
// Information about what version or versions of jQuery this plugin has been
// tested with, what browsers it has been tested in, and where the unit tests
// reside (so you can test it yourself).
// 
// jQuery Versions - 1.3.2
// Browsers Tested - Internet Explorer 6-8, Firefox 2-3.7, Safari 3-4, Chrome, Opera 9.6-10.
// Unit Tests      - http://benalman.com/code/projects/jquery-sphere3d/unit/
// 
// About: Release History
// 
// 1.0pre - (??/??/2010) Pre-Initial release

(function ($, window, document, Math) {
    '$:nomunge'; // Used by YUI compressor.

    // Help minification, slightly.
    var sphere3d = 'sphere3d',

    // Each sphere3d "parent" element needs a unique ID, for child element IDs.
    uuid = 0,

    // Some reasonable defaults.
    defaults = {
        elems: '> *',
        elems_opacity: '> *',

        delay: 30,
        reflow: 0,

        // Percentages, from 0 <= 1
        scale: 1,
        perspective: 0.25,
        reveal: 1,
        noclick_opacity: 0.9,

        rotation: { x: -0.01, y: -0.01 },

        mouse: true,
        mouse_speed: 0.15,
        mouse_deadzone: 0.1,

        base_zindex: 5000,

        scaleMode: 'showAll',
        points: [],
        set_width: true,
        set_fontsize: true,

        blur_pause: true
    };

    $.fn[sphere3d] = function (opts) {
        var that = this,

        // `data` will reference this.data( 'sphere3d', {} ).
      data,

        // A unique ID for this "parent" element that all sub-elements and styles
        // will use.
      uid,

        // `opts` override defaults.
      options = $.extend(true, {}, defaults, opts),
      elems = options.elems && that.find(options.elems),
      rotation = options.rotation,
      elems_opacity = options.elems_opacity,
      noclick_opacity = options.noclick_opacity,
      reveal = options.reveal,
      base_zindex = options.base_zindex,
      points,

        // The stylesheet to be written into the HEAD.
      stylesheet,
      stylesheet_prop,

        // A few CSS-related items.
      opacity_reveal = reveal > 0.5 ? 0.5 : reveal,
      opacity_offset = reveal > 0.5 ? 1 - noclick_opacity : 0,
      background_color = get_background_color(that),
      imp = '!important;',
      noclick_css = [
        'background-color:' + background_color,
        'z-index:' + (base_zindex + 1999),
        'position:absolute', 'top:0', 'bottom:0', 'left:0', 'right:0', 'width:100%', 'height:100%'
      ].join(imp) + imp + get_opacity_css(elems_opacity ? noclick_opacity : 0.01),

        // This max radius value is used to scale all 3D coordinates, so that
        // visible scaling stays consistent.
      max_r = 0,

        // These values are computed initially on the "parent" element, and then
        // in the `reflow` function every `options.reflow` iterations.
      w,
      h,
      offset,
      reflow_counter = 0,

        // -1 <= percent <= 1 values for the mouse position in respect to the
        // "parent" element.
      mouse_x = rotation.x || 0,
      mouse_y = rotation.y || 0;

        // Initialize `data`.
        that.data(sphere3d, data = that.data(sphere3d) || {});

        // Initialize `uid`. This should be unique per-sphere3d "parent" element.
        uid = data.uid = data.uid || sphere3d + '-' + uuid++;

        // Initialize `points`.
        points = data.points = data.points || options.points;

        if (opts === false) {
            if (!data.running) { return that; }

            // Stop iterating.
            stop();

            // Un-initialize all the DOM changes.
            $('head .' + uid).remove();

            // Remove IDs from DOM elements.
            identify_elements();

            // Cleanup.
            $(window).unbind('.' + sphere3d);

            that
        .removeData(sphere3d)
        .unbind('.' + sphere3d)
        .find('#' + uid + '-x')
          .remove();
        }

        if (!data.running) {
            data.running = true;

            // Initialize `points` array.
            if (points.length) {
                // An array of points was specified. Use a specified elem if possible,
                // otherwise use the `i`th specified `elems` element.
                $.each(points, function (i, point) {
                    add_point(point.x, point.y, point.z, point.elem || elems.eq(i));
                });
            } else {
                // Since an array of points was not specified, use fancy math-learning
                // to distribute `elems` quasi-uniformly around the surface of a sphere.
                distribute(elems.length, function (i, x, y, z) {
                    add_point(x, y, z, elems.eq(i));
                });
            };

            // Instead of modifying each DOM element's style attribute individually, on
            // every iteration, just assign each element a unique class at the start,
            // and then write a single stylesheet to the HEAD on every iteration. This
            // should scale significantly better.

            // Append stylesheet to HEAD.
            if ($.browser.msie) { // ie is awesome
                stylesheet = document.createStyleSheet();
                stylesheet_prop = 'cssText';
            } else {
                stylesheet = $('<style rel="stylesheet" type="text/css"/>')[0];
                stylesheet_prop = typeof document.body.style.WebkitAppearance === 'string' ? 'innerText' : 'innerHTML';
            }

            $(stylesheet).addClass(uid).appendTo('head');

            // Add IDs to DOM elements.
            identify_elements(true);

            // Convert mouse position into a +/- percent value, with optional center
            // "dead zone"
            if (options.mouse) {
                that.bind('mousemove.' + sphere3d, function (e) {
                    // There's a lot of math used in calculating a really natural-feeling
                    // center "deadzone". It doesn't feel right if you test both axes'
                    // distances separately, because if the value of only one axis is high
                    // enough to pass the threshold, things only move along that one axis.
                    // Instead of considering x or y distances from the center separately,
                    // you must consider one radius from the center. Once that minimum
                    // radius is met, both x and y axes can then be moved proportionately.
                    var deadzone = options.mouse_deadzone,
            speed = options.mouse_speed,
            pct_x = ((e.pageX - offset.left) / w) - 1,
            pct_y = ((e.pageY - offset.top) / h) - 1,
            r = Math.sqrt(pct_x * pct_x + pct_y * pct_y),
            x_for_y = Math.sqrt(deadzone * deadzone - pct_y * pct_y),
            y_for_x = Math.sqrt(deadzone * deadzone - pct_x * pct_x);

                    mouse_x = speed *
            (r < deadzone ? 0
            : Math.abs(pct_y) > deadzone ? pct_x
            : (pct_x - (x_for_y * Math.abs(pct_x) / pct_x)) / (1 - x_for_y));

                    mouse_y = speed *
            (r < deadzone ? 0
            : Math.abs(pct_x) > deadzone ? pct_y
            : (pct_y - (y_for_x * Math.abs(pct_y) / pct_y)) / (1 - y_for_x));
                });
            }

            // Create a div at 50% z-index to prevent accidental clicks of low
            // z-index items.
            if (reveal > 0.5) {
                $('<div id="' + uid + '-x" class="' + sphere3d + '-noclick"/>').appendTo(that);
            }

            // Pausing animation on window blur makes this co-exist much better with
            // pretty much the entire rest of the universe.
            if (options.blur_pause) {
                $(window)
          .bind('focus.' + sphere3d, start)
          .bind('blur.' + sphere3d, stop);
            }

            // Start iterating.
            reflow(true);

            // TODO: REMOVE
            if (options.benchmark) {
                (function () {
                    var time = +new Date(), i, iterations = 200;
                    for (i = 0; i < iterations; i++) {
                        start();
                    }
                    setTimeout(function () {
                        alert(((+new Date() - time) / iterations) + 'ms per iteration');
                    }, 10);
                })();

                return;
            }

            start();
        }

        return that;

        // Add or remove classes and IDs from DOM elements.
        function identify_elements(init) {
            that[init ? 'addClass' : 'removeClass'](sphere3d);

            function set_id(point, elem, id, id_prop) {
                if (init) {
                    if (!elem.attr('id')) {
                        point[id_prop + 'no'] = true;
                        elem.attr('id', id);
                    }
                    point[id_prop] = elem.attr('id');

                } else if (point[id_prop + 'no']) {
                    elem.removeAttr('id');
                }
            };

            $.each(points, function (i, point) {
                var elem = point.elem,
          elem_o,
          c = uid + '-' + i;

                set_id(point, elem, c, 'e_id');

                if (elems_opacity) {
                    elem_o = point.elem_o = elems_opacity === true ? elem : elem.find(elems_opacity);
                    set_id(point, elem_o, c + '-o', 'o_id');
                }
            });
        };

        // Add a point.
        function add_point(x, y, z, elem) {
            max_r = Math.max(max_r, Math.sqrt(x * x + y * y + z * z));
            points.push({ x: x, y: y, z: z, elem: elem, w: elem.width(), h: elem.height() });
        };

        // Distribute `n` points roughly equidistant on a sphere.
        // http://www.xsi-blog.com/archives/115
        function distribute(n, callback) {
            var inc = Math.PI * (3 - Math.sqrt(5)),
        off = 2 / n,
        y, r, phi;

            while (n--) {
                y = (n * off) - 1 + (off / 2);
                r = Math.sqrt(1 - y * y);
                phi = n * inc;
                callback(n, Math.cos(phi) * r, y, Math.sin(phi) * r);
            }
        };

        // Start polling loop.
        function start() {
            var delay = options.delay;

            stop();

            reflow();
            rotate(points, -mouse_y, mouse_x, 0);
            render(points);

            if (typeof delay === 'number') {
                data.id = setTimeout(start, delay);
            }
        };

        // Stop polling loop.
        function stop() {
            clearTimeout(data.id);
        };

        // Update offset, width and height, in case elements have reflowed.
        function reflow(force) {
            var reflow = options.reflow;

            if (force || (reflow && !(++reflow_counter % reflow))) {
                w = that.width() / 2;
                h = that.height() / 2;
                offset = that.offset();
            }
        };

        // Rotate points based on x, y, z rotation deltas.
        // 
        // Based on senocular's flash rotation code and a bit of my own:
        // 
        // http://www.kirupa.com/developer/actionscript/rotation_center.htm
        // http://benalman.com/portfolio/flash-3d-engine/
        // http://benalman.com/portfolio/website-personal-cowboy-v4/
        //
        function rotate(points, rotation_x, rotation_y, rotation_z) {
            var x_y, x_z, y_z, y_x, z_x, z_y,
        sin_x = Math.sin(rotation_x),
        cos_x = Math.cos(rotation_x),
        sin_y = Math.sin(rotation_y),
        cos_y = Math.cos(rotation_y),
        sin_z = Math.sin(rotation_z),
        cos_z = Math.cos(rotation_z),
        i = points.length,
        point;

            while (--i >= 0) {
                point = points[i];

                x_y = (cos_x * point.y) - (sin_x * point.z),
        x_z = (sin_x * point.y) + (cos_x * point.z),
        y_z = (cos_y * x_z) - (sin_y * point.x),
        y_x = (sin_y * x_z) + (cos_y * point.x),
        z_x = (cos_z * y_x) - (sin_z * x_y),
        z_y = (sin_z * y_x) + (cos_z * x_y);

                point.x = z_x;
                point.y = z_y;
                point.z = y_z;
            }
        };

        // Render 3D points as 2D points.
        function render(points) {
            var i = points.length,
        point,

        scale = options.scale / max_r,
        perspective_multiplier = options.perspective,
        set_width = options.set_width,
        scaleMode = options.scaleMode,

        x, y, z,
        perspective,
        elem_w,
        elem_h,
        view_w,
        view_h,

        opacity,

        css_arr,
        css = '#' + uid + '-x{' + noclick_css + '}';

            while (--i >= 0) {
                point = points[i];

                x = point.x * scale;
                y = point.y * scale;
                z = -point.z * scale;

                perspective = (z + (1 / perspective_multiplier)) / ((1 / perspective_multiplier) + 1);

                elem_w = point.w / 2 * (set_width ? perspective : 1);
                elem_h = point.h / 2 * (set_width ? perspective : 1);

                if (z < 1 - (2 * reveal)) {
                    css_arr = ['display:none'];
                } else {
                    view_w = w;
                    view_h = h;

                    if (scaleMode !== 'exactFit') {
                        view_w = view_h = Math[scaleMode === 'noBorder' ? 'max' : 'min'](view_w, view_h);
                    }

                    css_arr = [
            'display:block',
            'position:absolute',
            'left:' + (view_w + ((view_w - elem_w) * x) - elem_w + w - view_w) + 'px',
            'top:' + (view_h + ((view_h - elem_h) * y) - elem_h + h - view_h) + 'px',
            'z-index:' + (base_zindex + (parseInt((z + 2) * 500) * 2)),
            'background-color:' + background_color
          ];

                    if (set_width) {
                        css_arr.push('width:' + (elem_w * 2) + 'px');
                        css_arr.push('height:' + (elem_h * 2) + 'px');
                    }

                    if (options.set_fontsize) {
                        css_arr.push('font-size:' + perspective + 'em');
                    }

                    if (elems_opacity && z > 0) {
                        opacity = (((z - 1) / 2) + opacity_reveal + opacity_offset) / opacity_reveal - opacity_offset;
                        css += '#' + point.o_id + '{' + get_opacity_css(opacity) + '}';
                    }

                }

                css += '#' + point.e_id + '{' + css_arr.join(imp) + imp + '}';
            }

            // Instead of modifying each DOM element's style attribute individually, on
            // every iteration, just assign each element a unique class at the start,
            // and then write a single stylesheet to the HEAD on every iteration. This
            // should scale significantly better.

            stylesheet[stylesheet_prop] = css;
        };

        // Get a cross-browser CSS opacity string.
        function get_opacity_css(opacity) {
            return 'filter:alpha(opacity=' + (opacity * 100) + ')' + imp + 'opacity:' + opacity + imp;
        };

        // Get the first background color that you can actually see, from an element
        // or the first parent that matters.
        function get_background_color(elem) {
            var result = '#fff';

            elem.add(elem.parents()).each(function () {
                var c = $(this).css('background-color');
                if (c !== 'transparent' && c !== 'rgba(0, 0, 0, 0)') {
                    result = c;
                    return false;
                }
            });

            return result;
        };

    };

})(jQuery, this, document, Math);
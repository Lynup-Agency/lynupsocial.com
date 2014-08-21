window.jQuery(function($){

	if (window.NinjaContactFormOpts && window.NinjaContactFormOpts.test_mode === 'yes' && !$('body').is('.logged-in.admin-bar')) return;

	/*
	jquery.animate-enhanced plugin v1.07
	---
	http://github.com/benbarnett/jQuery-Animate-Enhanced
	http://benbarnett.net
	@benpbarnett
	---
	Copyright (c) 2013 Ben Barnett

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
	---
	Extends jQuery.animate() to automatically use CSS3 transformations where applicable.
	Tested with jQuery 1.3.2+

	Supports -moz-transition, -webkit-transition, -o-transition, transition

	Targetted properties (for now):
	        - left
	        - top
	        - opacity
	        - width
	        - height

	Usage (exactly the same as it would be normally):

	        jQuery(element).animate({left: 200},  500, function() {
	                // callback
	        });

	*/
	;
	(function(jQuery, originalAnimateMethod, originalStopMethod) {

	        // ----------
	        // Plugin variables
	        // ----------
	        var  cssTransitionProperties = ['top', 'right', 'bottom', 'left', 'opacity', 'height', 'width'],
	                directions = ['top', 'right', 'bottom', 'left'],
	                cssPrefixes = ['-webkit-', '-moz-', '-o-', ''],
	                pluginOptions = ['avoidTransforms', 'useTranslate3d', 'leaveTransforms'],
	                rfxnum = /^([+-]=)?([\d+-.]+)(.*)$/,
	                rupper = /([A-Z])/g,
	                defaultEnhanceData = {
	                        secondary: {},
	                        meta: {
	                                top : 0,
	                                right : 0,
	                                bottom : 0,
	                                left : 0
	                        }
	                },
	                valUnit = 'px',

	                DATA_KEY = 'jQe',
	                CUBIC_BEZIER_OPEN = 'cubic-bezier(',
	                CUBIC_BEZIER_CLOSE = ')',

	                originalAnimatedFilter = null,
	                pluginDisabledDefault = false;


	        // ----------
	        // Check if this browser supports CSS3 transitions
	        // ----------
	        var thisBody = document.body || document.documentElement,
	                thisStyle = thisBody.style,
	                transitionEndEvent = 'webkitTransitionEnd oTransitionEnd transitionend',
	                cssTransitionsSupported = thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.OTransition !== undefined || thisStyle.transition !== undefined,
	                has3D = ('WebKitCSSMatrix' in window && 'm11' in new WebKitCSSMatrix()),
	                use3DByDefault = has3D;



	        // ----------
	        // Extended :animated filter
	        // ----------
	        if ( jQuery.expr && jQuery.expr.filters ) {
	                originalAnimatedFilter = jQuery.expr.filters.animated;
	                jQuery.expr.filters.animated = function(elem) {
	                        return jQuery(elem).data('events') && jQuery(elem).data('events')[transitionEndEvent] ? true : originalAnimatedFilter.call(this, elem);
	                };
	        }


	        /**
	                @private
	                @name _getUnit
	                @function
	                @description Return unit value ("px", "%", "em" for re-use correct one when translating)
	                @param {variant} [val] Target value
	        */
	        function _getUnit(val){
	                return val.match(/\D+$/);
	        }


	        /**
	                @private
	                @name _interpretValue
	                @function
	                @description Interpret value ("px", "+=" and "-=" sanitisation)
	                @param {object} [element] The Element for current CSS analysis
	                @param {variant} [val] Target value
	                @param {string} [prop] The property we're looking at
	                @param {boolean} [isTransform] Is this a CSS3 transform?
	        */
	        function _interpretValue(e, val, prop, isTransform) {
	                // this is a nasty fix, but we check for prop == 'd' to see if we're dealing with SVG, and abort
	                if (prop == "d") return;
	                if (!_isValidElement(e)) return;

	                var parts = rfxnum.exec(val),
	                        start = e.css(prop) === 'auto' ? 0 : e.css(prop),
	                        cleanCSSStart = typeof start == 'string' ? _cleanValue(start) : start,
	                        cleanTarget = typeof val == 'string' ? _cleanValue(val) : val,
	                        cleanStart = isTransform === true ? 0 : cleanCSSStart,
	                        hidden = e.is(':hidden'),
	                        translation = e.translation();

	                if (prop == 'left') cleanStart = parseInt(cleanCSSStart, 10) + translation.x;
	                if (prop == 'right') cleanStart = parseInt(cleanCSSStart, 10) + translation.x;
	                if (prop == 'top') cleanStart = parseInt(cleanCSSStart, 10) + translation.y;
	                if (prop == 'bottom') cleanStart = parseInt(cleanCSSStart, 10) + translation.y;

	                // deal with shortcuts
	                if (!parts && val == 'show') {
	                        cleanStart = 1;
	                        if (hidden) {
	                                elem = e[0];
	                                if (elem.style) {
	                                        display = elem.style.display;

	                                        // Reset the inline display of this element to learn if it is
	                                        // being hidden by cascaded rules or not
	                                        if (!jQuery._data(elem, 'olddisplay') && display === 'none') {
	                                                display = elem.style.display = '';
	                                        }

	                                        // Set elements which have been overridden with display: none
	                                        // in a stylesheet to whatever the default browser style is
	                                        // for such an element
	                                        if ( display === '' && jQuery.css(elem, 'display') === 'none' ) {
	                                                jQuery._data(elem, 'olddisplay', _domElementVisibleDisplayValue(elem.context ? elem.context.tagName : elem.tagName));
	                                        }

	                                        if (display === '' || display === 'none') {
	                                                elem.style.display = jQuery._data(elem, 'olddisplay') || '';
	                                        }
	                                }
	                                e.css('opacity', 0);
	                        }
	                } else if (!parts && val == 'hide') {
	                        cleanStart = 0;
	                }

	                if (parts) {
	                        var end = parseFloat(parts[2]);

	                        // If a +=/-= token was provided, we're doing a relative animation
	                        if (parts[1]) end = ((parts[1] === '-=' ? -1 : 1) * end) + parseInt(cleanStart, 10);

	                        // check for unit  as per issue #69
	                        if (parts[3] && parts[3] != 'px') end = end + parts[3];

	                        return end;
	                } else {
	                        return cleanStart;
	                }
	        }

	        /**
	                @private
	                @name _getTranslation
	                @function
	                @description Make a translate or translate3d string
	                @param {integer} [x]
	                @param {integer} [y]
	                @param {boolean} [use3D] Use translate3d if available?
	        */
	        function _getTranslation(x, y, use3D) {
	                return ((use3D === true || ((use3DByDefault === true && use3D !== false)) && has3D)) ? 'translate3d(' + x + 'px, ' + y + 'px, 0)' : 'translate(' + x + 'px,' + y + 'px)';
	        }


	        /**
	                @private
	                @name _applyCSSTransition
	                @function
	                @description Build up the CSS object
	                @param {object} [e] Element
	                @param {string} [property] Property we're dealing with
	                @param {integer} [duration] Duration
	                @param {string} [easing] Easing function
	                @param {variant} [value] String/integer for target value
	                @param {boolean} [isTransform] Is this a CSS transformation?
	                @param {boolean} [isTranslatable] Is this a CSS translation?
	                @param {boolean} [use3D] Use translate3d if available?
	        */
	        function _applyCSSTransition(e, property, duration, easing, value, isTransform, isTranslatable, use3D) {
	                var eCSSData = e.data(DATA_KEY),
	                        enhanceData = eCSSData && !_isEmptyObject(eCSSData) ? eCSSData : jQuery.extend(true, {}, defaultEnhanceData),
	                        offsetPosition = value,
	                        isDirection = jQuery.inArray(property, directions) > -1;

	                if (isDirection) {
	                        var meta = enhanceData.meta,
	                                cleanPropertyValue = _cleanValue(e.css(property)) || 0,
	                                stashedProperty = property + '_o';

	                        offsetPosition = value - (value === 0 ? 0 : cleanPropertyValue );


	                        meta[property] = offsetPosition;
	                        meta[stashedProperty] = e.css(property) == 'auto' ? 0 + offsetPosition : cleanPropertyValue + offsetPosition || 0;
	                        enhanceData.meta = meta;

	                        // fix 0 issue (transition by 0 = nothing)
	                        if (isTranslatable && offsetPosition === 0) {
	                                offsetPosition = 0 - meta[stashedProperty];
	                                meta[property] = offsetPosition;
	                                meta[stashedProperty] = 0;
	                        }
	                }

	                // reapply data and return
	                return e.data(DATA_KEY, _applyCSSWithPrefix(e, enhanceData, property, duration, easing, offsetPosition, isTransform, isTranslatable, use3D));
	        }

	        /**
	                @private
	                @name _applyCSSWithPrefix
	                @function
	                @description Helper function to build up CSS properties using the various prefixes
	                @param {object} [cssProperties] Current CSS object to merge with
	                @param {string} [property]
	                @param {integer} [duration]
	                @param {string} [easing]
	                @param {variant} [value]
	                @param {boolean} [isTransform] Is this a CSS transformation?
	                @param {boolean} [isTranslatable] Is this a CSS translation?
	                @param {boolean} [use3D] Use translate3d if available?
	        */
	        function _applyCSSWithPrefix(e, cssProperties, property, duration, easing, value, isTransform, isTranslatable, use3D) {
	                var saveOriginal = false,
	                        transform = isTransform === true && isTranslatable === true;


	                cssProperties = cssProperties || {};
	                if (!cssProperties.original) {
	                        cssProperties.original = {};
	                        saveOriginal = true;
	                }
	                cssProperties.properties = cssProperties.properties || {};
	                cssProperties.secondary = cssProperties.secondary || {};

	                var meta = cssProperties.meta,
	                        original = cssProperties.original,
	                        properties = cssProperties.properties,
	                        secondary = cssProperties.secondary;

	                for (var i = cssPrefixes.length - 1; i >= 0; i--) {
	                        var tp = cssPrefixes[i] + 'transition-property',
	                                td = cssPrefixes[i] + 'transition-duration',
	                                tf = cssPrefixes[i] + 'transition-timing-function';

	                        property = (transform ? cssPrefixes[i] + 'transform' : property);

	                        if (saveOriginal) {
	                                original[tp] = e.css(tp) || '';
	                                original[td] = e.css(td) || '';
	                                original[tf] = e.css(tf) || '';
	                        }

	                        secondary[property] = transform ? _getTranslation(meta.left, meta.top, use3D) : value;

	                        properties[tp] = (properties[tp] ? properties[tp] + ',' : '') + property;
	                        properties[td] = (properties[td] ? properties[td] + ',' : '') + duration + 'ms';
	                        properties[tf] = (properties[tf] ? properties[tf] + ',' : '') + easing;
	                }

	                return cssProperties;
	        }

	        /**
	                @private
	                @name _isBoxShortcut
	                @function
	                @description Shortcut to detect if we need to step away from slideToggle, CSS accelerated transitions (to come later with fx.step support)
	                @param {object} [prop]
	        */
	        function _isBoxShortcut(prop) {
	                for (var property in prop) {
	                        if ((property == 'width' || property == 'height') && (prop[property] == 'show' || prop[property] == 'hide' || prop[property] == 'toggle')) {
	                                return true;
	                        }
	                }
	                return false;
	        }


	        /**
	                @private
	                @name _isEmptyObject
	                @function
	                @description Check if object is empty (<1.4 compatibility)
	                @param {object} [obj]
	        */
	        function _isEmptyObject(obj) {
	                for (var i in obj) {
	                        return false;
	                }
	                return true;
	        }

	        /**
	         * Fetch most appropriate display value for element types
	         * @see  https://github.com/benbarnett/jQuery-Animate-Enhanced/issues/121
	         * @private
	         * @param  {[type]} tagName [description]
	         * @return {[type]}         [description]
	         */
	        function _domElementVisibleDisplayValue(tagName) {
	                tagName = tagName.toUpperCase();
	                var displayValues = {
	                        'LI'       : 'list-item',
	                        'TR'       : 'table-row',
	                        'TD'       : 'table-cell',
	                        'TH'       : 'table-cell',
	                        'CAPTION'  : 'table-caption',
	                        'COL'      : 'table-column',
	                        'COLGROUP' : 'table-column-group',
	                        'TFOOT'      : 'table-footer-group',
	                        'THEAD'      : 'table-header-group',
	                        'TBODY'      : 'table-row-group'
	                };

	                return typeof displayValues[tagName] == 'string' ? displayValues[tagName] : 'block';
	        }


	        /**
	                @private
	                @name _cleanValue
	                @function
	                @description Remove 'px' and other artifacts
	                @param {variant} [val]
	        */
	        function _cleanValue(val) {
	                return parseFloat(val.replace(_getUnit(val), ''));
	        }


	        function _isValidElement(element) {
	                var allValid=true;
	                element.each(function(index, el) {
	                        allValid = allValid && el.ownerDocument;
	                        return allValid;
	                });
	                return allValid;
	        }

	        /**
	                @private
	                @name _appropriateProperty
	                @function
	                @description Function to check if property should be handled by plugin
	                @param {string} [prop]
	                @param {variant} [value]
	        */
	        function _appropriateProperty(prop, value, element) {
	                if (!_isValidElement(element)) {
	                        return false;
	                }

	                var is = jQuery.inArray(prop, cssTransitionProperties) > -1;
	                if ((prop == 'width' || prop == 'height' || prop == 'opacity') && (parseFloat(value) === parseFloat(element.css(prop)))) is = false;
	                return is;
	        }


	        jQuery.extend({
	                /**
	                        @public
	                        @name toggle3DByDefault
	                        @function
	                        @description Toggle for plugin settings to automatically use translate3d (where available). Usage: $.toggle3DByDefault
	                */
	                toggle3DByDefault: function() {
	                        return use3DByDefault = !use3DByDefault;
	                },


	                /**
	                        @public
	                        @name toggleDisabledByDefault
	                        @function
	                        @description Toggle the plugin to be disabled by default (can be overridden per animation with avoidCSSTransitions)
	                */
	                toggleDisabledByDefault: function() {
	                        return pluginDisabledDefault = !pluginDisabledDefault;
	                },


	                /**
	                        @public
	                        @name setDisabledByDefault
	                        @function
	                        @description Set or unset the 'disabled by default' value
	                */
	                setDisabledByDefault: function(newValue) {
	                        return pluginDisabledDefault = newValue;
	                }
	        });


	        /**
	                @public
	                @name translation
	                @function
	                @description Get current X and Y translations
	        */
	        jQuery.fn.translation = function() {
	                if (!this[0]) {
	                        return null;
	                }

	                var        elem = this[0],
	                        cStyle = window.getComputedStyle(elem, null),
	                        translation = {
	                                x: 0,
	                                y: 0
	                        };

	                if (cStyle) {
	                        for (var i = cssPrefixes.length - 1; i >= 0; i--) {
	                                var transform = cStyle.getPropertyValue(cssPrefixes[i] + 'transform');
	                                if (transform && (/matrix/i).test(transform)) {
	                                        var explodedMatrix = transform.replace(/^matrix\(/i, '').split(/, |\)$/g);
	                                        translation = {
	                                                x: parseInt(explodedMatrix[4], 10),
	                                                y: parseInt(explodedMatrix[5], 10)
	                                        };

	                                        break;
	                                }
	                        }
	                }

	                return translation;
	        };



	        /**
	                @public
	                @name jQuery.fn.animate
	                @function
	                @description The enhanced jQuery.animate function
	                @param {string} [property]
	                @param {string} [speed]
	                @param {string} [easing]
	                @param {function} [callback]
	        */
	        jQuery.fn.animate = function(prop, speed, easing, callback) {
	                prop = prop || {};

	                var isTranslatable = !(typeof prop['bottom'] !== 'undefined' || typeof prop['right'] !== 'undefined'),
	                        optall = jQuery.speed(speed, easing, callback),
	                        callbackQueue = 0,
	                        propertyCallback = function() {
	                                callbackQueue--;
	                                if (callbackQueue === 0) {
	                                        // we're done, trigger the user callback
	                                        if (typeof optall.complete === 'function') {
	                                                optall.complete.apply(this, arguments);
	                                        }
	                                }
	                        },
	                        bypassPlugin = (typeof prop['avoidCSSTransitions'] !== 'undefined') ? prop['avoidCSSTransitions'] : pluginDisabledDefault;

	                if (bypassPlugin === true || !cssTransitionsSupported || _isEmptyObject(prop) || _isBoxShortcut(prop) || optall.duration <= 0 || optall.step) {
	                        return originalAnimateMethod.apply(this, arguments);
	                }

	                return this[ optall.queue === true ? 'queue' : 'each' ](function() {
	                        var self = jQuery(this),
	                                opt = jQuery.extend({}, optall),
	                                cssCallback = function(e) {
	                                        var selfCSSData = self.data(DATA_KEY) || { original: {} },
	                                                restore = {};

	                                        if (e.eventPhase != 2)  // not at dispatching target (thanks @warappa issue #58)
	                                                return;

	                                        // convert translations to left & top for layout
	                                        if (prop.leaveTransforms !== true) {
	                                                for (var i = cssPrefixes.length - 1; i >= 0; i--) {
	                                                        restore[cssPrefixes[i] + 'transform'] = '';
	                                                }
	                                                if (isTranslatable && typeof selfCSSData.meta !== 'undefined') {
	                                                        for (var j = 0, dir; (dir = directions[j]); ++j) {
	                                                                restore[dir] = selfCSSData.meta[dir + '_o'] + valUnit;

	                                                                jQuery(this).css(dir, restore[dir]);
	                                                        }
	                                                }
	                                        }


	                                        // remove transition timing functions
	                                        self.
	                                                unbind(transitionEndEvent).
	                                                css(selfCSSData.original).
	                                                css(restore).
	                                                data(DATA_KEY, null);

	                                        // if we used the fadeOut shortcut make sure elements are display:none
	                                        if (prop.opacity === 'hide') {
	                                                elem = self[0];
	                                                if (elem.style) {
	                                                        display = jQuery.css(elem, 'display');

	                                                        if (display !== 'none' && !jQuery._data(elem, 'olddisplay')) {
	                                                                jQuery._data(elem, 'olddisplay', display);
	                                                        }
	                                                        elem.style.display = 'none';
	                                                }

	                                                self.css('opacity', '');
	                                        }

	                                        // run the main callback function
	                                        propertyCallback.call(this);
	                                },
	                                easings = {
	                                        bounce: CUBIC_BEZIER_OPEN + '0.0, 0.35, .5, 1.3' + CUBIC_BEZIER_CLOSE,
	                                        linear: 'linear',
	                                        swing: 'ease-in-out',

	                                        // Penner equation approximations from Matthew Lein's Ceaser: http://matthewlein.com/ceaser/
	                                        easeInQuad:     CUBIC_BEZIER_OPEN + '0.550, 0.085, 0.680, 0.530' + CUBIC_BEZIER_CLOSE,
	                                        easeInCubic:    CUBIC_BEZIER_OPEN + '0.550, 0.055, 0.675, 0.190' + CUBIC_BEZIER_CLOSE,
	                                        easeInQuart:    CUBIC_BEZIER_OPEN + '0.895, 0.030, 0.685, 0.220' + CUBIC_BEZIER_CLOSE,
	                                        easeInQuint:    CUBIC_BEZIER_OPEN + '0.755, 0.050, 0.855, 0.060' + CUBIC_BEZIER_CLOSE,
	                                        easeInSine:     CUBIC_BEZIER_OPEN + '0.470, 0.000, 0.745, 0.715' + CUBIC_BEZIER_CLOSE,
	                                        easeInExpo:     CUBIC_BEZIER_OPEN + '0.950, 0.050, 0.795, 0.035' + CUBIC_BEZIER_CLOSE,
	                                        easeInCirc:     CUBIC_BEZIER_OPEN + '0.600, 0.040, 0.980, 0.335' + CUBIC_BEZIER_CLOSE,
	                                        easeInBack:     CUBIC_BEZIER_OPEN + '0.600, -0.280, 0.735, 0.045' + CUBIC_BEZIER_CLOSE,
	                                        easeOutQuad:    CUBIC_BEZIER_OPEN + '0.250, 0.460, 0.450, 0.940' + CUBIC_BEZIER_CLOSE,
	                                        easeOutCubic:   CUBIC_BEZIER_OPEN + '0.215, 0.610, 0.355, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutQuart:   CUBIC_BEZIER_OPEN + '0.165, 0.840, 0.440, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutQuint:   CUBIC_BEZIER_OPEN + '0.230, 1.000, 0.320, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutSine:    CUBIC_BEZIER_OPEN + '0.390, 0.575, 0.565, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutExpo:    CUBIC_BEZIER_OPEN + '0.190, 1.000, 0.220, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutCirc:    CUBIC_BEZIER_OPEN + '0.075, 0.820, 0.165, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeOutBack:    CUBIC_BEZIER_OPEN + '0.175, 0.885, 0.320, 1.275' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutQuad:  CUBIC_BEZIER_OPEN + '0.455, 0.030, 0.515, 0.955' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutCubic: CUBIC_BEZIER_OPEN + '0.645, 0.045, 0.355, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutQuart: CUBIC_BEZIER_OPEN + '0.770, 0.000, 0.175, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutQuint: CUBIC_BEZIER_OPEN + '0.860, 0.000, 0.070, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutSine:  CUBIC_BEZIER_OPEN + '0.445, 0.050, 0.550, 0.950' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutExpo:  CUBIC_BEZIER_OPEN + '1.000, 0.000, 0.000, 1.000' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutCirc:  CUBIC_BEZIER_OPEN + '0.785, 0.135, 0.150, 0.860' + CUBIC_BEZIER_CLOSE,
	                                        easeInOutBack:  CUBIC_BEZIER_OPEN + '0.680, -0.550, 0.265, 1.550' + CUBIC_BEZIER_CLOSE
	                                },
	                                domProperties = {},
	                                cssEasing = easings[opt.easing || 'swing'] ? easings[opt.easing || 'swing'] : opt.easing || 'swing';

	                        // seperate out the properties for the relevant animation functions
	                        for (var p in prop) {
	                                if (jQuery.inArray(p, pluginOptions) === -1) {
	                                        var isDirection = jQuery.inArray(p, directions) > -1,
	                                                cleanVal = _interpretValue(self, prop[p], p, (isDirection && prop.avoidTransforms !== true));


	                                        if (/**prop.avoidTransforms !== true && **/_appropriateProperty(p, cleanVal, self)) {
	                                                _applyCSSTransition(
	                                                        self,
	                                                        p,
	                                                        opt.duration,
	                                                        cssEasing,
	                                                        cleanVal, //isDirection && prop.avoidTransforms === true ? cleanVal + valUnit : cleanVal,
	                                                        isDirection && prop.avoidTransforms !== true,
	                                                        isTranslatable,
	                                                        prop.useTranslate3d);

	                                        }
	                                        else {
	                                                domProperties[p] = prop[p];
	                                        }
	                                }
	                        }

	                        self.unbind(transitionEndEvent);

	                        var selfCSSData = self.data(DATA_KEY);


	                        if (selfCSSData && !_isEmptyObject(selfCSSData) && !_isEmptyObject(selfCSSData.secondary)) {
	                                callbackQueue++;

	                                self.css(selfCSSData.properties);

	                                // store in a var to avoid any timing issues, depending on animation duration
	                                var secondary = selfCSSData.secondary;

	                                // has to be done in a timeout to ensure transition properties are set
	                                setTimeout(function() {
	                                        self.bind(transitionEndEvent, cssCallback).css(secondary);
	                                });
	                        }
	                        else {
	                                // it won't get fired otherwise
	                                opt.queue = false;
	                        }

	                        // fire up DOM based animations
	                        if (!_isEmptyObject(domProperties)) {
	                                callbackQueue++;
	                                originalAnimateMethod.apply(self, [domProperties, {
	                                        duration: opt.duration,
	                                        easing: jQuery.easing[opt.easing] ? opt.easing : (jQuery.easing.swing ? 'swing' : 'linear'),
	                                        complete: propertyCallback,
	                                        queue: opt.queue
	                                }]);
	                        }

	                        // strict JS compliance
	                        return true;
	                });
	        };

	    jQuery.fn.animate.defaults = {};


	        /**
	                @public
	                @name jQuery.fn.stop
	                @function
	                @description The enhanced jQuery.stop function (resets transforms to left/top)
	                @param {boolean} [clearQueue]
	                @param {boolean} [gotoEnd]
	                @param {boolean} [leaveTransforms] Leave transforms/translations as they are? Default: false (reset translations to calculated explicit left/top props)
	        */
	        jQuery.fn.stop = function(clearQueue, gotoEnd, leaveTransforms) {
	                if (!cssTransitionsSupported) return originalStopMethod.apply(this, [clearQueue, gotoEnd]);

	                // clear the queue?
	                if (clearQueue) this.queue([]);

	                // route to appropriate stop methods
	                this.each(function() {
	                        var self = jQuery(this),
	                                selfCSSData = self.data(DATA_KEY);

	                        // is this a CSS transition?
	                        if (selfCSSData && !_isEmptyObject(selfCSSData)) {
	                                var i, restore = {};

	                                if (gotoEnd) {
	                                        // grab end state properties
	                                        restore = selfCSSData.secondary;

	                                        if (!leaveTransforms && typeof selfCSSData.meta['left_o'] !== undefined || typeof selfCSSData.meta['top_o'] !== undefined) {
	                                                restore['left'] = typeof selfCSSData.meta['left_o'] !== undefined ? selfCSSData.meta['left_o'] : 'auto';
	                                                restore['top'] = typeof selfCSSData.meta['top_o'] !== undefined ? selfCSSData.meta['top_o'] : 'auto';

	                                                // remove the transformations
	                                                for (i = cssPrefixes.length - 1; i >= 0; i--) {
	                                                        restore[cssPrefixes[i]+'transform'] = '';
	                                                }
	                                        }
	                                } else if (!_isEmptyObject(selfCSSData.secondary)) {
	                                        var cStyle = window.getComputedStyle(self[0], null);
	                                        if (cStyle) {
	                                                // grab current properties
	                                                for (var prop in selfCSSData.secondary) {
	                                                        if(selfCSSData.secondary.hasOwnProperty(prop)) {
	                                                                prop = prop.replace(rupper, '-$1').toLowerCase();
	                                                                restore[prop] = cStyle.getPropertyValue(prop);

	                                                                // is this a matrix property? extract left and top and apply
	                                                                if (!leaveTransforms && (/matrix/i).test(restore[prop])) {
	                                                                        var explodedMatrix = restore[prop].replace(/^matrix\(/i, '').split(/, |\)$/g);

	                                                                        // apply the explicit left/top props
	                                                                        restore['left'] = (parseFloat(explodedMatrix[4]) + parseFloat(self.css('left')) + valUnit) || 'auto';
	                                                                        restore['top'] = (parseFloat(explodedMatrix[5]) + parseFloat(self.css('top')) + valUnit) || 'auto';

	                                                                        // remove the transformations
	                                                                        for (i = cssPrefixes.length - 1; i >= 0; i--) {
	                                                                                restore[cssPrefixes[i]+'transform'] = '';
	                                                                        }
	                                                                }
	                                                        }
	                                                }
	                                        }
	                                }

	                                // Remove transition timing functions
	                                // Moving to seperate thread (re: Animation reverts when finished in Android - issue #91)
	                                self.unbind(transitionEndEvent);
	                                self.
	                                        css(selfCSSData.original).
	                                        css(restore).
	                                        data(DATA_KEY, null);
	                        }
	                        else {
	                                // dom transition
	                                originalStopMethod.apply(self, [clearQueue, gotoEnd]);
	                        }
	                });

	                return this;
	        };
	})($, $.fn.animate, $.fn.stop);


	/*
	 http://mths.be/placeholder v2.0.7 by @mathias
	 */
	(function(window, document, $) {

	        var isInputSupported = !/Firefox/.test(navigator.userAgent) && 'placeholder' in document.createElement('input');
	        var isTextareaSupported = !/Firefox/.test(navigator.userAgent) && 'placeholder' in document.createElement('textarea');
	        var prototype = $.fn;
	        var valHooks = $.valHooks;
	        var propHooks = $.propHooks;
	        var hooks;
	        var placeholder;

	        if (isInputSupported && isTextareaSupported ) {

	                placeholder = prototype.placeholder = function() {
	                        return this;
	                };

	                placeholder.input = placeholder.textarea = true;

	        } else {

	                placeholder = prototype.placeholder = function() {
	                        var $this = this;
	                        $this
	                                .filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
	                                .not('.placeholder')
	                                .bind({
	                                        'focus.placeholder': clearPlaceholder,
	                                        'blur.placeholder': setPlaceholder
	                                })
	                                .data('placeholder-enabled', true)
	                                .trigger('blur.placeholder');
	                        return $this;
	                };

	                placeholder.input = isInputSupported;
	                placeholder.textarea = isTextareaSupported;

	                hooks = {
	                        'get': function(element) {
	                                var $element = $(element);

	                                var $passwordInput = $element.data('placeholder-password');
	                                if ($passwordInput) {
	                                        return $passwordInput[0].value;
	                                }

	                                return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
	                        },
	                        'set': function(element, value) {
	                                var $element = $(element);

	                                var $passwordInput = $element.data('placeholder-password');
	                                if ($passwordInput) {
	                                        return $passwordInput[0].value = value;
	                                }

	                                if (!$element.data('placeholder-enabled')) {
	                                        return element.value = value;
	                                }
	                                if (value == '') {
	                                        element.value = value;
	                                        // Issue #56: Setting the placeholder causes problems if the element continues to have focus.
	                                        if (element != safeActiveElement()) {
	                                                // We can't use `triggerHandler` here because of dummy text/password inputs :(
	                                                setPlaceholder.call(element);
	                                        }
	                                } else if ($element.hasClass('placeholder')) {
	                                        clearPlaceholder.call(element, true, value) || (element.value = value);
	                                } else {
	                                        element.value = value;
	                                }
	                                // `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
	                                return $element;
	                        }
	                };

	                if (!isInputSupported) {
	                        valHooks.input = hooks;
	                        propHooks.value = hooks;
	                }
	                if (!isTextareaSupported) {
	                        valHooks.textarea = hooks;
	                        propHooks.value = hooks;
	                }

	                $(function() {
	                        // Look for forms
	                        $(document).delegate('form', 'submit.placeholder', function() {
	                                // Clear the placeholder values so they don't get submitted
	                                var $inputs = $('.placeholder', this).each(clearPlaceholder);
	                                setTimeout(function() {
	                                        $inputs.each(setPlaceholder);
	                                }, 10);
	                        });
	                });

	                // Clear placeholder values upon page reload
	                $(window).bind('beforeunload.placeholder', function() {
	                        $('.placeholder').each(function() {
	                                this.value = '';
	                        });
	                });

	        }

	        function args(elem) {
	                // Return an object of element attributes
	                var newAttrs = {};
	                var rinlinejQuery = /^jQuery\d+$/;
	                $.each(elem.attributes, function(i, attr) {
	                        if (attr.specified && !rinlinejQuery.test(attr.name)) {
	                                newAttrs[attr.name] = attr.value;
	                        }
	                });
	                return newAttrs;
	        }

	        function clearPlaceholder(event, value) {
	                var input = this;
	                var $input = $(input);
	                if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
	                        if ($input.data('placeholder-password')) {
	                                $input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
	                                // If `clearPlaceholder` was called from `$.valHooks.input.set`
	                                if (event === true) {
	                                        return $input[0].value = value;
	                                }
	                                $input.focus();
	                        } else {
	                                input.value = '';
	                                $input.removeClass('placeholder');
	                                input == safeActiveElement() && input.select();
	                        }
	                }
	        }

	        function setPlaceholder() {
	                var $replacement;
	                var input = this;
	                var $input = $(input);
	                var id = this.id;
	                if (input.value == '') {
	                        if (input.type == 'password') {
	                                if (!$input.data('placeholder-textinput')) {
	                                        try {
	                                                $replacement = $input.clone().attr({ 'type': 'text' });
	                                        } catch(e) {
	                                                $replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
	                                        }
	                                        $replacement
	                                                .removeAttr('name')
	                                                .data({
	                                                        'placeholder-password': $input,
	                                                        'placeholder-id': id
	                                                })
	                                                .bind('focus.placeholder', clearPlaceholder);
	                                        $input
	                                                .data({
	                                                        'placeholder-textinput': $replacement,
	                                                        'placeholder-id': id
	                                                })
	                                                .before($replacement);
	                                }
	                                $input = $input.removeAttr('id').hide().prev().attr('id', id).show();
	                                // Note: `$input[0] != input` now!
	                        }
	                        $input.addClass('placeholder');
	                        $input[0].value = $input.attr('placeholder');
	                } else {
	                        $input.removeClass('placeholder');
	                }
	        }

	        function safeActiveElement() {
	                // Avoid IE9 `document.activeElement` of death
	                // https://github.com/mathiasbynens/jquery-placeholder/pull/99
	                try {
	                        return document.activeElement;
	                } catch (err) {}
	        }

	}(this, document, $));

	// jQuery form validation light-weight plugin
	// https://github.com/Tape/jValidator
	;(function($) {

		/* Defaults
		 * ======== */

		var
		defaults = {
				api_fn: function(e) {
						if(!$(this).jvalidator().valid)
								e.preventDefault();
				},
				api_selector: "[data-validate=true]",
				error_fn: $.noop,
				field_attr: "name",
				fields: "input, textarea, select",
				regex: { email: /.+@.+/ },
				use_api: true
		},

		ips = {
				4: { split: ".", segments: 4, radix: 10, upper: 255 },
				6: { split: ":", segments: 8, radix: 16, upper: 0xFFFF }
		},

		functions = {
				required: function() {
						return this.field === true || !!this.field.length;
				},

				min: function(len) {
						return this.field.length >= +len;
				},

				max: function(len) {
						return this.field.length <= +len;
				},

				matches: function(field) {
						return this.form[field] === this.field;
				},

				less: function(num) {
						return +this.field < +num;
				},

				greater: function(num) {
						return +this.field > +num;
				},

				numeric: function() {
						return !isNaN(+this.field);
				},

				email: function() {
						return this.field.match(this.opts.regex.email);
				},

				answer: function(answer) {
						return +this.field == +answer
				},

				ip: function(verno) {
						var ver = ips[verno ? verno : "4"],
								parts = this.field.split(ver.split),
								i = 0, num;

						if(!ver || parts.length != ver.segments)
								return false;

						for(; i < ver.segments; i++) {
								num = parseInt(parts[i], ver.radix);
								if(num < 0 || num > ver.upper)
										return false;
						}

						return true;
				}
		},

		errors =  window.NinjaContactFormOpts ?
			window.NinjaContactFormOpts.errors :
			{
				required: "* Please enter %%",
				min: "* %% must have at least %% characters.",
				max: "* %% can have at most %% characters.",
				matches: "* %% must match %%.",
				less: "* %% must be less than %%",
				greater: "* %% must be greater than %%.",
				numeric: "* %% must be numeric.",
				email: "* %% must be a valid email address.",
				ip: "* %% must be a valid ip address.",
				answer: "* Wrong %%"
			};

		/* Data API Definitions
		 * ==================== */

		$(function() {
				if(!defaults.use_api)
						return;

				$("html").on("submit", defaults.api_selector, defaults.api_fn);
		});

		/* Helper functions
		 * ================ */

		function extract(el) {
				return el.type === "checkbox" ? el.checked : $(el).val();
		}

		function processError(fn, name, params) {
				var errorstr = errors[fn];
				params.unshift(name);

				while(params.length)
						errorstr = errorstr.replace(/%%/, params.shift());

				return errorstr;
		}

		function capitaliseFirstLetter(string)
		{
		    return string.charAt(0).toUpperCase() + string.slice(1);
		}

		/* API Declarations
		 * ================ */

		$.fn.jvalidator = function(options) {
				var opts = $.extend({}, defaults, options),
						form = {},
						context = { opts: opts, form: form },
						errors = [],
						fields = $(opts.fields, this),
						error;

				//Loop to store the existing form data for validation.
				fields.filter("[" + opts.field_attr + "]").each(function() {
						form[this.name] = extract(this);
				});

				//Loop to validate each field.
				fields.filter("[data-rules]").each(function() {
						var t = $(this),
								rules = t.data("rules").split("|"),
								regex = /^(.+)\[(.+)\]$/,
								i = 0, match, fn, params;

						//Set the value of the field for the context.
						context.obj = t;
						context.field = extract(this);
						//Loop through each rule.
						for(; i < rules.length; i++) {
								if((match = rules[i].match(regex))) {
										fn = match[1];
										params = match[2].split(",");
								} else {
										fn = rules[i];
										params = [];
								}

								if(!functions[fn].apply(context, params)) {
										error = processError(fn, t.data("name") || this[opts.field_attr], params);
										opts.error_fn(t, error);
										errors.push(error);
								}
						}
				});

				//Return the final result set.
				return {
						valid: !errors.length,
						errors: errors,
						fields: form
				};
		};

		$.fn.jvalidator.defaults = defaults;
		$.fn.jvalidator.errors = errors;
		$.fn.jvalidator.functions = functions;

	})($);

	if ($('body').is('.admin-bar')) { $('html').attr('style', 'margin-top: 0 !important'); }

	$.extend($.easing, { easeOutSine: function(x, t, b, c, d) {
	 return c * Math.sin(t / d * (Math.PI / 2)) + b;
	}});
	$.extend($.easing, {easeInOutSine: function(x, t, b, c, d) {
       return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
   }});

	// main logic, human readable
	setTimeout(function () {

	  var NinjaSidebar = window.NinjaSidebar || (function () {
	    var opts = window.NinjaContactFormOpts;

	    // caching
	    var $win = $(window);
	    var $html = $('html');
	    var $body = $('body');
		  var bodyOverflowX = $body.css('overflowX');
		  var labelTopPercent = opts.label_top ? opts.label_top : '50%';
	    var $label = $('<div class="ncf_trigger_label '+ (opts.label.indexOf('rectangle') === -1 ? 'ncf_label_' + opts.label : 'ncf_rect ncf_color1')  + ' ncf_label_' + opts.label_vis + '" style="top:'+ labelTopPercent+ '"><span class="ncf_color1"></span></div>').appendTo($body);
		  var labelMargin = $label.css('marginTop');
	    var $overlay = $('<div id="ns-overlay"></div>');
	    var $sidebar = $('#ncf_sidebar');
	    var $cont = $sidebar.find('.ncf_sidebar_cont');
			var $social = $sidebar.find('.ncf_sidebar_socialbar');
			var $form = $sidebar.find('form.ncf_form');
		  var $formResult = $('.ncf_form_result');
	    var $outer;
	    var $inner;

		  var direction = opts.sidebar_pos;
		  var opposite = direction === 'left' ? 'right' : 'left';
		  var reset = (function () {
			  var o = {};
			  o[direction] = 0;
			  o[opposite] = 'auto';
			  return o;
		  }());
		  var processingRequest = false;
		  var origDocWrite;
		  var webkit = /safari|chrome/.test(navigator.userAgent.toLowerCase());

			// jQuery has bug in Firefox when getting just background
			var htmlCss = {
				'backgroundColor':$html.css('backgroundColor'),
				'backgroundImage':$html.css('backgroundImage'),
				'backgroundAttachment':$html.css('backgroundAttachment'),
				'backgroundSize':$html.css('backgroundSize'),
				'backgroundPosition':$html.css('backgroundPosition'),
				'backgroundRepeat':$html.css('backgroundRepeat'),
				'backgroundOrigin':$html.css('backgroundOrigin'),
				'backgroundClip':$html.css('backgroundClip'),
				'margin': $html.css('margin'),
				'marginTop': $html.css('marginTop'),
				'marginLeft': $html.css('marginLeft'),
				'marginRight': $html.css('marginRight'),
				'padding': $html.css('padding'),
				'paddingTop': $html.css('paddingTop'),
				'paddingLeft': $html.css('paddingLeft'),
				'paddingRight': $html.css('paddingRight'),
				'box-sizing': $html.css('box-sizing')
			};
		  if (htmlCss.backgroundColor.indexOf('(0, 0, 0, 0') + 1 || htmlCss.backgroundColor.indexOf('transparent') + 1) {
			  htmlCss.backgroundColor = '#fff';
		  }
		  if (htmlCss.margin === 'auto') htmlCss.margin = 0;

			var bodyCss = {
				'backgroundColor':$body.css('backgroundColor'),
				'backgroundImage':$body.css('backgroundImage'),
				'backgroundAttachment':$body.css('backgroundAttachment'),
				'backgroundSize':$body.css('backgroundSize'),
				'backgroundPosition':$body.css('backgroundPosition'),
				'backgroundRepeat':$body.css('backgroundRepeat'),
				'backgroundOrigin':$body.css('backgroundOrigin'),
				'backgroundClip':$body.css('backgroundClip'),
				'margin': $body.css('margin'),
				'marginTop': $body.css('marginTop'),
				'marginLeft': $body.css('marginLeft'),
				'marginRight': $body.css('marginRight'),
				'padding': $body.css('padding'),
				'paddingTop': $body.css('paddingTop'),
				'paddingLeft': $body.css('paddingLeft'),
				'paddingRight': $body.css('paddingRight'),
				'box-sizing': $body.css('box-sizing')
			};
		  var htmlClasses;
		  var bodyClasses;

		  if (htmlCss.backgroundImage === 'none' && (bodyCss.backgroundColor.indexOf('(0, 0, 0, 0') + 1 || bodyCss.backgroundColor.indexOf('transparent') + 1) ) {
        bodyCss.backgroundColor = '#fff';
      }

		  var answer;
		  var isMobile = /Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent);
	    var isAnimated = false;
		  var isExposed = false;
		  var opacityLevel = opts.fade_content === 'light' ? 0.3 : (opts.fade_content === 'dark' ? 0.7 : 0);


		  var prefix = (function () {
			  if (!window.getComputedStyle) return {dom: 'ms', lowercase: 'ms', css: '-ms-', js: 'ms'}
		 	  var styles = window.getComputedStyle(document.documentElement, ''),
		 	    pre = (Array.prototype.slice
		 	      .call(styles)
		 	      .join('')
		 	      .match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
		 	    )[1],
		 	    dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];
		 	  return {
		 	    dom: dom,
		 	    lowercase: pre,
		 	    css: '-' + pre + '-',
		 	    js: pre[0].toUpperCase() + pre.substr(1)
		 	  };
		 	})();

	    function init() {
				populateSocialBarWith(opts.social, opts.theme);

	      $label.add('.ncf_trigger_element').click(function(){
		      if (isExposed) hideSidebar()
		      else showSidebar();
          return false;
	      });

		    if (opts.label_mouseover) {
			    $label.add('.ncf_trigger_element').mouseover(function () {
				    if (!isExposed) NinjaSidebar.showSidebar()
			    })
		    }

		    $body.addClass('ncf_sidebar_pos_' + direction);
		    $cont.data('init_margins', $cont.css('margin'));
		    $.setDisabledByDefault(true); // enhanced animation disabled by default

	      $sidebar
					.find('a.ncf_button').click(function(){
			      //$('.ncf_err_msg:visible').css('left', 3).animate(cssObject('left', 0), {duration:200});

						var results = $form.jvalidator({error_fn: showErrors});
						if (results.valid) {
							$form.submit();
						}
						else {

						}
	          return false;
	      	}).end()
					.find('input, textarea')
		      .bind('input', function(){
            $(this).parent().find('.ncf_err_msg:visible').slideUp();
          })
          .placeholder();

		    // reset inputs
		    setTimeout(function(){$form.find('input, textarea').not(':hidden').val('')}, 0);

        if (/MSIE 8/.test(navigator.userAgent)) {
          $sidebar.find('input, textarea').bind('keydown', function () {
            $(this).parent().find('.ncf_err_msg:visible').slideUp();
          });
	        if (opts.theme === 'aerial') {
            $sidebar.addClass('msie8');
          }
        }

				$form.submit(function(){
					if (processingRequest) return false;
					processingRequest = true;
					var $btn = $form.find('.ncf_button').find('span');
					var sendT = $btn.text();
					$btn.html(opts.sending_text + '...');

					(function blink(){
						blink.cacheOp = blink.cacheOp === 1 ? 0.5 : 1;
						$btn.fadeTo(400, blink.cacheOp, blink);
					})();

					$.ajax({
						type: "post",
						url: opts.ajaxurl,
						data: $form.serialize(),
						dataType: "json",
						success: function() {
							processingRequest = false;
							$btn.stop().html(sendT);
							$form.fadeOut(200, function(){
								var btnClass = opts.theme === 'flat' ? 'ncf_color8' : 'ncf_color1';
								$formResult.html(
									'<p class="ncf_form_res_message">' + opts.success +'</p>' +
									'<p class="ncf_btn_wrapper"><a class="ncf_button ncf_btn_more '+ btnClass + '" href="#">'+ opts.send_more_text + '</a></p>' +
									'<p class="ncf_btn_wrapper"><a class="ncf_button ncf_btn_close '+ btnClass + '" href="#">'+ opts.close_text + '</a></p>'
								).fadeIn();
							})
						},
						error: function(){
							processingRequest = false;
							$btn.stop().html(sendT);
							$form.fadeOut(200, function(){
								var btnClass = opts.theme === 'flat' ? 'ncf_color8' : 'ncf_color1';
								$formResult.html('<p class="ncf_form_res_message">'+ opts.msg_fail_text + '</p>' +
									'<p class="ncf_btn_wrapper"><a class="ncf_button ncf_btn_more '+ btnClass + '" href="#">'+ opts.try_again_text + '</a></p>' +
									'<p class="ncf_btn_wrapper"><a class="ncf_button ncf_btn_close '+ btnClass + '" href="#">'+ opts.close_text + '</a></p>'
									).fadeIn()
							})
						}
					});
					return false; // prevent default
				});

		    $cont.delegate('a', 'click', function(){
					var $t = $(this);
			    if ($t.is('.ncf_btn_more')) {
				    $formResult.fadeOut(200, function(){
					    $form.fadeIn(200);
				    })
				    return false;
			    } else if ($t.is('.ncf_btn_close')) {
				    hideSidebar(function () {
              $formResult.hide();
              $form.show();
				    });
				    return false
			    }
		    });

		    if (opts.humantest){
			    (function(){
				    var number1 = Math.ceil(Math.random() * 10);
				    var number2 = Math.ceil(Math.random() * 10);
				    answer = number1 + number2;
				    $('#ncf_question').html(number1 + ' + ' + number2 + ' = ');
				    $('#ncf_answer_field').attr('data-rules', 'required|numeric|answer[' + answer +']');
			    }())
		    }

		    if (opts.label_vis === 'scroll') {
			    $win.one('scroll', function(){
				    $label.removeClass('ncf_label_scroll')
			    });
		    } else if (opts.label_scroll_selector && opts.label_vis !== 'visible') {
          var $elToScroll = $(opts.label_scroll_selector).eq(0);
          $win.scroll(function(){
            if (isExposed) return;
            isScrolledIntoView($elToScroll) ? $label.removeClass('ncf_label_scroll_into') : $label.addClass('ncf_label_scroll_into')
          }).scroll();
        }

		    // themes specific
				if (opts.theme !== 'minimalistic' && opts.flat_socialbar === 'bottom') {
					$social.parent().append($social);
				}

		    if (opts.theme === 'minimalistic') {
			    var basecolor = $('.ncf_button').css('backgroundColor');
			    $form.find('.ncf_form_input_wrapper :input').focusin(function(){
				    $(this).parent().css('backgroundColor', basecolor);
			    }).focusout(function(){
            $(this).parent().css('backgroundColor', '#cccccc');
          });
		    }
		    if(opts.theme === 'aerial') {
			    $form.find('.ncf_form_input_wrapper :input').focusin(function(){
            $(this).parent().addClass('ncf_focusin');
          }).focusout(function(){
            $(this).parent().removeClass('ncf_focusin');
          });
		    }

		    $win.resize(resize);
		    $cont.css({minHeight: $cont.height()});

		    if ($body.is('.admin-bar')) { $html.attr('style', 'margin-top: 0 !important'); }

		    NinjaSidebar.init = function () {};
	      return this;
	    }

		  function showSidebar (callback) {
        if (isAnimated || isExposed) return;
        else isAnimated = true;

        var ww = $win.width();
        var wh = $win.height();
        var invoked = !!$('body .bodyWrapperOuter').length;
        var shift = ww > 500 ? 500 : ww - 40;
        var scrollLeft;
			  var scrollTop;

        // first time
        if (!invoked) {
	        origDocWrite = document.write;
	        document.write = function () {};
	        scrollLeft = $body.scrollLeft();
	        scrollTop = $body.scrollTop();
	        $body.children().not('script, #nks_cc_sidebar').wrapAll('<div class = "bodyWrapperOuter"><div class="bodyWrapperInner"></div></div>');
          document.write = origDocWrite;
          $body.append($label);
        }

        $outer = $('.bodyWrapperOuter');
        $inner = $('.bodyWrapperInner');

        if (!invoked) {
          // replicate html & body styles
	        $body.add($html).css({margin:0, padding:0});
	        htmlClasses = $html.attr('class');
	        bodyClasses = $body.attr('class');
          $outer.css(htmlCss).addClass(htmlClasses);
          $inner.css(bodyCss).addClass(bodyClasses).scrollLeft(scrollLeft).scrollTop(scrollTop);
	        $inner.append($overlay);

          $sidebar
            .appendTo($body)
            .css(direction, direction === 'left' ? 0 : -40);

	        if (isMobile) attachSwipesHandler();
        } else if ($('#nks_cc_sidebar').length) { // compatibility
	        $sidebar
           .appendTo($body)
           .css(direction, direction === 'left' ? 0 : -40);
	        $body.append($label);
	        $inner.append($overlay);
        }

			  if ($.fn.layerSlider) $('div[id*=layerslider]').layerSlider('stop');

        var labelCss = {
          'position': 'absolute',
          'top':        0,
          'marginTop':  parseInt($label.css('top'),10) + $sidebar.offset().top + 1
        };
			  labelCss['margin-' + direction] = scrollLeft ? scrollLeft : $inner.scrollLeft();
        if (direction === 'right') labelCss['margin-' + direction] = -labelCss['margin-' + direction];

        $inner.append($label.css(labelCss));
        //$label.animate(cssObject('opacity', 0), {duration: 200});
        $win.scroll(freezeX);
			  $sidebar.find('.ncf_sidebar_cont_scrollable').bind('mousewheel DOMMouseScroll', freezeBodyScroll);

        $body.css('overflowX', 'hidden').addClass('ncf_exposed'); // hide scrollbars
        //if (!isMobile) $form.find('#ncf_name_field').focus();
        $inner.css('minHeight', wh);
			  $overlay.css('visibility', 'visible');


			  shiftFixed(shift);

        setTimeout(function(){

	          $outer
		          .css(reset) // reset opposite value
		          .bind('mousewheel DOMMouseScroll', freezeBody)
		          .animate(cssObject(direction, shift), {
		            duration: 400,
		            complete: function () {
		              $outer.css({'overflowX':'hidden', maxWidth:ww - (direction === 'left' ? shift : 0)});
		              $inner.width(ww).click(hideSidebar);
		              $win.unbind('scroll', freezeX);

		              labelCss = {'position': 'fixed', top: labelTopPercent, marginTop : labelMargin};
		              labelCss['margin-' + direction] = 0;
		              $body.append(
		                  $label.css($.extend(labelCss, cssObject(direction, shift)))
		              );

		              if (!isMobile) $form.find('#ncf_name_field').focus();

		              isAnimated = false;
		              isExposed = true;
		              if (typeof callback === 'function') callback();
            }
          });

	        setTimeout(function(){
		        console.log(ww)

		        if (ww < 540) {
			        var coof = ww > 400 ? ww / 500 : ((ww - 40) / 400);
			        var margin = ww > 400 ? '20px auto 20px ' + (ww / 20) + 'px' : 0;
			        var css = {'margin':margin};
			        css[prefix.css + 'transform'] = 'scale(' + coof + ', ' + coof + ')';
			        $cont.removeClass('shrinked').css(css);
		        } else {
			        $cont.removeClass('shrinked')
		        }
	        },100)

        }, invoked ? 25 : 75);

      }

      function hideSidebar(callback) {
        if (isAnimated || !isExposed) return
        else isAnimated = true;

        var ww = $win.width();
        var shift = ww > 500 ? 500 : ww - 40;
        var scrollLeft = $inner.scrollLeft();

        $win.scroll(freezeX);
	      $sidebar.find('.ncf_sidebar_cont_scrollable').unbind('mousewheel DOMMouseScroll', freezeBodyScroll);

        var labelCss = {'position': 'absolute', top: 0, marginTop : parseInt($label.css('top'), 10) + $sidebar.offset().top + 1};
        labelCss['margin-' + direction] = direction === 'left' ? scrollLeft: -scrollLeft;
        labelCss[direction] = 0;
        $inner.append(
          $label.css(labelCss)
        );

	      $outer
          .css({'maxWidth': '', 'overflowX': ''})
          .unbind('mousewheel DOMMouseScroll', freezeBody)

        setTimeout(function(){

          $outer.animate(cssObject(direction, 0), {
            duration: 400,
            easing: $.easing && $.easing.easeInOutSine ? 'easeInOutSine' : 'swing',
            complete:  function () {

              // reset
              $inner.unbind('click', hideSidebar).css('width', 'auto');
              $win.unbind('scroll', freezeX);
              $cont.css({'transform' : 'scale(1,1)', 'margin': ''});
              labelCss = {'position': 'fixed', top: labelTopPercent, marginTop: labelMargin};
              labelCss['margin-' + direction] = 0;
              $body
                .removeClass('ncf_exposed')
                .css('overflowX', bodyOverflowX)
                .append($label.css(labelCss));

              $cont.addClass('shrinked');
              unshiftFixed(shift);

	            setTimeout(function(){$overlay.css('visibility', 'hidden')}, 500)

	            if ($.fn.layerSlider) $('div[id*=layerslider]').layerSlider('start');

              isAnimated = false;
              isExposed = false;
              if (typeof callback === 'function') callback();
          }});
        }, 25)
      }

	    function freezeX (){
	      window.scrollTo(0, $win.scrollTop());
	    }

			function populateSocialBarWith (social, theme) {
				var $items = $social.find('li');
				var len = $items.length;
				var href;
				var name;
				var index = 0;

				for (name in social) {
					if (social.hasOwnProperty(name)) {
						index++;
					}
				}

				if (index === 0 && theme === 'minimalistic') {
					$social.add('.ncf_line_sep:last').hide();
				}

				for (name in social) {
					if(social.hasOwnProperty(name) && index >= 0) {
						href = social[name];
						$('<a class="'+ name + '" href="'+ href +'" target="_blank"></a>').appendTo($items.eq(len - index));
						index--;
					}
				}

				if (theme !== 'flat') {
						$social.find('li:empty').remove();
						/*
						var insertTo = 3;
						var step = 0;
						for (name in social) {
							if (social.hasOwnProperty(name) && index >= 0) {
								href = social[name];
								insertTo = insertTo + step;
								$items.eq(insertTo).append('<a class="'+ name + '" href="'+ href +'">');

								step = -step;
								step >= 0 ? step++ : step--;
								index--;
							}
						}*/
				}
			}

		  function cssObject (css, val) {
			  var o = {};
			  o[css] = val;
			  o['avoidCSSTransitions'] = false;
			  o['useTranslate3d'] = true;
			  return o;
		  }

		  function clearCss ($el) {
			  $el.css('transition', '');
		  }


		  function shiftFixed(shift) {
			  var scrollTop = $win.scrollTop();
			  var children;
			  var $cont = $('#ncf_fixed_elements_container')
				if ($cont.length) {
					$children = $cont.find('*');
					$children.each(function (i, el) {
		        var $t = $(this);
			      fadeOutCSS($t, scrollTop);
		      });
				} else {
					$children = $inner.find('*');
					$body.append($('<div id="ncf_fixed_elements_container"></div>'));
					$cont = $('#ncf_fixed_elements_container');
					$children.each(function (i, el) {
		        var $t = $(this);
		        if ($t.css('position') === 'fixed') {
			        fadeOutCSS($t, scrollTop);
			        $cont.append($t);
		        }
		      });
				}


			  $('.nks_cc_trigger_label').animate(cssObject('opacity', 0));
	    }


	    function unshiftFixed(shift) {
	      var $children = $('#ncf_fixed_elements_container').find('.ncf_body_fixed_el');
	      $children.add('.nks_cc_trigger_label').animate(cssObject('opacity', 1));
	    }

		  function showErrors(input, error) {
			  var $t = $(input);
			  var $parent = $t.parent();
			  var $err = $parent.has('.ncf_err_msg').length ? $parent.find('.ncf_err_msg') : $('<div class="ncf_err_msg"></div>').appendTo($parent);

			  if (!$err.is(':visible')) {
				  $err.html(error).slideDown( 200 );
			  }
		  }

		  function fadeOutCSS ($el, scrollTop) {
			  //if (scrollTop > 0 && webkit) $el.css({top: '+=' + scrollTop}); // webkit
			  $el.addClass('ncf_body_fixed_el').animate(cssObject('opacity', 0));
		  }

		  function isScrolledIntoView($elem)
		  {
		      var docViewTop = $win.scrollTop();
		      var docViewBottom = docViewTop + $win.height();

		      var elemTop = $elem.offset().top;
		      var elemBottom = elemTop /*+ $elem.height()*/; // when element scrolls into view

		      return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
		  }

		  function resize () {
			  var ww;
			  if (isExposed) {
				  ww = $win.width();
				  if (ww < 540) {
					  //var coof = ww / 500;
					  //$cont.css({'transform':'scale(' + coof + ', ' + coof + ')', 'margin':'20px auto 20px ' + (ww / 16) + 'px'});
					  var coof = ww > 400 ? ww / 500 : ((ww - 40) / 400);
						var margin = ww > 400 ? '20px auto 20px ' + (ww / 20) + 'px' : 0;
					  var css = {'margin':margin};
            css[prefix.css + 'transform'] = 'scale(' + coof + ', ' + coof + ')';
            $cont.css(css);
					  $outer.css($.extend(cssObject(direction, ww - 40), {maxWidth:ww - (direction === 'left' ? 40 : 0)}));
            $label.css(cssObject(direction, ww - 40));

				  } else {
					  $cont.css({'transform' : 'scale(1,1)', 'margin': ''});
					  $outer.css($.extend(cssObject(direction, 500), {maxWidth:ww - (direction === 'left' ? 500 : 0)}));
            $label.css(cssObject(direction, 500));

				  }
				  $inner.width(ww);
			  }
		  }

		  function attachSwipesHandler () {
        var startX, startY, startTime, moveX, moveY;
        $sidebar.add($outer).bind('touchstart', function (e) {
          if (isExposed) {
            startTime = (new Date).getTime();
            startX = e.originalEvent.touches[0].pageX;
            startY = e.originalEvent.touches[0].clientY;
          }
        })
        .bind('touchmove', function (e) {
          if (isExposed) {
            moveX = e.originalEvent.touches[0].pageX;
            moveY = e.originalEvent.touches[0].clientY
          }
        })
        .bind('touchend', function () {
          if (isExposed) {
            var swipeDirection = moveX > startX ? "right" : "left";
            var finalY = moveY - startY > 30 || -30 > moveY - startY;
            var finalX = moveX - startX > 60 || -60 > moveX - startX;
            var now = (new Date).getTime();
            if (!(now - startTime > 500 || finalY) && finalX) {
              switch (swipeDirection) {
                case "left":
                  "left" === direction ? hideSidebar() : showSidebar();
                  break;
                case "right":
                  "left" === direction ? showSidebar() : hideSidebar()
              }
            }
          }
        });
      }

		  function freezeBodyScroll(e) {
	      var scrollTo = null;

	      if (e.type == 'mousewheel') {
	        scrollTo = (e.originalEvent.wheelDelta * -1);
	      }
	      else if (e.type == 'DOMMouseScroll') {
	        scrollTo = 40 * e.originalEvent.detail;
	      }

	      if (scrollTo) {

	        e.preventDefault();
	        $(this).scrollTop(scrollTo + $(this).scrollTop());
	      }
	    }

	    function freezeBody(e) {
	      if (e.type == 'mousewheel' || e.type == 'DOMMouseScroll') {
	        e.preventDefault()
	      }
      }

		  function visible () {
			  return isExposed
		  }

	    return {
	      init: init,
		    showSidebar: showSidebar,
		    hideSidebar: hideSidebar,
		    visible: visible
	    }
	  }());

		window.NinjaSidebar = NinjaSidebar.init();

	}, 0)
});






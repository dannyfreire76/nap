/**
 * @author redwards
 */

function _(obj) {

    /**
     * source converts ampersands, quotes and angle brackets to equivalent html entities
     * @param {Object} x
     */

    function source(x) {
        return x.toString().replace(/&/g, '&amp;').replace(/\"/g, '&quot;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;')
    }
    _.source = source; //publish source function
    /**
     * q returns a double-quoted value
     * @param {Object} x
     */

    function q(x) {
        return ['"', '"'].join(x)
    } // array.join outperforms string concatenation.
    _.q = q;

    /**
     * s returns a single-quoted value
     * @param {Object} x
     */

    function s(x) {
        return ["'", "'"].join(x)
    } // Don't like \"escaped\" quotes?  Neither do I.
    /**
     * d returns a Date object constructed from a string value
     * @param {Object} x
     */

    function d(x) {
        return ['new Date( "', '" )'].join(x)
    } // Date's constructor accepts a string
    /**
     * a accepts an array and returns its implicit string representation in square brackets.
     * @param {Object} x
     */

    function a(x) {
        return ['[ ', ' ]'].join(x)
    } // Array's toString implicitly provides commas.
    /**
     * o accepts an array and returns its implicit string representation in curly brackets.
     * @param {Object} x
     */

    function o(x) {
        return ['{ ', ' }'].join(x)
    } // Properties are presented in discovery order.
    /**
     * p accepts a property (as an attribute value pair) and returns its string representation.
     * @param {Object} a
     * @param {Object} v
     */

    function p(a, v) {
        return [a, v].join(': ')
    } // pretty spacing included in d(x),a(x),o(x),p(a,v).
    /**
     * json accepts an object and returns its string representation.
     * @param {Object} x
     */

    function json(x) {
        switch (x.constructor) {
        case Array:
            var X = [];
            for (var i = 0; i < x.length; i++)
            X.push(' ' + json(x[i])); //recursive
            return a(X);
            break;
        case Object:
            var X = [];
            for (var i in x)
            X.push(' ' + p(i, json(x[i]))); //recursive
            return o(X);
            break;
        case String:
            return isNaN(x) ? s(x) : x;
            break;
        case Date:
            return d(x);
            break;
        default:
            return x;
        }
    }
    _.json = json; //publish json function
    /**
     * av accepts an attribute value pair and returns an attribute equals double-quoted value string.
     * @param {Object} a
     * @param {Object} v
     */

    function av(a, v) {
        return [a, q(v)].join('=')
    } // Concatenation avoidance isn't very reader-friendly.
    /**
     * t accepts a tag name (with optional attribute value pairs) and returns an appropriate xhtml tag.
     * @param {Object} x
     */

    function t(x) {
        return ['<', '>'].join(x.join(' '))
    } // The plus sign union is upset about this one!
    /**
     * tavc (Tag Attribute=Value Content) accepts a non-empty, one-dimensional array of
     * xhtml tag components, starting with the required tag name, followed by optional
     * attribute value pairs, and optionally concluded with a content item for enclosure.
     * If the content item is an array, each element is individually wrapped.
     *
     * @param {Object} x
     */

    function tavc(x) {
        var T = x[0]; // tag name
        var X = [T]; // tag (prior to inclusion of properties)
        for (var i = 2; i < x.length; i += 2)
        X.push(av(x[i - 1], x[i])); // tag with properties
        if (x.length % 2) {
            X.push('/');
            return t(X); // empty tag (has no optional content)
        } else {
            var C = x[x.length - 1]; // unmatched element is content
            if (C.constructor === Array) C = C.join(t(['/' + T]) + t(X)); // prepare array to..
            return [t(X), t(['/' + T])].join(C); // wrap content(s)
        }
    }
    _.tavc = tavc;

    /**
     * TAVC (Tags Attributes=Values Contents) accepts a non-empty, two-dimensional array
     * of one-dimensional arrays.
     * @param {Object} X
     */

    function TAVC(X) {
        var values = [].concat(X); //must be a 1-dimensional array suitable for tavc.
        for (var i = 0; i < values.length; i++)
        values[i] = [].concat(values[i]); //each value must be an array for striping.
        var pending = 0;
        for (var i = 0; i < values.length; i++)
        pending = Math.max(pending, values[i].length); //determine number of constructs.
        for (var i = 0; i < values.length; i++) {
            while (values[i].length < pending) {
                var x = values[i].pop();
                values[i].push(x);
                values[i].push(x); //echo the last value given for all missing values.
            }
        }
        var constructs = [];
        for (var i = 0; i < values[0].length; i++) {
            constructs.push([]); //start a new construct...
            for (var j = 0; j < values.length; j++) {
                var value = values[j];
                constructs[i].push(value[i]); //add values required for this construct.
            }
            constructs[i] = tavc(constructs[i]); //build it!
        }
        String.prototype._ = constructs.slice(0); //makes last array available as ._
        return constructs.join(''); //return completed constructs as a single string.
    }
    _.TAVC = TAVC;

    // process _ arguments ...
    switch (_.arguments.length) {
    case 0:
        return; //register interface only
        break;
    case 1:
        switch (obj.constructor) {
        case jQuery:
            return json(obj.toArray());
            break;
        case Array:
            return TAVC(obj);
            break;
        default:
            return json(obj);
        }
        break;
    default:
        var arr = [];
        for (var i = 0; i < _.arguments.length; i++)
        if (_.arguments[i].constructor === Object) {
            var properties = _.arguments[i]; //TODO: Make this Object option more robust, implementing nested elements, etc.
            for (var property in properties) {
                arr.push(property);
                arr.push(properties[property]);
            }
        } else {
            arr.push(_.arguments[i]); //queue multiple arguments
        }
        return _(arr);
    }
}

(function($) {
    $.fn.alert = function(msg) {
        msg = (arguments.length < 1) ? 'jQuery =' : msg;
        alert([msg, _(this)].join('\n\n'));
        return this;
    }
    return $;
})(jQuery);

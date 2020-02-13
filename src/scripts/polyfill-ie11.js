/* Polyfill service v3.42.0
 * For detailed credits and licence information see https://github.com/financial-times/polyfill-service.
 *
 * Features requested: Object.assign
 *
 * - _ESAbstract.Get, License: CC0 (required by "Object.assign")
 * - _ESAbstract.IsCallable, License: CC0 (required by "Function.prototype.bind", "Object.getOwnPropertyDescriptor", "Object.assign")
 * - _ESAbstract.ToObject, License: CC0 (required by "Object.assign")
 * - Object.defineProperty, License: CC0 (required by "_ESAbstract.CreateMethodProperty", "Object.assign")
 * - _ESAbstract.CreateMethodProperty, License: CC0 (required by "Object.assign", "Object.getOwnPropertyDescriptor", "Object.keys", "Function.prototype.bind")
 * - Function.prototype.bind, License: MIT (required by "Object.getOwnPropertyDescriptor", "Object.assign")
 * - Object.getOwnPropertyDescriptor, License: CC0 (required by "Object.assign")
 * - Object.keys, License: MIT (required by "Object.assign")
 * - Object.assign, License: CC0 */

(function(undefined) {

// _ESAbstract.Get
// 7.3.1. Get ( O, P )
    function Get(O, P) { // eslint-disable-line no-unused-vars
        // 1. Assert: Type(O) is Object.
        // 2. Assert: IsPropertyKey(P) is true.
        // 3. Return ? O.[[Get]](P, O).
        return O[P];
    }

// _ESAbstract.IsCallable
// 7.2.3. IsCallable ( argument )
    function IsCallable(argument) { // eslint-disable-line no-unused-vars
        // 1. If Type(argument) is not Object, return false.
        // 2. If argument has a [[Call]] internal method, return true.
        // 3. Return false.

        // Polyfill.io - Only function objects have a [[Call]] internal method. This means we can simplify this function to check that the argument has a type of function.
        return typeof argument === 'function';
    }

// _ESAbstract.ToObject
// 7.1.13 ToObject ( argument )
// The abstract operation ToObject converts argument to a value of type Object according to Table 12:
// Table 12: ToObject Conversions
    /*
    |----------------------------------------------------------------------------------------------------------------------------------------------------|
    | Argument Type | Result                                                                                                                             |
    |----------------------------------------------------------------------------------------------------------------------------------------------------|
    | Undefined     | Throw a TypeError exception.                                                                                                       |
    | Null          | Throw a TypeError exception.                                                                                                       |
    | Boolean       | Return a new Boolean object whose [[BooleanData]] internal slot is set to argument. See 19.3 for a description of Boolean objects. |
    | Number        | Return a new Number object whose [[NumberData]] internal slot is set to argument. See 20.1 for a description of Number objects.    |
    | String        | Return a new String object whose [[StringData]] internal slot is set to argument. See 21.1 for a description of String objects.    |
    | Symbol        | Return a new Symbol object whose [[SymbolData]] internal slot is set to argument. See 19.4 for a description of Symbol objects.    |
    | Object        | Return argument.                                                                                                                   |
    |----------------------------------------------------------------------------------------------------------------------------------------------------|
    */
    function ToObject(argument) { // eslint-disable-line no-unused-vars
        if (argument === null || argument === undefined) {
            throw TypeError();
        }
        return Object(argument);
    }
    if (!("defineProperty"in Object&&function(){try{var e={}
            return Object.defineProperty(e,"test",{value:42}),!0}catch(t){return!1}}()
    )) {

// Object.defineProperty
        (function (nativeDefineProperty) {

            var supportsAccessors = Object.prototype.hasOwnProperty('__defineGetter__');
            var ERR_ACCESSORS_NOT_SUPPORTED = 'Getters & setters cannot be defined on this javascript engine';
            var ERR_VALUE_ACCESSORS = 'A property cannot both have accessors and be writable or have a value';

            // Polyfill.io - This does not use CreateMethodProperty because our CreateMethodProperty function uses Object.defineProperty.
            Object['defineProperty'] = function defineProperty(object, property, descriptor) {

                // Where native support exists, assume it
                if (nativeDefineProperty && (object === window || object === document || object === Element.prototype || object instanceof Element)) {
                    return nativeDefineProperty(object, property, descriptor);
                }

                if (object === null || !(object instanceof Object || typeof object === 'object')) {
                    throw new TypeError('Object.defineProperty called on non-object');
                }

                if (!(descriptor instanceof Object)) {
                    throw new TypeError('Property description must be an object');
                }

                var propertyString = String(property);
                var hasValueOrWritable = 'value' in descriptor || 'writable' in descriptor;
                var getterType = 'get' in descriptor && typeof descriptor.get;
                var setterType = 'set' in descriptor && typeof descriptor.set;

                // handle descriptor.get
                if (getterType) {
                    if (getterType !== 'function') {
                        throw new TypeError('Getter must be a function');
                    }
                    if (!supportsAccessors) {
                        throw new TypeError(ERR_ACCESSORS_NOT_SUPPORTED);
                    }
                    if (hasValueOrWritable) {
                        throw new TypeError(ERR_VALUE_ACCESSORS);
                    }
                    Object.__defineGetter__.call(object, propertyString, descriptor.get);
                } else {
                    object[propertyString] = descriptor.value;
                }

                // handle descriptor.set
                if (setterType) {
                    if (setterType !== 'function') {
                        throw new TypeError('Setter must be a function');
                    }
                    if (!supportsAccessors) {
                        throw new TypeError(ERR_ACCESSORS_NOT_SUPPORTED);
                    }
                    if (hasValueOrWritable) {
                        throw new TypeError(ERR_VALUE_ACCESSORS);
                    }
                    Object.__defineSetter__.call(object, propertyString, descriptor.set);
                }

                // OK to define value unconditionally - if a getter has been specified as well, an error would be thrown above
                if ('value' in descriptor) {
                    object[propertyString] = descriptor.value;
                }

                return object;
            };
        }(Object.defineProperty));

    }


// _ESAbstract.CreateMethodProperty
// 7.3.5. CreateMethodProperty ( O, P, V )
    function CreateMethodProperty(O, P, V) { // eslint-disable-line no-unused-vars
        // 1. Assert: Type(O) is Object.
        // 2. Assert: IsPropertyKey(P) is true.
        // 3. Let newDesc be the PropertyDescriptor{[[Value]]: V, [[Writable]]: true, [[Enumerable]]: false, [[Configurable]]: true}.
        var newDesc = {
            value: V,
            writable: true,
            enumerable: false,
            configurable: true
        };
        // 4. Return ? O.[[DefineOwnProperty]](P, newDesc).
        Object.defineProperty(O, P, newDesc);
    }
    if (!("bind"in Function.prototype
    )) {

// Function.prototype.bind
        /* global CreateMethodProperty, IsCallable */
// 19.2.3.2. Function.prototype.bind ( thisArg, ...args )
// https://github.com/es-shims/es5-shim/blob/d6d7ff1b131c7ba14c798cafc598bb6780d37d3b/es5-shim.js#L182
        CreateMethodProperty(Function.prototype, 'bind', function bind(that) { // .length is 1
            // add necessary es5-shim utilities
            var $Array = Array;
            var $Object = Object;
            var ArrayPrototype = $Array.prototype;
            var Empty = function Empty() { };
            var array_slice = ArrayPrototype.slice;
            var array_concat = ArrayPrototype.concat;
            var array_push = ArrayPrototype.push;
            var max = Math.max;
            // /add necessary es5-shim utilities

            // 1. Let Target be the this value.
            var target = this;
            // 2. If IsCallable(Target) is false, throw a TypeError exception.
            if (!IsCallable(target)) {
                throw new TypeError('Function.prototype.bind called on incompatible ' + target);
            }
            // 3. Let A be a new (possibly empty) internal list of all of the
            //   argument values provided after thisArg (arg1, arg2 etc), in order.
            // XXX slicedArgs will stand in for "A" if used
            var args = array_slice.call(arguments, 1); // for normal call
            // 4. Let F be a new native ECMAScript object.
            // 11. Set the [[Prototype]] internal property of F to the standard
            //   built-in Function prototype object as specified in 15.3.3.1.
            // 12. Set the [[Call]] internal property of F as described in
            //   15.3.4.5.1.
            // 13. Set the [[Construct]] internal property of F as described in
            //   15.3.4.5.2.
            // 14. Set the [[HasInstance]] internal property of F as described in
            //   15.3.4.5.3.
            var bound;
            var binder = function () {

                if (this instanceof bound) {
                    // 15.3.4.5.2 [[Construct]]
                    // When the [[Construct]] internal method of a function object,
                    // F that was created using the bind function is called with a
                    // list of arguments ExtraArgs, the following steps are taken:
                    // 1. Let target be the value of F's [[TargetFunction]]
                    //   internal property.
                    // 2. If target has no [[Construct]] internal method, a
                    //   TypeError exception is thrown.
                    // 3. Let boundArgs be the value of F's [[BoundArgs]] internal
                    //   property.
                    // 4. Let args be a new list containing the same values as the
                    //   list boundArgs in the same order followed by the same
                    //   values as the list ExtraArgs in the same order.
                    // 5. Return the result of calling the [[Construct]] internal
                    //   method of target providing args as the arguments.

                    var result = target.apply(
                        this,
                        array_concat.call(args, array_slice.call(arguments))
                    );
                    if ($Object(result) === result) {
                        return result;
                    }
                    return this;

                } else {
                    // 15.3.4.5.1 [[Call]]
                    // When the [[Call]] internal method of a function object, F,
                    // which was created using the bind function is called with a
                    // this value and a list of arguments ExtraArgs, the following
                    // steps are taken:
                    // 1. Let boundArgs be the value of F's [[BoundArgs]] internal
                    //   property.
                    // 2. Let boundThis be the value of F's [[BoundThis]] internal
                    //   property.
                    // 3. Let target be the value of F's [[TargetFunction]] internal
                    //   property.
                    // 4. Let args be a new list containing the same values as the
                    //   list boundArgs in the same order followed by the same
                    //   values as the list ExtraArgs in the same order.
                    // 5. Return the result of calling the [[Call]] internal method
                    //   of target providing boundThis as the this value and
                    //   providing args as the arguments.

                    // equiv: target.call(this, ...boundArgs, ...args)
                    return target.apply(
                        that,
                        array_concat.call(args, array_slice.call(arguments))
                    );

                }

            };

            // 15. If the [[Class]] internal property of Target is "Function", then
            //     a. Let L be the length property of Target minus the length of A.
            //     b. Set the length own property of F to either 0 or L, whichever is
            //       larger.
            // 16. Else set the length own property of F to 0.

            var boundLength = max(0, target.length - args.length);

            // 17. Set the attributes of the length own property of F to the values
            //   specified in 15.3.5.1.
            var boundArgs = [];
            for (var i = 0; i < boundLength; i++) {
                array_push.call(boundArgs, '$' + i);
            }

            // XXX Build a dynamic function with desired amount of arguments is the only
            // way to set the length property of a function.
            // In environments where Content Security Policies enabled (Chrome extensions,
            // for ex.) all use of eval or Function costructor throws an exception.
            // However in all of these environments Function.prototype.bind exists
            // and so this code will never be executed.
            bound = Function('binder', 'return function (' + boundArgs.join(',') + '){ return binder.apply(this, arguments); }')(binder);

            if (target.prototype) {
                Empty.prototype = target.prototype;
                bound.prototype = new Empty();
                // Clean up dangling references.
                Empty.prototype = null;
            }

            // TODO
            // 18. Set the [[Extensible]] internal property of F to true.

            // TODO
            // 19. Let thrower be the [[ThrowTypeError]] function Object (13.2.3).
            // 20. Call the [[DefineOwnProperty]] internal method of F with
            //   arguments "caller", PropertyDescriptor {[[Get]]: thrower, [[Set]]:
            //   thrower, [[Enumerable]]: false, [[Configurable]]: false}, and
            //   false.
            // 21. Call the [[DefineOwnProperty]] internal method of F with
            //   arguments "arguments", PropertyDescriptor {[[Get]]: thrower,
            //   [[Set]]: thrower, [[Enumerable]]: false, [[Configurable]]: false},
            //   and false.

            // TODO
            // NOTE Function objects created using Function.prototype.bind do not
            // have a prototype property or the [[Code]], [[FormalParameters]], and
            // [[Scope]] internal properties.
            // XXX can't delete prototype in pure-js.

            // 22. Return F.
            return bound;
        });

    }

    if (!("getOwnPropertyDescriptor"in Object&&"function"==typeof Object.getOwnPropertyDescriptor&&function(){try{var t={}
            return t.test=0,0===Object.getOwnPropertyDescriptor(t,"test").value}catch(e){return!1}}()
    )) {

// Object.getOwnPropertyDescriptor
        /* global CreateMethodProperty */
        (function () {
            var call = Function.prototype.call;
            var prototypeOfObject = Object.prototype;
            var owns = call.bind(prototypeOfObject.hasOwnProperty);

            var lookupGetter;
            var lookupSetter;
            var supportsAccessors;
            if ((supportsAccessors = owns(prototypeOfObject, "__defineGetter__"))) {
                lookupGetter = call.bind(prototypeOfObject.__lookupGetter__);
                lookupSetter = call.bind(prototypeOfObject.__lookupSetter__);
            }
            function doesGetOwnPropertyDescriptorWork(object) {
                try {
                    object.sentinel = 0;
                    return Object.getOwnPropertyDescriptor(
                        object,
                        "sentinel"
                    ).value === 0;
                } catch (exception) {
                    // returns falsy
                }
            }
            // check whether getOwnPropertyDescriptor works if it's given. Otherwise,
            // shim partially.
            if (Object.defineProperty) {
                var getOwnPropertyDescriptorWorksOnObject =
                    doesGetOwnPropertyDescriptorWork({});
                var getOwnPropertyDescriptorWorksOnDom = typeof document == "undefined" ||
                    doesGetOwnPropertyDescriptorWork(document.createElement("div"));
                if (!getOwnPropertyDescriptorWorksOnDom ||
                    !getOwnPropertyDescriptorWorksOnObject
                ) {
                    var getOwnPropertyDescriptorFallback = Object.getOwnPropertyDescriptor;
                }
            }

            if (!Object.getOwnPropertyDescriptor || getOwnPropertyDescriptorFallback) {
                var ERR_NON_OBJECT = "Object.getOwnPropertyDescriptor called on a non-object: ";

                CreateMethodProperty(Object, 'getOwnPropertyDescriptor', function getOwnPropertyDescriptor(object, property) {
                    if ((typeof object != "object" && typeof object != "function") || object === null) {
                        throw new TypeError(ERR_NON_OBJECT + object);
                    }

                    // make a valiant attempt to use the real getOwnPropertyDescriptor
                    // for I8's DOM elements.
                    if (getOwnPropertyDescriptorFallback) {
                        try {
                            return getOwnPropertyDescriptorFallback.call(Object, object, property);
                        } catch (exception) {
                            // try the shim if the real one doesn't work
                        }
                    }

                    // If object does not owns property return undefined immediately.
                    if (!owns(object, property)) {
                        return;
                    }

                    // If object has a property then it's for sure both `enumerable` and
                    // `configurable`.
                    var descriptor = { enumerable: true, configurable: true };

                    // If JS engine supports accessor properties then property may be a
                    // getter or setter.
                    if (supportsAccessors) {
                        // Unfortunately `__lookupGetter__` will return a getter even
                        // if object has own non getter property along with a same named
                        // inherited getter. To avoid misbehavior we temporary remove
                        // `__proto__` so that `__lookupGetter__` will return getter only
                        // if it's owned by an object.
                        var prototype = object.__proto__;
                        object.__proto__ = prototypeOfObject;

                        var getter = lookupGetter(object, property);
                        var setter = lookupSetter(object, property);

                        // Once we have getter and setter we can put values back.
                        object.__proto__ = prototype;

                        if (getter || setter) {
                            if (getter) {
                                descriptor.get = getter;
                            }
                            if (setter) {
                                descriptor.set = setter;
                            }
                            // If it was accessor property we're done and return here
                            // in order to avoid adding `value` to the descriptor.
                            return descriptor;
                        }
                    }

                    // If we got this far we know that object has an own property that is
                    // not an accessor so we set it as a value and return descriptor.
                    descriptor.value = object[property];
                    descriptor.writable = true;
                    return descriptor;
                });
            }
        }());

    }

    if (!("keys"in Object&&function(){return 2===Object.keys(arguments).length}(1,2)&&function(){try{return Object.keys(""),!0}catch(t){return!1}}()
    )) {

// Object.keys
        /* global CreateMethodProperty */
        CreateMethodProperty(Object, "keys", (function() {
            'use strict';

            // modified from https://github.com/es-shims/object-keys

            var has = Object.prototype.hasOwnProperty;
            var toStr = Object.prototype.toString;
            var isEnumerable = Object.prototype.propertyIsEnumerable;
            var hasDontEnumBug = !isEnumerable.call({ toString: null }, 'toString');
            var hasProtoEnumBug = isEnumerable.call(function () {}, 'prototype');
            var dontEnums = [
                'toString',
                'toLocaleString',
                'valueOf',
                'hasOwnProperty',
                'isPrototypeOf',
                'propertyIsEnumerable',
                'constructor'
            ];
            var equalsConstructorPrototype = function (o) {
                var ctor = o.constructor;
                return ctor && ctor.prototype === o;
            };
            var excludedKeys = {
                $console: true,
                $external: true,
                $frame: true,
                $frameElement: true,
                $frames: true,
                $innerHeight: true,
                $innerWidth: true,
                $outerHeight: true,
                $outerWidth: true,
                $pageXOffset: true,
                $pageYOffset: true,
                $parent: true,
                $scrollLeft: true,
                $scrollTop: true,
                $scrollX: true,
                $scrollY: true,
                $self: true,
                $webkitIndexedDB: true,
                $webkitStorageInfo: true,
                $window: true
            };
            var hasAutomationEqualityBug = (function () {
                /* global window */
                if (typeof window === 'undefined') { return false; }
                for (var k in window) {
                    try {
                        if (!excludedKeys['$' + k] && has.call(window, k) && window[k] !== null && typeof window[k] === 'object') {
                            try {
                                equalsConstructorPrototype(window[k]);
                            } catch (e) {
                                return true;
                            }
                        }
                    } catch (e) {
                        return true;
                    }
                }
                return false;
            }());
            var equalsConstructorPrototypeIfNotBuggy = function (o) {
                /* global window */
                if (typeof window === 'undefined' || !hasAutomationEqualityBug) {
                    return equalsConstructorPrototype(o);
                }
                try {
                    return equalsConstructorPrototype(o);
                } catch (e) {
                    return false;
                }
            };

            function isArgumentsObject(value) {
                var str = toStr.call(value);
                var isArgs = str === '[object Arguments]';
                if (!isArgs) {
                    isArgs = str !== '[object Array]' &&
                        value !== null &&
                        typeof value === 'object' &&
                        typeof value.length === 'number' &&
                        value.length >= 0 &&
                        toStr.call(value.callee) === '[object Function]';
                }
                return isArgs;
            }

            return function keys(object) {
                var isFunction = toStr.call(object) === '[object Function]';
                var isArguments = isArgumentsObject(object);
                var isString = toStr.call(object) === '[object String]';
                var theKeys = [];

                if (object === undefined || object === null) {
                    throw new TypeError('Cannot convert undefined or null to object');
                }

                var skipProto = hasProtoEnumBug && isFunction;
                if (isString && object.length > 0 && !has.call(object, 0)) {
                    for (var i = 0; i < object.length; ++i) {
                        theKeys.push(String(i));
                    }
                }

                if (isArguments && object.length > 0) {
                    for (var j = 0; j < object.length; ++j) {
                        theKeys.push(String(j));
                    }
                } else {
                    for (var name in object) {
                        if (!(skipProto && name === 'prototype') && has.call(object, name)) {
                            theKeys.push(String(name));
                        }
                    }
                }

                if (hasDontEnumBug) {
                    var skipConstructor = equalsConstructorPrototypeIfNotBuggy(object);

                    for (var k = 0; k < dontEnums.length; ++k) {
                        if (!(skipConstructor && dontEnums[k] === 'constructor') && has.call(object, dontEnums[k])) {
                            theKeys.push(dontEnums[k]);
                        }
                    }
                }
                return theKeys;
            };
        }()));

    }

    if (!("assign"in Object
    )) {

// Object.assign
        /* global CreateMethodProperty, Get, ToObject */
// 19.1.2.1 Object.assign ( target, ...sources )
        CreateMethodProperty(Object, 'assign', function assign(target, source) { // eslint-disable-line no-unused-vars
            // 1. Let to be ? ToObject(target).
            var to = ToObject(target);

            // 2. If only one argument was passed, return to.
            if (arguments.length === 1) {
                return to;
            }

            // 3. Let sources be the List of argument values starting with the second argument
            var sources = Array.prototype.slice.call(arguments, 1);

            // 4. For each element nextSource of sources, in ascending index order, do
            var index1;
            var index2;
            var keys;
            var from;
            for (index1 = 0; index1 < sources.length; index1++) {
                var nextSource = sources[index1];
                // a. If nextSource is undefined or null, let keys be a new empty List.
                if (nextSource === undefined || nextSource === null) {
                    keys = [];
                    // b. Else,
                } else {
                    // i. Let from be ! ToObject(nextSource).
                    from = ToObject(nextSource);
                    // ii. Let keys be ? from.[[OwnPropertyKeys]]().
                    /*
                        This step in our polyfill is not complying with the specification.
                        [[OwnPropertyKeys]] is meant to return ALL keys, including non-enumerable and symbols.
                        TODO: When we have Reflect.ownKeys, use that instead as it is the userland equivalent of [[OwnPropertyKeys]].
                    */
                    keys = Object.keys(from);
                }

                // c. For each element nextKey of keys in List order, do
                for (index2 = 0; index2 < keys.length; index2++) {
                    var nextKey = keys[index2];
                    var enumerable;
                    try {
                        // i. Let desc be ? from.[[GetOwnProperty]](nextKey).
                        var desc = Object.getOwnPropertyDescriptor(from, nextKey);
                        // ii. If desc is not undefined and desc.[[Enumerable]] is true, then
                        enumerable = desc !== undefined && desc.enumerable === true;
                    } catch (e) {
                        // Polyfill.io - We use Object.prototype.propertyIsEnumerable as a fallback
                        // because `Object.getOwnPropertyDescriptor(window.location, 'hash')` causes Internet Explorer 11 to crash.
                        enumerable = Object.prototype.propertyIsEnumerable.call(from, nextKey);
                    }
                    if (enumerable) {
                        // 1. Let propValue be ? Get(from, nextKey).
                        var propValue = Get(from, nextKey);
                        // 2. Perform ? Set(to, nextKey, propValue, true).
                        to[nextKey] = propValue;
                    }
                }
            }
            // 5. Return to.
            return to;
        });

    }

})
    .call('object' === typeof window && window || 'object' === typeof self && self || 'object' === typeof global && global || {});
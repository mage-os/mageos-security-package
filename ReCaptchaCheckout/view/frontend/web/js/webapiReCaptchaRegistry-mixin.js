/**
 * Copyright 2022 Adobe
 * All Rights Reserved.
 */

define([], function () {
    'use strict';

    return function (originalFunction) {
        /**
         * {@inheritDoc}
         */
       originalFunction.addListener = function (id , func) {
            this._listeners[id] = func;
       };

        return originalFunction;
    };

});

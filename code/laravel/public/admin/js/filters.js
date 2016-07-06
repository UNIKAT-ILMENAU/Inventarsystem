'use strict';

/* Filters */

//Still work in progress
angular.module('invFilter', []).filter('checkmark', function() {
  return function(input) {
    return input ? '\u2713' : '\u2718';
  };
});
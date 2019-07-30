'use strict';

/* Filters */
// need load the moment.js to use this filter.
angular.module('app')
    .filter('fromNow', function() {
        return function(date) {
            return moment(date).fromNow();
        }
    })
    .filter('toDate', function() {
        return function(date, fmt) {
            var mm = moment(date);
            if (fmt) {
                return mm.format(fmt);
            } else {
                return mm.format();
            }
        }
    })
    .filter('fromUnix', function() {
        return function(date, fmt) {
            var mm = moment.unix(date);
            if (fmt) {
                return mm.format(fmt);
            } else {
                return mm.format();
            }
        }
    })
    .filter('groupTypeName', function() {
        return function(type) {
            return type == 0 ? "固定组":"条件组"
        }
    });

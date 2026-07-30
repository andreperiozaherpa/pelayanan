<?php

/**
 * File ini digunakan khusus untuk membantu Intelephense dalam mengenali
 * method-method Eloquent yang dimuat secara dinamis.
 */

namespace Illuminate\Database\Eloquent {
    class Model
    {
        /**
         * Register a created model event with the dispatcher.
         *
         * @param  \Closure|string  $callback
         */
        public static function created($callback) {}

        /**
         * Register an updated model event with the dispatcher.
         *
         * @param  \Closure|string  $callback
         */
        public static function updated($callback) {}

        /**
         * Register a deleted model event with the dispatcher.
         *
         * @param  \Closure|string  $callback
         */
        public static function deleted($callback) {}
    }
}

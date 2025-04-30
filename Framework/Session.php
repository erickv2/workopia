<?php

namespace Framework;

class Session {
    /**
     * start the session
     * 
     * @return void
     */

     public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
     }

     /**
      * set a session key/value pair
      * @param string $key
      * @param mixed $value
      * @return void
      */

     public static function set($key, $value) {
        $_SESSION[$key] = $value;
     }

    /**
      * get a session value by the key
      * @param string $key
      * @param mixed $default
      * @return void
      */

     public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
     }

    /**
      * check if session key exists
      * @param string $key
      * @return bool
      */

      public static function has($key) {
        return isset($_SESSION[$key]);
      }

    /**
      * clear session by key
      * @param string $key
      * @return void
      */

      public static function clear($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
      }

    /**
      * clear all session data
      * @return void
      */

      public static function clearAll() {
        session_unset();
        session_destroy();
      }
}
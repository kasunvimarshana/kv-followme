<?php

namespace App\Helpers;

class NotifyHelper
{
    protected static $sessionKey = 'notification';

    /**
     * Flash a notification to the session
     */
    public static function flash($title, $type = 'success', $options = [])
    {
        session()->flash(self::$sessionKey, [
            'title' => $title,
            'type' => $type,
            'options' => $options
        ]);
    }

    /**
     * Check if a notification is ready to be displayed
     */
    public static function ready()
    {
        return session()->has(self::$sessionKey);
    }

    /**
     * Get the notification message/title
     */
    public static function message()
    {
        return session(self::$sessionKey . '.title', '');
    }

    /**
     * Get the notification type
     */
    public static function type()
    {
        return session(self::$sessionKey . '.type', 'info');
    }

    /**
     * Get a notification option
     */
    public static function option($key, $default = null)
    {
        return session(self::$sessionKey . '.options.' . $key, $default);
    }

    /**
     * Shortcut for success notification
     */
    public static function success($title, $options = [])
    {
        self::flash($title, 'success', $options);
    }

    /**
     * Shortcut for error notification
     */
    public static function error($title, $options = [])
    {
        self::flash($title, 'error', $options);
    }

    /**
     * Shortcut for warning notification
     */
    public static function warning($title, $options = [])
    {
        self::flash($title, 'warning', $options);
    }

    /**
     * Shortcut for info notification
     */
    public static function info($title, $options = [])
    {
        self::flash($title, 'info', $options);
    }
}

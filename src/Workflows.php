<?php

namespace WillFarrell\AlfredPkgMan;

use Alfred\Workflows\Workflow;
use RuntimeException;
use samdark\hydrator\Hydrator;

/**
 * Name:         Workflows
 * Description:  This PHP class object provides several useful functions for retrieving, parsing,
 *               and formatting data to be used with Alfred 3+ Workflows.
 * ChangeLog:
 *   - 4/26/2021 by Vardan Pogosian (@varp) - Under the hood the original class was refactored to be compatible
 *                                            with Alfred 3+ for which the core functions are returning back data to the
 *                                            Alfred now use `joetannenbaum/alfred-workflow`
 * Author:       David Ferguson (@jdfwarrior)
 * Revised:      4/26/2021
 * Version:      0.4.0
 */
class Workflows
{

    /**
     * @var Workflow
     */
    private $workflow;
    private $cache;
    private $data;
    private $bundle;
    private $path;
    private $home;
    private $results;

    /**
     * Description:
     * Class constructor function. Initializes all class variables. Accepts one optional parameter
     * of the workflow bundle id in the case that you want to specify a different bundle id. This
     * would adjust the output directories for storing data.
     */
    public function __construct()
    {
        $this->bundle = getenv('alfred_workflow_bundleid');
        $this->path = getcwd();
        $this->home = getenv('HOME');
        $this->cache = getenv('alfred_workflow_cache');
        $this->data = getenv('alfred_workflow_data');
        $this->workflow = new Workflow();
        $this->results = [];

        $this->createDirectory($this->cache);
        $this->createDirectory($this->data);
    }

    private function createDirectory($dir)
    {
        if (!file_exists($dir)) {
            exec("mkdir '" . $dir . "'");
        }
    }

    /**
     * Description:
     * Accepts no parameter and returns the value of the bundle id for the current workflow.
     * If no value is available, then false is returned.
     *
     * @return false if not available, bundle id value if available.
     */
    public function bundle()
    {
        return $this->bundle ?? false;
    }

    /**
     * Description:
     * Accepts no parameter and returns the value of the path to the cache directory for your
     * workflow if it is available. Returns false if the value isn't available.
     *
     * @return false if not available, path to the cache directory for your workflow if available.
     */
    public function cache()
    {
        return $this->cache ?? false;
    }

    /**
     * Description:
     * Accepts no parameter and returns the value of the path to the storage directory for your
     * workflow if it is available. Returns false if the value isn't available.
     *
     * @return false if not available, path to the storage directory for your workflow if available.
     */
    public function data()
    {
        return $this->data ?? false;
    }

    /**
     * Description:
     * Accepts no parameter and returns the value of the path to the current directory for your
     * workflow if it is available. Returns false if the value isn't available.
     *
     * @return false if not available, path to the current directory for your workflow if available.
     */
    public function path()
    {
        return $this->path ?? false;
    }

    /**
     * Description:
     * Accepts no parameter and returns the value of the home path for the current user
     * Returns false if the value isn't available.
     *
     * @return false if not available, home path for the current user if available.
     */
    public function home()
    {
        return $this->home ?? false;
    }

    /**
     * Description:
     * Returns an array of available result items
     *
     * @return array - list of result items
     */
    public function results()
    {
        return $this->results;
    }

    /**
     * Description:
     * Convert an associative array into JSON format
     *
     * @param $data - An associative array to convert
     * @return false - JSON string representation of the array
     */
    public function toJson($data = null)
    {
        $data = $data ?: $this->results;

        if (is_string($data)) {
            $data = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON');
            }
        }

        if (empty($data)) {
            return $this->workflow->output();
        }

        foreach ($data as $item) {
            $c = $this->workflow->result();
            (new Hydrator(array_combine(array_keys($item), array_keys($item))))->hydrateInto($item, $c);
        }

        return $this->workflow->output();
    }

    /**
     * Description:
     * Remove all items from an associative array that do not have a value
     *
     * @param $a - Associative array
     * @return bool
     */
    private function empty_filter($a)
    {
        return !($a === '' || $a === null);
    }

    /**
     * Description:
     * Save values to a specified plist. If the first parameter is an associative
     * array, then the second parameter becomes the plist file to save to. If the
     * first parameter is string, then it is assumed that the first parameter is
     * the label, the second parameter is the value, and the third parameter is
     * the plist file to save the data to.
     *
     * @param $a - associative array of values to save
     * @param $b - the value of the setting
     * @param $c - the plist to save the values into
     * @return string - execution output
     */
    public function set($a = null, $b = null, $c = null)
    {
        $file = $this->resolveFilePath($a, $b, $c);
        if (is_array($a)) {
            foreach ($a as $k => $v) {
                exec('defaults write "' . $file . '" ' . $k . ' "' . $v . '"');
            }
        } else {
            exec('defaults write "' . $file . '" ' . $a . ' "' . $b . '"');
        }
    }

    /**
     * Description:
     * Read a value from the specified plist
     *
     * @param $a - the value to read
     * @param $b - plist to read the values from
     * @return bool false if not found, string if found
     */
    public function get($a, $b)
    {
        $file = $this->resolveFilePath(null, $b);
        exec('defaults read "' . $file . '" ' . $a, $out);
        return $out[0] ?? false;
    }

    private function resolveFilePath($a, $b, $c = null)
    {
        $file = $b ?? $c;
        if (file_exists($file)) {
            return $file;
        }
        if (file_exists($this->path . '/' . $file)) {
            return $this->path . '/' . $file;
        }
        if (file_exists($this->data . "/" . $file)) {
            return $this->data . "/" . $file;
        }
        if (file_exists($this->cache . "/" . $file)) {
            return $this->cache . "/" . $file;
        }
        return $this->data . "/" . $file;
    }

    /**
     * Description:
     * Read data from a remote file/url, essentially a shortcut for curl
     *
     * @param $url - URL to request
     * @param $options - Array of curl options
     * @return string|bool from curl_exec
     */
    public function request($url = null, $options = null)
    {
        if (is_null($url)) {
            return false;
        }

        $defaults = [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_USERAGENT => 'AlfredPkgMan-Workflow',
        ];

        if ($options) {
            $defaults = array_replace($defaults, $options);
        }

        $defaults = array_filter($defaults, [$this, 'empty_filter']);
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $out = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        return $err ?: $out;
    }

    /**
     * Description:
     * Allows searching the local hard drive using mdfind
     *
     * @param $query - search string
     * @return array - array of search results
     */
    public function mdfind($query)
    {
        exec('mdfind "' . $query . '"', $results);
        return $results;
    }

    /**
     * Delete a cache file
     *
     * @param string $a Path to the file to delete
     * @return void
     * @author @willfarrell
     */
    public function delete($a)
    {
        $file = $this->resolveFilePath(null, $a);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Description:
     * Accepts data and a string file name to store data to local file as cache
     *
     * @param array - data to save to file
     * @param file - filename to write the cache data to
     * @return bool
     */
    public function write($a, $b)
    {
        $file = $this->resolveFilePath(null, $b);
        $data = is_array($a) ? json_encode($a) : $a;
        file_put_contents($file, $data);
        return true;
    }

    /**
     * Description:
     * Returns data from a local cache file
     *
     * @param file - filename to read the cache data from
     * @return false if the file cannot be found, the file data if found. If the file
     *           format is json encoded, then a json object is returned.
     */
    public function read($a, $array = false)
    {
        $file = $this->resolveFilePath(null, $a);
        if (!file_exists($file)) {
            return false;
        }

        $out = file_get_contents($file);
        $decoded = json_decode($out, $array);
        return $decoded ?? $out;
    }

    /**
     * Check the file modification time
     *
     * @param string $a Path to a file
     * @return integer    Returns the file modification time, or false
     * @author @willfarrell
     */
    public function filetime($a)
    {
        $file = $this->resolveFilePath(null, $a);
        return file_exists($file) ? filemtime($file) : false;
    }

    /**
     * Description:
     * Helper function that just makes it easier to pass values into a function
     * and create an array result to be passed back to Alfred
     *
     * @param $uid - the uid of the result, should be unique
     * @param $arg - the argument that will be passed on
     * @param $title - The title of the result item
     * @param $subtitle - The subtitle text for the result item
     * @param $icon - the icon to use for the result item
     * @param string $valid - sets whether the result item can be actioned
     * @param null $autocomplete - the autocomplete value for the result item
     * @param null $type
     * @return array - array item to be passed back to Alfred
     */
    public function result(
        $uid,
        $arg,
        $title,
        $subtitle,
        $icon,
        $valid = 'yes',
        $autocomplete = null,
        $type = null
    ): array {
        $temp = compact('uid', 'arg', 'title', 'subtitle', 'valid', 'autocomplete', 'type');
        $temp['icon'] = [
            'path' => $icon
        ];

        if (is_null($type)) {
            unset($temp['type']);
        }

        $this->results[] = $temp;
        return $temp;
    }
}

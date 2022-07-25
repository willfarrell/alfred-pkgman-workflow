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

        if (!file_exists($this->cache)) {
            exec("mkdir '" . $this->cache . "'");
        }

        if (!file_exists($this->data)) {
            exec("mkdir '" . $this->data . "'");
        }

        $this->results = [];
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
        if (is_null($this->bundle)) {
            return false;
        };
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
        if (is_null($this->bundle)) {
            return false;
        }

        return is_null($this->cache) ? false : $this->cache;
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
        if (is_null($this->bundle)) {
            return false;
        }
        return is_null($this->data) ? false : $this->data;
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
        if (is_null($this->path)) {
            return false;
        }

        return $this->path;
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
        if (is_null($this->home)) {
            return false;
        }

        return $this->home;
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
        $data = !empty($data) ? $data : $this->results;

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

            $keys = array_keys($item);
            (new Hydrator(array_combine($keys, $keys)))->hydrateInto($item, $c);
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
        if (is_array($a)) {
            if (file_exists($b)) {
                if (file_exists($this->path . '/' . $b)) {
                    $b = $this->path . '/' . $b;
                }
            } elseif (file_exists($this->data . "/" . $b)) {
                $b = $this->data . "/" . $b;
            } elseif (file_exists($this->cache . "/" . $b)) {
                $b = $this->cache . "/" . $b;
            } else {
                $b = $this->data . "/" . $b;
            }
        } else {
            if (file_exists($c)) {
                if (file_exists($this->path . '/' . $c)) {
                    $c = $this->path . '/' . $c;
                }
            } elseif (file_exists($this->data . "/" . $c)) {
                $c = $this->data . "/" . $c;
            } elseif (file_exists($this->cache . "/" . $c)) {
                $c = $this->cache . "/" . $c;
            } else {
                $c = $this->data . "/" . $c;
            }
        }

        if (is_array($a)) {
            foreach ($a as $k => $v) {
                exec('defaults write "' . $b . '" ' . $k . ' "' . $v . '"');
            }
        } else {
            exec('defaults write "' . $c . '" ' . $a . ' "' . $b . '"');
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
        if (file_exists($b)) {
            if (file_exists($this->path . '/' . $b)) {
                $b = $this->path . '/' . $b;
            }
        } elseif (file_exists($this->data . "/" . $b)) {
            $b = $this->data . "/" . $b;
        } elseif (file_exists($this->cache . "/" . $b)) {
            $b = $this->cache . "/" . $b;
        } else {
            return false;
        }

        exec('defaults read "' . $b . '" ' . $a, $out);   // Execute system call to read plist value

        if ($out === "") {
            return false;
        }

        $out = $out[0];

        return $out;                                            // Return item value
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

        $defaults = [                                  // Create a list of default curl options
            CURLOPT_RETURNTRANSFER => true,                 // Returns the result as a string
            CURLOPT_URL => $url,                            // Sets the url to request
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_USERAGENT => 'AlfredPkgMan-Workflow',
        ];

        if ($options) {
            foreach ($options as $k => $v) {
                $defaults[$k] = $v;
            }
        }

        array_filter(
            $defaults,                            // Filter out empty options from the array
            [$this, 'empty_filter']
        );

        $ch = curl_init();                                 // Init new curl object
        curl_setopt_array($ch, $defaults);                // Set curl options
        $out = curl_exec($ch);                            // Request remote data
        $err = curl_error($ch);
        curl_close($ch);                                  // End curl request

        if ($err) {
            return $err;
        }

        return $out;
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
        if (file_exists($a)) {
            if (file_exists($this->path . '/' . $a)) {
                unlink($this->path . '/' . $a);
            }
        } elseif (file_exists($this->data . "/" . $a)) {
            unlink($this->data . "/" . $a);
        } elseif (file_exists($this->cache . "/" . $a)) {
            unlink($this->cache . "/" . $a);
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
        if (file_exists($b)) {
            if (file_exists($this->path . '/' . $b)) {
                $b = $this->path . '/' . $b;
            }
        } elseif (file_exists($this->data . "/" . $b)) {
            $b = $this->data . "/" . $b;
        } elseif (file_exists($this->cache . "/" . $b)) {
            $b = $this->cache . "/" . $b;
        } else {
            $b = $this->data . "/" . $b;
        }

        if (is_array($a)) {
            $a = json_encode($a);
            file_put_contents($b, $a);

            return true;
        }

        if (is_string($a)) {
            file_put_contents($b, $a);

            return true;
        }

        return false;
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
        if (file_exists($a)) {
            if (file_exists($this->path . '/' . $a)) {
                $a = $this->path . '/' . $a;
            }
        } elseif (file_exists($this->data . "/" . $a)) {
            $a = $this->data . "/" . $a;
        } elseif (file_exists($this->cache . "/" . $a)) {
            $a = $this->cache . "/" . $a;
        } else {
            return false;
        }

        $out = file_get_contents($a);
        if (!is_null(json_decode($out)) && !$array) {
            $out = json_decode($out);
        } elseif (!is_null(json_decode($out)) && $array) {
            $out = json_decode($out, true);
        }

        return $out;
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
        if (file_exists($a)) {
            if (file_exists($this->path . '/' . $a)) {
                return filemtime($this->path . '/' . $a);
            }
        } elseif (file_exists($this->data . "/" . $a)) {
            return filemtime($this->data . '/' . $a);
        } elseif (file_exists($this->cache . "/" . $a)) {
            return filemtime($this->cache . '/' . $a);
        }

        return false;
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

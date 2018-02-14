<?php

namespace Lexide\JsonSchema;

/**
 * SchemaStore.php
 *
 * @author: Geraint Luff                              
 * 
 * adapted into PSR/2 and made anti-static by:
 * @author: Danny Smart <downsider84@hotmail.com>
 */

class SchemaStore
{

    private $schemas = array();
    private $refs = array();

    private function pointerGet(&$value, $path = "", $strict=false)
    {
        if ($path == "") {
            return $value;
        } else if ($path[0] != "/") {
            throw new \Exception("Invalid path: $path");
        }
        $parts = explode("/", $path);
        array_shift($parts);
        foreach ($parts as $part) {
            $part = str_replace("~1", "/", $part);
            $part = str_replace("~0", "~", $part);
            if (is_array($value) && is_numeric($part)) {
                $value =& $value[$part];
            } elseif (is_object($value)) {
                if (isset($value->$part)) {
                    $value =& $value->$part;
                } elseif ($strict) {
                    throw new \Exception("Path does not exist: $path");
                } else {
                    return null;
                }
            } else if ($strict) {
                throw new \Exception("Path does not exist: $path");
            } else {
                return null;
            }
        }
        return $value;
    }

    private function isNumericArray($array)
    {
        $count = count($array);
        for ($i = 0; $i < $count; $i++) {
            if (!isset($array[$i])) {
                return false;
            }
        }
        return true;
    }

    private function resolveUrl($base, $relative)
    {
        if (parse_url($relative, PHP_URL_SCHEME) != '') {
            // It's already absolute
            return $relative;
        }
        $baseParts = parse_url($base);
        if ($relative[0] == "?") {
            $baseParts['query'] = substr($relative, 1);
            unset($baseParts['fragment']);
        } elseif ($relative[0] == "#") {
            $baseParts['fragment'] = substr($relative, 1);
        } elseif ($relative[0] == "/") {
            if ($relative[1] == "/") {
                return $baseParts['scheme'].$relative;
            }
            $baseParts['path'] = $relative;
            unset($baseParts['query']);
            unset($baseParts['fragment']);
        } else {
            $basePathParts = explode("/", $baseParts['path']);
            $relativePathParts = explode("/", $relative);
            array_pop($basePathParts);
            while (count($relativePathParts)) {
                if ($relativePathParts[0] == "..") {
                    array_shift($relativePathParts);
                    if (count($basePathParts)) {
                        array_pop($basePathParts);
                    }
                } elseif ($relativePathParts[0] == ".") {
                    array_shift($relativePathParts);
                } else {
                    array_push($basePathParts, array_shift($relativePathParts));
                }
            }
            $baseParts['path'] = implode("/", $basePathParts);
            if ($baseParts['path'][0] != '/') {
                $baseParts['path'] = "/".$baseParts['path'];
            }
        }

        $result = "";
        if (!empty($baseParts['scheme'])) {
            $result .= $baseParts['scheme']."://";
            if (!empty($baseParts['user'])) {
                $result .= ":".$baseParts['user'];
                if ($baseParts['pass']) {
                    $result .= ":".$baseParts['pass'];
                }
                $result .= "@";
            }
            $result .= $baseParts['host'];
            if (!empty($baseParts['port'])) {
                $result .= ":".$baseParts['port'];
            }
        }
        $result .= $baseParts["path"];
        if (!empty($baseParts['query'])) {
            $result .= "?".$baseParts['query'];
        }
        if (!empty($baseParts['fragment'])) {
            $result .= "#".$baseParts['fragment'];
        }
        return $result;
    }

    public function missing()
    {
        return array_keys($this->refs);
    }

    public function add($url, $schema, $trusted=false)
    {
        $urlParts = explode("#", $url);
        $baseUrl = array_shift($urlParts);
        $fragment = urldecode(implode("#", $urlParts));

        $trustBase = explode("?", $baseUrl);
        $trustBase = $trustBase[0];

        $this->schemas[$url] =& $schema;
        $this->normaliseSchema($url, $schema, $trusted? true : $trustBase);
        if ($fragment == "") {
            $this->schemas[$baseUrl] = $schema;
        }
        if (isset($this->refs[$baseUrl])) {
            foreach ($this->refs[$baseUrl] as $fullUrl => &$refSchema) {
                $refSchema = $this->get($fullUrl);
                unset($this->refs[$baseUrl][$fullUrl]);
            }
            if (count($this->refs[$baseUrl]) == 0) {
                unset($this->refs[$baseUrl]);
            }
        }
        // debug
        //echo "<pre>" . print_r($this->schemas, true) . "</pre>\n";
    }

    private function normaliseSchema($url, &$schema, $trustPrefix)
    {
        if (is_array($schema) && !$this->isNumericArray($schema)) {
            $schema = (object)$schema;
        }
        if (is_object($schema)) {
            if (isset($schema->{'$ref'})) {
                $refUrl = $schema->{'$ref'} = $this->resolveUrl($url, $schema->{'$ref'});
                if ($refSchema = $this->get($refUrl)) {
                    $schema = $refSchema;
                    return;
                } else {
                    $urlParts = explode("#", $refUrl);
                    $baseUrl = array_shift($urlParts);
                    $fragment = urldecode(implode("#", $urlParts));
                    $this->refs[$baseUrl][$refUrl] =& $schema;
                }
            } else if (isset($schema->id)) {
                $schema->id = $url = $this->resolveUrl($url, $schema->id);
                if (
                    $trustPrefix === true ||
                    (
                        substr($schema->id, 0, strlen($trustPrefix)) == $trustPrefix &&
                        (
                            $schema->id == $trustPrefix ||
                            $trustPrefix[strlen($trustPrefix) - 1] == "/" ||
                            $schema->id[strlen($trustPrefix)] == "#" ||
                            $schema->id[strlen($trustPrefix)] == "?"
                        )
                    )
                ) {
                    if (!isset($this->schemas[$schema->id])) {
                        $this->add($schema->id, $schema);
                    }
                }
            }
            foreach ($schema as $key => &$value) {
                if ($key != "enum") {
                    $this->normaliseSchema($url, $value, $trustPrefix);
                }
            }
        } elseif (is_array($schema)) {
            // check if we have schemas in a numeric array
            foreach ($schema as &$subSchema) {
                $this->normaliseSchema($url, $subSchema, $trustPrefix);
            }
        }
    }

    public function get($url)
    {
        if (isset($this->schemas[$url])) {
            return $this->schemas[$url];
        }
        $urlParts = explode("#", $url);
        $baseUrl = array_shift($urlParts);
        $fragment = urldecode(implode("#", $urlParts));
        if (isset($this->schemas[$baseUrl])) {
            $schema = $this->schemas[$baseUrl];
            if ($schema && $fragment == "" || $fragment[0] == "/") {
                $schema = $this->pointerGet($schema, $fragment);
                $this->add($url, $schema);
                return $schema;
            }
        }
        throw new \Exception("$url cannot be located");
    }
}

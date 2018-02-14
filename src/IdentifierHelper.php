<?php

namespace Lexide\JsonSchema;

/**
 * IdentifierHelper.php
 *
 * @author: Danny Smart <lexide84@hotmail.com>
 */
class IdentifierHelper
{

    /**
     * @param string $string
     *
     * @return string
     */
    public function toCamelCase($string)
    {
        return lcfirst( // lowercase the first letter
            $this->toStudlyCaps($string)
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function toStudlyCaps($string)
    {
        return str_replace( // remove the spaces
            " ",
            "",
            ucwords( // uppercase the 1st letter of each word
                str_replace( // replace underscores with spaces
                    "_",
                    " ",
                    $string
                )
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function toUnderscores($string)
    {
        return $this->toSplitCase(str_replace(" ", "_", $string));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function toSpaces($string)
    {
        return $this->toSplitCase(str_replace("_", " ", $string), " ");
    }

    /**
     * TODO: work out how to insert the delimiter and remove repeats in one regex call
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return string
     */
    protected function toSplitCase($string, $delimiter = "_")
    {
        return strtolower( // convert uppercase chars
            trim( // remove any preceding delimiter
                preg_replace( // replace repeats
                    "/([$delimiter]+)/",
                    $delimiter,
                    preg_replace( // insert delimiter
                        "/([A-Z])/",
                        "$delimiter\$1",
                        $string
                    )
                ),
                $delimiter
            )
        );
    }

}

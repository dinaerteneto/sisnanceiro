<?php

namespace Sisnanceiro\Components;

class Cartesian
{
    public static function build($set)
    {
        if (!$set) {
            return array(array());
        }
        $subset          = array_shift($set);
        $cartesianSubset = self::build($set);
        $result          = array();
        foreach ($subset as $value) {
            foreach ($cartesianSubset as $p) {
                array_unshift($p, $value);
                $result[] = $p;
            }
        }
        return $result;
    }
}

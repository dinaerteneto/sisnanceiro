<?php
namespace Sisnanceiro\Helpers;

class FloatConversor
{

    /**
     * try transform string xx.xxx,00 to xxxxx.00
     * @param string str
     * @return float
     * @throws Exception if return is not float
     */
    public static function covert($str)
    {
        $newStr = str_replace('.', '', $str);
        $newStr = (float) str_replace(',', '.', $newStr);
        if (!is_float($newStr)) {
            throw new \Exception("Erro na tentativa de converter em número");
        }
        return $newStr;
    }

}

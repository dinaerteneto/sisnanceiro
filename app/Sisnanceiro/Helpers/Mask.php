<?php

namespace Sisnanceiro\Helpers;

abstract class Mask
{

 /**
  * @var array
  */
 private static $null_value;

 /**
  * Mask
  *
  * private para não poder instanciada nem herdada
  */
 private function __construct()
 {
 }

 /**
  * setNullValue
  *
  * Seta um caracter ou texto para o retorno nulo
  *
  * @param string $sMask
  * @param string $sValue
  * @return void
  */
 public static function setNullValue($sMask, $sValue)
 {
  self::$null_value[$sMask] = $sValue;
 }

 /**
  * getNullValue
  *
  * Obtém um caracter ou texto para o retorno nulo
  *
  * @param string $sMask
  * @return string
  */
 private static function getNullValue($sMask)
 {
  if (isset(self::$null_value[$sMask])) {
   return self::$null_value[$sMask];
  }
 }

 /**
  * date
  *
  * Máscara
  *
  * @param string $sValue yyyy-mm-dd hh:ii:ss
  * @param string $sType full_str=07/09/1976, full_int=7/9/1976, abrv_dm=7/set, , abrv_dmy=7/set/76
  * @return string
  */
 public static function date($sValue, $sType = 'full_str')
 {
  if ($sValue) {
   $sDay   = substr($sValue, 8, 2);
   $sMonth = substr($sValue, 5, 2);
   $sYear  = substr($sValue, 0, 4);

   if ('00' == $sDay || '00' == $sMonth || '0000' == $sYear) {
    $sDate = null;
   } else if ('abrv_dm' == $sType) {
    $sDate = (int) $sDay . '/' . Date::writeMonth($sMonth, 'abrv');
   } else if ('abrv_dmy' == $sType) {
    $sDate = (int) $sDay . '/' . Date::writeMonth($sMonth, 'abrv') . '/' . substr($sYear, 2, 2);
   } else if ('full_int' == $sType) {
    $sDate = (int) $sDay . '/' . (int) $sMonth . '/' . $sYear;
   } else {
    $sDate = $sDay . '/' . $sMonth . '/' . $sYear;
   }
  } else {
   $sDate = null;
  }

  if (null == $sDate) {
   $sDate = self::getNullValue('date');
   self::setNullValue('date', null);
  }
  return $sDate;
 }

 /**
  * time
  *
  * Máscara
  *
  * @param string $sValue yyyy-mm-dd hh:ii:ss
  * @param string $sType full=09:15:32, abrv=9h15
  * @return string
  */
 public static function time($sValue, $sType = 'full')
 {
  if ($sValue) {
   $sTime = substr($sValue, 11, 8);
   if ('abrv' == $sType) {
    $aTime = explode(':', $sTime);
    $sTime = $aTime[0] . 'h';
    $sTime .= $aTime[1];
   }
  } else {
   $sTime = null;
  }

  if (null == $sTime) {
   $sTime = self::getNullValue('time');
   self::setNullValue('time', null);
  }
  return $sTime;
 }

 /**
  * dateTime
  *
  * Máscara
  *
  * @see Mask::date()
  * @param string sValue yyyy-mm-dd hh:ii:ss
  * @param string sTypeDate {@see self::date()}
  * @param string sTypeTime {@see self::time()}
  * @return string
  */
 public static function dateTime($sValue, $sTypeDate = 'full_str', $sTypeTime = 'abrv')
 {
  $sDate = self::date($sValue, $sTypeDate);
  $sTime = self::time($sValue, $sTypeTime);
  if ($sDate && $sTime) {
   $sDateTime = $sDate . ' ' . $sTime;
  } else {
   $sDateTime = null;
  }

  if (null == $sDateTime) {
   $sDateTime = self::getNullValue('dateTime');
   self::setNullValue('dateTime', null);
  }
  return $sDateTime;
 }

 /**
  * year
  *
  * Máscara
  *
  * @param mixed $mValue integer yyyy ou string yyyy-mm-dd hh:ii:ss
  * @return integer
  */
 public static function year($mValue)
 {
  if ($mValue) {
   if (is_numeric($mValue)) {
    $iYear = $mValue;
   } else {
    $iYear = substr($mValue, 0, 4);
   }
  } else {
   $iYear = null;
  }

  if (null == $iYear) {
   $iYear = self::getNullValue('year');
   self::setNullValue('year', null);
  }
  return $iYear;
 }

 /**
  * monthYear
  *
  * Máscara
  * O valor é salvo no banco com hora zero e sempre dia 1: 1976-09-01 00:00:00
  *
  * @param string $sValue string yyyy-mm-dd hh:ii:ss
  * @param string $sType full_str=09/1976, full_int=9/1976, write=set/1976
  * @return string
  */
 public static function monthYear($sValue, $sType = 'full_str')
 {
  $sDate = self::date($sValue);
  if ($sDate) {
   $aDate                       = explode('/', $sDate);
   list($sDay, $sMonth, $sYear) = $aDate;
   if ('write' == $sType) {
    $sMonthYear = Date::writeMonth($sMonth, 'abrv') . '/' . $sYear;
   } else if ('full_int' == $sType) {
    $sMonthYear = (int) $sMonth . '/' . $sYear;
   } else {
    $sMonthYear = $sMonth . '/' . $sYear;
   }
  } else {
   $sMonthYear = null;
  }

  if (null == $sMonthYear) {
   $sMonthYear = self::getNullValue('monthYear');
   self::setNullValue('monthYear', null);
  }
  return $sMonthYear;
 }

 /**
  * number
  *
  * Máscara
  *
  * @param integer $iValue
  * @param string $sMil separador de milhar
  * @return string
  */
 public static function number($iValue, $sMil = '')
 {
  if (null !== $iValue) {
   $sNumber = number_format($iValue, 0, '', $sMil);
  } else {
   $sNumber = null;
  }

  if (null === $sNumber) {
   $sNumber = self::getNullValue('number');
   self::setNullValue('number', null);
  }
  return $sNumber;
 }

 /**
  * float
  *
  * Máscara
  *
  * @param float $fValue
  * @param bool $sMil separador de milhar
  * @param integer $iPrecision número de casas
  * @return string
  */
 public static function float($fValue, $sMil = null, $iPrecision = null)
 {
  if (null !== $fValue) {
   if (!$iPrecision) {
    $aPrecision = explode('.', $fValue);
    $iPrecision = 0;
    if (isset($aPrecision[1])) {
     $iPrecision = strlen($aPrecision[1]);
    }
   }
   $sFloat = number_format($fValue, $iPrecision, ',', $sMil);
  } else {
   $sFloat = null;
  }

  if (null === $sFloat) {
   $sFloat = self::getNullValue('float');
   self::setNullValue('float', null);
  }
  return $sFloat;
 }

 /**
  * floatVar
  *
  * Máscara float, variando o número de casas depois da vírgula
  *
  * @param float $fValue
  * @param bool $sMil separador de milhar
  * @param integer $iMinPrecision número mínimo de casas
  * @return string
  */
 public static function floatVar($fValue, $sMil = null, $iMinPrecision = null)
 {
  if (null !== $fValue) {
   if (0 === $iMinPrecision && ($fValue >= 1 or $fValue <= -1)) {
    // Retorna o valor inteiro, sem casas decipais e coma rredondamento matemático
    $sFloatVar = self::number($fValue, $sMil);
   } else {
    $sValue = self::float($fValue, $sMil, null);
    if ('0' == $sValue) {
     $sFloatVar = 0;
    } else {
     list($iBeforePoint, $sAfterPoint) = explode(',', $sValue);
     $sFloatVar                        = $iBeforePoint . ',';
     for ($i = 0; $i < 10; $i++) {
      $sFloatVar .= $sAfterPoint[$i];
      // se apareceu um número maior que zero para por ai
      if ($sAfterPoint[$i] > 0) {
       // se ainda não atingiu o número mínimo de dígitos continua
       if ($iMinPrecision && ($i < ($iMinPrecision - 1))) {
        continue;
       }
       break;
      }
     }
    }
   }
  } else {
   $sFloatVar = null;
  }
  return $sFloatVar;
 }

 /**
  * currency
  *
  * Máscara
  *
  * @param double $dValue
  * @return string
  */
 public static function currency($dValue)
 {
  if (null !== $dValue) {
   $sCurrency = number_format($dValue, 2, ',', '.');
  } else {
   $sCurrency = null;
  }

  if (null === $sCurrency) {
   return '0,00';
  }
  return $sCurrency;
 }

 /**
  * phone
  *
  * Máscara
  *
  * @param integer $iValue
  * @return string
  */
 public static function phone($iValue)
 {
  $sPhone = null;
  if ($iValue) {
   if (strlen($iValue) == 11) {
    $iValue = preg_replace('/[^0-9]/', '', $iValue);
    $iValue = substr('000000000000' . $iValue, -11);
    $sPhone = '(' . substr($iValue, 0, 2) . ') ';
    $sPhone .= substr($iValue, 2, 5) . '-';
    $sPhone .= substr($iValue, 7, 4);
   } else if (strlen($iValue) == 10) {
    $iValue = preg_replace('/[^0-9]/', '', $iValue);
    $iValue = substr('00000000000' . $iValue, -10);
    $sPhone = '(' . substr($iValue, 0, 2) . ') ';
    $sPhone .= substr($iValue, 2, 4) . '-';
    $sPhone .= substr($iValue, 6, 4);
   } else if (strlen($iValue) == 9 || strlen($iValue) == 8) {
    $iValue = preg_replace('/[^0-9]/', '', $iValue);
    //$iValue = substr('00000000000'.$iValue, -9);
    $sPhone = substr($iValue, 0, 4) . '-';
    $sPhone .= substr($iValue, 4, 4);
   } else if (strlen($iValue) < 8) {
    $sPhone = $iValue;
   }

  } else {
   $sPhone = null;
  }

  if (null == $sPhone) {
   $sPhone = self::getNullValue('phone');
   self::setNullValue('phone', null);
  }
  return $sPhone;
 }

 /**
  * cnpj
  *
  * Máscara
  *
  * @param integer $iValue
  * @return string
  */
 public static function cnpj($iValue)
 {
  if ($iValue) {
   $iValue = preg_replace('/[^0-9]/', '', $iValue);
   $iValue = substr('00000000000000' . $iValue, -14);
   $sCnpj  = substr($iValue, 0, 2) . '.';
   $sCnpj .= substr($iValue, 2, 3) . '.';
   $sCnpj .= substr($iValue, 5, 3) . '/';
   $sCnpj .= substr($iValue, 8, 4) . '-';
   $sCnpj .= substr($iValue, 12, 2);
  } else {
   $sCnpj = null;
  }

  if (null == $sCnpj) {
   $sCnpj = self::getNullValue('cnpj');
   self::setNullValue('cnpj', null);
  }
  return $sCnpj;
 }

 /**
  * cpf
  *
  * Máscara
  *
  * @param integer $iValue
  * @return string
  */
 public static function cpf($iValue)
 {
  if ($iValue) {
   $iValue = preg_replace('/[^0-9]/', '', $iValue);
   $iValue = substr('00000000000' . $iValue, -11);
   $sCpf   = substr($iValue, 0, 3) . '.';
   $sCpf .= substr($iValue, 3, 3) . '.';
   $sCpf .= substr($iValue, 6, 3) . '-';
   $sCpf .= substr($iValue, 9, 2);
  } else {
   $sCpf = null;
  }

  if (null == $sCpf) {
   $sCpf = self::getNullValue('cpf');
   self::setNullValue('cpf', null);
  }
  return $sCpf;
 }

 /**
  * cep
  *
  * Máscara
  *
  * @param integer $iValue
  * @return string
  */
 public static function cep($iValue)
 {
  if ($iValue) {
   $iValue = preg_replace('/[^0-9]/', '', $iValue);
   $iValue = substr('00000000' . $iValue, -8);
   $sCep   = substr($iValue, 0, 5) . '-';
   $sCep .= substr($iValue, 5, 3);
  } else {
   $sCep = null;
  }

  if (null == $sCep) {
   $sCep = self::getNullValue('cep');
   self::setNullValue('cep', null);
  }
  return $sCep;
 }

 /**
  * fileSize
  *
  * Máscara
  *
  * @param integer $iFileSize
  * @return string
  */
 public static function fileSize($iFileSize)
 {
  if ($iFileSize >= 1073741824) {
   $sFileSize = (round($iFileSize / 1073741824 * 100) / 100) . ' GB';
  } else if ($iFileSize >= 1048576) {
   $sFileSize = (round($iFileSize / 1048576 * 100) / 100) . ' MB';
  } else if ($iFileSize >= 1024) {
   $sFileSize = (round($iFileSize / 1024 * 100) / 100) . ' KB';
  } else {
   $sFileSize = $iFileSize . ' bytes';
  }
  return str_replace('.', ',', $sFileSize);
 }
}

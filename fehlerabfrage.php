<?php
function error.string($value)
{
    $level_names = array(
        E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE', E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR', E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR', E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR', E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE' );
    if(defined('E_STRICT')) $level_names[E_STRICT]='E_STRICT';
    $levels=array();
    if(($value&E_ALL)==E_ALL)
    { 
        $levels[]='E_ALL';
        $value&=~E_ALL;
    }
    foreach($level_names as $level=>$name)
        if(($value&$level)==$level) $levels[]=$name;
    return implode(' | ',$levels);
}

function string.error($string)
{
    $level_names = array( 'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE',
        'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING',
        'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE', 'E_ALL' );
    if(defined('E_STRICT')) $level_names[]='E_STRICT';
    $value=0;
    $levels=explode('|',$string);
    foreach($levels as $level)
    {
        $level=trim($level);
        if(defined($level)) $value|=(int)constant($level);
    }
    return $value;
}
?>

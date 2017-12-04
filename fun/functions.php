<?php
function var2file($targetPath, $var)
{
    $writtern = file_put_contents(
        $targetPath,
        '<?php return ' . var_export($var, true) . ';'
    );

    if (false === $writtern) {
        throw new \Exception("Write content to file '$targetPath' failed");
    }
}

function prop($arr, $key, $default = '')
{
    return isset($arr[$key]) ? $arr[$key] : $default;
}

function obj($object)
{
    return $object;
}

function micro_date($time = null)
{
    if (!$time) {
        $time = microtime(true);
    }

    $date = date_create_from_format('U.u', $time);
    return $date->format('Y-m-d\TH:i:s.u');
}

function attr_json($arr)
{
    return htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8');
}

function script_json($obj)
{
    return '<script type="text/json">'
        . json_encode($obj)
        . '</script>'
        . '<div class="data-rendering"></div>';
}

<?php
function var2file(string $targetPath, $var): void
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

/*
function obj($object)
{
    return $object;
}

function micro_date($time = null): string
{
    if (!$time) {
        $time = microtime(true);
    }

    $date = date_create_from_format('U.u', $time);
    return $date->format('Y-m-d\TH:i:s.u');
}

function current_date(): string
{
    return date('Y-m-d\TH:i:s');
}

function current_micro_date(): string
{
    $date = date_create_from_format('U.u', microtime(true));
    return $date->format('Y-m-d\TH:i:s.u');
}

function gap_date_format(): string
{
    return 'Y-m-d\TH:i:s';
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
 */

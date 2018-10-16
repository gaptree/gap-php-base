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

function uuid(): string
{
    // http://www.seanbehan.com/how-to-generate-a-uuid-in-php
    // http://php.net/manual/en/function.uniqid.php
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function uniqBin(int $salt = 0): string
{
    $offset = 12219292800;
    $n100s = intval((microtime(true) + $offset) * (10 ** 7));

    $version = '1'; // 4-bit
    $timeHex = $version. dechex($n100s);
    $timeBin = hex2bin($timeHex);
    if (!$salt) {
        return '' . $timeBin;
    }

    $maxSalt = 2 ** 14;
    $seq = (2 ** 15) + ($salt % $maxSalt);
    return hex2bin(dechex($seq)) . $timeBin;
    /*
    $seq = hex2bin(substr(md5($salt), 0, 4));
    if ($seq === false) {
        throw new \Exception('hex2bin failed');
    }
    $seq[0] = chr(ord($seq[0]) & 0x3f | 0x80);
    return $seq . $timeBin;
     */
}

/*
function uniqBin_old(int $len = 10): string
{
    // https://jason.pureconcepts.net/2013/09/php-convert-uniqid-to-timestamp/
    // https://mariadb.com/kb/en/library/guiduuid-performance/
    if ($len < 8) {
        throw new \Exception("Length of uniqBin cannot less than 8");
    }

    $preLen = 6;
    $micros = intval(microtime(true) * (10 ** 8));
    $pre = substr(dechex($micros), 0, $preLen * 2);
    return hex2bin($pre) . random_bytes($len - $preLen);
}
 */

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

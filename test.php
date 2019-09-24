<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 9/24/19
 * Time: 2:19 PM
 */
require_once 'Snowflake.php';
$snowflake = new Snowflake(1);

$chan = new chan(100000);
$n = 10;

for ($i = 0; $i < $n; $i++) {
    go(function () use ($snowflake, $chan) {
        $id = $snowflake->getId();
        $chan->push($id);
    });
}

go(function () use ($chan, $n) {
    $arr = [];
    for ($i = 0; $i < $n; $i++) {
        $id = $chan->pop();     // PHP Swoole的channel一定要写在go(func)的协程里面！？
        if (in_array($id, $arr)) {
            exit("ID 已存在");
        }
        array_push($arr, $id);
    }
    var_export($arr);
});

$chan->close();

echo "ok";
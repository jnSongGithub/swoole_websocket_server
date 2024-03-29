<?php
define('DEBUG', 'on');
define('WEBPATH', dirname(__DIR__));
use app\common\SLog;
require_once __DIR__ . '/extend/libs/lib_config.php';

//引入TP自带加载文件
require_once __DIR__ . '/thinkphp/base.php';

$client = Swoole\Client\RPC::getInstance();
//$client->setEncodeType(false, true);
$client->setEncodeType(Swoole\Protocol\RPCServer::DECODE_SWOOLE, false);
$client->putEnv('app', 'test');
$client->putEnv('appKey', 'test1234');
$client->auth('xiangqin', 'xiangqin123');

//$client->addServers(array(
//    array('host' => '127.0.0.1', 'port' => 8888),
//));

//$client->addServers(array('host' => 'devtabossapi.budingvip.com', 'port' => 8888));
$client->addServers(array('host' => 'devtaapi.budingvip.com', 'port' => 16666));


$s = microtime(true);
$ok = $err = 0;
for ($i = 0; $i < 1; $i++)
{
    $s2 = microtime(true);
    $ret1 = $client->task("app\\index\\controller\\RpcServer::getUserInfo", ["hello{$i}_1"], function($retObj) {
        echo "task1 finish\n";
    });
    echo json_encode($ret1);
//    $ret2 = $client->task("app\im\controller\\RClient::index");
//    $ret3 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_3"]);
//    $ret4 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_4"]);
//    $ret5 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_5"]);
//    $ret6 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_6"]);
//    $ret7 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_7"]);
//    $ret8 = $client->task("app\im\controller\\RClient::index", ["hello{$i}_8"]);
    echo "send " . (microtime(true) - $s2) * 1000, "\n";

    $n = $client->wait(0.5); //500ms超时

    SLog::INFO('测试SLOG');

    echo $n."超时打印\n";
    var_dump($ret1->data);
    echo "finish\n";
    $ok++;


   /* //表示全部OK了
    if ($n === 8)
    {
//        var_dump($ret1->data, $ret2->data, $ret3->data, $ret4->data, $ret5->data, $ret6->data, $ret7->data, $ret8->data);
        var_dump($ret1->data);
        echo "finish\n";
        $ok++;
    }
    else
    {
        echo "#{$i} \t";
//        echo $ret1->code . '|' . $ret2->code . '|' . $ret3->code . '|' . $ret4->code . '|' . $ret5->code . '|' . $ret6->code . '|' . $ret7->code . '|' . $ret8->code . '|' . "\n";
        echo $ret1->code . "\n";
        $err++;
        exit;
    }*/
//    unset($ret1, $ret2, $ret3, $ret4, $ret5, $ret6, $ret7, $ret8);
    unset($ret1);
}
echo "failed=$err.\n";
echo "success=$ok.\n";
echo "use " . (microtime(true) - $s) * 1000, "ms\n";
unset($client, $ret1, $ret2);

<?php

// 关于压测
// 压测工具：https://github.com/wg/wrk
// 此脚本用于生成 wrk 的测试脚本文件
// 
// 压测命令：
// wrk -t4 -c2000 -d60s -T5s --script=hprose.lua --latency http://hprose.vm/server.php
// 
// Usage: wrk <options> <url>
//  Options:
//    -c, --connections <N>  Connections to keep open
//    -d, --duration    <T>  Duration of test
//    -t, --threads     <N>  Number of threads to use
//    -s, --script      <S>  Load Lua script file
//    -H, --header      <H>  Add header to request
//        --latency          Print latency statistics
//        --timeout     <T>  Socket/request timeout
//    -v, --version          Print version details
// 
// 压测结果：
// 
// Running 1m test @ http://hprose.vm/server.php
//   4 threads and 2000 connections
//   Thread Stats   Avg      Stdev     Max   +/- Stdev
//     Latency   997.84ms    1.31s    5.00s    83.32%
//     Req/Sec   270.33    793.67     6.01k    93.74%
//   Latency Distribution
//      50%  320.31ms
//      75%    1.67s
//      90%    3.14s
//      99%    4.78s
//   29587 requests in 1.00m, 9.57MB read
//   Socket errors: connect 0, read 2203, write 0, timeout 3404
//   Non-2xx or 3xx responses: 26665
// Requests/sec:    491.82
// Transfer/sec:    162.92KB

require_once dirname(__FILE__) . "/inc.php";

// 需要测试的接口方法及参数
// 每次请求后会将请求的接口参数写入 hprose.lua 文件中
// 通过浏览器请求此文件所在地址或在命令行下执行 php ./index.php
// 如下面生成的是测试 toArray 接口方法, 传递参数为 "php"。
$client->toArray("php");

echo "Done;";

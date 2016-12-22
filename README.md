# hprose 接口压测方法及工具

这个项目用来说说如何对使用 Hprose 开发的接口进行压力测试。

# 压测工具 Wrk

压测工具采用Wrk，支持Linux及Mac环境。

[Linux 安装](https://github.com/wg/wrk/wiki/Installing-Wrk-on-Linux)

[Mac 安装](https://github.com/wg/wrk/wiki/Installing-wrk-on-OSX)

# 本工具的使用

本工具可帮助你生成用于接口测试的脚本文件。

## 下载本工具

```
git clone https://github.com/Lao-liu/hprose-http-benchmark-wrk.git hhbw
cd hhbw
```

## 安装扩展包

```
composer install
```

## 修改接口配置

使用编辑器打开 .env 文件进行编辑，填写接口地址。

```
vim .env

// OR
subl .env
```

## 修改需要测试的接口方法及参数

```
// $client 即 Hprose http client

$client->调用方法("传入参数1", "传入参数2", "传入参数3");

```

## 生成测试用配置文件

```
php ./index.php
```

# 使用 Wrk 进行压测

```
wrk -t4 -c2000 -d60s -T5s --script=hprose.lua --latency http://hprose-service-url
```

打印测试结果

```
//   查看压测测试结果
//   Running 1m test @ http://hprose.vm/server.php
//     4 threads and 2000 connections
//     Thread Stats   Avg      Stdev     Max   +/- Stdev
//       Latency   997.84ms    1.31s    5.00s    83.32%
//       Req/Sec   270.33    793.67     6.01k    93.74%
//     Latency Distribution
//        50%  320.31ms
//        75%    1.67s
//        90%    3.14s
//        99%    4.78s
//     29587 requests in 1.00m, 9.57MB read
//     Socket errors: connect 0, read 2203, write 0, timeout 3404
//     Non-2xx or 3xx responses: 26665
//   Requests/sec:    491.82
//   Transfer/sec:    162.92KB
```
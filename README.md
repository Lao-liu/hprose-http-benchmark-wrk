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

修改接口方法，主要是编写Hprose-php的客户端。代码会将客户端中请求的所有内容记录下来生成 hprose.lua 帮助你后续进行压力测试。

```
subl index.php
// $client 即 Hprose http client

$client->调用方法("传入参数1", "传入参数2", "传入参数3");
```

### 例如

```php
// 远程hello方法，参数 string "laoliu"
$client->hello("laoliu");

// 远程count方法，参数数组
$client->count(["a", "b", "c", "d", "e", "f", "g"]);

// 远程toArray方法，参数对象
$obj = new stdClass();
$obj->param1 = 'a';
$obj->param2 = 'x';
$client->toArray($obj);
```

## 生成测试用配置文件

```
php ./index.php
```

# 使用 Wrk 进行压测

下面我们就可以开始测试啦，下面是我的测试结果。CentOS 7.3 VM 2CPU 2G内存 `PHP7` `Hprose 2.0.26`

## 打印测试结果

4线程 2000并发 持续60秒 5秒超时

```
wrk -t4 -c2000 -d60s -T5s --script=hprose.lua --latency http://hprose-service-url
```

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

2线程 1000并发 持续60秒

```
wrk -t2 -c1000 -d60s --script=hprose.lua --latency http://hprose-service-url
```

```
//   查看压测测试结果
//   Running 1m test @ http://hprose.vm/server.php
//     2 threads and 1000 connections
//     Thread Stats   Avg      Stdev     Max   +/- Stdev
//       Latency   262.86ms  462.53ms   2.00s    85.01%
//       Req/Sec     1.12k     2.30k   11.23k    85.71%
//     Latency Distribution
//        50%   33.53ms
//        75%  308.36ms
//        90%  984.34ms
//        99%    1.89s
//     51418 requests in 1.03m, 16.42MB read
//     Socket errors: connect 0, read 407, write 0, timeout 8184
//     Non-2xx or 3xx responses: 48508
//   Requests/sec:    834.47
//   Transfer/sec:    272.92KB
```

1线程 500并发 持续60秒

```
wrk -t1 -c500 -d60s --script=hprose.lua --latency http://hprose-service-url
```

```
//   查看压测测试结果
//   Running 1m test @ http://hprose.vm/server.php
//     1 threads and 500 connections
//     Thread Stats   Avg      Stdev     Max   +/- Stdev
//       Latency    62.40ms  101.03ms   1.70s    90.22%
//       Req/Sec     8.97k     4.92k   22.59k    63.13%
//     Latency Distribution
//        50%   28.30ms
//        75%   54.99ms
//        90%  160.61ms
//        99%  429.21ms
//     462280 requests in 1.00m, 145.54MB read
//     Socket errors: connect 0, read 0, write 0, timeout 2522
//     Non-2xx or 3xx responses: 459746
//   Requests/sec:   7695.46
//   Transfer/sec:      2.42MB
```
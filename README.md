**jsonrpc-cli is a command-line tool that performs json-rpc queries**


# About

This tool is useful for manually performing single json-rpc queries from the command-line.

Typically this is useful for debugging purposes or one-off needs.

A distinguishing feature of this client is that it supports logging the raw HTTP communications
(request, headers, response) to a logfile.  So one can see exactly what is sent to the server
and what it returns.

The tool also colors the results (syntax highlighting) and can output results in multiple structured data
formats such as json, yaml, print_r, var_dump, and php's serialization format.

# Motivation

I built this tool because I needed to debug json-rpc interactions between two third-party tools.  I wanted
to see exactly what http headers are sent and exactly what the server responds with, but existing json-rpc
tools and libraries I found did not support this.


# Examples

## Basic typical usage

By default, only the value of the "result" field of the server's json-rpc response will be displayed.

```
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword  http://localhost:28332/ getblockcount

588589
```

## Server's full json response.

For this, we specify --resultonly=off

```json
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --resultonly=off  http://localhost:28332/ getblockcount

{
    "error": null,
    "result": 588590,
    "id": 2048885931,
    "jsonrpc": "2.0"
}
```


### Need to debug?  We can log the full http request and response to a file.

```
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --format=raw --httpfile=/tmp/http.log  http://localhost:28332/ getblockcount

{"error": null, "result": 588593, "id": 941852157, "jsonrpc": "2.0"}

$ cat /tmp/http.log
POST / HTTP/1.0
Accept: text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,image/jpeg,image/gif,*/*
Accept-Language: en-us
Host: localhost
User-Agent: Mozilla/4.0 (compatible; jsonrpc-cli HTTP Client; Linux)
Connection: Close
Content-Length: 57
Authorization: Basic cnBjdXNlcjpycGNwYXNzd29yZA==
Cookie: 

{"jsonrpc":"2.0","method":"getblockcount","id":941852157}

HTTP/1.0 200 OK
Content-Type: application/json; charset=utf-8
Content-Length: 68
Date: Sun, 04 Aug 2019 16:09:41 GMT
Server: Python/3.5 aiohttp/3.0.0b0

{"error": null, "result": 588593, "id": 941852157, "jsonrpc": "2.0"}
```

## We can also display the result data in other formats.

Available formats are raw|json|jsonpretty|yaml|printr|vardump|serialize


### Let's see the server's raw response without any formatting.

```
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --format=raw  http://localhost:28332/ getblockcount

{"error": null, "result": 588593, "id": 641121075, "jsonrpc": "2.0"}
```


### yaml example

```yaml
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --format=yaml  http://localhost:28332/ getblockchaininfo

pruned: false
bestblockhash: >
  0000000000000000001babf6c3e29d839db7b208dfab64948b015796a8b45a72
headers: 588593
blocks: 588593
chain: main
difficulty: null
chainwork: null
warning: >
  spruned 0.0.2a3, emulating bitcoind
  v0.16
verificationprogress: 100
mediantime: 1564934597
```

### printr example

```
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --format=printr  http://localhost:28332/ getblockchaininfo

Array
(
    [pruned] => 
    [bestblockhash] => 0000000000000000001babf6c3e29d839db7b208dfab64948b015796a8b45a72
    [headers] => 588593
    [blocks] => 588593
    [chain] => main
    [difficulty] => 
    [chainwork] => 
    [warning] => spruned 0.0.2a3, emulating bitcoind v0.16
    [verificationprogress] => 100
    [mediantime] => 1564934597
)
```

# Use at your own risk.

The author makes no claims or guarantees of correctness.

By using this software you agree to take full responsibility for any losses
incurred before, during, or after the usage, whatsoever the cause, and not to
hold the software author liable in any manner.


# Output formats

The output may be printed in the following formats:
* jsonpretty - pretty-printed json.  ( default )
* json       - compact json
* yaml       - pretty printed yaml
* raw        - raw text response from server
* printr     - tree format using php's print_r() function
* vardump    - tree format using php's var_dump() function
* serialize  - tree format using php's serialize() function

# Highlighting / colors

Colored output is available for all formats except raw.

Use the flag --highlight=on (default) or --highlight=off to disable.

Examples:

![Image](doc/image/example1.png?raw=true)

![Image](doc/image/example2.png?raw=true)

![Image](doc/image/example3.png?raw=true)


Or, if you use the one true Green on Black background:
![Image](doc/image/example4.png?raw=true)

# Errors

Exceptions are displayed as structured data, according to --format flag and can be easily read
by a calling program.

```yaml
$ ./jsonrpc-cli --user=rpcuser --pass=rpcpassword --format=yaml  http://localhost:28332/ getblockchaininfos
message: HTTP/1.0 404 Not Found
class: JsonRPC\Exception\NotFoundException
code: 404
file: >
  /tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/HttpClient.php
line: 654
trace:
  - '/tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/HttpClient.php:324 in JsonRPC\HttpClient->handleExceptions'
  - '/tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/HttpClient.php:152 in JsonRPC\HttpClient->sendRequest'
  - '/tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/HttpClient.php:159 in JsonRPC\HttpClient->post'
  - '/tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/Client.php:195 in JsonRPC\HttpClient->execute'
  - '/tmp/jsonrpc-cli/vendor/dan-da/json-rpc/src/JsonRPC/Client.php:177 in JsonRPC\Client->sendPayload'
  - '/tmp/jsonrpc-cli/src/AppCore.php:66 in JsonRPC\Client->execute'
  - '/tmp/jsonrpc-cli/jsonrpc-cli:43 in App\AppCore->request'
  - /tmp/jsonrpc-cli/jsonrpc-cli:73 in main
```

# Usage

```
$./jsonrpc-cli --help

   jsonrpc-cli.php [options] <url> <method> [params]

   This script makes a request to a jsonrpc server.
   
   params may be provided as either:
     (a) space separated scalar values, eg: "6" or "6" "7"  -- or --
     (b) json values, eg: "[6] or "[6,7]", or '{color: "red", size: "small"}'
         
         note: json values can have nested arrays or objects. 

   Options:
   
    --user=<user>        username for http basic auth
    --pass=<pass>        password for http basic auth

    --outfile=<path>     specify output file path.
    
    --timeout=<s>        request timeout in secs.  default = none.
    
    --format=<format>    [ raw|json|jsonpretty|yaml|printr|vardump|serialize|all ]
                           
                         default=jsonpretty
                         
                         raw will print response exactly as received from
                         server (after http chunk decoding) even if invalid json.
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
    --resultonly=<flag>  [ on | off ]   default = on.
                           on  --> display "result" key of server's json response.
                           off --> display server's entire json response.
                           
                           note: --format=raw forces --resultonly=off
                         
    --highlight=<flag>   [ on | off ]   default = on.
                           highlights output if possible, depending on --format.

    --logfile=<path>    path to logfile. if not present logs to stdout.
    --loglevel=<level>  debug,info,specialinfo,warning,exception,fatalerror
                          default = info
                          
    --httpfile=<path>   writes raw http request, headers, and response to a file.
```


# Installation and Running.

Ubuntu 16.04 requirements:
```
$ apt-get install php composer
```

Basics
```
$ git clone https://github.com/dan-da/jsonrpc-cli
$ cd jsonrpc-cli
$ composer install
$ ./jsonrpc-cli --help
```


# Todos

* make test cases
* Support batching (maybe)


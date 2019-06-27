**jsonrpc-cli is a command-line tool that performs json-rpc queries**


# About

This tool is useful for manually performing single json-rpc queries from the command-line.

Typically this is useful for debugging purposes or one-off needs.

A distinguishing feature of this client is that it supports logging the raw HTTP communications
(request, headers, response) to a logfile.  So one can see exactly what the server is
receiving and what it returns.


# examples.

## example 1.

Todo.



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

# Usage

```
   jsonrpc-cli.php [options] <url> <method> [params]

   This script makes a request to a jsonrpc server.
   
   params may be provided as either:
     (a) space separated scalar values, eg: "6" or "6" "7"  -- or --
     (b) json values, eg: "[6] or "[6,7]", or '{color: "red", size: "small"}'
         
         note: json values can have nested arrays or objects. 

   Options:
   
    --user <user>        username for http basic auth
    --pass <pass>        password for http basic auth

    --outfile=<path>     specify output file path.
    
    --timeout=<s>        request timeout in secs.  default = none.
    
    --format=<format>    [ raw|json|jsonpretty|yaml|printr|vardump|serialize|all ]
                           
                         default=jsonpretty
                         
                         raw will print response exactly as received from
                         server (after http chunk decoding) even if invalid json.
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
    --highlight=<flag>   [ 'on' | 'off' ]   default = on.
                           highlights output if possible, depending on --format.

    --logfile=<path>    path to logfile. if not present logs to stdout.
    --loglevel=<level>  debug,info,specialinfo,warning,exception,fatalerror
                          default = info
                          
    --httpfile=<path>   writes raw http request, headers, and response to a file.
```


# Installation and Running.

Linux Ubuntu 16.04 requirements:
```
apt-get install php composer
```

Basics   ( see below for big performance speedup )
```
 git clone https://github.com/dan-da/jsonrpc-cli
 cd jsonrpc-cli
 composer install
```



# Todos

* Handle error conditions better
* Support batching (maybe)
* colored json pretty printing

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
* raw        - raw text response from server
* printr     - tree format using php's print_r() function
* vardump    - tree format using php's var_dump() function
* serialize  - tree format using php's serialize() function
* md         - markdown formatted table
* txt        - an ascii formatted table, intended for humans.
* html       - an html formatted table.
* csv        - CSV formatted table.  For spreadsheet programs.
* list       - single column list. for easy cut/paste.  uses first col.

note: table formats assume that results are an array of objects where each
inner object represents a row.  Scalar values are converted to this format.
Other structures may not work well.


# Usage

```
$ ./jsonrpc-cli --help

   jsonrpc-cli.php [options] <url> <method> [params]

   This script makes a request to a jsonrpc server.
   
   params should be provided in json format.  eg:
     "6" or "6,7", or "[6,7]", or '{color: "red", size: "small"}'

   Options:
   
    --user <user>        username for http basic auth
    --pass <pass>        password for http basic auth

    --outfile=<path>     specify output file path.
    
    --format=<format>    [ raw|txt|md|csv|json|jsonpretty|html|list
                           printr|vardump|serialize|all ]
                           
                         default=jsonpretty
                         
                         raw will print response exactly as received from
                         server (after http chunk decoding) even if invalid json.
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
                         'list' prints only the first column. see --cols

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

# Aerospike::Store for PHP

Use Aerospike as session store for PHP.
Aerospike easily scales up, and besides RAM it also supports SSD for persistency in a highly optimized architecture. This session handler supports auto expiration of sessions at database level.
Find out more about [Aerospike](http://www.aerospike.com).

## Dependencies

- It is written for PHP 5.4 and above. 
- [Aerospike PHP Client](https://github.com/aerospike/aerospike-client-php): 
Using [Composer](https://getcomposer.org/) the dependency will be installed automatically.

## Installation

### Using Composer

    $ composer require aerospike/store "*"

### Manually

Download and use `AerospikeSessionHandler.php`.

## Usage

Auto-load or:

```php
require 'AerospikeSessionHandler.php';
```

Create an instance of `AerospikeSessionHandler` and set it as session handler:
 
```php
$Handler = new AerospikeSessionHandler();
session_set_save_handler($Handler);
session_start();
```
It is possible to pass a custom client instance:

```php
$db = new Aerospike(["hosts" => [["addr" => "127.0.0.1", "port" => 3000]]]);
$Handler = new AerospikeSessionHandler($db);
```

And also a custom set of options:

```php
$Handler = new AerospikeSessionHandler(NULL, array(
	'addr' => '127.0.0.1',
	'port' => 3000,
	'ns' => 'test',
	'set' => 'session',
	'bin' => 'data',
	'ttl' => 3600 // defualt = session.gc_maxlifetime
));
```

## Single-Bin
By default an Aerospike namespace supports multiple `bins` per key. As the session store only use a single bin for stroing data, it is recommended to to enable `single-bin` option in namespace configuration for higher performance.

## Contributing

1. Fork it ( https://github.com/amirrf/aerospike-store-php/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request

## License

The Aerospike-Store-PHP is made available under the terms of the Apache License, Version 2, as stated in the file `LICENSE`.

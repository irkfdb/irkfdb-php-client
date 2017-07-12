# irkfdb.in php client
PHP API Client for the Internet Rajinikanth Facts Database. Its a wrapper class for the free database of Rajinikanth Facts hosted by irkfdb.in

## Status
[![Build Status](https://travis-ci.org/irkfdb/irkfdb-php-client.svg?branch=master)](https://travis-ci.org/irkfdb/irkfdb-php-client)

## Install
Using Composer

```
composer require irkfdb/irkfdb-php-client
```

## Usage
To get the categories
```
require_once "vendor/autoload.php";

use Irkfdb\IrkfdbClient;

$irkfdbClient = new IrkfdbClient();
$irkfdbClient->getCategories()
```

Sample Response
```
Array
(
    [status] => OK
    [resultSet] => Array
        (
            [data] => Array
                (
                    [0] => nsfw
                    [1] => geeky
                )
        )
)
```

In case of API failure, the response would be as follows
Sample Response
```
Array
(
    [status] => FAIL
    [errMessage] => '<err message>'
)
```

To get the random fact
```
$irkfdbClient = new IrkfdbClient();
$irkfdbClient->getRandomFact()
```

Sample Response
```
Array
(
    [status] => OK
    [resultSet] => Array
        (
            [data] => Array
                (
                    [0] => Array
                        (
                            [hash_id] => 9a004def16176d9a2b258a15bf898119
                            [db_id] => 426
                            [fact] => Rajinikanth writes code that optimizes itself.
                            [categories] => Array
                                (
                                    [0] => geeky
                                )
                            [sources] => Array
                                (
                                    [0] => api.icndb.com
                                    [1] => raw.githubusercontent.com/jenkinsci
                                )
                        )
                )

            [total_facts] => 9361
        )
)
```

To get the random fact from the selected category/categories
```
$irkfdbClient = new IrkfdbClient();
// random fact belonging to one category
$irkfdbClient->fromCategories('geeky')->getRandomFact();

//or for multiple categories
$irkfdbClient->fromCategories('nsfw,geeky')->getRandomFact();
or
$irkfdbClient->fromCategories(['nsfw','geeky'])->getRandomFact();
```

To exclude the fact from the particular category/categories
```
$irkfdbClient = new IrkfdbClient();
// random fact belonging to one category
$irkfdbClient->excludeCategories('geeky')->getRandomFact();

//or for multiple categories
$irkfdbClient->excludeCategories('nsfw,geeky')->getRandomFact();
or
$irkfdbClient->excludeCategories(['nsfw','geeky'])->getRandomFact();
```

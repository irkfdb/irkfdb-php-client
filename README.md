# irkfdb.in php client
PHP API Client for the Internet Rajinikanth Facts Database. Its a wrapper class for the free database of Rajinikanth Facts hosted by irkfdb.in

## Install
Using Composer

`composer require irkfdb/irkfdb-php-client`

## Usage
To get the categories
```
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

# Parse ansible output to PHP array/Object 

# Installation
### The recommended approach is to install the project through [Composer](https://getcomposer.org/).
```php
composer require laijim/playbook-conclusion
```

#Usage
```php
$parser = new \Laijim\PlaybookConclusion\Conclusion(
    new \Laijim\PlaybookConclusion\Impl\RegExParser(
        new \Laijim\PlaybookConclusion\Entity\Result()
    )
);
$resultObj = $parser->parse($str);

echo $resultObj->finalResult;
echo $resultObj->isSuccess();
echo $resultObj->isFail();
var_dump($resultObj->verbose);
var_dump($resultObj->statistics);
foreach ($resultObj as $row){
    var_dump($row);
}
```
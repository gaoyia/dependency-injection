<h1 align="center"> dependency-injection </h1>

<p align="center"> Let your custom class methods also enjoy dependency injection.</p>

## Doc

- [中文文档](https://github.com/iiDestiny/dependency-injection/blob/master/Zh-README.md)

## Requirement

- PHP >= 7.0

## install

```bash
composer require iidestiny/dependency-injection -vvv
```

## Usage

Use helper methods

```php
    // register class
    di_register(Tools::class)
    
    // Call a method in a class
    di_register(Tools::class)->generate($param, $param, $param)
    
    // more
    di_register(Tools::class)->foo($bar)
```

## example
Sometimes we need dependency injection when calling our own defined methods. See the example below.

```php
<?php

namespace App\Services;


use App\Tools;
use App\User;
use Cache;
use Petstore30\Order;

class OrderService
{
    /**
     * place order
     *
     * @param User  $user
     * @param       $goods
     * @param       $address
     * @param Cache $cache
     * @param Tools $tools
     */
    public function placeOrder(User $user, $goods, $address, Cache $cache, Tools $tools)
    {
        // code something
    }

    /**
     * pay
     *
     * @param Order $order
     * @param Cache $cache
     * @param Tools $tools
     */
    public function pay(Order $order, Cache $cache, Tools $tools)
    {
        // code something
    }

}

```

Then we easily use the method of dependency injection

```php
    /**
     * store
     */
    public function store()
    {
        di_register(OrderService::class)->placeOrder($user, $goods, $address);
    }
```

or

```php
/**
     * store
     */
    public function store()
    {
        $orderService = di_register(OrderService::class);
        
        $orderService->placeOrder($user, $goods, $address);
        $orderService->pay($order);
    }
```

## License

MIT
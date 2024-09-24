<?php

namespace ItpassionLtd\Countries\Tests;

use ItpassionLtd\Countries\Models\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testItHasCurrencyEuro()
    {
        $currency = Currency::whereAlpha3('EUR')->first();
        $this->assertNotNull($currency);
    }
}
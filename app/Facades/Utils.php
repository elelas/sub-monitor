<?php


namespace App\Facades;


use App\Utils\Utils as UtilsImplementation;
use Illuminate\Support\Facades\Facade;

class Utils extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UtilsImplementation::class;
    }
}
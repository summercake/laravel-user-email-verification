<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 9/1/18
 * Time: 4:25 PM
 */

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
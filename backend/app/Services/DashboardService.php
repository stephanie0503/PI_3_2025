<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Client;
class DashboardService
{

 public static function getProductsCount() {
    
    return Product::count();
 }

 public static function getClientsCount() {
   return Client::count();
 }

}
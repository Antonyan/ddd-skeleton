<?php

namespace Contexts\RestaurantManagement;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Infrastructure\Models\PaginationCollection;
use Infrastructure\Models\SearchCriteria\SearchCriteria;

interface RestaurantManagementContract
{
    public function loadRestaurants(SearchCriteria $conditions) : PaginationCollection;

    public function create(array $data) : Restaurant;

    public function update($id, array $data) : Restaurant;

    public function delete($id) : bool;

    public function get($id) : Restaurant;
}
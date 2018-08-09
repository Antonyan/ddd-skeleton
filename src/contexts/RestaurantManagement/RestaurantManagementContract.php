<?php

namespace Contexts\RestaurantManagement;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Doctrine\Common\Collections\ArrayCollection;

interface RestaurantManagementContract
{
    public function loadRestaurants() : ArrayCollection;

    public function create(array $data) : Restaurant;

    public function update($id, array $data) : Restaurant;

    public function delete($id) : bool;

    public function get($id) : Restaurant;
}
<?php

namespace App\Repositories;

use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * Create a new order.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;

    /**
     * Retrieve order details by ID.
     *
     * @param int $id
     * @return Order
     */
    public function show(int $id): Order;
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderRepository;

    /**
     * Inject the OrderRepositoryInterface into the controller.
     *
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Create a new order.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderRepository->create($request->validated());
            return response()->json([
                'message' => 'Order created successfully!',
                'data' => new OrderResource($order),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Retrieve order details.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->show($id);

        return response()->json(new OrderResource($order), 200);
    }
}

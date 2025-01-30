<?php

namespace App\Http\Controllers;

use App\Enums\Checkout\OrderStatus;
use App\Models\Order;
use App\Models\ShippingOrder;
use App\Services\Shipping\DHL\DHLShipmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShippingController extends Controller
{
    public function __construct(
        private readonly DHLShipmentService $shipmentService
    ) {
    }

    public function showTracking()
    {
        return view("storefront.shipping.tracking");
    }

    public function createShipment(Request $request, Order $order)
    {
        try {
            // Check if shipping order already exists
            if ($order->shippingOrder()->exists()) {
                return response()->json(
                    [
                        "error" =>
                            "Shipping order already exists for this order",
                    ],
                    400
                );
            }

            // Create DHL shipment
            $shipmentData = $this->shipmentService->createShipment($order);

            // Save shipping label PDF if present
            $labelPath = null;
            if (!empty($shipmentData["shipping_label_data"])) {
                $labelPath = "shipping-labels/{$order->order_number}.pdf";
                Storage::put(
                    $labelPath,
                    base64_decode($shipmentData["shipping_label_data"])
                );
            }

            // Create shipping order record
            $shippingOrder = ShippingOrder::create([
                "order_id" => $order->id,
                "carrier" => "dhl",
                "service_code" => $order->shipping_method,
                "tracking_number" => $shipmentData["tracking_number"],
                "label_url" => $labelPath,
                "carrier_response" => $shipmentData["raw_response"],
                "status" => "created",
                "shipped_at" => now(),
            ]);

            // Update order status
            $order->update([
                "status" => OrderStatus::PROCESSING,
            ]);

            // Send shipping confirmation email
            // TODO: Implement email notification

            return response()->json([
                "success" => true,
                "shipping_order" => $shippingOrder,
                "tracking_url" => $shippingOrder->getTrackingUrl(),
            ]);
        } catch (Exception $e) {
            Log::error("Failed to create shipment", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    "error" => "Failed to create shipment: " . $e->getMessage(),
                ],
                500
            );
        }
    }

    public function downloadLabel(ShippingOrder $shippingOrder)
    {
        if (
            !$shippingOrder->label_url ||
            !Storage::exists($shippingOrder->label_url)
        ) {
            return back()->with("error", "Shipping label not found");
        }

        return Storage::download(
            $shippingOrder->label_url,
            "shipping-label-{$shippingOrder->order->order_number}.pdf"
        );
    }

    public function trackShipment(ShippingOrder $shippingOrder)
    {
        try {
            $trackingInfo = $this->shipmentService->trackShipment(
                $shippingOrder->tracking_number
            );

            if (
                isset($trackingInfo["delivered"]) &&
                $trackingInfo["delivered"]
            ) {
                $shippingOrder->update([
                    "status" => "delivered",
                    "delivered_at" => $trackingInfo["delivered_at"],
                ]);
            }

            return response()->json($trackingInfo);
        } catch (Exception $e) {
            Log::error("Failed to track shipment", [
                "tracking_number" => $shippingOrder->tracking_number,
                "error" => $e->getMessage(),
            ]);

            return response()->json(
                [
                    "error" => "Failed to retrieve tracking information",
                ],
                500
            );
        }
    }
}

<?php

namespace App\Services\Order;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\Invoice\InvoiceService;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Mail;

readonly class OrderProcessor
{
    //    TODO: copy function from TabbyPaymentVerification handleExpired , handleCanceled ,handleClosed , handleRefund ... All extract to this service
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    /**
     * @throws Exception
     */
    public function processSuccessfulPayment(
        Order $order,
        array $paymentDetails = [],
        bool $isCaptured = true
    ): void {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Update order status
            $order->update([
                "status" => OrderStatus::PROCESSING,
                "payment_status" => PaymentStatus::PAID,
                "payment_authorized_at" => now(),
                "payment_method_details" => $paymentDetails,
            ]);

            if ($isCaptured) {
                $order->update([
                    "payment_captured_at" => now(),
                ]);
            }

            $this->invoiceService->generateInvoice($order);

            //TODO: don't send mail 2 times
            if (app()->isProduction()) {
                Mail::to($order->email)->queue(
                    new OrderConfirmationMail($order)
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Failed to process order", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function processFailedPayment(
        Order $order,
        array $failureDetails = []
    ): void {
        try {
            $order->update([
                "status" => OrderStatus::CANCELLED,
                "payment_status" => PaymentStatus::FAILED,
                //                "payment_failure_details" => $failureDetails,
            ]);

            //TODO: Here you might want to:
            // 1. Release held inventory
            // 2. Send payment failed notification
            // 3. Log the failure details
        } catch (Exception $e) {
            Log::error("Failed to process payment failure", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function processRefund(Order $order, array $refundDetails = []): void
    {
        try {
            $order->update([
                "status" => OrderStatus::REFUNDED,
                "payment_status" => PaymentStatus::REFUNDED,
                "payment_refunded_at" => now(),
                //                "refund_details" => $refundDetails,
            ]);

            // TODO: Here you might want to:
            // 1. Generate credit note
            // 2. Send refund confirmation email
            // 3. Update inventory if necessary
        } catch (Exception $e) {
            Log::error("Failed to process refund", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

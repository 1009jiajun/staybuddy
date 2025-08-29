<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Handle the payment callback from ToyyibPay.
     * This is called by ToyyibPay's server-to-server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Request $request)
    {
        Log::info('ToyyibPay Callback Received: ' . json_encode($request->all()));

        $billCode = $request->input('billcode');
        $paymentStatusId = $request->input('status_id');
        $transactionId = $request->input('transaction_id');
        $externalReference = $request->input('refno');

        Log::debug("Callback Parameters - BillCode: {$billCode}, StatusID: {$paymentStatusId}, TransactionID: {$transactionId}, ExternalRef: {$externalReference}");

        $booking = Booking::where('external_reference', $externalReference)
                        ->orWhere('toyyibpay_bill_code', $billCode)
                        ->first();

        if (!$booking) {
            Log::warning("ToyyibPay callback for unknown booking: BillCode {$billCode}, ExternalRef {$externalReference}. Request Data: " . json_encode($request->all()));
            return response('Booking not found in our system', 200);
        }

        Log::debug("Booking found. Current Status: {$booking->status}, Current Transaction ID: {$booking->transaction_id}, Current Paid At: {$booking->paid_at}");

        $updates = [];
        $logMessage = "Booking {$booking->id} status updated to ";

        if ($paymentStatusId == 1) { // ToyyibPay "Success"
            if ($booking->status !== 'pending' || $booking->transaction_id === null) {
                Log::debug("Payment status ID is 1 (Success). Booking not yet pending or transaction_id is null. Preparing update.");
                $updates['status'] = 'pending';
                $updates['paid_at'] = Carbon::now();
                $updates['transaction_id'] = $transactionId;
                $logMessage .= "pending (from ToyyibPay success, Transaction ID: {$transactionId}).";
            } else {
                Log::info("Booking {$booking->id} already has 'pending' status and transaction ID. Skipping update from success callback.");
            }
        } elseif ($paymentStatusId == 2) { // ToyyibPay "Failed"
            if ($booking->status !== 'unpaid' && $booking->status !== 'completed') {
                Log::debug("Payment status ID is 2 (Failed). Booking status is {$booking->status}. Preparing update to 'unpaid'.");
                $updates['status'] = 'unpaid';
                $logMessage .= "unpaid (from ToyyibPay failed callback).";
            } else {
                Log::info("Booking {$booking->id} status is {$booking->status}. Not changing to unpaid from failed callback.");
            }
        } elseif ($paymentStatusId == 3) { // ToyyibPay "Pending"
            if ($booking->status === 'unpaid') {
                Log::debug("Payment status ID is 3 (Pending). Booking status is 'unpaid'. Preparing update to 'pending'.");
                $updates['status'] = 'pending';
                $logMessage .= "pending (from ToyyibPay pending callback).";
            } else {
                Log::info("Booking {$booking->id} status is {$booking->status}. No change needed from ToyyibPay pending callback.");
            }
        } else {
            Log::warning("ToyyibPay callback: Unknown status_id ({$paymentStatusId}) for Booking {$booking->id}. No status change.");
            return response('Callback received, unknown status_id', 200);
        }

        if (!empty($updates)) {
            Log::debug('Applying updates: ' . json_encode($updates));
            try {
                $booking->update($updates);
                Log::info($logMessage . ' - Update successful.');
            } catch (\Exception $e) {
                Log::error("Failed to update booking {$booking->id} from ToyyibPay callback: " . $e->getMessage() . '. Trace: ' . $e->getTraceAsString());
            }
        } else {
            Log::info("Booking {$booking->id} status is already appropriate, no update needed from callback for status_id {$paymentStatusId}.");
        }

        return response('Callback received', 200);
    }

    /**
     * Handle the payment return from ToyyibPay.
     * This is where the user is redirected after payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleReturn(Request $request)
    {
        $statusId = $request->query('status_id'); // ToyyibPay's return status_id
        $billcode = $request->query('billcode');
        $externalReference = $request->query('refno');

        // Always re-fetch the booking to ensure you display the most up-to-date status
        // which should have been set by the handleCallback (server-to-server) call.
        $booking = Booking::where('external_reference', $externalReference)
                         ->orWhere('toyyibpay_bill_code', $billcode)
                         ->first();

        $message = '';
        $alertType = '';

        if ($booking) {
            // Display status based on the actual booking status from DB, which should be reliable.
            // This way, if callback updates before return, the user sees the true status.
            if ($booking->status === 'pending') { // Your custom 'pending' for successful payment
                $message = 'Your payment was successful and your booking is awaiting confirmation!';
                $alertType = 'success'; // Still display as success to the user, as payment went through
            } elseif ($booking->status === 'unpaid') { // Your custom 'unpaid' for failed payment
                $message = 'Your payment failed. Please try again or contact support.';
                $alertType = 'danger';
            } elseif ($booking->status === 'completed') { // If it somehow became 'completed' (e.g., from admin action)
                $message = 'Your booking is confirmed!';
                $alertType = 'success';
            } elseif ($booking->status === 'cancelled') { // If it was cancelled (e.g., failed payment, then moved to cancelled)
                $message = 'Your booking has been cancelled.';
                $alertType = 'danger';
            } else {
                $message = 'Booking status is currently ' . $booking->status . '. Please check your booking history.';
                $alertType = 'info';
            }
        } else {
            $message = 'Payment status unknown. We could not find your booking. Please contact support with Bill Code: ' . $billcode;
            $alertType = 'info';
        }

        return view('payment_confirmation', compact('statusId', 'billcode', 'booking', 'message', 'alertType'));
    }
}
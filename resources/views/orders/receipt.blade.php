<!-- filepath: g:\projects\restaurant-system\resources\views\orders\receipt.blade.php -->
<div class="modal fade" id="orderReceiptModal" tabindex="-1" aria-labelledby="orderReceiptModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
           <div class="modal-header bg-success text-white">
               <h5 class="modal-title" id="orderReceiptModalLabel">
                   <i class="fas fa-check-circle me-2"></i> Order Placed Successfully!
               </h5>
               <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body p-4">
               <div class="text-center mb-4">
                   <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                   <h3>Thank you for your order!</h3>
                   <p class="text-muted">Your order has been placed successfully.</p>
               </div>

               <div class="card border-0 bg-light mb-4">
                   <div class="card-body">
                       <div class="d-flex justify-content-between align-items-center mb-3">
                           <div>
                               <h4 class="mb-0">Order Receipt</h4>
                               <p class="text-muted mb-0" id="receipt-order-id">Order #<span id="receipt-order-number"></span></p>
                           </div>
                           <div class="text-end">
                               <p class="mb-0" id="receipt-date"></p>
                               <p class="text-muted mb-0" id="receipt-time"></p>
                           </div>
                       </div>

                       <hr>

                       <div class="table-responsive">
                           <table class="table">
                               <thead>
                                   <tr>
                                       <th>Item</th>
                                       <th class="text-center">Qty</th>
                                       <th class="text-end">Price</th>
                                       <th class="text-end">Total</th>
                                   </tr>
                               </thead>
                               <tbody id="receipt-items">
                                   <!-- Items will be inserted here by JavaScript -->
                               </tbody>
                               <tfoot>
                                   <tr>
                                       <td colspan="3" class="text-end">Subtotal:</td>
                                       <td class="text-end fw-bold" id="receipt-subtotal"></td>
                                   </tr>
                                   <tr>
                                       <td colspan="3" class="text-end text-muted">Tax (10%):</td>
                                       <td class="text-end text-muted" id="receipt-tax"></td>
                                   </tr>
                                   <tr>
                                       <td colspan="3" class="text-end fw-bold">Total:</td>
                                       <td class="text-end fw-bold" id="receipt-total"></td>
                                   </tr>
                               </tfoot>
                           </table>
                       </div>
                   </div>
               </div>

               <div class="text-center">
                   <p class="mb-0">Need help with your order?</p>
                   <p class="mb-0">Contact us at <a href="tel:+11234567890">(123) 456-7890</a> or <a href="mailto:support@restaurant.com">support@restaurant.com</a></p>
               </div>
           </div>
           <div class="modal-footer">
            @if (Auth::check() && Auth::user()->role == 'admin')
                
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i> View All Orders
            </a>
            @endif   
               <button type="button" class="btn btn-primary" onclick="window.print()">
                   <i class="fas fa-print me-2"></i> Print Receipt
               </button>
               <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                   <i class="fas fa-utensils me-2"></i> Continue Shopping
               </button>
           </div>
       </div>
   </div>
</div>
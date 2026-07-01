@if (hasUnpaidPackages() && hasUnpaidPackages()->isNotEmpty())
<style> 
   .unpaid .pay-button {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .row {
        margin-bottom: 1rem; /* Adds consistent spacing between rows */
    }

   .unpaid .d-flex {
        transition: all 0.3s ease, transform 0.3s ease;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0); /* Initial shadow */
    }

   .unpaid .d-flex:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Subtle hover shadow */
        transform: scale(1.02); /* Slightly enlarge the row */
        background-color: rgba(255, 255, 255, 0.9); /* Light background glow */
    }
</style>
 
    <div class="container mt-2 mb-0">
        <div class="row unpaid">
            @foreach (hasUnpaidPackages() as $package)
                <div class="col-12 mb-2">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 rounded shadow-sm" 
                         style="background-color: {{ $package->payment_status === 'pending' ? '#ffe5e7' : '#eaffea' }}; font-size: 0.85rem; border: 1px solid #ccc;">
                        <div>
                            <span class=""><strong>Waybill:</strong> {{ $package->waybill ?? 'NA' }}</span>
                        </div>
                        <span class=""><strong>KG:</strong> {{ number_format($package->kg, 2) }}</span>
                        <span><strong>{{__('fields.grand_total')}}:</strong> ${{ number_format($package->grand_total, 2) }}</span>
                        <button 
                            class="btn btn-success btn-sm pay-button" 
                            data-id="{{ $package->id }}" 
                            data-amount="{{ $package->grand_total }}">
                            {{__('fields.pay_now')}}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

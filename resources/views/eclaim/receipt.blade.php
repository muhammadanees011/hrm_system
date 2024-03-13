<div class="modal-body">
    <!-- Display Eclaim history -->
    <div>
        @if(!empty($eclaim->receipt))
            <img src="{{ asset('eclaimreceipts/'.$eclaim->receipt) }}" style="height: auto; max-height: 250px; width: auto; max-width: 500px;" alt="Receipt Image">
        @else
            <p>No Receipt available</p>
        @endif
    </div>
</div>

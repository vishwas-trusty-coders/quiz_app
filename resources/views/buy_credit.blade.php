<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buy Credits') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

    <!-- <div class="buy-cr">
        <div class="">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3>Your Credits Available: {{ $user->credits }}</h3>
                    <form action="{{ route('buy.credit.payment') }}" method="POST">
                        @csrf
                        <select name="credit_plan" id="credit_plan">
                            @foreach($creditPlans as $creditPlan)
                                <option 
                                    value="{{ $creditPlan['number_of_credit'] }}" 
                                    data-amount="{{ $creditPlan['price_of_credit'] }}" data-creditnum="{{ $creditPlan['number_of_credit'] }}">
                                    {{ $creditPlan['number_of_credit'] }} Credit - ${{ $creditPlan['price_of_credit'] }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="price-account">
                            <p>Your Account will be Charged: <span id="charge_amount"></span></p><br>
                            <p>Your New Credit Balance will be: <span id="new_balance">{{ $user->credits }}</span></p>
                        </div>
                        <input type="hidden" name="amount" id="amount" value="">
                        
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <div class="container text-center buy-cr">
        <h1>BUY CREDITS</h1>
        <p>Your credits available: {{ $user->credits }}</p>

        <div class="card p-4">
            <div class="dasbord-card-box">
            <div>
                    <img class="" src="../img/doctor-icon.png" alt="profile image">
            </div>
            <div>
                <span id="creditAmount">80</span>
                <h2>Credits</h2>
            </div>
            </div>

            <p>Buy now for $<span id="creditPrice">{{80 * $pricePerCredit}}</span></p>

            <form action="{{ route('buy.credit.payment') }}" method="POST">
                @csrf
                <div class="d-flex justify-content-center align-items-center mb-3 check-out-box">
                    <button type="button" id="decreaseCredit" class="btn btn-danger"><img class="mins-icon" src="../img/minis.png" alt="profile image"></button>
                    <input type="hidden" name="credit_plan" id="creditInput" value="80">
                    <input type="hidden" name="amount" id="amountInput" value="{{80 * $pricePerCredit}}">
                    <button type="button" id="increaseCredit" class="btn btn-success"><img class="mins-icon" src="../img/plus-icon.png" alt="profile image"></button>
                    <button type="submit" class="btn btn-primary dasbord-card-box-button">CHECK OUT</button>
                </div>
                
            </form>
        </div>
    </div>

    <!-- Modal -->
    @if(session('success') || session('error'))
        <div id="successModal" style="display: block; backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
            <div style="background: white; margin: 20% auto; padding: 20px; width: 400px; text-align: center; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <h3 class="text-xl font-bold">{{ session('success') ? session('success') : session('error') }}</h3>
                @if(session('success'))
                    <p class="mt-2">Your new credit balance is: <span id="modal_new_credit">{{ session('new_credits') }}</span></p>
                @else
                    <p class="mt-2">Please try again</p>
                @endif
                <button id="closeModal" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; margin-top: 10px;">Close</button>
            </div>
        </div>
    @endif
    <script>
        document.getElementById('closeModal')?.addEventListener('click', function () {
            if ({{ session('success') ? 'true' : 'false' }}) {
                window.location.href = '/dashboard'; // Redirect to the dashboard on success
            } else {
                document.getElementById('successModal').style.display = 'none'; // Close the modal on error
            }
        });
    </script>

    <script>
        // Event listener for the select dropdown
        // document.getElementById('credit_plan').addEventListener('change', function () {
        //     // Get the selected option's data-amount attribute
        //     const amount = this.options[this.selectedIndex].getAttribute('data-amount');
        //     const number_of_credit = this.options[this.selectedIndex].getAttribute('data-creditnum');
            
        //     // Update the charge amount
        //     document.getElementById('charge_amount').textContent = '$'+amount;
        //     document.getElementById('new_balance').textContent = number_of_credit;
        //     document.getElementById('amount').value = amount;
        // });

        // Initialize the charge amount with the default selected option
        // const initialAmount = document.getElementById('credit_plan').options[document.getElementById('credit_plan').selectedIndex].getAttribute('data-amount');
        // document.getElementById('charge_amount').textContent = '$'+initialAmount;
        // const initialCreditBalance = document.getElementById('credit_plan').options[document.getElementById('credit_plan').selectedIndex].getAttribute('data-creditnum');
        // document.getElementById('new_balance').textContent = initialCreditBalance;
        // document.getElementById('amount').value = initialAmount;



        document.addEventListener("DOMContentLoaded", function() {
            let creditAmount = document.getElementById("creditAmount");
            let creditPrice = document.getElementById("creditPrice");
            let creditInput = document.getElementById("creditInput");
            let amountInput = document.getElementById("amountInput");
            let increaseButton = document.getElementById("increaseCredit");
            let decreaseButton = document.getElementById("decreaseCredit");

            let pricePerCredit = @json($pricePerCredit); // $1 per credit
            let minCredits = 5;
            let maxCredits = 1000;

            function updateDisplay(value) {
                creditAmount.innerText = value;
                creditPrice.innerText = (value * pricePerCredit).toFixed(2);
                creditInput.value = value;
                amountInput.value = (value * pricePerCredit).toFixed(2);
            }

            increaseButton.addEventListener("click", function() {
                let currentCredits = parseInt(creditInput.value);
                if (currentCredits < maxCredits) {
                    updateDisplay(currentCredits + 5);
                }
            });

            decreaseButton.addEventListener("click", function() {
                let currentCredits = parseInt(creditInput.value);
                if (currentCredits > minCredits) {
                    updateDisplay(currentCredits - 5);
                }
            });
        });
    </script>
</x-app-layout>

<div>

    <style>
        .StripeElement {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>

    <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg">

        <h1 class="my-6 text-xl font-bold uppercase">Pay for your tickets - {{ Number::currency($transaction->cost/100, in: 'GBP') }}</h1>
        <form id="payment-form">
            <div id="payment-element">
            </div>
            
            <div class="flex items-center justify-between mt-5">
                <div class="w-full mb-6">
                    <a href="{{ url()->previous() }}"><span class="px-4 py-2 bg-gray-100 rounded hover:bg-black hover:text-white">BACK</span></a>
                </div>                        
                <div class="mb-6">
                    <button type="submit" id="submit" class="w-full px-4 py-2 text-white bg-teal-800 rounded hover:bg-green-600">SUBMIT</button>
                </div>
            </div>

            <div id="error-message">
            </div>
        </form>
    </div>

{{-- @section('scripts')  --}}
<script src="https://js.stripe.com/v3/"></script>
<script>   
const stripe = Stripe('{{ config('stripe.key') }}');
const options = {
clientSecret: '{{ $client_secret }}',

// Fully customizable with appearance API.
//appearance: {/*...*/},
};

const elements = stripe.elements(options);

const paymentElement = elements.create('payment');
paymentElement.mount('#payment-element');

const form = document.getElementById('payment-form');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const {error} = await stripe.confirmPayment({
    //`Elements` instance that was used to create the Payment Element
    elements,
    confirmParams: {
        return_url: '{{  route('confirmpayment') }}',
    },
});

if (error) {
// This point will only be reached if there is an immediate error when
// confirming the payment. Show error to your customer (for example, payment
// details incomplete)
const messageContainer = document.querySelector('#error-message');
messageContainer.textContent = error.message;
} else {
// Your customer will be redirected to your `return_url`. For some payment
// methods like iDEAL, your customer will be redirected to an intermediate
// site first to authorize the payment, then redirected to the `return_url`.
}
});


</script>

</div>

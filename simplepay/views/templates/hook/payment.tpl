<div class="row">
	<div class="col-xs-12">
        <p class="payment_module">
		<a class="simplepay_button js_button_checkout" href="#" rel="nofollow">
			<img src="http://assets.simplepay.ng/buttons/pay_medium_dark.png" alt="SimplePay Pay Button" />
		</a>
	</p>
    </div>
</div>

<script src="https://checkout.simplepay.ng/simplepay.js"></script>
<script type="text/javascript">
function formatAmount(amount) {
	var strAmount = amount.toString().split(".");
	var decimalPlaces = strAmount[1] === undefined ? 0: strAmount[1].length;
	var formattedAmount = strAmount[0];
	
	if (decimalPlaces === 0) {
		formattedAmount += '00';
	
	} else if (decimalPlaces === 1) {
		formattedAmount += strAmount[1] + '0';
	
	} else if (decimalPlaces === 2) {
		formattedAmount += strAmount[1];
	}
	
	return formattedAmount;
}

var amount = formatAmount('{$amount}');

function processPayment (token) {
	var form = $('<form />', { action: '{$module_dir}validate.php', method: 'POST' });
	form.append(
		$('<input />', { name: 'token', type: 'hidden', value: token }),
		$('<input />', { name: 'amount', type: 'hidden', value: amount }),
		$('<input />', { name: 'ps_amount', type: 'hidden', value: '{$amount}' })
	);
	form.submit();
}

var handler = SimplePay.configure({
	platform: 'PrestaShop',
	token: processPayment,
	key: '{$public_key}',
	image: '{$image}'
});

$('.js_button_checkout').on('click', function (e) {
    	e.preventDefault();
	handler.open(SimplePay.CHECKOUT,{
		email: '{$email}',
		phone: '{$phone}',
		description: '{$description}',
		address: '{$address}',
		postal_code: '{$postal_code}',
		city: '{$city}',
		country: '{$country}',
		amount: amount,
		currency: '{$currency->iso_code}'
    	});
});

</script>

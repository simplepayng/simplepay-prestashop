<div id="btn-checkout" class="payment">
	<span>Pay now with</span>
	<img src="{$module_dir}views/templates/img/logo-payment.png" alt="SimplePay Pay Button" />
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

function processPayment (token, reference) {
	var form = $('<form />', { action: '{$module_dir}validate.php', method: 'POST' });
	form.append(
		$('<input />', { name: 'token', type: 'hidden', value: token }),
		$('<input />', { name: 'reference', type: 'hidden', value: reference }),
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

$('#btn-checkout').on('click', function (e) {
    handler.open(SimplePay.CHECKOUT,
    {
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
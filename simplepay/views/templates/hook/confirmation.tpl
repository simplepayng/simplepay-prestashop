{if $status == 'ok'}
	<p>
		Your order is complete.
	</p>
	<p>
		Your order will be sent as soon as possible.
	</p>
	<p>
		<b>For any questions or for further information, please contact our <a href="mailto:support@simplepay.ng?Subject=Gateway">customer support</a>.</b>
	</p>
{else}
	<p class="warning">
		We noticed a problem with your order. If you think this is an error, you can contact our <a href="mailto:support@simplepay.ng?Subject=Gateway">customer support</a>
	</p>
{/if}

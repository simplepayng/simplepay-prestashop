<div class="container">
	<div class="row">
		<div class="col-lg-4">
			<img src="{$module_dir}views/templates/img/logo.png" alt="SimplePay" />
		</div>
	</div>

	<form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
		<div class="row margin-top-md">
			<div class="col-lg-4">
				<label>Test Mode</label>
				<select class="form-control" name="simplepay_test_mode">
					<option value="1" {if ((int)$simplepay_test_mode == 1)}selected{/if}>Enable</option>
					<option value="0" {if ((int)$simplepay_test_mode == 0)}selected{/if}>Disable</option>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<h2>Live API Keys</h2>
			</div>
		</div>

		<div class="row margin-top-sm">
			<div class="col-lg-4">	
				<label>Private API Key</label>
				<input type="text" class="form-control" name="simplepay_live_private_key" value="{$simplepay_live_private_key}" />
			</div>
		</div>
		
		<div class="row margin-top-sm">
			<div class="col-lg-4">
				<label>Public API Key</label>
				<input type="text" class="form-control" name="simplepay_live_public_key" value="{$simplepay_live_public_key}" />
			</div>
		</div>
		
		<div class="row margin-top-md">
			<div class="col-lg-4">
				<h2>Test API Keys</h2>
			</div>
		</div>
		
		<div class="row margin-top-sm">
			<div class="col-lg-4">
				<label>Private API Key</label>
				<input type="text" class="form-control" name="simplepay_test_private_key" value="{$simplepay_test_private_key}" />
			</div>
		</div>
		
		<div class="row margin-top-sm">
			<div class="col-lg-4">
				<label>Public API Key</label>
				<input type="text" class="form-control" name="simplepay_test_public_key" value="{$simplepay_test_public_key}" />
			</div>
		</div>

		<div class="row margin-top-md">
			<div class="col-lg-4">
				<h2>SimplePay Checkout Dialog</h2>
			</div>
		</div>

		<div class="row margin-top-sm">
			<div class="col-lg-4">
				<label>Payment Description</label>
				<input type="text" class="form-control" name="simplepay_payment_description" value="{$simplepay_payment_description}" />
			</div>
		</div>

		<div class="row margin-top-sm">
			<div class="col-lg-4">
				<label>Checkout image</label>
				<input type="text" class="form-control" name="simplepay_image" value="{$simplepay_image}" />
			</div>
		</div>

		<div class="row margin-top-md">
			<div class="col-lg-4">
				<input type="submit" name="submitModule" value="Save" class="btn btn-simplepay pull-right" />
			</div>
		</div>
	</form>
</div>

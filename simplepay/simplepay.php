<?php   
if (!defined('_PS_VERSION_'))
  exit;

class SimplePay extends PaymentModule
{
	public function __construct()
	{
		$this->name = 'simplepay';
		$this->tab = 'payments_gateways';
		$this->version = '1.0.0';
		$this->author = 'SimplePay';
		$this->need_instance = 1;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->bootstrap = true;
		
		parent::__construct();

		$this->displayName = $this->l('SimplePay');
		$this->description = $this->l('Online and Mobile Payment. Secure. Simple.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall SimplePay?');

		/* Check if cURL is enabled */
		if (!is_callable('curl_exec')) 
		{
			$this->warning = $this->l('cURL extension must be enabled on your server to use this module.');
		}
	}
	
	public function install()
	{	
		return parent::install() &&
			$this->registerHook('backOfficeHeader') &&
  			$this->registerHook('footer') &&
  			$this->registerHook('payment') &&
  			$this->registerHook('paymentReturn') &&
			Configuration::updateValue('SIMPLEPAY_TEST_MODE', 1);

	}

	public function uninstall()
	{
		Configuration::deleteByName('SIMPLEPAY_TEST_MODE');

		return parent::uninstall();
	}

	public function hookFooter()
	{
		$this->context->controller->addCSS($this->_path.'views/templates/css/simplepay.css');	
	}

	public function hookBackOfficeHeader()
	{
		$this->context->controller->addCSS($this->_path.'views/templates/css/simplepay.css');	
	}

	public function getContent()
	{
		$html = '';

		if (Tools::isSubmit('submitModule'))
		{
			$simplepay_mode = (int)Tools::getvalue('simplepay_test_mode');
			if ($simplepay_mode)
			{
				Configuration::updateValue('SIMPLEPAY_TEST_MODE', 1);
			}
			else
			{
				Configuration::updateValue('SIMPLEPAY_TEST_MODE', 0);
			}

			Configuration::updateValue('SIMPLEPAY_LIVE_PRIVATE_KEY', Tools::getvalue('simplepay_live_private_key'));
			Configuration::updateValue('SIMPLEPAY_LIVE_PUBLIC_KEY', Tools::getvalue('simplepay_live_public_key'));
			Configuration::updateValue('SIMPLEPAY_TEST_PRIVATE_KEY', Tools::getvalue('simplepay_test_private_key'));
			Configuration::updateValue('SIMPLEPAY_TEST_PUBLIC_KEY', Tools::getvalue('simplepay_test_public_key'));
			Configuration::updateValue('SIMPLEPAY_PAYMENT_DESCRIPTION', Tools::getvalue('simplepay_payment_description'));
			Configuration::updateValue('SIMPLEPAY_IMAGE', Tools::getvalue('simplepay_image'));
			
			$html .= $this->displayConfirmation($this->l('SimplePay settings updated'));
		}

		$this->context->smarty->assign(array(
			'module_dir' => $this->_path,
			'simplepay_test_mode' => Configuration::get('SIMPLEPAY_TEST_MODE'),
			'simplepay_live_private_key' => Configuration::get('SIMPLEPAY_LIVE_PRIVATE_KEY'),
			'simplepay_live_public_key' => Configuration::get('SIMPLEPAY_LIVE_PUBLIC_KEY'),
			'simplepay_test_private_key' => Configuration::get('SIMPLEPAY_TEST_PRIVATE_KEY'),
			'simplepay_test_public_key' => Configuration::get('SIMPLEPAY_TEST_PUBLIC_KEY'),
			'simplepay_payment_description' => Configuration::get('SIMPLEPAY_PAYMENT_DESCRIPTION'),
			'simplepay_image' => Configuration::get('SIMPLEPAY_IMAGE')
		));

		return $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/configuration.tpl');
	}

	public function hookPayment($params)
	{	
		$cart = $params['cart'];
		$customer = new Customer((int)$cart->id_customer);
		$deliveryAddress = new Address((int)$cart->id_address_delivery);
		$country = new Country((int)$deliveryAddress->id_country);
		$currency = Currency::getCurrencyInstance($this->context->cookie->id_currency);
		
		if (!Validate::isLoadedObject($currency))
		{
			return false;
		}
		
		$phone = Tools::safeOutput($deliveryAddress->phone_mobile);
		if (empty($phone)) 
		{
			$phone = Tools::safeOutput($deliveryAddress->phone);
		}

		$public_key = Configuration::get('SIMPLEPAY_LIVE_PUBLIC_KEY');
		if ((int)Configuration::get('SIMPLEPAY_TEST_MODE')) 
		{
			$public_key = Configuration::get('SIMPLEPAY_TEST_PUBLIC_KEY');
		}
		
		$this->context->smarty->assign('email', $customer->email);
		$this->context->smarty->assign('phone', $phone);
		$this->context->smarty->assign('description', Configuration::get('SIMPLEPAY_PAYMENT_DESCRIPTION') .' #'.$cart->id);
		$this->context->smarty->assign('address', Tools::safeOutput($deliveryAddress->address1.' '.$deliveryAddress->address2));
		$this->context->smarty->assign('postal_code', Tools::safeOutput($deliveryAddress->postcode));
		$this->context->smarty->assign('city', Tools::safeOutput($deliveryAddress->city));
		$this->context->smarty->assign('country', $country->iso_code);
		$this->context->smarty->assign('amount', $cart->getOrderTotal());
		$this->context->smarty->assign('currency', $currency);
		$this->context->smarty->assign('public_key', $public_key);
		
		$this->context->smarty->assign('module_dir', $this->_path);
		$this->context->smarty->assign('cart_id', $cart->id);
		$this->context->smarty->assign('cart_id', $cart->id);
		$this->context->smarty->assign('image', Configuration::get('SIMPLEPAY_IMAGE'));
		
		return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
	}
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
		{
			return;
		}

		$order = $params['objOrder'];
		$state = $order->getCurrentState();
		if ($state == _PS_OS_ERROR_)
		{
			$status = 'callback';
			$msg = 'SimplePay: Confirmation failed';
			Logger::addLog($msg, 2, 0, 'Order', $order->id);
		}
		else
		{
			$status = 'ok';
		}
		$this->smarty->assign('status', $status);
		
		return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
	}
}

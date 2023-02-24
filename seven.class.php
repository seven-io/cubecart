<?php

class seven {
    protected $_language_strings = [];
    protected $_module_config = [];
    protected $_name;

    public function setNavItem(array &$navItems): void {
        $navItems[$this->_name] = '?_g=plugin&amp;name=' . $this->_name;
    }

    public function getName(): string {
        return $this->_name;
    }

    public function getModuleConfig(): array {
        return $this->_module_config;
    }

    public function __construct() {
        $this->_name = get_class($this);
        $this->_module_config = $GLOBALS['config']->get($this->_name);
        $GLOBALS['language']->loadDefinitions($this->_name, CC_ROOT_DIR . '/modules/plugins/'
            . $this->_name . '/language', 'module.definitions.xml');
        $this->_language_strings = $GLOBALS['language']->getStrings($this->_name);
    }

    public function renderCustomerListScript(): void {
        $title = $GLOBALS['language']->seven['sms_send'];

        echo <<<HTML
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Array.from(document.querySelectorAll(`#customer-list > table > tbody tr`)).forEach(row => {
                    const url = new URL(row.querySelector(':nth-child(4) > a').getAttribute('href'))
                    const customerId = url.searchParams.get('customer_id')

                    row.querySelector(':nth-child(8)').insertAdjacentHTML('beforeend', `<a
href='?_g=plugin&name=$this->_name&customer_id=\${customerId}'
title='$title'>
<i class='fa fa-envelope'</i>
</a>`)
                })
            })
        </script>
HTML;
    }

    protected function getDefaultAddress(int $id, bool $removeEmpty): array {
        $address = [];

        if ($id > 0) {
            $address = $GLOBALS['db']
                ->select('CubeCart_addressbook', [
                    'company_name',
                    'country',
                    'description',
                    'first_name',
                    'last_name',
                    'line1',
                    'line2',
                    'postcode',
                    'state',
                    'title',
                    'town',
                ], [
                    'customer_id' => $id,
                    'default' => 1,
                ], false, 1);

            if (is_array($address)) {
                $address = reset($address);

                if ($removeEmpty) {
                    foreach ($address as $k => $v) {
                        if (empty($v)) {
                            unset($address[$k]);
                            continue;
                        }

                        switch ($k) {
                            case 'country':
                                $address[$k] = getCountryFormat($v);
                                break;
                            case 'state':
                                $address[$k] = getStateFormat($v);
                                break;
                        }
                    }
                }
            }
        }

        return $address;
    }

    public function getCustomer(int $id, bool $removeEmpty = true): array {
        $customer = [];

        if ($id > 0) {
            $customer = $GLOBALS['db']
                ->select('CubeCart_customer', [
                    'email',
                    'first_name',
                    'ip_address',
                    'last_name',
                    'mobile',
                    'notes',
                    'order_count',
                    'phone',
                    'title',
                ], ['customer_id' => $id], false, 1);

            if (is_array($customer)) {
                $customer = reset($customer);

                if ($removeEmpty)
                    foreach ($customer as $k => $v)
                        if (empty($v)) unset($customer[$k]);

                foreach ($this->getDefaultAddress($id, $removeEmpty) as $k => $v)
                    $customer['default_address_' . $k] = $v;
            }
        }

        return $customer;
    }

    protected function callAPI(string $endpoint, array $params, string $apiKey = null): stdClass {
        if (!$apiKey) $apiKey = $this->_module_config['token'];

        $ch = curl_init('https://gateway.seven.io/api/' . $endpoint);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Api-Key: ' . $apiKey,
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    public function display() {
        if (isset($_POST['sms'])) {
            $params = $_POST;
            unset($params['sms'], $params['sms'], $params['token']);

            $result = $this->callAPI('sms', $params);

            $GLOBALS['gui']->setNotify(json_encode($result, JSON_PRETTY_PRINT));
        }

        $GLOBALS['gui']->addBreadcrumb($this->_name, '?_g=plugin&name=' . $this->_name);
        $GLOBALS['gui']->changeTemplateDir('modules/plugins/' . $this->_name . '/skin/admin');

        $GLOBALS['smarty']->assign('MODULE_LANG', $this->_language_strings);

        $GLOBALS['main']->addTabControl('SMS', 'sms');
        $GLOBALS['main']->addTabControl(
            'Plugin Configuration', '', '?_g=plugins&type=plugins&module=' . $this->_name);

        $html_out = $GLOBALS['smarty']->fetch('sms.tpl');
        $GLOBALS['gui']->changeTemplateDir();

        return $html_out;
    }
}

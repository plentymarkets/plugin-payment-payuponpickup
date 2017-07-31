# plentymarkets Payment – Pay upon pickup

With this plugin, you integrate the payment method **Pay upon pickup** into your online store.

## Setting up a payment method

In order to make this payment method available in your online store, you have to carry out the settings in the plentymarkets back end.

##### Setting up a payment method:

1. Go to **Settings&nbsp;» Orders&nbsp;» Pay upon pickup**. 
2. Select a Client (store). 
3. Carry out the settings. Pay attention to the information given in table 1. 
4. **Save** the settings.

<table>
<caption>Table 1: Carrying out settings for the payment method</caption>
	<thead>
		<th>
			Setting
		</th>
		<th>
			Explanation
		</th>
	</thead>
	<tbody>
        <tr>
			<td>
				<b>Language</b>
			</td>
			<td>
				Select a language. Other settings, such as name, info page, etc., will be saved depending on the selected language.
			</td>
		</tr>
        <tr>
			<td>
				<b>Name</b>
			</td>
			<td>
				The name of the payment method that will be displayed in the overview of payment methods in the checkout.
			</td>
		</tr>
		<!--tr>
			<td>
				<b>Infoseite</b>
			</td>
			<td>
				Als <a href="https://www.plentymarkets.eu/handbuch/payment/bankdaten-verwalten/#2-2"><strong>Information zur Zahlungsart</strong></a> eine Kategorieseite vom Typ <strong>Content</strong> anlegen oder die URL einer Webseite eingeben.
			</td>
		</tr-->
		<tr>
			<td>
				<b>Logo</b>
			</td>
			<td>
			Select either no logo, <strong>Default logo</strong> or <strong>Logo URL</strong>.<br /><strong>Default logo:</strong> Display the default logo of the payment method in the checkout.<br /><strong>Logo URL:</strong> Enter a HTTPS URL that leads to the logo. Valid file formats are .gif, .jpg or .png. The image may not exceed a maximum size of 190 pixels in width and 60 pixels in height.
			</td>
		</tr>
        <tr>
			<td>
				<b>Surcharge (domestic)</b>
			</td>
			<td>
Enter a flat rate. The value that is entered will be taken into consideration for those orders that correspond to the system country. Once the customer has selected the payment method, these costs will be added to the particular order in the order process. The amount will be added to the total in the order and will not be displayed individually.
		</tr>
		<tr>
			<td>
				<b>Surcharge (foreign countries)</b>
			</td>
			<td>
Enter a flat rate. The value that is entered will be taken into consideration for those orders that correspond to a foreign country. Once the customer has selected the payment method, these costs will be added to the particular order in the order process. The amount will be added to the total in the order and will not be displayed individually.
		</tr>
		<tr>
			<td>
				<b>Countries of delivery</b>
			</td>
			<td>
				This payment method is active only for the countries in this list.
			</td>
		</tr>
	</tbody>
</table>

## Displaying the logo of the payment method on the homepage

The template plugin **Ceres** allows you to display the logo of your payment method on the homepage by using template containers. Proceed as described below to link the logo of the payment method.

##### Linking the logo with a template container:

1. Go to **Plugins » Content**. 
3. Go to the **Pay upon pickup icon** area. 
4. Activate the container **Homepage: Payment method container**. 
5. **Save** the settings.<br />→ The logo of the payment method will be displayed on the homepage of the online store.

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE. – find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-payment-payuponpickup/blob/master/LICENSE.md).

# Release Notes for PayUponPickup

## 2.0.3

### Fixed
- The link to an internal info page is now displayed correctly again.

## 2.0.2

### Changed
- Added Icon for the backend

### Fixed
- Settings were not correctly loaded in the assistant when settings for several clients were entered.

## 2.0.1

### Changed
- Added methods for the backend visibility and backend name
- Some texts have been adapted

## 2.0.0

### Note 
- The settings for the pay upon pickup plugin have been transferred to an assistant in the **Setup » Assistants » Payment** menu.

### Changed
- The description and the name of the payment method is now also maintained via **CMS » Multilingualism**.

## 1.2.2

### Changed
- The user guide has been updated.

## 1.2.1

### Changed
- The settings for shipping countries have been optimized.

## 1.2.0

### Fixed
- A possible problem with deploying the plugin has been fixed.

## 1.1.9

### Changed
- Update support information

## 1.1.8

### Added
- Further languages have been added for the plugin UI.

### Fixed
- Problems with saving multi-client settings in the Plugin-UI have been fixed.

## 1.1.6

### Changed
- Expanded user guide.

## 1.1.5

### Changed
- New menu path **System&nbsp;» Orders&nbsp;» Payment » Plugins » Pay upon pickup**.

## 1.1.4

### Fixed
- The `$MethodOfPaymentName` variable will now be displayed in the respective language in email templates.

## 1.1.3

### Changed
- The user guide has been updated.

## 1.1.2

### Changed
- The entry point in the system tree is now **System » Orders » Payment » PayUponPickup » Pay upon pickup**.

## 1.1.1

### Changed
- The user guide has been updated.

## 1.1.0

### Added
- Settings for **Info page** were added.
- Settings for **Description** were added.

### Changed
- Removed surcharges for the payment method.

## 1.0.5

### Added
- A method was added to determine if a customer can switch from this payment method to another payment method.
- A method was added to determine if a customer can switch to this payment method from another payment method.

### Known issues
- The settings for **Surcharges** currently have no function when calculating prices in the checkout.

## 1.0.4

### Fixed
- Use the correct payment method id.

## 1.0.3

### Fixed
- The settings will be loaded again.

## 1.0.2

### Fixed
- The CSS of the **Settings** in the back end has been fixed. The settings will now cover the entire width.

### Known issues
- At the moment, the **Surcharges** settings have no functionality in the price calculation of the checkout page

## 1.0.1

### Changed
- For this payment method, the payment method ID from the system will be used

## 1.0.0

### Features
- Payment method **Pay upon pickup** for plentymarkets online stores

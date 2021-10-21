# Mage2 Module UIS FlashDeals

    ``uis/module-flashdeals``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Flash Deals by UIS

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/UIS`
 - Enable the module by running `php bin/magento module:enable UIS_FlashDeals`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require uis/module-flashdeals`
 - enable the module by running `php bin/magento module:enable UIS_FlashDeals`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - MAX Products per subscription (options/general/max_products_per_subscription)

 - Max vertical size of image (px) (options/general/max_image_vertical_size)

 - Max horizontal size of image (px) (options/general/max_image_vertical_size)


## Specifications

 - Cache
	- uis_flashdeals - uis_flashdeals_cache_tag > UIS\FlashDeals\Model\Cache\Uis_flashdeals

 - Controller
	- adminhtml > uisflashdeals/index/index

 - Helper
	- UIS\FlashDeals\Helper\Data


## Attributes

## Database Update
 - Use commands below:

 1. bin/magento setup:db-declaration:generate-whitelist
 2. bin/magento setup:upgrade --keep-generated 
 
 Recompile DI and static frontend if needed: 
 3. bin/magento setup:di:compile
 4. sh recompile_static_frontend.sh 




# Contao Banner Plus Bundle 

This bundle brings enhancements to [BugBusters contao-banner-bundle](https://github.com/BugBuster1701/contao-banner-bundle).

## Features
- add better page filter, with page depth inheritance and include/except pattern to banners
- [Slick support](https://github.com/heimrichhannot/contao-slick-bundle) - display adds within your slick newslist module
- fireplace ads
- html banner support

## Setup

### Install

1. Install via composer or contao manager:
    
    ```
    composer require heimrichhannot/contao-banner-plus-bundle
    ```
    
1. Update your Database

 
### Usages

#### Page filter

Setup the page filter in your banner settings. Show or hide banners on configured pages including optional page inheritance.

#### Slick support

Select a banner cateogy in your slick newslist module and set configuration to display banners within the slideshow. Two types of banner-inclusion are possible.

#### Fireplace add

Setup fireplace ads. Add additional images to the fireplace image fields in your banner config and use mod_banner_list_fireplace_ad template within module config.

#### HTML Banner

Setup Html ads. Select HTML file from filesystem. For better usability and file management use new directory for every new add. Css and JavaScript files can be added to the directory too and linked inside the html file.
HTML files will be loaded via iframe, so it is possible to have multiple links inside the banner. Links inside banner cannot be tracked. By selecting target url inside banner configuration, this link will be placed over the iframe. Clicks on the target link will be tracked as usual. 




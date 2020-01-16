# Contao Banner Plus Bundle 

This bundle brings enhancements to [BugBusters contao-banner-bundle](https://github.com/BugBuster1701/contao-banner-bundle).

## Features
- add better page filter, with page depth inheritance and include/except pattern to banners
- [Slick support](https://github.com/heimrichhannot/contao-slick-bundle) - display adds within your slick newslist module
- fireplace ads
- media query support to track banner views only if visible by user window dimension 

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

#### Media queries 

Add media queries to banner categories, to don't have non-visible ads tracked.




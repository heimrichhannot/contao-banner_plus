# Change Log
All notable changes to this project will be documented in this file.

## [3.0.0] - 2024-08-13
- Changed: dropped media query support
- Changed: update bundle structure
- Changed: rename bundle class
- Fixed: remove unnecessary deps

## [2.2.0] - 2024-08-13
- Added: encore contracts support
- Added: BeforeRenderBannerEvent
- Changed: require contao 4.13
- Changed: require at least php 8.1
- Changed: iframe resizing now uses own js (stopped using and bundling iframe resizer!)
- Changed: use custom route for internal html banners (fix issues with local paths)
- Changed: some code modernization

## [2.1.1] - 2023-02-08
- Fixed: slick bundle compatibility

## [2.1.0] - 2022-08-24
- Added: support for newer banner version and drop support for older ones ([#14](https://github.com/heimrichhannot/contao-banner_plus/pull/14))
- Changed: minimum contao version is now 4.9
- Changed: minimum php version is now 7.2
- Changed: code enhancements and refactoring

## [2.0.0] - 2022-08-24
Same as beta 6

## [2.0.0-beta6] - 2021-09-28
- fixed banner_start and banner_end to be displayed correctly in backend

## [2.0.0-beta5] - 2021-07-27
- fixed backend js bundle name

## [2.0.0-beta4] - 2021-07-27
- added new banner type HTML(#1)
- added encore configuration
- added backend js

## [2.0.0-beta3] - 2020-02-18
- fixed another dca install error

## [2.0.0-beta2] - 2020-02-17
- fixed an dca install error

## [2.0.0-beta1] - 2020-01-16
- refactored to bundle structure
- contao-banner-bundle compatiblity (instead of banner module)
# TYPO3 Forum

## Migration from mm_forum

Migration is only possible from `mm_forum` 2.0. There is no backwards compatibility to `mm_forum` 1.x!

* Install `typo3_forum`
* Execute the Scheduler Task `Database Migration`
* Open your root TypoScript Template Record and include `typo3_forum`'s static TypoScript
* Change your TypoScript configuration (e.g. in your template extension or Typoscript records) from `plugin.tx_mmforum` to `plugin.tx_typo3forum`
* Uninstall `mm_forum`
* Clean up the database using the Install Tool "compare" functionality.
* If you have custom fluid templates for the forum using the forum's ViewHelpers you have to adjust the namespace declaration accordingly. E.g. `{namespace t3f=Mittwald\Typo3Forum\ViewHelpers}`

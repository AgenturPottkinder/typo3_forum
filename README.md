# TYPO3 Forum

## Migration from mm_forum

Migration is only possible from `mm_forum` 2.0. There is no backwards compatibility to `mm_forum` 1.x!

* Install `typo3_forum`
* Execute the Scheduler Task `Database Migration`
* Open your root TypoScript Template Record and include `typo3_forum`'s static TypoScript
* Uninstall `mm_forum`
* Clean up the database using the Install Tool "compare" functionality.
# TYPO3 Forum

[![Crowdin](https://badges.crowdin.net/typo3-extension-typo3forum/localized.svg)](https://crowdin.com/project/typo3-extension-typo3forum)


The typo3_forum is a frontend plugin that adds a discussion board to your TYPO3-based website. The extension was originally written to be used for discussing questions about TYPO3 in the TYPO3 portal TYPO3.net by Mittwald CM Service.

The extension currently consists of 9 different plugins. These plugins provide the following features:

* "Dashboard": Displays the logged in user's personal data, subscriptions and posts. It is meant to be extended as a hub for all externally added user management tools (profile editing, direct messaging, etc.).
* "Forum": Offers all the basic functionalities of a fully-featured discussion board, including subforums, topics and posts contained in these threads.
* "Forum Statistics Box": A small box that displays numeric statistics about the forum (member count, post count, topic count). Can be extended by third parties.
* "Moderation: Manage reports": List of all reported posts, to be managed by moderators.
* "Post List": Gives a list of all posts or only the most recent posts, including a preview of the rendered post text.
* "Tag List": Tags offer the possiblity to categorize topics and to search topics by tags. They can be freely created by users.
* "Topic List": Gives different overviews over the topics users create: latest topics, popular topics, latest questions as well as a list of unsolved questions.
* "User List": Different kinds of user lists: all users, most helpful users, currently online users.
* "User profile": Displays user profiles, including all specified userfields, rank and their latest activity.

## Migration from mm_forum

Migration is only possible from `mm_forum` 1.0. until typo3_forum version 1.1.0

You will find more details in the Documenation: http://typo3-forum.readthedocs.io/en/master/UsersManual/MigrationfromMm_forum/Index.html

# Special Thanks

Special Thanks to Philipp Stranghöner and the whole team of Mittwald for providing support.

# Contact Information

This project was originally built by Mittwald and is now maintaned by us, the Pottkinder in Bochum, Germany.

You can read more here: https://www.mittwald.de/blog/cms/typo3-cms/maintainer-fuer-typo3-forum-pottkinder

If you need to contact us via mail, please use support@agentur-pottkinder.de

# TODOs

* Move AJAX from dispatcher to middleware and refactor
* UnitTesting
* Notifications (for subscriptions and for third parties to hook into the dashboard with something like a private messaging system)
* More moderation options
    * Disabling users, including temporarily
    * User reports
* Replace all PageBrowser-Partials with the generic Control/PageBrowser one, or replace it with a ViewHelper that renders pagination links based on innerChildClosure.
* Setup code analysis and CI/CD properly, currently it all seems rather dead

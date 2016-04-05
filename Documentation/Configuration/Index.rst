.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration
=======================

Target group: **Developers**


.. _configuration-typoscript:

Template
--------
Just override ``plugin.tx_typo3_forum.view`` to use you own template. Default Template is found in ``EXT:typo3_forum/Resources/Private/``

TypoScript Reference
--------------------

* Configuration must be set in TypoScript
* Include Static Typoscript Template in BackendModule "Templates"
::

	plugin.tx_typo3forum {
		persistence {
			# Pid for your main storage sysfolder
			storagePid = 12
		}
		settings {
			debug = 0
			cutUsernameOnChar = 9
			cutBreadcrumbOnChar = 40
			popularTopicTimeDiff = 604800
			useSqlStatementsOnCriticalFunctions = 1
			
			pids {
				# Insert pid of page where the fourm plugin is on
				Forum = pid
				# Insert pid of page where the User profile plugin is on
				UserShow = pid
				# Insert pid of page where the Users List plugin is on
				UserList = pid
				# Insert pid of page where the User profile plugin is on
				UserEdit = pid
				# Insert pid of page where the Dashboard plugin is on
				Dashboard = pid
			}
			
			mailing {
				# Insert name of the sender
				sender.name = Name of Sender
				# Insert Mail address of the sender
				sender.address = Mail of Sender
			}
			
		}
	}
	
.. toctree::
   :maxdepth: 5
   :titlesonly:
   :glob:

   Installation/Index
   RegisterandLogin/Index

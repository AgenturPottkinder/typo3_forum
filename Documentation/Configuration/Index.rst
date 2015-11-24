.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Target group: **Developers**


.. _configuration-typoscript:

Installation
---------------------
* The first step to install the typo3_forum extension is – of course – to import it into your TYPO3 environment using the extension manager. To do so, go to the extension manager and select “Get extensions” in the drop-down menu on the top of the page.

Then search for the extension key typo3_forum. After that click on Button “Import and Install”:

.. image:: ../Images/get-extension.png

In order to use typo3_forum you need to tell it where to find and store its data. For this, you should create a new page where your forum data is to be stored. A SysFolder would probably be the best solution for this. 

.. image:: ../Images/sys-folder.png

Follow these steps in order to configure typo3_forum:

Create a new Record inside the Forum data Folder you just created. typo3_forum gives you a couple new options. First you’re gonna need a ‘Forum’, you can name it however you want. 

.. image:: ../Images/functions.png

For the first installation you need to leave the other fields blank. Now save and close the Forum.

.. image:: ../Images/create-forum.png

All you have to do to display your Forum now is create a new record on a page that contains the ‘General Plugin’ Element.

.. image:: ../Images/add.png

.. image:: ../Images/general-plugin.png

Within the settings for that Element you need to configure the Plugin. You have to set typo3_forum as Selected Plugin and confirm it by pressing the ‘Save and Close’ Button

.. image:: ../Images/general-plugin.png
.. image:: ../Images/select-plugin-first.png

Now a couple more Options should appear, under ‘DEF: Plugin behavior’ please select ‘Forum’.

.. image:: ../Images/select-plugin.png

You still have to tell the Plugin where the Forum data is stored, to do so select the ‘Behaviour’ tab and select the folder that contains your data as ‘Record Storage Page’ and then press Save.

If you’ve followed all the instructions correctly you should end up with a result like that: 

.. image:: ../Images/frontend.png

With the Plugin Options you can select with User can see with Forum or post new topics. 


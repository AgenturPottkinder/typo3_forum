<?php
namespace Mittwald\Typo3Forum\Service\Notification;
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */


/**
 *
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Service
 * @version    $Id: NotificationService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class NotificationService extends \Mittwald\Typo3Forum\Service\AbstractService
	implements \Mittwald\Typo3Forum\Service\Notification\NotificationServiceInterface
{


	/*
	 * ATTRIBUTES
	 */

	/**
	 * @var \Mittwald\Typo3Forum\Service\Mailing\HTMLMailingService
	 */
	protected $htmlMailingService;


	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
	 */
	protected $uriBuilder;


	/**
	 * An instance of the typo3_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript typo3_forum settings
	 * @var array
	 */
	protected $settings;


	/*
	  * INITIALIZATION METHODS
	  */


	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param \Mittwald\Typo3Forum\Service\Mailing\HTMLMailingService $htmlMailingService
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder
	 */
	public function __construct(\Mittwald\Typo3Forum\Service\Mailing\HTMLMailingService $htmlMailingService, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder)
	{
		$this->htmlMailingService = $htmlMailingService;
		$this->uriBuilder = $uriBuilder;
	}


	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService)
	{
		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_typo3forum']['settings'];
	}


	/*
	 * SERVICE METHODS
	 */


	/**
	 *
	 * Notifies subscribers of a subscribeable objects about a new notifiable object
	 * within the subscribeable object, e.g. of a new post within a subscribed topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface $subscriptionObject
	 *                             The subscribed object. This may for example be a
	 *                             forum or a topic.
	 * @param  \Mittwald\Typo3Forum\Domain\Model\NotifiableInterface $notificationObject
	 *                             The object that the subscriber is notified about.
	 *                             This may for example be a new post within an
	 *                             observed topic or forum or a new topic within an
	 *                             observed forum.
	 *
	 * @return void
	 *
	 */
	public function notifySubscribers(\Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface $subscriptionObject,
									  \Mittwald\Typo3Forum\Domain\Model\NotifiableInterface $notificationObject)
	{
		$topic = $subscriptionObject;
		$post  = $notificationObject;


		$subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("Mail_Subscribe_NewPost_Subject", 'typo3_forum');
		$messageTemplate = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("Mail_Subscribe_NewPost_Body", 'typo3_forum');
		$postAuthor = $post->getAuthor()->getUsername();
		$uriBuilder = $this->uriBuilder;
		$arguments = array(
			'tx_typo3forum_pi1[controller]' => 'Topic',
			'tx_typo3forum_pi1[action]' => 'show',
			'tx_typo3forum_pi1[topic]' => $topic->getUid()
		);
		$pageNumber = $post->getTopic()->getPageCount();
		if ($pageNumber > 1) {
			$arguments['@widget_0']['currentPage'] = $pageNumber;
		}

		$topicLink = $uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments($arguments)->build();
		$topicLink = '<a href="' . $topicLink . '">' . $topic->getTitle() . '</a>';
		$uriBuilder->reset();
		$unSubscribeLink = $uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments(array('tx_typo3forum_pi1[topic]' => $topic->getUid(), 'tx_typo3forum_pi1[controller]' => 'User', 'tx_typo3forum_pi1[action]' => 'subscribe', 'tx_typo3forum_pi1[unsubscribe]' => 1))->build();
		$unSubscribeLink = '<a href="' . $unSubscribeLink . '">' . $unSubscribeLink . '</a>';
		foreach ($topic->getSubscribers() AS $subscriber) {
			if ($subscriber != $post->getAuthor()) {
				$marker = array(
					'###RECIPIENT###' => $subscriber->getUsername(),
					'###POST_AUTHOR###' => $postAuthor,
					'###TOPIC_LINK###' => $topicLink,
					'###UNSUBSCRIBE_LINK###' => $unSubscribeLink,
					'###FORUM_NAME###' => $this->settings['mailing']['sender']['name']
				);
				$message = $messageTemplate;
				foreach ($marker As $name => $value) {
					$message = str_replace($name, $value, $message);
				}
				$this->htmlMailingService->sendMail($subscriber, $subject, nl2br($message));
			}
		}
	}


}

?>

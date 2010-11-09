<?php

Interface Tx_MmForum_Domain_Model_SubscribeableInterface {

		/**
		 *
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 *
		 */
	Public Function getSubscribers();

		/**
		 *
		 * @return string
		 *
		 */
	Public Function getTitle();

}

?>
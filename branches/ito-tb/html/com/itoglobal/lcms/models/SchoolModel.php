<?php

class SchoolModel {
	
	private $caption = '';
	
	private $avatar = '';
	
	private $description = '';
	
	private $fee_structure = '';
	
	private $moderator_user = '';
	/**
	 * @return the $caption
	 */
	public function getCaption() {
		return $this->caption;
	}
	
	/**
	 * @param $caption the $caption to set
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
	}
	
	/**
	 * @return the $avatar
	 */
	public function getAvatar() {
		return $this->avatar;
	}
	
	/**
	 * @param $avatar the $avatar to set
	 */
	public function setAvatar($avatar) {
		$this->avatar = $avatar;
	}
	
	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @param $description the $description to set
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * @return the $fee_structure
	 */
	public function getFee_structure() {
		return $this->fee_structure;
	}
	
	/**
	 * @param $fee_structure the $fee_structure to set
	 */
	public function setFee_structure($fee_structure) {
		$this->fee_structure = $fee_structure;
	}
	
	/**
	 * @return the $moderator_user
	 */
	public function getModerator_user() {
		return $this->moderator_user;
	}
	
	/**
	 * @param $moderator_user the $moderator_user to set
	 */
	public function setModerator_user($moderator_user) {
		$this->moderator_user = $moderator_user;
	}

}

?>
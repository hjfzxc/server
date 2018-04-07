<?php

/**
 *
 * @copyright Copyright (c) 2018, Daniel Calviño Sánchez (danxuliu@gmail.com)
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

use Behat\Behat\Context\Context;

class FileListContext implements Context, ActorAwareInterface {

	/**
	 * @var Actor
	 */
	private $actor;

	/**
	 * @var array
	 */
	private $fileListAncestorsByActor;

	/**
	 * @var Locator
	 */
	private $fileListAncestor;

	/**
	 * @BeforeScenario
	 */
	public function initializeFileListAncestors() {
		$this->fileListAncestorsByActor = array();
		$this->fileListAncestor = null;
	}

	/**
	 * @param Actor $actor
	 */
	public function setCurrentActor(Actor $actor) {
		$this->actor = $actor;

		if (array_key_exists($actor->getName(), $this->fileListAncestorsByActor)) {
			$this->fileListAncestor = $this->fileListAncestorsByActor[$actor->getName()];
		} else {
			$this->fileListAncestor = null;
		}
	}

	/**
	 * Sets the file list ancestor to be used in the steps performed by the
	 * given actor from that point on (until changed again).
	 *
	 * This is meant to be called from other contexts, for example, when the
	 * Files app or the public page for a shared folder are opened.
	 *
	 * The FileListAncestorSetter trait can be used to reduce the boilerplate
	 * needed to set the file list ancestor from other contexts.
	 *
	 * @param null|Locator $fileListAncestor the file list ancestor
	 * @param Actor $actor the actor
	 */
	public function setFileListAncestorForActor($fileListAncestor, Actor $actor) {
		$this->fileListAncestorsByActor[$actor->getName()] = $fileListAncestor;
	}

	/**
	 * @return Locator
	 */
	public static function mainWorkingIcon($fileListAncestor) {
		return Locator::forThe()->css(".mask.icon-loading")->
				descendantOf($fileListAncestor)->
				describedAs("Main working icon in file list");
	}

	/**
	 * @return Locator
	 */
	public static function createMenuButton($fileListAncestor) {
		return Locator::forThe()->css("#controls .button.new")->
				descendantOf($fileListAncestor)->
				describedAs("Create menu button in file list");
	}

	/**
	 * @return Locator
	 */
	private static function createMenuItemFor($fileListAncestor, $newType) {
		return Locator::forThe()->xpath("//div[contains(concat(' ', normalize-space(@class), ' '), ' newFileMenu ')]//span[normalize-space() = '$newType']/ancestor::li")->
				descendantOf($fileListAncestor)->
				describedAs("Create $newType menu item in file list");
	}

	/**
	 * @return Locator
	 */
	public static function createNewFolderMenuItem($fileListAncestor) {
		return self::createMenuItemFor($fileListAncestor, "New folder");
	}

	/**
	 * @return Locator
	 */
	public static function createNewFolderMenuItemNameInput($fileListAncestor) {
		return Locator::forThe()->css(".filenameform input")->
				descendantOf(self::createNewFolderMenuItem($fileListAncestor))->
				describedAs("Name input in create new folder menu item in file list");
	}

	/**
	 * @return Locator
	 */
	public static function rowForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->xpath("//*[@id = 'fileList']//span[contains(concat(' ', normalize-space(@class), ' '), ' nametext ') and normalize-space() = '$fileName']/ancestor::tr")->
				descendantOf($fileListAncestor)->
				describedAs("Row for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function favoriteActionForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css(".action-favorite")->
				descendantOf(self::rowForFile($fileListAncestor, $fileName))->
				describedAs("Favorite action for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function favoritedStateIconForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css(".icon-starred")->
				descendantOf(self::favoriteActionForFile($fileListAncestor, $fileName))->
				describedAs("Favorited state icon for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function mainLinkForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css(".name")->
				descendantOf(self::rowForFile($fileListAncestor, $fileName))->
				describedAs("Main link for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function renameInputForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css("input.filename")->
				descendantOf(self::rowForFile($fileListAncestor, $fileName))->
				describedAs("Rename input for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function shareActionForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css(".action-share")->
				descendantOf(self::rowForFile($fileListAncestor, $fileName))->
				describedAs("Share action for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function fileActionsMenuButtonForFile($fileListAncestor, $fileName) {
		return Locator::forThe()->css(".action-menu")->
				descendantOf(self::rowForFile($fileListAncestor, $fileName))->
				describedAs("File actions menu button for file $fileName in file list");
	}

	/**
	 * @return Locator
	 */
	public static function fileActionsMenu() {
		return Locator::forThe()->css(".fileActionsMenu")->
				describedAs("File actions menu in file list");
	}

	/**
	 * @return Locator
	 */
	private static function fileActionsMenuItemFor($itemText) {
		return Locator::forThe()->xpath("//a[normalize-space() = '$itemText']")->
				descendantOf(self::fileActionsMenu())->
				describedAs($itemText . " item in file actions menu in file list");
	}

	/**
	 * @return Locator
	 */
	public static function detailsMenuItem() {
		return self::fileActionsMenuItemFor("Details");
	}

	/**
	 * @return Locator
	 */
	public static function renameMenuItem() {
		return self::fileActionsMenuItemFor("Rename");
	}

	/**
	 * @return Locator
	 */
	public static function viewFileInFolderMenuItem() {
		return self::fileActionsMenuItemFor("View in folder");
	}

	/**
	 * @Given I create a new folder named :folderName
	 */
	public function iCreateANewFolderNamed($folderName) {
		$this->actor->find(self::createMenuButton($this->fileListAncestor), 10)->click();

		$this->actor->find(self::createNewFolderMenuItem($this->fileListAncestor), 2)->click();
		$this->actor->find(self::createNewFolderMenuItemNameInput($this->fileListAncestor), 2)->setValue($folderName . "\r");
	}

	/**
	 * @Given I enter in the folder named :folderName
	 */
	public function iEnterInTheFolderNamed($folderName) {
		$this->actor->find(self::mainLinkForFile($this->fileListAncestor, $folderName), 10)->click();
	}

	/**
	 * @Given I open the details view for :fileName
	 */
	public function iOpenTheDetailsViewFor($fileName) {
		$this->actor->find(self::fileActionsMenuButtonForFile($this->fileListAncestor, $fileName), 10)->click();

		$this->actor->find(self::detailsMenuItem(), 2)->click();
	}

	/**
	 * @Given I rename :fileName1 to :fileName2
	 */
	public function iRenameTo($fileName1, $fileName2) {
		$this->actor->find(self::fileActionsMenuButtonForFile($this->fileListAncestor, $fileName1), 10)->click();

		$this->actor->find(self::renameMenuItem(), 2)->click();

		$this->actor->find(self::renameInputForFile($this->fileListAncestor, $fileName1), 10)->setValue($fileName2 . "\r");
	}

	/**
	 * @Given I mark :fileName as favorite
	 */
	public function iMarkAsFavorite($fileName) {
		$this->actor->find(self::favoriteActionForFile($this->fileListAncestor, $fileName), 10)->click();
	}

	/**
	 * @When I view :fileName in folder
	 */
	public function iViewInFolder($fileName) {
		$this->actor->find(self::fileActionsMenuButtonForFile($this->fileListAncestor, $fileName), 10)->click();

		$this->actor->find(self::viewFileInFolderMenuItem(), 2)->click();
	}

	/**
	 * @Then I see that the file list is eventually loaded
	 */
	public function iSeeThatTheFileListIsEventuallyLoaded() {
		if (!WaitFor::elementToBeEventuallyNotShown(
				$this->actor,
				self::mainWorkingIcon($this->fileListAncestor),
				$timeout = 10 * $this->actor->getFindTimeoutMultiplier())) {
			PHPUnit_Framework_Assert::fail("The main working icon for the file list is still shown after $timeout seconds");
		}
	}

	/**
	 * @Then I see that it is not possible to create new files
	 */
	public function iSeeThatItIsNotPossibleToCreateNewFiles() {
		// Once a file list is loaded the "Create" menu button is always in the
		// DOM, so it is checked if it is visible or not.
		PHPUnit_Framework_Assert::assertFalse($this->actor->find(self::createMenuButton($this->fileListAncestor))->isVisible());
	}

	/**
	 * @Then I see that the file list contains a file named :fileName
	 */
	public function iSeeThatTheFileListContainsAFileNamed($fileName) {
		PHPUnit_Framework_Assert::assertNotNull($this->actor->find(self::rowForFile($this->fileListAncestor, $fileName), 10));
	}

	/**
	 * @Then I see that :fileName is marked as favorite
	 */
	public function iSeeThatIsMarkedAsFavorite($fileName) {
		PHPUnit_Framework_Assert::assertNotNull($this->actor->find(self::favoritedStateIconForFile($this->fileListAncestor, $fileName), 10));
	}

}
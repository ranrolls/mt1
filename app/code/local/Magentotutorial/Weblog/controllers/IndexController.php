<?php
class Magentotutorial_Weblog_IndexController extends Mage_Core_Controller_Front_Action {

	public function indexAction() {
		echo 'we blog simple';
	}

	// public function testModelAction() {
	// $blogpost = Mage::getModel('weblog/blogpost');
	// echo get_class($blogpost);
	// }
	public function testModelAction() {
		// localhost/p/m/mt1/weblog/index/testmodel/id/1
		$params = $this -> getRequest() -> getParams();
		$blogpost = Mage::getModel('weblog/blogpost');
		echo("Loading the blogpost with an ID of " . $params['id'] . "<br />");
		$blogpost -> load($params['id']);
		$data = $blogpost -> getData();
		var_dump($data);
	}

	public function createNewPostAction() {
		$blogpost = Mage::getModel('weblog/blogpost');
		$blogpost -> setTitle('Second Code Post!');
		$blogpost -> setPost('This second post was created from code!');
		$blogpost -> save();
		echo 'post with ID ' . $blogpost -> getId() . ' created';
	}

	public function showAllBlogPostsAction() {
		echo 'yeh';
		$posts = Mage::getModel('weblog/blogpost') -> getCollection();
		foreach($posts as $blogpost) {
			echo '<h3>' . $blogpost -> getTitle() . '</h3>';
			echo nl2br($blogpost -> getPost());
		}
	}

}

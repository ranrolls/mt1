<?php
/*
 /////////////////////////////////////////////////////
 ///////// Part 1 - Introduction to Magento //////////
 /////////////////////////////////////////////////////

 // Mage::getModel('catalog/product');
 // Mage_Catalog_Model_Product
 // Mage::helper('catalog/product');
 // Mage_Catalog_Helper_Product

 // $model = Mage::getModel('catalog/product')->load(27);
 // $price = $model->getPrice();
 // $price += 5;
 // $model->setPrice($price)->setSku('SK83293432');
 // $model->save();

 // $products_collection = Mage::getModel('catalog/product')
 // ->getCollection()
 // ->addAttributeToSelect('*')
 // ->addFieldToFilter('price','5.00');
 //
 // foreach($products_collection as $product)
 // {
 // echo $product->getName();
 // }

 // $helper = Mage::helper('catalog');
 // $helper = Mage::helper('catalog/data');
 //
 // $translated_output =  $helper->__('Magento is Great'); //gettext style translations
 // if($helper->isModuleOutputEnabled()): //is output for this module on or off?

 // public function galleryAction()
 // {
 // if (!$this->_initProduct()) {
 // if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
 // $this->_redirect('');
 // } elseif (!$this->getResponse()->isRedirect()) {
 // $this->_forward('noRoute');
 // }
 // return;
 // }
 // $this->loadLayout();
 // $this->renderLayout();
 // }

 // public function indexAction()
 // {
 // $this->loadLayout();
 // $block = $this->getLayout()->createBlock('adminhtml/system_account_edit')
 // $this->getLayout()->getBlock('content')->append($block);
 // $this->renderLayout();
 // }

 // <catalog_category_default>
 // <reference name="left">
 // <block type="catalog/navigation" name="catalog.leftnav" after="currency" template="catalog/navigation/left.phtml"/>
 // </reference>
 // </catalog_category_default>

 // $this->getChildHtml('order_items')
 //
 //
 // Observers
 // Like any good object-oriented system, Magento implements an Event/Observer pattern for end users to hook into.
 // As certain actions happen during a Page request (a Model is saved, a user logs in, etc.),
 // Magento will issue an event signal.
 // When creating your own Modules, you can "listen" for these events.
 // Say you wanted to get an email every time a certain customer logged into the store.
 // You could listen for the "customer_login" event (setup in config.xml)

 //
 // <events>
 // <customer_login>
 // <observers>
 // <unique_name>
 // <type>singleton</type>
 // <class>mymodule/observer</class>
 // <method>iSpyWithMyLittleEye</method>
 // </unique_name>
 // </observers>
 // </customer_login>
 // </events>
 //
 // class Packagename_Mymodule_Model_Observer
 // {
 // public function iSpyWithMyLittleEye($observer)
 // {
 // $data = $observer->getData();
 // //code to check observer data for out user,
 // //and take some action goes here
 // }
 // }

 // $product = Mage::getModel('catalog/product');
 // class Packagename_Modulename_Model_Foobazproduct extends Mage_Catalog_Model_Product
 // {
 // public function validate()
 // {
 // //add custom validation functionality here
 // return $this;
 // }
 //
 // }

 // <models>
 // <!-- does the override for catalog/product-->
 // <catalog>
 // <rewrite>
 // <product>Packagename_Modulename_Model_Foobazproduct</product>
 // </rewrite>
 // </catalog>
 // </models>

 /////////////////////////////////////////////////////
 ///////// Part 2 - The Magento Config //////////
 /////////////////////////////////////////////////////

 // app/code/local/Magentotutorial/Configviewer/Block
 // app/code/local/Magentotutorial/Configviewer/controllers
 // app/code/local/Magentotutorial/Configviewer/etc
 // app/code/local/Magentotutorial/Configviewer/Helper
 // app/code/local/Magentotutorial/Configviewer/Model
 // app/code/local/Magentotutorial/Configviewer/sql

 app/code/local/Magentotutorial/Configviewer/etc/config.xml
 <config>
 <modules>
 <Magentotutorial_Configviewer>
 <version>0.1.0</version>
 </Magentotutorial_Configviewer>
 </modules>
 </config>

 app/etc/modules/Magentotutorial_Configviewer.xml
 <config>
 <modules>
 <Magentotutorial_Configviewer>
 <active>true</active>
 <codePool>local</codePool>
 </Magentotutorial_Configviewer>
 </modules>
 </config>

 Clear your Magento cache
 In the Magento Admin, go to System->Configuration->Advanced
 In the "Disable modules output" panel verify that Magentotutorial_Configviewer shows up

 * Of course, this module doesn't do anything yet. When we're done, our module will

 Check for the existence of a "showConfig" query string variable
 If showConfig is present, display our Magento config and halt normal execution
 Check for the existence of an additional query string variable,
 * showConfigFormat that will let us specify text or xml output.

 config.xml file.
 <config>
 <modules>...</modules>
 <global>
 <events>
 <controller_front_init_routers>
 <observers>
 <Magentotutorial_configviewer_model_observer>
 <type>singleton</type>
 <class>Magentotutorial_Configviewer_Model_Observer</class>
 <method>checkForConfigRequest</method>
 </Magentotutorial_configviewer_model_observer>
 </observers>
 </controller_front_init_routers>
 </events>
 </global>
 </config>
 *
 create Magentotutorial/Configviewer/Model/Observer.php
 * <?php
 class Magentotutorial_Configviewer_Model_Observer {
 const FLAG_SHOW_CONFIG = 'showConfig';
 const FLAG_SHOW_CONFIG_FORMAT = 'showConfigFormat';

 private $request;

 public function checkForConfigRequest($observer) {
 $this->request = $observer->getEvent()->getData('front')->getRequest();
 if($this->request->{self::FLAG_SHOW_CONFIG} === 'true'){
 $this->setHeader();
 $this->outputConfig();
 }
 }

 private function setHeader() {
 $format = isset($this->request->{self::FLAG_SHOW_CONFIG_FORMAT}) ?
 $this->request->{self::FLAG_SHOW_CONFIG_FORMAT} : 'xml';
 switch($format){
 case 'text':
 header("Content-Type: text/plain");
 break;
 default:
 header("Content-Type: text/xml");
 }
 }

 private function outputConfig() {
 die(Mage::app()->getConfig()->getNode()->asXML());
 }
 }
 *
 That's it. Clear your Magento cache again and then load any Magento URL with a showConfig=true query string
 * http://magento.example.com/?showConfig=true

 *
 * Why Do I Care?
 Right now this may seem esoteric, but this config is key to understanding Magento.
 * Every module you'll be creating will add to this config,
 * and anytime you need to access a piece of core system functionality,
 * Magento will be referring back to the config to look something up.
 *
 * $helper_sales = new HelperSales();		-> In Php
 * $this->load->helper('url');				->	In Ci
 * $helper_sales = Mage::helper('sales');	->	In Magento
 *
 * In plain english, the static helper method will:
 Look in the <helpers /> section of the Config.
 Within <helpers />, look for a <sales /> section
 Within the <sales /> section look for a <class /> section
 Append the part after the slash to the value found in #3 (defaulting to data in this case)
 Instantiate the class found in #4 (Mage_Sales_Helper_Data)
 *

 /////////////////////////////////////////////////////
 ///////// Part 3 - Magento Controller Dispatch //////////
 /////////////////////////////////////////////////////

 * The Model-View-Controller (MVC) architecture traces its origins back to the Smalltalk Programming language and
 * Xerox Parc. Since then, there have been many systems that describe their architecture as MVC.
 * Each system is slightly different, but all have the goal of separating data access, business logic,
 * 	and user-interface code from one another.

 The architecture of most PHP MVC frameworks will looks something like this.

 A URL is intercepted by a single PHP file (usually called a Front Controller).
 This PHP file will examine the URL, and derive a Controller name and an Action name
 * (a process that's often called routing).
 The derived Controller is instantiated.
 The method name matching the derived Action name is called on the Controller.
 This Action method will instantiate and call methods on models,
 * depending on the request variables.
 The Action method will also prepare a data structure of information.
 * This data structure is passed on to the view.
 The view then renders HTML, using the information in the data structure it has received from the Controller.

 *****************************
 * How MAGENTO do it?
 * ****************************
 * As you've probably guessed, the Magento team shares this world view and
 * has created a more abstract MVC pattern that looks something like this:.

 A URL is intercepted by a single PHP file.
 This PHP file instantiates a Magento application.
 The Magento application instantiates a Front Controller object.
 Front Controller instantiates any number of Router objects (specified in global config).
 Routers check the request URL for a "match".
 If a match is found, an Action Controller and Action are derived.
 The Action Controller is instantiated and the method name matching the Action Name is called.
 This Action method will instantiate and call methods on models, depending on the request.
 This Action Controller will then instantiate a Layout Object.
 This Layout Object will, based some request variables and system properties
 * (also known as "handles"), create a list of Block objects that are valid for this request.
 Layout will also call an output method on certain Block objects, which start a nested rendering
 * (Blocks will include other Blocks).
 Each Block has a corresponding Template file. Blocks contain PHP logic,
 * templates contain HTML and PHP output code.
 Blocks refer directly back to the models for their data. In other words,
 * the Action Controller does not pass them a data structure.
 We'll eventually touch on each part of this request,
 * but for now we're concerned with the Front Controller -> Routers -> Action Controller section.

 Hello World
 Enough theory, it's time for Hello World. We're going to

 Create a Hello World module in the Magento system
 Configure this module with routes
 Create Action Controller(s) for our routes
 *
 *
 * app/code/local/Magentotutorial/Helloworld/Block
 app/code/local/Magentotutorial/Helloworld/controllers
 app/code/local/Magentotutorial/Helloworld/etc
 app/code/local/Magentotutorial/Helloworld/Helper
 app/code/local/Magentotutorial/Helloworld/Model
 app/code/local/Magentotutorial/Helloworld/sql
 * app/code/local/Magentotutorial/Helloworld/etc/config.xml):

 <config>
 <modules>
 <Magentotutorial_Helloworld>
 <version>0.1.0</version>
 </Magentotutorial_Helloworld>
 </modules>
 </config>
 Then create a file to activate the module (at path app/etc/modules/Magentotutorial_Helloworld.xml):

 <config>
 <modules>
 <Magentotutorial_Helloworld>
 <active>true</active>
 <codePool>local</codePool>
 </Magentotutorial_Helloworld>
 </modules>
 </config>
 Finally, we ensure the module is active:

 Clear your Magento cache.
 In the Magento Admin, go to System->Configuration->Advanced.
 Expand "Disable Modules Output" (if it isn't already).
 Ensure that Magentotutorial_Helloworld shows up.
 *
 * In your config.xml file, add the following section:

 <config>
 ...
 <frontend>
 <routers>
 <helloworld>
 <use>standard</use>
 <args>
 <module>Magentotutorial_Helloworld</module>
 <frontName>helloworld</frontName>
 </args>
 </helloworld>
 </routers>
 </frontend>
 ...
 </config>
 *
 * What is a <frontend>?
 The <frontend> tag refers to a Magento Area. For now, think of Areas as individual Magento applications.
 * The "frontend" Area is the public facing Magento shopping cart application.
 * The "admin" Area is the the private administrative console application.
 * The "install" Area is the application you use to run though installing Magento the first time.

 Why a <routers> tags if we're configuring individual routes?
 There's a famous quote about computer science, often attributed to Phil Karlton:

 "There are only two hard things in Computer Science: cache invalidation and naming things"

 Magento, like all large systems, suffers from the naming problem in spades.
 * You'll find there are are many places in the global config, and the system in general,
 * where the naming conventions seem unintuitive or even ambiguous. This is one of those places.
 * Sometimes the <routers> tag will enclose configuration information about routers,
 * other times it will enclose configuration information about the actual router objects that do the routing.
 * This is going to seem counter intuitive at first, but as you start to work with Magento more and more,
 * you'll start to understand its world view a little better. (Or, in the words of Han Solo, "Hey, trust me!").

 What is a <frontName>?
 When a router parses a URL, it gets separated as follows

 http://example.com/frontName/actionControllerName/actionMethod/
 So, by defining a value of "helloworld" in the <frontName> tags,
 * we're telling Magento that we want the system to respond to URLs in the form of

 http://example.com/helloworld/*
 Many developers new to Magento confuse this frontName with the Front Controller object.
 * They are not the same thing. The frontName belongs solely to routing.

 What's the <helloworld> tag for?
 This tag should be the lowercase version of you module name. Our module name is Helloworld,
 * this tag is helloworld. Technically this tag defines our route name

 You'll also notice our frontName matches our module name.
 * It's a loose convention to have frontNames match the module names,
 * but it's not a requirement. In your own modules,
 * it's probably better to use a route name that's a combination of your module name and
 * package name to avoid possible namespace collisions.

 What's <module>Magentotutorial_Helloworld</module> for?
 This module tag should be the full name of your module, including its package/namespace name.
 * This will be used by the system to locate your Controller files.

 Create Action Controller(s) for our Routes
 One last step to go, and we'll have our Action Controller. Create a file at

 app/code/local/Magentotutorial/Helloworld/controllers/IndexController.php
 That contains the following

 class Magentotutorial_Helloworld_IndexController extends Mage_Core_Controller_Front_Action {

 public function indexAction() {

 echo 'Hello Index!';

 }

 }
 * Clear your config cache, and load the following URL

 http://exmaple.com/helloworld/index/index
 You should also be able to load

 http://exmaple.com/helloworld/index/
 http://exmaple.com/helloworld/
 *
 * Where do Action Controllers go?
 Action Controllers should be placed in a module's controllers (lowercase c) folder.
 * This is where the system will look for them.

 How should Action Controllers be named?
 Remember the <module> tag back in config.xml?

 <module>Magentotutorial_Helloworld</module>
 *
 * An Action Controller's name will

 Start with this string specified in config.xml (Magentotutorial_Helloworld)
 Be followed by an underscore (Magentotutorial_Helloworld_)
 Which will be followed by the Action Controller's name (Magentotutorial_Helloworld_Index)
 And finally, the word "Controller" (Magentotutorial_Helloworld_IndexController)
 All Action Controllers need Mage_Core_Controller_Front_Action as an ancestor.

 What's that index/index nonsense?
 As we previously mentioned, Magento URLs are routed (by default) as follows

 http://example.com/frontName/actionControllerName/actionMethod/
 So in the URL

 http://example.com/helloworld/index/index
 the URI portion "helloworld" is the frontName, which is followed by index (The Action Controller name),
 * which is followed by another index, which is the name of the Action Method that will be called.
 * (an Action of index will call the method public function indexAction(){...}.

 If a URL is incomplete, Magento uses "index" as the default, which is why the following URLs are equivalent.

 http://example.com/helloworld/index
 http://example.com/helloworld
 If we had a URL that looked like this

 http://example.com/checkout/cart/add
 Magento would

 Consult the global config to find the module to use for the frontName checkout (Mage_Checkout)
 Look for the cart Action Controller (Mage_Checkout_CartController)
 Call the addAction method on the cart Action Controller
 Other Action Controller Tricks
 Let's try adding a non-default method to our Action Controller. Add the following code to IndexController.php

 public function goodbyeAction() {
 echo 'Goodbye World!';
 }
 And then visit the URL to test it out:

 http://example.com/helloworld/index/goodbye
 Because we're extending the Mage_Core_Controller_Front_Action class, we get some methods for free. For example,
 * additional URL elements are automatically parsed into key/value pairs for us.
 * Add the following method to your Action Controller.

 public function paramsAction() {
 echo '<dl>';
 foreach($this->getRequest()->getParams() as $key=>$value) {
 echo '<dt><strong>Param: </strong>'.$key.'</dt>';
 echo '<dt><strong>Value: </strong>'.$value.'</dt>';
 }
 echo '</dl>';
 }
 and visit the following URL

 http://example.com/helloworld/index/params?foo=bar&baz=eof
 You should see each parameter and value printed out.

 Finally, what would we do if we wanted a URL that responded at

 http://example.com/helloworld/messages/goodbye
 Here our Action Controller's name is messages, so we'd create a file at

 app/code/local/Magentotutorial/Helloworld/controllers/MessagesController.php
 with an Action Controller named Magentotutorial_Helloworld_MessagesController and an Action Method that
 * looked something like

 public function goodbyeAction()
 {
 echo 'Another Goodbye';
 }

 /////////////////////////////////////////////////////
 ///////// Part 4 - Magento Layouts, Blocks and Templates //////////
 /////////////////////////////////////////////////////

 Take a look a the default product Template at the file at

 app/design/frontend/base/default/template/catalog/product/list.phtml.
 You'll see the following PHP template code.

 <?php $_productCollection=$this->getLoadedProductCollection() ?>
 <?php if(!$_productCollection->count()): ?> <div class="note-msg">
 <?php echo $this->__("There are no products matching the selection.") ?>
 </div> <?php else: ?>
 ...
 The getLoadedProductCollection method can be found in the Template's Block class,
 * Mage_Catalog_Block_Product_List as shown:

 File: app/code/core/Mage/Catalog/Block/Product/List.php
 ...
 public function getLoadedProductCollection()
 {
 return $this->_getProductCollection();
 }

 *
 *
 * The Layout
 So, Blocks and Templates are all well and good, but you're probably wondering

 How do I tell Magento which Blocks I want to use on a page?
 How do I tell Magento which Block I should start rendering with?
 How do I specify a particular Block in getChildHtml(...)? Those argument strings don't look like Block names to me.
 This is where the Layout Object enters the picture.
 * The Layout Object is an XML object that will define which Blocks are included on a page,
 * and which Block(s) should kick off the rendering process.

 Last time we were echoing content directly from our Action Methods.
 * This time let's create a simple HTML template for our Hello World module.

 First, create a file at

 app/design/frontend/base/default/layout/local.xml
 with the following contents

 <layout version="0.1.0">
 <default>
 <block type="page/html" name="root" output="toHtml" template="magentotutorial/helloworld/simple_page.phtml" />
 </default>
 </layout>
 Then, create a file at

 app/design/frontend/base/default/template/magentotutorial/helloworld/simple_page.phtml
 with the following contents

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <title>Hello World</title>
 <style type="text/css">
 body {
 background-color:#f00;
 }
 </style>
 </head>
 <body>

 </body>
 </html>
 Finally, each Action Controller is responsible for kicking off the layout process.
 * We'll need to add two method calls to the Action Method.

 public function indexAction() {
 //remove our previous echo
 //echo 'Hello Index!';
 $this->loadLayout();
 $this->renderLayout();
 }
 *
 *What's Going On
 So, that's a lot of voodoo and cryptic incantations. Let's take a look at what's going on.

 First, you'll want to install the Layoutviewer module.
 * This is a module similar to the Configviewer module you built in the Hello World
 * article that will let us peek at some of Magento's internals.

 Once you've installed the module (similar to how you setup the Configviewer module),
 * go to the following URL

 http://example.com/helloworld/index/index?showLayout=page
 This is the layout xml for your page/request. It's made up of <block />, <reference /> and <remove /> tags.
 * When you call the loadLayout method of your Action Controller, Magento will

 Generate this Layout XML

 Instantiate a Block class for each <block /> tag,
 * looking up the class using the tag's type attribute as a global config path and
 * store it in the internal _blocks array of the layout object,
 * using the tag's name attribute as the array key.

 If the <block /> tag contains an output attribute,
 * its value is added to the internal _output array of the layout object.

 Then, when you call the renderLayout method in your Action Controller,
 * Magento will iterate over all the Blocks in the _output array,
 * using the value of the output attribute as a callback method.
 * This is always toHtml, and means the starting point for output will be that Block's Template.

 The following sections will cover how Blocks are instantiated,
 * how this layout file is generated, and finishes up with kicking off the output process.

 If we create a block with the same name as an already existing block,
 * the new block instance will replace the original instance.
 * This is what we've done in our local.xml file from above.
 * The Block named root has been replaced with our Block, which points at a different phtml Template file.
 *
 *
 Using references
 <refernce name="" /> will hook all contained XML declarations into an existing block with the specified name.
 * Contained <block /> nodes will be assigned as child blocks to the referenced parent block.
	
	 <layout version="0.1.0">
	 <default>
	 <block type="page/html" name="root" output="toHtml" template="page/2columns-left.phtml">
	 <!-- ... sub blocks ... -->
	 </block>
	 </default>
	 </layout>
	 In a different layout file:
	
	 <layout version="0.1.0">
	 <default>
	 <reference name="root">
	 <!-- ... another sub block ... -->
	 <block type="page/someothertype" name="some.other.block.name" template="path/to/some/other/template" />
	 </reference>
	 </default>
	 </layout>*
 *
 *
 * http://www.magentocommerce.com/knowledge-base/entry/magento-for-dev-part-4-magento-layouts-blocks-and-templates
 * How Layout Files are Generated
 * today 22-7-2014, we will do the remaining on 23-7-2014
 * enough for today I suppose, Prac this much at home well
 *
 *
 continue further on 24-07-2014
 How Layout Files are Generated
 So, we have a slightly better understanding of what's going on with the Layout XML,
 * but where is this XML file coming from?
 * To answer that question, we need to introduce two new concepts; Handles and the Package Layout.

 -> Handles
 Each page request in Magento will generate several unique Handles.
 * The Layoutview module can show you these Handles by using a URL something like

 http://example.com/helloworld/index/index?showLayout=handles
 You should see a list similar to the following (depending on your configuration)

	 default
	 STORE_bare_us
	 THEME_frontend_default_default
	 helloworld_index_index
	 customer_logged_out
 *
 * Each of these is a Handle. Handles are set in a variety of places within the Magento system.
 * The two we want to pay attention to are default and helloworld_index_index.
 * The default Handle is present in every request into the Magento system.
 * The helloworld_index_index Handle is created by combining the route name (helloworld),
 * Action Controller name (index), and Action Controller Action Method (index) into a single string.
 * This means each possible method on an Action Controller has a Handle associated with it.
 * Remember that "index" is the Magento default for both Action Controllers and Action Methods, so the following request

 http://example.com/helloworld/?showLayout=handles
 Will also produce a Handle named helloworld_index_index
 *
 *
 -> Package Layout
 You can think of the Package Layout similar to the global config.
 * It's a large XML file that contains every possible layout configuration for a particular Magento install.
 * Let's take a look at it using the Layoutview module

 http://example.com/helloworld/index/index?showLayout=package
 This may take a while to load. If your browser is choking on the XML rendering, try the text format

 http://example.com/helloworld/index/index?showLayout=package&showLayoutFormat=text
 You should see a very large XML file. This is the Package Layout.
 * This XML file is created by combining the contents of all the XML layout files for the current theme (or package).
 * For the default install, this is at

 app/design/frontend/base/default/layout/
 Behind the scenes there are <frontend><layout><updates /> and <adminhtml><layout><updates />
 * sections of the global config that contains nodes with all the file names to load for the respective area.
 * Once the files listed in the config have been combined, Magento will merge in one last xml file, local.xml.
 * This is the file where you're able to add your customizations to your Magento install.
 *
 * Combining Handles and The Package Layout
 So, if you look at the Package Layout, you'll see some familiar tags such as <block /> and <reference />,
 * but they're all surrounded by tags that look like

 <default />
 <catalogsearch_advanced_index />
 etc...
 These are all Handle tags.
 * The Layout for an individual request is generated by grabbing all the sections of the Package Layout
 * that match any Handles for the request. So, in our example above,
 * our layout is being generated by grabbing tags from the following sections
	
	 <default />
	 <STORE_bare_us />
	 <THEME_frontend_default_default />
	 <helloworld_index_index />
	 <customer_logged_out />
 There's one additional tag you'll need to be aware of in the Package Layout.
 * The <update /> tag allows you to include another Handle's tags. For example

	 <customer_account_index>
	 <!-- ... -->
	 <update handle="customer_account"/>
	 <!-- ... -->
	 </customer_account_index>
 Is saying that requests
 * with a customer_account_index Handle should include <blocks />s from the <customer_account /> Handle.
 *
 * Applying What We've Learned
 OK, that's a lot of theory. Lets get back to what we did earlier. Knowing what we know now, adding

	 <layout version="0.1.0">
	 <default>
	 <block type="page/html" name="root" output="toHtml" template="magentotutorial/helloworld/simple_page.phtml" />
	 </default>
	 </layout>
 to local.xml means we've overridden the "root" tag. with a different Block. By placing this in the <default />
 * Handle we've ensured that this override will happen for every page request in the system.
 * That's probably not what we want.

 If you go to any other page in your Magento site, you'll notice they're either blank white,
 * or have the same red background that your hello world page does.
 * Let's change your local.xml file so it only applies to the hello world page.
 * We'll do this by changing default to use the full action name handle (helloworld_index_index).

	 <layout version="0.1.0">
	 <helloworld_index_index>
	 <block type="page/html" name="root" output="toHtml" template="magentotutorial/helloworld/simple_page.phtml" />
	 </helloworld_index_index>
	 </layout>
 Clear your Magento cache, and the rest of your pages should be restored.

 Right now this only applies to our index Action Method. Let's add it to the goodbye Action Method as well.
 * In your Action Controller, modify the goodbye action so it looks like
 *
	 * public function goodbyeAction() {
	 $this->loadLayout();
	 $this->renderLayout();
	 }
 If you load up the following URL, you'll notice you're still getting the default Magento layout.

 http://example.com/helloworld/index/goodbye
 We need to add a Handle for the full action name (helloworld_index_goodbye) to our local.xml file.
 * Rather than specify a new <block />, lets use the update tag to include the helloworld_index_index Handle.
 *
 * <layout version="0.1.0">
 <!-- ... -->
	 <helloworld_index_goodbye>
	 <update handle="helloworld_index_index" />
	 </helloworld_index_goodbye>
 </layout>
 *
 * Loading the following pages (after clearing your Magento cache) should now produce identical results.

 http://example.com/helloworld/index/index
 http://example.com/helloworld/index/goodbye
 *
 *
 * Starting Output and getChildHtml
 In a standard configuration, output starts on the Block named root (because it has an output attribute).
 * We've overridden root's Template with our own

 template="magentotutorial/helloworld/simple_page.phtml"
 Templates are referenced from the root folder of the current theme. In this case, that's

 app/design/frontend/base/default
 so we need to drill down to our custom page. Most Magento Templates are stored in

 app/design/frontend/base/default/templates
 Combining this gives us the full path

 app/design/frontend/base/default/templates/magentotutorial/helloworld/simple_page.phtml
 *
 * Adding Content Blocks
 A simple red page is pretty boring. Let's add some content to this page.
 * Change your <helloworld_index_index /> Handle in local.xml so it looks like the following

	 <helloworld_index_index>
	 <block type="page/html" name="root" output="toHtml" template="magentotutorial/helloworld/simple_page.phtml">
	 <block type="customer/form_register" name="customer_form_register" template="customer/form/register.phtml"/>
	 </block>
	 </helloworld_index_index>
 We're adding a new Block nested within our root. This is a Block that's distributed with Magento,
 * and will display a customer registration form. By nesting this Block within our root Block,
 * we've made it available to be pulled into our simple_page.html Template.
 * Next, we'll use the Block's getChildHtml method in our simple_page.phtml file.
 * Edit simple_page.html so it looks like this
 *
 * <body>
 <?php echo $this->getChildHtml('customer_form_register');
 ?>
 </body>
 *
 * Clear your Magento cache and reload the page and you should see the customer registration form on your red background.
 * Magento also has a Block named top.links. Let's try including that. Change your simple_page.html file so it reads

 <body>
 <h1>Links</h1>
 <?php echo $this->getChildHtml('top.links'); ?>
 </body>
 When you reload the page, you'll notice that your <h1>Links</h1> title is rendering,
 * but nothing is rendering for top.links.
 That's because we didn't add it to local.xml. The getChildHtml method can only include
 * Blocks that are specified as sub-Blocks in the Layout. This allows Magento to only instantiate the Blocks it needs,
 * and also allows you to set difference Templates for Blocks based on context.

 Let's add the top.links Block to our local.xml
	 <helloworld_index_index>
	 <block type="page/html" name="root" output="toHtml" template="magentotutorial/helloworld/simple_page.phtml">
	 <block type="page/template_links" name="top.links"/>
	 <block type="customer/form_register" name="customer_form_register" template="customer/form/register.phtml"/></block>
	 </helloworld_index_index>
	 *
 * 
 * Time for action
 There is one more important concept to cover before we wrap up this lesson, and that is the
 <action />
 tag.
 * Using the
 <action />
 tag enables us to call public PHP methods of the block classes.
 * So instead of changing the template of the root block by replacing the block instance with our own,
 * we can use a call to setTemplate instead.
	 <layout version="0.1.0">
	 <helloworld_index_index>
	 <reference name="root">
	 <action method="setTemplate">
	 <template>
	 magentotutorial/helloworld/simple_page.phtml
	 </template>
	 </action>
	 <block type="page/template_links" name="top.links"/>
	 <block type="customer/form_register" name="customer_form_register" template="customer/form/register.phtml"/>
	 </reference>
	 </helloworld_index_index>
	 </layout>
 This layout XML will first set the template property of the root block, and
 * then will add the two blocks we use as child blocks.
 * Once we clear the cache, the result should look just as before.
 * The benefit of using the
 <action />
 is the same block instance is used that was created earlier, and
 * all other parent/child associations still exist.
 * For that reason this is a more upgrade proof way of implementing our changes.

 All arguments to the action's method need to be wrapped in an individual child node of the
 <action />
 tag.
 * The name of that node doesn't matter, only the order of the nodes.
 * We could have written the action node from the previous example as follows with the same effect.
	 <action method="setTemplate">
	 <some_new_template>
	 magentotutorial/helloworld/simple_page.phtml
	 </some_new_template>
	 </action>
 This is just to illustrate that the action's argument node names are arbitrary.

 * 
 * Block Types

Magento defines some built-in block types which are widely used in layout.

core/template: This block renders a template defined by its template attribute. 
 * The majority of blocks defined in the layout are of type or subtype of core/template.
page/html: This is a subtype of core/template and defines the root block. 
 * All other blocks are child blocks of this block.
page/html_head: Defines the HTML head section of the page which contains elements for including JavaScript, CSS etc.
page/html_header: Defines the header part of the page which contains the site logo, top links, etc.
page/template_links: This block is used to create a list of links. 
 * Links visible in the footer and header area use this block type.
core/text_list: Some blocks like content, left, right etc. are of type core/text_list. 
 * When these blocks are rendered, all their child blocks are rendered automatically without 
 * the need to call the getChildHtml() method.
page/html_wrapper: This block is used to create a wrapper block which renders its child blocks 
 * inside an HTML tag set by the action setHtmlTagName. The default tag is <div> if no element is set.
page/html_breadcrumbs: This block defines breadcrumbs on the page.
page/html_footer: Defines footer area of page which contains footer links, copyright message etc.
core/messages: This block renders error/success/notice messages.
page/switch: This block can be used for the language or store switcher.
This is a list of only commonly used block types. There are many other block types 
 * which are used in advanced theme implementations.
 * 
 * 
 Wrapup
 That covers Layout fundamentals. We have covered the tags
	 <block />
	 ,
	 <reference />
	 ,
	 <update />
	 and
	 <action />
	 , and
	 * also layout update handles like
	 <default />
	 and
	 <cms_index_index />
 .
 * These make up most of the layout configuration used in Magento.
 * If you found it somewhat daunting, don't worry, you'll rarely need to work with layouts on such a fundamental level.
 * Magento provides a number of pre-built layouts which can be modified and skinned to meet the needs of your store.
 * Understanding how the entire Layout system works can be a great help when you're trouble shooting Layout issues,
 * or adding new functionality to an existing Magento system.
 *
 /////////////////////////////////////////////////////
 ///////// Part 5 - Magento Models and ORM Basics //////////
 /////////////////////////////////////////////////////

 * 
 * The implementation of a "Models Tier" is a huge part of any MVC framework. 
 * It represents the data of your application, and most applications are useless without data. 
 * Magento Models play an even bigger role, as they typically contain the "Business Logic" 
 * that's often relegated to the Controller or Helper methods in other PHP MVC frameworks.
 * 
 * Magento Models
It should be no surprise that Magento takes the ORM approach. 
 * While the Zend Framework SQL abstractions are available, 
 * most of your data access will be via the built in Magento Models, and Models you build yourself. 
 * It should also come as no surprise that Magento has a highly flexible, highly abstract, 
 * concept of what a Model is.

Anatomy of a Magento Model
Most Magento Models can be categorized in one of two ways. There's a basic, 
 * ActiveRecord-like/one-object-one-table Model, and there's also an Entity Attribute Value (EAV) Model. 
 * Each Model also gets a Model Collection. 
 * Collections are PHP objects used to hold a number of individual Magento Model instances. 
 * The Magento team has implemented the PHP Standard Library interfaces of IteratorAggregate and 
 * Countable to allow each Model type to have it's own collection type. 
 * If you're not familiar with the PHP Standard Library, 
 * think of Model Collections as arrays that also have methods attached.

Magento Models don't contain any code for connecting to the database. 
 * Instead, each Model uses a modelResource class, that is used to communicate with the database server 
 * (via one read and one write adapter object). By decoupling the logical Model and 
 * the code that talks to the database, 
 * it's theoretically possible to write new resource classes for a different database schemas and 
 * platforms while keeping your Models themselves untouched.
 * 
 * Creating a Basic Model
To begin, we're going to create a basic Magento Model. 
 * PHP MVC tradition insists we model a weblog post. The steps we'll need to take are

Create a new "Weblog" module
Create a database table for our Model
Add Model information to the config for a Model named Blogpost
Add Model Resource information to the config for the Blogpost Model
Add a Read Adapter to the config for the Blogpost Model
Add a Write Adapter to the config for the Blogpost Model
Add a PHP class file for the Blogpost Model
Add a PHP class file for the Blogpost Resource Model
Instantiate the Model
 * 
 * Create a Weblog Module
You should be an old hat at creating empty modules at this point, 
 * so we'll skip the details and assume you can create an empty module named Weblog. 
 * After you've done that, we'll setup a route for an index Action Controller with an action named "testModel". 
 * As always, the following examples assume a Package Name of "Magentotutorial".

In Magentotutorial/Weblog/etc/config.xml, setup the following route

<frontend>
    <routers>
        <weblog>
            <use>standard</use>
            <args>
                <module>Magentotutorial_Weblog</module>
                <frontName>weblog</frontName>
            </args>
        </weblog>
    </routers>
</frontend>
 * 
 * And then add the following Action Controller in

class Magentotutorial_Weblog_IndexController extends Mage_Core_Controller_Front_Action {
    public function testModelAction() {
        echo 'Setup!';
    }
}
at Magentotutorial/Weblog/controllers/IndexController.php. 
 * Clear your Magento cache and load the following URL to ensure everything's been setup correctly.

http://example.com/weblog/index/testModel
You should see the word "Setup" on a white background.
 * 
 Creating the Database Table
Magento has a system for automatically creating and changing your database schemas, 
 * but for the time being we'll just manually create a table for our Model.

Using the command-line or your favorite MySQL GUI application, create a table with the following schema

CREATE TABLE `blog_posts` (
  `blogpost_id` int(11) NOT NULL auto_increment,
  `title` text,
  `post` text,
  `date` datetime default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`blogpost_id`)
)
And then populate it with some data

INSERT INTO `blog_posts` VALUES (1,'My New Title','This is a blog post','2010-07-01 00:00:00','2010-07-02 23:12:30');
 * 
 * The Global Config and Creating The Model
There are five individual things we need to setup for a Model in our config.

Enabling Models in our Module
Enabling Model Resources in our Module
Add an "entity" table configuration to our Model Resource.
When you instantiate a Model in Magento, you make a call like this

$model = Mage::getModel('weblog/blogpost'); 
 * The first part of the URI you pass into get Model is the Model Group Name. 
 * Because it is a good idea to follow conventions, 
 * this should be the (lowercase) name of your module, 
 * or to be safeguarded agains conflicts use the packagename and modulename (also in lowercase). 
 * The second part of the URI is the lowercase version of your Model name.

So, let's add the following XML to our module's config.xml.

<global>
    <!-- ... -->
    <models>
        <weblog>
            <class>Magentotutorial_Weblog_Model</class>
            <!--
            need to create our own resource, can't just
            use core_resource
            -->
            <resourceModel>weblog_resource</resourceModel>
        </weblog>
    </models>
    <!-- ... -->
</global>
 * 
 * The outer <weblog /> tag is your Group Name, which should match your module name. 
 * <class /> is the BASE name all Models in the weblog group will have, 
 * also calles Class Prefix. The <resourceModel /> tag indicates which 
 * Resource Model that weblog group Models should use. There's more on this below, 
 * but for now be content to know it's your Group Name, followed by a the literal string "resource".

So, we're not done yet, but let's see what happens if we clear our Magento cache and 
 * attempt to instantiate a blogpost Model. In your testModelAction method, use the following code
 * 
 * public function testModelAction() {
        $blogpost = Mage::getModel('weblog/blogpost');
        echo get_class($blogpost);
    }
 * 
 * and reload your page. You should see an exception that looks something like this 
 * (be sure you've turned on developer mode).

include(Magentotutorial/Weblog/Model/Blogpost.php) [function.include]: failed to open stream: No such file or directory
By attempting to retrieve a weblog/blogpost Model, you told Magento to instantiate a class with the name

Magentotutorial_Weblog_Model_Blogpost
Magento is trying to __autoload include this Model, but can't find the file. 
 * Let's create it! Create the following class at the following location

File: app/code/local/Magentotutorial/Weblog/Model/Blogpost.php
class Magentotutorial_Weblog_Model_Blogpost extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('weblog/blogpost');
    }
}
Reload your page, and the exception should be replaced with the name of your class.

All basic Models that interact with the database should extend the Mage_Core_Model_Abstract class. 
 * This abstract class forces you to implement a single method named _construct 
 * (NOTE: this is not PHP's constructor __construct). 
 * This method should call the class's _init method with the same identifying 
 * URI you'll be using in the Mage::getModel method call.
 * 
The Global Config and Resources
So, we've setup our Model. Next, we need to setup our Model Resource. 
 * Model Resources contain the code that actually talks to our database. 
 * In the last section, we included the following in our config.

<resourceModel>weblog_resource</resourceModel>
The value in <resourceModel /> will be used to instantiate a Model Resource class. 
 * Although you'll never need to call it yourself, when any Model in the weblog group needs to talk to the database, 
 * Magento will make the following method call to get the Model resource

Mage::getResourceModel('weblog/blogpost');
Again, weblog is the Group Name, and blogpost is the Model. 
 * The Mage::getResourceModel method will use the weblog/blogpost URI to inspect the global config and 
 * pull out the value in <resourceModel> (in this case, weblog_resource). 
 * Then, a model class will be instantiated with the following URI

weblog_resource/blogpost
So, if you followed that all the way, what this means is, 
 * resource models are configured in the same section of the XML config as normal Models.
 * This can be confusing to newcomers and old-hands alike.

So, with that in mind, let's configure our resource. In our <models> section add
<global>
    <!-- ... -->
    <models>
        <!-- ... -->
        <weblog_resource>
            <class>Magentotutorial_Weblog_Model_Resource</class>
        </weblog_resource>
    </models>
</global>
You're adding the <weblog_resource /> tag, which is the value of the <resourceModel /> tag you just setup. 
 * The value of <class /> is the base name that all your resource modes will have, 
 * and should be named with the following format

Packagename_Modulename_Model_Resource
So, we have a configured resource, let's try loading up some Model data. 
 * Change your action to look like the following

public function testModelAction() {
    $params = $this->getRequest()->getParams();
    $blogpost = Mage::getModel('weblog/blogpost');
    echo("Loading the blogpost with an ID of ".$params['id']);
    $blogpost->load($params['id']);
    $data = $blogpost->getData();
    var_dump($data);
}
And then load the following URL in your browser (after clearing your Magento cache)

http://example.com/weblog/index/testModel/id/1
You should see an exception something like the following

Warning: include(Magentotutorial/Weblog/Model/Resource/Blogpost.php) [function.include]: 
 * failed to open stream: No such file ....
As you've likely intuited, we need to add a resource class for our Model. 
 * Every Model has its own resource class. Add the following class at at the following location

File: app/code/local/Magentotutorial/Weblog/Model/Resource/Blogpost.php
class Magentotutorial_Weblog_Model_Resource_Blogpost extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('weblog/blogpost', 'blogpost_id');
    }
}
Again, the first parameter of the init method is the URL used to identify the Model. 
 * The second parameter is the database field that uniquely identifies any particular column. 
 * In most cases, this should be the primary key. Clear your cache, reload, and you should see

Can't retrieve entity config: weblog/blogpost
Another exception! When we use the Model URI weblog/blogpost, we're telling Magento we want the Model Group weblog, 
 * and the blogpost Entity. In the context of simple Models that extend Mage_Core_Model_Resource_Db_Abstract, 
 * an entity corresponds to a table. In this case, the table named blog_post that we created above. 
 * Let's add that entity to our XML config.

<models>
        <!-- ... --->
        <weblog_resource>
            <class>Magentotutorial_Weblog_Model_Resource</class>
            <entities>
                <blogpost>
                    <table>blog_posts</table>
                </blogpost>
            </entities>
        </weblog_resource>
    </models>
We've added a new <entities /> section to the resource Model section of our config. 
 * This, in turn, has a section named after our entity (<blogpost />) that specifies the name 
 * of the database table we want to use for this Model.

Clear your Magento cache, cross your fingers, reload the page and ...

Loading the blogpost with an ID of 1

array
  'blogpost_id' => string '1' (length=1)
  'title' => string 'My New Title' (length=12)
  'post' => string 'This is a blog post' (length=19)
  'date' => string '2009-07-01 00:00:00' (length=19)
  'timestamp' => string '2009-07-02 16:12:30' (length=19)
Eureka! We've managed to extract our data and, more importantly, completely configure a Magento Model.

Basic Model Operations
All Magento Models inherit from the the Varien_Object class. 
 * This class is part of the Magento system library and not part of any Magento core module. 
 * You can find this object at

lib/Varien/Object.php
Magento Models store their data in a protected _data property. 
 * The Varien_Object class gives us several methods we can use to extract this data. 
 * You've already seen getData, which will return an array of key/value pairs. 
 * This method can also be passed a string key to get a specific field.

$model->getData();
$model->getData('title');
There's also a getOrigData method, which will return the Model data as it was when the object was initially populated, 
 * (working with the protected _origData method).

$model->getOrigData();
$model->getOrigData('title');
The Varien_Object also implements some special methods via PHP's magic __call method. 
 * You can get, set, unset, or check for the existence of any property using a method that 
 * begins with the word get, set, unset or has and is followed by the camel cased name of a property.

$model->getBlogpostId();
$model->setBlogpostId(25);
$model->unsetBlogpostId();
if($model->hasBlogpostId()){...}
For this reason, you'll want to name all your database columns with lower case characters and 
 * use underscores to separate characters.

CRUD, the Magento Way
Magento Models support the basic Create, Read, Update, and Delete functionality of CRUD with load, save, and 
 * delete methods. You've already seen the load method in action. When passed a single parameter, 
 * the load method will return a record whose id field (set in the Model's resource) matches the passed in value.

$blogpost->load(1);
The save method will allow you to both INSERT a new Model into the database, or UPDATE an existing one. 
 * Add the following method to your Controller

public function createNewPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->setTitle('Code Post!');
    $blogpost->setPost('This post was created from code!');
    $blogpost->save();
    echo 'post with ID ' . $blogpost->getId() . ' created';
}
and then execute your Controller Action by loading the following URL

http://example.com/weblog/index/createNewPost
You should now see an additional saved post in you database table. Next, try the following to edit your post

public function editFirstPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->load(1);
    $blogpost->setTitle("The First post!");
    $blogpost->save();
    echo 'post edited';
}
And finally, you can delete your post using very similar syntax.

public function deleteFirstPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->load(1);
    $blogpost->delete();
    echo 'post removed';
}
Model Collections
So, having a single Model is useful, but sometimes we want to grab list of Models. 
 * Rather than returning a simple array of Models, 
 * each Magento Model type has a unique collection object associated with it. 
 * These objects implement the PHP IteratorAggregate and Countable interfaces, 
 * which means they can be passed to the count function, and used in for each constructs.

We'll cover Collections in full in a later article, but for now let's look at basic setup and usage. 
 * Add the following action method to your Controller, and load it in your browser.

public function showAllBlogPostsAction() {
    $posts = Mage::getModel('weblog/blogpost')->getCollection();
    foreach($posts as $blogpost){
        echo '<h3>'.$blogpost->getTitle().'</h3>';
        echo nl2br($blogpost->getPost());
    }
}
Load the action URL,

http://example.com/weblog/index/showAllBlogPosts
and you should see a (by now) familiar exception.

Warning: include(Magentotutorial/Weblog/Model/Resource/Blogpost/Collection.php) 
 * [function.include]: failed to open stream
You're not surprised, are you? We need to add a PHP class file that defines our Blogpost collection. 
 * Every Model has a protected property named _resourceCollectionName that contains a URI that's used 
 * to identify our collection.

protected '_resourceCollectionName' => string 'weblog/blogpost_collection'
By default, this is the same URI that's used to identify our Resource Model, with the string "_collection" 
 * appended to the end. Magento considers Collections part of the Resource, so this URI is converted into the class name

Magentotutorial_Weblog_Model_Resource_Blogpost_Collection
Add the following PHP class at the following location

File: app/code/local/Magentotutorial/Weblog/Model/Resource/Blogpost/Collection.php
class Magentotutorial_Weblog_Model_Resource_Blogpost_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
            $this->_init('weblog/blogpost');
    }
}
Just as with our other classes, we need to init our Collection with the Model URI. (weblog/blogpost). 
 * Rerun your Controller Action, and you should see your post information.

Wrapup
Congratulations, you've created and configured you first Magento Model. 
 * In a later article we'll take a look at Magento's Entity Attribute Value Models (EAV), 
 * which expand on what we've learned here.


 /////////////////////////////////////////////////////
 ///////// Part 6 - Magento Setup Resources //////////
 /////////////////////////////////////////////////////

On any fast paced software development project, the task of keeping the development and 
 * production databases in sync become a sticky wicket. Magento offers a system to create 
 * versioned resource migration scripts that can help your team deal with this often contentious 
 * part of the development process.

In the ORM article we created a model for a weblog post. At the time, 
 * we ran our CREATE TABLE statements directly against the database. 
 * This time, we'll create a Setup Resource for our module that will create the table for us. 
 * We'll also create an upgrade script for our module that will update an already installed module. 
 * The steps we'll need to take are

Add the Setup Resource to our config
Create our resource class file
Create our installer script
Create our upgrade script

Adding the Setup Resource
So, let's continue with the weblog module we created last time. In our <global /> section, 
 * add the following

<global>
    <!-- ... -->
    <resources>
        <weblog_setup>
            <setup>
                <module>Magentotutorial_Weblog</module>
                <class>Magentotutorial_Weblog_Model_Resource_Setup</class>
            </setup>
        </weblog_setup>
    </resources>
    <!-- ... -->
</global>
The <weblog_setup> tag will be used to uniquely identify this Setup Resource. 
 * It's encouraged, but not necessary, that you use the modelname_setup naming convention. 
 * The <module>Magentotutorial_Weblog</module> tag block should contain the Packagename_Modulename of your module. 
 * Finally, <class>Magentotutorial_Weblog_Model_Resource_Setup</class> 
 * should contain the name of the class we'll be creating for our Setup Resource. 
 * For basic setup scripts it's not necessary to create a custom class, 
 * but by doing it now you'll give yourself more flexibility down the line.

After adding the above section to your config, clear your Magento cache and 
 * try to load any page of your Magento site. You'll see an exception something like

Fatal error: Class 'Magentotutorial_Weblog_Model_Resource_Setup' not found in
Magento just tried to instantiate the class you specified in your config, but couldn't find it. 
 * You'll want to create the following file, with the following contents.

File: app/code/local/Magentotutorial/Weblog/Model/Resource/Setup.php

class Magentotutorial_Weblog_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup {
}
Now, reload any page of your Magento site. The exception should be gone, and your page should load as expected.

Creating our Installer Script
Next, we'll want to create our installer script. This is the script that will contain any 
 * CREATE TABLE or other SQL code that needs to be run to initialize our module.

First, take a look at your config.xml file

<modules>
    <Magentotutorial_Weblog>
        <version>0.1.0</version>
    </Magentotutorial_Weblog>
</modules>
This section is required in all config.xml files, and identifies the module as well as the its version number. 
 * Your installer script's name will be based on this version number. 
 * The following assumes the current version of your module is 0.1.0.

Create the following file at the following location

File: app/code/local/Magentotutorial/Weblog/sql/weblog_setup/mysql4-install-0.1.0.php

echo 'Running This Upgrade: '.get_class($this)."\n <br /> \n";
die("Exit for now");
The weblog_setup portion of the path should match the tag you created in your config.xml file (<weblog_setup />). 
 * The 0.1.0 portion of the filename should match the starting version of your module. 
 * Clear your Magento cache and reload any page in your Magento site and you should see something like

Running This Upgrade: Magentotutorial_Weblog_Model_Resource_Setup
Exit for now
 ...
Which means your update script ran. Eventually we'll put our SQL update scripts here, 
 * but for now we're going to concentrate on the setup mechanism itself. 
 * Remove the "die" statement from your script so it looks like the following

echo 'Running This Upgrade: '.get_class($this)."\n <br /> \n";
Reload your page. You should see your upgrade message displayed at the top of the page. 
 * Reload again, and your page should be displayed as normal.

Resource Versions
Magento's Setup Resources allow you to simply drop your install scripts (and upgrade scripts, 
 * which we'll get to in a bit) onto the server, and have the system automatically run them. 
 * This allows you to have all your database migrations scripts stored in the system in a consistent format. 

The weblog_setup is already installed, so it won't be updated. 
 * If you want to re-run your installer script (useful when you're developing), 
 * just delete the row for your module from this table. Let's do that now, and actually add 
 * the SQL to create our table. So first, run the following SQL from your sql client.

DELETE from core_resource where code = 'weblog_setup';
We'll also want to drop the table we manually created in the ORM article.

DROP TABLE blog_posts;
 * 
Then, add the following code to your setup script.
File: app/code/local/Magentotutorial/Weblog/sql/weblog_setup/mysql4-install-0.1.0.php

$installer = $this;
$installer->startSetup();
$installer->run("
    CREATE TABLE `{$installer->getTable('weblog/blogpost')}` (
      `blogpost_id` int(11) NOT NULL auto_increment,
      `title` text,
      `post` text,
      `date` datetime default NULL,
      `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
      PRIMARY KEY  (`blogpost_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT INTO `{$installer->getTable('weblog/blogpost')}` VALUES (1,
 * 'My New Title','This is a blog post','2009-07-01 00:00:00',
 * '2009-07-02 23:12:30');
");
$installer->endSetup();
Clear your Magento cache and reload any page in the system. 
 * You should have a new blog_posts table with a single row.

Anatomy of a Setup Script
So, let's go over the script line-by-line. First, there's this (or is that $this?)

$installer = $this;
Each installer script is run from the context of a Setup Resource class, 
 * the class you created above. That means any reference to $this from within the script will 
 * be a reference to an object instantiated from this class. While not necessary, 
 * most setup scripts in the core modules will alias $this to a variable called installer, 
 * which is what we've done here. While not necessary, it is the convention and it's always 
 * best to follow the convention unless you have a good reason for breaking it.

Next, you'll see our queries are bookended by the following two method calls.

$installer->startSetup();
//...
$installer->endSetup();
If you take a look at the Mage_Core_Model_Resource_Setup class in 
 * app/code/core/Mage/Core/Model/Resource/Setup.php (which your setup class inherits from) 
 * you can see that these methods do some basic SQL setup

public function startSetup()
    {
        $this->getConnection()->startSetup()
        return $this;
    }

    public function endSetup()
    {
        $this->getConnection()->endSetup();
        return $this;
    }
Look can into Varien_Db_Adapter_Pdo_Mysql to find the real SQL setup executed for 
 * MySQL connections in the startSetup() and endSetup() methods.

Finally, there's the call to the run method

$installer->run(...);
which accepts a string containing the SQL needed to setup your database table(s). 
 * You may specify any number of queries, separated by a semi-colon. You also probably noticed the following

$installer->getTable('weblog/blogpost')
The getTable method allows you to pass in a Magento Model URI and get its table name. 
 * While not necessary, using this method ensures that your script will continue to run, 
 * even if someone changes the name of their table in the config file. 
 * The Mage_Core_Model_Resource_Setup class contains many useful helper methods like this. 
 * The best way to become familiar with everything that's possible is to study the installer 
 * scripts used by the core Magento modules.

RDBMS Agnostic Scripts
Since version 1.6, Magento (in theory) supports more database backends then only MySQL. 
 * Since our setup script contains raw SQL statements, it may not run correctly on a 
 * different database system, say MSSQL. For that reason the setup script name is prefixt with the string mysql4-

In order to make setup scripts cross-database compatible, Magento offers a 
 * DDL (Data Definition Language) Table object. Here is an alternative version of our 
 * setup script that would run on any supported RDBMS.

File: app/code/local/Magentotutorial/Weblog/sql/weblog_setup/mysql4-install-0.1.0.php

$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('weblog/blogpost'))
    ->addColumn('blogpost_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'Blogpost ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Blogpost Title')
    ->addColumn('post', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        ), 'Blogpost Body')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        ), 'Blogpost Date')
    ->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Timestamp')
    ->setComment('Magentotutorial weblog/blogpost entity table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
As you can see, there is no raw SQL in this version of the setup script. 
 * So which version should you use? If you want your Modules to run on any RDBMS backend, 
 * use the new DDL style upgrade scripts. If you are concerned about backward compatibility, 
 * use the raw SQL flavor, that is still supported by Magento 1.6 and 1.7 
 * (and probably will be supported by any 1.x Magento release).

Module Upgrades
So, that's how you create a script that will setup your initial database tables, 
 * but what if you want to alter the structure of an existing module? Magento's Setup Resources 
 * support a simple versioning scheme that will let you automatically run scripts to upgrade your modules.

Once Magento runs an installer script for a module, it will never run another installer 
 * for that module again (short of manually deleting the reference in the core_resource table). 
 * Instead, you'll need to create an upgrade script. Upgrade scripts are very similar to installer scripts, 
 * with a few key differences.

To get started, we'll create a script at the following location, with the following contents

File: app/code/local/Magentotutorial/Weblog/sql/weblog_setup/upgrade-0.1.0-0.2.0.php:

echo 'Testing our upgrade script (upgrade-0.1.0-0.2.0.php) and halting execution to avoid 
 * updating the system version number <br />';
die();
Upgrade scripts are placed in the same folder as your installer script, but named slightly differently. 
 * First, and most obviously, the file name contains the word upgrade. Secondly, 
 * you'll notice there are two version numbers, separated by a "-". The first (0.1.0) 
 * is the module version that we're upgrading from. The second (0.2.0) is the module version we're upgrading to.

If we cleared our Magento cache and reloaded a page, our script wouldn't run. 
 * We need to update the the version number in our module's config.xml file to trigger the upgrade

<modules>
    <Magentotutorial_Weblog>
        <version>0.2.0</version>
    </Magentotutorial_Weblog>
</modules>
With the new version number in place, we'll need to clear our Magento cache and load any page in our Magento site. 
 * You should now see output from your upgrade script.

By the way, we also could have names our upgrade script mysql4-upgrade-0.1.0-0.2.0.php. 
 * This would indicate our upgrade would contain MySQL specific SQL.

Before we continue and actually implement the upgrade script, there's one important piece of 
 * behavior you'll want to be aware of. Create another upgrade file at the following location 
 * with the following contents.

File: app/code/local/Magentotutorial/Weblog/sql/weblog_setup/upgrade-0.1.0-0.1.5.php:

echo 'Testing our upgrade script (upgrade-0.1.0-0.1.5.php) and NOT halting execution <br />';
If you reload a page, you'll notice you see BOTH messages. When Magento notices the version 
 * number of a module has changed, it will run through all the setup scripts needed to bring that 
 * version up to date. Although we never really created a version 0.1.5 of the Weblog module, 
 * Magento sees the upgrade script, and will attempt to run it. Scripts will be run in order 
 * from lowest to highest. If you take a peek at the core_resource table,

mysql> select * from core_resource where code = 'weblog_setup';
+--------------+---------+--------------+
| code         | version | data_version |
+--------------+---------+--------------+
| weblog_setup | 0.1.5   | 0.1.5        |
+--------------+---------+--------------+
1 row in set (0.00 sec)
you'll notice Magento considers the version number to be 1.5. That's because we completed 
 * executing the 1.0 to 1.5 upgrade, but did not complete execution of the 1.0 to 2.0 upgrade.

So, with all that out of the way, writing our actual upgrade script is identical to writing an 
 * installer script. Let's change the 0.1.0-0.2.0 script to read

$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->changeColumn($installer->getTable('weblog/blogpost'), 'post', 'post', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Blogpost Body'
    )
);
$installer->endSetup();
die("You'll see why this is here in a second");
Try refreshing a page in your Magento site and ... nothing. The upgrade script didn't run. 
 * The post field in our table still allows null values, and more importantly, the call to die() 
 * did not halt execution. Here's what happened

The weblog_setup resource was at version 0.1.0
We upgraded our module to version 0.2.0
Magento saw the upgraded module, and saw there were two upgrade scripts to run; 0.1.0 to 0.1.5 
 * and 0.1.0 to 0.2.0
Magento queued up both scripts to run
Magento ran the 0.1.0 to 0.1.5 script
The weblog_setup resource is now at version 0.1.5
Magento ran the 0.1.0 to 0.2.0 script, execution was halted
On the next page load, Magento saw weblog_setup at version 0.1.5 and did not see any upgrade scripts 
 * to run since both scripts indicated they should be run from 0.1.0
The correct way to achieve what we wanted would have been to name our scripts as follows

upgrade-0.1.0-0.1.5.php #This goes from 0.1.0 to 0.1.5
upgrade-0.1.5-0.2.0.php #This goes 0.1.5 to 0.2.0
Magento is smart enough to run both scripts on a single page load. You can go back in time 
 * and give this a try by updating the core_resource table

UPDATE core_resource SET version = '0.1.0', data_version = '0.1.0' WHERE code = 'weblog_setup';
...
It's one of the odd quirks of the Magento system that the updates will run as previously configured. 
 * This means you'll want to be careful with multiple developers adding update scripts to the system. 
 * You'll either want a build-meister/deployment-manager type in charge of the upgrade scripts 
 * or (heaven forbid) developers will need to talk to one another.

Wrap-up
You should now know the basics of how to use Magento Setup Resources to create 
 * versioned database migration scripts, as well as understand the scripts provided in the core modules. 
 * Beyond having a standard way for developers to write migration scripts, 
 * Setup Resources become much more important when creating and modifying Entity Attribute Value models.

 /////////////////////////////////////////////////////////////////////////
 ///////// Part 7 - Advanced ORM - Entity Attribute Value //////////
 /////////////////////////////////////////////////////////////////////////

In the first ORM article we told you there were two kinds of Models in Magento. 
 * Regular, or "simple" Models, and Entity Attribute Value (or EAV) Models. 
 * We also told you this was a bit of a fib. Here's where we come clean.

ALL Magento Models interacting with the database inherit from the Mage_Core_Model_Abstract / Varien_Object chain. 
 * What makes something either a simple Model or an EAV Model is its Model Resource. 
 * While all resources extend the base Mage_Core_Model_Resource_Abstract class, 
 * simple Models have a resource that inherits from Mage_Core_Model_Resource_Db_Abstract, and 
 * EAV Models have a resource that inherits from Mage_Eav_Model_Entity_Abstract

If you think about it, this makes sense. As the end-programmer-user of the system 
 * you want a set of methods you can use to talk to and manipulate your Models. 
 * You don't care what the back-end storage looks like, you just want to get properties and 
 * invoke methods that trigger business rules.

What is EAV
Wikipedia defines EAV as

Entity-Attribute-Value model (EAV), also known as object-attribute-value model and 
 * open schema is a data model that is used in circumstances where the number of attributes 
 * (properties, parameters) that can be used to describe a thing (an "entity" or "object") 
 * is potentially very vast, but the number that will actually apply to a given entity is relatively modest. 
 * In mathematics, this model is known as a sparse matrix.
Another metaphor that helps me wrap my head around it is "EAV brings some aspects of 
 * normalization to the database table schema". In a traditional database, tables have a fixed number of columns

Every product has a name, every product has a price, etc.

In an EAV Model, each "entity" (product) being modeled has a different set of attributes. 
 * EAV makes a lot of sense for a generic eCommerce solution. 
 * A store that sells laptops (which have a CPU speed, color, ram amount, etc) is going to have a 
 * different set of needs than a store that sells yarn (yarn has a color, but no CPU speed, etc.). 
 * Even within our hypothetical yarn store, some products will have length (balls of yarn), 
 * and others will have diameter (knitting needles).

There aren't many open source or commercial databases that use EAV by default. 
 * There are none that are available on a wide variety of web hosting platforms. 
 * Because of that, the Magento engineers have built an EAV system out of PHP objects that use 
 * MySQL as a data-store. In other words, they've built an EAV database system on top of a 
 * traditional relational database.
 
In practice this means any Model that uses an EAV resource has 
 * its attributes spread out over a number of MySQL tables.


The above diagram is a rough layout of the database tables Magento consults when it looks up an 
 * EAV record for the catalog_product entity. Each individual product has a row in catalog_product_entity. 
 * All the available attributes in the entire system (not just for products) are stored in eav_attribute, 
 * and the actual attribute values are stored in tables with names like catalog_product_entity_varchar, 
 * catalog_product_entity_decimal, catalog_product_entity_etc..

Beyond the mental flexibility an EAV system gives you, there's also the practical benefit of 
 * avoiding ALTER TABLE statements. When you add a new attribute for your products, 
 * a new row is inserted into eav_attribute. In a traditional relational database/single-table system, 
 * you'd need to ALTER the actual database structure, which can be a time consuming/risky proposition 
 * for tables with large data-sets.

The downside is there's no one single simple SQL query you can use to get at all your product data. 
 * Several single SQL queries or one large join need to be made.

Implementing EAV
That's EAV in a nutshell. The rest of this articles is a run-through of what's needed to create a 
 * new EAV Model in Magento. It's the hairiest thing you'll read about Magento and it's something that 
 * 95% of people working with the system will never need to do. However, understanding what it takes to 
 * build an EAV Model Resource will help you understand what's going on with the EAV Resources that Magento uses.

Because the EAV information is so dense, we're going to assume you've studied up and are already very 
 * familiar with Magento's MVC and grouped class name features. We'll help you along the way, 
 * but training wheels are off.

Weblog, EAV Style
We're going to create another Model for a weblog post, but this time using an EAV Resource. 
 * To start with, setup and create a new module which responds at the the following URL

http://example.com/complexworld
If you're unsure how to do this, be sure you've mastered the concepts in the previous tutorials.

Next, we'll create a new Model named Weblogeav. Remember, it's the Resource that's considered EAV. 
 * We design and configure our Model the exact same way, so let's configure a Model similar to one we 
 * created in the first ORM article.

<global>
    <!-- ... -->
    <models>
        <!-- ... -->
        <complexworld>
            <class>Magentotutorial_Complexworld_Model</class>
            <resourceModel>complexworld_resource</resourceModel>
        </complexworld>
        <!-- ... -->
    </models>
    <!-- ... -->
</global>
You'll notice so far there is no difference to setting up a regular Model and flat table resource Model.

We'll still need to let Magento know about this resource. Similar to basic Models, 
 * EAV Resources are configured in the same <model/> node with everything else.

<global>
    <!-- ... -->
    <models>
        <!-- ... -->
        <complexworld_resource>
            <class>Magentotutorial_Complexworld_Model_Resource</class>
            <entities>
                <eavblogpost>
                    <table>eavblog_posts</table>
                </eavblogpost>
            </entities>
        </complexworld_resource>
        <!-- ... -->
    </models>
    <!-- ... -->
</global>
Again, so far this is setup similar to our regular Model Resource. We provide a <class/> 
 * that configures a PHP class, as well as an <entities/> section that will let Magento know the 
 * base table for an individual Model we want to create. The <eavblogpost/> tag is the name of the 
 * specific Model we want to create, and its inner <table/> tag specifies the base table this Model 
 * will use (more on this later).

Where Does That File Go?
Until wide adoption of PHP 5.3 and namespaces, one of the trickier (and tedious) parts of Magento 
 * will remain remembering how <classname/>s relate to file paths, and then ensuring you create the 
 * correctly named directory structure and class files. After configuring any <classname/>s or URIs, 
 * you may find it useful to attempt to instantiate an instance of the class in a controller without 
 * first creating the class files. This way PHP will throw an exception telling me it can't find a file, 
 * along with the file location. Give the following a try in your Index Controller.

public function indexAction() {
    $weblog2 = Mage::getModel('complexworld/eavblogpost');
    $weblog2->load(1);
    var_dump($weblog2);
}
As predicted, a warning should be thrown

Warning: include(Magentotutorial/Complexworld/Model/Eavblogpost.php) [function.include]: 
 * failed to open stream: No such file or directory  in 
 * /Users/username/Sites/magento.dev/lib/Varien/Autoload.php on line 93
In addition to telling us the path where we'll need to define the new resource class 
 * this also serves as a configuration check. If we'd been warned with the following

Warning: include(Mage/Complexworld/Model/Eavblogpost.php) [function.include]: failed to 
 * open stream: No such file or directory  in 
 * /Users/username/Sites/magento.dev/lib/Varien/Autoload.php on line 93
we'd know our Model was misconfigured, as Magento was looking for the Model in 
 * code/core/Mage instead of code/local/Magentotutorial.

So, lets create our Model class

File: app/code/local/Magentotutorial/Complexworld/Model/Eavblogpost.php:

class Magentotutorial_Complexworld_Model_Eavblogpost extends Mage_Core_Model_Abstract {
    protected function _construct()
    {
        $this->_init('complexworld/eavblogpost');
    }
}
Remember, the Model itself is resource independent. A regular Model and an EAV Model both 
 * extend from the same class. It's the resource that makes them different.

Clear your Magento cache, reload your page, and you should see a new warning.

Warning: include(Magentotutorial/Complexworld/Model/Resource/Eavblogpost.php)
As expected, we need to create a class for our Model's resource. Let's do it!

File: app/code/local/Magentotutorial/Complexworld/Model/Resource/Eavblogpost.php:

class Magentotutorial_Complexworld_Model_Resource_Eavblogpost extends Mage_Eav_Model_Entity_Abstract
{
    protected function _construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('complexworld_eavblogpost');
        $this->setConnection(
            $resource->getConnection('complexworld_read'),
            $resource->getConnection('complexworld_write')
        );
    }
}
So, already we're seeing a few differences between a simple Model Resource and an EAV Model Resource. 
 * First off, we're extending the Mage_Eav_Model_Entity_Abstract class. 
 * While Mage_Eav_Model_Entity_Abstract uses the same _construct concept as a regular Model Resource, 
 * there's no _init method. Instead, we need to handle the init ourselves. 
 * This means telling the resource what connection-resources it should use, and 
 * passing a unique identifier into the setType method of our object.

Another difference in Mage_Eav_Model_Entity_Abstract is _construct is not an abstract method, 
 * primarily for reasons of backwards compatibility with older versions of the system.

So, with that, let's clear the Magento cache and reload the page. You should see a new exception which reads

Invalid entity_type specified: complexworld_eavblogpost
Magento is complaining that it can't find a entity_type named complexworld_eavblogpost. 
 * This is the value you set above

$this->setType('complexworld_eavblogpost');
Every entity has a type. Types will, among other things, 
 * and allow the system to link to tables that store the values for attributes. 
 * We'll need to let Magento know that we're adding a new entity type. 
 * Take a look in the MySQL table named eav_entity_type.

This table contains a list of all the entity_types in the system. 
 * The unique identifier complexworld_eavblogpost corresponds to the entity_type_code column.

Systems and Applications
This illustrates the single most important Magento concept, one that many people struggle to learn.

Consider the computer in front of you. The OS (Mac OS X, Windows, Linux, etc.) is the software system. 
 * Your web browser (Firefox, Safari, IE, Opera) is the application. Magento is a system first, 
 * and an application second. You build eCommerce applications using the Magneto system. 
 * What gets confusing is, there's a lot of places in Magento where the system code is exposed 
 * in a really raw form to the application code. The EAV system configuration living in the same database 
 * as your store's data is an example of this.

If you're going to get deep into Magento, you need to treat it like it's an old Type 650 machine. 
 * That is to say, it's the kind of thing you can't effectively program applications in unless 
 * unless you have a deep understanding of the system itself.

Creating a Setup Resource
So, it's theoretically possible to manually insert the rows you'll need into the Magento database to get 
 * your Model working, but it's not recommended. Fortunately, Magento provides a specialized Setup Resource 
 * that provides a number of helper method that will automatically create the needed records to 
 * get the system up and running.

So, for starters, configure the Setup Resource like you would any other.
<global>
    <!-- ... -->
    <resources>
        <complexworld_setup>
            <setup>
                <module>Magentotutorial_Complexworld</module>
                <class>Magentotutorial_Complexworld_Model_Resource_Setup</class>
            </setup>
        </complexworld_setup>
    </resources>
    <!-- ... -->
</global>
Next, create its class file.

File: app/code/local/Magentotutorial/Complexworld/Model/Resource/Setup.php:

class Magentotutorial_Complexworld_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup {
}
Take note that we're extending from Mage_Eav_Model_Entity_Setup rather than Mage_Core_Model_Resource_Setup.

Finally, we'll set up our installer script. If you're not familiar with the naming conventions here, 
 * you'll want to review the setup resource tutorial on Setup Resources.

File: app/code/local/Magentotutorial/Complexworld/sql/complexworld_setup/install-0.1.0.php:

<?php
$installer = $this;
throw new Exception("This is an exception to stop the installer from completing");
Clear your Magento Cache, reload you page, and the above exception should be thrown, 
 * meaning you've correctly configured your Setup Resource.

NOTE: We'll be building up our install script piece by piece. If you've read the previous tutorial, 
 * you'll know you need to remove the setup's row from the core_resource table and clear your cache to 
 * make an installer script re-run. For the remainder of this tutorial, please remember that anytime 
 * we add or remove an item from our installer and re-run it, you'll need to remove this row from the 
 * database and clear your Magento cache. Normally you would create this file and run it once, 
 * a tutorial is something of an edge case.

Adding the Entity Type
To begin, add the following to your Setup Resource installer script, and then run the script by 
 * loading any page (after removing the above exception)

$installer = $this;
$installer->startSetup();
$installer->addEntityType('complexworld_eavblogpost', array(
    //entity_mode is the URI you'd pass into a Mage::getModel() call
    'entity_model'    => 'complexworld/eavblogpost',

    //table refers to the resource URI complexworld/eavblogpost
    //<complexworld_resource>...<eavblogpost><table>eavblog_posts</table>
    'table'           =>'complexworld/eavblogpost',
));
$installer->endSetup();
We're calling the addEntityType method on our installer object. This method allows us to pass in the 
 * entity type (complexworld_eavblogpost) along with a list of parameters to set its default values. 
 * If you've run this script, you'll notice new rows in the eav_attribute_group, eav_attribute_set, 
 * and eav_entity_type tables.

So, with that in place, if we reload our complexworld page, we'll get a new error.

SQLSTATE[42S02]: Base table or view not found: 1146 Table 'magento.eavblog_posts' doesn't exist
Creating the Data Tables
So, we've told Magento about our new entity type. Next, we need to add the MySQL tables that 
 * will be used to store all the entity values, as well as configure the system so it knows about these tables.

Our EAV Setup Resource has a method named createEntityTables which will automatically setup the tables we need, 
 * as well as add some configuration rows to the system. Let's add the following line to our setup resource.

$installer->createEntityTables(
    $this->getTable('complexworld/eavblogpost')
);
The createEntityTables method accepts two parameters. The first is the base table name, 
 * the second is a list of options. We're using the Setup Resource's getTable method to pull 
 * the table name from our config. If you've been following along, you know this should resolve 
 * to the string eavblog_posts. We've omitted the second parameter which is an array of options you'll 
 * only need to used it for advanced situations that are beyond the scope of this tutorial.

After running the above script, you should have the following new tables in your database

eavblog_posts
eavblog_posts_datetime
eavblog_posts_decimal
eavblog_posts_int
eavblog_posts_text
eavblog_posts_varchar
You'll also have an additional row in the eav_attribute_set table

mysql> select * from eav_attribute_set order by attribute_set_id DESC LIMIT 1 \G
*************************** 1. row ***************************
  attribute_set_id: 65
    entity_type_id: 37
attribute_set_name: Default
        sort_order: 6
So, let's go back to our page and reload.

http://example.com/complexworld
Success! You should see no errors or warnings, and and 
 * a dumped Magentotutorial_Complexworld_Model_Eavblogpost --- with no data.

Adding Attributes
The last step we need to take in our Setup Resource is telling Magento what attributes we want 
 * our EAV Model to have. This would be equivalent to adding new columns in a single database table setup. 
 * Again, the Setup Resource will help us. The method we're interested in is addAttribute.

The code from the previous section was simply telling Magento about a type of entity that we add to the system. 
 * These next bits of code are what will actually add possible attributes for our new type to the system.

We do that with the method addAttribute. When we call addAttribute, 
 * Magento will need to do several things to install your entities.

To start with, we'll give our Eavblogpost a single attribute named title.


$this->addAttribute('complexworld_eavblogpost', 'title', array(
    //the EAV attribute type, NOT a MySQL varchar
    'type'              => 'varchar',
    'label'             => 'Title',
    'input'             => 'text',
    'class'             => '',
    'backend'           => '',
    'frontend'          => '',
    'source'            => '',
    'required'          => true,
    'user_defined'      => true,
    'default'           => '',
    'unique'            => false,
));

All right, that's a small pile of code. Let's break it apart.

The first argument to addAttribute is the entity type code. 
 * It has to match the code specified when calling addEntityType. 
 * It tells Magento which entity we are adding the attribute to, 
 * in our example it is our complexworld_eavblogpost entity. 
 * To see other available entities that come shipped with Magento, 
 * remember you can look into the eav_entity_type table at the entity_type_code column.

The second argument to addAttribute is the attribute code. 
 * It has to be unique within the given entity.

The third argument is where it get real interesting. 
 * This is an array of key value pairs, describing the attribute properties. 
 * For the sake of simplicity we've chose to define a single attribute, 
 * but you could go on to define as many as you'd like, by adding additional addAttribute calls to the setup script.

Array of Key Value Pairs that Define the Attribute
Finally, we have a long list of attribute properties.

//the EAV attribute type, NOT a MySQL varchar
'type'              => 'varchar',
'label'             => 'Title',
'input'             => 'text',
'class'             => '',
'backend'           => '',
'frontend'          => '',
'source'            => '',
'required'          => true,
'user_defined'      => true,
'default'           => '',
'unique'            => false,
Most of these define how Magento would build a backend form element for this attribute, 
 * and probably you'll won't have to deal with the,. That said, the one important property you'll want to make note of is

'type' => 'varchar'
This defines the type of the value that the attribute will contain. 
 * You'll recall that we added table for each attribute type

eavblog_posts_datetime
eavblog_posts_decimal
eavblog_posts_int
eavblog_posts_text
eavblog_posts_varchar
While these do not refer to the MySQL column types, (but instead the EAV attribute types), 
 * their names (varchar, datetime, etc.) are indicative of the values they'll hold.

All of these attribute properties are optional, if we wouldn't have specified them, 
 * Magento would have used a default value. These default values are defined in the _prepareValues method 
 * of the Mage_Eav_Model_Entity_Setup class (inherited by our setup class).

// Mage_Eav_Model_Entity_Setup
protected function _prepareValues($attr)
{
    $data = array(
        'backend_model'   => $this->_getValue($attr, 'backend'),
        'backend_type'    => $this->_getValue($attr, 'type', 'varchar'),
        'backend_table'   => $this->_getValue($attr, 'table'),
        'frontend_model'  => $this->_getValue($attr, 'frontend'),
        'frontend_input'  => $this->_getValue($attr, 'input', 'text'),
        'frontend_label'  => $this->_getValue($attr, 'label'),
        'frontend_class'  => $this->_getValue($attr, 'frontend_class'),
        'source_model'    => $this->_getValue($attr, 'source'),
        'is_required'     => $this->_getValue($attr, 'required', 1),
        'is_user_defined' => $this->_getValue($attr, 'user_defined', 0),
        'default_value'   => $this->_getValue($attr, 'default'),
        'is_unique'       => $this->_getValue($attr, 'unique', 0),
        'note'            => $this->_getValue($attr, 'note'),
        'is_global'       => $this->_getValue($attr, 'global',
                                 Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
                             ),
    );

    return $data;
}
The second argument to the method calls to _getValue is the array key from our addAttribute argument array, 
 * and the third is the default value. So by default Magento would assume you are adding a varchar attribute 
 * with a text input.

Adding the other attributes
Lets add attributes for the blog post content and the post date. This is what the complete install script looks like.

$installer = $this;
    $installer->startSetup();

    $installer->addEntityType('complexworld_eavblogpost', array(
        //entity_mode is the URI you'd pass into a Mage::getModel() call
        'entity_model'    => 'complexworld/eavblogpost',

        //table refers to the resource URI complexworld/eavblogpost
        //<complexworld_resource>...<eavblogpost><table>eavblog_posts</table>
        'table'           =>'complexworld/eavblogpost',
    ));

    $installer->createEntityTables(
        $this->getTable('complexworld/eavblogpost')
    );

    $this->addAttribute('complexworld_eavblogpost', 'title', array(
        //the EAV attribute type, NOT a MySQL varchar
        'type'              => 'varchar',
        'label'             => 'Title',
        'input'             => 'text',
        'class'             => '',
        'backend'           => '',
        'frontend'          => '',
        'source'            => '',
        'required'          => true,
        'user_defined'      => true,
        'default'           => '',
        'unique'            => false,
    ));
    $this->addAttribute('complexworld_eavblogpost', 'content', array(
        'type'              => 'text',
        'label'             => 'Content',
        'input'             => 'textarea',
    ));
    $this->addAttribute('complexworld_eavblogpost', 'date', array(
        'type'              => 'datetime',
        'label'             => 'Post Date',
        'input'             => 'datetime',
        'required'          => false,
    ));

    $installer->endSetup();
So, now that we have everything in place, lets refresh things one last time to run our installer script. 
 * After calling addAttribute, we should have

A new row in eav_entity_type for the complexworld_eavblogpost entity type
A new row in eav_attribute for the title attribute
A new row in eav_attribute for the content attribute
A new row in eav_attribute for the date attribute
A new row in eav_entity_attribute
Tying it all Together
This is clearly the lamest.blogmodel.ever, but lets try adding some rows and iterating through a 
 * collection and get the heck out of here before our heads explode. Add the following two actions to 
 * your Index Controller.

public function populateEntriesAction() {
    for ($i=0;$i<10;$i++) {
        $weblog2 = Mage::getModel('complexworld/eavblogpost');
        $weblog2->setTitle('This is a test '.$i);
        $weblog2->setContent('This is test content '.$i);
        $weblog2->setDate(now());
        $weblog2->save();
    }

    echo 'Done';
}

public function showCollectionAction() {
    $weblog2 = Mage::getModel('complexworld/eavblogpost');
    $entries = $weblog2->getCollection()
        ->addAttributeToSelect('title')
        ->addAttributeToSelect('content');
    $entries->load();
    foreach($entries as $entry)
    {
        // var_dump($entry->getData());
        echo '<h2>' . $entry->getTitle() . '</h2>';
        echo '<p>Date: ' . $entry->getDate() . '</p>';
        echo '<p>' . $entry->getContent() . '</p>';
    }
    echo '</br>Done</br>';
}
Let's populate some entries! Load up the following URL

http://magento.dev/index.php/complexworld/index/populateEntries
If you take a look at your database, you should see 10 new rows in the eavblog_posts table.

 as well as 10 new rows in the eavblog_posts_varchar table.
 
Notice that eavblog_posts_varchar is linked to eavblog_posts by the entity_id column.

Finally, let's pull our Models back out. Load the following URL in your browser

http://magento.dev/index.php/complexworld/index/showCollection
This should give us a

Warning: include(Magentotutorial/Complexworld/Model/Resource/Eavblogpost/Collection.php) [function.include]: failed to open stream: No such file or directory  in /Users/username/Sites/magento.dev/lib/Varien/Autoload.php on line 93
So Close! We didn't make a class for our collection object! Fortunately, doing so is just as easy as with a regular Model Resource. Add the following file with the following contents

File: Magentotutorial/Complexworld/Model/Resource/Eavblogpost/Collection.php:

class Magentotutorial_Complexworld_Model_Resource_Eavblogpost_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('complexworld/eavblogpost');
    }
}
This is just a standard Magento _construct method to initialize the Model. With this in place, reload the page, and we'll see all the titles and the content outputted. But notice, the date value is missing!

Which Attributes?
Those of you with sharp eyes may have noticed something slightly different about the collection loading.

$entries = $weblog2->getCollection()
    ->addAttributeToSelect('title')
    ->addAttributeToSelect('content');
Because querying for EAV data can be SQL intensive, you'll need to specify which attributes it is you want your Models to fetch for you. This way the system can make only the queries it needs. If you're willing to suffer the performance consequences, you can use a wild card to grab all the attributes

$entries = $weblog2->getCollection()->addAttributeToSelect('*');
Jumping Off
So, that should give you enough information to be dangerous, or at least enough information so you're not drowning the next time you're trying to figure out why the yellow shirts aren't showing up in your store. There's still plenty to learn about EAV; here's a few topics I would have liked to cover in greater detail, and may talk about in future articles

EAV Attributes: Attributes aren't limited to datetime, decimal, int, text and varchar. You can create your own class files to model different attributes. This is what the attribute_model entity property is for.
Collection Filtering: Filtering on EAV collections can get tricky, especially when you're dealing with the above mentioned non-simple attributes. You need to use the addAttributeToFilter method on your collection before loading.
The Magento EAV Hierarchy: Magento has taken their basic EAV Model and built up a hierarchy that's very tied to store functionality, as well as including strategies to reduce the number of queries an EAV Model generates (the concept of a flat Model, for example)
EAV Models are, without a doubt, the most complicated part of the Magento system that an ecommerce web developer will need to deal with. Remember to take deep breaths and that, at the end of the day, its just programming. Everything happens for a concrete reason, you just need to figure out why.


 /////////////////////////////////////////////////////
 ///////// Part 8 - Varien Data Collections //////////
 /////////////////////////////////////////////////////

 
Originally, as a PHP programmer, if you wanted to collect together a group of 
 * related variables you had one choice, the venerable Array. While it shares a 
 * name with C's array of memory addresses, a PHP array is a general purpose 
 * dictionary like object combined with the behaviors of a numerically indexed mutable array.

In other languages the choice isn't so simple. You have multiple data structures 
 * to chose from, each offering particular advantages in storage, speed and semantics. 
 * The PHP philosophy was to remove this choice from the client programmer and give 
 * them one useful data structure that was "good enough".

All of this is galling to a certain type of software developer, and PHP 5 set out to 
 * change the status quo by offering built-in classes and interfaces that allow you to 
 * create your own data structures.

$array = new ArrayObject();
class MyCollection extends ArrayObject{...}
$collection = new MyCollection();
$collection[] = 'bar';
While this is still galling to a certain type of software developer, as you don't have 
 * access to low level implementation details, you do have the ability to create array-like 
 * Objects with methods that encapsulate specific functionality. You can also setup rules to 
 * offer a level of type safety by only allowing certain kinds of Objects into your Collection.

It should come as no surprise that Magento offers you a number of these Collections. 
 * In fact, every Model object that follows the Magento interfaces gets a Collection type 
 * for free. Understanding how these Collections work is a key part to being an effective 
 * Magento programmer. We're going to take a look at Magento Collections, 
 * starting from the bottom and working our way up. Set up a controller action where you can 
 * run arbitrary code, and let's get started.

A Collection of Things
First, we're going to create a few new Objects.

$thing_1 = new Varien_Object();
$thing_1->setName('Richard');
$thing_1->setAge(24);

$thing_2 = new Varien_Object();
$thing_2->setName('Jane');
$thing_2->setAge(12);

$thing_3 = new Varien_Object();
$thing_3->setName('Spot');
$thing_3->setLastName('The Dog');
$thing_3->setAge(7);
The Varien_Object class defines the object all Magento Models inherit from. 
 * This is a common pattern in object oriented systems, and ensures you'll 
 * always have a way to easily add methods/functionally to every object in your system without 
 * having to edit every class file.

Any Object that extends from Varien_Object has magic getter and setters that can be used to 
 * set data properties. Give this a try

var_dump($thing_1->getName());
If you don't know what the property name you're after is, you can pull out all the data as an array

var_dump($thing_3->getData());
The above will give you an array something like

array
'name' => string 'Spot' (length=4)
'last_name' => string 'The Dog' (length=7)
'age' => int 7
Notice the property named "last_name"? If there's an underscore separated property, 
 * you camel case it if you want to use the getter and setter magic.

$thing_1->setLastName('Smith');
The ability to do these kinds of things is part of the power of PHP5, and the development 
 * style a certain class of people mean when they say "Object Oriented Programming".

So, now that we have some Objects, let's add them to a Collection. Remember, a Collection is 
 * like an Array, but is defined by a PHP programmer.

$collection_of_things = new Varien_Data_Collection();
$collection_of_things
    ->addItem($thing_1)
    ->addItem($thing_2)
    ->addItem($thing_3);
The Varien_Data_Collection is the Collection that most Magento data Collections inherit from. 
 * Any method you can call on a Varien_Data_Collection you can call on Collections higher up 
 * the chain (We'll see more of this later)

What can we do with a Collection? For one, with can use foreach to iterate over it

foreach($collection_of_things as $thing)
{
    var_dump($thing->getData());
}
There are also shortcuts for pulling out the first and last items

var_dump($collection_of_things->getFirstItem()->getData());
var_dump($collection_of_things->getLastItem()->getData());
Want your Collection data as XML? There's a method for that

var_dump( $collection_of_things->toXml() );
Only want a particular field?

var_dump($collection_of_things->getColumnValues('name'));
The team at Magento have even given us some rudimentary filtering capabilities.

var_dump($collection_of_things->getItemsByColumnValue('name','Spot'));
Neat stuff.

Model Collections
So, this is an interesting exercise, but why do we care?

We care because all of Magento's built in data Collections inherit from this object. 
 * That means if you have, say, a product Collection you can do the same sort of things. 
 * Let's take a look

public function testAction()
{
    $collection_of_products = Mage::getModel('catalog/product')->getCollection();
    var_dump($collection_of_products->getFirstItem()->getData());
}
Most Magento Model objects have a method named getCollection which will return a collection that, 
 * by default, is initialized to return every Object of that type in the system.

A Quick Note: Magento's Data Collections contain a lot of complicated logic that handles when 
 * to use an index or cache, as well as the logic for the EAV entity system. Successive method 
 * calls to the same Collection over its life can often result in unexpected behavior. 
 * Because of that, all the of the following examples are wrapped in a single method action. 
 * I'd recommend doing the same while you're experimenting. Also, XDebug's var_dump is a godsend 
 * when working with Magento Objects and Collections, as it will (usually) intelligently short 
 * circuit showing hugely recursive Objects, but still display a useful representation of the 
 * Object structure to you.

The products Collection, as well as many other Magento Collections, also have the 
 * Varien_Data_Collection_Db class in their ancestor chain. This gives us a lot of useful methods. 
 * For example, if you want to see the select statement your Collection is using

public function testAction()
{
    $collection_of_products = Mage::getModel('catalog/product')->getCollection();
    var_dump($collection_of_products->getSelect()); //might cause a segmentation fault
}
The output of the above will be

object(Varien_Db_Select)[94]
  protected '_bind' =>
    array
      empty
  protected '_adapter' =>
...
Whoops! Since Magento is using the Zend database abstraction layer, your Select is also an Object. 
 * Let's see that as a more useful string.

public function testAction()
{
    $collection_of_products = Mage::getModel('catalog/product')->getCollection();
    //var_dump($collection_of_products->getSelect()); //might cause a segmentation fault
    var_dump(
        (string) $collection_of_products->getSelect()
    );
}
Sometimes this is going to result in a simple select

'SELECT `e`.* FROM `catalog_product_entity` AS `e`'
Other times, something a bit more complex

string 'SELECT `e`.*, `price_index`.`price`, `price_index`.`final_price`, IF(`price_index`.`tier_price`, 
 * LEAST(`price_index`.`min_price`, `price_index`.`tier_price`), `price_index`.`min_price`) AS `minimal_price`, 
 * `price_index`.`min_price`, `price_index`.`max_price`, 
 * `price_index`.`tier_price` FROM `catalog_product_entity` AS `e`
INNER JOIN `catalog_product_index_price` AS `price_index` 
 * ON price_index.entity_id = e.entity_id AND price_index.website_id = '1' 
 * AND price_index.customer_group_id = 0'
The discrepancy depends on which attributes you're selecting, as well as the 
 * aforementioned indexing and cache. If you've been following along with the other articles in this series, 
 * you know that many Magento models (including the Product Model) use an EAV system. By default, 
 * a EAV Collection will not include all of an Object's attributes. You can add them all by using the 
 * addAttributeToSelect method

$collection_of_products = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('*');  //the asterisk is like a SQL SELECT * FROM ...
Or, you can add just one

//or just one
$collection_of_products = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('meta_title');
or chain together several

//or just one
$collection_of_products = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('meta_title')
    ->addAttributeToSelect('price');
Lazy Loading
One thing that will trip up PHP developers new to Magento's ORM system is when Magento makes 
 * its database calls. When you're writing literal SQL, 
 * or even when you're using a basic ORM system, 
 * SQL calls are often made immediately when instantiating an Object.

$model = new Customer();
//SQL Calls being made to Populate the Object
echo 'Done'; //execution continues
Magento doesn't work that way. Instead, the concept of Lazy Loading is used. 
 * In simplified terms, Lazy loading means that no SQL calls are made until 
 * the client-programmer needs to access the data. That means when you do something something 
 * like this

$collection_of_products = Mage::getModel('catalog/product')
    ->getCollection();
Magento actually hasn't gone out to the database yet. You can safely add attributes later

$collection_of_products = Mage::getModel('catalog/product')
    ->getCollection();
$collection_of_products->addAttributeToSelect('meta_title');
and not have to worry that Magento is making a database query each time a new attribute is added. 
 * The database query will not be made until you attempt to access an item in the Collection.

In general, try not to worry too much about the implementation details in your day to day work. 
 * It's good to know that there's s SQL backend and Magento is doing SQLy things, 
 * but when you're coding up a feature try to forget about it, 
 * and just treat the objects as block boxes that do what you need.

Filtering Database Collections
The most important method on a database Collection is addFieldToFilter. 
 * This adds your WHERE clauses to the SQL query being used behind the scenes. 
 * Consider this bit of code, run against the sample data database 
 * (substitute your own SKU is you're using a different set of product data)

public function testAction()
{
    $collection_of_products = Mage::getModel('catalog/product')
        ->getCollection();
    $collection_of_products->addFieldToFilter('sku','n2610');

    //another neat thing about collections is you can pass them into the count      
 * //function.  More PHP5 powered goodness
    echo "Our collection now has " . count($collection_of_products) . ' item(s)';
    var_dump($collection_of_products->getFirstItem()->getData());
}
The first parameter of addFieldToFilter is the attribute you wish to filter by. 
 * The second is the value you're looking for. Here's we're adding a sku filter for the value n2610.

The second parameter can also be used to specify the type of filtering you want to do. 
 * This is where things get a little complicated, and worth going into with a little more depth.

So by default, the following

$collection_of_products->addFieldToFilter('sku','n2610');
is (essentially) equivalent to

WHERE sku = "n2610"
Take a look for yourself. Running the following

public function testAction()
{
    var_dump(
    (string)
    Mage::getModel('catalog/product')
    ->getCollection()
    ->addFieldToFilter('sku','n2610')
    ->getSelect());
}
will yield

SELECT `e`.* FROM `catalog_product_entity` AS `e` WHERE (e.sku = 'n2610')'
Keep in mind, this can get complicated fast if you're using an EAV attribute. Add an attribute

var_dump(
    (string)
    Mage::getModel('catalog/product')
        ->getCollection()
        ->addAttributeToSelect('*')
        ->addFieldToFilter('meta_title','my title')
        ->getSelect()
);
and the query gets gnarly.

SELECT `e`.*, IF(_table_meta_title.value_id>0, _table_meta_title.value, _table_meta_title_default.value) 
 * AS `meta_title`
FROM `catalog_product_entity` AS `e`
INNER JOIN `catalog_product_entity_varchar` AS `_table_meta_title_default`
    ON (_table_meta_title_default.entity_id = e.entity_id) AND (_table_meta_title_default.attribute_id='103')
    AND _table_meta_title_default.store_id=0
LEFT JOIN `catalog_product_entity_varchar` AS `_table_meta_title`
    ON (_table_meta_title.entity_id = e.entity_id) AND (_table_meta_title.attribute_id='103')
    AND (_table_meta_title.store_id='1')
WHERE (IF(_table_meta_title.value_id>0, _table_meta_title.value, _table_meta_title_default.value) = 'my title')
Not to belabor the point, but try not to think too much about the SQL if you're on deadline.

Other Comparison Operators
I'm sure you're wondering "what if I want something other than an equals by query"? Not equal, 
 * greater than, less than, etc. The addFieldToFilter method's second parameter has you covered there as well. 
 * It supports an alternate syntax where, instead of passing in a string, you pass in a single element Array.

The key of this array is the type of comparison you want to make. 
 * The value associated with that key is the value you want to filter by. 
 * Let's redo the above filter, but with this explicit syntax

public function testAction()
{
    var_dump(
        (string)
        Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('sku', array('eq'=>'n2610'))
            ->getSelect()
    );
}
Calling out our filter

addFieldToFilter('sku',array('eq'=>'n2610'))
As you can see, the second parameter is a PHP Array. Its key is eq, which stands for equals. 
 * The value for this key is n2610, which is the value we're filtering on.

Magento has a number of these english language like filters that will bring a 
 * tear of remembrance (and perhaps pain) to any old perl developers in the audience.

Listed below are all the filters, along with an example of their SQL equivalents.

array("eq"=>'n2610')
WHERE (e.sku = 'n2610')

array("neq"=>'n2610')
WHERE (e.sku != 'n2610')

array("like"=>'n2610')
WHERE (e.sku like 'n2610')

array("nlike"=>'n2610')
WHERE (e.sku not like 'n2610')

array("is"=>'n2610')
WHERE (e.sku is 'n2610')

array("in"=>array('n2610'))
WHERE (e.sku in ('n2610'))

array("nin"=>array('n2610'))
WHERE (e.sku not in ('n2610'))

array("notnull"=>true)
WHERE (e.sku is NOT NULL)

array("null"=>true)
WHERE (e.sku is NULL)

array("gt"=>'n2610')
WHERE (e.sku > 'n2610')

array("lt"=>'n2610')
WHERE (e.sku < 'n2610')

array("gteq"=>'n2610')
WHERE (e.sku >= 'n2610')

array("moreq"=>'n2610') //a weird, second way to do greater than equal
WHERE (e.sku >= 'n2610')

array("lteq"=>'n2610')
WHERE (e.sku <= 'n2610')

array("finset"=>array('n2610'))
WHERE (find_in_set('n2610',e.sku))

array('from'=>'10','to'=>'20')
WHERE e.sku >= '10' and e.sku <= '20'
Most of these are self explanatory, but a few deserve a special callout

in, nin, find_in_set
The in, nin and finset conditionals allow you to pass in an Array of values. That is, 
 * the value portion of your filter array is itself allowed to be an array.

array("in"=>array('n2610','ABC123')
WHERE (e.sku in ('n2610','ABC123'))
notnull, null
The keyword NULL is special in most flavors of SQL. 
 * It typically won't play nice with the standard equality (=) operator. 
 * Specifying notnull or null as your filter type will get you the correct syntax for a 
 * NULL comparison while ignoring whatever value you pass in

array("notnull"=>true)
WHERE (e.sku is NOT NULL)
from - to filter
This is another special format that breaks the standard rule. Instead of a single element array, 
 * you specify a two element array. One element has the key from, the other element has the key to. 
 * As the keys indicated, this filter allows you to construct a from/to range 
 * without having to worry about greater than and less than symbols

public function testAction
{
    var_dump(
        (string)
        Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('price',array('from'=>'10','to'=>'20'))
            ->getSelect()
    );
}
The above yields

WHERE (_table_price.value >= '10' and _table_price.value <= '20')'
AND or OR, or is that OR and AND?
Finally, we come to the boolean operators. It's the rare moment where we're only 
 * filtering by one attribute. Fortunately, Magento's Collections have us covered. 
 * You can chain together multiple calls to addFieldToFilter to get a number of "AND" queries.

function testAction()
{
    echo
        (string)
        Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('sku',array('like'=>'a%'))
            ->addFieldToFilter('sku',array('like'=>'b%'))
            ->getSelect();
}
By chaining together multiple calls as above, we'll produce a where 
 * clause that looks something like the the following

WHERE (e.sku like 'a%') AND (e.sku like 'b%')
To those of you that just raised your hand, yes, the above example would always return 0 records. 
 * No sku can begin with BOTH an a and a b. What we probably want here is an OR query. 
 * This brings us to another confusing aspect of addFieldToFilter's second parameter.

If you want to build an OR query, you need to pass an Array of filter Arrays in as the second parameter. 
 * I find it's best to assign your individual filter Arrays to variables

public function testAction()
{
    $filter_a = array('like'=>'a%');
    $filter_b = array('like'=>'b%');
}
and then assign an array of all my filter variables

public function testAction()
{
    $filter_a = array('like'=>'a%');
    $filter_b = array('like'=>'b%');
    echo
        (string)
        Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('sku', array($filter_a, $filter_b))
            ->getSelect();
}
In the interest of being explicit, here's the aforementioned array of filter arrays.

array($filter_a, $filter_b)
This will gives us a WHERE clause that looks something like the following

WHERE (((e.sku like 'a%') or (e.sku like 'b%')))
Wrap Up
You're now a Magento developer walking around with some serious firepower. 
 * Without having to write a single line of SQL you now know how to query Magento for 
 * any Model your store or application might need.
 
  
 */
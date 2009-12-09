<?php

// Tento modul vyžaduje úpravu Nette: http://forum.nettephp.com/cs/2233-snippet-a-jeho-druhy-parametr

class DefaultPresenter extends BasePresenter {

    /**
     * ID of the row for edit form
     * @var null|int
     * @persistent
     */
    public $id=null;



    /**
     * Returns array of classes persistent parameters. They have public visibility and are non-static.
     * This default implementation detects persistent parameters by annotation @persistent.
     * @return array
     */
    public static function getPersistentParams() {
        return array("id");
    }



    function createComponentTabs($name) {
        $tc = new TabControl($this,$name);
        $tc->mode = TabControl::MODE_LAZY;
        $tc->sortable = true;
        //$tc->jQueryTabsOptions = "{ fx: { height: 'toggle',opacity:'toggle',marginTop:'toggle',marginBottom:'toggle',paddingTop:'toggle',paddingBottom:'toggle'} }";
        //$tc->handlerComponent = $this; // Is automatic

        $t = $tc->addTab("datagrid");
        $t->header = "<i>Data</i>Grid";
        $t->contentFactory = array($this,"createTabDataGrid");
        $t->hasSnippets = true; // Potřeba nastavit u každého tabu, ve kterém budou snippety! Jinak nebude fungovat AJAX! Má stejnou funkci jako @ v šablonách

        $t = $tc->addTab("novy");
        $t->header = "Nový";
        $t->contentFactory = array($this,"createTabNovy");

        $t = $tc->addTab("edit");
        $t->header = "Editovat";
        $t->contentFactory = array($this,"createTabEdit");
        $t->contentRenderer = array($this,"renderTabEdit");

        $t = $tc->addTab("customRenderer");
        $t->header = "Vlastní vykreslení tabu";
        $t->content = "Ahoj, jak se máš Jirko!";
        $t->contentRenderer = array($this,"renderTabCustomRenderer");

        $t = $tc->addTab("help");
        $t->header = "Nápověda";
        $t->contentFactory = array($this,"createTabHelp");

        $t = $tc->addTab("template");
        $t->header = "Ze šablony";
        $t->contentFactory = array($this,"createTabTemplate");
	/*$t->hasSnippets = true;*/

        $t = $tc->addTab("templateWithComponent");
        $t->header = "Ze šablony s komponentou";
        $t->contentFactory = array($this,"createTabTemplateWithComponent");
	$t->hasSnippets = true; // Pokud komponenta umí ajax

	$t = $tc->addTab("InnerTabControl");
	$t->header = "TabControl";
	$t->contentFactory = array($this,"createTabInnerTabControl");
	$t->hasSnippets = true;

        return $tc;
    }







    /* Tab factories */

    function createForm($name,Tab $tab) {
        $form = new AppForm($tab,$name);
        $form->getElementPrototype()->addClass("ajax"); // Zajaxovatění formulářů v jquery.nette.js
        $form->addText("Neco", "Něco")->addRule(Form::FILLED, "Něco je povinné!");
        $form->addTextArea("Popis", "Popis");
        $form->addSubmit("odeslat", "Odeslat");
        $form->onSubmit[] = array($this,"ulozFormular");
        return $form;
    }

    function createTabNovy($name,Tab $tab) {
        $form = $this->createForm($name, $tab);
        $form["odeslat"]->caption = "Přidat";
        return $form;
    }

    function createTabEdit($name,Tab $tab) {
        $form = $this->createForm($name, $tab);
        $form["odeslat"]->caption = "Uložit změny";

        // Přidáme skryté políčko pro přenos Id
        $form->addHidden("Id");

        return $form;
    }

    function renderTabEdit(Tab $tab) {
        $form = $tab->content;
        if($form["Id"]->value != "")
            $form->render();
        else
            echo "Nejprve vyberte položku v DataGridu kliknutím na ikonku editace.";
    }

    function ulozFormular(AppForm $form) {
        $values = $form->values;
        $this->flashMessage("Data přijatá z formuláře:<br>".Debug::dump($values, TRUE), "info");

        $form->setValues(array(), TRUE); // Empty the form

        // Parent of AppForm is Tab
        $tab = $form->parent;
        // Parent of Tab is TabControl
        $tabControl = $tab->parent;

        $tab->redraw(); // Překresli - vymazali jsme formulář

        // Přepneme uživatele zpět na datagrid
        $tabControl->select("datagrid");
    }





    function createTabDataGrid($name,Tab $tab) {
        $model = new DatagridModel('customers');
        $grid = new DataGrid;

        //$translator = new Translator(Environment::expand('%templatesDir%/customersGrid.cs.mo'));
        //$grid->setTranslator($translator);

        $renderer = new DataGridRenderer;
        //$renderer->paginatorFormat = '%input%'; // customize format of paginator
        //$renderer->onCellRender[] = array($this, 'customersGridOnCellRendered');
        $grid->setRenderer($renderer);

        $grid->itemsPerPage = 10; // display 10 rows per page
        $grid->displayedItems = array('all', 10, 20, 50); // items per page selectbox items
        //$grid->rememberState = TRUE; // Nepoužívat s TabControlem - když je více DG tak potom se ty stavy nějak popletou mezi s sebou. Pokud je FALSE funguje vše OK. (stavy se pamatují - persistentní pamerty)
        //$grid->timeout = '+ 7 days'; // change session expiration after 7 days
        $grid->bindDataTable($model->getCustomerAndOrderInfo());
        $grid->multiOrder = FALSE; // order by one column only

        $operations = array('delete' => 'delete', 'deal' => 'deal', 'print' => 'print', 'forward' => 'forward'); // define operations
        // in czech for example: $operations = array('delete' => 'smazat', 'deal' => 'vyřídit', 'print' => 'tisk', 'forward' => 'předat');
        // or you can left translate values by translator adapter
        $callback = array($this, 'handleDemo');
        $grid->allowOperations($operations, $callback, 'customerNumber'); // allows checkboxes to do operations with more rows


        /**** add some columns ****/

        $grid->addColumn('customerName', 'Name');
        $grid->addColumn('contactLastName', 'Surname');
        $grid->addColumn('addressLine1', 'Address')->getHeaderPrototype()->addStyle('width: 180px');
        $grid->addColumn('city', 'City');
        $grid->addColumn('country', 'Country');
        $grid->addColumn('postalCode', 'Postal code');
        $caption = Html::el('span')->setText('O')->title('Has orders?')->class('link');
        $grid->addCheckboxColumn('orders', $caption)->getHeaderPrototype()->addStyle('text-align: center');
        $grid->addDateColumn('orderDate', 'Date', '%m/%d/%Y'); // czech format: '%d.%m.%Y'
        $grid->addColumn('status', 'Status');
        $grid->addNumericColumn('creditLimit', 'Credit', 0);


        /**** add some filters ****/

        $grid['customerName']->addFilter();
        $grid['contactLastName']->addFilter();
        $grid['addressLine1']->addFilter();
        $grid['city']->addSelectboxFilter()->translateItems(FALSE);
        $grid['country']->addSelectboxFilter()->translateItems(FALSE);
        $grid['postalCode']->addFilter();
        $grid['orders']->addSelectboxFilter(array('0' => "Don't have", '1' => "Have"), TRUE);
        $grid['orderDate']->addDateFilter();
        $grid['status']->addSelectboxFilter(array('Cancelled' => 'Cancelled', 'Resolved' => 'Resolved', 'Shipped' => 'Shipped', 'NULL' => "Without orders"));
        $grid['creditLimit']->addFilter();


        /**** default sorting and filtering ****/

        $grid['city']->addDefaultSorting('asc');
        $grid['contactLastName']->addDefaultSorting('asc');
        $grid['orders']->addDefaultFiltering(TRUE);
        $grid['country']->addDefaultFiltering('USA');

        /**** column content affecting ****/

        // by css styling
        $grid['orderDate']->getCellPrototype()->addStyle('text-align: center');
        $grid['status']->getHeaderPrototype()->addStyle('width: 60px');
        $grid['addressLine1']->getHeaderPrototype()->addStyle('width: 150px');
        $grid['city']->getHeaderPrototype()->addStyle('width: 90px');

        // by replacement of given pattern
        $el = Html::el('span')->addStyle('margin: 0 auto');
        $grid['status']->replacement['Shipped'] = clone $el->class("icon icon-shipped")->title("Shipped");
        $grid['status']->replacement['Resolved'] = clone $el->class("icon icon-resolved")->title("Resolved");
        $grid['status']->replacement['Cancelled'] = clone $el->class("icon icon-cancelled")->title("Cancelled");
        $grid['status']->replacement[''] = clone $el->class("icon icon-no-orders")->title("Without orders");

        // by callback(s)
        $grid['creditLimit']->formatCallback[] = 'Helpers::currency';


        /**** add some actions ****/

        $grid->addActionColumn('Actions')->getHeaderPrototype()->addStyle('width: 98px');
        $icon = Html::el('span');
        $grid->addAction('Copy', 'demo!', clone $icon->class('icon icon-copy'),TRUE);
        $grid->addAction('Detail', 'demo!', clone $icon->class('icon icon-detail'),TRUE);
        $grid->addAction('Edit', 'edit!', clone $icon->class('icon icon-edit'),TRUE);
        $grid->addAction('Delete', 'demo!', clone $icon->class('icon icon-del'),TRUE);

        return $grid;
    }

	    /* Obslužné handlery k datagridu */

	    function handleEdit($customerNumber) {
		$this->flashMessage("Přepínám vás do editačního formuláře.", "info");
		/* $this["tabs"] - class TabControl (objekt skupiny Tabů)
		 * $this["tabs"]["form2"] - class Tab (objekt jednoho tabu)
		 * $this["tabs"]["form2"]->content - class AppForm (obsah tabu)
		 */
		$this["tabs"]["edit"]->content["Id"]->value = $customerNumber;
		$this["tabs"]->select("edit"); // Nikam nepřesměrovává, pouze přepne tab. (s JS i bez něj)
	    }

	    function handleDemo() {
		$this->flashMessage("Toto je pouze DEMO aplikace!", "info");
	    }





    /* ### Tab CustomRenderer ### */
    function renderTabCustomRenderer(Tab $tab) {
        $content = $tab->content;
        echo str_replace("Jirko","Franto",$content);
        echo "<hr><i>Tento tab byl vykreslen vlastním rendererem.</i>";
    }





    /* ### Tab Help ### */
    function createTabHelp($name,Tab $tab) {
        return "V tomto příkladu můžete vyzkoušet, jak TabControl funguje. Můžete se v tabech přepínat, jak AJAXově nebo úplně bez podpory JavaScriptu. Pokud máte zapnutý JavaScript, můžete tabům i změnit pořadí přetažením. (pořadí se uloží do session - toto chování můžete jednoduše změnit pomocí callbacku) K chodu této knihovny je potřeba nejnovější Nette Framework, jQuery, jQuery UI Core, jQuery UI Tabs, jQuery UI Sortable (pro podporu přesouvání).";
    }





    /* ### Tab Template ### */

    function createTabTemplate($name, Tab $tab) {
        $templatePersons = $this->createTemplate(); // Zde bych mohl volat i new Template; (nepotřebuji v template mít $control, $presenter a podobně)
        $templatePersons->setFile(Environment::expand("%appDir%/templates/Default/Tabs/sablona.phtml"));
        return $templatePersons;
    }





    /* ### Tab TabControl ### */

    function createTabInnerTabControl($name, Tab $tab) {
        $tc = new TabControl($tab,$name);
        $tc->mode = TabControl::MODE_LAZY;
        $tc->sortable = true;
        //$tc->jQueryTabsOptions = "{ fx: { height: 'toggle',opacity:'toggle',marginTop:'toggle',marginBottom:'toggle',paddingTop:'toggle',paddingBottom:'toggle'} }";
        //$tc->handlerComponent = $this; // Is automatic

	$t = $tc->addTab("tab1");
        $t->header = "Tab 1";
        $t->contentFactory = array($this,"createInnerTab1");

        $t = $tc->addTab("tab2");
        $t->header = "tab 2";
        $t->contentFactory = array($this,"createInnerTab2");

        return $tc;
    }

	    function createInnerTab1($name,Tab $tab) {
		$template = $this->createTemplate();
		$template->setFile(Environment::expand("%appDir%/templates/Default/InnerTabs/Tab1.phtml"));
		return $template;
	    }

	    function createInnerTab2($name,Tab $tab) {
		$template = $this->createTemplate();
		$template->setFile(Environment::expand("%appDir%/templates/Default/InnerTabs/Tab2.phtml"));
		return $template;
	    }





    /* ### Tab Šablona s komponentou ### */

    function createTabTemplateWithComponent($name, Tab $tab) {
	// Vytvoří šablonu v presenteru, tzn. $control v šawbloně bude ukazovat na Presenter.
	// Sice to funguje, ale myslím si, že to není úplně správně,
	// proto prosím někoho nette-zkušenějšího, aby mi poradil, jak to udělat elegantněji
        $template = $this->createTemplate();
        $template->setFile(Environment::expand("%appDir%/templates/Default/Tabs/sablonaSKomponentou.phtml"));
        return $template;
    }
    
	    function createComponentAnotherTabControl($name) {
		$tc = new TabControl($this,$name);
		$tc->mode = TabControl::MODE_LAZY;
		$tc->sortable = true;

		// Obsah tabů si převezmene z příkladu tabu TabControl
		$t = $tc->addTab("tab1");
		$t->header = "Tab 1";
		$t->contentFactory = array($this,"createInnerTab1");

		$t = $tc->addTab("tab2");
		$t->header = "tab 2";
		$t->contentFactory = array($this,"createInnerTab2");

		return $tc;
	    }





    /* ### Handlery k odkazů v sekci Externí ovládání TabControlu ### */

    function handlePrepniNa($tab) {
        $this["tabs"]->select($tab);
        if(!$this->isAjax())
            $this->redirect("this");
    }



    function handlePrekresli($tab) {
        $this["tabs"]->redraw($tab);
    }
    
}

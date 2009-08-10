<?php

// Tento modul vyžaduje úpravu Nette: http://forum.nettephp.com/cs/2233-snippet-a-jeho-druhy-parametr

class DefaultPresenter extends BasePresenter
{

    // Dokud se neopraví: http://forum.nettephp.com/cs/2301-dalsi-nekonecny-cyklus-canonicalize
    public $autoCanonicalize = FALSE;

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
    public static function getPersistentParams()
    {
        return array("id");
    }

    function createComponentTabs($name){
        $tc = new TabControl($this,$name);
        $tc->mode = TabControl::MODE_LAZY;
        $tc->sortable = true;
        //$tc->jQueryTabsOptions = "{ fx: { height: 'toggle',opacity:'toggle',marginTop:'toggle',marginBottom:'toggle',paddingTop:'toggle',paddingBottom:'toggle'} }";
        //$tc->handlerComponent = $this; // Is automatic

        $t = $tc->addTab("datagrid");
            $t->header = "DataGrid";
            $t->contentFactory = array($this,"createTabDataGrid");
            $t->hasSnippets = true; // Potřeba nastavit u každého tabu, ve kterém budou snippety! Jinak nebude fungovat AJAX! Mástejnou funkci jako @ v šablonách

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

        return $tc;
    }



    /* Tab factories */

    /**
     * Creates form
     * @param string $name
     * @param Tab $tab
     * @return AppForm
     */
    function createForm($name,Tab $tab){
        $form = new AppForm($tab,$name);
        $form->getElementPrototype()->addClass("ajax"); // Zajaxovatění formulářů v jquery.nette.js
        $form->addText("Neco", "Něco")->addRule(Form::FILLED, "Něco je povinné!");
        $form->addTextArea("Popis", "Popis");
        $form->addSubmit("odeslat", "Odeslat");
        $form->onSubmit[] = array($this,"ulozFormular");
        return $form;
    }

    function createTabNovy($name,Tab $tab){
        $form = $this->createForm($name, $tab);
        $form["odeslat"]->caption = "Přidat";
        return $form;
    }

    function createTabEdit($name,Tab $tab){
        $form = $this->createForm($name, $tab);
        $form["odeslat"]->caption = "Uložit změny";

        // Přidáme skryté políčko pro přenos Id
        $form->addHidden("Id");

        return $form;
    }

    function renderTabEdit(Tab $tab){
        $form = $tab->content;
        if($form["Id"]->value != "")
            $form->render();
        else
            echo "Nejprve vyberte položku v DataGridu kliknutím na ikonku editace.";
    }

    function ulozFormular(AppForm $form){
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




    function createTabDataGrid($name,Tab $tab){
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

    function handleEdit($customerNumber){
        $this->flashMessage("Přepínám vás do editačního formuláře.", "info");
        /* $this["tabs"] - class TabControl (objekt skupiny Tabů)
         * $this["tabs"]["form2"] - class Tab (objekt jednoho tabu)
         * $this["tabs"]["form2"]->content - class AppForm (obsah tabu)
         */
        $this["tabs"]["edit"]->content["Id"]->value = $customerNumber;
        $this["tabs"]->select("edit"); // Nikam nepřesměrovává, pouze přepne tab. (s JS i bez něj)
    }

    function handleDemo(){
        $this->flashMessage("Toto je pouze DEMO aplikace!", "info");
    }



    /* ### Tab CustomRenderer ### */
    function renderTabCustomRenderer(Tab $tab){
        $content = $tab->content;
        echo str_replace("Jirko","Franto",$content);
        echo "<hr><i>Tento tab byl vykreslen vlastním rendererem.</i>";
    }

    /* ### Tab Help ### */
   function createTabHelp($name,Tab $tab){
       return "V tomto příkladu můžete vyzkoušet, jak TabControl funguje. Můžete se v tabech přepínat, jak AJAXově nebo úplně bez podpory JavaScriptu. Pokud máte zapnutý JavaScript, můžete tabům i změnit pořadí přetažením. (pořadí se uloží do session - toto chování můžete jednoduše změnit pomocí callbacku) K chodu této knihovny je potřeba nejnovější Nette Framework, jQuery, jQuery UI Core, jQuery UI Tabs, jQuery UI Sortable (pro podporu přesouvání).";
   }



    /* ### Handlery k odkazů v sekci Externí ovládání TabControlu ### */
    function handleJdiNaTab($tab){
        $this["tabs"]->select($tab);
    }

    function handleJdiNaTabCanonicky($tab){
        $this["tabs"]->select($tab);
        if(!$this->isAjax())
            $this->redirect("this");
    }

    function handlePrekresliTab($tab){
        $this["tabs"]->redraw($tab);
    }
}

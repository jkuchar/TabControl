<?php

/* Vyžaduje nette >= rev. 465 */

/**
 * TabControl class
 *
 * show off @property, @property-read, @property-write
 * @property-read   array           $tabsForDraw    Tabs for redraw
 * @property-read   ArrayIterator   $tabs           Tabs
 */
class TabControl extends Control
{

    /**************************************************************************/
    /*                               Variables                                */
    /**************************************************************************/

    /**
     * Container of tabs
     * @var Html
     */
    public $tabContainer;

    /**
     * Active tab name
     * @var string
     * @persistent
     */
    public $activeTab;

    /**
     * SELECT: First tab (default)
     */
    const SELECT_FIRST = -1;

    /**
     * Redraw all
     */
    const REDRAW_ALL = -1;

    /**
     * Redraw current
     */
    const REDRAW_CURRENT = null;

    /**
     * Mode: do not use ajax
     */
    const MODE_NO_AJAX=0;

    /**
     * Mode: preload all tabs when page loads
     */
    const MODE_PRELOAD=1;

    /**
     * Mode: load tab when tab is clicked
     */
    const MODE_LAZY=2;

    /**
     * Reload tab content every time, when tab is clicked
     */
    const MODE_RELOAD=3;

    /**
     * Mode: detects the best mode
     */
    const MODE_AUTO=null;

    /**
     * Mode (see MODE_xxx constants)
     * @var int
     */
    public $mode=self::MODE_AUTO;

    /**
     * Text what is displayed while loading content of the tab
     * @var string|null
     */
    public $loaderText="Načítám...";

    /**
     * jQuery Tabs options
     * @var String
     * @link http://jqueryui.com/demos/tabs/#options
     */
    public $jQueryTabsOptions = "{}";

    /**
     * Component where you have handlers
     * @var PresenterComponent
     */
    public $handlerComponent;

    /**
     * Internal buffer for JavaScript
     * @var array
     */
    private $JavaScript = array();

    /**
     * Tab object identificator (JavaScript)
     * @var string
     */
    public $DOMtabsID;

    /**
     * Tabs to be drawed
     * @var array|null
     */
    private $tabsForDraw;

    static function getPersistentParams(){
        return array("activeTab");
    }

    public function  __construct($parent, $name) {
        parent::__construct($parent, $name);
        $this->tabContainer = Html::el("div")->class("tabs");
        $this->handlerComponent = $parent;
        $this->DOMtabsID = $this->getSnippetId("jQueryUITabs");
    }

    /**************************************************************************/
    /*                            Main methods                                */
    /**************************************************************************/

    /**
     * Adds tab
     * @param string $name
     * @return Tab
     */
    public function addTab($name){
        // Pokud není aktuální záložka -> nastavíme ji
        if(count($this->tabs)===0 and $this->activeTab===null)
            $this->activeTab = $name;

        return new Tab($this,$name); // It will be registered automaticly
    }

    /**
     * Render tab control
     */
    public function render(){
        // Mode: auto
        if($this->mode===self::MODE_AUTO){
            if(count($this->tabs)<=3)
                $this->mode = self::MODE_PRELOAD; // Málo tabů -> načtem vše dopředu
            else
                $this->mode = self::MODE_LAZY; // Hodně tabů, načteme to, až to bude potřeba
        }

        // Pokud není nic vybráno - nastavíme první položku
        // -> přesunuto do addTab

        if(!isSet($this->tabs[$this->activeTab]))
            throw new InvalidStateException("Active tab is not registered!");

        if($this->presenter->isAjax())
        foreach($this->tabs AS $tab){
            if($tab->hasSnippets === true){
                $this->redraw($tab->name,FALSE);
            }
        }

        // Pokud je NEajaxový požadavek, tak se bude renderovat pouze aktivní tab
        if(!$this->presenter->isAjax())
            $this->tabsForDraw = array($this->activeTab=>true);

        $template = $this->createTemplate();
        $template->registerFilter('Nette\Templates\CurlyBracketsFilter::invoke');
        $template->setFile(DIRNAME(__FILE__)."/TabControl.phtml");
        $template->activeTab = $this->tabs[$this->activeTab];
        $template->render();
    }

    /**
     * Selects tab
     * @param string $tab Tab name
     * @param bool   $redirect C
     * @return TabControl
     */
    function select($tab){
        if($tab===self::SELECT_FIRST){
            reset($this->tabs);
            if(($firstTab = current($this->tabs))===false)
                throw new InvalidStateException("There is no tabs in tabControl!");
            $tab = $firstTab->name;
        }
        if($this->tabs[(string)$tab] instanceof Tab){
            $this->activeTab  = $tab;
            $this->javaScript = "tabs.tabs('select','".$this->getSnippetId($tab)."')";
        }
        $this->redraw(self::REDRAW_CURRENT);
        return $this;
    }

    /**
     * Redraws tab
     * @param mixed $tab Accepts constants TabControl::REDRAW_CURRENT and TabControl::REDRAW_ALL or tab name
     * @param bool $invalidate (internal) Invalidate tab
     * @return TabControl
     */
    function redraw($tab=self::REDRAW_CURRENT,$invalidate=true){
        if($tab===self::REDRAW_CURRENT)
            $tab = $this->activeTab;
        if($invalidate === true){
            if($tab === self::REDRAW_ALL)
                $this->invalidateControl();
            else
                $this->invalidateControl($tab);
        }

        $this->tabsForDraw[$tab]=true;

        return $this;
    }

    /**************************************************************************/
    /*                        Getters and setters                             */
    /**************************************************************************/

    /**
     * Tabs for redraw
     * @return array
     */
    function getTabsForDraw() {
        return $this->tabsForDraw;
    }



    /**
     * Returns sippets of JavaScript code
     * @return array
     */
    function getJavaScript(){
        return $this->JavaScript;
    }
    
    /**
     * Adds line of JavaScript code
     * @param string $code
     * @return TabControl
     */
    function setJavaScript($code){
        $this->invalidateControl("JavaScript");
        $this->JavaScript[] = $code;
        return $this;
    }



    /**
     * Returns Tab object
     * @param string $tab Tab name
     * @return Tab
     */
    function getTab($tab){
        return $this->tabs[$tab];
    }



    /**
     * Returns all registered tabs
     * @return ArrayIterator
     */
    function getTabs(){
        return $this->components;
    }

    /**************************************************************************/
    /*                              Handlers                                  */
    /**************************************************************************/

/*
    function handlePreload() {
        //Debug::fireLog($this->activeTab);
        foreach($this->tabs AS $name => $tab){
            if($this->activeTab !== $name)
                $this->redraw($name);
        }
    }
 */
    /**
     * What to do when tab is preloading
     */
    function handlePreload(){
        $this->redraw(self::REDRAW_CURRENT);
    }

    /**
     * (internal) Activates tab
     * Do not call directly!
     */
    function handleActivateTab(){
        $this->select($this->activeTab);
        if(!$this->presenter->isAjax() and $this->isSignalReceiver($this, "activateTab"))
            $this->redirect("this");
    }
    /**************************************************************************/
    /*                         Extension methods                              */
    /**************************************************************************/

    /**
     * Is $component receiver of $signal?
     *
     * @param PresenterComponent $component
     * @param string $signal
     * @return bool
     */
    function isSignalReceiver(PresenterComponent $component,$signal){
        $_signal = $this->presenter->getSignal();
        if($_signal[0]==$component->getUniqueId() and $_signal[1]==$signal)
            return true;
        else
            return false;
    }

    /**************************************************************************/
    /*                              Others                                    */
    /**************************************************************************/

    /**
     * Descendant can override this method to disallow insert a child by throwing an \InvalidStateException.
     * @param  IComponent
     * @return void
     * @throws \InvalidStateException
     */
    protected function validateChildComponent(IComponent $child)
    {
        if(!$child instanceof Tab)
            throw new InvalidStateException("In 'TabControl' you can add only 'Tab's!");
        parent::validateChildComponent($child);
    }
}
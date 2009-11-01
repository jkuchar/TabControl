<?php

abstract class BasePresenter extends /*Nette\Application\*/Presenter {

    protected function startup() {
        $session = Environment::getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        /*if($this->isAjax() and !preg_match("/FirePHP/i",Environment::getHttpRequest()->getHeader("User-agent", ""))) {
            // Když není k dispozici firebug a je to ajaxový požadavek, tak začni logovat, jako kdyby byl požadavek na produkčním serveru, ať se alespoň něco dozvíme
            Debug::enable(Debug::PRODUCTION); // V PHP 5.3 pád Apache
        }*/
        parent::startup();
    }

    protected function createTemplate() {
        $template = parent::createTemplate();
        $template->registerFilter('Nette\Templates\CurlyBracketsFilter::invoke');
        //$template->registerFilter('Nette\Templates\TemplateFilters::netteLinks');
        //$template->registerFilter('Nette\Templates\TemplateFilters::relativeLinks');
        return $template;
    }

    /**
     * Saves the message to template, that can be displayed after redirect.
     * @param  string
     * @param  string
     * @return stdClass
     */
    public function flashMessage($message, $type = 'info') {
        $this->invalidateControl("flashes");
        parent::flashMessage($message, $type);
    }
}
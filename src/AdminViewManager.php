<?php

namespace Erik\AdminManagerImplementation;

use Erik\AdminManager\Contracts\AdminViewManager as ViewManagerContract;
use Illuminate\Support\Collection;

class AdminViewManager implements ViewManagerContract
{

    /**
     * Blade layout that admin views should extend
     * @return string
     */
    public function layout(): string
    {
        //TODO: pull the layout name from config
        return 'admin::layouts.master';
    }

    /**
     * Name of the main content blade section
     * @return string
     */
    public function mainSection(): string
    {
        //TODO: pull main section name from config
        return 'main';
    }

    /**
     * Name of the main widget blade section
     * @return string
     */
    public function widgetSection(): string
    {
        //TODO: pull widget section name from config
        return 'widgets';
    }

    /**
     * Add a stylesheet that the layout should pull in
     * @param string[] ...$url
     * @return $this
     */
    public function addStylesheetUrl(string ...$url)
    {
        // TODO: Implement addStylesheetUrl() method.
        return $this;
    }

    /**
     * Add a javascript that the layout should pull in
     * @param string[] ...$url
     * @return $this
     */
    public function addJavascriptUrl(string ...$url)
    {
        // TODO: Implement addJavascriptUrl() method.
        return $this;
    }

    /**
     * All registered stylesheets for the layout
     * @return Collection
     */
    public function getStylesheetUrls(): Collection
    {
        // TODO: Implement getStylesheetUrls() method.
        return collect();
    }

    /**
     * All registered javascripts for the layout
     * @return Collection
     */
    public function getJavascriptUrls(): Collection
    {
        // TODO: Implement getJavascriptUrls() method.
        return collect();
    }
}
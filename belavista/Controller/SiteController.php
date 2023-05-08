<?php

class SiteController extends Controller {

    public function index() {
        // $this->viewSite('index');
        $login = new LoginController();
        return $login->index();
    }
}
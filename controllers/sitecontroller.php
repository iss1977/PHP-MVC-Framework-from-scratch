<?php

namespace app\controllers;

class SiteController
{

    public function contact()
    {
        return 'Show contact form';
    }


    /**
     * This function is used when we post a form from the contact page.
     */
    public function handleContact()
    {
        return 'Handling submitted data';
    }
}
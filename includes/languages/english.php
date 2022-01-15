<?php

    function lang($phrase){

        static $lang = array(
            'MESSAGE' => 'welcome'
        );

        return $lang[$phrase];
    }



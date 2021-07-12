<?php

    class AlertMessages{

        static function get_prul_alert( $alert ){

            $get_alert = get_option("prul_alerts");

            return $get_alert[$alert];

        }

    }
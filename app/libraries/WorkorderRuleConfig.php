<?php

class WorkorderRuleConfig {

    public const REQUIRES_GRILLE_FINISH = [
        '1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16' //Small Product
        ,'20' //PSM12
        ,'33','34','35','36','37' //BRs
        ,'38','39' //RES1
    ];

    public const DEFAULT_GRILLE_FINISH = [
        '17' => 18, //MB210LP
        '19' => 17  //MB212
    ];

    public const REQUIRE_CONNECTOR_SELECTION = [
        '1','2','3','4','5','6','9','10','13','14'
    ];

    public const DEFAULT_CONNECTORS = [
        'NL4 & PHX' => ['7','8','11','12','15','16'],   //.2 compact range
        'NL8' => ['43','45','52']                       //Triamped fullrange speakers
    ];

    public const REQUIRE_WAVEGUIDE_FINISH = [
        '38','39','40','41','42','43','44','45','46','47','48','49','50','52','53','54','55','56','57'
    ];

    public const REQUIRE_CABINET_FINISH = [
        '1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20',
        '21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39',
        '40','41','43','44','45','46','47','48','51','52','53','54','55','58','60'
        
    ];

}
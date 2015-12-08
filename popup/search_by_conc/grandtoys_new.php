<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 08.12.2015
     * Time: 11:00
     */
    
    $categories = array(
        'Новинки, Хиты, Акция' => array(
            'Новинки'        => 'page_size500?new=1',
            'Хиты'           => 'page_size500?bestseller=1',
            'Акция'          => 'page_size500?offer=1',
            '5 декабря 2015' => 'shop/news/view/296?page_size=500',
            '7 декабря 2015' => 'shop/news/view/301?page_size=500',
            '8 декабря 2015' => 'shop/news/view/303?page_size=500'
        )
    );
    
    $parts = count($categories);
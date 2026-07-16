<?php

class Pagination {
 
        public static function build (int $currentPage, int $totalItems): array {
            //object format: type - str, page - int, current - bool 

            $totalPages = (int) ceil($totalItems / ITEMSPERPAGE);
            $pages = [];

            if($totalPages <= 7) {
                for($i = 1; $i <= $totalPages; $i++) {
                    $current = ($i === $currentPage) ? true : false;
                    $pages[] = (object) [
                        'type' => 'page',
                        'page' => $i,
                        'current' => $current
                    ]; 
                }
            } else {

               $pages[] = (object) [
                    'type' => 'page',
                    'page' => 1,
                    'current' => ($currentPage === 1) ? true : false
                ]; 
                $pages[] = (object) [
                    'type' => 'page',
                    'page' => 2,
                    'current' => ($currentPage === 2) ? true : false 
                ];
                if($currentPage > 2 && $currentPage < $totalPages-1) {
                    $pages[] = (object) [
                        'type' => 'page',
                        'page' => $currentPage-1,
                        'current' => false 
                    ]; 
                
                    $pages[] = (object) [
                        'type' => 'page',
                        'page' => $currentPage,
                        'current' => true 
                    ];
                    $pages[] = (object) [
                        'type' => 'page',
                        'page' => $currentPage+1,
                        'current' => false 
                    ];
                }
                $pages[] = (object) [
                    'type' => 'page',
                    'page' => $totalPages-1,
                    'current' => ($currentPage === $totalPages-1) ? true : false 
                ];
                $pages[] = (object) [
                    'type' => 'page',
                    'page' => $totalPages,
                    'current' => ($currentPage === $totalPages) ? true : false
                ];
            }
            $pages = array_unique($pages, SORT_REGULAR);

            $results = [];
            $previous = [];

            foreach($pages as $page) {

                
                if($previous){
                    if($previous->page+1 !== $page->page) {
                        $results[] = (object) [
                            'type' => 'ellipsis'
                        ];
                    }
                }

                $results[] = $page;

                $previous = $page;
            }

            return $results;
        }


}
?>
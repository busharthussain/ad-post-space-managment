<?php
use Illuminate\Pagination\Paginator;
class Grid
{
    private static $dsn;

    public static function runSql($grid)
    {
        $currentPage = $grid['page'];
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $sql = $grid['query'];
        $gridFields = (isset($grid['gridFields'])) ? $grid['gridFields'] : '';
        $records = $sql->paginate($grid['perPage'])->setPageName('page');
        /*print_r($records->items());
        exit;*/
        $record_item = [];
        if(count($records->items()) > 0){
            foreach ($records->items() as $item){
                if(isset($item->parent_category_name) && !empty($item->parent_category_name)){
                    $item->parent_category_name = _lang($item->parent_category_name);
                }
                if(isset($item->category_name) && !empty($item->category_name)){
                    $item->category_name = _lang($item->category_name);
                }
                        if(isset($item->option) && !empty($item->option)){
                            $item->option = _lang($item->option);
                        }
                        if(isset($item->category) && !empty($item->category)){
                            $item->category = _lang($item->category);
                        }
                if(isset($item->type) && !empty($item->type)){
                    $item->type = _lang($item->type);
                }

                $record_item[] = $item;
            }
        }

        $data = [
            'result' => $record_item,
            'total' => $records->total(),
            'pager' => make_complete_pagination_block($records, 'short'),
            'gridFields' => $gridFields
        ];

        return $data;
    }

    public static function getDSN($dsn_name = 'default', $reBuild = false)
    {
        self::$dsn =  new GridDSN();

        return self::$dsn;
    }

    public static function tokenizeGridFields($fields)
    {
        return "'" . implode ( "', '", array_keys($fields)) . "'";
        return implode(',', array_keys($fields));
    }

    function quote_escape($str) {
       return self::getDSN()->escape($str);
    }
}
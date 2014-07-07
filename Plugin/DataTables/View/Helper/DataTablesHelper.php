<?php

App::uses('AppHelper', 'View/Helper');

class DataTablesHelper extends AppHelper {

    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->view = $view;
    }

    public function getColumnHeadings($view='default'){
        return array_keys($this->settings[$view]['dataTable']['columns']);
    }

    public function render($recordCallback=false,$view='default'){

        $outputData = $this->flattenData($view);
        if(is_callable($recordCallback)){

            $outputDataLen = count($outputData);
            $rawData = $this->settings[$view]['dataTable']['data'];
    
            $newOutputData = array();
            for($x=0;$x<$outputDataLen;$x++)
                $newOutputData[$x] = call_user_func($recordCallback,$this->view,$outputData[$x],$rawData[$x]);
            
            $outputData = $newOutputData;
        }

        return json_encode(array(
            'sEcho' => $this->settings[$view]['dataTable']['echo'],
            'iTotalRecords' => $this->settings[$view]['dataTable']['unfilteredCount'],
            'iTotalDisplayRecords' => $this->settings[$view]['dataTable']['filteredCount'],
            'aaData' => $outputData
        ));
    }

    protected function flattenData($view='default'){

        $data = $this->settings[$view]['dataTable']['data'];
        $columns = $this->settings[$view]['dataTable']['columns'];

        $flattenedResults = array();
        foreach($data as $row){
            $flattenedResult = array();
            foreach($columns as $column){
                if(!isset($row[$column['model']]) || !isset($row[$column['model']][$column['column']]))
                    $flattenedResult[] = null;
                else
                    $flattenedResult[] = $row[$column['model']][$column['column']];
            }
            $flattenedResults[] = $flattenedResult;
        }
        return $flattenedResults; 
    }

    /**
     * Generates a Datatable component (HTML)
     *
     * @param string $tableElementID Table HTML element id
     * @param string $tableTitle Tabel title
     * @param string[] $tableColumns Table column headings
     * @param mixed $tableData If string, data-src attribute value else two dimensional array containing table data
     * @param string $ctaElement Call-to-action a element
     * @return string HTML table
     */
    public function table($tableElementID, $tableColumns, $tableData, $dataTableOptions=array()) {

        //Determine data source
        $loadDataViaAjax = false;
        $tableDataSrc = "";
        $tableValues = array();
        if(is_array($tableData)){
            $tableValues = $tableData;
        }
        else {
            $loadDataViaAjax = true;
            $tableDataSrc = $tableData;
        }

        $tableAttributes = array(
            'class' => 'table table-striped table-bordered datatable dataTable',
            'data-type' => 'datatable',
            'id' => $tableElementID,
            'data-src' => $tableDataSrc,
            'data-length' => 10,
            'data-empty-table' => 'No data available',
            'data-title' => 'false',
            'data-raw-title' => 'false',
            'data-search' => 'true',
            'data-processing' => 'false',
            'data-refresh' => 'false'
        );

        $tableAttributes = array_merge($tableAttributes,$dataTableOptions);

        if($loadDataViaAjax){
            $src = "<table " . self::buildElementAttributes($tableAttributes,"\"") . ">";
            $src .= "<thead><tr>";
            foreach($tableColumns as $column){
                $src .= "<th>$column</th>";
            }
            $src .= "</tr></thead>";
            $src .= "<tbody>";
            $src .= "<tr><td colspan=\"100\" class=\"blank\">";
            if($tableAttributes['data-processing'] != 'true')
                $src .= "<img class=\"loading\" src=\"/img/loading.gif\" />";
            $src .= "</td></tr>";
            $src .= "</tbody>";
            $src .= "</table>";
            return $src;
        }
        else {
            throw new \Exception('Not implemented');
        }
    }

        /**
      * Converts element attributes stored in an associative array into a string
      *
      * Wrapper for toKeyValueString that's specific to HTML attributes
      *
      * @param mixed[] $attributes Associative array containing element attributes
      * @param string $quote Attribute values will be wrapped in this quote
      * @return string The attributes string
      */
     public static function buildElementAttributes($attributes,$quote="\""){
        return self::toKeyValueString($attributes,'=',' ',$quote);
     }

     /**
      * Converts associate array into a key-value delimited string
      *
      * @param mixed[] $fields Key-value array
      * @param string $keyValueDelimiter The delimiter that separates key and value
      * @param string $fieldDelimiter The delimiter that separates pairs of key-values
      * @param string $escapeValue Each value will be wrapped in this value
      * @return string The key-value string
      */
     public static function toKeyValueString($fields,$keyValueDelimiter='=',$fieldDelimiter=' ',$escapeValue="\""){

        $keyValueArray = array();
        foreach($fields as $k => $v){
            $keyValueArray[] = $k.$keyValueDelimiter.$escapeValue.$v.$escapeValue;
        }

        return implode($fieldDelimiter,$keyValueArray);
     }
}

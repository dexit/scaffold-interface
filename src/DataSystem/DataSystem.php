<?php

namespace Amranidev\ScaffoldInterface\DataSystem;

use Illuminate\Support\Facades\Schema;

class DataSystem
{

    /**
     * Main interface reqeust
     *
     * @var $data
     */
    public $data;

    /**
     * on data specification
     *
     * @var $onData
     */
    public $onData;

    /**
     * Data For views
     *
     * @var $viewData
     */
    public $viewData;

    /**
     * Data for migration
     *
     * @var $migrationData
     */
    public $migrationData;

    /**
     * The forrignKeys and relations
     *
     * @var $foreignKeys
     */
    public $foreignKeys;

    /**
     * Relation Columns
     *
     * @var $relationAttr
     */
    public $relationAttr;

    /**
     * Create DataSystem instance
     *
     * @param Array Data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->migrationData = $this->dataScaffold('migration');
        $this->viewData = $this->dataScaffold('v');
        $this->Tables($data);
        $this->getAttr($data);
    }

    /**
     * Analyse data and attributes
     *
     * @param Array $data
     */
    private function getAttr($data)
    {
        unset($data['TableName']);

        foreach ($this->foreignKeys as $key => $value) {
            $Schema = Schema::getColumnListing($value);
            unset($Schema[0]);
            $this->relationAttr[$value] = $Schema;
        }
    }

    /**
     * Analyse data and get ondata specification
     *
     * @param Array $data
     */
    private function Tables($data)
    {
        $this->onData = [];
        $this->foreignKeys = [];
        unset($data['TableName']);
        $i = 0;
        $j = 0;
        foreach ($data as $key => $value) {
            if ($key == 'tbl' . $i) {
                array_push($this->foreignKeys, $value);
                $i++;
            } elseif ($key == 'on' . $j) {
                array_push($this->onData, $value);
                $j++;
            }

        }
    }

    /**
     * Data for migration and views
     *
     * @param String specification
     *
     * @return Array $request
     */
    public function dataScaffold($spec)
    {
        unset($this->data['TableName']);
        if ($spec == 'migration') {
            $i = 0;
        } else {
            $i = 1;
        }
        $request = [];
        foreach ($this->data as $key => $value) {
            if ($i == 1) {
                $i = 0;
            } elseif ($i == 0) {
                if ($key == 'tbl0' or $key == 'on0') {break;} else {
                    array_push($request, $value);
                    $i = 1;
                }
            }
        }
        return $request;

    }
}

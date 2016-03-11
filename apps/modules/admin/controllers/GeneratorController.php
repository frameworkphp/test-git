<?php
/**
 * GeneratorController.php 09/03/2016
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2016, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Admin\Controllers;


class GeneratorController extends BaseController
{
    protected $listIgnoreTables = [
        TABLE_PREFIX . 'user'
    ];

    public function indexAction()
    {
        $tables = $this->db->listTables();

        $listTable = [];
        foreach ($tables as $tb) {
            if (!$this->isIgnoreTable($tb)) {
                $listTable[] = $tb;
            }
        }

        $this->view->setVars([
            'listTable' => $listTable,
        ]);
        $this->tag->prependTitle('Code generator');
    }

    public function tableAction($table)
    {
        $formParam = [];
        if ($this->db->tableExists($table) && !$this->isIgnoreTable($table)) {

            $className = $this->getClassName($table);
            // Set value default
            $formParam['modelNamespace'] = 'Models';
            $formParam['modelClass'] = $className;
            $formParam['modelExtents'] = 'BaseModel';
            $formParam['tableAlias'] = $this->getTableAlias($table);

            $formParam['ctrIcon'] = '';
            $formParam['ctrNamespace'] = 'Admin';
            $formParam['ctrClass'] = $className . 'Controller';
            $formParam['ctrExtends'] = 'BaseController';
            $formParam['recordPerPage'] = 30;

            // Mapping for Model
            $formParam['property'] = [];
            $formParam['filterable'] = [];
            $formParam['sortable'] = [];
            $formParam['searchable'] = [];
            $formParam['constant'] = [];

            // Mapping for Controller
            $formParam['label'] = [];
            $formParam['indexExclude'] = [];
            $formParam['addEditExclude'] = [];
            $formParam['validatingAddEdit'] = [];

            // After submit
            if ($this->request->isPost()) {
                $formParam = array_merge($formParam, $this->request->getPost());
                //var_dump($formParam);die;
            }

            $mapping = $this->mapping($table, $formParam);

        } else {
            $this->flashSession->error('<strong>Oh snap!</strong> Table not found.');
        }
        $this->view->setVars([
            'table' => $table,
            'formParam' => $formParam,
            'mapping' => $mapping
        ]);
        $this->tag->prependTitle('Code generator');
    }

    /**
     * Mapping fields table
     * @param $table
     * @param $formParam
     * @return array
     */
    private function mapping($table, $formParam)
    {
        // get index column
        $columnIndex = [];
        $indexList = $this->db->describeIndexes($table);
        foreach ($indexList as $indexNames) {
            foreach ($indexNames->getColumns() as $indexCol) {
                $columnIndex[] = $indexCol;
            }
        }

        // Get all columns detail for table
        $tableFields = $this->db->describeColumns($table);

        $mapping = [];
        $prefixColumn = '';
        foreach ($tableFields as $field) {
            $nameColumn = $field->getName();
            if ($field->isPrimary()) {
                if (strpos($nameColumn, '_') !== false) {
                    $prefixColumn = substr($nameColumn, 0, strpos($nameColumn, '_') + 1);
                }
            }

            // Check index column
            $isIndex = false;
            if (in_array($nameColumn, $columnIndex)) {
                $isIndex = true;
            }

            /**
             * Model
             * If submit form then get from form else get from default
             */
            if (isset($formParam['property'][$nameColumn])) {
                $property = $formParam['property'][$nameColumn];
            } else {
                $property = $this->getProperty($nameColumn, $prefixColumn);
            }

            $isFilterable = false;
            if (isset($formParam['filterable'][$nameColumn]) && $formParam['filterable'][$nameColumn] == 'on') {
                $isFilterable = true;
            }

            $isSortable = false;
            if (isset($formParam['sortable'][$nameColumn]) && $formParam['sortable'][$nameColumn] == 'on') {
                $isSortable = true;
            }

            $isSearchable = false;
            if (isset($formParam['searchable'][$nameColumn]) && $formParam['searchable'][$nameColumn] == 'on') {
                $isSearchable = true;
            }

            $constant = '';
            if (isset($formParam['constant'][$nameColumn])) {
                $constant = $formParam['constant'][$nameColumn];
            }

            /**
             * Controller
             * If submit form then get from form else get from default
             */
            if (isset($formParam['label'][$nameColumn])) {
                $label = $formParam['label'][$nameColumn];
            } else {
                $label = $this->getLabel($nameColumn, $prefixColumn);
            }

            $isIndexExclude = false;
            if (isset($formParam['indexExclude'][$nameColumn]) && $formParam['indexExclude'][$nameColumn] == 'on') {
                $isIndexExclude = true;
            }

            $isAddEditExclude = false;
            if (isset($formParam['addEditExclude'][$nameColumn]) && $formParam['addEditExclude'][$nameColumn] == 'on') {
                $isAddEditExclude = true;
            }

            $validatingAddEdit = '';
            if (isset($formParam['validatingAddEdit'][$nameColumn])) {
                $validatingAddEdit = $formParam['validatingAddEdit'][$nameColumn];
            }

            $mapping[] = [
                'name' => $nameColumn,
                'typeName' => $this->getTypeName($field->getType()),
                'isIndex' => $isIndex,
                'size' => $field->getSize(),
                'isNumeric' => $field->isNumeric(),
                'isPrimary' => $field->isPrimary(),
                'label' => $label,
                'property' => $property,
                'isFilterable' => $isFilterable,
                'isSortable' => $isSortable,
                'isSearchable' => $isSearchable,
                'constant' => $constant,
                'isIndexExclude' => $isIndexExclude,
                'isAddEditExclude' => $isAddEditExclude,
                'validatingAddEdit' => $validatingAddEdit
            ];
        }

        return $mapping;
    }

    private function getClassName($table)
    {
        $tableNoPrefix = str_replace(TABLE_PREFIX, '', $table);
        $className = ucwords(str_replace('_', ' ', $tableNoPrefix));
        $className = str_replace(' ', '', $className);

        return $className;
    }

    private function getTypeName($type)
    {
        $constant = new \ReflectionClass('\Phalcon\Db\Column');
        $constants = $constant->getConstants();

        $constantList = [];
        foreach ($constants as $name => $value) {
            if (preg_match('/^TYPE_([A-Z])+$/', $name)) {
                $constantList[$value] = strtolower(str_replace('TYPE_', '', $name));
            }
        }

        return $constantList[$type];
    }

    private function getTableAlias($table)
    {
        $tableNoPrefix = str_replace(TABLE_PREFIX, '', $table);
        $alias = '';
        $words = explode('_', $tableNoPrefix);
        foreach ($words as $w) {
            $alias .= $w[0];
        }

        return $alias;
    }

    private function getProperty($nameColumn, $prefixColumn)
    {
        $property = str_replace($prefixColumn, '', $nameColumn);
        $property = ucwords(str_replace('_', ' ', $property));
        $property = lcfirst(str_replace(' ', '', $property));

        return $property;
    }

    private function getLabel($nameColumn, $prefixColumn)
    {
        $predefinedLabel = [
            'id' => 'ID',
            'u_id' => 'User ID',
            'p_id' => 'Product ID',
        ];

        $label = str_replace($prefixColumn, '', $nameColumn);
        if (isset($predefinedLabel[$label])) {
            $label = $predefinedLabel[$label];
        } else {
            $label = ucwords(str_replace('_', ' ', $label));
        }

        return $label;
    }

    public function isIgnoreTable($table)
    {
        if (in_array($table, $this->listIgnoreTables)) {
            return true;
        }

        return false;
    }

}

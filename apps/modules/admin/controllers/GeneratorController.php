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
        $mapping = [];
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

                $directories['models'] = APP_URL . 'models';
                $directories['modules'] = APP_URL . 'modules/' . strtolower($formParam['ctrNamespace']);
                $directories['controllers'] = $directories['modules'] . '/controllers';
                $directories['views'] = $directories['modules'] . '/views';

                if ($this->validateInput($formParam, $error)) {
                    //var_dump($formParam);die;
                    // $this->generatorModel($table, $formParam, $directories);
                    $this->generatorController($table, $formParam, $directories);
                } else {
                    $this->flash->outputMessage('error', $error);
                }
            }

            // Create mapping with formParam
            $mapping = $this->mapping($table, $formParam);

        } else {
            $this->flash->error('<strong>Oh snap!</strong> Table not found.');
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

    public function validateInput($formParam, &$error)
    {
        // Validate Model
        if ($formParam['modelNamespace'] == '') {
            $error[] = 'Model namespace is required';
        } else {
            $modelPath = APP_URL . $formParam['modelNamespace'];
            if (!file_exists($modelPath)) {
                $error[] = 'Directory contains model not existed. (' . $modelPath . ')';
            } else if (!is_dir($modelPath)) {
                $error[] = 'Model Namespace path is not directory.';
            } else if (!is_writable($modelPath)) {
                $error[] = 'Directory contains model is not writable. Check Permission and CHMOD...';
            }
        }

        if ($formParam['modelClass'] == '') {
            $error[] = 'Model class is required';
        }

        if ($formParam['modelExtents'] == '') {
            $error[] = 'Base class for Model is required';
        } else {
            $baseClassPath = $formParam['modelNamespace'] . DIRECTORY_SEPARATOR . $formParam['modelExtents'];
            if (!class_exists($baseClassPath)) {
                $error[] = 'Base class for Model is not existed. Check again (' . $baseClassPath . ')';
            }
        }

        if ($formParam['tableAlias'] == '') {
            $error[] = 'Table prefix is required';
        }

        foreach ($formParam['property'] as $k => $v) {
            if (strlen($v) == 0) {
                $error[] = 'Model mapping key <code>' . $k . '</code> is required';
            }
        }

        // Validate Controller and template
        if ($formParam['ctrNamespace'] == '') {
            $error[] = 'Controller namespace is required';
        } else {
            $controllerPath = APP_URL . 'modules' . DIRECTORY_SEPARATOR . $formParam['ctrNamespace'];
            if (!file_exists($controllerPath)) {
                $error[] = 'Directory contains controller not existed. (' . $controllerPath . ')';
            } else if (!is_dir($controllerPath)) {
                $error[] = 'Controller namespace path is not directory.';
            } else if (!is_writable($controllerPath)) {
                $error[] = 'Directory contains controller is not writable. Check Permission and CHMOD...';
            }
        }

        if ($formParam['ctrClass'] == '') {
            $error[] = 'Controller class is required';
        }

        if ($formParam['ctrExtends'] == '') {
            $error[] = 'Base class for Controller is required';
        }

        if ((int)$formParam['recordPerPage'] <= 0) {
            $error[] = 'Record per page default is required';
        }

        foreach ($formParam['label'] as $k => $v) {
            if (strlen($v) == 0) {
                $error[] = 'Controller mapping key <code>' . $k . '</code> is required';
            }
        }

        if (!empty($error)) {
            return false;
        }

        return true;
    }

    private function generatorModel($table, $formParam, $directories)
    {
        $search = [];
        $search['{{MODEL_NAME}}'] = $formParam['modelClass'];
        $search['{{DATE}}'] = date('d/m/Y', time());
        $search['{{YEAR}}'] = date('Y', time());
        $search['{{MODEL_NAMESPACE}}'] = $formParam['modelNamespace'];
        $search['{{BASE_MODEL}}'] = $formParam['modelExtents'];
        $search['{{TABLE_NAME}}'] = str_replace(TABLE_PREFIX, '', $table);

        $search['{{FUNCTION_NAME}}'] = $formParam['modelClass'];
        $search['{{RECORD_PER_PAGE}}'] = $formParam['recordPerPage'];
        $search['{{USE_NAMESPACE}}'] = '';
        $search['{{IMAGE_FUNCTION}}'] = '';
        $search['{{DATE_CREATED}}'] = '';
        $search['{{DATE_MODIFIED}}'] = '';


        // Handel property
        $columnDefine = '';
        $tableFields = $this->db->describeColumns($table);
        foreach ($tableFields as $field) {
            $columnDefine .= "    /** \n";
            $nameColumn = $field->getName();

            if ($field->isPrimary()) {
                $columnDefine .= "     * @Primary \n";
                $columnDefine .= "     * @Identity \n";
            }

            if ($field->isNumeric()) {
                $type = 'integer';
            } else {
                $type = 'string';
            }

            if ($field->isNotNull()) {
                $nullAble = 'true';
            } else {
                $nullAble = 'false';
            }

            $columnDefine .= '     * @Column(type="' . $type . '", length=' . $field->getSize() . ', nullable=' . $nullAble . ', column="' . $nameColumn . '")' . "\n";
            $columnDefine .= "     */ \n";
            $columnDefine .= '    public $' . $formParam['property'][$nameColumn];

            if ($field->getDefault() != null) {
                if (is_numeric($field->getDefault())) {
                    $columnDefine .= " = " . $field->getDefault() . ";" . "\n\n";
                } else {
                    $columnDefine .= " = '" . $field->getDefault() . "';" . "\n\n";
                }
            } else {
                $columnDefine .= ';' . "\n\n";
            }

            if ($formParam['property'][$nameColumn] == 'dateCreated') {
                $search['{{DATE_CREATED}}'] = '$this->dateCreated = time();';
            }

            if ($formParam['property'][$nameColumn] == 'dateModified') {
                $search['{{DATE_MODIFIED}}'] = '$this->dateModified = time();';
            }
        }

        // Handel constant
        $templateFunctionConstant = APP_URL . 'modules/admin/views/generator/format/models_constant.volt';

        $constantDefine = '';
        $constantFunction = '';
        foreach ($formParam['constant'] as $key => $value) {
            if ($value != '') {
                $constantDefine .= "    /**\n";
                $constantDefine .= "     * Declare const\n";
                $constantDefine .= "     */\n";
                if (file_exists($templateFunctionConstant)) {
                    // Search replace
                    $st = [];
                    $st['{{COLUMN_NAME}}'] = $formParam['property'][$key];
                    $st['{{UCFIRST_COLUMN_NAME}}'] = ucfirst($formParam['property'][$key]);

                    $constantGroup = explode(',', $value);
                    foreach ($constantGroup as $group) {
                        $constant = explode(':', $group);
                        if (is_numeric(trim($constant[1]))) {
                            $constant[1] = trim($constant[1]);
                        } else {
                            $constant[1] = "'" . trim($constant[1]) . "'";
                        }
                        $constantDefine .= '    const ' . trim($constant[0]) . ' = ' . $constant[1] . ';' . "\n";

                        $st['{{PROPERTY_CONSTANT_LIST}}'] .= "        self::" . trim($constant[0]) . " => " . "'" . trim($constant[2]) . "'," . "\n";
                        $st['{{LABEL_CONSTANT_LIST}}'] .= "        self::" . trim($constant[0]) . " => 'label-success'," . "\n";
                    }

                    $constantDefine .= "\n";

                    $contentConstant = file_get_contents($templateFunctionConstant);
                    if ($contentConstant != '') {
                        $constantFunction .= strtr($contentConstant, $st) . "\n\n";
                    }
                } else {
                    $exceptionMsgTmp = 'Can not found volt file for generate related methods for'
                        . ' constant property (Not found file volt at ' . $templateFunctionConstant . ').';
                    throw new \Exception($exceptionMsgTmp);
                }
            }
        }


        $search['{{PROPERTY}}'] = $columnDefine;
        $search['{{CONSTANT}}'] = $constantDefine;
        $search['{{FUNCTION_CONSTANT}}'] = $constantFunction;

        // define searchable
        $keywordIn = [];
        foreach ($formParam['searchable'] as $key => $value) {
            if ($value == 'on') {
                $keywordIn[] = "'" . $formParam['property'][$key] . "'";
            }
        }

        $search['{{KEYWORD_IN}}'] = '[' . implode(', ', $keywordIn) . ']';

        $validation = '';
        $validationUseNamespace = [];
        foreach ($formParam['validatingAddEdit'] as $key => $value) {
            switch($value) {
                case 'unique':
                    $validation .= '        $this->validate(new Uniqueness([' . "\n";
                    $validation .= "            'field' => '" . $formParam['property'][$key] . "'," . "\n";
                    $validation .= "            'message' => '" . $formParam['label'][$key] . " is already used.'" . "\n";
                    $validation .= "        ]));\n\n";
                    $validationUseNamespace[] = 'use Phalcon\Mvc\Model\Validator\Uniqueness;';
                    break;
                case 'notEmpty':
                    $validation .= '        $this->validate(new PresenceOf([' . "\n";
                    $validation .= "            'field' => '" . $formParam['property'][$key] . "'," . "\n";
                    $validation .= "            'message' => '" . $formParam['label'][$key] . " is required.'" . "\n";
                    $validation .= "        ]));\n\n";
                    $validationUseNamespace[] = 'use Phalcon\Mvc\Model\Validator\PresenceOf;';
                    break;
                case 'email':
                    $validation .= '        $this->validate(new Email([' . "\n";
                    $validation .= "            'field' => '" . $formParam['property'][$key] . "'," . "\n";
                    $validation .= "            'message' => '" . $formParam['label'][$key] . " is invalid.'" . "\n";
                    $validation .= "        ]));\n\n";
                    $validationUseNamespace[] = 'use Phalcon\Mvc\Model\Validator\Email;';
                    break;
                case 'isNumber':
                    $validation .= '        $this->validate(new Numericality([' . "\n";
                    $validation .= "            'field' => '" . $formParam['property'][$key] . "'," . "\n";
                    $validation .= "            'message' => '" . $formParam['label'][$key] . " is not numeric.'" . "\n";
                    $validation .= "        ]));\n\n";
                    $validationUseNamespace[] = 'use Phalcon\Mvc\Model\Validator\Numericality;';
                    break;
            }
        }

        if ($validation != '') {
            $validation .= '        return !$this->validationHasFailed();';
            $search['{{USE_NAMESPACE}}'] .= implode("\n", array_unique($validationUseNamespace));
        }

        $search['{{VALIDATION}}'] = $validation;

        $urlTemplateModel = APP_URL . 'modules/admin/views/generator/format/models.volt';
        if (file_exists($urlTemplateModel)) {
            $contentModel = file_get_contents($urlTemplateModel);
            if ($contentModel != '') {
                $sourceModel = str_replace(array_keys($search), array_values($search), $contentModel);

                if (file_put_contents($directories['models'] . '/' . $formParam['modelClass'] . '.php', $sourceModel) !== false) {
                    $this->flash->success('Generate Model success');
                }
            }
        } else {
            $this->flash->error("Not found file template model to generation (Not found file volt at ' . $urlTemplateModel . ')");
        }
    }

    public function generatorController($table, $formParam, $directories)
    {
        $search = [];
        $search['{{CLASS_NAME}}'] = $formParam['ctrClass'];
        $search['{{MODEL_NAME}}'] = $formParam['modelClass'];
        $search['{{DATE}}'] = date('d/m/Y', time());
        $search['{{YEAR}}'] = date('Y', time());
        $search['{{CONTROLLER_NAMESPACE}}'] = $formParam['ctrNamespace'] . '\Controllers';
        $search['{{BASE_CONTROLLER}}'] = $formParam['ctrExtends'];
        $search['{{TABLE_NAME}}'] = str_replace(TABLE_PREFIX, '', $table);
        $search['{{VARIABLE_NAME}}'] = lcfirst($formParam['modelClass']);

        $search['{{FUNCTION_NAME}}'] = $formParam['modelClass'];
        $search['{{RECORD_PER_PAGE}}'] = $formParam['recordPerPage'];
        $search['{{USE_NAMESPACE}}'] = 'use ' . $formParam['modelNamespace'] . '\\' . $formParam['modelClass'] . ';';


        // define searchable
        $keywordIn = [];
        foreach ($formParam['searchable'] as $key => $value) {
            if ($value == 'on') {
                $keywordIn[] = "'" . $formParam['property'][$key] . "'";
            }
        }

        $search['{{KEYWORD_IN}}'] = '[' . implode(', ', $keywordIn) . ']';

        // Filter
        $filterGet = '';
        $filterParam = '';
        $filterAssign = '';
        foreach ($formParam['filterable'] as $key => $value) {
            if ($value == 'on') {
                $filterGet .= '        $' . $formParam['property'][$key] . ' = $this->request->getQuery(\'' . strtolower($formParam['property'][$key]) . '\', \'string\', \'\');' . "\n";

                $filterParam .= "        if ($" . $formParam['property'][$key] . "!= '') {\n";
                $filterParam .= '            $parameter[\'' . $formParam['property'][$key] .'\'] = $' . $formParam['property'][$key] . ';' . "\n";
                $filterParam .= '            $queryUrl .= ($queryUrl == \'\' ? \'?\' : \'&\') . \'' . strtolower($formParam['property'][$key]) . '=\' . $' . $formParam['property'][$key] .';' . "\n";
                $filterParam .= "        } \n\n";

                $filterAssign .= '            \'' . $formParam['property'][$key] . '\' => ' . $formParam['modelClass'] .'::$' . $formParam['property'][$key] . 'Name,' . "\n";
            }
        }

        $search['{{FILTER_GET}}'] = $filterGet;
        $search['{{FILTER_PARAM}}'] = $filterParam;
        $search['{{FILTER_ASSIGN}}'] = $filterAssign;


        // add assign property
        $search['{{ADD_ASSIGN_PROPERTY}}'] = '';
        foreach ($formParam['addEditExclude'] as $key => $value) {
            if ($value == 'on') {
                if ($formParam['property'][$key] == 'password') {
                    $search['{{ADD_ASSIGN_PROPERTY}}'] .= '                $' . $search['{{VARIABLE_NAME}}'] . '->'
                        . $formParam['property'][$key] . ' = $this->security->hash($formData[\''
                        . $formParam['property'][$key] . '\'])' . "\n";
                } else {
                    $search['{{ADD_ASSIGN_PROPERTY}}'] .= '                $' . $search['{{VARIABLE_NAME}}'] . '->'
                        . $formParam['property'][$key] . ' = $formData[\''
                        . $formParam['property'][$key] . '\'];' . "\n";
                }
            }
        }

        $search['{{REDIRECT_INDEX}}'] = strtolower($formParam['ctrNamespace'] . '/' . $search['{{VARIABLE_NAME}}']);

        $search['{{PROPERTY_NAME}}'] = 'name';

        $fileTemplateController = APP_URL . 'modules/admin/views/generator/format/admin_controller.volt';
        if (file_exists($fileTemplateController)) {
            $contentController = file_get_contents($fileTemplateController);
            if ($contentController != '') {
                $sourceController = str_replace(array_keys($search), array_values($search), $contentController);

                if (file_put_contents($directories['controllers'] . '/' . $formParam['ctrClass'] . '.php', $sourceController) !== false) {
                    $this->flash->success('Generate controller success');
                }
            }
        } else {
            $this->flash->error("Not found file template controller to generation (Not found file volt at ' . $fileTemplateController . ')");
        }

    }
}

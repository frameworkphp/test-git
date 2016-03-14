<?php
/**
 * {{MODEL_NAME}}.php {{DATE}}
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) {{YEAR}}
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
*/

namespace {{MODEL_NAMESPACE}};

{{USE_NAMESPACE}}

class {{MODEL_NAME}} extends {{BASE_MODEL}}
{
{{PROPERTY}}

    {{CONSTANT}}

    public function initialize()
    {
        $this->setSource(TABLE_PREFIX . '{{TABLE_NAME}}');

        {{IMAGE_FUNCTION}}
    }
	
	public function beforeCreate()
    {
        {{DATE_CREATED}}
    }

    public function beforeUpdate()
    {
		{{DATE_MODIFIED}}
    }
	
	public function afterUpdate()
    {
        $cache = $this->getDi()->get('cache');
        // Delete cache by id
        $key = HOST_HASH . md5(get_class() . '::get{{FUNCTION_NAME}}ById::' . $this->id);
        $cache->delete($key);
    }
	
	/**
     * Validate that emails are unique across users
     *
     * @return boolean
     */
    public function validation()
    {
{{VALIDATION}}
    }
	
	/**
     * Get record by id
     * @param $id
     * @return mixed
     */
	public static function get{{FUNCTION_NAME}}ById($id)
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
            'cache' => [
                'key' => HOST_HASH . md5(get_class() . '::get{{FUNCTION_NAME}}ById::' . $id),
                'lifetime' => 3600,
            ]
        ]);
    }
	
	/**
     * Select the record, Interface with the outside (Controller Action)
     * @param array $parameter
     * @param string $columns
     * @param int $limit
     * @param int $offset
     * @param string $sortBy
     * @param string $sortType
     * @return mixed
     */
	public static function get{{FUNCTION_NAME}}s($parameter = [], $columns = '*', $limit = {{RECORD_PER_PAGE}}, $offset = 1, $sortBy = '', $sortType = '')
    {
        $whereString = '';
        $bindParams = [];
        $modelName = get_class();

        // Begin assign keyword to search
        if (isset($parameter['keyword']) && $parameter['keyword'] != '') {
            $keyword = $parameter['keyword'];

            // Define default keywordIn
            $keywordIn = {{KEYWORD_IN}};

            // If controller use keywordIn
            if (isset($parameter['keywordIn']) && !empty($parameter['keywordIn'])) {
                $keywordIn = $parameter['keywordIn'];
            }

            $whereString .= ($whereString != '' ? ' OR ' : ' (');
            $filter = '';
            foreach ($keywordIn as $in) {
                $filter .= ($filter != '' ? ' OR ' : '') . $in . ' LIKE :searchKeyword:';
            }

            $whereString .= $filter . ')';
            $bindParams['searchKeyword'] = '%' . $keyword . '%';
        }
        unset($parameter['keyword']);
        unset($parameter['keywordIn']);
        // End Search

        // Assign name params same MetaData
        foreach ($parameter as $key => $value) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . $key . ' = :' . $key . ':';
            $bindParams[$key] = $value;
        }

        $conditions = [];
        if ($whereString != '' && !empty($bindParams)) {
            $conditions = [[$whereString, $bindParams]];
        }

        // Check order
        if ($sortBy == '') {
            $sortBy = 'id';
        }

        if (strcasecmp($sortType, 'ASC') != 0 && strcasecmp($sortType, 'DESC') != 0) {
            $sortType = 'DESC';
        }
        $order = $sortBy . ' ' . $sortType;

        $params = [
            'models' => $modelName,
            'columns' => $columns,
            'conditions' => $conditions,
            'order' => [$modelName . '.' . $order . '']
        ];

        return parent::getList($params, $limit, $offset);
    }
	
	{{FUNCTION_CONSTANT}}
}

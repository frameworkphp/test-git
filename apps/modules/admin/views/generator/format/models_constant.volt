
    /**
     * @var array name
     */
    public static ${{COLUMN_NAME}}Name = [
{{PROPERTY_CONSTANT_LIST}}
    ];

    /**
     * @var array label
     */
    public static ${{COLUMN_NAME}}Label = [
{{LABEL_CONSTANT_LIST}}
    ];
	
	public function get{{UCFIRST_COLUMN_NAME}}Name()
    {
        return self::${{COLUMN_NAME}}Name[$this->{{COLUMN_NAME}}];
    }

    public function get{{UCFIRST_COLUMN_NAME}}Label()
    {
        return self::${{COLUMN_NAME}}Label[$this->{{COLUMN_NAME}}];
    }
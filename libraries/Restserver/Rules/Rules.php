<?php
/**
 * REST Full server for Codeigniter 3
 * 
 * @author Yoann Vanitou <yvanitou@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link   https://github.com/maltyxx/restserver
 * @since   Version 2.1.0
 * @filesource
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * Restserver (Librairie REST Serveur)
 */
class Restserver_rule
{
    const TYPE_INPUT  = 'input';
    
    const TYPE_FILTER = 'filter';
    
    const TYPE_SORT   = 'sort';
    
    /**
     * Nom de la donnée entrante
     * @var string 
     */
    public $field;
    
    /**
     * type de donnée
     * @var string 
     */
    public $type;

    /**
     * Les alias
     * @var string 
     */
    public $alias;

    /**
     * Nom du label
     * @var type
     */
    public $label;

    /**
     * Les règles
     * @var string
     */
    public $rules;

    /**
     * Les erreurs
     * @var type 
     */
    public $errors;

    /**
     * Commentaire
     * @var string
     */
    public $comment;

    /**
     * Constructeur
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $config_key => $config_value) {
            $this->{$config_key} = $config_value;
        }
        
        // Si le type n'est pas définie
        if (empty($config['type'])) {
            $this->type = self::TYPE_INPUT;
        }
        
        switch ($this->type) {
            case self::TYPE_FILTER:
                $this->type = self::TYPE_FILTER;
                break;
            case self::TYPE_SORT:
                $this->type = self::TYPE_SORT;
                break;
            default:
                $this->type = self::TYPE_INPUT;
        }

        // Si le name est définie
        if (!empty($config['name'])) {
            $this->label = $config['name'];
        }
        
        // Si le input est définie, il est converti en field
        if (!empty($config['input'])) {
            $this->field = $config['input'];
        }

        // Si l'alias n'est pas définie
        if (empty($this->alias)) {
            $this->alias = $this->field;
        }

        // Si le label n'est pas définie
        if (empty($this->label)) {
            $this->label = $this->field;
        }
    }

    /**
     * Retourne la configuration pour le form_validator
     * @return array|boolean
     */
    public function get_rules()
    {
        if (empty($this->rules)) {
            return NULL;
        }

        return array(
            'field'  => $this->field,
            'label'  => $this->label,
            'rules'  => $this->rules,
            'errors' => $this->errors
        );
    }
    
    public function get_field()
    {
        return array(
            'field'   => $this->field,
            'type'    => $this->type,
            'alias'   => $this->alias,
            'label'   => $this->label,
            'rules'   => $this->rules,
            'errors'  => $this->errors,
            'comment' => $this->comment
        );
    }
}

/* End of file Restserver_rule.php */
/* Location: ./libraries/Restserver/Restserver_rule.php */

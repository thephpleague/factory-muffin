<?php namespace Zizaco\FactoryMuff;

/**
* Creates models with random attributes
*
* @package  Zizaco\FactoryMuff
* @author   Zizaco <zizaco@gmail.com>
* @license  MIT
* @link     https://github.com/Zizaco/factorymuff
*/
class FactoryMuff
{
    /**
     * $wordlist
     *
     * @var array
     *
     * @access private
     * @static
     */
    private $wordlist = array();

    /**
     * $mail_domains
     *
     * @var array
     *
     * @access private
     */
    private $mail_domains = array(
        'example.com', 'dontexist.com',
        'mockdomain.com', 'emailprovider.com',
        'exampledomain.org'
    );

    /**
     * $factories
     *
     * @var array
     *
     * @access private
     */
    private $factories = array();

    /**
     * Creates and saves in db an instance
     * of Model with mock attributes
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return mixed Returns the model instance.
     */
    public function create( $model, $attr = array() )
    {
        $obj = $this->instance( $model, $attr );

        $result = $obj->save();
        if ( !$result ) {

            $message = '';

            if(isset($obj->validationErrors))
            {
                if($obj->validationErrors)
                {
                    $message = $obj->validationErrors.' - ';
                }
            }

            throw new SaveException($message.'Could not save the model of type: '.$model);
        }

        return $obj;
    }

    /**
     * Return an instance of the model, which is
     * not saved in the database
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return mixed Returns the model instance.
     */
    public function instance( $model, $attr = array() )
    {
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor( $model, $attr );

        // Create, set, save and return instance
        $obj = new $model();

        foreach ($attr_array as $attr => $value) {
            $obj->$attr = $value;
        }

        return $obj;
    }

    /**
     * Returns an array of mock attributes
     * for the specified model
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return array Returns an attributes array.
     */
    public function attributesFor( $model, $attr = array() )
    {
        $factory_attrs = $this->getFactoryAttrs($model);

        // Prepare attributes
        foreach ( $factory_attrs as $key => $kind ) {
            if ( ! isset($attr[$key]) ){
                $attr[$key] = $this->generateAttr( $kind, $model );
            }
        }

        return $attr;
    }

    /**
     * Define a new model factory
     *
     * @param string $model Model class name.
     * @param array $definition Array with definition of attributes.
     *
     * @access public
     *
     * @return void
     */
    public function define($model, array $definition = array())
    {
        $this->factories[$model] = $definition;
    }

    /**
     * Returns an array with factory definition for the especified model
     *
     * @param string $model Model class name.
     *
     * @access private
     *
     * @return array Returns an factory definition array.
     */
    private function getFactoryAttrs($model)
    {
        if(isset($this->factories[$model])) {
            return $this->factories[$model];
        }
        else {
            // Get the $factory static and check for errors
            $static_vars = get_class_vars( $model );

            if (isset( $static_vars['factory'] ) ) {
                return $static_vars['factory'];
            }
        }
        throw new NoDefinedFactoryException('Factory not defined for class: ' . $model);
    }

    /**
     * Generate an attribute based in the wordlist
     *
     * @param string $kind The kind of attribute that will be generate.
     * @param string $model The name of the model class
     *
     * @access private
     *
     * @return mixed String or an instance of related model.
     */
    private function generateAttr( $kind, $model = NULL )
    {
        $result = 'muff';

        if($kind instanceof \Closure) {
            $result = $kind();
        }
        // If the kind begins with "factory|", then create
        // that object and save the relation.
        else if ( is_string($kind) && substr( $kind, 0, 8 ) == 'factory|' ) {
            $related = $this->create( substr( $kind, 8 ) );

            if (method_exists($related, 'getKey'))
            {
                return $related->getKey();
            }
            elseif (method_exists($related, 'pk')) // Kohana Primary Key
            {
                return $related->pk();
            }
            elseif( isset($related->id) ) // id Attribute
            {
                return $related->id;
            }
            elseif( isset($related->_id) ) // Mongo _id attribute
            {
                return $related->_id;
            }
            else
            {
                return null;
            }
        }

        else if ( is_string($kind) && substr( $kind, 0, 5 ) === 'call|' ) {
            $callable = substr( $kind, 5 );
            $params = array();

            if ( strstr( $callable, '|' ) ) {
                $parts = explode('|', $callable);
                $callable = array_shift($parts);
                if ( $parts[0] === 'factory' && count($parts) > 1 ) {
                    $params[] = $this->create($parts[1]);
                } else {
                    // slight overkill here, since there's probably only 1
                    // in the array, but let's make it a string in case the
                    // piped format changes more in the future
                    $attr = implode('|', $parts);
                    $params[] = $this->generateAttr($attr, $model);
                }
            }
            if (method_exists($model, $callable)) {
                $return = call_user_func_array("$model::$callable", $params);
                return $return;
            } else {
                throw new \Exception("$model does not have a static $callable method");
            }
        }

        else if ( is_string($kind) && substr( $kind, 0, 5 ) === 'date|' ) {
            $format = substr( $kind, 5 );
            $result = date($format);
        }

        else if ( is_string($kind) && substr( $kind, 0, 8 ) === 'integer|' ) {
            $numgen = substr( $kind, 8 );
            $result = null;
            for ( $i=0; $i<$numgen; $i++ ) {
                $result .= mt_rand(0,9);
            }

            return (int) $result;
        }
        else {

            // Overwise interpret the kind and 'generate' some
            // crap.
            switch ( $kind ) {

                // Pick a word and append a domain
                case 'email':
                    shuffle( $this->mail_domains );

                    $result = $this->getWord().'@'.$this->mail_domains[0];
                    break;

                // Pick some words
                case 'text':
                    for ( $i=0; $i < ( ((int)date( 'U' )+rand(0,5)) % 8 ) + 2; $i++ ) {
                        $result .= $this->getWord()." ";
                    }

                    $result = trim( $result );
                    break;

                // Pick a single word
                case 'string':
                    $result = $this->getWord();

                    if (rand(0,1))
                        $result = ucfirst($result);

                    break;

                /**
                 * ITS HERE: The point where you can extend
                 * this class, to support new datatypes
                 */

                // Returns the original string or number
                default:
                    $result = $kind;
                    break;
            }


        }

        return $result;
    }

    public function getWord() {
        // Reset wordlist if empty
        if ( count( $this->wordlist ) == 0 ) {
            $this->wordlist = include(__DIR__.'/Wordlist.php');
            shuffle( $this->wordlist );
        }

        return array_pop($this->wordlist);
    }
}

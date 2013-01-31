<?php namespace Zizaco\FactoryMuff;

/**
* Creates models with randomic attributes
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
        'mockdoman.com', 'emailprovider.com',
        'exampledomain.org'
    );

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
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor( $model, $attr );

        // Create, set, save and return instance
        $obj = new $model();

        foreach ($attr_array as $attr => $value) {
            $obj->$attr = $value;
        }

        $obj->save();

        return $obj;
    }

    /**
     * Returns an array of mock attributes
     * for the especified model
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
        // Prepare word list if empty
        if ( count( $this->wordlist ) == 0 ) {
            $this->wordlist = include(__DIR__.'/Wordlist.php');
            shuffle( $this->wordlist );
        }

        // Get the $factory static and check for errors
        $static_vars = get_class_vars( $model );

        if ( !$static_vars ) {
            trigger_error( "$model Model is not an valid Class for FactoryMuff" );
            return false;
        }

        if ( !isset( $static_vars['factory'] ) ) {
            trigger_error( "$model Model should have an static \$factory array in order to be created with FactoryMuff" );
            return false;
        }

        // Prepare attributes
        $attr_array = array();
        foreach ( $static_vars['factory'] as $key => $kind ) {
            $attr_array[$key] = $this->generateAttr( $kind );
        }

        $attr_array = array_merge( $attr_array, $attr );

        return $attr_array;
    }

    /**
     * Generate an attribute based in the wordlist
     * 
     * @param tring $kind The kind of attribute that will be generate.
     *
     * @access private
     *
     * @return mixed String or an instance of related model.
     */
    private function generateAttr( $kind )
    {
        $result = 'muff';

        // If the kind begins with "factory|", then create
        // that object and save the relation.
        if ( substr( $kind, 0, 8 ) == 'factory|' ) {
            $related = $this->create( substr( $kind, 8 ) );

            if (method_exists($related, 'getKey'))
            {
                return $related->getKey();
            }
            else
            {
                return $related->id;
            }
        }

        // Overwise interpret the kind and 'generate' some
        // crap.
        switch ( $kind ) {

        // Pick a word and append a domain
        case 'email':
            shuffle( $this->mail_domains );
            $result = array_pop( $this->wordlist ).'@'.$this->mail_domains[0];
            break;

        // Pick some words
        case 'text':
            for ( $i=0; $i < ( (int)date( 'U' ) % 8 ) + 2; $i++ ) {
                $result .= array_pop( $this->wordlist )." ";
            }

            $result = trim( $result );
            break;

        // Pick a single word then
        case 'string':
            $result = array_pop( $this->wordlist );

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

        return $result;
    }
}

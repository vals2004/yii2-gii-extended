<?php

namespace yii2mod\gii\enum;

use Yii;
use yii\gii\CodeFile;

use yii\helpers\Inflector;
use yii\helpers\VarDumper;

/**
 * This generator will generate the enumerable class.
 *
 * @property array $constValues An array of constant values entered by the user.
 * @property string $enumerableClass The enumerable class name without the namespace part. This property is
 * read-only.
 * @property string $author The author name in generated Enumerable class.
 * @property string $description The description text in generated Enumerable class.
 * read-only.
 * @author Igor Chepurnoy
 *
 * @since 1.0
 */
class Generator extends \yii\gii\Generator
{

    /**
     * @var string the Enumerable class
     */
    public $enumerableClass;

    /**
     * @var string author in generated Enumerable class
     */
    public $author = 'Igor Chepurnoy';

    /**
     * @var string description in generated Enumerable class
     */
    public $description = 'This is the CEnumerable class for';

    /**
     * @var string constants values. Separate multiple values with commas or spaces.
     */
    public $constValues;

    /**
     * @var string the namespace of the enumerable class
     */
    public $ns = 'app\models\enumerables';

    /**
     * @var integer. Start numbers of values from.
     */
    public $start = 0;

    /**
     * @var boolean. Sort values or no.
     */
    public $sort = 0;

    public function init()
    {
        parent::init();
    }

    /**
     * @return string name of the code generator
     */
    public function getName()
    {
        return 'Enumerable Generator';
    }

    /**
     * @return string the detailed description of the generator.
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new Enumerable class';

    }

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     * @return array validation rules
     * @see scenarios()
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['enumerableClass', 'constValues', 'description', 'ns'], 'filter', 'filter' => 'trim'],
            [['enumerableClass', 'constValues', 'start'], 'required'],
            [['start'], 'integer'],
            [['sort'], 'boolean'],
            [['enumerableClass'], 'match', 'pattern' => '/^[a-z][a-z0-9\\-\\/]*$/', 'message' => 'Only a-z, 0-9, dashes (-) and slashes (/) are allowed.'],
        ]);
    }

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute
     * `firstName`, we can declare a label `First Name` which is more user-friendly and can
     * be displayed to end users.
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'enumerableClass' => 'Enumerable Class',
            'constValues' => 'All const values',
            'description' => 'Class description',
            'author' => 'Author',
            'ns' => 'Enumerable Namespace',
        ];
    }

    /**
     * Returns a list of code template files that are required.
     * Derived classes usually should override this method if they require the existence of
     * certain template files.
     * @return array list of code template files that are required. They should be file paths
     * relative to [[templatePath]].
     */
    public function requiredTemplates()
    {
        return [
            'enumerable.php',
        ];
    }

    /**
     * Returns the list of hint messages.
     * The array keys are the attribute names, and the array values are the corresponding hint messages.
     * Hint messages will be displayed to end users when they are filling the form for the generator.
     * @return array the list of hint messages
     */
    public function hints()
    {
        return [

            'ns' => 'This is the namespace that the new enumerable class will use.',
            'enumerableClass' => 'Enumerable ID should be in lower case and may contain module ID(s) separated by slashes. For example:
                <ul>
                    <li><code>order</code> generates <code>Order.php</code></li>
                    <li><code>order-item</code> generates <code>OrderItem.php</code></li>
                </ul>',
            'author' => 'The author  in generated Enum class.',
            'description' => 'Description in generated Enum class.',
            'constValues' => 'Provide one or multiple values to generate const(s) in the Enumerable. Separate multiple values with commas or spaces. For Example:
            <code>free,paid</code> generates
            <p><code>const FREE = 0;</code></p>
            <p><code>const PAID = 1;</code></p>


            '
        ];
    }

    /**
     * Returns the message to be displayed when the newly generated code is saved successfully.
     * Child classes may override this method to customize the message.
     * @return string the message to be displayed when the newly generated code is saved successfully.
     */
    public function successMessage()
    {
        return "The Enumerable class has been generated successfully.";
    }

    /**
     * Generates the code based on the current user input and the specified code template files.
     * This is the main method that child classes should implement.
     * Please refer to [[\yii\gii\generators\controller\Generator::generate()]] as an example
     * on how to implement this method.
     * @return CodeFile[] a list of code files to be created.
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getPathEnumerableClass(),
            $this->render('enumerable.php')
        );

        return $files;
    }

    /**
     * @return string the enumerable class name without the namespace part.
     */
    public function getEnumerableClass()
    {
        return Inflector::id2camel($this->enumerableClass);
    }

    /**
     * Get absolute path for generated Enumerable file
     * @return string
     */
    public function getPathEnumerableClass()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $this->getEnumerableClass() . '.php';
    }

    /**
     * Normalizes [[constValues]] into an array of const values.
     * @return array an array of const values entered by the user
     */
    public function getConstIDs()
    {
        $constValues = array_map('trim', explode(',', $this->constValues));
        $constValues = array_filter($constValues);

        array_walk($constValues, function (&$value) {
            $value = preg_replace('/[^\w]+/', "_", $value);
        }, $constValues);

        $actions = array_unique($constValues);
        if ($this->sort) {
            sort($actions);
        }
        
        return $actions;
    }

    /**
     * Get author for Enumerable class
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get description for Enumerable class
     * @return string
     */
    public function getEnumerableDescription()
    {
        return $this->description;
    }
}

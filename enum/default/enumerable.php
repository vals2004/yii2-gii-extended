<?php

/**
 * This is the template for generating a enumerable class file.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\controller\Generator $generator
 */

use yii\helpers\Inflector;

echo "<?php\n";
?>

<?php if (!empty($generator->ns)): ?>
namespace <?= $generator->ns ?>;
<?php endif; ?>

use yii2mod\enum\helpers\BaseEnum;

/**
 *  @author <?= $generator->getAuthor() . "\n"; ?>
 *  <?= $generator->getEnumerableDescription() . "\n"; ?>
 */

class <?= $generator->getEnumerableClass() . " extends BaseEnum " . "\n" ?>
{
<?php foreach ($generator->getConstIDs() as $key => $const): ?>
    const <?= strtoupper($const) ?> = <?= $key+$generator->start ?>;
<?php endforeach; ?>

    public static $list = [
<?php foreach ($generator->getConstIDs() as $key => $const): ?>
       self::<?= strtoupper($const) ?> => '<?= Inflector::humanize($const); ?>',
<?php endforeach; ?>
   ];
}


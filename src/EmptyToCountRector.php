<?php

namespace tamagoage\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\VerbosityLevel;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EmptyToCountRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [BooleanNot::class, Empty_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change func to count($array) === 0 instead of empty($array)', [
            new CodeSample('empty($array)', 'count($array) === 0'),
        ]);
    }

    /**
     * @param BooleanNot|Empty_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $realNode = $node;
        $isBooleanNot = false;
        if ($node instanceof BooleanNot && $node->expr instanceof Empty_) {
            $realNode = $node->expr;
            $isBooleanNot = true;
        }

        if (!$realNode instanceof Empty_) {
            return null;
        }

        $subjectType = $this->getType($realNode->expr);

        echo $subjectType->describe(VerbosityLevel::cache()), ' => ', $subjectType->isNull()->describe(),PHP_EOL;
        if ($subjectType->describe(VerbosityLevel::cache()) === 'array') {
            var_dump($subjectType);
        }

        $conditions = [];

        if (!$subjectType->isArray()->no()) {
            $left = new FuncCall(
                new Name('\count'),
                [new Arg($realNode->expr)]
            );
            $right = new LNumber(0);
            $conditions[] = $isBooleanNot ? new NotIdentical($left, $right) : new Identical($left, $right);
        }

        $possibleEmptyValues = [];

        if (!$subjectType->isFalse()->no()) {
            $possibleEmptyValues[] = new ConstFetch(new Name('false'));
        }

        if (!$subjectType->isNull()->no()) {
             $possibleEmptyValues[] = new ConstFetch(new Name('null'));
        }

        if (!$subjectType->isString()->no()) {
            $possibleEmptyValues[] = new String_('');
            $possibleEmptyValues[] = new String_('0');
        }

        if (!$subjectType->isInteger()->no()) {
            $possibleEmptyValues[] = new LNumber(0);
        }

        if (!$subjectType->isFloat()->no()) {
            $possibleEmptyValues[] = new LNumber(0.0);
        }

        if (count($possibleEmptyValues) === 1) {
            $left = $realNode->expr;
            $right = $possibleEmptyValues[0];
            $conditions[] = $isBooleanNot ? new NotIdentical($left, $right) : new Identical($left, $right);
        }
        else if (count($possibleEmptyValues) === 2) {
            $left = new FuncCall(
                new Name('in_array'),
                [
                    new Arg($realNode->expr),
                    new Arg(new Node\Expr\Array_(array_map(
                        fn($item) => new ArrayItem($item),
                        $possibleEmptyValues
                    ))),
                    new Arg(new ConstFetch(new Name('true'))),
                ]
            );

            if ($isBooleanNot) {
                $left = new BooleanNot($left);
            }
            $conditions[] = $left;
        }

        return match (count($conditions)) {
            0 => null,
            1 => $conditions[0],
            default => (function () use ($conditions) {
                $node = array_shift($conditions);
                foreach (array_reverse($conditions) as $condition) {
                    $node = new BooleanOr($condition, $node);
                }
                return $node;
            })()
        };
    }
}

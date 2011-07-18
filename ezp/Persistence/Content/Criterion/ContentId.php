<?php
/**
 * File containing the ezp\Persistence\Content\Criterion\ContentId class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 *
 */

namespace ezp\Persistence\Content\Criterion;
use ezp\Persistence\Content\Criterion,
    ezp\Persistence\Content\Interfaces\Criterion as CriterionInterface;

/**
 * A criterion that matches content based on its id
 *
 * Supported operators:
 * - IN: will match from a list of ContentId
 * - EQ: will match against one ContentId
 */
class ContentId extends Criterion implements CriterionInterface
{
    /**
     * Creates a new ContentId criterion
     *
     * @param null $target Not used
     * @param string $operator
     *        Possible values:
     *        - Operator::IN: match against a list of contentId. $value must be an array of contentId
     *        - Operator::EQ: match against a single contentId. $value must be a single contentId
     * @param integer|array(integer) One or more content Id that must be matched
     *
     * @throw InvalidArgumentException if a non numeric id is given
     * @throw InvalidArgumentException if the value type doesn't match the operator
     */
    public function __construct( $target = null, $operator, $value )
    {
        parent::__construct( $target, $operator, $value );
    }

    public function getSpecifications()
    {
        $types = array( OperatorSpecifications::TYPE_INTEGER, OperatorSpecifications::TYPE_STRING );
        return array(
            new OperatorSpecifications( Operator::IN, OperatorSpecifications::FORMAT_ARRAY, $types ),
            new OperatorSpecifications( Operator::EQ, OperatorSpecifications::FORMAT_SINGLE, $types ),
        );
    }

}
?>

<?php
/**
 * File containing the BinaryFile Value class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace ezp\Content\FieldType\BinaryFile;
use ezp\Content\FieldType\ValueInterface,
    ezp\Content\FieldType\Value as BaseValue,
    ezp\Base\Exception\PropertyNotFound;

/**
 * Value for BinaryFile field type
 *
 * @property-read string $filename The internal name of the file (generated by the system)
 * @property-read string $mimeType The MIME type of the file (for example "audio/wav").
 * @property-read string $mimeTypeCategory The MIME type category (for example "audio").
 * @property-read string $mimeTypePart The MIME type part (for example "wav").
 * @property-read int $filesize The size of the file (number of bytes).
 * @property-read string $filepath The path to the file (including the filename).
 */
class Value extends BaseValue implements ValueInterface
{
    /**
     * BinaryFile object
     *
     * @var \ezp\Io\BinaryFile
     */
    public $file;

    /**
     * Original file name
     *
     * @var string
     */
    public $originalFilename;

    /**
     * Number of times the file has been downloaded through content/download module
     *
     * @var int
     */
    public $downloadCount = 0;

    /**
     * @var \ezp\Content\FieldType\BinaryFile\Handler
     */
    protected $handler;

    /**
     * Construct a new Value object.
     * To affect a BinaryFile object to the $file property, use the handler:
     * <code>
     * use \ezp\Content\FieldType\BinaryFile;
     * $binaryValue = new BinaryFile\Value;
     * $binaryValue->file = $binaryValue->getHandler()->createFromLocalPath( '/path/to/local/file.txt' );
     * </code>
     */
    public function __construct()
    {
        $this->handler = new Handler;
    }

    /**
     * @see \ezp\Content\FieldType\Value
     * @return \ezp\Content\FieldType\BinaryFile\Value
     */
    public static function fromString( $stringValue )
    {
        $value = new static();
        $value->file = $value->handler->createFromLocalPath( $stringValue );
        $value->originalFilename = basename( $stringValue );
        return $value;
    }

    /**
     * @see \ezp\Content\FieldType\Value
     */
    public function __toString()
    {
        return $this->file->path;
    }

    /**
     * Returns handler object.
     * Useful manipulate {@link self::$file}
     *
     * @return \ezp\Content\FieldType\BinaryFile\Handler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    public function __get( $name )
    {
        switch( $name )
        {
            case 'filename':
                return basename( $this->file->path );
                break;

            case 'mimeType':
                return $this->file->contentType->__toString();
                break;

            case 'mimeTypeCategory':
                return $this->file->contentType->type;
                break;

            case 'mimeTypePart':
                return $this->file->contentType->subType;
                break;

            case 'filesize':
                return $this->file->size;
                break;

            case 'filepath':
                return $this->file->path;
                break;

            default:
                throw new PropertyNotFound( $name, get_class( $this ) );
        }
    }

    /**
     * @see \ezp\Content\FieldType\ValueInterface::getTitle()
     */
    public function getTitle()
    {
        return $this->originalFilename;
    }
}

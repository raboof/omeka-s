<?php
namespace Omeka\Model\Entity;

/**
 * @Entity
 */
class Media extends Resource
{
    /**
     * @Id
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column
     */
    protected $type;
    
    /**
     * @Column(type="json_array", nullable=true)
     */
    protected $data;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $source;

    /**
     * @Column(nullable=true)
     */
    protected $mediaType;

    /**
     * @Column(nullable=true)
     */
    protected $filename;

    /**
     * @ManyToOne(targetEntity="Item", inversedBy="media")
     * @JoinColumn(nullable=false)
     */
    protected $item;

    public function getResourceName()
    {
        return 'media';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
    }

    public function getMediaType()
    {
        return $this->mediaType;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setItem(Item $item = null)
    {
        $this->synchronizeOneToMany($item, 'item', 'getMedia');
    }

    public function getItem()
    {
        return $this->item;
    }
}

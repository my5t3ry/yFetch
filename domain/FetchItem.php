<?php
/**
 * User: sascha.bast
 * Date: 12/1/18
 * Time: 11:25 PM
 */

class FetchItem
{

    private $type;
    private $id;
    private $status;
    private $metaData;


    public function __construct(string $id, FetchItemType $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getType(): FetchItemType
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
    }

    public function getMetaData()
    {
        return $this->metaData;
    }

    public function getDataUrl($cfgSerice)
    {
        if ($this->metaData == null) {
            throw new \splitbrain\phpcli\Exception("Could not build data url metadata was not set");
        }
        if ($this->getType() == FetchItemType::THUMBNAIL_TYPE) {
            return $this->getMetaData()->snippet->thumbnails->{$cfgSerice->getValue("thumbResolution")}->{"url"};
        } else {
            return "https://www.youtube.com/watch?v=" . $this->id;
        }
    }
}
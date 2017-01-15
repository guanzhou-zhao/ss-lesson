<?php

class Region extends DataObject {
  private static $has_one = array (
    'RegionsPage' => 'RegionsPage',
    'Photo' => 'Image'
  );
  private static $db = array (
    'Title' => 'Varchar',
    'Description' => 'HTMLText',
  );
  private static $summary_fields = array (
    'Photo.Filename' => 'Photo file name',
    'Title' => 'Title of region',
    'Description' => 'Short description'
  );

  public function Link() {
    return $this->RegionsPage()->Link('show/'.$this->ID);
  }

  public function LinkingMode() {
    return Controller::curr()->getRequest()->param('ID') == $this->ID ? 'current' : 'link';
  }

  public function getGridThumbnail() {
    if ($this->Photo()->exists()) {
      return $this->Photo()->SetHeight(200);
    }
    return "(no image)";
  }

  public function getCMSFields() {
    $fields = FieldList::create(
      TextField::create('Title'),
      HtmlEditorField::create('Description'),
      $uploader = UploadField::create('Photo')
    );

    $uploader
      ->setFolderName('region-photos')
      ->getValidator()->setAllowedExtensions(array('png','gif','jpeg','jpg'));

    return $fields;
  }
}

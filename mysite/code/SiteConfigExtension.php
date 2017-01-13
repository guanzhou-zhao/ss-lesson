<?php

class SiteConfigExtension extends DataExtension {
  private static $db = array (
    'FacebookLink' => 'Varchar',
    'TwitterLink' => 'Varchar',
    'GoogleLink' => 'Varchar',
    'YoutubeLink' => 'Varchar',
    'FooterContent' => 'Text'
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->addFieldsToTab('Root.Social', Array (
      TextField::create('FacebookLink', 'FaceBook'),
      TextField::create('TwitterLink', 'Twitter'),
      TextField::create('GoogleLink', 'Google'),
      TextField::create('YoutubeLink', 'Youtube')
    ));

    $fields->addFieldsToTab('Root.Main', TextareaField::create('FooterContent', 'Content for Footer'));
  }
}

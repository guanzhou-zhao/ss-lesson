<?php

class ArticlePage extends Page {
  private static $can_be_root = false;

  private static $db = array (
    'Date' => 'Date',
    'Teaser' => 'Text',
    'Author' => 'Varchar'
  );

  private static $has_one = array (
    'Photo' => 'Image',
    'Brochure' => 'File'
  );

  private static $many_many = array (
    'Categories' => 'ArticleCategory'
  );

  private static $has_many = array (
    'Comments' => 'ArticleComment'
  );

  public function CategoriesList() {
        if($this->Categories()->exists()) {
            return implode(', ', $this->Categories()->column('Title'));
        }
    }

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', DateField::create('Date', 'Date of article')->setConfig('showcalendar', true), 'Content');
    $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser'), 'Content');
    $fields->addFieldToTab('Root.Main', TextField::create('Author', 'Author of article'), 'Content');

    $fields->addFieldToTab('Root.Attachment', $photo = UploadField::create('Photo'));
    $fields->addFieldToTab('Root.Attachment', $brochure = UploadField::create(
      'Brochure',
      'Travel brochure, optional (PDF only)'
    ));
    $photo->setFolderName('travel-photos');
    $brochure
    ->setFolderName('travel-brochures')
    ->getValidator()->setAllowedExtensions(array('pdf'));

    $fields->addFieldToTab('Root.Categories', CheckboxSetField::create(
            'Categories',
            'Selected categories',
            $this->Parent()->Categories()->map('ID','Title')
        ));
    return $fields;
  }
}

class ArticlePage_Controller extends Page_Controller {

  private static $allowed_actions = array (
    'CommentForm'
  );

  public function CommentForm() {
    $form = Form::create(
      $this,
      __FUNCTION__,
      FieldList::create(
        TextField::create('Name', ''),
        EmailField::create('Email', ''),
        TextareaField::create('Comment', '')
      ),
      FieldList::create(
        FormAction::create('handleComment', 'Post Comment')
          ->setUseButtonTag(true)
                ->addExtraClass('btn btn-default-color btn-lg')
      ),
      RequiredFields::create('Name', 'Email', 'Comment')
    )
    ->addExtraClass('form-style');


    foreach($form->Fields() as $field) {
        $field->addExtraClass('form-control')
               ->setAttribute('placeholder', $field->getName().'*');
    }

    $data = Session::get("FormData.{$form->getName()}.data");
    return $data ? $form->loadDataFrom($data) : $form;
  }

  public function handleComment($data, $form) {
    error_log('set formdata');
    Session::set("FormData.{$form->getName()}.data", $data);

    $existing = $this->Comments()->filter(array (
      'Comment' => $data['Comment']
    ));

    if($existing->exists() && strlen($data['Comment']) > 20) {
        $form->sessionMessage('That comment already exists! Spammer!','bad');

        return $this->redirectBack();
    }

    $comment = ArticleComment::create();
    $form->saveInto($comment);
    // error_log('haha');
    $comment->ArticlePageID = $this->ID;

    $comment->write();

    Session::clear("FormData.{$form->getName()}.data");
    $form->sessionMessage('Thanks for your comment!', 'good');

    return $this->redirectBack();
  }

}

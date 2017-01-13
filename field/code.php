<?php

class CodeField extends BaseField {

  public static $assets = [
    'js' => [
      'script.js',
      'prism.js',
    ],
    'css' => [
      'style.css',
      'prism.css',
    ],
  ];

  public function __construct()
  {
    // load language configuration
    if (!isset($this->languages) || !is_array($this->languages)) {
      $this->languages = c::get('plugin.fieldcode.defaultlanguages', [
      	'text' => 'Plain text (No highlighting)',
      	'ruby' => 'Ruby',
      	'elixir' => 'Elixir',
      	'go' => 'Go',
      	'javascript' => 'JavaScript',
      	'jsx' => 'React JSX',
      	'json' => 'JSON',
      ]);
    }
  }

  public function code()
  {
    $code = $this->value() ?: '';
    $code = removeCodeFieldLanguageSpecification($code);
    $code = decodeCodeFieldWhitespaces($code);
    return $code;
  }

  public function language()
  {
    $code = $this->value() ?: '';
    return getCodeFieldLanguageSpecification($code);
  }

  public function input()
  {
    // build hidden textarea field
    $input = new Brick('textarea');
    $input->addClass('input code-field-input');
    $input->attr(array(
      'required' => $this->required(),
      'name'     => $this->name(),
      'readonly' => 'readonly',
      'id'       => $this->id()
    ));
    $input->data(array(
      'field'  => 'codefield',
      'editor' => '#' . $this->id() . '-editor',
      'language' => '#' . $this->id() . '-language',
    ));
    return $input;
  }

  public function content()
  {
    $content = parent::content();
    $content->append($this->languageSelect());
    $content->append($this->editor());
    return $content;
  }

  protected function languageSelect()
  {
    $language = $this->language();

    // build language select field
    $select = new Brick('select');
    $select->addClass('input');
    $select->addClass('code-field-language');
    $select->attr('id', $this->id() . '-language');

    // append options to select
    foreach ($this->languages() as $value => $label) {
      $option = new Brick('option');
      $option->attr('value', $value);
      $option->html($label);

      if ($language === $value) {
        $option->attr('selected', 'selected');
      }

      $select->append($option);
    }

    return $select;
  }

  protected function editor()
  {
    $editor = new Brick('div');
    $editor->html($this->code());
    $editor->addClass('input');
    $editor->addClass('code-field-editor');
    $editor->attr('id', $this->id() . '-editor');
    $editor->attr('contenteditable', 'true');
    $editor->attr('spellcheck', 'false');
    $editor->data(array(
      'storage' => $this->id(),
    ));

    $wrapper = new Brick('div');
    $wrapper->append($editor);
    $wrapper->addClass('code-field-editor-wrapper');
    return $wrapper;
  }

  public function result()
  {
    // convert whitespaces
    return encodeCodeFieldWhitespaces(parent::result());
  }
}

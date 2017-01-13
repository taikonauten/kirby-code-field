/* globals Prism */

var CodeField = (function($, $field) {

  // bind elements
  this.$field = $field;
  this.$editor = $(this.$field.data('editor'));
  this.$language = $(this.$field.data('language'));

  this.code = '';

  this.updateInput = function(force) {
    var code = this.$editor.text();
    if (force || code !== this.code) {
      this.code = code;

      // add language specification to code
      var language = this.$language.val();
      if (language !== 'text') {
        code = '/' + '*language=' + language + '*' + '/' + code;
      }

      // update input value
      this.$field.text(code);

      return true;
    }

    return false;
  };

  this.highlight = function() {
    var language = this.$language.val();
    var code = this.$editor.text();

    if (language !== 'text') {
      var html = Prism.highlight(code, Prism.languages[language]);
      this.$editor.html(html);
    } else {
      this.$editor.html(code);
    }
  };

  this.onLanguageChange = function() {
    this.highlight();
    this.updateInput(true);
  };

  this.onEditorKeyDown = function(evt) {
    if (evt.keyCode === 13) {
      // trap return key
      // prevent creation of a div, just append a line break instead
      document.execCommand('insertHTML', false, '\n');
      evt.preventDefault();
      return false;
    }

    if (evt.keyCode === 9) {
      // trap tab key
      // instead of leaving field, append two spaces
      document.execCommand('insertHTML', false, '  ');
      evt.preventDefault();
      return false;
    }
  };

  this.onEditorKeyUp = function(evt) {
    var changed = this.updateInput(false);

    // only do the heavy lifting when something has changed
    if (changed) {
      var cursorPosition = this.getCursorPositionInElement(this.$editor);
      this.highlight();
      this.setCursorPositionInElement(this.$editor, cursorPosition);
    }
  };

  this.getCursorPositionInElement = function($element) {
    // read current cursor position
    var range = window.getSelection().getRangeAt(0);
    var preCursorRange = range.cloneRange();
    preCursorRange.selectNodeContents($element[0]);
    preCursorRange.setEnd(range.endContainer, range.endOffset);
    return preCursorRange.toString().length;
  };

  this.setCursorPositionInElement = function($element, position) {
    var range = document.createRange();
    var sel = window.getSelection();
    var element = $element[0];

    // select appropriate node
    var currentNode = null;
    var previousNode = null;

    for (var i = 0; i < element.childNodes.length; i++) {
      // save previous node
      previousNode = currentNode;

      // get current node
      currentNode = element.childNodes[i];
      while (currentNode.childNodes.length > 0) {
        currentNode = currentNode.childNodes[0];
      }

      // calc offset in current node
      if (previousNode != null) {
        position -= previousNode.length;
      }

      // check whether current node has enough length
      if (position <= currentNode.length) {
        break;
      }
    }

    // move cursor to specified position
    if (currentNode !== null) {
      range.setStart(currentNode, position);
      range.collapse(true);
      sel.removeAllRanges();
      sel.addRange(range);
    }
  };

  // bind events
  this.$language.change(this.onLanguageChange.bind(this));
  this.$editor.keyup(this.onEditorKeyUp.bind(this));
  this.$editor.keydown(this.onEditorKeyDown.bind(this));

  // initial event calls
  this.onLanguageChange();
});

(function($) {

  'use strict';

  // gets called by the panel to initialize field
  // https://github.com/getkirby/panel/issues/228#issuecomment-58379016
  $.fn.codefield = function() {
    return new CodeField($, this);
  };

})(jQuery);

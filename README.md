# kirby-code-field

![Version](https://img.shields.io/badge/version-0.1.0-green.svg) ![License](https://img.shields.io/badge/license-MIT-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3.2%2B-red.svg)

[Kirby](https://getkirby.com/) plugin adding a code field to the panel. It offers language selection, code highlighting and stores the code without losing indentation (which would be the case when using the default text field).

![Code Field Screenshot](screenshot.png?raw=true)

## Installation

1. [Clone](https://github.com/taikonauten/kirby-code-field.git) or [download](https://github.com/taikonauten/kirby-code-field/archive/master.zip) this repository.
2. Unzip the archive if needed and rename the folder to `field-code`.

**Make sure that the plugin folder structure looks like this:**

```
site/plugins/field-code/
```

3. [Build and download](http://prismjs.com/download.html) the prism.js that fits your needs and save it to `field/assets/js/prism.js`.

## Setup

To make the field appear in the panel interface, add following code to your blueprint:

```yaml
fields:
  yourfield:
    label: Your Field
    type: code
```

You can also specify what languages the user can choose from if they differ from the defaults (see `plugin.fieldcode.defaultlanguages` option).

```yaml
fields:
  yourfield:
    label: Your Field
    type: code
    languages:
      text: Text
      javascript: JavaScript
      html: HTML
```

## Usage

Similar to `kirbytext` use `kirbycode` to render the snippet on your website:

```php
<?= kirbycode($page->yourfield()) ?>
```

This renders following HTML code:

```html
<pre>
  <code class="language-javascript">
    console.log([
      '         (__)',
      '         (oo)',
      '   /------\/ ',
      '  / |    ||  ',
      ' *  /\---/\  ',
      '    ~~   ~~  ',
      '...."Have you mooed today?"...'
    ].join('\n'));
  </code>
</pre>
```

**Note:** This plugin does not handle code highlighting outside the Kirby Panel.

You can also specify a template if it differs from the default setting (see `plugin.fieldcode.template` option):

```php
<?= kirbycode($page->yourfield(), [
  'template' => '<pre>%2$s</pre>'
]) ?>
```

Use the placeholders `%2$s` for the code and `%1$s` for the language.

## Options

There is no configuration needed, this plugin works out of the box.

Following options can be set in your config files:

```php
// default code field languages
c::set('plugin.fieldcode.defaultlanguages', [
  'text'       => 'Plain text (No highlighting)',
  'ruby'       => 'Ruby',
  'elixir'     => 'Elixir',
  'go'         => 'Go',
  'javascript' => 'JavaScript',
  'jsx'        => 'React JSX',
  'json'       => 'JSON',
]);

// default code template
c::set(
  'plugin.fieldcode.template',
  '<pre><code class="language-%s">%s</code></pre>'
);
```



--

'made with ♡ by Taikonauten'

<?php

// register the code field
$kirby->set('field', 'code', __DIR__ . DS . 'field');

function kirbycode($code, $options = []) {
  $code = (string) $code;

  $options = array_merge([
    'template' => c::get(
      'plugin.fieldcode.template',
      '<pre><code class="language-%s">%s</code></pre>'
    ),
  ], $options);

  $language = getCodeFieldLanguageSpecification($code);

  $code = removeCodeFieldLanguageSpecification($code);
  $code = decodeCodeFieldWhitespaces($code);

  return sprintf($options['template'], $language, $code);
}

function encodeCodeFieldWhitespaces($code)
{
  $code = preg_replace('/·/', '¶', $code);
  $code = preg_replace('/\h/', '·', $code);
  return $code;
}

function decodeCodeFieldWhitespaces($code)
{
  $code = preg_replace('/·/', ' ', $code);
  $code = preg_replace('/¶/', '·', $code);
  return $code;
}

function getCodeFieldLanguageSpecification($code)
{
  $matches = [];
  $language = 'text';
  $pattern = '/^\/\*language=([a-zA-Z0-9]+)\*\//';

  if (preg_match($pattern, $code, $matches) === 1) {
    $language = $matches[1];
  }
  return $language;
}

function addCodeFieldLanguageSpecification($code, $language = 'text')
{
  if ($language !== 'text') {
    $code = sprintf('/' . '*language=%s*' . '/', $language) . $code;
  }
  return $code;
}

function removeCodeFieldLanguageSpecification($code)
{
  $language = getCodeFieldLanguageSpecification($code);
  if ($language !== 'text') {
    $code = substr($code, strlen(
      sprintf('/' . '*language=%s*' . '/', $language)));
  }
  return $code;
}

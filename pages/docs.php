<?php
/**
 * @var rex_addon $this
 */

echo rex_view::title(rex_i18n::msg('formslider_title'));

if (is_readable($this->getPath('README.' . rex_i18n::getLanguage() . '.md'))) {
  [$readmeToc, $readmeContent] = rex_markdown::factory()->parseWithToc(rex_file::require($this->getPath('README.' . rex_i18n::getLanguage() . '.md')), 2, 3, [
      rex_markdown::SOFT_LINE_BREAKS => false,
      rex_markdown::HIGHLIGHT_PHP => true,
  ]);
  $fragment = new rex_fragment();
  $fragment->setVar('content', $readmeContent, false);
  $fragment->setVar('toc', $readmeToc, false);
  $content .= $fragment->parse('core/page/docs.php');
} elseif (is_readable($this->getPath('README.md'))) {
  [$readmeToc, $readmeContent] = rex_markdown::factory()->parseWithToc(rex_file::require($this->getPath('README.md')), 2, 3, [
      rex_markdown::SOFT_LINE_BREAKS => false,
      rex_markdown::HIGHLIGHT_PHP => true,
  ]);
  $fragment = new rex_fragment();
  $fragment->setVar('content', $readmeContent, false);
  $fragment->setVar('toc', $readmeToc, false);
  $content .= $fragment->parse('core/page/docs.php');
}

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('formslider_info'));
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
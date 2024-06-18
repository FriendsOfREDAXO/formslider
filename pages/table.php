<?php

echo rex_view::title(rex_i18n::msg('formslider_title'));

$table_name = 'formslider';

$show_list = true;

if ($show_list && rex::getUser()->isAdmin()) {

    $page = rex_request('page', 'string', 'yform/manager/table_edit');

    $context = new rex_context([
        'page' => $page,
    ]);

    // formatting func fuer status col
    function rex_yform_status_col($params)
    {
        $list = $params['list'];
        return 1 == $list->getValue('status') ? '<span class="rex-online"><i class="rex-icon rex-icon-online"></i> ' . rex_i18n::msg('yform_tbl_active') . '</span>' : '<span class="rex-offline"><i class="rex-icon rex-icon-offline"></i> ' . rex_i18n::msg('yform_tbl_inactive') . '</span>';
    }

    function rex_yform_hidden_col($params)
    {
        $list = $params['list'];
        return 1 == $list->getValue('hidden') ? '<span class="text-muted">' . rex_i18n::msg('yform_hidden') . '</span>' : '<span>' . rex_i18n::msg('yform_visible') . '</span>';
    }

    $sql = 'select id, prio, name, table_name, status, hidden, import, export, search, mass_deletion, mass_edit, history  from `' . rex_yform_manager_table::table() . '` where table_name like \'%'.$table_name.'%\'';
    //dump($sql);
    $list = rex_list::factory($sql, 200, defaultSort: [
    'prio' => 'asc',
    'table_name' => 'asc',
    ]);

    $list->addTableAttribute('class', 'table-hover');
    $list->addParam('start', rex_request('start', 'int'));

    $list->setColumnSortable('prio');
    $list->setColumnSortable('name');
    $list->setColumnSortable('table_name');
    $list->setColumnSortable('status');
    $list->setColumnSortable('hidden');

    $tdIcon = '<i class="rex-icon rex-icon-table"></i>';
    $thIcon = '<a class="rex-link-expanded" href="index.php?page=yform/manager/table_edit&func=add"' . rex::getAccesskey(rex_i18n::msg('add'), 'add') . '><i class="rex-icon rex-icon-add"></i></a>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    //$list->setColumnParams($thIcon, ['func' => 'edit', 'table_id' => '###id###']);

    $list->removeColumn('id');
    $list->removeColumn('prio');
    $list->removeColumn('import');
    $list->removeColumn('export');
    $list->removeColumn('search');
    $list->removeColumn('mass_deletion');
    $list->removeColumn('mass_edit');
    $list->removeColumn('history');

    $list->setColumnLabel('name', rex_i18n::msg('yform_manager_name'));
    $list->setColumnFormat('name', 'custom', static function ($params) {
        $name = $params['value'];
        if ($name === $params['list']->getValue('table_name')) {
            $name = 'translate:' . $name;
        }
        $name = rex_i18n::translate($name);
        if (preg_match('/^\[translate:(.*?)\]$/', $name, $match)) {
            $name = $match[1];
        }
        return $name . ' <p><a href="index.php?page=yform/manager/data_edit&table_name=###table_name###"><i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('yform_edit_datatable') . '</a></p>';
    });

    $list->setColumnLabel('table_name', rex_i18n::msg('yform_manager_table_name'));
    $list->setColumnFormat('table_name', 'custom', static function ($params) {
        $name = $params['value'];
        return $name . ' <p><a href="index.php?page=yform/manager/table_edit&func=edit&table_id=###id###&table_name=###table_name###"><i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('yform_manager_edit_table') . '</a></p>';
    });

    $list->setColumnLabel('status', rex_i18n::msg('yform_manager_table_status'));
    $list->setColumnFormat('status', 'custom', 'rex_yform_status_col');

    $list->setColumnLabel('hidden', rex_i18n::msg('yform_manager_table_hidden'));
    $list->setColumnFormat('hidden', 'custom', 'rex_yform_hidden_col');

    $list->addColumn(rex_i18n::msg('yform_editfields'), rex_i18n::msg('yform_editfields'));
    $list->setColumnLayout(rex_i18n::msg('yform_editfields'), ['<th></th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('yform_editfields'), ['page' => 'yform/manager/table_field', 'table_name' => '###table_name###']);

    $content = $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', rex_i18n::msg('yform_table_overview'));
    $fragment->setVar('options', $panel_options, false);
    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');
    echo $content;
}

echo '<div class="alert alert-warning">' . rex_i18n::msg('formslider_table_notice') . '</div>';
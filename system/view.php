<?php
/** Page rendering: runs a view from views/ and wraps it in a layout from views/layouts/. */

/** Renders views/<name>.php with $vars as local variables and returns the output. */
function render_view($__view, array $__vars = []) {
    extract($__vars);
    ob_start();
    require VIEWS_PATH . '/' . $__view . '.php';
    return ob_get_clean();
}

/** Renders page $key inside views/layouts/<layout>.php (default: main). */
function render_page($key, $layout = 'main', $status = 200) {
    load('helper/functions');
    load('helper/seo');
    load('helper/icons');
    $page = site_pages()[$key];
    http_response_code($status);

    // The view can use $key and $page.
    ob_start();
    require VIEWS_PATH . '/' . $page['view'] . '.php';
    $content = ob_get_clean();

    require VIEWS_PATH . '/layouts/' . $layout . '.php';
}

/** Echoes views/components/<name>.php with $vars available as local variables. */
function component($name, array $vars = []) {
    echo render_view('components/' . $name, $vars);
}

/**
 * Component with inline content: everything between component_start() and
 * component_end() is passed to the component as $slot.
 */
function component_start($name, array $vars = []) {
    $GLOBALS['__component_stack'][] = [$name, $vars];
    ob_start();
}

function component_end() {
    [$name, $vars] = array_pop($GLOBALS['__component_stack']);
    $vars['slot'] = ob_get_clean();
    component($name, $vars);
}

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>YosNote - Notebook Manager</title>
    <link rel="stylesheet" href="<?php echo URL; ?>tpl/style.css">
</head>
<body>
<div id="app">
<?php if(isset($notebook['tree'])) { ?><nav id="panel">
    <ul class="actions">
        <li><a href="<?php echo URL; ?>?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=addnote" title="Add a new note inside the current directory"><img src="<?php echo URL; ?>tpl/img/document--plus.png" alt="Add note"></a></li>
        <li><a href="<?php echo URL; ?>?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=adddir" title="Add a new directory inside the current directory"><img src="<?php echo URL; ?>tpl/img/folder--plus.png" alt="Add directory"></a></li>
    </ul>
    <h1><?php echo urldecode($notebookName); ?></h1>
<?php

function Tree2Html($tree, $nbName, $selectedPath, $parents = array()) {
    $level = count($parents);
    $html = str_repeat("\t", $level*2)."<ul";
    if($level == 0) {
        $html .= " id=\"root\" class=\"open\"";
    } else {
        $html .= " class=\"closed\"";
    }
    $html .= ">\r\n";
    
    foreach ($tree as $key => $value) {
        $isArray = is_array($value);
        $isNote = substr($key, -3) == '.md';
        if($isArray || $isNote) {
            //path to element
            $path = '';
            if(!empty($parents)) {
                $path = implode('/', $parents).'/';
            }
            $path = (!empty($parents)?implode('/', $parents).'/':'').$key.($isArray?'/':'');

            $html .= str_repeat("\t", $level*2+1)."<li class=\"".($isArray?"directory":"file").($path == $selectedPath?' selected':'')."\">";
            $html .= '<a href="'.URL.'?nb='.$nbName.'&item='.$path.'">';
            $html .= basename($key, '.md');
            $html .= '</a>';

            //if array, show its children
            if($isArray) {
                $html .= "\r\n";
                $html .= Tree2Html($value, $nbName, $selectedPath, array_merge($parents, (array)$key));
                $html .= str_repeat("\t", $level*2+1);
            }

            $html .= "</li>\r\n";
        }
    }

    $html .= str_repeat("\t", $level*2)."</ul>\r\n";
    return $html;
}

echo Tree2Html($notebook['tree'], $notebookName, $itemPath);

?>
</nav><?php } ?>
<section id="content">

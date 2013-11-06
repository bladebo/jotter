<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>YosNote - Notebook Manager</title>
    <link rel="stylesheet" href="<?php echo URL; ?>tpl/style.css">
<?php if($isNote) { ?>
    <script src="<?php echo URL; ?>tpl/js/ext/jquery-2.0.3.min.js"></script>
    <script src="<?php echo URL; ?>tpl/js/ext/jquery.hotkeys.js"></script>
    <script src="<?php echo URL; ?>tpl/js/ext/bootstrap.min.js"></script>
    <script src="<?php echo URL; ?>tpl/js/ext/bootstrap-wysiwyg.js"></script>
    <script src="<?php echo URL; ?>tpl/js/main.js"></script>
<?php } ?>
</head>
<body>
<div id="app">
<?php if(isset($notebook['tree'])) { ?><nav id="panel">
    <ul class="actions">
        <li><a href="<?php echo URL; ?>?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=addnote" title="Add a new note inside the current directory"><img src="<?php echo URL; ?>tpl/img/document--plus.png" alt="Add note"></a></li>
        <li><a href="<?php echo URL; ?>?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=adddir" title="Add a new directory inside the current directory"><img src="<?php echo URL; ?>tpl/img/folder--plus.png" alt="Add directory"></a></li>
    </ul>
    <h1><a href="?nb=<?php echo $notebookName; ?>"><?php echo urldecode($notebookName); ?></a></h1>
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
            $path = (!empty($parents)?implode('/', $parents).'/':'').$key;

            $html .= str_repeat("\t", $level*2+1)."<li class=\"".($isArray?"directory":"file").($path == $selectedPath?' selected':'')."\">";
            $html .= '<a href="'.URL.'?nb='.$nbName.'&amp;item='.$path.'">';
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

echo Tree2Html($notebook['tree'], $notebookName, isset($_GET['item'])?$_GET['item']:'');

?>
</nav>
<?php } ?>
<section id="content">
    <ul class="actions" id="item-toolbar">
<?php if($isNote || $isDir) { ?>

        <li>
            <a href="?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=edit" title="Edit (rename) this <?php echo $isNote?'note':'directory'; ?>">
                <img src="<?php echo URL; ?>tpl/img/<?php echo $isNote?'document':'folder'; ?>--pencil.png" alt="Edit <?php echo $isNote?'note':'directory'; ?>">
            </a>
        </li>
        <li>
            <a href="?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=delete" title="Delete this <?php echo $isNote?'note':'directory'; ?>">
                <img src="<?php echo URL; ?>tpl/img/<?php echo $isNote?'document':'folder'; ?>--minus.png" alt="Delete <?php echo $isNote?'note':'directory'; ?>">
            </a>
        </li>
<?php if($isNote) { ?>
        <li>
            <a href="?nb=<?php echo $notebookName; ?>&amp;item=<?php echo $itemPath; ?>&amp;action=save" title="Save this note">
                <img src="<?php echo URL; ?>tpl/img/disk-black.png" alt="Save note">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="justifyleft" title="Align left selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-alignment.png" alt="Align left">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="justifycenter" title="Align center selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-alignment-center.png" alt="Align center">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="justifyright" title="Align right selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-alignment-right.png" alt="Align right">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="justifyfull" title="Justify selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-alignment-justify.png" alt="Justify">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="bold" title="Toggle bold for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-bold.png" alt="Bold">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="italic" title="Toggle italic for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-italic.png" alt="Italic">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="underline" title="Toggle underline for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-underline.png" alt="Underline">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="insertUnorderedList" title="Toggle list for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-list.png" alt="List">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="insertOrderedList" title="Toggle ordered list for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-list-order.png" alt="Ordered List">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="formatblock" data-value="blockquote" title="Toggle quotation for selected text">
                <img src="<?php echo URL; ?>tpl/img/edit-quotation.png" alt="Quotation">
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-formatter" data-tag="createLink" title="Insert link on selected text">
                Link
            </a>
        </li>
        <li class="secondary">
            <a href="#" class="ajax-source" title="View source">
                <img src="<?php echo URL; ?>tpl/img/edit-code.png" alt="Source">
            </a>
        </li>
<?php
    } // $isNote
} // $isNote || $isDir
?>

    </ul><?php if($isNote || $isDir) { ?>

    <header class="path"><?php echo $_GET['item']; ?></header><?php } ?>

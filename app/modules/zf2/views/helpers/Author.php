<?php
class Zf2_View_Helper_Author extends Zend_View_Helper_Abstract
{
    public function author($author)
    {
        if (is_string($author)) {
            return $this->view->escape($author);
        }

        if (is_array($author)) {
            $author = (object) $author;
        }

        if (!isset($author->name)) {
            return 'Unknown';
        }

        $string = $this->view->escape($author->name);

        if (isset($author->link)) {
            $string = sprintf(
                '<a href="%s">%s</a>',
                $this->view->escape($author->link),
                $string
            );
        }

        return $string;
    }
}

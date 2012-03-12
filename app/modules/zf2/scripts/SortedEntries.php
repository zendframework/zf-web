<?php
class SortedEntries extends SplPriorityQueue
{
    public function compare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        if (!a < $b) {
            return 1;
        }

        return -1;
    }
}

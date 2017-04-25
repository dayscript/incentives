<?php
function mostRecentModifiedFileTime($dirName,$doRecursive) {
    $d = dir($dirName);
    $lastModified = 0;
    while($entry = $d->read()) {
        if ($entry != "." && $entry != "..") {
            if (!is_dir($dirName."/".$entry)) {
                $currentModified = filemtime($dirName."/".$entry);
            } else if ($doRecursive && is_dir($dirName."/".$entry)) {
                $currentModified = mostRecentModifiedFileTime($dirName."/".$entry,true);
            }
            if ($currentModified > $lastModified){
                $lastModified = $currentModified;
            }
        }
    }
    $d->close();
    return $lastModified;
}
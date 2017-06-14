<?php
foreach ($validator['rules'] as $field => &$rule) {
    if (config('jsvalidation.disable_remote_validation')) {
        unset($rule['laravelValidationRemote']);
    }
}

echo guxy_json_encode($validator['selector']) . ': ' . guxy_json_encode($validator['rules']);

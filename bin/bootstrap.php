<?php
declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

const DEFAULT_PARALLEL_PROCESSES_AMOUNT = 5;
const DEFAULT_PAGES_AMOUNT_TO_DOWNLOAD = 100;
const ARGUMENT_URL = 'url';
const ARGUMENT_MAX_PROCESSES = 'max-processes';
const ARGUMENT_MAX_PAGES = 'max-pages';

$arguments  = array(
    \sprintf('%s:', ARGUMENT_URL),
    \sprintf('%s::', ARGUMENT_MAX_PROCESSES),
    \sprintf('%s::', ARGUMENT_MAX_PAGES),
);
$defaults = [
    ARGUMENT_MAX_PROCESSES => DEFAULT_PARALLEL_PROCESSES_AMOUNT,
    ARGUMENT_MAX_PAGES => DEFAULT_PAGES_AMOUNT_TO_DOWNLOAD,
];
$cliArguments = \array_merge($defaults, \getopt('', $arguments));
$cliArgumentsToPass = '';

foreach ($cliArguments as $argument => $value) {
    $cliArgumentsToPass .= \sprintf(' --%s=%s', $argument, $value);
}

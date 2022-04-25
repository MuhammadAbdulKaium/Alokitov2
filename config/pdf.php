<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4-L',
    'orientation' => 'L',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
    'margin_left' => 5,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 9,
    'margin_footer' => 9,

	'tempDir'               => base_path('../temp/'),
    'font_path' => base_path('storage/fonts/'),
    'font_data' => [
        'bangla' => [
            'R'  => 'SolaimanLipi.ttf',    // regular font
            'B'  => 'SolaimanLipi.ttf',       // optional: bold font
            'I'  => 'SolaimanLipi.ttf',     // optional: italic font
            'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ]
        // ...add as many as you want.
    ]
];

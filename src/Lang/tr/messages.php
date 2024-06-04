<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'audit' => [],

    'constraint' => [
        'question' => [
            'table_selection' => 'Hangi tabloyu denetlemek istiyorsunuz?',
            'continue' => 'Daha fazla kısıtlama eklemek ister misiniz?',
            'constraint_selection' => 'Lütfen eklemek istediğiniz kısıtlamayı seçin.',
            'field_selection' => 'Lütfen kısıtlama eklemek için bir alan seçin',
            'foreign_table' => 'Lütfen yabancı tablo adını ekleyin.',
            'foreign_field' => 'Lütfen yabancı tablonun birincil anahtar adını ekleyin.',
        ],
        'success_message' => [
            'constraint_added' => 'Tebrikler! Kısıtlama Başarıyla Eklendi.'
        ],
        'error_message' => [
            'constraint_not_apply' => 'Uygulanamıyor: kısıtlama anahtarı | Lütfen tabloyu kısaltın.',
            'foreign_not_apply' => 'Sütunlar aynı veri türüne sahip olmalıdır.',
            'table_not_found' => 'Yabancı tablo bulunamadı.',
            'field_not_found' => 'Yabancı alan bulunamadı.',
            'foreign_selected_table_match' => ":selected tablo ve yabancı :yabancı tablo aynı olduğundan kısıtlama eklenemiyor. Lütfen farklı bir tablo adı kullanın.",
            'unique_constraint_not_apply' => "Tüm alan değerleri yineleniyor. Benzersiz kısıtlama ekleyemezsiniz.",
        ]
    ],
    'standard' => [
        'error_message' => [
            'length' => 'Tablo adı 64 karakterden fazla olmamalıdır.',
            'plural' => 'Tablo adı çoğul olmalıdır.',
            'space' => 'Kelimeler arasında boşluk bırakılması tavsiye edilmez. Lütfen Alt Çizgi "_" Kullanın',
            'alphabets' => 'Sayılar isimler için değildir ve tavsiye edilmez! Lütfen isim için alfabe kullanın.',
            'convention' => 'İsim küçük harf, camelCase veya snake_case olmalıdır.',
            'datatype_change' => 'Burada, bir sütundaki veri değerleri aynı uzunluktaysa VARCHAR yerine CHAR veri tipini kullanabilirsiniz.',
        ],
        'question' => [
            'table_selection' => 'Tablo raporunu görmek istiyorsanız lütfen tablo adını girin',
        ]
    ]
];

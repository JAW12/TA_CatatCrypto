<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tdana hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa array.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':attribute harus diantara :min dan :max.',
        'file' => ':attribute harus diantara :min dan :max kilobytes.',
        'string' => ':attribute harus diantara :min dan :max karakter.',
        'array' => ':attribute harus diantara :min dan :max buah.',
    ],
    'boolean' => ':attribute harus berupa benar dan salah',
    'confirmed' => ':attribute konfirmasi tidak sesuai.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus berupa tanggal pada :date.',
    'date_format' => ':attribute tidak sesuai format :format.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus berupa :digits digit.',
    'digits_between' => ':attribute harus diantara :min dan :max digit.',
    'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':attribute memiliki nilai kembar.',
    'email' => ':attribute harus merupakan email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan: :values.',
    'exists' => ':attribute yang dipilih adalah tidak valid.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file' => ':attribute harus lebih besar dari :value kilobytes.',
        'string' => ':attribute harus lebih banyak dari :value karakter.',
        'array' => ':attribute harus lebih banyak dari :value buah.',
    ],
    'gte' => [
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'file' => ':attribute harus lebih besar atau sama dengan :value kilobytes.',
        'string' => ':attribute harus lebih banyak atau sama dengan :value karakter.',
        'array' => ':attribute harus memiliki :value buah atau lebih.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih adalah tidak valid.',
    'in_array' => ':attribute tidak ada di koleksi :other.',
    'integer' => ':attribute harus berupa angka integer.',
    'ip' => ':attribute harus berupa IP address yang valid.',
    'ipv4' => ':attribute harus berupa IPv4 address yang valid.',
    'ipv6' => ':attribute harus berupa IPv6 address yang valid.',
    'json' => ':attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric' => ':attribute harus lebih kecil dari :value.',
        'file' => ':attribute harus lebih kecil dari :value kilobytes.',
        'string' => ':attribute harus lebih sedikit dari :value karakter.',
        'array' => ':attribute harus lebih sedikit dari :value buah.',
    ],
    'lte' => [
        'numeric' => ':attribute harus lebih kecil atau sama dengan :value.',
        'file' => ':attribute harus lebih kecil atau sama dengan :value kilobytes.',
        'string' => ':attribute harus lebih sedikit atau sama dengan :value karakter.',
        'array' => ':attribute tidak boleh lebih dari :value buah.',
    ],
    'max' => [
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'file' => ':attribute tidak boleh lebih besar dari :max kilobytes.',
        'string' => ':attribute tidak boleh lebih banyak dari :max karakter.',
        'array' => ':attribute tidak boleh lebih besar dari :max buah.',
    ],
    'mimes' => ':attribute harus berupa tipe dengan file berikut: :values.',
    'mimetypes' => ':attribute harus berupa tipe dengan file berikut: :values.',
    'min' => [
        'numeric' => ':attribute tidak boleh lebih kecil dari :min.',
        'file' => ':attribute tidak boleh lebih kecil dari :min kilobytes.',
        'string' => ':attribute tidak boleh lebih sedikit dari :min karakter.',
        'array' => ':attribute tidak boleh lebih sedikit dari :min buah.',
    ],
    'multiple_of' => ':attribute harus merupakan nilai majemuk dari :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => 'Kata sandi salah.',
    'present' => ':attribute harus ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute harus diisi.',
    'required_if' => ':attribute harus dipenuhi apabila :other adalah :value.',
    'required_unless' => ':attribute harus dipenuhi kecuali :other adalah :values.',
    'required_with' => ':attribute harus dipenuhi apabila :values memiliki nilai.',
    'required_with_all' => ':attribute harus dipenuhi apabila :values memiliki nilai.',
    'required_without' => ':attribute harus dipenuhi apabila :values tidak memiliki nilai.',
    'required_without_all' => ':attribute field harus dipenuhi apabila semua :values tidak memiliki nilai.',
    'same' => ':attribute dan :other harus sama.',
    'size' => [
        'numeric' => ':attribute harus sejumlah :size.',
        'file' => ':attribute harus memiliki ukuran :size kilobytes.',
        'string' => ':attribute harus berjumlah :size karakter.',
        'array' => ':attribute harus memiliki :size buah.',
    ],
    'starts_with' => ':attribute harus dimulai dengan nilai berikut: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus berupa zona yang valid.',
    'unique' => ':attribute terdapat dalam database.',
    'uploaded' => ':attribute gagal untuk diunggah.',
    'url' => 'Format url :attribute tidak valid.',
    'uuid' => ':attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

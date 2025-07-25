<?php

declare(strict_types=1);

return [
    'dashboard' => 'لوحة التحكم',
    'home' => 'الرئيسية',
    'logout' => 'تسجيل الخروج',
    'profile' => [
        'name' => 'الملف الشخصي',
        'description' => 'تعديل تفاصيل حسابك وكلمة المرور.',
        'personal_info' => [
            'title' => 'المعلومات الشخصية',
            'description' => 'تعديل معلوماتك الشخصية والبريد الإلكتروني.',
            'placeholder' => [
                'name' => 'أدخل اسمك',
                'email' => 'أدخل بريدك الإلكتروني',
            ],
            'toast' => [
                'updated' => 'تم تحديث ملفك الشخصي بنجاح!',
            ],
        ],
        'password' => [
            'title' => 'كلمة المرور',
            'placeholder' => [
                'current_password' => 'أدخل كلمة المرور الحالية',
                'new_password' => 'أدخل كلمة المرور الجديدة',
                'new_password_confirmation' => 'تأكيد كلمة المرور الجديدة',
            ],
            'button' => 'تغيير كلمة المرور',
            'toast' => [
                'updated' => 'تم تحديث كلمة المرور.',
            ],
        ],
        'delete_account' => [
            'title' => 'حذف الحساب',
            'description' => 'منطقة الخطر',
            'button' => [
                'text' => 'حذف حسابي',
                'description' => 'سيتم حذف جميع بياناتك فورًا. هذا الإجراء لا يمكن التراجع عنه، لذا تصرف بحذر.',
            ],
            'model' => [
                'title' => 'حذف الحساب',
                'description' => 'هل أنت متأكد من أنك تريد حذف حسابك؟ بمجرد حذفه، سيتم مسح جميع بياناتك نهائيًا. يرجى إدخال كلمة المرور لتأكيد هذا الإجراء.',
            ],
            'toast' => [
                'deleted' => 'تم حذف حسابك.',
            ],
        ],
    ],
    'authentication' => [
        'login' => [
            'name' => 'تسجيل الدخول',
            'title' => 'تسجيل الدخول إلى حساب :appName',
            'description' => 'الوصول إلى المنتجات الرقمية عبر :appName.',
            'submit' => 'تسجيل الدخول',
            'forgot_your_password' => 'هل نسيت كلمة المرور؟',
        ],
        'forgot_password' => 'نسيت كلمة المرور',
        'confirm_password' => 'تأكيد كلمة المرور',
    ],
    'resources' => [
        'admin' => [
            'plural' => 'المشرفون',
            'singular' => 'مشرف',
            'attributes' => [
                'name' => 'الاسم',
                'phone' => 'الهاتف',
                'role' => 'الوظيفة',
            ],
            'enums' => [
                'role' => [
                    'admin' => 'مشرف',
                    'super_admin' => 'مشرف عام',
                ],
            ],
        ],
        'user' => [
            'plural' => 'المستخدمون',
            'singular' => 'مستخدم',
            'attributes' => [
                'name' => 'الاسم',
                'phone' => 'الهاتف',
                'birth_date' => 'تاريخ الميلاد',
            ],
        ],
    ],
    'global' => [
        'id' => 'ID',
        'count' => [
            'name' => 'عدد',
            'resource' => 'عدد :resource',
        ],
        'created_at' => 'تاريخ الانشاء',
        'updated_at' => 'تاريخ التحديث',
        'deleted_at' => 'تاريخ الحذف',
        'attributes' => [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'current_password' => 'كلمة المرور الحالية',
            'password' => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
            'new_password' => 'كلمة المرور الجديدة',
            'new_password_confirmation' => 'تأكيد كلمة المرور الجديدة',
            'payment_type' => 'نوع الدفع',
            'payment_method' => 'طريقة الدفع',
            'employment_type' => 'نوع العمل',
            'media' => 'الوسائط',
        ],
        'placeholder' => 'أدخل :attribute',
        'example' => 'مثال: :example',
        'loading' => 'جار التحميل',
        'select_all' => 'اختر الكل',
        'choose' => 'إختر :attribute',
        'download' => 'تحميل',
        'search' => [
            'name' => 'بحث',
            'resource' => 'بحث عن :resource',
            'global' => [
                'label' => 'بحث عالمي',
                'resources' => [
                    'admins' => 'المشرفون',
                    'users' => 'المستخدمون',
                ],
            ],
        ],
        'not_found' => [
            'results' => 'لم يتم العثور على نتائج',
            'resource' => 'لم يتم العثور على :resource',
            'description' => 'يرجى تجربة مصطلح بحث مختلف أو إنشاء جديد.',
        ],
        'crud' => [
            'edit' => 'تعديل',
            'update' => 'تحديث',
            'delete' => 'حذف',
            'show' => 'عرض',
            'create' => 'إنشاء',
            'save' => 'حفظ',
        ],
        'closure' => [
            'cancel' => 'إلغاء',
            'close' => 'إغلاق',
            'to_close' => 'للإغلاق',
        ],
        'confirmation' => [
            'yes' => 'نعم',
            'no' => 'لا',
        ],
        'resource' => [
            'show' => 'عرض :resource',
            'create' => 'إنشاء :resource',
            'edit' => 'تعديل :resource',
            'delete' => 'حذف :resource',
        ],
        'actions' => [
            'created' => 'تم إنشاء :resource!',
            'updated' => 'تم تحديث :resource!',
            'deleted' => 'تم حذف :resource!',
            'restored' => 'تم استعادة :resource!',
        ],
        'options' => [
            'delete' => [
                'delete_resource' => 'حذف المورد',
                'soft_deleted' => 'حذف بشكل مؤقت',
                'force_delete' => 'الحذف النهائي',
                'force_delete_resource' => 'الحذف النهائي للمورد',
                'force_delete_selected' => 'الحذف النهائي للمختار',
                'delete_selected' => 'حذف المختار',
            ],
            'restore' => [
                'restore_resource' => 'استعادة المورد',
                'restore' => 'استعادة',
                'restore_selected' => 'استعادة المختار',
            ],
            'detach' => [
                'detach_resource' => 'فصل المورد',
                'detach' => 'فصل',
                'detach_selected' => 'فصل المختار',
            ],
        ],
        'gender' => 'الجنس',
        'day' => 'يوم',
        'weekend' => 'نهاية الأسبوع',
        'attendance_methods' => 'طرق الحضور',
        'enums' => [
            'gender' => [
                'male' => 'ذكر',
                'female' => 'أنثى',
            ],
            'day' => [
                'saturday' => 'السبت',
                'sunday' => 'الأحد',
                'monday' => 'الاثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
            ],
        ],
        'file_upload' => [
            'single_file_only' => 'يمكن تحميل ملف واحد فقط في كل مرة',
            'max_files_exceeded' => 'الحد الأقصى لعدد الملفات المسموح بها :count',
            'file_too_large' => 'ملف :name يتجاوز الحجم الأقصى :size ميجابايت',
            'invalid_file_type' => 'نوع الملف :type غير مسموح به',
            'drop_here_or' => 'أسقط ملفك هنا',
            'browse' => 'تصفح',
            'max_size' => 'اختر ملفًا بحجم :size ميجابايت',
            'allowed_types' => 'الأنواع المسموح بها: :types',
            'upload_failed' => 'فشل تحميل الملف',
        ],
    ],
    'import_and_export' => 'استيراد / تصدير',
    'export' => [
        'label' => 'تصدير',
        'resource' => 'تصدير :resource',
        'model' => [
            'title' => 'اختر تصدير الصفحة الحالية أو جميع السجلات المتوفرة بتنسيق .XLSX',
            'current_page' => 'الصفحة الحالية',
            'all_resource' => 'كل :resource',
            'confirm' => 'تأكيد التصدير',
        ],
        'success' => 'سيتم إرسال بريد إلكتروني إلى حسابك بالملف بمجرد أن يصبح التصدير جاهزًا.',
    ],
    'import' => [
        'label' => 'استيراد',
        'resource' => 'استيراد :resource',
        'model' => [
            'example' => [
                'first' => 'قم بتنزيل نموذج CSV',
                'second' => 'لرؤية التنسيق المطلوب.',
            ],
            'confirm' => 'تأكيد الاستيراد',
        ],
    ],
    'models' => [
        'delete' => [
            'title' => 'هل أنت متأكد أنك تريد حذف :resource هذا؟',
            'description' => 'يمكنك التراجع عن هذا الإجراء لاحقًا بحذف هذا المورد.',
        ],
        'delete_all' => [
            'title' => 'هل أنت متأكد أنك تريد حذف جميع :resource المحددة؟',
            'description' => 'يمكنك التراجع عن هذا الإجراء لاحقًا بحذف الموارد المحددة.',
        ],
    ],
    'pagination' => [
        'showing' => 'عرض',
        'page' => 'الصفحة',
        'next' => 'التالي',
        'previous' => 'السابق',
        'of' => 'من',
    ],
    'filters' => [
        'name' => 'تصفية',
        'apply' => 'تطبيق',
        'reset' => 'إعادة ضبط',
        'clear' => 'مسح',
        'trashed' => [
            'name' => 'المحذوفات',
            'with' => 'مع المحذوفات',
            'only' => 'فقط المحذوفات',
        ],
    ],
    'navigation' => [
        'go_home' => 'الذهاب إلى الرئيسية',
        'go_to_link' => 'الذهاب للرابط',
    ],
    'photo' => 'صورة',
    'upload_photo' => 'تحميل صورة',
    'upload' => 'تحميل',
    'browse' => 'تصفح',
    'errors' => [
        'error' => 'خطأ',
        'error_happened' => 'حدث خطأ، يرجى المحاولة لاحقًا.',
    ],
    'all_rights_reserved' => 'جميع الحقوق محفوظة',
    'faq' => 'الأسئلة الشائعة',
    'license' => 'الترخيص',
    'back_to_home' => 'العودة للرئيسية',
];

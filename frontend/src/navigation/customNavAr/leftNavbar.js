export default [
    /* {
      header: 'Apps & Pages',
    }, */
    {
        title: 'لوحة القيادة',
        route: 'dashboard-ecommerce',
        icon: 'HomeIcon',
        action: 'view',
        resource: 'navbar',
    },
    {
        title: 'محفظتى',
        route: 'my-wallets',
        action: 'view',
        icon: 'BriefcaseIcon',
        resource: 'my_wallets',
    },
    {
        title: 'الموارد البشرية',
        icon: 'FileTextIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'الموظفين',
                route: 'Employee-sheet',
                action: 'view',
                resource: 'employees',
            },
            {
                title: 'إدارة الوثائق',
                // icon: 'FileTextIcon',
                // action: 'view',
                // resource: 'navbar',
                route: 'list-docs',
                action: 'view',
                resource: 'document',

            },
        ],
    },
    {
        title: 'مركز الموارد',
        icon: 'ToolIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'مصدر جديد',
                route: 'allocate-resource',
                action: 'add/copy',
                resource: 'resource',
            },
            {
                title: 'قائمة الموارد',
                route: 'allocated-resources',
                action: 'view',
                resource: 'resource',
            },

        ],
    },

    {
        title: 'عمليات',
        icon: 'SettingsIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'إقامة',
                route: 'accommodation-table',
                action: 'view',
                resource: 'accommodation',
            },
            {
                title: 'عرض مدفوعات الإيجار',
                route: 'view-general-accommodation-payments',
                action: 'view',
                resource: 'rent_payment',
            },
            {
                title: 'دفع الإيجار الجديد',
                route: 'create-accommodation-payment',
                action: 'add/copy',
                resource: 'rent_payment',
            },
            {
                title: 'مشاهدة دفع الفواتير',
                route: 'view-general-bill-payments',
                action: 'view',
                resource: 'bill_payment',
            },
            {
                title: 'دفع الفواتير الجديدة',
                route: 'create-bill-payment',
                action: 'add/copy',
                resource: 'bill_payment',
            },
        ],
    },

    {
        title: 'مبيعات',
        icon: 'BarChartIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
            title: 'العملاء',
            route: 'list-clients',
            action: 'view',
            resource: 'client',
        }, ],
    },

    {
        title: 'حسابات',
        icon: 'BookIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'الفواتير',
                route: 'list-invoices',
                action: 'view',
                resource: 'invoice',
            },
            {
                title: 'طلب الدفع',
                // icon: 'ClipboardIcon',
                // action: 'view',
                // resource: 'navbar',
                route: 'list-payment',
                action: 'view',
                resource: 'payment',
            },
            {
                title: 'محافظ',
                route: 'list-wallets',
                action: 'view',
                resource: 'wallets',
            },
            {
                title: 'حساب البنك',
                route: 'list-bank-account',
                action: 'view',
                resource: 'bank_account',
            },
        ],
    },
    {
        title: 'Expenses Accounts',
        icon: 'BookIcon',
        action: 'view',
        resource: 'expense_accounts',
        route: 'expense_accounts',


    },
    {
        title: 'نفقات',
        icon: 'ShoppingCartIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'إنشاء حساب',
                route: 'save-purchase',
                action: 'add/copy',
                resource: 'purchase_order',
            },
            {
                title: 'قائمة المصاريف',
                route: 'purchase-list',
                action: 'view',
                resource: 'purchase_order',
            },
            {
                title: 'الباعة',
                route: 'vendors-list',
                action: 'view',
                resource: 'vendor',
            },
            {
                title: 'الموردين',
                // icon: 'UsersIcon',
                // action: 'view',
                // resource: 'navbar',
                route: 'list-supplier',
                action: 'view',
                resource: 'supplier',
            },
        ],
    },
    // {
    //   title: 'Document Management',
    //   icon: 'FileTextIcon',
    //   // action: 'view',
    //   // resource: 'navbar',
    //   route: 'list-docs',
    //   action: 'view',
    //   resource: 'document',

    // },
    // {
    //   title: 'Payment Request',
    //   icon: 'ClipboardIcon',
    //   // action: 'view',
    //   // resource: 'navbar',
    //   route: 'list-payment',
    //   action: 'view',
    //   resource: 'payment',
    // },

    // {
    //   title: 'Suppliers',
    //   icon: 'UsersIcon',
    //   // action: 'view',
    //   // resource: 'navbar',
    //   route: 'list-supplier',
    //   action: 'view',
    //   resource: 'supplier',
    // },
    {
        title: 'الإبلاغ',
        icon: 'BookIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
            title: 'Product Stats',
            route: 'product-stats',
            action: 'view',
            resource: 'vendor',
        }, ],
    },
    {
        title: 'التقارير',
        icon: 'FileTextIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
            title: '-',
            route: 'N/A',
        }, ],
    },

    {
        title: 'إعدادات',
        icon: 'SlidersIcon',
        action: 'view',
        resource: 'navbar',
        children: [{
                title: 'الاعدادات العامة',
                route: 'global-terms',
                action: 'view',
                resource: 'user_level',
            },
            {
                title: 'المستخدمون',
                route: 'system-users',
                action: 'view',
                resource: 'user',
            },
            {
                title: 'أنواع الموردين',
                route: 'supplier-types',
                // action: 'view',
                // resource: 'navbar',
                action: 'view',
                resource: 'supplier_type',
            },
            {
                title: 'مستويات المستخدم',
                route: 'roles',
                action: 'view',
                resource: 'user_level',
            },


            {
                title: 'سجل تدقيق',
                route: 'audit-trail',
                action: 'view',
                resource: 'user',
            },
            {
                title: 'مالك البنوك',
                route: 'owner-banks',
                action: 'view',
                resource: 'my_bank_account',
            },

            {
                title: 'إعدادات الموارد',
                children: [{
                        title: 'الموظف',
                        route: 'employee-items',
                        action: 'view',
                        resource: 'resource_item',
                    },
                    {
                        title: 'مشروع',
                        route: 'project-items',
                        action: 'view',
                        resource: 'resource_item',
                    },
                    {
                        title: 'إقامة',
                        route: 'accommodation-items',
                        action: 'view',
                        resource: 'resource_item',
                    },
                ],
            },
            {
                title: 'إعدادات الفاتورة',
                children: [{
                    title: 'Services List',
                    route: 'list-expence-items',
                    action: 'view',
                    resource: 'resource_item',
                }, ],
            },
        ],
    },

]
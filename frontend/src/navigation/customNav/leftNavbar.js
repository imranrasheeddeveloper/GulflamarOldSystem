export default [
    /* {
      header: 'Apps & Pages',
    }, */
    {
        title: "Dashboard",
        route: "dashboard-ecommerce",
        icon: "HomeIcon",
        action: "view",
        resource: "navbar"
    },
    {
        title: "My Wallet",
        route: "my-wallets",
        action: "view",
        icon: "BriefcaseIcon",
        resource: "my_wallets"
    },
    {
        title: "HR",
        icon: "FileTextIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Employees",
                route: "Employee-sheet",
                action: "view",
                resource: "employees"
            },
            {
                title: "Document Management",
                // icon: 'FileTextIcon',
                // action: 'view',
                // resource: 'navbar',
                route: "list-docs",
                action: "view",
                resource: "document"
            }
        ]
    },
    {
        title: "Resource Center",
        icon: "ToolIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "New Resource",
                route: "allocate-resource",
                action: "add/copy",
                resource: "resource"
            },
            {
                title: "List Resources",
                route: "allocated-resources",
                action: "view",
                resource: "resource"
            }
        ]
    },

    {
        title: "Operations",
        icon: "SettingsIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Real Estate",
                route: "accommodation-table",
                action: "view",
                resource: "accommodation"
            },
            {
                title: "View Rent Payments",
                route: "view-general-accommodation-payments",
                action: "view",
                resource: "rent_payment"
            },
            {
                title: "New Rent Payment",
                route: "create-accommodation-payment",
                action: "add/copy",
                resource: "rent_payment"
            },
            {
                title: "View Bill Payment",
                route: "view-general-bill-payments",
                action: "view",
                resource: "bill_payment"
            },
            {
                title: "New Bill Payment",
                route: "create-bill-payment",
                action: "add/copy",
                resource: "bill_payment"
            }
        ]
    },

    {
        title: "Sales",
        icon: "BarChartIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Clients",
                route: "list-clients",
                action: "view",
                resource: "client"
            }
        ]
    },

    {
        title: "Accounts",
        icon: "BookIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Invoices",
                route: "list-invoices",
                action: "view",
                resource: "invoice"
            },
            {
           title: "Expenses Accounts",
           icon: "BookIcon",
           action: "view",
           resource: "expense_accounts",
           route: "expense_accounts"
           },
            {
                title: "Wallets",
                route: "list-wallets",
                action: "view",
                resource: "wallets"
            },
            {
                title: "Ledger",
                route: "list-bank-account",
                action: "view",
                resource: "bank_account"
            }
        ]
    },
    
    {
        title: "Call Center",
        icon: "PhoneCallIcon",
        action: "view",
        resource: "call_center",
        route: "call-center"
    },

    {
        title: "Purchasing",
        icon: "ShoppingCartIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Create Expense",
                route: "save-purchase",
                action: "add/copy",
                resource: "purchase_order"
            },
            {
                title: "Payment Request",
                route: "list-payment",
                action: "view",
                resource: "payment"
            },
            {
                title: "Expense List",
                route: "purchase-list",
                action: "view",
                resource: "purchase_order"
            },
            {
                title: "Vendors",
                route: "vendors-list",
                action: "view",
                resource: "vendor"
            },
            {
                title: "Suppliers",
                // icon: 'UsersIcon',
                // action: 'view',
                // resource: 'navbar',
                route: "list-supplier",
                action: "view",
                resource: "supplier"
            }
        ]
    },
    {
        title: "Reporting",
        icon: "BookIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Product Stats",
                route: "product-stats",
                action: "view",
                resource: "vendor"
            }
        ]
    },
    {
        title: "Reports",
        icon: "FileTextIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "-",
                route: "N/A"
            }
        ]
    },
    {
        title: "Assets",
        icon: "ShoppingCartIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Assets Managment",
                route: "assets_list",
                action: "view",
                resource: "assets_managments"
            },
            {
                title: "Asset Work Order",
                route: "list_assets_work_order",
                action: "view",
                resource: "asset_work_orders"
            },
            {
                title: "Asset Maintenance",
                route: "list_asset_maintenance",
                action: "view",
                resource: "asset_maintenances"
            }
        ]
    },

    {
        title: "Settings",
        icon: "SlidersIcon",
        action: "view",
        resource: "navbar",
        children: [
            {
                title: "Global Settings",
                route: "global-terms",
                action: "view",
                resource: "user_level"
            },
            {
                title: "Users",
                route: "system-users",
                action: "view",
                resource: "user"
            },
            {
                title: "Supplier Types",
                route: "supplier-types",
                // action: 'view',
                // resource: 'navbar',
                action: "view",
                resource: "supplier_type"
            },
            {
                title: "User Levels",
                route: "roles",
                action: "view",
                resource: "user_level"
            },

            {
                title: "Audit Trail",
                route: "audit-trail",
                action: "view",
                resource: "user"
            },
            {
                title: "Owner Banks",
                route: "owner-banks",
                action: "view",
                resource: "my_bank_account"
            },

            {
                title: "Resource  Settings",
                children: [
                    {
                        title: "Employee",
                        route: "employee-items",
                        action: "view",
                        resource: "resource_item"
                    },
                    {
                        title: "Project",
                        route: "project-items",
                        action: "view",
                        resource: "resource_item"
                    },
                    {
                        title: "Accommodation",
                        route: "accommodation-items",
                        action: "view",
                        resource: "resource_item"
                    }
                ]
            },
            {
                title: "Invoice  Settings",
                children: [
                    {
                        title: "Services List",
                        route: "list-expence-items",
                        action: "view",
                        resource: "resource_item"
                    }
                ]
            },

            {
                title: "Asset Settings",
                children: [
                    {
                        title: "Type",
                        route: "assetTypeList",
                        action: "view",
                        resource: "navbar"
                    },
                    {
                        title: "Make",
                        route: "assetMakeList",
                        action: "view",
                        resource: "navbar"
                    },
                    {
                        title: "Model",
                        route: "assetModelList",
                        action: "view",
                        resource: "navbar"
                    },
                    {
                        title: "Capacity",
                        route: "assetCapacityList",
                        action: "view",
                        resource: "navbar"
                    },

                ]
            },
        ]
    }
];
///////////////////////////////

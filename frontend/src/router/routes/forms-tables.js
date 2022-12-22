export default [
    {
        path: "/userLevel",
        name: "roles",
        component: () => import("@/views/table/bs-table/userLevel.vue"),
        meta: {
            pageTitle: "User Levels",
            breadcrumb: [
                {
                    text: "Table"
                },
                {
                    text: "User roles",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/userLevel",
        name: "create-userLevel",
        component: () =>
            import("@/views/table/bs-table/userLevel/createUserLevel.vue"),
        meta: {
            pageTitle: "User Levels",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "User Level",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/userLevel/:id",
        name: "edit-userLevel",
        component: () =>
            import("@/views/table/bs-table/userLevel/editUserLevel.vue"),
        meta: {
            pageTitle: "User Levels",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "User Level",
                    active: true
                }
            ]
        }
    },
    {
        path: "/save_purchase",
        name: "save-purchase",
        component: () =>
            import(
                "@/views/table/bs-table/purchasingCenter/createPurchase.vue"
            ),
        meta: {
            pageTitle: "Expense",
            breadcrumb: [
                {
                    text: "Expenses"
                },
                {
                    text: "create",
                    active: true
                }
            ]
        }
    },
    {
        path: "/purchase_list",
        name: "purchase-list",
        component: () =>
            import("@/views/table/bs-table/purchasingCenter/purchaseTable.vue"),
        meta: {
            pageTitle: "Expense",
            breadcrumb: [
                {
                    text: "Expenses"
                },
                {
                    text: "List",
                    active: true
                }
            ]
        }
    },
    {
        path: "/product-stats",
        name: "product-stats",
        component: () =>
            import("@/views/table/bs-table/productstats/productStatsTable.vue"),
        meta: {
            pageTitle: "Product Stats",
            breadcrumb: [
                {
                    text: "Product Stats"
                },
                {
                    text: "List",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/purchase/:id",
        name: "edit-purchase",
        component: () =>
            import("@/views/table/bs-table/purchasingCenter/purchaseEdit.vue"),
        meta: {
            pageTitle: "Expense",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Expense",
                    active: true
                }
            ]
        }
    },
    {
        path: "/employee/items",
        name: "employee-items",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/employeeItemsTable.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Employees"
                },
                {
                    text: "Items",
                    active: true
                }
            ]
        }
    },
    {
        path: "/add/employee/items",
        name: "add-emp-items",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/addEmployeeItems.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Employees"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/item/:id/:resourceType",
        name: "resource-item-edit",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/editResourceItem.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/project/items",
        name: "project-items",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/projectItemsTable.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Project"
                },
                {
                    text: "Items",
                    active: true
                }
            ]
        }
    },
    {
        path: "/add/project/items",
        name: "add-project-items",
        component: () =>
            import("@/views/table/bs-table/ResourceCenter/addProjectItems.vue"),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Projects"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/accommodation/items",
        name: "accommodation-items",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/accommodationItemsTable.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Real Estate"
                },
                {
                    text: "Items",
                    active: true
                }
            ]
        }
    },
    {
        path: "/add/accommodation/items",
        name: "add-accommodation-items",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/addAccommodationItems.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Real Estate"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/allocated_resources",
        name: "allocated-resources",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/allocatedResourceTable.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Allocated"
                },
                {
                    text: "Resources",
                    active: true
                }
            ]
        }
    },
    {
        path: "/allocate_resource",
        name: "allocate-resource",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/allocateResource.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Allocate"
                },
                {
                    text: "Resource",
                    active: true
                }
            ]
        }
    },

    {
        path: "/edit/allocated_resource/:id",
        name: "allocated-resource-edit",
        component: () =>
            import(
                "@/views/table/bs-table/ResourceCenter/allocatedResourceEdit.vue"
            ),
        meta: {
            pageTitle: "Resource Center",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Resource",
                    active: true
                }
            ]
        }
    },
    {
        path: "/employee",
        name: "Employee-sheet",
        component: () => import("@/views/table/bs-table/employeeTable.vue"),
        meta: {
            pageTitle: "Employees",
            breadcrumb: [
                {
                    text: "Table"
                },
                {
                    text: "Employees",
                    active: true
                }
            ]
        }
    },
    {
        path: "/add/employee",
        name: "add-employee",
        component: () =>
            import("@/views/table/bs-table/employee/addEmployee.vue"),
        meta: {
            pageTitle: "Employees",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Employee",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/employee/:empId",
        name: "Employee-edit",
        component: () =>
            import("@/views/table/bs-table/employee/employeeEdit.vue"),
        meta: {
            pageTitle: "Employees",
            breadcrumb: [
                {
                    text: "Table"
                },
                {
                    text: "Employees",
                    active: true
                }
            ]
        }
    },
    {
        path: "/accommodations",
        name: "accommodation-table",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/accommodationTable.vue"
            ),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "Table"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/addAccommodations",
        name: "create-accommodation",
        component: () =>
            import("@/views/table/bs-table/accommodation/addAccommodation.vue"),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "Add"
                },
                {
                    text: "Real Estate",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/accommodation/:id",
        name: "edit-accommodation",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/accommodationEdit.vue"
            ),
        meta: {
            pageTitle: "Real Estate",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Real Estate",
                    active: true
                }
            ]
        }
    },
    {
        path: "/accommodationPayment",
        name: "create-accommodation-payment",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/addAccommodationPayment.vue"
            ),
        meta: {
            pageTitle: "Payments",
            breadcrumb: [
                {
                    text: "Add"
                },
                {
                    text: "Payment",
                    active: true
                }
            ]
        }
    },
    {
        path: "/view/accommodation/payments/:id",
        name: "view-accommodation-payments",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/rentPaymentsTable.vue"
            ),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "View"
                },
                {
                    text: "Rent Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/view/accommodation/payments",
        name: "view-general-accommodation-payments",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/rentPaymentsTable.vue"
            ),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "View"
                },
                {
                    text: "Rent Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/accommodation/payments/:id",
        name: "edit-accommodation-payments",
        component: () =>
            import("@/views/table/bs-table/accommodation/rentPaymentsEdit.vue"),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Rent Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/accommodationBillPayment",
        name: "create-bill-payment",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/addAccommodationBillPayment.vue"
            ),
        meta: {
            pageTitle: "Bills",
            breadcrumb: [
                {
                    text: "Add"
                },
                {
                    text: "Payment",
                    active: true
                }
            ]
        }
    },
    {
        path: "/view/accommodation/bills/:id",
        name: "view-bill-payments",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/billPaymentsTable.vue"
            ),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "View"
                },
                {
                    text: "Bill Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/view/accommodation/bills",
        name: "view-general-bill-payments",
        component: () =>
            import(
                "@/views/table/bs-table/accommodation/billPaymentsTable.vue"
            ),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "View"
                },
                {
                    text: "Bill Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/accommodation/bills/payments/:id",
        name: "edit-accommodation-bills-payments",
        component: () =>
            import("@/views/table/bs-table/accommodation/billPaymentsEdit.vue"),
        meta: {
            pageTitle: "Accommodations",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Bill Payments",
                    active: true
                }
            ]
        }
    },
    {
        path: "/vendors",
        name: "vendors-list",
        component: () =>
            import("@/views/table/bs-table/vendors/vendorTable.vue"),
        meta: {
            pageTitle: "Vendors",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/vendor",
        name: "create-new-vendor",
        component: () => import("@/views/table/bs-table/vendors/addVendor.vue"),
        meta: {
            pageTitle: "Vendors",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Vendor",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/vendor/:id",
        name: "edit-vendor",
        component: () =>
            import("@/views/table/bs-table/vendors/editVendor.vue"),
        meta: {
            pageTitle: "Vendors",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Vendor",
                    active: true
                }
            ]
        }
    },
    {
        path: "/clients",
        name: "list-clients",
        component: () =>
            import("@/views/table/bs-table/client/clientTable.vue"),
        meta: {
            pageTitle: "Clients",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/document_management",
        name: "list-docs",
        component: () =>
            import(
                "@/views/table/bs-table/documentManagement/documentTableView.vue"
            ),
        meta: {
            pageTitle: "Document Management",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/document",
        name: "create-new-document",
        component: () =>
            import(
                "@/views/table/bs-table/documentManagement/addDocumentView.vue"
            ),
        meta: {
            pageTitle: "Document Management",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Document",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/document/:id",
        name: "edit-document",
        component: () =>
            import(
                "@/views/table/bs-table/documentManagement/editDocumentView.vue"
            ),
        meta: {
            pageTitle: "Document Management",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Document",
                    active: true
                }
            ]
        }
    },

    {
        path: "/bank_accounts",
        name: "list-bank-account",
        component: () =>
            import(
                "@/views/table/bs-table/bankAccount/listBankAccountView.vue"
            ),
        meta: {
            pageTitle: "Ledger",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/new-bank-account",
        name: "create-new-bank-account",
        component: () =>
            import("@/views/table/bs-table/bankAccount/addBankAccountView.vue"),
        meta: {
            pageTitle: "Ledger",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Ledger",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/bank-account/:id",
        name: "edit-bank-account",
        component: () =>
            import("@/views/table/bs-table/bankAccount/ediBankAccountView.vue"),
        meta: {
            pageTitle: "Bank Account Transactions",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Transaction",
                    active: true
                }
            ]
        }
    },

    {
        path: "/bank_accounts_transactions/:id",
        name: "list-bank-account-transactions",
        component: () =>
            import(
                "@/views/table/bs-table/bankAccount/bankAccountTransactionsTableView.vue"
            ),
        meta: {
            pageTitle: "Bank Account Transactions",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/new-bank-account-transaction/:id",
        name: "create-new-bank-account-transaction",
        component: () =>
            import(
                "@/views/table/bs-table/bankAccount/addBankTransactionView.vue"
            ),
        meta: {
            pageTitle: "Bank Account Transactions",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Transaction",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/bank-account-transaction/:id",
        name: "edit-bank-account-transaction",
        component: () =>
            import(
                "@/views/table/bs-table/bankAccount/editBankTransactionView.vue"
            ),
        meta: {
            pageTitle: "Bank Account Transactions",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Transaction",
                    active: true
                }
            ]
        }
    },
    {
        path: "/ownerBanks",
        name: "owner-banks",
        component: () =>
            import("@/views/table/bs-table/ownerBanks/ownerbanksTableView.vue"),
        meta: {
            pageTitle: "Owner Banks",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/ownerBank",
        name: "create-new-owner-banks",
        component: () =>
            import("@/views/table/bs-table/ownerBanks/addownerBank.vue"),
        meta: {
            pageTitle: "Owner Banks",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Owner Bank",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/ownerBank/:id",
        name: "edit-owner-banks",
        component: () =>
            import("@/views/table/bs-table/ownerBanks/editownerBank.vue"),
        meta: {
            pageTitle: "Owner Banks",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Owner Bank",
                    active: true
                }
            ]
        }
    },

    {
        path: "/List_Payment_Request",
        name: "list-payment",
        component: () =>
            import(
                "@/views/table/bs-table/paymentRequest/listPaymentRequestView.vue"
            ),
        meta: {
            pageTitle: "Payment Requests",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/payment_request",
        name: "create-new-payment-request",
        component: () =>
            import(
                "@/views/table/bs-table/paymentRequest/addPaymentRequestView.vue"
            ),
        meta: {
            pageTitle: "Payment Request",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "payment",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/payment_request/:id",
        name: "edit_payment_request",
        component: () =>
            import(
                "@/views/table/bs-table/paymentRequest/editPaymentRequestView.vue"
            ),
        meta: {
            pageTitle: "Payment Request",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Payment",
                    active: true
                }
            ]
        }
    },
    {
        path: "/suppliers",
        name: "list-supplier",
        component: () =>
            import("@/views/table/bs-table/supplier/supplierTableView.vue"),
        meta: {
            pageTitle: "Suppliers",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/supplier",
        name: "create-new-supplier",
        component: () =>
            import("@/views/table/bs-table/supplier/addSupplierView.vue"),
        meta: {
            pageTitle: "Suppliers",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Supplier",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/supplier/:id",
        name: "edit-supplier",
        component: () =>
            import("@/views/table/bs-table/supplier/editSupplierView.vue"),
        meta: {
            pageTitle: "Suppliers",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Supplier",
                    active: true
                }
            ]
        }
    },
    {
        path: "/List_Patty_Cash/:id",
        name: "list-patty-cash",
        component: () =>
            import("@/views/table/bs-table/pattyCash/listPattyCashView.vue"),
        meta: {
            pageTitle: "Petty Cash",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/patty_cash/:id",
        name: "create-new-patty-cash",
        component: () =>
            import("@/views/table/bs-table/pattyCash/addPattyCashView.vue"),
        meta: {
            pageTitle: "Petty Cash",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Petty Cash",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/patty_cash/:id",
        name: "edit-patty-cash",
        component: () =>
            import("@/views/table/bs-table/pattyCash/editPattyCashView.vue"),
        meta: {
            pageTitle: "Petty Cash",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Petty Cash",
                    active: true
                }
            ]
        }
    },
    {
        path: "/wallets",
        name: "list-wallets",
        component: () =>
            import("@/views/table/bs-table/wallets/listWalletView.vue"),
        meta: {
            pageTitle: "Wallets",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/my_wallets",
        name: "my-wallets",
        component: () =>
            import("@/views/table/bs-table/my_wallets/listWalletView.vue"),
        meta: {
            pageTitle: "My Wallets",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/my_wallet",
        name: "create-my-wallet",
        component: () =>
            import("@/views/table/bs-table/my_wallets/addWalletView.vue"),
        meta: {
            pageTitle: "My Wallets",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "My Wallet",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/my_wallet/:id",
        name: "edit-my_wallet",
        component: () =>
            import("@/views/table/bs-table/my_wallets/editWalletView.vue"),
        meta: {
            pageTitle: "My Wallets",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Wallet",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/wallet",
        name: "create-wallet",
        component: () =>
            import("@/views/table/bs-table/wallets/addWalletView.vue"),
        meta: {
            pageTitle: "Wallets",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Wallet",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/wallet/:id",
        name: "edit-wallet",
        component: () =>
            import("@/views/table/bs-table/wallets/editWalletView.vue"),
        meta: {
            pageTitle: "Wallets",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Wallet",
                    active: true
                }
            ]
        }
    },
    {
        path: "/My_List_Patty_Cash/:id",
        name: "my-list-patty-cash",
        component: () =>
            import("@/views/table/bs-table/my_wallets/listPattyCashView.vue"),
        meta: {
            pageTitle: "MY Petty Cash",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/my_patty_cash/:id",
        name: "create-my-new-patty-cash",
        component: () =>
            import("@/views/table/bs-table/my_wallets/addPattyCashView.vue"),
        meta: {
            pageTitle: "My Petty Cash",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "My Petty Cash",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/my_patty_cash/:id",
        name: "edit-my-patty-cash",
        component: () =>
            import("@/views/table/bs-table/my_wallets/editPattyCashView.vue"),
        meta: {
            pageTitle: "My Petty Cash",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "My Petty Cash",
                    active: true
                }
            ]
        }
    },
    {
        path: "/expense_accounts",
        name: "expense_accounts",
        component: () =>
            import(
                "@/views/table/bs-table/expense_accounts/listWalletView.vue"
            ),
        meta: {
            pageTitle: "Expense Accounts",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/expense_account",
        name: "create-expense-account",
        component: () =>
            import("@/views/table/bs-table/expense_accounts/addWalletView.vue"),
        meta: {
            pageTitle: "Expense Accounts",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "New Expense Account",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/expense_account/:id",
        name: "edit_my_expense_account",
        component: () =>
            import(
                "@/views/table/bs-table/expense_accounts/editWalletView.vue"
            ),
        meta: {
            pageTitle: "Expense Accounts",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Expense Account",
                    active: true
                }
            ]
        }
    },
    {
        path: "/ExpenseAccountSummary/:id",
        name: "expense-account-summary",
        component: () =>
            import(
                "@/views/table/bs-table/expense_accounts/supplierTypeTableView.vue"
            ),
        meta: {
            pageTitle: "Expense Account Summary",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/AddExpenseEntryView/:id",
        name: "add-expense-entry-view",
        component: () =>
            import(
                "@/views/table/bs-table/expense_accounts/addExpenseEntryView.vue"
            ),
        meta: {
            pageTitle: "Add Expense Entry",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/ExpenseCategoryDetail/:id/:category?",
        name: "expense-category-detail",
        component: () =>
            import(
                "@/views/table/bs-table/expense_accounts/listPattyCashView.vue"
            ),
        meta: {
            pageTitle: "Expense Category Detail",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/Call_Center",
        name: "call-center",
        component: () =>
            import("@/views/table/bs-table/callCenter/listCallCenter.vue"),
        meta: {
            pageTitle: "Call Center",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/call",
        name: "create-call",
        component: () =>
            import("@/views/table/bs-table/callCenter/addCallCenter.vue"),
        meta: {
            pageTitle: "Call Center",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "call",
                    active: true
                }
            ]
        }
    },
    {
        path: "/Edit_call/:id",
        name: "edit-call",
        component: () =>
            import("@/views/table/bs-table/callCenter/editCallCenter.vue"),
        meta: {
            pageTitle: "Call Center",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "call",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/client",
        name: "create-new-client",
        component: () => import("@/views/table/bs-table/client/addClient.vue"),
        meta: {
            pageTitle: "Clients",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Client",
                    active: true
                }
            ]
        }
    },

    {
        path: "/edit/client/:client_code",
        name: "edit-client",
        component: () => import("@/views/table/bs-table/client/editClient.vue"),
        meta: {
            pageTitle: "Clients",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Client",
                    active: true
                }
            ]
        }
    },
    {
        path: "/invoices",
        name: "list-invoices",
        component: () =>
            import("@/views/table/bs-table/invoice/invoiceTable.vue"),
        meta: {
            pageTitle: "Invoices",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/invoice",
        name: "create-new-invoice",
        component: () =>
            import("@/views/table/bs-table/invoice/addInvoice.vue"),
        meta: {
            pageTitle: "Invoices",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Invoice",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/invoice/:invoice_number",
        name: "edit-invoice",
        component: () =>
            import("@/views/table/bs-table/invoice/editInvoice.vue"),
        meta: {
            pageTitle: "Invoices",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Invoice",
                    active: true
                }
            ]
        }
    },
    {
        path: "/expence/items",
        name: "list-expence-items",
        component: () =>
            import("@/views/table/bs-table/invoice/expenceItems.vue"),
        meta: {
            pageTitle: "Expence Items",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/expence/items/edit/:id",
        name: "expence-item-edit",
        component: () =>
            import("@/views/table/bs-table/invoice/editExpenceItems.vue"),
        meta: {
            pageTitle: "Expence Items",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/expence/items/creat",
        name: "expence-item-create",
        component: () =>
            import("@/views/table/bs-table/invoice/addExpenceItems.vue"),
        meta: {
            pageTitle: "Expence Items",
            breadcrumb: [
                {
                    text: "Add"
                },
                {
                    text: "Item",
                    active: true
                }
            ]
        }
    },
    {
        path: "/users",
        name: "system-users",
        component: () => import("@/views/table/bs-table/user/userTable.vue"),
        meta: {
            pageTitle: "Users",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/SupplierTypes",
        name: "supplier-types",
        component: () =>
            import("@/views/table/bs-table/supplier/supplierTypeTableView.vue"),
        meta: {
            pageTitle: "Supplier Types",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "View",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/supplierType",
        name: "create-new-supplier-type",
        component: () =>
            import("@/views/table/bs-table/supplier/addSupplierType.vue"),
        meta: {
            pageTitle: "Supplier Type",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "supplier Type",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/supplierType/:id",
        name: "edit-supplierType",
        component: () =>
            import("@/views/table/bs-table/supplier/editSupplierTypeView.vue"),
        meta: {
            pageTitle: "Supplier Type",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "supplierType",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create/user",
        name: "create-new-user",
        component: () => import("@/views/table/bs-table/user/addUser.vue"),
        meta: {
            pageTitle: "Users",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "User",
                    active: true
                }
            ]
        }
    },
    {
        path: "/edit/user/:id",
        name: "edit-user",
        component: () => import("@/views/table/bs-table/user/editUser.vue"),
        meta: {
            pageTitle: "Users",
            breadcrumb: [
                {
                    text: "Edit"
                },
                {
                    text: "User",
                    active: true
                }
            ]
        }
    },
    {
        path: "/update/profile",
        name: "update-profile",
        component: () =>
            import("@/views/table/bs-table/profile/updateProfile.vue"),
        meta: {
            pageTitle: "User",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Profile",
                    active: true
                }
            ]
        }
    },
    {
        path: "/audit_trail",
        name: "audit-trail",
        component: () => import("@/views/table/bs-table/audit/auditTable.vue"),
        meta: {
            pageTitle: "Audit Trail",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "Activity",
                    active: true
                }
            ]
        }
    },
    {
        path: "/set/global_terms",
        name: "global-terms",
        component: () =>
            import("@/views/table/bs-table/global/globalterms.vue"),
        meta: {
            pageTitle: "Settings",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Global Terms",
                    active: true
                }
            ]
        }
    },
    {
        path: "/error-404",
        name: "error-404",
        component: () => import("@/views/error/Error404.vue"),
        meta: {
            layout: "full",
            resource: "Auth",
            action: "read"
        }
    },
    {
        path: "/pages/miscellaneous/not-authorized",
        name: "misc-not-authorized",
        component: () =>
            import("@/views/pages/miscellaneous/NotAuthorized.vue"),
        meta: {
            layout: "full",
            resource: "Auth"
        }
    },
    {
        path: "/login",
        name: "auth-login",
        component: () => import("@/views/pages/authentication/Login.vue"),
        meta: {
            layout: "full",
            resource: "Auth",
            redirectIfLoggedIn: true
        }
    },
    {
        path: "/create/assets",
        name: "create-new-assets",
        component: () =>
            import("@/views/table/bs-table/assets/create_assets.vue"),
        meta: {
            pageTitle: "Clients",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/assets",
        name: "assets_list",
        component: () =>
            import("@/views/table/bs-table/assets/assets_list.vue"),
        meta: {
            pageTitle: "Assets",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/addAssets",
        name: "add_assets",
        component: () =>
            import("@/views/table/bs-table/assets/create_assets.vue"),
        meta: {
            pageTitle: "Assets",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/updateAsset/:id",
        name: "update_asset",
        component: () =>
            import("@/views/table/bs-table/assets/update_asset.vue"),
        meta: {
            pageTitle: "Assets",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create_work_order",
        name: "create_work_order",
        component: () =>
            import("@/views/table/bs-table/assets_work_order/crreate_assets_work_order.vue"),
        meta: {
            pageTitle: "Asset Work Order",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/list_assets_work_order",
        name: "list_assets_work_order",
        component: () =>
            import("@/views/table/bs-table/assets_work_order/list_assets_work_order.vue"),
        meta: {
            pageTitle: "Asset Work Order",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/update_assets_work_order/:id",
        name: "update_assets_work_order",
        component: () =>
            import("@/views/table/bs-table/assets_work_order/update_assets_work_order.vue"),
        meta: {
            pageTitle: "Asset Work Order",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/list_asset_maintenance",
        name: "list_asset_maintenance",
        component: () =>
            import("@/views/table/bs-table/assetMaintenance/list_assetMaintenance.vue"),
        meta: {
            pageTitle: "Asset Maintenance",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/create_asset_maintenance",
        name: "create_asset_maintenance",
        component: () =>
            import("@/views/table/bs-table/assetMaintenance/crreate_assetMaintenance.vue"),
        meta: {
            pageTitle: "Asset Maintenance",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/update_assetMaintenance/:id",
        name: "update_assetMaintenance",
        component: () =>
            import("@/views/table/bs-table/assetMaintenance/update_assetMaintenance.vue"),
        meta: {
            pageTitle: "Asset Maintenance",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets",
                    active: true
                }
            ]
        }
    },
    {
        path: "/addAssetType",
        name: "addAssetType",
        component: () =>
            import("@/views/table/bs-table/AssetsTypeSetting/addAssetType.vue"),
        meta: {
            pageTitle: "Asset Type",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets Type",
                    active: true
                }
            ]
        }
    },
    {
        path: "/assetTypeList",
        name: "assetTypeList",
        component: () =>
            import("@/views/table/bs-table/AssetsTypeSetting/assetTypeList.vue"),
        meta: {
            pageTitle: "Asset Type",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets Type",
                    active: true
                }
            ]
        }
    },
    {
        path: "/editAssetType/:id",
        name: "editAssetType",
        component: () =>
            import("@/views/table/bs-table/AssetsTypeSetting/editAssetType.vue"),
        meta: {
            pageTitle: "Asset Type",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets Type",
                    active: true
                }
            ]
        }
    },

    {
        path: "/addAssetCapacity",
        name: "addAssetCapacity",
        component: () =>
            import("@/views/table/bs-table/AssetCapacity/addAssetCapacity.vue"),
        meta: {
            pageTitle: "Asset Capacity",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets Capacity",
                    active: true
                }
            ]
        }
    },
    {
        path: "/assetCapacityList",
        name: "assetCapacityList",
        component: () =>
            import("@/views/table/bs-table/AssetCapacity/assetCapacityList.vue"),
        meta: {
            pageTitle: "Asset Type",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Capacity",
                    active: true
                }
            ]
        }
    },
    {
        path: "/editAssetCapacity/:id",
        name: "editAssetCapacity",
        component: () =>
            import("@/views/table/bs-table/AssetCapacity/editAssetCapacity.vue"),
        meta: {
            pageTitle: "Asset Capacity",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets Capacity",
                    active: true
                }
            ]
        }
    },

    {
        path: "/addAssetMake",
        name: "addAssetMake",
        component: () =>
            import("@/views/table/bs-table/AssetMakeSetting/addAssetMake.vue"),
        meta: {
            pageTitle: "Asset Make",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets Capacity",
                    active: true
                }
            ]
        }
    },
    {
        path: "/assetMakeList",
        name: "assetMakeList",
        component: () =>
            import("@/views/table/bs-table/AssetMakeSetting/assetMakeList.vue"),
        meta: {
            pageTitle: "Asset Make",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Make",
                    active: true
                }
            ]
        }
    },
    {
        path: "/editAssetMake/:id",
        name: "editAssetMake",
        component: () =>
            import("@/views/table/bs-table/AssetMakeSetting/editAssetMake.vue"),
        meta: {
            pageTitle: "Asset Make",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets Make",
                    active: true
                }
            ]
        }
    },


    {
        path: "/addAssetModel",
        name: "addAssetModel",
        component: () =>
            import("@/views/table/bs-table/AssetModelSetting/addAssetModel.vue"),
        meta: {
            pageTitle: "Asset Model",
            breadcrumb: [
                {
                    text: "Create"
                },
                {
                    text: "Assets Model",
                    active: true
                }
            ]
        }
    },
    {
        path: "/assetModelList",
        name: "assetModelList",
        component: () =>
            import("@/views/table/bs-table/AssetModelSetting/assetModelList.vue"),
        meta: {
            pageTitle: "Asset Model",
            breadcrumb: [
                {
                    text: "List"
                },
                {
                    text: "Model",
                    active: true
                }
            ]
        }
    },
    {
        path: "/editAssetModel/:id",
        name: "editAssetModel",
        component: () =>
            import("@/views/table/bs-table/AssetModelSetting/editAssetModel.vue"),
        meta: {
            pageTitle: "Asset Model",
            breadcrumb: [
                {
                    text: "Update"
                },
                {
                    text: "Assets Model",
                    active: true
                }
            ]
        }
    },


    
];

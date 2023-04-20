<?php

return [
    'brands' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'string',
            'sort' => false
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
    ],
    'categories' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
       
        'icon' => [
            'name' => 'Icon',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'parent' => [
            'name' => 'Parent Name',
            'search' => 'string',
            'type' => 'String',
            'sort' => true
        ],
        'slug' => [
            'name' => 'Slug',
            'search' => 'string',
            'type' => 'String',
            'sort' => true
        ],
    ],
    'countries' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'flag' => [
            'name' => 'Flag',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'iso_code_1' => [
            'name' => 'ISO code one',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'iso_code_2' => [
            'name' => 'ISO code two',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'phone_code' => [
            'name' => 'Phone code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
       
    ],
    'currencies' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'symbol' => [
            'name' => 'Symbol',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'rate' => [
            'name' => 'Rate',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
       
    ],
    'discounts' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'start_date' => [
            'name' => 'Start Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => true
        ],
        'end_date' => [
            'name' => 'Start Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => true
        ],
        'discount_percentage' => [
            'name' => 'Discount Percentage',
            'search' => '',
            'type' => 'date',
            'sort' => true
        ],
    ],
    'fields' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'title' => [
            'name' => 'Title',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'type' => [
            'name' => 'Type',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'entity' => [
            'name' => 'Entity',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'labels' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'title' => [
            'name' => 'Title',
            'search' => 'string',
            'type' => 'string',
            'sort' => true

        ],
        'entity' => [
            'name' => 'Entity',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'color' => [
            'name' => 'Color',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
  
        'key' => [
            'name' => 'Key',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'languages' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
       
    ],
    'prices' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'currency' => [
            'name' => 'Currency',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'original_price' => [
            'name' => 'Original Price',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'original_percent' => [
            'name' => 'Original Percentage',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'products' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'sku' => [
            'name' => 'Sku',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'type' => [
            'name' => 'Type',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'quantity' => [
            'name' => 'Quantity',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
     
        'website_status' => [
            'name' => 'Status',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        // 'stock' => [
        //     'name' => 'Stock',
        //     'search' => 'string',
        //     'type' => 'string',
        //     'sort' => true
        // ],

        'categories' => [
            'name' => 'category',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],

        'tags' => [
            'name' => 'Tags',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'brands' => [
            'name' => 'Brands',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],


    ],
    'products_select_product' => [
        'id' => [
            'key' => 'id',
            'name' => 'ID',
            'search' => 'string',
            'type' => 'string',
            'sort' => true,
            'is_visible' => false,
            'is_show' => true
        ],
        'image' => [
            'key' => 'image',
            'name' => 'Image',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => true,
            'is_show' => true
        ],
        'name' => [
            'key' => 'name',
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true,
            'is_visible' => true,
            'is_show' => true
        ],
        'quantity' => [
            'key' => 'quantity',
            'name' => 'Quantity',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => false,
            'is_show' => true
        ],
        'tax' => [
            'key' => 'tax',
            'name' => 'Tax',
            'search' => 'double',
            'type' => 'double',
            'sort' => true,
            'is_visible' => false,
            'is_show' => true
        ],
        'original_tax' => [
            'key' => 'original_tax',
            'name' => 'original tax',
            'search' => 'double',
            'type' => 'double',
            'sort' => true,
            'is_visible' => false,
            'is_show' => true
        ],
        'sku' => [
            'key' => 'sku',
            'name' => 'Sku',
            'search' => 'string',
            'type' => 'double',
            'sort' => true,
            'is_visible' => false,
            'is_show' => true
        ],
        'unit_price' => [
            'key' => 'unit_price',
            'name' => 'Cost Per Unit',
            'search' => '',
            'type' => 'double',
            'sort' => false,
            'is_visible' => true,
            'is_show' => true
        ],
        'original_unit_price' => [
            'key' => 'original_unit_price',
            'name' => 'original unit price',
            'search' => '',
            'type' => 'double',
            'sort' => false,
            'is_visible' => false,
            'is_show' => false
        ],
        'currency_symbol' => [
            'key' => 'currency_symbol',
            'name' => 'Currency Symbol',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => false,
            'is_show' => true
        ],
        'quantity_in_stock' => [
            'key' => 'quantity_in_stock',
            'name' => 'Quantity in stock',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => true,
            'is_show' => true
        ],
        'edit_status' => [
            'key' => 'edit_status',
            'name' => 'Edit Status',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => false,
            'is_show' => false
        ],
        'type' => [
            'key' => 'type',
            'name' => 'Type',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => false,
            'is_show' => false
        ],
        'pre_order' => [
            'key' => 'pre_order',
            'name' => 'Pre Order',
            'search' => '',
            'type' => 'string',
            'sort' => false,
            'is_visible' => false,
            'is_show' => false
        ],

    ],
    'roles' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'parent_role' => [
            'name' => 'Parent Role',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ]
    ],
    'settings' => [
        'key' => [
            'key' => 'key',
            'name' => '#',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'title' => [
            'key' => 'title',
            'name' => 'Variable Name',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'name' => [
            'key' => 'name',
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'type' => [
            'key' => 'type',
            'name' => 'Type',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'options' => [
            'key' => 'options',
            'name' => 'Options',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'value' => [
            'key' => 'value',
            'name' => 'Value',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
    ],
    'tags' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'taxes' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'percentage' => [
            'name' => 'Percentage',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'complex_behavior' => [
            'name' => 'Complex Behavior',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],


    ],
    'units' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],

    ],
    'users' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'username' => [
            'name' => 'User Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'email' => [
            'name' => 'Email',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'first_name' => [
            'name' => 'First Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'last_name' => [
            'name' => 'Last Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'role_name' => [
            'name' => 'Role Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ]
    ],
    'prices_list' => [
        'code' => [
            'is_show' => true,
            'name' => 'Code',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'item' => [
            'is_show' => true,
            'name' => 'Item',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'UOM' => [
            'is_show' => true,
            'name' => 'UOM',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'orders' => [
        'id' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'customer_first_name' => [
            'name' => 'Customer First Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'customer_last_name' => [
            'name' => 'Customer Last Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'time' => [
            'name' => 'Time',
            'search' => 'time',
            'type' => 'time',
            'sort' => false
        ],
        'date' => [
            'name' => 'Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => false
        ],
        'total' => [
            'name' => 'Total',
            'search' => 'double',
            'type' => 'double',
            'sort' => false
        ]
    ],
    'coupons' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'title' => [
            'name' => 'Title',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'start_date' => [
            'name' => 'Start Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => false
        ],
        'expiry_date' => [
            'name' => 'Expiry Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => false
        ],
        'discount_percentage' => [
            'name' => 'Discounted Percentage',
            'search' => 'float',
            'type' => 'float',
            'sort' => false
        ],
        'discount_amount' => [
            'name' => 'Discounted Amount',
            'search' => 'float',
            'type' => 'float',
            'sort' => false
        ],
        'min_amount' => [
            'name' => 'Minimum Amount',
            'search' => 'float',
            'type' => 'float',
            'sort' => false
        ],
    ],




];

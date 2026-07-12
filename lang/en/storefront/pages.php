<?php

return [

    'about' => [
        'title' => 'About Us',
        'description' => 'Get to know :site, the online store curating quality products at the best prices',
        'heading' => 'About :site',
        'stats' => [
            'products' => ['value' => '50+', 'label' => 'Curated Quality Products'],
            'categories' => ['value' => '10', 'label' => 'Complete Categories'],
            'support' => ['value' => '24 hrs', 'label' => 'Customer Support'],
        ],
        'paragraph1' => ':site was founded with the intention of making online shopping easy, safe, and worthwhile. We curate quality products across a wide range of categories, from fashion and electronics to home goods, backed by fast shipping and secure payment.',
        'paragraph2' => 'This project is a portfolio demo — built with Laravel 12, Tailwind CSS, Alpine.js, and PostgreSQL (Supabase) following Clean Architecture principles, ready to be extended into a production system.',
    ],

    'contact' => [
        'title' => 'Contact Us',
        'description' => 'Contact channels for the ShopSmart team, available every day',
        'heading' => 'Contact Us',
        'subheading' => 'Our team is ready to help and answer all your questions',
        'phone_label' => 'Phone',
        'phone_hours' => 'Every day 09:00 – 21:00',
        'email_label' => 'Email',
        'email_response' => 'We respond within 24 hours',
        'address_label' => 'Address',
        'faq_heading' => 'Frequently Asked Questions',
        'faq' => [
            'order' => [
                'q' => 'How do I place an order?',
                'a' => 'Choose the products you want, add them to your cart, then follow the checkout steps (this is a demo system — no real charges are made).',
            ],
            'shipping' => [
                'q' => 'How long does shipping take?',
                'a' => 'Typically 1-3 business days for Bangkok, and 3-5 business days for other provinces.',
            ],
            'returns' => [
                'q' => 'Can I return a product?',
                'a' => 'Yes, free returns are available within 7 days of receiving your order if the product has an issue or does not match what you ordered.',
            ],
        ],
    ],

];

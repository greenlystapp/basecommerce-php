module.exports = {
    title: 'BaseCommerce PHP SDK',
    description: 'Unofficial PHP SDK for BaseCommerce Payments',
    head: [
        [
            'link',
            {
                href:
                    'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i',
                rel: 'stylesheet',
                type: 'text/css',
            },
        ],
        [
            'link',
            {
                href:
                    '/css/custom.css',
                rel: 'stylesheet',
                type: 'text/css',
            },
        ],
    ],
    themeConfig: {
        logo: '/logo_wide.jpg',
        siteTitle: false,
        displayAllHeaders: true,
        sidebarDepth: 1,
        nav: [
            {text: 'Github', link: 'https://github.com/greenlystapp/basecommerce-php'},
            {text: 'SDK Support', link: 'https://github.com/greenlystapp/basecommerce-php/issues'},
            {text: 'Base Commerce', link: 'https://confluence.basecommerce.net/bctd'},
            {text: 'Base Commerce Support', link: 'https://www.basesdksupport.com'},
        ],

        sidebar: {
            '/1.0/': require('./1.0')
        },
    },
    plugins: [
        ['@vuepress/last-updated'],
    ]
}

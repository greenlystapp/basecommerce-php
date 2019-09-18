module.exports = [
    {
        title: 'Guide',
        collapsable: false,
        children: ['introduction'],
    },
    {
        title: 'Using the SDK',
        collapsable: false,
        children: prefix('sdk', [
            'client',
            'card',
            'bank-account',
            'transactions',
            'webhooks',
            'errors',
        ]),
    },
    {
        title: 'API Reference',
        collapsable: false,
        children: prefix('api', [
            'client',
            'card'
        ])
    }
];

function prefix(prefix, children) {
    return children.map(child => `${prefix}/${child}`)
}

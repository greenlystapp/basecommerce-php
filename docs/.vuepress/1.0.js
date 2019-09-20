module.exports = [
    {
        title: 'Guide',
        collapsable: false,
        children: ['introduction'],
    },
    {
        title: 'Using the SDK',
        collapsable: true,
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
        collapsable: true,
        children: prefix('api', [
            'client',
            'card',
            'transaction',
            'recurring'
        ])
    }
];

function prefix(prefix, children) {
    return children.map(child => `${prefix}/${child}`)
}

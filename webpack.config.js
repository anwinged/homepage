const path = require('path');


module.exports = income_env => {

    const env = income_env || {};
    const is_prod = !!env.production;
    const dist_dir = is_prod ? 'output_prod' : 'output_dev';

    return {
        mode: is_prod ? 'production' : 'development',
        entry: {
            layout_default: './source/_assets/layout_default/style.scss',
            about_me: './source/_assets/about_me/index.js',
        },
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, `${dist_dir}/static`),
        },
        module: {
            rules: [
                {
                    test: /\.scss$/,
                    use: ['style-loader', 'css-loader', 'sass-loader'],
                }
            ]
        }
    }
};

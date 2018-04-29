const path = require('path');


module.exports = income_env => {

    const env = income_env || {};
    const is_prod = !!env.production;
    const dist_dir = is_prod ? 'output_prod' : 'output_dev';

    return {
        mode: is_prod ? 'production' : 'development',
        entry: {
            layout_default: './source/_assets/layout_default/style.scss',
            layout_internal: './source/_assets/layout_internal/style.scss',
            about_me: './source/_assets/about_me/index.js',
            // wishlist: './source/_assets/wishlist/index.js',
        },
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, `${dist_dir}/static`),
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['babel-preset-env']
                        }
                    },

                },
                {
                    test: /\.scss$/,
                    use: ['style-loader', 'css-loader', 'sass-loader'],
                }
            ]
        }
    }
};

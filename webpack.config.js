const path = require('path');
const autoprefixer = require('autoprefixer');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = (env = {}) => {
    const is_prod = !!env.production;
    const dist_dir = is_prod ? './output_prod' : './output_dev';

    const STYLE_LOADER = { loader: 'style-loader' };

    const CSS_LOADER = {
        loader: 'css-loader',
        options: {
            importLoaders: true,
        },
    };

    const POSTCSS_LOADER = {
        loader: 'postcss-loader',
        options: {
            plugins: [
                autoprefixer({
                    browsers: ['last 8 version'],
                }),
            ],
        },
    };

    const SCSS_LOADER = { loader: 'sass-loader' };

    const BABEL_LOADER = {
        loader: 'babel-loader',
        options: {
            presets: ['babel-preset-env'],
        },
    };

    const VUE_LOADER = {
        loader: 'vue-loader',
        options: {
            loaders: {
                css: ['vue-style-loader', CSS_LOADER, POSTCSS_LOADER],
                scss: [
                    'vue-style-loader',
                    CSS_LOADER,
                    POSTCSS_LOADER,
                    'sass-loader',
                ],
            },
        },
    };

    return {
        mode: is_prod ? 'production' : 'development',
        entry: {
            layout_default: './source/_assets/layout_default/style.scss',
            layout_internal: './source/_assets/layout_internal/style.scss',
            about_me: './source/_assets/about_me/index.js',
            predictor: './source/_assets/predictor/index.js',
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
                    use: [BABEL_LOADER],
                },
                {
                    test: /\.css$/,
                    use: [STYLE_LOADER, CSS_LOADER, POSTCSS_LOADER],
                },
                {
                    test: /\.scss$/,
                    use: [
                        STYLE_LOADER,
                        CSS_LOADER,
                        POSTCSS_LOADER,
                        SCSS_LOADER,
                    ],
                },
                {
                    test: /\.vue$/,
                    use: [VUE_LOADER],
                },
            ],
        },
        plugins: [new VueLoaderPlugin()],
    };
};

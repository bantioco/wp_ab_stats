// webpack v4

const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: { 
        main: './assets/src/js/wpabstats.js' 
    },
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: 'wpabstats.pack.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract(
                {
                    fallback: 'style-loader',
                    use: ['css-loader', 'sass-loader']
                })
            }
        ]
    },
    plugins: [ 
        new ExtractTextPlugin({
            filename: 'wpabstats.css'
        })
    ]
};
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  mode: "production",
  entry: './src/index.js', module: {
    rules: [{
      test: /\.s[ac]ss$/i,
      use: [MiniCssExtractPlugin.loader, {loader: "css-loader", options: {sourceMap: true}}, {
        loader: "sass-loader",
        options: {sourceMap: true}
      },]
    }]
  }, plugins: [
    new MiniCssExtractPlugin({
      filename: 'build.min.css',
    }),], optimization: {
    minimizer: [new CssMinimizerPlugin({
      minimizerOptions: {
        preset: ["default", {discardComments: {removeAll: true}}]
      },
    }),]
  }, performance: {hints: false, maxEntrypointSize: 512000, maxAssetSize: 512000}
};
